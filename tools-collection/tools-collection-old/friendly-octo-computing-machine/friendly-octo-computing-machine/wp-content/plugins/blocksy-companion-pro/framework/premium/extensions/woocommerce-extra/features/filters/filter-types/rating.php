<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class RatingFilter extends BaseFilter {
	private $product_ids_cache = [];

	static private $filter_param = 'filter_by_rating';

	public function get_filter_id() {
		return 'rating_filter';
	}

	public function get_reset_url($attributes = []) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if (isset($_GET[self::$filter_param])) {
			return remove_query_arg(self::get_query_params());
		}

		return false;
	}

	public function render($attributes = []) {
		$ratings_html = [];
		$built_in_ratings = self::get_rating_options();

		foreach ($built_in_ratings as $rating => $rating_label) {
			$is_active = FilterPresenter::is_filter_active(
				self::$filter_param,
				$rating
			);

			$api_url = FiltersUtils::get_link_url(
				self::$filter_param,
				$rating,
				[
					'is_multiple' => false,
					'to_add' => $attributes['exactMatch'] ? [
						self::$filter_param . '_exact_match' => '1'
					] : []
				]
			);

			$tooltip = '';

			if ($attributes['showTooltips']) {
				$tooltip_label = $rating_label;

				if (! $attributes['exactMatch'] && intval($rating) !== 5) {
					$tooltip_label = blocksy_safe_sprintf(
						// translators: %s is replaced with the minimum star label for the filter tooltip.
						__('%s and up', 'blocksy-companion'),
						$rating_label
					);
				}

				$tooltip = blocksy_html_tag(
					'span',
					[
						'class' => 'ct-tooltip'
					],
					$tooltip_label
				);
			}

			$ratings_html[] = blocksy_html_tag(
				'li',
				[
					'class' => 'ct-filter-item' . ($is_active ? ' active' : ''),
				],
				blocksy_html_tag(
					'a',
					[
						'href' => $api_url,
						'rel' => 'nofollow',
						'aria-label' => $rating_label,
						'data-key' => 'filter_rating',
						'data-value' => $rating,
					],
					$tooltip .
					'<svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
					</svg>'
				)
			);
		}

		if (empty($ratings_html)) {
			return '';
		}

		return blocksy_html_tag(
			'div',
			[
				'class' => 'ct-rating-filter',
			],
			blocksy_html_tag(
				'ul',
				[
					'class' => 'ct-filter-widget',
					'data-display-type' => 'inline'
				],
				implode(
					'',
					$ratings_html
				)
			)
		);
	}

	public static function get_query_params() {
		return [self::$filter_param];
	}

	public static function get_rating_options() {
		return [
			'1' => __('1 star', 'blocksy-companion'),
			'2' => __('2 stars', 'blocksy-companion'),
			'3' => __('3 stars', 'blocksy-companion'),
			'4' => __('4 stars', 'blocksy-companion'),
			'5' => __('5 stars', 'blocksy-companion'),
		];
	}

	public function get_applied_filters() {
		if (! $this->get_reset_url()) {
			return [];
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$values = explode(',', blocksy_akg(self::$filter_param, $_GET, ''));

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$exact_match = blocksy_akg(self::$filter_param . '_exact_match', $_GET, '');
		$items = [];

		foreach ($values as $value) {
			$labels = self::get_rating_options();

			if (! isset($labels[$value])) {
				continue;
			}

			$item_name = $labels[$value];

			if (! $exact_match && intval($value) !== 5) {
				$item_name = blocksy_safe_sprintf(
					// translators: %s is replaced with the minimum star label for the applied filter summary.
					__('%s and up', 'blocksy-companion'),
					$labels[$value]
				);
			}

			$items[] = [
				'name' => $item_name,
				'value' => $value,
				'href' => remove_query_arg(self::$filter_param, $this->get_reset_url())
			];
		}

		return [
			'name' => __('Rating', 'blocksy-companion'),
			'items' => $items
		];
	}

	public function get_product_ids_for_picked_ratings($picked_rating, $exact_match = false) {
		$final_product_ids = [];
		$built_in_ratings = array_keys(self::get_rating_options());

		if (
			! empty($picked_rating)
			&&
			in_array($picked_rating, $built_in_ratings)
		) {
			$cache_key = md5('rating_' . $picked_rating);

			$aproximate_rating_min = $picked_rating - 0.5;

			$meta_query = [
				[
					'key' => '_wc_average_rating',
					'value' => $aproximate_rating_min,
					'compare' => '>=',
				],
			];

			if ($exact_match) {
				$aproximate_rating_max = $picked_rating + 0.5;

				$meta_query[] = [
					'key' => '_wc_average_rating',
					'value' => $aproximate_rating_max,
					'compare' => '<=',
				];
			}

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
					'meta_query' => array_merge(
						[
							'relation' => 'AND',
							[
								'key' => '_wc_average_rating',
								'value' => '',
								'compare' => '!=',
							],
							[
								'key' => '_wc_average_rating',
								'compare' => 'EXISTS',
							],
						],
						$meta_query
					)
				]);

				$this->product_ids_cache[$cache_key] = $products->posts;
			}

			$final_product_ids = array_merge(
				$final_product_ids,
				$this->product_ids_cache[$cache_key]
			);
		}

		return $final_product_ids;
	}

	public function wp_query_arg($query_string, $query_args, $reason) {
		if (empty(blocksy_akg(self::$filter_param, $query_string, ''))) {
			return $query_args;
		}

		$final_product_ids = $this->get_product_ids_for_picked_ratings(
			blocksy_akg(self::$filter_param, $query_string, ''),
			blocksy_akg(self::$filter_param . '_exact_match', $query_string, '')
		);

		if (empty($final_product_ids)) {
			$query_args['post__in'] = [0];

			return $query_args;
		}

		$query_args['post__in'] = array_unique(
			array_merge(
				$query_args['post__in'] ?? [],
				$final_product_ids
			)
		);

		return $query_args;
	}

	// public function posts_clauses($clauses, $query, $query_string) {
	// 	global $wpdb;

	// 	if (! $query->get('blocksy_filter_by_rating')) {
	// 		return $clauses;
	// 	}

	// 	$min_rating = intval($query->get('blocksy_filter_by_rating'));

	// 	// Replace comparison with ROUND()
	// 	$clauses['where'] .= $wpdb->prepare(
	// 		" AND ROUND(CAST(pm.meta_value AS DECIMAL(3,1))) >= %d ",
	// 		$min_rating
	// 	);

	// 	return $clauses;
	// }

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
