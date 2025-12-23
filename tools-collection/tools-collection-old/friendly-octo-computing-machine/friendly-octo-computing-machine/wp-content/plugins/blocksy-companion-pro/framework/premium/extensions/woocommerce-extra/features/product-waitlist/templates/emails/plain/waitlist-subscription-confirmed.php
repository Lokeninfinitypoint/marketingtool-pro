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

echo esc_html__('You have been successfully added to the waitlist for the following item:', 'blocksy-companion') . "\n\n";

if ($product) {
	echo esc_html(wp_strip_all_tags(sprintf('%1$s (%2$s) [%3$s]', $product->get_name(), wc_price($product->get_price()), $product->get_permalink()))) . "\n";
}

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
