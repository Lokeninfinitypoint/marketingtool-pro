<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class OffcanvasCart {
	public function __construct() {
		add_filter('blocksy:header:cart:cart_drawer_type:option', function ($type) {
			return 'ct-image-picker';
		}, 10);

		add_filter(
			'blocksy:footer:offcanvas-drawer',
			function ($elements, $payload) {
				if (
					$payload['location'] === 'start'
					&&
					$payload['blocksy_has_default_header']
				) {
					$elements[] = $this->render_cart_offcanvas();
				}

				return $elements;
			},
			10, 2
		);

		add_filter('blocksy:woocommerce:cart-fragments', function ($fragments) {
			$fragments['#woo-cart-panel .ct-panel-content'] = $this->render_cart_offcanvas([
				'has_container' => false,
				'force_output' => true
			]);

			return $fragments;
		});

		add_action('wp_ajax_blocksy_update_qty_cart', [
			$this,
			'blocksy_update_qty_cart',
		]);

		add_action('wp_ajax_nopriv_blocksy_update_qty_cart', [
			$this,
			'blocksy_update_qty_cart',
		]);
	}

	public function render_cart_offcanvas($args = []) {
		$args = wp_parse_args($args, [
			'has_container' => true,
			'device' => 'mobile',
			'force_output' => false
		]);

		$render = new \Blocksy_Header_Builder_Render();

		if (! $args['force_output']) {
			if (! $render->contains_item('cart')) {
				return '';
			}
		}

		if (! function_exists('woocommerce_mini_cart')) {
			return '';
		}

		$atts = $render->get_item_data_for('cart');

		$has_cart_dropdown = blocksy_default_akg(
			'has_cart_dropdown',
			$atts,
			'yes'
		) === 'yes';

		$cart_drawer_type = blocksy_default_akg(
			'cart_drawer_type',
			$atts,
			'dropdown'
		);
		$cart_panel_close_button_type = blocksy_default_akg(
			'cart_panel_close_button_type',
			$atts,
			'type-1'
		);

		if (! $has_cart_dropdown) {
			return;
		}

		if ($cart_drawer_type !== 'offcanvas' && ! $args['force_output']) {
			return;
		}

		if (blocksy_default_akg('has_cart_panel_quantity', $atts, 'no') === 'yes') {
			add_filter(
				'woocommerce_widget_cart_item_quantity',
				[$this, 'add_minicart_quantity_fields'],
				10, 3
			);
		}

		global $blocksy_is_offcanvas_cart;
		$blocksy_is_offcanvas_cart = true;

		global $blocksy_mini_cart_ratio;
		global $blocksy_mini_cart_size;

		$blocksy_mini_cart_ratio = blocksy_default_akg('thumb_ratio', $atts, '1/1');
		$blocksy_mini_cart_size = blocksy_default_akg(
			'image_size',
			$atts,
			'woocommerce_thumbnail'
		);

		ob_start();
		woocommerce_mini_cart();
		$content = ob_get_clean();

		remove_filter(
			'woocommerce_widget_cart_item_quantity',
			[$this, 'add_minicart_quantity_fields'],
			10, 3
		);

		$class = 'ct-panel';
		$behavior = 'modal';

		$position_output = [];

		if (blocksy_default_akg('offcanvas_behavior', $atts, 'panel') !== 'modal') {
			$behavior = blocksy_default_akg(
				'cart_panel_position',
				$atts,
				'right'
			) . '-side';
		}

		$without_container = blocksy_html_tag(
			'div',
			array_merge([
				'class' => 'ct-panel-content',
			]),
			'<div class="ct-panel-content-inner">' . $content . '</div>'
		);

		if (! $args['has_container']) {
			return $without_container;
		}

		$cart_offcanvas_close_icon = apply_filters(
			'blocksy:cart:offcanvas:close:icon',
			'<svg class="ct-icon" width="12" height="12" viewBox="0 0 15 15"><path d="M1 15a1 1 0 01-.71-.29 1 1 0 010-1.41l5.8-5.8-5.8-5.8A1 1 0 011.7.29l5.8 5.8 5.8-5.8a1 1 0 011.41 1.41l-5.8 5.8 5.8 5.8a1 1 0 01-1.41 1.41l-5.8-5.8-5.8 5.8A1 1 0 011 15z"/></svg>'
		);

		return blocksy_html_tag(
			'div',
			array_merge(
				[
					'id' => 'woo-cart-panel',
					'class' => $class,
					'data-behaviour' => $behavior,
					'role' => 'dialog',
					'aria-label' => __('Shopping cart panel', 'blocksy-companion'),
					'inert' => ''
				],
				$position_output
			),

			'<div class="ct-panel-inner">
			<div class="ct-panel-actions">
			<span class="ct-panel-heading">' . __('Shopping Cart', 'blocksy-companion') . '</span>

			<button class="ct-toggle-close" data-type="' . $cart_panel_close_button_type . '" aria-label="' . __('Close cart drawer', 'blocksy-companion') . '">
			'. $cart_offcanvas_close_icon . '
			</button>
			</div>
			'
			. $without_container .

			'</div>'
		);
	}

	public function add_minicart_quantity_fields($html, $cart_item, $cart_item_key) {
		$_product = apply_filters(
			'woocommerce_cart_item_product',
			$cart_item['data'],
			$cart_item,
			$cart_item_key
		);

		$product_price = apply_filters(
			'woocommerce_cart_item_price',
			WC()->cart->get_product_price($cart_item['data']),
			$cart_item,
			$cart_item_key
		);

		if ($_product->is_sold_individually()) {
			$product_quantity = blc_safe_sprintf(
				'1 <input type="hidden" name="cart[%s][qty]" value="1">',
				$cart_item_key
			);
		} else {
			// https://wordpress.org/plugins/wc-min-max-quantities/ integration
			$callback = function($args) use ($cart_item) {
				$args['input_value'] = $cart_item['quantity'];

				return $args;
			};

			add_filter('woocommerce_quantity_input_args', $callback, 50, 1);

			$product_quantity = trim(woocommerce_quantity_input(
				array(
					'input_name'   => "cart[{$cart_item_key}][qty]",
					'input_value'  => $cart_item['quantity'],
					'max_value'    => $_product->get_max_purchase_quantity(),
					'min_value'    => '0',
					'product_name' => $_product->get_name(),
				),
				$_product,
				false
			));

			remove_filter('woocommerce_quantity_input_args', $callback, 50, 1);
		}

		return '<div class="ct-product-actions">' . $product_quantity . '<span class="ct-product-multiply-symbol">×</span>' . $product_price . '</div>';
	}

	public function blocksy_update_qty_cart() {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if (! isset($_POST['hash'])) {
			wp_send_json_error();
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if (! isset($_POST['quantity'])) {
			wp_send_json_error();
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$cart_item_key = sanitize_text_field(wp_unslash($_POST['hash']));

		$threeball_product_values = WC()->cart->get_cart_item($cart_item_key);

		$threeball_product_quantity = apply_filters(
			'woocommerce_stock_amount_cart_item',
			apply_filters(
				'woocommerce_stock_amount',
				preg_replace(
					'/[^0-9\.]/',
					'',
					// phpcs:ignore WordPress.Security.NonceVerification.Missing
					filter_var(wp_unslash($_POST['quantity']), FILTER_SANITIZE_NUMBER_INT)
				)
			),
			$cart_item_key
		);

		$passed_validation = apply_filters(
			'woocommerce_update_cart_validation',
			true,
			$cart_item_key,
			$threeball_product_values,
			$threeball_product_quantity
		);

		if ($passed_validation) {
			WC()->cart->set_quantity(
				$cart_item_key,
				$threeball_product_quantity,
				true
			);
		}

		die();
	}
}

