<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class FloatingCart {
	public function get_dynamic_styles_data($args) {
		return [
			'path' => dirname(__FILE__) . '/dynamic-styles.php'
		];
	}

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
				) {
					return;
				}

				wp_enqueue_style(
					'blocksy-ext-woocommerce-extra-floating-cart-styles',
					BLOCKSY_URL .
						'framework/premium/extensions/woocommerce-extra/static/bundle/floating-bar.min.css',
					['blocksy-ext-woocommerce-extra-styles'],
					$data['Version']
				);
			},
			50
		);

		add_filter(
			'blocksy_single_product_floating_cart',
			function ($opts) {
				$opts['has_floating_bar'] = blocksy_get_options(
					dirname(__FILE__) . '/options.php',
					[],
					false
				);

				return $opts;
			}
		);

		add_filter(
			'blocksy:footer:offcanvas-drawer',
			function ($els, $payload) {
				$position = blc_theme_functions()->blocksy_get_theme_mod(
					'floating_bar_position',
					'top'
				);

				if (
					$position === 'top' && $payload['location'] !== 'start'
					||
					$position === 'bottom' && $payload['location'] !== 'end'
				) {
					return $els;
				}

				if (! $this->has_floating_cart()) {
					return $els;
				}

				$view = blocksy_render_view(
					dirname(__FILE__) . '/view.php',
					[]
				);

				if (! $view) {
					return $els;
				}

				if ($payload['location'] === 'start') {
					$els[] = $view;
				}

				if ($payload['location'] === 'end') {
					$els[] = [
						'attr' => [
							'data-floating-bar' => 'no'
						],
						'content' => $view,
					];
				}

				return $els;
			},
			5,
			2
		);

		add_filter('blocksy:frontend:dynamic-js-chunks', function ($chunks) {
			if (!class_exists('WC_AJAX')) {
				return $chunks;
			}

			$chunks[] = [
				'id' => 'blocksy_ext_woo_extra_floating_cart',
				'selector' => '.ct-floating-bar',
				'url' => blocksy_cdn_url(
					BLOCKSY_URL . 'framework/premium/extensions/woocommerce-extra/static/bundle/floating-cart.js'
				),
				'trigger' => [
					[
						'selector' => join(', ', [
							'.ct-floating-bar .quantity .qty',
							'.ct-cart-actions .quantity .qty'
						]),
						'trigger' => 'dom-event',
						'events' => [
							'input',
						],
					],
					[
						'selector' => '.ct-floating-bar',
						'trigger' => 'intersection-observer',
					]
				],
				'position' => 'bottom',
				'target' => '.single-product #main-container .single_add_to_cart_button'

			];

			return $chunks;
		});
	}

	public function has_floating_cart() {
		if (! function_exists('is_woocommerce')) {
			return false;
		}

		global $product;

		global $post;

		if (is_string($product)) {
			$product = wc_get_product();
		}

		if (! blc_theme_functions()->blocksy_manager()) {
			return false;
		}

		if (! blc_theme_functions()->blocksy_manager()->screen->is_product()) {
			return false;
		}

		if (
			! $product && $post
			||
			intval($product->get_id()) !== intval($post->ID)
		) {
			$product = wc_get_product($post->ID);
		}

		if (! $product) {
			return false;
		}

		if (
			(
				! $product->is_purchasable() || ! $product->is_in_stock()
			) && ! $product->is_type('external')
		) {
			return false;
		}

		return $product;
	}
}

