<?php

if (! defined('ABSPATH')) {
	exit;
}

$button_link = $product->is_type('simple') ? add_query_arg('add-to-cart', $product->get_id(), $product->get_permalink()) : $product->get_permalink();

echo esc_html(
	sprintf(
		// translators: %s User name.
		__('Hi, %s!', 'blocksy-companion'),
		$user_name
	)
) . "\n\n";

echo esc_html(
	sprintf(
		// translators: %s is the product name
		__('Great news! The %s from your waitlist is now back in stock!', 'blocksy-companion'),
		esc_html($product->get_name())
	)
) . "\n\n";

echo esc_html__('Click the link below to secure your purchase before it is gone!', 'blocksy-companion') . "\n\n";

echo "----------------------------------------\n\n";

echo esc_html__('Product:', 'blocksy-companion') . ' ' . esc_html($product->get_name()) . "\n";
// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo esc_html__('Price:', 'blocksy-companion') . ' ' . wc_price($product->get_price()) . "\n";
echo esc_html__('Add to cart:', 'blocksy-companion') . ' ' . esc_url($button_link) . "\n";

echo "\n----------------------------------------\n\n";

echo wp_kses_post(apply_filters('woocommerce_email_footer_text', get_option('woocommerce_email_footer_text')));
?>
