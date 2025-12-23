<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class ApplyFilters {
	use QueryManager;

	public $custom_query_string = [];

	public function mount_entry_point() {
		$this->apply_on_main_query();
	}

	public function apply_on_main_query() {
		add_action('pre_get_posts', [$this, 'filter_products'], 100);
	}

	/**
	 * @phpcs:disable WordPress.DB.SlowDBQuery.slow_db_query_tax_query
	 */
	public function filter_products($query) {
		if (! $this->is_main_query($query)) {
			return;
		}

		$params = FiltersUtils::get_query_params();

		$query_args = [
			'tax_query' => []
		];

		if (is_array($query->get('tax_query'))) {
			$query_args['tax_query'] = $query->get('tax_query');
		}

		$initial_tax_query_count = count($query_args['tax_query']);

		$filters = Filters::get_filter_instance();

		foreach ($filters as $filter) {
			if (method_exists($filter, 'wp_query_arg')) {
				$query_args = $filter->wp_query_arg(
					$params['params'],
					$query_args,
					BaseFilter::$WP_QUERY_ARG_REASON_MAIN
				);
			}
		}

		if (
			! empty($query_args['tax_query'])
			&&
			count($query_args['tax_query']) !== $initial_tax_query_count
		) {
			add_filter('woocommerce_is_filtered', '__return_true');
		}

		foreach ($query_args as $key => $value) {
			if (empty($value)) {
				continue;
			}

			$query->set($key, $value);
		}
	}

	public function get_custom_query_for($query_string) {
		$filters = Filters::get_filter_instance();

		$tax_query = [];
		$search_query = '';

		$is_taxonomy_page = $this->is_taxonomy_page();

		if ($is_taxonomy_page) {
			$queried_object = get_queried_object();

			$tax_query = [
				[
					'taxonomy' => $queried_object->taxonomy,
					'field' => 'id',
					'terms' => $queried_object->term_id,
					'operator' => 'IN'
				]
			];
		}

		if (
			is_search()
			&&
			! empty($query_string['s'])
		) {
			$search_query = $query_string['s'];
		}

		$query_args = [
			's' => $search_query,
			'paged' => 1,
			'posts_per_page' => -1,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'cache_results' => false,
			'no_found_rows' => true,
			'nopaging' => true, // prevent "offset" issues
			'blocksy-woocommerce-extra-filters' => false,
			'fields' => 'ids',
			'post_type' => 'product',

			'tax_query' => $tax_query
		];

		foreach ($filters as $filter) {
			if (method_exists($filter, 'wp_query_arg')) {
				$query_args = $filter->wp_query_arg(
					$query_string,
					$query_args,
					BaseFilter::$WP_QUERY_ARG_REASON_COUNT
				);
			}
		}

		$this->custom_query_string = $query_string;

		add_filter(
			'posts_clauses',
			[$this, 'posts_clauses_for_fresh_query'],
			10, 2
		);

		$query = new \WP_Query($query_args);

		remove_filter(
			'posts_clauses',
			[$this, 'posts_clauses_for_fresh_query'],
			10, 2
		);

		return $query;
	}

	public function posts_clauses_for_fresh_query($clauses, $query) {
		$filters = Filters::get_filter_instance();

		foreach ($filters as $filter) {
			if (method_exists($filter, 'posts_clauses')) {
				$clauses = $filter->posts_clauses(
					$clauses,
					$query,
					$this->custom_query_string
				);
			}
		}

		return $clauses;
	}
}
