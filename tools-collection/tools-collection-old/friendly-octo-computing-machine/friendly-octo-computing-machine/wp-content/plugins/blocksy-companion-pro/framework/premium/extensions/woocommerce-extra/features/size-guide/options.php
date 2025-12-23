<?php

$products = get_posts([
	'numberposts' => -1,
	'post_type' => 'product',
]);

$choices = [];

$choices[] = [
	'key' => 'all',
	'value' => __('All Products', 'blocksy-companion')
];

foreach ($products as $product) {
	$choices[] = [
		'key' => $product->ID,
		'value' => $product->post_title
	];
}

$options = [
	'conditions' => [
		'label' => __('Display Conditions', 'blocksy-companion'),
		'type' => 'blocksy-display-condition',
		'filter' => 'product_tabs',
		'sectionAttr' => [ 'class' => 'ct-content-blocks-conditions' ],
		'display' => 'modal',
		'modalTitle' => __('Size Guide Display Conditions', 'blocksy-companion'),
		'modalDescription' => __('Choose where you want this size guide to be displayed.', 'blocksy-companion'),
		'value' => [
			[
				'type' => 'include',
				'rule' => 'singulars',
				'payload' => []
			]
		],

		'value' => [],
		'design' => 'block',
		'divider' => 'bottom:full',
	],
];
