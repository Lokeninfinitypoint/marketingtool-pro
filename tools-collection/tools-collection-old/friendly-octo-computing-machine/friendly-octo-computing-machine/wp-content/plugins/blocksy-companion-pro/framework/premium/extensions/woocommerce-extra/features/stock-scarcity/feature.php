<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class StockScarcity {
	public function get_dynamic_styles_data($args) {
		return [
			'path' => dirname(__FILE__) . '/dynamic-styles.php'
		];
	}

	public function __construct() {
		add_filter('blocksy_woo_single_options_layers:defaults', [
			$this,
			'register_layer_defaults',
		]);

		add_filter('blocksy_woo_single_right_options_layers:defaults', [
			$this,
			'register_layer_defaults',
		]);

		add_filter('blocksy_woo_single_options_layers:extra', [
			$this,
			'register_layer_options',
		]);

		add_action('blocksy:woocommerce:product:custom:layer', [
			$this,
			'render_layer',
		]);

		add_filter('blocksy_woo_card_options_layers:defaults', [
			$this,
			'register_layer_defaults',
		]);

		add_filter('blocksy_woo_card_options_layers:extra', [
			$this,
			'register_card_layer_options',
		]);

		add_action('blocksy:woocommerce:product-card:custom:layer', [
			$this,
			'render_layer',
		]);

		add_filter('blocksy:frontend:dynamic-js-chunks', function ($chunks) {
			if (!class_exists('WC_AJAX')) {
				return $chunks;
			}

			$chunks[] = [
				'id' => 'blocksy_ext_woo_extra_stock_scarcity',
				'selector' => '.product .ct-product-stock-scarcity',
				'trigger' => [
					[
						'trigger' => 'jquery-event',
						'events' => [
							'found_variation',
							'reset_data'
						],
						'selector' => '.product .ct-product-stock-scarcity',
					],
				],
				'url' => blocksy_cdn_url(
					BLOCKSY_URL .
						'framework/premium/extensions/woocommerce-extra/static/bundle/product-stock-scarcity.js'
				)
			];

			return $chunks;
		});

		add_filter(
			'blocksy_customizer_options:woocommerce:general:end',
			function ($opts) {
				$opts['has_stock_scarcity_panel'] = blocksy_get_options(
					dirname(__FILE__) . '/options.php',
					[],
					false
				);

				return $opts;
			},
			57
		);

		add_filter(
			'woocommerce_available_variation',
			function ($result, $product, $variation) {
				$stock_quantity_min = blc_theme_functions()->blocksy_get_theme_mod(
					'product_stock_scarcity_min',
					50
				);

				$result['blocksy_low_stock_amount'] = $stock_quantity_min;

				if (! empty($variation->get_low_stock_amount())) {
					$result['blocksy_low_stock_amount'] = wc_get_low_stock_amount($variation);
				}

				return $result;
			},
			10, 3
		);
	}

	public function render_layer($layer) {

		if ($layer['id'] === 'product_stock_scarcity') {
			global $product;

			$stock_quantity = 0;
			$need_to_show = false;

			$stock_quantity_min = blc_theme_functions()->blocksy_get_theme_mod(
				'product_stock_scarcity_min',
				50
			);

			if (! empty($product->get_low_stock_amount())) {
				$stock_quantity_min = wc_get_low_stock_amount($product);
			}

			if ($product->get_manage_stock()) {
				$stock_quantity = max(0, $product->get_stock_quantity());

				$need_to_show = !!$stock_quantity && $stock_quantity < $stock_quantity_min;
			}

			if ($product->is_type('variable')) {
				$maybe_current_variation = null;

				if (blc_theme_functions()->blocksy_manager()) {
					$maybe_current_variation = blocksy_manager()
						->woocommerce
						->retrieve_product_default_variation($product);
				}

				if ($maybe_current_variation) {
					if ($maybe_current_variation->get_manage_stock()) {
						$stock_quantity = max(0, $maybe_current_variation->get_stock_quantity());

						if (! empty($maybe_current_variation->get_low_stock_amount())) {
							$stock_quantity_min = $maybe_current_variation->get_low_stock_amount();
						}

						$need_to_show = !!$stock_quantity && $stock_quantity < $stock_quantity_min;
					} else {
						$stock_quantity = 0;
						$stock_quantity_min = 0;
					}
				}

				// TODO: this is a potential performance issue
				// If large setups will need this, we will need to find a
				// faster way to query only relevant variations.
				$all_variations = $product->get_available_variations();

				foreach ($all_variations as $variation) {
					if (
						! isset($variation['max_qty'])
						||
						! $variation['max_qty']
					) {
						continue;
					}

					$need_to_show = !!$variation['max_qty'] && $variation['max_qty'] < $stock_quantity_min;

					if ($need_to_show) {
						break;
					}
				}
			}

			if (! $need_to_show) {
				return;
			}

			if (! $stock_quantity_min) {
				$stock_quantity_min = 1;
			}

			$percent = min(
				100,
				($stock_quantity / $stock_quantity_min) * 100
			);

			$message = str_replace(
				'{items}',
				'<span class="ct-stock-quantity">' . $stock_quantity . '</span>',
				blc_theme_functions()->blocksy_get_theme_mod(
					'product_stock_scarcity_title',
					__(
						'🚨 Hurry up! Only {items} units left in stock!',
						'blocksy-companion'
					)
				)
			);

			$message_html = blocksy_html_tag(
				'div',
				[
					'class' => 'ct-message',
				],
				$message
			);

			$bar_html = blocksy_html_tag(
				'div',
				[
					'class' => 'ct-progress-bar',
				],
				blocksy_html_tag(
					'span',
					[
						'style' => 'width: ' . $percent . '%',
					],
					''
				)
			);

			blocksy_html_tag_e(
				'div',
				array_merge(
					[
						'class' => 'ct-product-stock-scarcity',
						'data-items' => $stock_quantity
					],
					(
						$percent >= 100
						||
						$percent === 0
					) ? [
						'hidden' => 'hidden',
					] : []
				),
				$message_html . $bar_html
			);
		}
	}

	public function register_layer_defaults($opt) {
		return array_merge($opt, [
			[
				'id' => 'product_stock_scarcity',
				'enabled' => false,
			],
		]);
	}

	public function register_card_layer_options($opt) {
		return array_merge($opt, [
			'product_stock_scarcity' => [
				'label' => __('Stock Scarcity', 'blocksy-companion'),
				'options' => [
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
		]);

		return $opt;
	}

	public function register_layer_options($opt) {
		return array_merge($opt, [
			'product_stock_scarcity' => [
				'label' => __('Stock Scarcity', 'blocksy-companion'),
				'options' => [
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
		]);

		return $opt;
	}
}
