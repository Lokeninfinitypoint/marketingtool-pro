<?php

namespace Blocksy\Extensions\WoocommerceExtra;

use \Automattic\WooCommerce\Internal\ProductAttributesLookup\Filterer;
use \Automattic\WooCommerce\Internal\ProductAttributesLookup\DataRegenerator;

class TaxonomiesFilter extends BaseFilter {
	use ProductTermsCountTrait;
	use QueryManager;

	public static $prefix = 'filter_tax_';
	public $attributes = [];

	private $ignore_current_query = false;

	public function get_filter_id() {
		return 'taxonomies_filter';
	}

	public function get_filter_name($taxonomy = '') {
		if (empty($taxonomy)) {
			$taxonomy = $this->attributes['taxonomy'];
		}

		return self::$prefix . $taxonomy;
	}

	public function render($attributes = []) {
		$this->attributes = $attributes;

		$lookup_table = blc_get_ext('woocommerce-extra')
			->filters
			->lookup_table;

		if (! $lookup_table->can_use_lookup_table()) {
			$last_product_id = $lookup_table->get_last_existing_product_id();

			if (
				// No products on the site yet
				! $last_product_id
				||
				! current_user_can('manage_options')
			) {
				return '';
			}

			return $this->render_progress();
		}

		$render_descriptor = [
			'items' => $this->get_items()
		];

		if (
			$this->attributes['type'] === 'categories'
			||
			$this->attributes['type'] === 'brands'
		) {
			$render_descriptor['list_attr'] = [
				'style' => '',
				'data-frame' => $this->attributes['useFrame'] ? 'yes' : 'no',
			];

			if ($this->attributes['logoMaxW']) {
				$render_descriptor['list_attr']['style'] = "--product-taxonomy-logo-size: {$this->attributes['logoMaxW']}px;";
			}

			if ($this->attributes['imageFit'] === 'contain') {
				$render_descriptor['list_attr']['style'] .= '--theme-object-fit: contain;';
			}

			if (empty($render_descriptor['list_attr']['style'])) {
				unset($render_descriptor['list_attr']['style']);
			}
		}

		$data_filter_criteria = $this->attributes['type'];

		if ($this->attributes['type'] === 'categories') {
			$data_filter_criteria = 'taxonomy:' . $this->attributes['taxonomy'];
		}

		if (empty($render_descriptor['items'])) {
			return '';
		}

		return blocksy_html_tag(
			'ul',
			array_merge(
				[
					'class' => 'ct-filter-widget',
					'data-display-type' => $this->attributes['viewType'],
					'data-filter-criteria' => $data_filter_criteria,
				],
				blocksy_akg('list_attr', $render_descriptor, []),
				$this->attributes['limitHeight'] ? [
					'style' => 'max-height: ' . $this->attributes['limitHeightValue'] . 'px'
				] : []
			),
			join('', $render_descriptor['items'])
		);
	}

	public function render_progress() {
		$state = blc_get_ext('woocommerce-extra')->get_filters()->lookup_table->get_regeneration_state();

		$percent = 0;

		if ($state['state'] === 'progress' && $state['total_count'] > 0) {
			$percent = round(($state['processed_count'] / $state['total_count']) * 100);
		}

		return blocksy_html_tag(
				'div',
				[
					'class' => 'ct-filter-widget-admin-notice'
				],
				blocksy_html_tag(
					'div',
					[
						'class' => 'ct-notice-heading'
					],
					'<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M0 0h24v24H0z" fill="none"></path><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2zm-2 1H8v-6c0-2.48 1.51-4.5 4-4.5s4 2.02 4 4.5v6z"></path></svg>' .
					__('Regenerating Taxonomies Data', 'blocksy-companion')
				)
				 .
				blocksy_html_tag(
					'div',
					[
						'class' => 'ct-notice-content'
					],
					blocksy_html_tag(
						'div',
						[
							'class' => 'ct-notice-progress-bar-wrapper'
						],
						blocksy_html_tag(
							'div',
							[
								'class' => 'ct-notice-progress-bar-heading'
							],
							blocksy_safe_sprintf(
								// translators: %s is replaced with the current progress percentage.
								__('Progress <span>%s%%</span>', 'blocksy-companion'),
								$percent
							)
						) . blocksy_html_tag(
							'div',
							[
								'class' => 'ct-notice-progress-bar'
							],
							"<span style='width: {$percent}%;'></span>"
						)
					)
					 .
					blocksy_html_tag(
						'div',
						[
							'class' => 'ct-notice-text'
						],
						__('Please wait until the WooCommerce product taxonomies lookup table is regenerated.', 'blocksy-companion')
					) . blocksy_html_tag(
						'div',
						[
							'class' => 'ct-notice-buttons-group'
						],
						blocksy_html_tag(
							'a',
							[
								'href' => 'https://creativethemes.com/blocksy/docs/troubleshooting/regenerating-product-lookup-tables/',
								'target' => '_blank',
								'rel' => 'noopener noreferrer',
							],
							__('Why is the table being regenerated?', 'blocksy-companion')
							.
							'<svg width="8" height="8" fill="currentColor" viewBox="0 0 10 10"><path fill-rule="evenodd" d="M.341.738C.341.33.671 0 1.079 0h8.183C9.67 0 10 .33 10 .738V8.92a.738.738 0 1 1-1.475 0V2.52L1.259 9.784A.738.738 0 0 1 .216 8.74l7.265-7.266H1.08A.738.738 0 0 1 .34.738Z"></path></svg>'
						)
						 .
						blocksy_html_tag(
							'a',
							[
								'href' => admin_url('admin.php?page=wc-status&tab=tools#selector_regenerate_product_attributes_lookup_table'),
								'target' => '_blank',
								'rel' => 'noopener noreferrer',
							],
							__('Regenerate table manually', 'blocksy-companion')
							.
							'<svg width="8" height="8" fill="currentColor" viewBox="0 0 10 10"><path fill-rule="evenodd" d="M.341.738C.341.33.671 0 1.079 0h8.183C9.67 0 10 .33 10 .738V8.92a.738.738 0 1 1-1.475 0V2.52L1.259 9.784A.738.738 0 0 1 .216 8.74l7.265-7.266H1.08A.738.738 0 0 1 .34.738Z"></path></svg>'
						)
					)
				)
			);
	}

	public function get_terms_for_all_products($taxonomy = 'product_cat') {
		$terms = get_terms([
			'taxonomy' => $taxonomy,
			'hide_empty' => false
		]);

		$this->ignore_current_query = true;

		$terms_counts = $this->get_terms_counts(
			self::$prefix . $taxonomy,
			[
				'term_ids' => wp_list_pluck($terms, 'term_id'),
				'ignore_current_query' => true
			]
		);

		if (! empty($terms_counts)) {
			$terms = $this->get_terms_for($terms_counts);
		}

		return $terms;
	}

	public static function get_query_params() {
		$attributes = wc_get_attribute_taxonomies();

		if (! $attributes) {
			$attributes = [];
		}

		$attributes = array_map(function ($attribute) {
			return wc_attribute_taxonomy_name($attribute->attribute_name);
		}, $attributes);

		$taxonomies = array_values(array_diff(
			get_object_taxonomies('product'),
			array_merge(
				[
					"post_format",
					"product_type",
					"product_visibility",
					"product_shipping_class",
					"translation_priority"
				],
				$attributes
			)
		));

		if (! $taxonomies) {
			return [];
		}

		$prefixed_params = [];

		foreach ($taxonomies as $taxonomy) {
			$prefixed_params[] = self::$prefix . $taxonomy;
		}

		return $prefixed_params;
	}

	public function get_applied_filter_descriptor($param, $value) {
		$query_params = self::get_query_params();

		$maybe_taxonomy = null;

		foreach ($query_params as $query_param) {
			if ($query_param === $param) {
				$maybe_taxonomy = str_replace(self::$prefix, '', $query_param);
				break;
			}
		}

		if (! $maybe_taxonomy) {
			return null;
		}

		$term = get_term_by('id', $value, $maybe_taxonomy);

		if ($term) {
			$api_url = FiltersUtils::get_link_url(
				$this->get_filter_name($maybe_taxonomy),
				$term->term_id,
			);

			return [
				'name' => $term->name,
				'href' => esc_url($api_url)
			];
		}

		return null;
	}

	public function get_taxonomy_name($param = '') {
		if (empty($param)) {
			$param = $this->attributes['taxonomy'];
		}

		$filter_name = '';

		$param = str_replace(self::$prefix, '', $param);

		$labels = get_taxonomy_labels(get_taxonomy($param));

		if (isset($labels->singular_name)) {
			$filter_name = $labels->singular_name;
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

			$maybe_taxonomy_name = str_replace(self::$prefix, '', $param);

			if (! taxonomy_exists($maybe_taxonomy_name)) {
				continue;
			}

			$filter_name = $this->get_taxonomy_name($param);

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$values = explode(',', (string) $_GET[$param]);

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

	protected function get_terms_counts_sql($args = []) {
		global $wpdb;

		$args = wp_parse_args($args, [
			'product_ids' => [],
			'term_ids' => []
		]);

		$lookup_table = blc_get_ext('woocommerce-extra')
			->filters
			->lookup_table;

		if (! $lookup_table->check_lookup_table_exists()) {
			return '';
		}

		$product_ids = array_filter(array_map('absint', $args['product_ids']));

		if (empty($product_ids)) {
			return '';
		}

		$placeholders = implode(', ', $product_ids);

		$template = "
			SELECT term_id, COUNT(DISTINCT product_id) as term_count
			FROM {$lookup_table->get_table_name()}
			WHERE (
				product_id IN ($placeholders)
				AND
				taxonomy = %s
			)
			GROUP BY term_id
		";

		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		return $wpdb->prepare($template, $this->attributes['taxonomy']);
	}

	private function get_items() {
		$is_hierarchical = false;

		if ($this->attributes['viewType'] === 'list') {
			$is_hierarchical = $this->attributes['hierarchical'];
		}

		$terms_counts = $this->get_terms_counts(
			self::$prefix . $this->attributes['taxonomy'],
			[
				// phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
				'exclude' => $this->attributes['taxonomy_not_in']
			]
		);

		if (empty($terms_counts)) {
			return [];
		}

		$terms = $this->get_terms_for($terms_counts, [
			'is_hierarchical' => $is_hierarchical
		]);

		$list_items_html = [];

		foreach ($terms as $key => $value) {
			$list_items_html[] = self::get_taxonomy_item($value);
		}

		return $list_items_html;
	}

	private function get_item_label($term, $is_expandable, $products_count) {
		$label_text = $term->name;

		$label_html = '';

		if (! $this->attributes['showLabel']) {
			$label_text = '';
		}

		if (! empty($label_text)) {
			$label_html = blocksy_html_tag(
				'span',
				['class' => 'ct-filter-label'],
				$label_text
			);
		}

		if (
			$is_expandable
			&&
			$this->attributes['showCounters']
		) {
			$label_html = blocksy_html_tag(
				'span',
				['class' => 'ct-filter-label'],
				$label_text . $products_count
			);
		}

		return $label_html;
	}

	private function get_taxonomy_item($term) {
		$is_hierarchical = false;

		if ($this->attributes['viewType'] === 'list') {
			$is_hierarchical = $this->attributes['hierarchical'];
		}

		$is_expandable = $is_hierarchical ? $this->attributes['expandable'] : false;

		$api_url = FiltersUtils::get_link_url(
			$this->get_filter_name(),
			$term->term_id,
			[
				'is_multiple' => $this->attributes['multipleFilters']
			]
		);

		$tax_image = '';
		$maybe_image = '';

		if (
			$this->attributes['taxonomy'] === 'product_cat'
			||
			$this->attributes['taxonomy'] === 'product_brand'
		) {
			$maybe_image_id = get_term_meta($term->term_id, 'thumbnail_id', true);

			if ($maybe_image_id) {
				$maybe_image = [
					'attachment_id' => $maybe_image_id,
					'url' => wp_get_attachment_image_url($maybe_image_id, 'full')
				];
			}
		} else {
			$term_atts = get_term_meta(
				$term->term_id,
				'blocksy_taxonomy_meta_options'
			);

			if (empty($term_atts)) {
				$term_atts = [[]];
			}

			$term_atts = $term_atts[0];

			$maybe_image = blocksy_akg('icon_image', $term_atts, '');
		}

		if (
			$maybe_image
			&&
			is_array($maybe_image)
			&&
			isset($maybe_image['attachment_id'])
			&&
			function_exists('blocksy_media')
			&&
			(
				(
					$this->attributes['showItemsRendered']
					&&
					$this->attributes['taxonomy'] === 'product_brand'
				)
				||
				(
					$this->attributes['showTaxonomyImages']
					&&
					! in_array($this->attributes['taxonomy'], ['product_brand', 'product_tag'])
				)
			)
		) {
			$attachment_id = $maybe_image['attachment_id'];

			$inner_content = '';

			if ($this->attributes['showTooltips']) {
				$inner_content = blocksy_html_tag(
					'span',
					[
						'class' => 'ct-tooltip'
					],
					$term->name
				);
			}

			$tax_image = blocksy_media([
				'attachment_id' => $maybe_image['attachment_id'],
				'size' => 'medium',
				'ratio' => $this->attributes['aspectRatio'],
				'inner_content' => $inner_content
			]);
		}

		$checbox_html = '';

		if ($this->attributes['showCheckbox']) {
			$checkox_attr = [
				'type' => 'checkbox',
				'class' => 'ct-checkbox',
				'tabindex' => '-1',
				'name' => $this->attributes['taxonomy'] . '_' . $term->term_id,
				'aria-label' => $term->name,
			];

			if (
				FilterPresenter::is_filter_active(
					$this->get_filter_name(),
					$term->term_id
				)
			) {
				$checkox_attr['checked'] = 'checked';
			}

			$checbox_html = blocksy_html_tag('input', $checkox_attr);
		}

		$products_count = FilterPresenter::format_products_count([
			'count' => $term->count,
			'with_wrap' => $is_expandable && $this->attributes['showCounters']
		]);

		if (! $products_count) {
			return '';
		}

		$label_html = $this->get_item_label(
			$term,
			$is_expandable,
			$products_count
		);

		if (! $this->attributes['showCounters']) {
			$products_count = '';
		}

		$childrens_html = '';
		$expandable_triger = '';

		if ($is_hierarchical && ! empty($term->children)) {
			$childrens_items_html = [];

			$term_children = $term->children;

			foreach ($term_children as $key => $value) {
				$childrens_items_html[] = self::get_taxonomy_item(
					$value,
					$this->attributes
				);
			}

			$childrens_html = blocksy_html_tag(
				'ul',
				[
					'class' => 'ct-filter-children',
					'aria-hidden' => $this->attributes['defaultExpanded'] ? 'false' : 'true',
					'data-behaviour' => $is_expandable ? 'drop-down' : 'list',
				],
				implode('', $childrens_items_html)
			);

			if ($is_expandable) {
				$expandable_triger = blocksy_html_tag(
					'button',
					[
						'class' => 'ct-expandable-trigger',
						'aria-expanded' => $this->attributes['defaultExpanded']
							? 'true'
							: 'false',
						'aria-label' => $this->attributes['defaultExpanded']
							? __('Collapse', 'blocksy-companion')
							: __('Expand', 'blocksy-companion'),
						'data-icon' => 'arrow'
					],
					"<svg class='ct-icon' width='10' height='10' viewBox='0 0 25 25'><path d='M.207 17.829 12.511 5.525l1.768 1.768L1.975 19.596z'/><path d='m10.721 7.243 1.768-1.768L24.793 17.78l-1.768 1.767z'/></svg>"
				);
			}
		}

		if ($this->attributes['showCounters'] && empty($products_count)) {
			return '';
		}

		if ($is_expandable && $this->attributes['showCounters']) {
			$products_count = '';
		}

		$item_classes = ['ct-filter-item'];

		if (
			FilterPresenter::is_filter_active(
				$this->get_filter_name(),
				$term->term_id
			)
		) {
			$item_classes[] = 'active';
		}

		return blocksy_html_tag(
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
						'aria-label' => $term->name,
						'data-key' => $this->attributes['taxonomy'],
						'data-value' => $term->term_id,
					],
					$checbox_html .
					$tax_image .
					$label_html .
					$products_count
				) . $expandable_triger
			) . $childrens_html
		);
	}

	private function get_terms_for($terms_counts, $args = []) {
		$args = wp_parse_args($args, [
			'is_hierarchical' => true
		]);

		$all_terms = $this->get_all_terms_objects(
			$terms_counts,
			$this->attributes['taxonomy']
		);

		$all_term_ids = [];

		foreach ($all_terms as $term) {
			$all_term_ids[] = $term->term_id;
		}

		$root_id = 0;

		$is_taxonomy_page = $this->is_taxonomy_page();

		if ($is_taxonomy_page) {
			$queried_object = get_queried_object();

			if ($queried_object->taxonomy === $this->attributes['taxonomy']) {
				$root_id = $queried_object->term_id;
			}
		}

		if ($args['is_hierarchical']) {
			$term_hierarchy = $this->get_term_hierarchy($this->attributes['taxonomy']);

			$hierarchy_children_first = [];

			foreach ($term_hierarchy as $parent_id => $children) {
				foreach ($children as $child) {
					$hierarchy_children_first[$child] = $parent_id;
				}
			}

			$root_terms = [];

			foreach ($all_term_ids as $term_id) {
				$should_append = false;

				if (
					// Root shop
					$root_id === 0
					&&
					// Not a child of another term
					! isset($hierarchy_children_first[$term_id])
				) {
					$should_append = true;
				}

				if (
					// On some taxonomy page, need to display only children of the current term
					$root_id !== 0
					&&
					// Current scope has children
					isset($term_hierarchy[$root_id])
					&&
					// Current term is a child of the root term
					in_array($term_id, $term_hierarchy[$root_id])
				) {
					$should_append = true;
				}

				if ($should_append) {
					$root_terms[] = $term_id;
				}
			}
		}

		if (! $args['is_hierarchical']) {
			if ($root_id !== 0) {
				$root_terms = array_diff(
					$all_term_ids,
					[$root_id]
				);
			}

			if ($root_id === 0) {
				$root_terms = $all_term_ids;
			}
		}

		$terms_structure_with_ids = [];

		foreach ($root_terms as $root_term) {
			if (in_array($root_term, $this->attributes['taxonomy_not_in'])) {
				continue;
			}

			$children = [];

			if ($args['is_hierarchical']) {
				$children = $this->find_children($root_term, $term_hierarchy, $all_term_ids);
			}

			$terms_structure_with_ids[] = [
				'term_id' => $root_term,
				'children' => $children
			];
		}

		if (is_wp_error($all_terms)) {
			return [];
		}

		$terms_by_id = [];

		foreach ($all_terms as $term) {
			$term->count = $terms_counts[$term->term_id]->term_count;
			$terms_by_id[$term->term_id] = $term;
		}

		return $this->transform_terms_structure_with_ids_into_real(
			$terms_structure_with_ids,
			$terms_by_id
		);
	}

	private function transform_terms_structure_with_ids_into_real(
		$terms_structure_with_ids,
		$terms_by_id
	) {
		$terms_structure = [];

		foreach ($terms_structure_with_ids as $term) {
			if (! isset($terms_by_id[$term['term_id']])) {
				continue;
			}

			$term_obj = $terms_by_id[$term['term_id']];

			unset($terms_by_id[$term['term_id']]);

			$term_obj->children = $this->transform_terms_structure_with_ids_into_real(
				$term['children'],
				$terms_by_id
			);

			// Expose brand image for block preview.
			if ($this->ignore_current_query) {
				$maybe_image = '';

				if (
					$this->attributes['taxonomy'] === 'product_cat'
					||
					$this->attributes['taxonomy'] === 'product_brand'
				) {
					$maybe_image_id = get_term_meta($term['term_id'], 'thumbnail_id', true);

					if ($maybe_image_id) {
						$maybe_image = [
							'attachment_id' => $maybe_image_id,
							'url' => wp_get_attachment_image_url($maybe_image_id, 'full')
						];
					}
				} else {
					$term_atts = get_term_meta(
						$term['term_id'],
						'blocksy_taxonomy_meta_options'
					);

					if (empty($term_atts)) {
						$term_atts = [[]];
					}

					$term_atts = $term_atts[0];

					$maybe_image = blocksy_akg('icon_image', $term_atts, '');
				}

				$term_obj->tax_image = $maybe_image;
			}

			$term_obj->name = htmlspecialchars_decode($term_obj->name);

			$terms_structure[] = $term_obj;
		}

		return $terms_structure;
	}

	public function wp_query_arg($query_string, $query_args, $reason) {
		$layered_nav_chosen = $this->get_layered_nav($query_string);

		if (! empty($layered_nav_chosen)) {
			add_filter(
				'wpml_display_as_translated_tax_query_is_archive',
				[$this, 'wpml_display_as_translated_tax_query_is_archive']
			);
		}

		foreach ($layered_nav_chosen as $taxonomy => $data) {
			$query_args['tax_query'][] = array(
				'taxonomy' => $taxonomy,
				'field' => 'slug',
				'terms' => $data['terms'],
				'operator' => 'IN',
				'include_children' => true
			);
		}

		return $query_args;
	}

	public function wpml_display_as_translated_tax_query_is_archive($is_archive) {
		// After this query is done, we need to remove the filter
		add_filter('posts_request', [$this, 'posts_request'], 10, 2);
		return true;
	}

	public function posts_request($request, $query) {
		remove_filter(
			'wpml_display_as_translated_tax_query_is_archive',
			[$this, 'wpml_display_as_translated_tax_query_is_archive']
		);

		remove_filter('posts_request', [$this, 'posts_request']);

		return $request;
	}

	private function get_layered_nav($query_string) {
		$layered_nav_chosen = [];

		foreach ($query_string as $key => $value) {
			if (0 !== strpos($key, self::$prefix)) {
				continue;
			}

			$all_taxonomies = array_values(
				array_diff(
					get_object_taxonomies('product'),
					[
						"post_format",
						"product_type",
						"product_visibility",
						"product_shipping_class",
						"translation_priority"
					]
				)
			);

			$taxonomy = wc_sanitize_taxonomy_name(
				str_replace(self::$prefix, '', $key)
			);

			$filter_terms = [];

			if (! empty($value)) {
				$filter_terms = explode(',', wc_clean(wp_unslash($value)));
			}

			if (
				empty($filter_terms)
				||
				! taxonomy_exists($taxonomy)
				||
				! in_array($taxonomy, $all_taxonomies)
			) {
				continue;
			}

			$all_terms = [];

			foreach ($filter_terms as $term) {
				$term_obj = get_term_by('id', $term, $taxonomy);

				if (! $term_obj) {
					$term_obj = get_term_by('slug', $term, $taxonomy);
				}

				if ($term_obj) {
					$all_terms[] = $term_obj->slug;
				}
			}

			if (! empty($all_terms)) {
				if (! isset($layered_nav_chosen[$taxonomy])) {
					$layered_nav_chosen[$taxonomy] = [
						'terms' => [],
						'query_type' => 'or',
					];
				}

				$layered_nav_chosen[$taxonomy]['terms'] = $all_terms;
			}
		}

		return $layered_nav_chosen;
	}

	public function get_reset_url($attributes = []) {
		$filter_param = $this->get_filter_name($attributes['taxonomy']);
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$has_filter = blocksy_akg($filter_param, $_GET, '');

		if (! empty($has_filter)) {
			return remove_query_arg($filter_param);
		}

		return false;
	}

	private function get_term_hierarchy($taxonomy) {
		if (! is_taxonomy_hierarchical($taxonomy)) {
			return [];
		}

		$children = get_option("{$taxonomy}_children");

		if (is_array($children)) {
			return $children;
		}

		$children = [];

		$terms = get_terms([
			'taxonomy' => $taxonomy,
			'get' => 'all',
			'fields' => 'id=>parent',
			'update_term_meta_cache' => false,
		]);

		foreach ($terms as $term_id => $parent) {
			if ($parent > 0) {
				$children[$parent][] = $term_id;
			}
		}

		update_option("{$taxonomy}_children", $children);

		return $children;
	}

	private function find_children($term_id, $terms_hierarchy, $all_term_ids = []) {
		if (! isset($terms_hierarchy[$term_id])) {
			return [];
		}

		$children = [];

		foreach ($terms_hierarchy[$term_id] as $child_id) {
			if (in_array($child_id, $this->attributes['taxonomy_not_in'])) {
				continue;
			}

			$child = [
				'term_id' => $child_id,
				'children' => $this->find_children($child_id, $terms_hierarchy, $all_term_ids)
			];

			$children[$child_id] = $child;
		}

		$result = [];

		foreach ($all_term_ids as $term_id) {
			if (isset($children[$term_id])) {
				$result[] = $children[$term_id];
			}
		}

		return $result;
	}

	// Just calling get_terms() on all the IDs is not an option because we need
	// to specifically get only the term in the current language, for plugins
	// like WPML to pick it up.
	private function get_all_terms_objects($terms_counts, $taxonomy) {
		$terms_ids = array_keys($terms_counts);

		$terms_ids = [];

		foreach ($terms_counts as $term_id => $term_count) {
			$terms_ids[] = apply_filters(
				'wpml_object_id',
				$term_id,
				$taxonomy,

				// Make sure to return original if missing, in every case
				true
			);
		}

		$all_terms = get_terms([
			'taxonomy' => $taxonomy,
			'hide_empty' => false,
			'include' => $terms_ids,
		]);

		if (is_wp_error($all_terms)) {
			return [];
		}

		return $all_terms;
	}
}
