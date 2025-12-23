<?php

namespace Blocksy\Extensions\WoocommerceExtra;

use \Automattic\WooCommerce\Internal\ProductAttributesLookup\Filterer;
use \Automattic\WooCommerce\Internal\ProductAttributesLookup\DataRegenerator;

class StatusFilter extends BaseFilter {
	private $product_ids_cache = [];

	static private $filter_param = 'filter_status';

	public function get_filter_id() {
		return 'status_filter';
	}

	public function get_reset_url($attributes = []) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if (isset($_GET[self::$filter_param]) ) {
			return remove_query_arg(self::get_query_params());
		}

		return false;
	}

	public function render($attributes = []) {
		$counts = $this->get_status_counts();

		if (
			! $attributes['statuses']
			||
			empty($attributes['statuses'])
		) {
			return '';
		}

		$statuses_html = [];

		foreach ($attributes['statuses'] as $status) {
			if (
				! isset($counts[$status['id']])
				||
				intval($counts[$status['id']]) === 0
				||
				! $status['enabled']
			) {
				continue;
			}

			$is_active = FilterPresenter::is_filter_active(
				self::$filter_param,
				$status['id']
			);

			$api_url = FiltersUtils::get_link_url(
				self::$filter_param,
				$status['id'],
				[
					'is_multiple' => true,
				]
			);

			$statuses_html[] = blocksy_html_tag(
				'li',
				[
					'class' => 'ct-filter-item' . ($is_active ? ' active' : ''),
				],
				blocksy_html_tag(
					'div',
					[
						'class' => 'ct-filter-item-inner',
					],
					blocksy_html_tag(
						'a',
						[
							'href' => $api_url,
							'rel' => 'nofollow',
							'aria-label' => $status['label'],
							'data-key' => 'filter_stock_status',
							'data-value' => $status['id'],
						],
						($attributes['showCheckboxes'] ? blocksy_html_tag(
							'input',
							array_merge(
								[
									'type' => 'checkbox',
									'class' => 'ct-checkbox',
									'tabindex' => '-1',
									'name' => 'filter_stock_status_' . $status['id'],
									'aria-label' => $status['label'],
								],
								$is_active ? ['checked' => 'checked'] : []
							)
						) : '') .
						blocksy_html_tag(
							'span',
							[
								'class' => 'ct-filter-label',
							],
							$status['label']
						) .
						(
							$attributes['showCounters'] ? blocksy_html_tag(
								'span',
								[
									'class' => 'ct-filter-count',
								],
								$counts[$status['id']]
							) : ''
						)
					)
				)
			);
		}

		if (empty($statuses_html)) {
			return '';
		}

		return blocksy_html_tag(
			'div',
			[
				'class' => 'ct-status-filter',
			],
			blocksy_html_tag(
				'ul',
				[
					'class' => 'ct-filter-widget',
					'data-display-type' => $attributes['layout'],
				],
				implode(
					'',
					$statuses_html
				)
			)
		);
	}

	public static function get_query_params() {
		return [self::$filter_param];
	}

	public static function get_status_options() {
		return array_merge(
			wc_get_product_stock_status_options(),
			[
				'on_sale' => __('On sale', 'blocksy-companion'),
				'featured' => __('Featured', 'blocksy-companion'),
			]
		);
	}

	public function get_applied_filters() {
		if (! $this->get_reset_url()) {
			return [];
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$values = explode(',', blocksy_akg(self::$filter_param, $_GET, ''));

		$items = [];

		foreach ($values as $value) {
			$labels = self::get_status_options();

			$items[] = [
				'name' => $labels[$value],
				'value' => $value,
				'href' => remove_query_arg(self::$filter_param, $this->get_reset_url())
			];
		}

		return [
			'name' => __('Status', 'blocksy-companion'),
			'items' => $items
		];
	}

	public function get_product_ids_for_picked_statuses($picked_statuses) {
		$built_in_statuses = array_keys(wc_get_product_stock_status_options());

		$picked_builtin_stock_statuses = array_intersect(
			$picked_statuses,
			$built_in_statuses
		);


		$final_product_ids = [];

		if (! empty($picked_builtin_stock_statuses)) {
			sort($picked_builtin_stock_statuses);

			$cache_key = md5(implode(',', $picked_builtin_stock_statuses));

			if (! isset($this->product_ids_cache[$cache_key])) {
				$products = new \WP_Query([
					'post_type' => 'product',
					'fields' => 'ids',
					'posts_per_page' => -1,
					'update_post_meta_cache' => false,
					'update_post_term_cache' => false,
					'cache_results' => false,
					'no_found_rows' => true,
					'nopaging' => true, // prevent "offset" issues
					'blocksy-woocommerce-extra-filters' => false,
					// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					'meta_query' => [
						[
							'key' => '_stock_status',
							'value' => $picked_builtin_stock_statuses,
							'compare' => 'IN'
						]
					]
				]);

				$this->product_ids_cache[$cache_key] = $products->posts;
			}

			$final_product_ids = array_merge(
				$final_product_ids,
				$this->product_ids_cache[$cache_key]
			);
		}

		if (in_array('on_sale', $picked_statuses)) {
			if (! isset($this->product_ids_cache['on_sale'])) {
				$this->product_ids_cache['on_sale'] = wc_get_product_ids_on_sale();
			}

			$final_product_ids = array_merge(
				$final_product_ids,
				$this->product_ids_cache['on_sale']
			);
		}

		if (in_array('featured', $picked_statuses)) {
			if (! isset($this->product_ids_cache['featured'])) {
				$this->product_ids_cache['featured'] = wc_get_featured_product_ids();
			}

			$final_product_ids = array_merge(
				$final_product_ids,
				$this->product_ids_cache['featured']
			);
		}

		return $final_product_ids;
	}

	public function wp_query_arg($query_string, $query_args, $reason) {
		if (empty(blocksy_akg(self::$filter_param, $query_string, ''))) {
			return $query_args;
		}
		
		$final_product_ids = $this->get_product_ids_for_picked_statuses(
			explode(',', blocksy_akg(self::$filter_param, $query_string, ''))
		);

		if (! empty($final_product_ids)) {
			$query_args['post__in'] = array_unique(
				array_merge(
					$query_args['post__in'] ?? [],
					$final_product_ids
				)
			);
		}

		return $query_args;
	}

	public function get_status_counts() {
		$product_ids = $this->get_product_ids_for_current_query(self::$filter_param);

		$all_statuses = self::get_status_options();

		foreach ($all_statuses as $key => $value) {
			$scoped_ids = $this->get_product_ids_for_picked_statuses([$key]);

			$all_statuses[$key] = count(array_intersect($product_ids, $scoped_ids));
		}

		return $all_statuses;
	}

	private function get_product_ids_for_current_query($param = '') {
		$apply_filters = new ApplyFilters();

		$params = FiltersUtils::get_query_params();
		$filter_params = $this->get_query_params();

		$params = $params['params'];

		foreach ($filter_params as $param) {
			unset($params[$param]);
		}

		$products_query = $apply_filters->get_custom_query_for($params);

		return $products_query->posts;
	}
}

