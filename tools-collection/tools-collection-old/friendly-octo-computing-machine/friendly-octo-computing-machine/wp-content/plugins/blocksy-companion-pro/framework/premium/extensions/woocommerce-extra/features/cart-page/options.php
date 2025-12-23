<?php

$options = [
	'has_cart_coupons' => [
		'label' => __('Coupon Form', 'blocksy-companion' ),
		'type' => 'ct-switch',
		'value' => 'yes',
		'sync' => blocksy_sync_whole_page([
			'loader_selector' => '.ct-cart-form .woocommerce-cart-form'
		]),
	],

	'has_cart_auto_update' => [
		'label' => __( 'Quantity Auto Update', 'blocksy-companion' ),
		'type' => 'ct-switch',
		'value' => 'no',
		'divider' => 'top',
		'sync' => blocksy_sync_whole_page([
			'loader_selector' => '.ct-cart-form .woocommerce-cart-form'
		]),
	],
];