<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class Checkout {
	public function __construct() {
		add_filter(
			'blocksy_customizer_options:woocommerce:general:coupon:after',
			function ($opts) {
				$opts[] = blocksy_get_options(
					dirname(__FILE__) . '/options.php',
					[],
					false
				);

				return $opts;
			},
			60
		);

		add_action('wp', function () {
			if (
				! blc_theme_functions()->blocksy_manager()
				||
				! isset(blc_theme_functions()->blocksy_manager()->woocommerce)
				||
				! isset(blc_theme_functions()->blocksy_manager()->woocommerce->checkout)
				||
				! blc_theme_functions()->blocksy_manager()->woocommerce->checkout->has_custom_checkout()
			) {
				return;
			}

			if (
				blc_theme_functions()->blocksy_get_theme_mod('blocksy_has_image_toggle', 'no') !== 'yes'
				&&
				blc_theme_functions()->blocksy_get_theme_mod('blocksy_has_quantity_toggle', 'no') !== 'yes'
			) {
				return;
			}

			add_action(
				'woocommerce_review_order_before_cart_contents',
				function () {
					add_filter(
						'woocommerce_cart_item_product',
						[$this, 'blc_reset_product']
					);
				}
			);

			add_action(
				'woocommerce_review_order_after_cart_contents',
				function () {
					remove_filter(
						'woocommerce_cart_item_product',
						[$this, 'blc_reset_product']
					);

					blocksy_render_view_e(dirname(__FILE__) . '/view.php', []);
				},
				0
			);

			if (blc_theme_functions()->blocksy_get_theme_mod('blocksy_has_quantity_toggle', 'no') === 'yes') {
				add_action(
					'woocommerce_checkout_cart_item_quantity',
					[$this, 'render_quantity_in_checkout'],
					10,
					3
				);

				add_action(
					'woocommerce_checkout_update_order_review',
					[$this, 'handle_quantity_change']
				);
			}
		});
	}

	public function blc_reset_product () {
		return null;
	}

	public function handle_quantity_change($post_data) {
		parse_str($post_data, $post_data_array);

		$updated_qty = false;

		foreach ($post_data_array as $key => $value) {
			if (substr($key, 0, 20) === 'shipping_method_qty_') {
				$id = substr($key, 20);

				WC()->cart->set_quantity(
					$post_data_array['product_key_' . $id],
					$post_data_array[$key],
					false
				);

				$updated_qty = true;
			}
		}

		if ($updated_qty) {
			WC()->cart->calculate_totals();
		}
	}

	public function render_quantity_in_checkout ($product_quantity, $cart_item, $cart_item_key) {
		if (blc_theme_functions()->blocksy_get_theme_mod('blocksy_has_quantity_toggle', 'no') !== 'yes') {
			return $product_quantity;
		}

		$product = apply_filters(
			'woocommerce_cart_item_product',
			$cart_item['data'],
			$cart_item,
			$cart_item_key
		);

		$product_id = apply_filters(
			'woocommerce_cart_item_product_id',
			$cart_item['product_id'],
			$cart_item,
			$cart_item_key
		);

		if (! $product->is_sold_individually()) {
			// https://codecanyon.net/item/b2bking-the-ultimate-woocommerce-b2b-plugin/26689576 integration
			$callback = function($args) use ($cart_item) {
				$args['input_value'] = $cart_item['quantity'];

				return $args;
			};

			add_filter('woocommerce_quantity_input_args', $callback, 999, 1);

			$product_quantity = woocommerce_quantity_input(
				[
					'input_name' => 'shipping_method_qty_' . $product_id,
					'input_value' => $cart_item['quantity'],
					'min_value' => 0,
					'max_value' => $product->get_max_purchase_quantity(),
				],
				$product,
				false
			);

			remove_filter('woocommerce_quantity_input_args', $callback, 999, 1);

			$product_quantity .= blocksy_html_tag(
				'input',
				[
					'type' => 'hidden',
					'name' => 'product_key_' . $product_id,
					'value' => $cart_item_key
				],
				''
			);
		}

		return $product_quantity;
	}
}
