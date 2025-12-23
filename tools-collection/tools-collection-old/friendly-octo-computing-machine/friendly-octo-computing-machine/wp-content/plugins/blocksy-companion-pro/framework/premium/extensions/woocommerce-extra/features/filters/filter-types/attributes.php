<?php

namespace Blocksy\Extensions\WoocommerceExtra;

use \Automattic\WooCommerce\Internal\ProductAttributesLookup\Filterer;
use \Automattic\WooCommerce\Internal\ProductAttributesLookup\DataRegenerator;

class AttributesFilter extends BaseFilter {
	use ProductTermsCountTrait;

	public static $prefix = 'filter_';
	public $attributes = [];

	public function get_filter_id() {
		return 'attributes_filter';
	}

	public function get_filter_name($attribute = '') {
		if (empty($attribute)) {
			$attribute = $this->attributes['attribute'];
		}

		return self::$prefix . $attribute;
	}

	public function render($attributes = []) {
		$this->attributes = $attributes;

		$taxonomy_terms = $this->filter_get_terms_list($attributes['attribute']);

		if (empty($taxonomy_terms)) {
			return '';
		}

		$taxonomy_terms = $this->get_attributes_counts($taxonomy_terms);

		$additional_attrs = [];

		$storage = new Storage();
		$settings = $storage->get_settings();

		if ($settings['features']['variation-swatches']) {
			$swatch_type = 'select';

			if (sizeof($taxonomy_terms)) {
				$conf = new SwatchesConfig();

				$swatch_type = $conf->get_attribute_type(
					$taxonomy_terms[0]->taxonomy
				);
			}

			$swatch_shape = 'round';

			if ($swatch_type === 'color') {
				$swatch_shape = blc_theme_functions()->blocksy_get_theme_mod('color_swatch_shape', 'round');
			}

			if ($swatch_type === 'image') {
				$swatch_shape = blc_theme_functions()->blocksy_get_theme_mod('image_swatch_shape', 'round');
			}

			if ($swatch_type === 'button') {
				$swatch_shape = blc_theme_functions()->blocksy_get_theme_mod('button_swatch_shape', 'round');
			}

			if ($swatch_type === 'mixed') {
				$swatch_shape = blc_theme_functions()->blocksy_get_theme_mod('mixed_swatch_shape', 'round');
			}

			$additional_attrs = [
				'data-swatches-type' => $swatch_type,
				'data-swatches-shape' => $swatch_shape,
			];
		}

		$items = $this->filter_get_items($taxonomy_terms);

		if (empty($items)) {
			return '';
		}

		return blocksy_html_tag(
			'ul',
			array_merge(
				[
					'class' => 'ct-filter-widget',
					'data-display-type' => $attributes['viewType'],
				],
				$additional_attrs,
				$this->attributes['limitHeight'] ? [
					'style' => 'max-height: ' . $this->attributes['limitHeightValue'] . 'px'
				] : []
			),
			join('', $items)
		);
	}

	public function filter_get_terms_list($attribute_slug = '') {
		if (! taxonomy_exists(wc_attribute_taxonomy_name($attribute_slug))) {
			return [];
		}

		$taxonomy_terms = [];
		$list_items_html = [];

		$params = [
			'hide_empty' => true,
			// phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
			'exclude' => $this->attributes['taxonomy_not_in'],
			'taxonomy' => wc_attribute_taxonomy_name($attribute_slug)
		];

		if (! $this->attributes['excludeTaxonomy']) {
			unset($params['exclude']);
		}

		$taxonomy_terms = get_terms($params);

		if (
			! $taxonomy_terms
			||
			is_wp_error($taxonomy_terms)
		) {
			return [];
		}

		return $taxonomy_terms;
	}

	private function filter_get_items($taxonomy_terms) {
		$attribute_slug = $this->attributes['attribute'];

		$list_items_html = [];

		foreach ($taxonomy_terms as $key => $value) {
			$api_url = FiltersUtils::get_link_url(
				$this->get_filter_name(),
				$value->slug,
				[
					'is_multiple' => $this->attributes['multipleFilters'],
					'to_add' => [
						'query_type_' . $this->attributes['attribute'] => 'or'
					]
				]
			);

			$products_count = FilterPresenter::format_products_count([
				'count' => $value->count
			]);

			if (! $products_count) {
				continue;
			}

			if (! $this->attributes['showCounters']) {
				$products_count = '';
			}

			$swatch_term_html = '';

			$conf = new SwatchesConfig();

			$swatch_term = new SwatchElementRender(
				$conf->get_swatch_element_descriptor($value)
			);

			if ($this->attributes['showItemsRendered']) {
				$swatch_term_html = $swatch_term->get_output(
					!$this->attributes['showTooltips']
				);
			}

			$label_html = $this->attributes['showLabel']
				? blocksy_html_tag(
					'span',
					['class' => 'ct-filter-label'],
					$value->name
				)
				: '';

			$item_classes = ['ct-filter-item'];

			if (
				FilterPresenter::is_filter_active(
					$this->get_filter_name(),
					$value->slug
				)
			) {
				$item_classes[] = 'active';
			}

			$checbox_html = $this->attributes['showAttributesCheckbox']
				? blocksy_html_tag(
					'input',
					array_merge(
						[
							'type' => 'checkbox',
							'class' => 'ct-checkbox',
							'tabindex' => '-1',
							'name' => 'product_attribute_' . $value->term_id,
							'aria-label' => $value->name,
						],
						FilterPresenter::is_filter_active(
							$this->get_filter_name(),
							$value->slug
						)
							? ['checked' => 'checked']
							: []
					)
				)
				: '';

			$list_items_html[] = blocksy_html_tag(
				'li',
				[
					'class' => implode(' ', $item_classes),
				],
				blocksy_html_tag(
					'div',
					[
						'class' => 'ct-filter-item-inner'
					],
					blocksy_html_tag(
						'a',
						[
							'href' => esc_url($api_url),
							'rel' => 'nofollow',
							'aria-label' => $value->name,
							'data-key' => $attribute_slug,
							'data-value' => $value->term_id,
						],
						$checbox_html .
						$swatch_term_html .
							$label_html .
							$products_count
					)
				)
			);
		}

		return $list_items_html;
	}

	public function get_attributes_counts($terms, $args = []) {
		$args = wp_parse_args($args, [
			'ignore_current_query' => false
		]);

		$filterer = wc_get_container()->get(Filterer::class);

		$counts_result = $this->get_terms_counts(
			self::$prefix . $this->attributes['attribute'],
			[
				'ignore_current_query' => $args['ignore_current_query'],
				'term_ids' => array_map(function ($term) {
					return $term->term_id;
				}, $terms)
			]
		);

		$terms_with_counts = [];

		foreach ($terms as $term) {
			$term_count = 0;

			if (isset($counts_result[$term->term_id])) {
				$term_count = intval($counts_result[$term->term_id]->term_count);
			}

			$term->count = $term_count;

			$terms_with_counts[] = $term;
		}

		return $terms_with_counts;
	}

	protected function get_terms_counts_sql($args = []) {
		$args = wp_parse_args($args, [
			'product_ids' => [],
			'term_ids' => []
		]);

		if (
			empty($args['product_ids'])
			||
			empty($args['term_ids'])
		) {
			return '';
		}

		$filterer = wc_get_container()->get(Filterer::class);

		if (! $filterer->filtering_via_lookup_table_is_active()) {
			global $wpdb;

			return "
				SELECT term_relationships.term_taxonomy_id as term_id, COUNT(DISTINCT posts.ID) as term_count
				FROM {$wpdb->posts} AS posts
				INNER JOIN {$wpdb->term_relationships} AS term_relationships ON posts.ID = term_relationships.object_id
				WHERE (
					posts.ID IN (" . implode(',', $args['product_ids']) . ")
					AND
					term_relationships.term_taxonomy_id IN (" . implode(',', $args['term_ids']) . ")
				)
				GROUP BY term_relationships.term_taxonomy_id
			";
		}

		$lookup_table_name = wc_get_container()->get(
			DataRegenerator::class
		)->get_lookup_table_name();

		return "
			SELECT term_id, COUNT(DISTINCT product_or_parent_id) as term_count
			FROM {$lookup_table_name}
			WHERE (
				product_or_parent_id IN (" . implode(',', $args['product_ids']) . ")
				AND
				term_id IN (" . implode(',', $args['term_ids']) . ")
			)
			GROUP BY term_id
		";
	}

	public static function get_query_params() {
		$attributes = wc_get_attribute_taxonomies();

		if (! $attributes) {
			return [];
		}

		$prefixed_params = [];

		foreach ($attributes as $attribute) {
			$prefixed_params[] = self::$prefix . $attribute->attribute_name;
		}

		return $prefixed_params;
	}

	public function get_applied_filter_descriptor($param, $value) {
		$attribute_taxonomies = wc_get_attribute_taxonomies();
		$maybe_attribute = null;

		foreach ($attribute_taxonomies as $attribute) {
			if (self::$prefix . $attribute->attribute_name === $param) {
				$maybe_attribute = $attribute;
				break;
			}
		}

		if (! $maybe_attribute) {
			return null;
		}

		$taxonomy = wc_attribute_taxonomy_name($attribute->attribute_name);
		$term = get_term_by('slug', $value, $taxonomy);

		if ($term) {
			return [
				'name' => $term->name,
				'href' => FiltersUtils::get_link_url(
					$this->get_filter_name($attribute->attribute_name),
					$term->slug,
					[
						'to_add' => [
							'query_type_' . $attribute->attribute_name => 'or'
						]
					]
				)
			];
		}

		return null;
	}

	public function get_taxonomy_name($param = '') {
		if (empty($param)) {
			$param = $this->attributes['attribute'];
		}

		$filter_name = '';

		$maybe_taxonomy_name = wc_attribute_taxonomy_name(str_replace(self::$prefix, '', $param));

		if (taxonomy_exists($maybe_taxonomy_name)) {
			$labels = get_taxonomy_labels(get_taxonomy($maybe_taxonomy_name));

			if (isset($labels->singular_name)) {
				$filter_name = $labels->singular_name;
			}
		}

		return $filter_name;
	}

	public function get_applied_filters() {
		$query_params = self::get_query_params();

		if (! $query_params) {
			return [];
		}

		$result = [];

		foreach ($query_params as $param) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if (! isset($_GET[$param])) {
				continue;
			}

			$filter_name = $this->get_taxonomy_name($param);

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$values = explode(',', $_GET[$param]);

			$items = [];

			foreach ($values as $single_value) {
				$descriptor = $this->get_applied_filter_descriptor(
					$param,
					$single_value
				);

				if ($descriptor) {
					$items[] = $descriptor;
				}
			}

			$result[] = [
				'name' => $filter_name,
				'items' => $items
			];
		}

		return $result;
	}

	public function wp_query_arg($query_string, $query_args, $reason) {
		if ($reason !== BaseFilter::$WP_QUERY_ARG_REASON_COUNT) {
			return $query_args;
		}

		$filterer = wc_get_container()->get(Filterer::class);

		if ($filterer->filtering_via_lookup_table_is_active()) {
			return $query_args;
		}

		$layered_nav_chosen = $this->get_layered_nav($query_string);

		foreach ($layered_nav_chosen as $taxonomy => $data) {
			$query_args['tax_query'][] = array(
				'taxonomy' => $taxonomy,
				'field' => 'slug',
				'terms' => $data['terms'],
				'operator' => 'and' === $data['query_type'] ? 'AND' : 'IN',
				'include_children' => false,
			);
		}

		return $query_args;
	}

	public function posts_clauses($clauses, $query, $query_string) {
		$layered_nav_chosen = [];

		$filterer = wc_get_container()->get(Filterer::class);

		if (! $filterer->filtering_via_lookup_table_is_active()) {
			return $clauses;
		}

		$layered_nav_chosen = $this->get_layered_nav($query_string);

		global $wp_the_query;
		$prev_wp_query = $wp_the_query;
		$GLOBALS['wp_the_query'] = $query;

		$clauses = $filterer->filter_by_attribute_post_clauses(
			$clauses,
			$query,
			$layered_nav_chosen
		);

		$GLOBALS['wp_the_query'] = $prev_wp_query;

		return $clauses;
	}

	private function get_layered_nav($query_string) {
		$layered_nav_chosen = [];

		foreach ($query_string as $key => $value) {
			if (0 !== strpos($key, self::$prefix)) {
				continue;
			}

			$attribute = wc_sanitize_taxonomy_name(
				str_replace(self::$prefix, '', $key)
			);

			$taxonomy = wc_attribute_taxonomy_name($attribute);

			if (
				! taxonomy_exists($taxonomy)
				||
				! wc_attribute_taxonomy_id_by_name($attribute)
			) {
				continue;
			}

			$filter_terms = ! empty($value)
				? explode(',', wc_clean(wp_unslash($value)))
				: array();

			if (empty($filter_terms)) {
				continue;
			}

			$all_terms = [];

			foreach ($filter_terms as $term) {
				$term_obj = get_term_by('id', $term, $taxonomy);

				if (! $term_obj) {
					$term_obj = get_term_by('slug', $term, $taxonomy);
				}

				if (! $term_obj) {
					continue;
				}

				$all_terms[] = $term_obj->slug;
			}

			if (! isset($layered_nav_chosen[$taxonomy])) {
				$layered_nav_chosen[$taxonomy] = [
					'terms' => [],
					'query_type' => 'or',
				];
			}

			$layered_nav_chosen[$taxonomy]['terms'] = $all_terms;
		}

		return $layered_nav_chosen;
	}

	public function get_reset_url($attributes = []) {
		$filter_param = $this->get_filter_name($attributes['attribute']);
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$has_filter = blocksy_akg($filter_param, $_GET, '');

		if (! empty($has_filter)) {
			return remove_query_arg(
				[
					$filter_param,
					'query_type_' . $attributes['attribute']
				]
			);
		}

		return false;
	}
}

