<?php

$added_to_cart_options =  [];

$mini_cart_options =  blocksy_get_options(
	dirname(__FILE__) . '/generic.php',
	[
		'prefix' => 'mini_cart_suggested_'
	],
	false
);

$cart_options =  blocksy_get_options(
	dirname(__FILE__) . '/generic.php',
	[
		'prefix' => 'cart_suggested_'
	],
	false
);

$checkout_options =  blocksy_get_options(
	dirname(__FILE__) . '/generic.php',
	[
		'prefix' => 'checkout_suggested_'
	],
	false
);

$storage = new \Blocksy\Extensions\WoocommerceExtra\Storage();
$settings = $storage->get_settings();

if (
	isset($settings['features']['added-to-cart-popup']) &&
	$settings['features']['added-to-cart-popup']
) {
	$added_to_cart_options =  blocksy_get_options(
		dirname(__FILE__) . '/generic.php',
		[
			'prefix' => 'cart_popup_suggested_'
		],
		false
	);
}

$options = [
	'label' => __('Suggested Products', 'blocksy-companion'),
	'type' => 'ct-panel',
	'setting' => ['transport' => 'postMessage'],
	'inner-options' => [

		! empty($added_to_cart_options) ? [
			'cart_popup_suggested_products' => [
				'label' => __( 'Added to Cart Popup', 'blocksy-companion' ),
				'type' => 'ct-panel',
				'panelSecondLevel' => true,
				'switch' => true,
				'value' => 'yes',
				'inner-options' => $added_to_cart_options,
				'sync' => blocksy_sync_whole_page([
					'prefix' => 'single_page',
					'loader_selector' => '.ct-suggested-products--cart-popup'
				]),
			]
		] : [],

		'mini_cart_suggested_products' => [
			'label' => __( 'Mini Cart', 'blocksy-companion' ),
			'type' => 'ct-panel',
			'panelSecondLevel' => true,
			'switch' => true,
			'value' => 'yes',
			'inner-options' => $mini_cart_options,
			'sync' => blocksy_sync_whole_page([
				'prefix' => 'single_page',
				'loader_selector' => '.ct-suggested-products--mini-cart'
			]),
		],

		'cart_suggested_products' => [
			'label' => __( 'Cart Page', 'blocksy-companion' ),
			'type' => 'ct-panel',
			'panelSecondLevel' => true,
			'switch' => true,
			'value' => 'yes',
			'inner-options' => $cart_options,
			'sync' => blocksy_sync_whole_page([
				'prefix' => 'single_page',
				'loader_selector' => '.ct-suggested-products--cart'
			]),
		],

		'checkout_suggested_products' => [
			'label' => __( 'Checkout Page', 'blocksy-companion' ),
			'type' => 'ct-panel',
			'panelSecondLevel' => true,
			'switch' => true,
			'value' => 'yes',
			'inner-options' => $checkout_options,
			'sync' => blocksy_sync_whole_page([
				'prefix' => 'single_page',
				'loader_selector' => '.ct-suggested-products--checkout'
			]),
		],
	]
];
