<?php

if (! defined('ABSPATH')) {
	exit;
}

echo esc_html(
	sprintf(
		// translators: %s User name.
		__('Hi, %s!', 'blocksy-companion'),
		$user_name
	)
) . "\n\n";

echo esc_html__('You have requested to join the waitlist for this item:', 'blocksy-companion') . "\n\n";

echo esc_html__('Product:', 'blocksy-companion') . ' ' . esc_html($product->get_name()) . "\n";
echo esc_html__('Price:', 'blocksy-companion') . ' ' . esc_html(wc_price($product->get_price())) . "\n";
echo esc_html__('Product link:', 'blocksy-companion') . ' ' . esc_url($product->get_permalink()) . "\n";

echo "\n----------------------------------------\n\n";

echo esc_html__('Click the link below to confirm your subscription. Once confirmed, we will notify you when the item is back in stock.', 'blocksy-companion') . "\n\n";
echo esc_url($confirm_url) . "\n\n";

echo esc_html__('Please note, the confirmation period is 2 days.', 'blocksy-companion') . "\n";

echo "\n----------------------------------------\n\n";

echo esc_html(
	sprintf(
		// translators: %s: unsubscribe link.
		__('If you don\'t want to receive any further notifications, please unsubscribe by clicking on this link - %s', 'blocksy-companion'),
		esc_url($unsubscribe_link)
	)
);

echo "\n\n----------------------------------------\n\n";

echo wp_kses_post(apply_filters('woocommerce_email_footer_text', get_option('woocommerce_email_footer_text')));
?>
