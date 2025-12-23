<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class ProductSaleCountdown {
	public function __construct() {
		add_action(
			'wp_enqueue_scripts',
			function () {
				if (!function_exists('get_plugin_data')) {
					require_once ABSPATH . 'wp-admin/includes/plugin.php';
				}

				$data = get_plugin_data(BLOCKSY__FILE__);

				if (
					is_admin()
					||
					! is_singular('product')
					||
					(
						function_exists('blocksy_has_product_specific_layer')
						&&
						! blocksy_has_product_specific_layer('product_countdown')
						&&
						! is_customize_preview()
					)
				) {
					return;
				}

				$product_data = self::need_to_show();

				if (
					! $product_data
					||
					(
						isset($product_data['need_to_show'])
						&&
						! $product_data['need_to_show']
					)
				) {
					return;
				}

				wp_enqueue_style(
					'blocksy-ext-woocommerce-extra-sale-countdown-styles',
					BLOCKSY_URL .
						'framework/premium/extensions/woocommerce-extra/static/bundle/sale-countdown.min.css',
					['blocksy-ext-woocommerce-extra-styles'],
					$data['Version']
				);
			},
			50
		);

		add_filter(
			'woocommerce_available_variation',
			function ($result, $product, $variation) {
				if (!blocksy_woocommerce_has_flexy_view()) {
					return $result;
				}

				if ($variation->is_on_sale()) {
					$result[
						'date_on_sale_to'
					] = $variation->get_date_on_sale_to();
				}

				return $result;
			},
			10,
			3
		);

		add_filter('blocksy_woo_single_options_layers:defaults', [
			$this,
			'register_layer_countdown_defaults',
		]);

		add_filter('blocksy_woo_single_options_layers:extra', [
			$this,
			'register_layer_options',
		]);

		add_action('blocksy:woocommerce:product:custom:layer', [
			$this,
			'render_layer',
		]);

		add_filter('blocksy:frontend:dynamic-js-chunks', function ($chunks) {
			if (!class_exists('WC_AJAX')) {
				return $chunks;
			}

			$global_data = [
				[
					'var' => 'blc_woo_extra_product_sale_countdown',
					'data' => [
						'days_label' => esc_attr__('Days', 'blocksy-companion'),
						'hours_label' => esc_attr__(
							'Hours',
							'blocksy-companion'
						),
						'min_label' => esc_attr__('Min', 'blocksy-companion'),
						'sec_label' => esc_attr__('Sec', 'blocksy-companion'),
					],
				],
			];

			$chunks[] = [
				'id' => 'blocksy_ext_woo_extra_countdown',
				'selector' => '.product .ct-product-sale-countdown',
				'trigger' => [
					[
						'trigger' => 'slight-mousemove',
						'selector' =>
							'.product .ct-product-sale-countdown [data-date]',
					],

					[
						'selector' => '.ct-product-sale-countdown',
						'trigger' => 'jquery-event',
						'events' => [
							'found_variation',
							'reset_data'
						],
					],
				],
				'url' => blocksy_cdn_url(
					BLOCKSY_URL .
						'framework/premium/extensions/woocommerce-extra/static/bundle/product-sale-countdown.js'
				),
				'global_data' => $global_data,
			];

			return $chunks;
		});
	}

	public static function need_to_show() {
		static $cache_current_product_data = null;

		if ($cache_current_product_data !== null) {
			return $cache_current_product_data;
		}

		global $post;
		$product = wc_get_product($post);

		$date = null;

		if ($product->is_on_sale()) {
			$date = $product->get_date_on_sale_to();
		}

		$need_to_show = !!$date;

		if ($product->is_type('variable')) {
			$maybe_current_variation = null;

			if (blc_theme_functions()->blocksy_manager()) {
				$maybe_current_variation = blc_theme_functions()->blocksy_manager()
					->woocommerce
					->retrieve_product_default_variation($product);
			}

			if ($maybe_current_variation) {
				if ($maybe_current_variation->is_on_sale()) {
					$date = $maybe_current_variation->get_date_on_sale_to();
				}
			}

			// TODO: this is a potential performance issue
			// If large setups will need this, we will need to find a
			// faster way to query only relevant variations.
			$all_variations = $product->get_available_variations();

			foreach ($all_variations as $variation) {
				if (! isset($variation['date_on_sale_to'])) {
					continue;
				}

				$date_on_sale_to = $variation[
					'date_on_sale_to'
				]->__toString();

				if (
					$date_on_sale_to
					&&
					strtotime($date_on_sale_to) >= time()
				) {
					$need_to_show = true;
					break;
				}
			}
		}

		$cache_current_product_data = [
			'need_to_show' => $need_to_show,
			'date' => $date,
		];

		return $cache_current_product_data;
	}

	public function render_layer($layer) {
		if ($layer['id'] === 'product_countdown') {

			$product_data = self::need_to_show();

			if (
				! $product_data
				||
				(
					isset($product_data['need_to_show'])
					&&
					! $product_data['need_to_show']
				)
			) {
				return;
			}

			$date = $product_data['date'];

			$countdown = '';

			if ($date) {
				$now = time();
				$date_time = strtotime($date);
				$diff = $date_time - $now;

				$days = floor($diff / (60 * 60 * 24));
				$hours = floor(($diff % ( 60 * 60 * 24)) / (60 * 60));
				$minutes = floor(($diff % (60 * 60)) / (60));
				$seconds = floor($diff % 60);

				$countdown = blocksy_html_tag(
					'div',
					[
						'data-date' => $date,
					],
					blocksy_html_tag(
						'span',
						[],
						blocksy_html_tag(
							'b',
							[],
							str_pad($days, 2, '0', STR_PAD_LEFT)
						) .
							blocksy_html_tag(
								'small',
								[],
								__('Days', 'blocksy-companion')
							)
					) .
						blocksy_html_tag(
							'span',
							[],
							blocksy_html_tag(
								'b',
								[],
								str_pad($hours, 2, '0', STR_PAD_LEFT)
							) .
								blocksy_html_tag(
									'small',
									[],
									__('Hours', 'blocksy-companion')
								)
						) .
						blocksy_html_tag(
							'span',
							[],
							blocksy_html_tag(
								'b',
								[],
								str_pad($minutes, 2, '0', STR_PAD_LEFT)
							) .
								blocksy_html_tag(
									'small',
									[],
									__('Min', 'blocksy-companion')
								)
						) .
						blocksy_html_tag(
							'span',
							[],
							blocksy_html_tag(
								'b',
								[],
								str_pad($seconds, 2, '0', STR_PAD_LEFT)
							) .
								blocksy_html_tag(
									'small',
									[],
									__('Sec', 'blocksy-companion')
								)
						)
				);
			}

			$section_title = blocksy_html_tag(
				'span',
				[
					'class' => 'ct-module-title'
				],
				blocksy_akg(
					'product_countdown_title',
					$layer,
					__('Hurry up! This sale ends in', 'blocksy-companion')
				)
			);

			blocksy_html_tag_e(
				'div',
				[
					'class' => 'ct-product-sale-countdown',
				],
				$section_title . $countdown
			);
		}
	}

	public function register_layer_countdown_defaults($opt) {
		return array_merge($opt, [
			[
				'id' => 'product_countdown',
				'enabled' => false,
			],
		]);
	}

	public function register_layer_options($opt) {
		return array_merge($opt, [
			'product_countdown' => [
				'label' => __('Countdown Box', 'blocksy-companion'),
				'options' => [
					'product_countdown_title' => [
						'label' => __('Title', 'blocksy-companion'),
						'type' => 'text',
						'design' => 'block',
						'value' => __(
							'Hurry up! This sale ends in',
							'blocksy-companion'
						),
						'sync' => [
							'id' => 'woo_single_layout_skip',
						],
					],
					'spacing' => [
						'label' => __('Bottom Spacing', 'blocksy-companion'),
						'type' => 'ct-slider',
						'min' => 0,
						'max' => 100,
						'value' => 35,
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
