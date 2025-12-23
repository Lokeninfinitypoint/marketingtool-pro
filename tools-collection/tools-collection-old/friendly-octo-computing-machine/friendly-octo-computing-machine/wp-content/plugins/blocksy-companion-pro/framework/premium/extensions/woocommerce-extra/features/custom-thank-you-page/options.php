<?php

$gateway_options = [];
$shipping_options = [];
$categories_options = [];

$available_gateways = (new \WC_Payment_Gateways())->get_available_payment_gateways();
$available_gateways = wp_list_pluck($available_gateways, 'title');

$gateway_options = array_map(
	function($gateway_slug, $gateway_title) {
		return array(
			'value' => $gateway_title,
			'key' => $gateway_slug,
		);
	},
	array_keys($available_gateways),
	array_values($available_gateways)
);

$shipping_zones = \WC_Shipping_Zones::get_zones();

if (! empty($shipping_zones)) {
	$shipping_options = call_user_func_array(
		'array_merge',
		array_map(
			function($zone) {
				$zone_name = $zone['zone_name'];

				return array_map(
					function($shipping_method) use ($zone_name) {
						return array(
							'value' => blc_safe_sprintf('%s - %s', $zone_name, $shipping_method->get_title()),
							'key' => $shipping_method->get_instance_id(),
						);
					},
					$zone['shipping_methods']
				);
			},
			$shipping_zones
		)
	);
}

$args = array(
	'taxonomy' => "product_cat",
	'hide_empty' => false,
);

$product_categories = get_terms($args);

$categories_options = array_map(
	function($category) {
		return array(
			'value' => $category->name,
			'key' => $category->term_id,
		);
	},
	$product_categories
);

$options = [
	! empty($shipping_options) ? [
		'custom_ty_shippings' => [
			'label' => __('Shipping Methods', 'blocksy-companion'),
			'type' => 'ct-checkboxes',
			'allow_empty' => true,
			'attr' => ['data-columns' => '1'],
			'divider' => 'bottom:full',
			'disableRevertButton' => true,
			'choices' => $shipping_options,
			'value' => []
		],
	] : [],

	! empty($gateway_options) ? [
		'custom_ty_gateways' => [
			'label' => __('Payment Gateway', 'blocksy-companion'),
			'type' => 'ct-checkboxes',
			'allow_empty' => true,
			'attr' => ['data-columns' => '1'],
			'divider' => 'bottom:full',
			'disableRevertButton' => true,
			'choices' => $gateway_options,
			'value' => []
		],
	] : [],

	! empty($categories_options) ? [
		'custom_ty_categories' => [
			'label' => __('Product Categories', 'blocksy-companion'),
			'type' => 'ct-checkboxes',
			'allow_empty' => true,
			'attr' => ['data-columns' => '1'],
			'divider' => 'bottom:full',
			'disableRevertButton' => true,
			'choices' => $categories_options,
			'value' => []
		]
	] : [],

	[
		'priority' => [
			'type' => 'ct-number',
			'design' => 'inline',
			'label' => __('Priority', 'blocksy-companion'),
			'min' => 0,
			'max' => 999,
			'step' => 1,
			'value' => 0,
		]
	]
];
