<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class AttributesLayer {
	public function __construct() {
		add_filter(
			'blocksy_woo_single_options_layers:defaults',
			[$this, 'add_layer_to_default_layout']
		);
		add_filter(
			'blocksy_woo_single_right_options_layers:defaults',
			[$this, 'add_layer_to_default_layout']
		);

		add_filter(
			'blocksy_woo_single_options_layers:extra',
			[$this, 'add_single_layer_options']
		);
		add_filter(
			'blocksy_woo_single_right_options_layers:extra',
			[$this, 'add_single_layer_options']
		);

		add_action(
			'blocksy:woocommerce:product:custom:layer',
			[$this, 'product_single_render']
		);

		add_action(
			'wp_enqueue_scripts',
			function () {
				if (!function_exists('get_plugin_data')) {
					require_once ABSPATH . 'wp-admin/includes/plugin.php';
				}

				$data = get_plugin_data(BLOCKSY__FILE__);

				wp_register_style(
					'blocksy-ext-woocommerce-extra-attributes-layer-styles',
					BLOCKSY_URL .
						'framework/premium/extensions/woocommerce-extra/static/bundle/attributes-layer.min.css',
					['blocksy-ext-woocommerce-extra-styles'],
					$data['Version']
				);

				if (blc_theme_functions()->blocksy_manager()) {
					blc_theme_functions()->blocksy_manager()->screen->on_product_shortcode_rendered(function ($tag) {
						if (
							$tag === 'product_page'
							&&
							function_exists('blocksy_has_product_specific_layer')
							&&
							blocksy_has_product_specific_layer('product_attributes', [
								'respect_post_type' => false
							])
						) {
							wp_enqueue_style(
								'blocksy-ext-woocommerce-extra-attributes-layer-styles'
							);
						}
					});
				}

				global $post;
				$product = null;

				if (
					$post
					&&
					$post instanceof \WP_Post
				) {
					$product = wc_get_product($post->ID);
				}

				if (
					is_admin()
					||
					(
						function_exists('is_woocommerce')
						&&
						! is_woocommerce()
					)
					||
					(
						function_exists('blocksy_has_product_specific_layer')
						&&
						! blocksy_has_product_specific_layer('product_attributes', [
							'respect_post_type' => false
						])
						&&
						! is_customize_preview()
					)
				) {
					return;
				}

				wp_enqueue_style(
					'blocksy-ext-woocommerce-extra-attributes-layer-styles'
				);
			}
		);
	}

	public function add_layer_to_default_layout($opt) {
		$opt = array_merge(
			$opt,
			[
				[
					'id' => 'product_attributes',
					'enabled' => false,
				]
			]
		);

		return $opt;
	}

	public function add_single_layer_options($opt) {
		$opt = array_merge(
			$opt,
			[
				'product_attributes' => [
					'label' => __('Attributes Table', 'blocksy-companion'),
					'options' => [

						'spacing' => [
							'label' => __('Bottom Spacing', 'blocksy-companion'),
							'type' => 'ct-slider',
							'min' => 0,
							'max' => 100,
							'value' => 10,
							'responsive' => true,
							'sync' => [
								'id' => 'woo_card_layout_skip'
							]
						],
					]
				],
			]
		);

		return $opt;
	}

	public function product_single_render($layer) {
		if ($layer['id'] !== 'product_attributes') {
			return;
		}

		global $product;

		$rows_html = [];

		foreach ($product->get_attributes() as $taxonomy => $value) {
			if (!$value['visible']) {
				continue;
			}

			$columns = [];

			$tax_label = $value['name'];
			$values = [];

			if (taxonomy_exists($value['name'])) {
				$taxonomy = get_taxonomy($value['name']);
				$is_public = $taxonomy->public;
				$tax_label = $taxonomy->labels->singular_name;

				foreach ($value['options'] as $term_id) {
					$term = get_term_by('id', $term_id, $value['name']);

					if ($term && !is_wp_error($term)) {
						if ($is_public) {
							$values[] = blocksy_html_tag(
								'a',
								[
									'href' => get_term_link($term),
									'class' => 'ct-product-attribute-link'
								],
								esc_html($term->name)
							);

							continue;
						}

						$values[] = esc_html($term->name);
					}
				}
			} else {
				$values = $value['options'];
			}

			if (empty($values)) {
				continue;
			}

			$rows_html[] = blocksy_html_tag(
				'div',
				[
					'class' => 'ct-product-attribute'
				],
				blocksy_html_tag(
					'div',
					[
						'class' => 'ct-attributes-label'
					],
					esc_html($tax_label)
				) .
					blocksy_html_tag(
						'div',
						[
							'class' => 'ct-attributes-value'
						],
						join(', ', $values)
					)
			);
		}

		if (empty($rows_html)) {
			return;
		}

		blocksy_html_tag_e(
			'div',
			[
				'class' => 'ct-product-attributes',
			],
			join('', $rows_html)
		);
	}
}

