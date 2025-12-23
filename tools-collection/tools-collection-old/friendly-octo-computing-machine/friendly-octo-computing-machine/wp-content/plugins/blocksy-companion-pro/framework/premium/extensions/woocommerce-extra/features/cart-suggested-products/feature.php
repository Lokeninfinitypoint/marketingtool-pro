<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class CartSuggestedProducts {
	public function get_dynamic_styles_data($args) {
		return [
			'path' => dirname(__FILE__) . '/dynamic-styles.php'
		];
	}

	public function __construct() {
		add_action('wp', function () {
			$prefixes = [
				'cart_popup_suggested',
				'mini_cart_suggested',
				'checkout_suggested'
			];

			$need_to_track = false;

			foreach ($prefixes as $prefix) {
				if (
					blc_theme_functions()->blocksy_get_theme_mod($prefix . '_products', 'yes') === 'yes'
					&&
					blc_theme_functions()->blocksy_get_theme_mod($prefix . '_products_source', 'related') === 'recent'
				) {
					$need_to_track = true;
					break;
				}
			}

			if (! $need_to_track) {
				return;
			}

			\Blocksy\Plugin::instance()
				->premium
				->recently_viewed_products
				->start_tracking();
		});

		add_filter('blocksy:frontend:dynamic-js-chunks', function ($chunks) {
			if (
				blc_theme_functions()->blocksy_get_theme_mod('checkout_suggested_products', 'yes') !== 'yes'
				&&
				blc_theme_functions()->blocksy_get_theme_mod('cart_suggested_products', 'yes') !== 'yes'
			) {
				return $chunks;
			}

			$chunks[] = [
				'id' => 'blocksy_ext_woo_extra_suggested_products',
				'url' => blocksy_cdn_url(
					BLOCKSY_URL .
					'framework/premium/extensions/woocommerce-extra/static/bundle/update-suggested-products.js'
				),
				'trigger' => [
					'trigger' => 'jquery-event',
					'matchTarget' => false,
					'events' => [
						'added_to_cart',
						'updated_checkout',
					]
				],
				'selector' => 'body',
			];

			return $chunks;
		});

		add_filter('blocksy:general:ct-scripts-localizations', function($data) {
			if (! function_exists('get_plugin_data')) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			$plugin_data = get_plugin_data(BLOCKSY__FILE__);

			$render = new \Blocksy_Header_Builder_Render();

			$storage = new Storage();
			$settings = $storage->get_settings();

			if (
				$render->contains_item('cart')
				&&
				blc_theme_functions()->blocksy_get_theme_mod('mini_cart_suggested_products', 'yes') !== 'no'
			) {
				$data['dynamic_styles_selectors'][] = [
					'selector' => '.ct-header-cart, #woo-cart-panel',
					'url' => add_query_arg(
						'ver',
						$plugin_data['Version'],
						blocksy_cdn_url(
							BLOCKSY_URL .
							'framework/premium/extensions/woocommerce-extra/static/bundle/suggested-products.min.css'
						)
					)
				];
			}

			if (
				isset($settings['features']['added-to-cart-popup'])
				&&
				$settings['features']['added-to-cart-popup']
				&&
				blc_theme_functions()->blocksy_get_theme_mod('cart_popup_suggested_products', 'yes') === 'yes'
			) {
				$data['dynamic_styles']['suggested_products'] = add_query_arg(
					'ver',
					$plugin_data['Version'],
					blocksy_cdn_url(BLOCKSY_URL . 'framework/premium/extensions/woocommerce-extra/static/bundle/suggested-products.min.css')
				);
			}

			return $data;
		});

		add_action('wp_ajax_blocksy_update_suggested', [
			$this,
			'update_suggested',
		]);

		add_action('wp_ajax_nopriv_blocksy_update_suggested', [
			$this,
			'update_suggested',
		]);

		add_action('wp_enqueue_scripts', function () {
			if (! function_exists('get_plugin_data')) {
				require_once(ABSPATH . 'wp-admin/includes/plugin.php');
			}

			$data = get_plugin_data(BLOCKSY__FILE__);

			$should_load_styles = false;

			if (
				is_cart()
				&&
				blc_theme_functions()->blocksy_get_theme_mod('cart_suggested_products', 'yes') === 'yes'
			) {
				$should_load_styles = true;
			}

			if (
				is_checkout()
				&&
				blc_theme_functions()->blocksy_get_theme_mod('checkout_suggested_products', 'yes') === 'yes'
			) {
				$should_load_styles = true;
			}

			if (! $should_load_styles) {
				return;
			}

			wp_enqueue_style(
				'blocksy-ext-suggested-products',
				BLOCKSY_URL . 'framework/premium/extensions/woocommerce-extra/static/bundle/suggested-products.min.css',
				['ct-main-styles'],
				$data['Version']
			);
		});

		add_action('blocksy:pro:woo-extra:offcanvas:minicart:list:after', function() {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $this->render_mini_cart();
		});

		add_action('blocksy:woo:checkout:order-review', function() {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $this->render_checkout();
		}, 20);

		add_action('wp', function() {
			$position = blc_theme_functions()->blocksy_get_theme_mod('cart_suggested_position', 'totals');
			$hook = 'blocksy:woo:cart:cart-totals';


			if ($position === 'below') {
				$hook = 'blocksy:woocommerce:cart:before-cross-sells';
			}

			if ($position === 'table') {
				$hook = 'woocommerce_after_cart_table';
			}

			add_action($hook, function() {
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $this->render_cart();
			}, 5);
		});

		add_filter(
			'blocksy:ext:woocommerce-extra:added-to-cart:suggested-products',
			function($content, $product_id) {
				$content .= $this->render_added_to_cart_suggested_products($product_id);

				return $content;
			},
			10,
			2
		);

		add_filter('blocksy:woocommerce:cart-fragments', [
			$this,
			'header_cart_item_fragment',
		]);

		add_filter(
			'blocksy_customizer_options:woocommerce:general:end',
			function ($opts) {
				$opts['has_suggested_products_panel'] = blocksy_get_options(
					dirname(__FILE__) . '/options/main.php',
					[],
					false
				);

				return $opts;
			},
			55
		);
	}

	public function update_suggested() {
		$content = $this->render_checkout();

		wp_send_json_success([
			'content' => $content
		]);
	}

	public function render_added_to_cart_suggested_products($product_id) {
		$product = wc_get_product($product_id);

		if ($product->get_type() === 'variation') {
			$product_id = $product->get_parent_id();
		}

		return blocksy_render_view(
			dirname(__FILE__) . '/views/suggested-products.php',
			[
				'added_products' => [$product_id],
				'prefix' => 'cart_popup_suggested_'
			]
		);
	}

	public function render_mini_cart() {
		$content = '';
		$added_products = [];

		foreach (WC()->cart->get_cart() as $cart_item) {
			$added_products[] = $cart_item['product_id'];
		}

		if (! empty($added_products)) {
			$content = blocksy_render_view(
				dirname(__FILE__) . '/views/suggested-products.php',
				[
					'added_products' => $added_products,
					'prefix' => 'mini_cart_suggested_'
				]
			);
		}

		return $content;
	}

	public function render_checkout() {
		$content = '';
		$added_products = [];

		foreach (WC()->cart->get_cart() as $cart_item) {
			$added_products[] = $cart_item['product_id'];
		}

		if (! empty($added_products)) {
			$content = blocksy_render_view(
				dirname(__FILE__) . '/views/suggested-products.php',
				[
					'added_products' => $added_products,
					'prefix' => 'checkout_suggested_'
				]
			);
		}

		return $content;
	}

	public function render_cart() {
		$content = '';
		$added_products = [];

		foreach (WC()->cart->get_cart() as $cart_item) {
			$added_products[] = $cart_item['product_id'];
		}

		if (! empty($added_products)) {
			$content = blocksy_render_view(
				dirname(__FILE__) . '/views/suggested-products.php',
				[
					'added_products' => $added_products,
					'prefix' => 'cart_suggested_'
				]
			);
		}

		return $content;
	}

	public function header_cart_item_fragment($fragments) {
		if (blc_theme_functions()->blocksy_get_theme_mod('mini_cart_suggested_products', 'yes') === 'yes') {
			$fragments['[class*="ct-suggested-products--mini-cart"]'] = $this->render_mini_cart();
		}

		return $fragments;
	}

	public static function get_option_defaults() {
		return blocksy_get_options(
			dirname(__FILE__) . '/options/defaults.php',
			[],
			false
		);
	}
}
