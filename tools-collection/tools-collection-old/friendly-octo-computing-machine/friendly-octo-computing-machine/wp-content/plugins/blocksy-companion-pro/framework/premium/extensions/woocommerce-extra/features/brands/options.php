<?php

$options = [
	'has_woo_brands_tab' => [
		'label' => __( 'Product Brand Tab', 'blocksy-companion' ),
		'type' => 'ct-switch',
		'switch' => true,
		'value' => 'no',
		'sync' => blocksy_sync_whole_page([
			'prefix' => 'product',
			'loader_selector' => '.woocommerce-tabs'
		]),
	],

	blocksy_rand_md5() => [
		'type' => 'ct-condition',
		'condition' => [ 'has_woo_brands_tab' => 'yes' ],
		'options' => [
			'use_brand_name_for_tab_title' => [
				'label' => __( 'Brand Name In Tab Title', 'blocksy-companion' ),
				'type' => 'ct-switch',
				'switch' => true,
				'value' => 'no',
				'sync' => blocksy_sync_whole_page([
					'prefix' => 'product',
					'loader_selector' => '.woocommerce-tabs'
				]),
			],
		]
	]
];