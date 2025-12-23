<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class SKULayer {
	public function __construct() {
		add_action('blocksy:woocommerce:product-card:custom:layer', [
			$this,
			'render_layer',
		]);

		add_filter('blocksy_woo_card_options_layers:defaults', [
			$this,
			'register_layer_content_block_defaults',
		]);

		add_filter('blocksy_woo_card_options_layers:extra', [
			$this,
			'register_layer_options',
		]);
	}

	public function render_layer($layer, $classes = 'ct-product-sku') {
		if ($layer['id'] === 'product_sku') {
			global $product;
			$product_sku = '';

			if (! $product) {
				return '';
			}

			if ( $product->get_sku() ) {
				$product_sku = $product->get_sku();
			}

			if (! empty($product_sku)) {
				blocksy_html_tag_e(
					'div',
					[
						'class' => $classes,
					],
					$product_sku
				);
			}
		}
	}

	public function register_layer_content_block_defaults($opt) {
		return array_merge($opt, [
			[
				'id' => 'product_sku',
				'enabled' => false,
			],
		]);
	}

	public function register_layer_options($opt) {
		return array_merge($opt, [
			'product_sku' => [
				'label' => __('SKU', 'blocksy-companion'),
				'options' => [
					[
						'spacing' => [
							'label' => __('Bottom Spacing', 'blocksy-companion'),
							'type' => 'ct-slider',
							'min' => 0,
							'max' => 100,
							'value' => 10,
							'responsive' => true,
							'sync' => [
								'id' => 'woo_single_layout_skip',
							],
						],
					],
				],
			]
		]);
	}
}

