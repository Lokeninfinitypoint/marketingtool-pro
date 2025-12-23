<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class WooHelpers {
	public function __construct() {
		add_action('init', function() {
			$storage = new \Blocksy\Extensions\WoocommerceExtra\Storage();
			$settings = $storage->get_settings();

			if (
				(
					isset($settings['features']['free-shipping'])
					&&
					$settings['features']['free-shipping']
					&&
					blc_theme_functions()->blocksy_get_theme_mod('woo_shipping_progress_in_checkout', 'no') === 'yes'
				)
				||
				(
					isset($settings['features']['suggested-products'])
					&&
					$settings['features']['suggested-products']
					&&
					blc_theme_functions()->blocksy_get_theme_mod('checkout_suggested_products', 'yes') === 'yes'
				)
			) {
				add_action('woocommerce_checkout_order_review', [$this, 'woocommerce_checkout_order_review'], 15);
			}

			if (
				(
					isset($settings['features']['free-shipping'])
					&&
					$settings['features']['free-shipping']
					&&
					blc_theme_functions()->blocksy_get_theme_mod('woo_shipping_progress_in_cart', 'no') === 'yes'
				)
				||
				(
					isset($settings['features']['suggested-products'])
					&&
					$settings['features']['suggested-products']
					&&
					blc_theme_functions()->blocksy_get_theme_mod('cart_suggested_products', 'yes') === 'yes'
					&&
					blc_theme_functions()->blocksy_get_theme_mod('cart_suggested_position', 'totals') === 'totals'
				)
			) {
				add_action('woocommerce_proceed_to_checkout', [$this, 'woocommerce_proceed_to_checkout'], 15);
			}
		});

		add_action(
			'wp_enqueue_scripts',
			[$this, 'enqueue_additional_action_styles']
		);

		add_filter(
			'blocksy:general:ct-scripts-localizations',
			function ($data) {
				if (! function_exists('get_plugin_data')) {
					require_once ABSPATH . 'wp-admin/includes/plugin.php';
				}

				if (
					! is_singular('product')
					&&
					function_exists('blocksy_has_product_specific_layer')
					&&
					! blocksy_has_product_specific_layer('product_actions', [
						'respect_post_type' => false
					])
				) {
					return $data;
				}

				$plugin_data = get_plugin_data(BLOCKSY__FILE__);

				$data['dynamic_styles']['additional_actions'] = add_query_arg(
					'ver',
					$plugin_data['Version'],
					blocksy_cdn_url(
						BLOCKSY_URL .
						'framework/premium/extensions/woocommerce-extra/static/bundle/additional-actions.min.css',
					)
				);

				return $data;
			}
		);
	}

	public function enqueue_additional_action_styles() {
		if (!function_exists('get_plugin_data')) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$data = get_plugin_data(BLOCKSY__FILE__);

		wp_register_style(
			'blocksy-ext-woocommerce-extra-additional-actions-styles',
			BLOCKSY_URL .
				'framework/premium/extensions/woocommerce-extra/static/bundle/additional-actions.min.css',
			['blocksy-ext-woocommerce-extra-styles'],
			$data['Version']
		);

		$blocksy_manager = blc_theme_functions()->blocksy_manager();

		if (
			! is_singular('product')
			||
			function_exists('blocksy_has_product_specific_layer')
			&&
			! blocksy_has_product_specific_layer('product_actions')
			&&
			! is_customize_preview()
			||
			! $blocksy_manager
			||
			empty(
				$blocksy_manager->woocommerce->single->additional_actions->get_actions()
			)
		) {
			return;
		}

		wp_enqueue_style(
			'blocksy-ext-woocommerce-extra-additional-actions-styles'
		);
	}

	public function woocommerce_proceed_to_checkout() {
		echo '<div class="ct-cart-totals-modules">';
		do_action('blocksy:woo:cart:cart-totals');
		echo '</div>';
	}

	public function woocommerce_checkout_order_review() {
		echo '<div class="ct-order-review-modules">';
		do_action('blocksy:woo:checkout:order-review');
		echo '</div>';
	}
}
