<?php

$plugin_path = trailingslashit(WP_PLUGIN_DIR) . 'woocommerce/woocommerce.php';

// https://woocommerce.com/document/create-a-plugin/#section-1
$requirement_check = (
	(
		function_exists('wp_get_active_and_valid_plugins')
		&&
		in_array($plugin_path, wp_get_active_and_valid_plugins())
	) || (
		function_exists('wp_get_active_network_plugins')
		&&
		in_array($plugin_path, wp_get_active_network_plugins())
	)
);

$config = blc_exts_get_preliminary_config('woocommerce-extra');

$config['requirement'] = [
	'message' => __('This extension requires the WooCommerce plugin to be installed and activated.', 'blocksy-companion'),
	'check' => $requirement_check
];

