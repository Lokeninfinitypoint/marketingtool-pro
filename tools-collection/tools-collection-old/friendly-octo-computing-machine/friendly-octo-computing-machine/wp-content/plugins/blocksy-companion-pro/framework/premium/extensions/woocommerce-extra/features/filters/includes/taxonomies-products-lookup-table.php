<?php

namespace Blocksy\Extensions\WoocommerceExtra;

use Automattic\WooCommerce\Internal\Utilities\DatabaseUtil;

class FiltersTaxonomiesProductsLookupTable {
	private $lookup_table_name = '';

	private $state_option_name = 'blocksy_taxonomy_lookup_regeneration_state';
	private $scheduled_action_name = 'blocksy_run_product_taxonomy_lookup_regeneration_callback';

	private $store = null;

	private $triggered_from_init = false;

	public function __construct() {
		global $wpdb;
		$this->lookup_table_name = $wpdb->prefix . 'blocksy_product_taxonomies_lookup';

		$this->store = new FiltersTaxonomiesProductsLookupStore($this->lookup_table_name);

		add_filter(
			'woocommerce_debug_tools',
			[$this, 'add_initiate_regeneration_entry_to_tools_array'],
			5
		);

		add_action(
			'init',
			function () {
				$state = $this->get_regeneration_state();

				// If the state is healthy, skip checking the existence of the
				// lookup table to not perform an extra SQL query.
				if ($state['state'] === 'idle' && $state['enabled']) {
					return;
				}

				if (
					! $this->check_lookup_table_exists()
					||
					(
						! $this->lookup_table_has_data()
						&&
						$state['state'] === 'idle'
					)
				) {
					$this->initiate_regeneration('on_init');
					$this->triggered_from_init = true;
				}
			}
		);

		// TODO: introduce action after update is done
		add_action(
			'blocksy:cache-manager:purge-all',
			function () {
				if (! $this->check_lookup_table_exists()) {
					return;
				}

				if ($this->triggered_from_init) {
					return;
				}

				$state = $this->get_regeneration_state();

				if ($state['state'] === 'idle' && ! $state['enabled']) {
					$this->initiate_regeneration(
						'on_cache_purge:state_was_disabled'
					);

					return;
				}

				if ($state['state'] === 'progress') {
					return;
				}

				if (
					$this->lookup_table_has_data()
					||
					! $this->get_last_existing_product_id()
				) {
					$this->finalize_regeneration([
						'outcome' => $this->can_use_lookup_table(),
						'reason' => 'blocksy:cache-manager:purge-all'
					]);
				} else {
					$this->initiate_regeneration('on_cache_purge');
				}
			}
		);

		add_action(
			$this->scheduled_action_name,
			[$this, 'run_regeneration_step_callback']
		);

		add_filter('blocksy_cli_tools', [$this, 'register_cli_tools']);

		// Allow regeneration via action
		add_action(
			'blocksy:pro:woo-extra:filters:lookup-table:regenerate',
			[$this, 'regenerate_inline']
		);
	}

	public function get_table_name() {
		return $this->lookup_table_name;
	}

	public function can_use_lookup_table() {
		$state = $this->get_regeneration_state();

		return (
			$state['state'] === 'idle'
			&&
			$state['enabled']
		);
	}

	public function check_lookup_table_exists() {
		global $wpdb;

		$query = $wpdb->prepare(
			'SHOW TABLES LIKE %s',
			$wpdb->esc_like($this->lookup_table_name)
		);

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
		return $this->lookup_table_name === $wpdb->get_var($query);
	}

	public function get_regeneration_state() {
		return get_option($this->state_option_name, [
			// Maybe introduce version here in order to be able to handle
			// changes in the state structure in the future.

			// 1. default
			// 2. progress
			// 3. aborted
			// 4. idle
			'state' => 'default',

			// Relevant only when state is 'idle':
			// 'enabled' => false,

			// Relevant only when state is 'progress':
			// 'processed_count' => 0,
			// 'total_count' => 0,
			// 'last_product_id' => 0,
		]);
	}

	public function set_regeneration_state($state) {
		$previous_state = $this->get_regeneration_state();

		update_option($this->state_option_name, $state);

		wc_get_logger()->debug(
			'FiltersTaxonomiesProductsLookupTable:set_regeneration_state',
			[
				'source' => 'taxonomies_lookup_table',
				'previous_state' => $previous_state,
				'state' => $state
			]
		);
	}

	public function initiate_regeneration($reason = 'default') {
		$state = $this->get_regeneration_state();

		wc_get_logger()->info(
			'FiltersTaxonomiesProductsLookupTable:start_regeneration:before',
			[
				'source' => 'taxonomies_lookup_table',
				'state' => $state,
				'reason' => $reason,
				'triggered_from_init' => $this->triggered_from_init
			]
		);

		$queue = WC()->get_instance_of(\WC_Queue::class);
		$queue->cancel_all($this->scheduled_action_name);

		$this->delete_all_taxonomies_lookup_data();

		$products_exist = $this->initialize_table_and_data();

		if ($products_exist) {
			$this->enqueue_regeneration_step_run();
		} else {
			$this->finalize_regeneration([
				'outcome' => true,
				'reason' => 'no_products_exists'
			]);
		}
	}

	private function finalize_regeneration($args = []) {
		$args = wp_parse_args($args, [
			// true | false
			'outcome' => true,
			'reason' => 'default'
		]);

		$state = $this->get_regeneration_state();

		$this->set_regeneration_state([
			'state' => 'idle',
			'enabled' => $args['outcome']
		]);

		wc_get_logger()->info(
			'FiltersTaxonomiesProductsLookupTable:finalize_regeneration',
			[
				'source' => 'taxonomies_lookup_table',
				'state' => $state,
				'result' => $args['outcome'],
				'reason' => $args['reason']
			]
		);
	}

	public function lookup_table_has_data() {
		global $wpdb;

		return (
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			(int) $wpdb->get_var(
				// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				"SELECT EXISTS (SELECT 1 FROM {$this->lookup_table_name})"
			)
		) !== 0;
	}

	public function get_last_existing_product_id() {
		$last_existing_product_id_array = WC()->call_function(
			'wc_get_products',
			[
				'return'  => 'ids',
				'limit'   => 1,
				'orderby' => [
					'ID' => 'DESC',
				]
			]
		);

		if (empty($last_existing_product_id_array)) {
			return null;
		}

		return current($last_existing_product_id_array);
	}

	private function initialize_table_and_data() {
		$database_util = wc_get_container()->get(DatabaseUtil::class);
		$database_util->dbdelta($this->get_table_creation_sql());

		$last_existing_product_id = $this->get_last_existing_product_id();

		if (! $last_existing_product_id) {
			// No products exist, nothing to (re)generate.
			return false;
		}

		$count = wp_count_posts( 'product' );

		$this->set_regeneration_state([
			'state' => 'progress',
			'processed_count' => 0,
			'total_count' => $count->publish,
			'last_product_id' => $last_existing_product_id
		]);

		wc_get_logger()->info(
			'FiltersTaxonomiesProductsLookupTable:start_regeneration',
			[
				'source' => 'taxonomies_lookup_table',
				'last_existing_product_id' => $last_existing_product_id
			]
		);

		return true;
	}

	private function delete_all_taxonomies_lookup_data() {
		global $wpdb;

		if ($this->check_lookup_table_exists()) {
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$wpdb->query("TRUNCATE TABLE {$this->lookup_table_name}");
		}
	}

	public function get_table_creation_sql() {
		global $wpdb;

		$collate = $wpdb->has_cap('collation') ? $wpdb->get_charset_collate() : '';

		return "CREATE TABLE {$this->lookup_table_name} (
			product_id bigint(20) NOT NULL,
			taxonomy varchar(32) NOT NULL,
			term_id bigint(20) NOT NULL,
			PRIMARY KEY  (`term_id`, `product_id`, `taxonomy`)
) $collate;";
	}

	private function enqueue_regeneration_step_run() {
		$queue = WC()->get_instance_of(\WC_Queue::class);

		$queue->schedule_single(
			WC()->call_function('time') + 1,
			$this->scheduled_action_name,
			[],
			'woocommerce-db-updates'
		);
	}

	/**
	 * Action scheduler callback, performs one regeneration step and then
	 * schedules the next step if necessary.
	 */
	public function run_regeneration_step_callback() {
		$state = $this->get_regeneration_state();

		wc_get_logger()->info(
			'FiltersTaxonomiesProductsLookupTable:run_regeneration_step_callback',
			[
				'source' => 'taxonomies_lookup_table',
				'state' => $state
			]
		);

		// If somehow the action ran while the state is idle, just do nothing.
		if ($state['state'] === 'idle') {
			return;
		}

		if ($state['state'] !== 'progress') {
			$this->finalize_regeneration([
				'outcome' => false,
				'reason' => 'non_progress_state_detected'
			]);

			return;
		}

		try {
			$result = $this->do_regeneration_step();
		} catch (\Exception $e) {
			wc_get_logger()->error(
				'FiltersTaxonomiesProductsLookupTable:run_regeneration_step_callback:error',
				[
					'source' => 'taxonomies_lookup_table',
					'error' => $e->getMessage()
				]
			);

			$this->finalize_regeneration([
				'outcome' => false,
				'reason' => 'exception_during_step'
			]);

			return;
		}

		if ($result) {
			$this->enqueue_regeneration_step_run();
		} else {
			$this->finalize_regeneration([
				'outcome' => true,
				'reason' => 'all_products_processed'
			]);
		}
	}

	/**
	 * Perform one regeneration step: grabs a chunk of products and creates
	 * the appropriate entries for them in the lookup table.
	 *
	 * @return bool True if more steps need to be run, false otherwise.
	 */
	private function do_regeneration_step() {
		$products_per_generation_step = 30;

		$state = $this->get_regeneration_state();

		$products_already_processed = 0;

		if (isset($state['processed_count'])) {
			$products_already_processed = $state['processed_count'];
		}

		// TODO: Maybe just use a plain $wpdb SQL query here, to avoid any other
		// potential filterers that might interfere with the results.
		$query = new \WP_Query([
			'post_type' => 'product',
			'posts_per_page' => $products_per_generation_step,
			'offset' => $products_already_processed,
			'orderby' => [
				'ID' => 'ASC',
			],
			'fields' => 'ids',

			// Avoid WPML filtering response
			// phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.SuppressFilters_suppress_filters
			'suppress_filters' => true,

			// Avoid Polylang filtering response
			// https://polylang.pro/doc/developpers-how-to/#all
			'lang' => ''
		]);

		$product_ids = $query->posts;

		$last_product_id_to_process = PHP_INT_MAX;

		if (isset($state['last_product_id'])) {
			$last_product_id_to_process = $state['last_product_id'];
		}

		$last = end($product_ids);

		wc_get_logger()->debug(
			'FiltersTaxonomiesProductsLookupTable:do_regeneration_step',
			[
				'source' => 'taxonomies_lookup_table',
				'product_ids' => $product_ids,
				'last' => $last,
				'result' => $last < $last_product_id_to_process
			]
		);

		if (
			! is_array($product_ids)
			||
			empty($product_ids)
		) {
			return false;
		}

		foreach ($product_ids as $id) {
			try {
				$this->store->create_data_for_product($id);
			} catch (\Exception $e) {
				wc_get_logger()->error(
					'FiltersTaxonomiesProductsLookupTable:do_regeneration_step:error',
					[
						'source' => 'taxonomies_lookup_table',
						'product_id' => $id,
						'error' => $e->getMessage()
					]
				);

				continue;
			}
		}

		$products_already_processed += count($product_ids);

		$this->set_regeneration_state([
			'state' => 'progress',
			'processed_count' => $products_already_processed,
			'last_product_id' => $last_product_id_to_process,
			'total_count' => $state['total_count']
		]);

		return $last < $last_product_id_to_process;
	}

	public function get_product_ids() {
		global $wpdb;

		if (! $this->can_use_lookup_table()) {
			return [];
		}

		$query = $wpdb->prepare(
			'SELECT DISTINCT product_id FROM %i',
			$this->lookup_table_name
		);

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
		$results = $wpdb->get_results($query, ARRAY_A);

		return array_column($results, 'product_id');
	}

	public function add_initiate_regeneration_entry_to_tools_array($tools_array) {
		if (! $this->check_lookup_table_exists()) {
			return $tools_array;
		}

		$state = $this->get_regeneration_state();

		$entry = [
			'name' => __('Regenerate the product taxonomies lookup table', 'blocksy-companion'),
			'desc' => __('This tool will regenerate the product taxonomies lookup table data from existing product(s) data. This process may take a while.', 'blocksy-companion'),
			'requires_refresh' => true,
			'callback' => function() {
				$state = $this->get_regeneration_state();

				if ($state['state'] === 'progress') {
					throw new \Exception("Can't do product taxonomies lookup data regeneration: regeneration is already in progress");
				}

				$this->initiate_regeneration('manual');

				return __('Product taxonomies lookup table data is regenerating', 'blocksy-companion');
			}
		];

		if ($state['state'] === 'progress') {
			$entry['button'] = sprintf(
				/* translators: %d: How many products have been processed so far. */
				__('Filling in progress (%d)', 'blocksy-companion'),
				$state['processed_count']
			);

			$entry['disabled'] = true;
		} else {
			$entry['button'] = __('Regenerate', 'blocksy-companion');
		}

		$tools_array['regenerate_product_taxonomies_lookup_table'] = $entry;

		if ($state['state'] === 'progress') {
			$entry = [
				'name' => __('Abort the product taxonomies lookup table regeneration', 'blocksy-companion'),
				'desc' => __('This tool will abort the regenerate product taxonomies lookup table regeneration. After this is done the process can be either started over, or resumed to continue where it stopped.', 'blocksy-companion'),
				'requires_refresh' => true,
				'callback' => function() {
					$state = $this->get_regeneration_state();

					if ($state['state'] !== 'progress') {
						throw new \Exception("Can't do product taxonomies lookup data regeneration abort: regeneration is not in progress");
					}

					// $this->abort_regeneration_from_tools_page();
					$queue = WC()->get_instance_of(\WC_Queue::class);
					$queue->cancel_all($this->scheduled_action_name);

					$this->set_regeneration_state([
						'state' => 'aborted',
						'processed_count' => $state['processed_count'],
						'total_count' => $state['total_count'],
						'last_product_id' => $state['last_product_id']
					]);

					return __('Product taxonomies lookup table regeneration process has been aborted.', 'blocksy-companion');
				},
				'button' => __('Abort', 'blocksy-companion'),
			];

			$tools_array['abort_product_taxonomies_lookup_table_regeneration'] = $entry;
		} elseif ($state['state'] === 'aborted') {
			$entry = [
				'name' => __('Resume the product taxonomies lookup table regeneration', 'blocksy-companion'),
				'desc' => sprintf(
					/* translators: %1$s = count of products already processed. */
					__('This tool will resume the product taxonomies lookup table regeneration at the point in which it was aborted (%1$s products were already processed).', 'blocksy-companion'),
					$state['processed_count']
				),
				'requires_refresh' => true,
				'callback' => function() {
					$state = $this->get_regeneration_state();

					if ($state['state'] !== 'aborted') {
						throw new \Exception("Can't do product taxonomies lookup data regeneration resume: regeneration is not in aborted state");
					}

					$this->set_regeneration_state([
						'state' => 'progress',
						'processed_count' => $state['processed_count'],
						'total_count' => $state['total_count'],
						'last_product_id' => $state['last_product_id']
					]);

					$this->enqueue_regeneration_step_run();

					return __('Product taxonomies lookup table regeneration process has been resumed.', 'blocksy-companion');
				},
				'button' => __('Resume', 'blocksy-companion'),
			];

			$tools_array['resume_product_taxonomies_lookup_table_regeneration'] = $entry;
		}

		return $tools_array;
	}

	/**
	 * Register CLI tools for the lookup table.
	 */
	public function register_cli_tools($tools) {
		$tools['regenerate_taxonomies_lookup'] = [
			'name' => 'Regenerate Product Taxonomies Lookup Table',
			'callback' => function() {
				\WP_CLI::log('Starting regeneration of product taxonomies lookup table...');

				$start_time = microtime(true);
				$result = $this->regenerate_inline();
				$end_time = microtime(true);
				$duration = round($end_time - $start_time, 2);

				if ($result) {
					\WP_CLI::success("Product taxonomies lookup table regenerated successfully in {$duration} seconds.");
				} else {
					\WP_CLI::warning("No products found to process.");
				}
			}
		];

		return $tools;
	}

	/**
	 * Regenerate the lookup table synchronously in one go.
	 * Use with caution on large product catalogs.
	 */
	public function regenerate_inline() {
		$this->delete_all_taxonomies_lookup_data();

		if (! $this->initialize_table_and_data()) {
			// No products exist
			return false;
		}

		$products = wc_get_products([
			'return' => 'objects',
			'limit' => -1,
			'status' => 'publish'
		]);

		foreach ($products as $product) {
			try {
				$this->store->create_data_for_product($product);
			} catch (\Exception $e) {
				wc_get_logger()->error(
					'FiltersTaxonomiesProductsLookupTable:regenerate_inline:error',
					[
						'source' => 'taxonomies_lookup_table',
						'product_id' => $product->get_id(),
						'error' => $e->getMessage()
					]
				);
			}
		}

		$this->set_regeneration_state([
			'state' => 'idle',
			'enabled' => true
		]);

		wc_get_logger()->info(
			'FiltersTaxonomiesProductsLookupTable:regenerate_inline:complete',
			[
				'source' => 'taxonomies_lookup_table',
				'products_processed' => count($products)
			]
		);

		return true;
	}
}
