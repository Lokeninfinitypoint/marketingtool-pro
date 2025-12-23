<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class FiltersBlock {
	public function __construct() {
		add_action('init', [$this, 'register_block']);
		add_action('enqueue_block_editor_assets', [$this, 'enqueue_admin']);
		add_action('wp_ajax_blc_ext_filters_get_block_data', [
			$this,
			'get_block_data',
		]);
	}

	private static function get_default_attribute() {
		$attribute_taxonomies = wc_get_attribute_taxonomies();

		$initial_attribute = null;

		if (sizeof($attribute_taxonomies)) {
			$initial_attribute = reset($attribute_taxonomies)->attribute_name;
		}

		if (
			isset($attributes['taxonomy'])
			&&
			$attributes['taxonomy'] !== null
		) {
			$maybe_taxonomy_name = str_replace('filter_', '', $attributes['taxonomy']);

			if (taxonomy_exists(wc_attribute_taxonomy_name($maybe_taxonomy_name))) {
				$initial_attribute = $maybe_taxonomy_name;
			}
		}

		return $initial_attribute;
	}

	public function register_block() {
		register_block_type('blocksy/woocommerce-filters', [
			'render_callback' => function ($attributes, $content, $block) {
				if (
					! is_woocommerce()
					&&
					! wp_doing_ajax()
					||
					(
						is_singular()
						&&
						! is_archive()
					)
				) {
					return '';
				}

				$attributes = wp_parse_args($attributes, [
					'type' => 'categories',
					'viewType' => 'list',
					'attribute' => -1,
					'showCounters' => false,
					'attribute' => self::get_default_attribute(),
					'taxonomy' => 'product_cat',
					'showLabel' => true,
					'multipleFilters' => true,
					'hierarchical' => false,
					'showResetButton' => false,
					'showCheckbox' => true,
					'showSearch' => false,
					'searchPlaceholder' => '',
					'showAttributesCheckbox' => false,
					'showItemsRendered' => true,
					'showTaxonomyImages' => false,
					'expandable' => false,
					'defaultExpanded' => true,
					'logoMaxW' => 40,
					'useFrame' => false,
					'aspectRatio' => '16/9',
					'excludeTaxonomy' => false,
					'taxonomy_not_in' => [],
					'limitHeight' => false,
					'limitHeightValue' => 400,
					'showTooltips' => false,
					'imageFit' => 'contain',
				]);

				// migrate to native brands
				if ($attributes['taxonomy'] === 'product_brands') {
					$attributes['taxonomy'] = 'product_brand';
				}

				if ($attributes['type'] === 'brands') {
					$attributes['type'] = 'categories';
					$attributes['taxonomy'] = 'product_brand';
				}

				$filter = Filters::get_filter_instance('taxonomies_filter');

				if ($attributes['type'] === 'attributes') {
					$filter = Filters::get_filter_instance('attributes_filter');
				}

				if (! $filter) {
					return '';
				}

				$presenter = new FilterPresenter($filter);
				return $presenter->render($attributes);
			},
		]);
	}

	public function get_block_data() {
		$body = json_decode(file_get_contents('php://input'), true);

		if (! $body || ! isset($body['type'])) {
			wp_send_json_error();
		}

		if ($body['type'] === 'categories' && ! isset($body['taxonomy'])) {
			wp_send_json_error();
		}

		if ($body['type'] === 'attributes' && ! isset($body['attribute'])) {
			wp_send_json_error();
		}

		$terms = [];
		$can_display_preview = true;

		if ($body['type'] === 'categories') {
			$filter = Filters::get_filter_instance('taxonomies_filter');

			$filter->attributes = [
				'taxonomy' => $body['taxonomy'],
				'taxonomy_not_in' => [],
			];

			$lookup_table = blc_get_ext('woocommerce-extra')
				->filters
				->lookup_table;

			if ($lookup_table->can_use_lookup_table()) {
				$terms = $filter->get_terms_for_all_products($body['taxonomy']);
			} else {
				$last_product_id = $lookup_table->get_last_existing_product_id();

				if ($last_product_id) {
					$can_display_preview = false;
				}
			}
		}

		if ($body['type'] === 'attributes') {
			$filter = Filters::get_filter_instance('attributes_filter');

			$filter->attributes = [
				'attribute' => $body['attribute'],
				'taxonomy_not_in' => [],
				'excludeTaxonomy' => false,
			];

			$result = $filter->get_attributes_counts(
				$filter->filter_get_terms_list($body['attribute']),
				[
					'ignore_current_query' => true
				]
			);

			$terms = [];

			foreach ($result as $term) {
				$term_as_array = (array) $term;

				$term_as_array['meta'] = blocksy_get_taxonomy_options(
					$term->term_id
				);

				$term_as_array['name'] = htmlspecialchars_decode($term->name);

				$terms[] = $term_as_array;
			}
		}

		if ($body['type'] === 'status') {
			$filter = Filters::get_filter_instance('status_filter');

			wp_send_json_success([
				'status_counts' => $filter->get_status_counts(),
				'status_labels' => $filter::get_status_options(),
			]);
		}

		if ($body['type'] === 'price') {
			wp_send_json_success([
				'currency_position' => get_option('woocommerce_currency_pos'),
				'currency_symbol' => html_entity_decode(get_woocommerce_currency_symbol())
			]);
		}

		$all_taxonomies = blc_get_ext('woocommerce-extra')
			->utils
			->get_product_taxonomies();

		$product_taxonomies = [];

		foreach ($all_taxonomies as $taxonomy) {
			$labels = get_taxonomy_labels(get_taxonomy($taxonomy));

			$product_taxonomies[$taxonomy] = [
				'taxonomy' => $taxonomy,
				'name' => $labels->singular_name,
				'labels' => $labels,
				'is_taxonomy_hierarchical' => is_taxonomy_hierarchical(
					$taxonomy
				),
			];
		}

		$storage = new Storage();
		$settings = $storage->get_settings();

		$conf = new SwatchesConfig();

		$attribute_taxonomies = wc_get_attribute_taxonomies();

		foreach ($attribute_taxonomies as $key => $attributes_tax) {
			$attribute_taxonomies[$key] = array_merge(
				(array) $attributes_tax,
				[
					'type' => $conf->get_attribute_type(
						$attributes_tax->attribute_name
					),
				]
			);
		}

		wp_send_json_success([
			'terms' => $terms,
			'can_display_preview' => $can_display_preview,

			'attributes_tax' => $attribute_taxonomies,
			'product_taxonomies' => $product_taxonomies,

			'ct_color_swatch_shape' => blc_theme_functions()->blocksy_get_theme_mod(
				'color_swatch_shape',
				'round'
			),
			'ct_image_swatch_shape' => blc_theme_functions()->blocksy_get_theme_mod(
				'image_swatch_shape',
				'round'
			),
			'ct_button_swatch_shape' => blc_theme_functions()->blocksy_get_theme_mod(
				'button_swatch_shape',
				'round'
			),
			'ct_mixed_swatch_shape' => blc_theme_functions()->blocksy_get_theme_mod(
				'mixed_swatch_shape',
				'round'
			),

			'has_swatches' => !! $settings['features']['variation-swatches'],
		]);
	}

	public function enqueue_admin() {
		$data = get_plugin_data(BLOCKSY__FILE__);

		$deps = [
			'wp-blocks',
			'wp-element',
			'wp-block-editor'
		];

		global $wp_customize;

		if ($wp_customize) {
			$deps[] = 'ct-customizer-controls';
		} else {
			$deps[] = 'ct-options-scripts';
		}

		wp_enqueue_script(
			'blocksy/woocommerce-filters',
			BLOCKSY_URL .
				'framework/premium/extensions/woocommerce-extra/static/bundle/woocommerce-filters.js',
			$deps,
			$data['Version'],
			false
		);
	}
}
