<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class OrderDetailsBlock {
	public function __construct() {
		add_action('init', [$this, 'blocksy_woo_order_block']);
		add_action('enqueue_block_editor_assets', [$this, 'enqueue_admin']);
	}

	public function enqueue_admin() {
		$data = get_plugin_data(BLOCKSY__FILE__);

		wp_enqueue_script(
			'blocksy/woo-ordery',
			BLOCKSY_URL .
				'framework/premium/extensions/woocommerce-extra/static/bundle/woocommerce-order.js',
			['wp-blocks', 'wp-element', 'wp-block-editor'],
			$data['Version'],
			true
		);
	}

	public function blocksy_woo_order_block() {
		register_block_type('blocksy/woo-order', [
			'render_callback' => function ($attributes, $content, $block) {

				$attributes = wp_parse_args(
					$attributes,
					[
						'showOrderOverview' => true,
						'showOrderDetails' => true,
						'showCustomerDetails' => true,
						'className' => '',
						'style' => []
					]
				);

				if (
					! $attributes['showOrderOverview']
					&&
					! $attributes['showOrderDetails']
					&&
					! $attributes['showCustomerDetails']
				) {
					return '';
				}

				global $wp;

				if (empty(get_query_var('order-received'))) {
					return '';
				}
		
				$order_id = absint(get_query_var('order-received'));
		
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reading WooCommerce order key from URL for order verification, standard WC functionality
				$order_key = isset($_GET['key']) ? sanitize_text_field(wp_unslash($_GET['key'])) : false;
		
				if (
					! ($order_id > 0)
					||
					empty($order_key)
				) {
					return '';
				}
		
				$order = wc_get_order($order_id);
		
				if (
					! $order
					||
					! hash_equals($order->get_order_key(), $order_key)
				) {
					return '';
				}

				return blocksy_render_view(
					dirname(__FILE__) . '/order-view.php',
					[
						'order' => $order,
						'atts' => $attributes
					]
				);
			},
		]);
	}
}