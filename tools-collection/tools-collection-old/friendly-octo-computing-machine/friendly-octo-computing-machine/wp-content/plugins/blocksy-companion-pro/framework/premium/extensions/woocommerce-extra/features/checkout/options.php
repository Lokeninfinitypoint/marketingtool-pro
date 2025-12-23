<?php

$options = [
	'blocksy_has_image_toggle' => [
		'label' => __( 'Product Image', 'blocksy-companion' ),
		'type' => 'ct-switch',
		'value' => 'no',
		'sync' => blocksy_sync_whole_page([
			'prefix' => 'single_page',
			'loader_selector' => '.ct-order-review'
		]),
	],

	blocksy_rand_md5() => [
		'type' => 'ct-condition',
		'condition' => [ 'blocksy_has_image_toggle' => 'yes' ],
		'options' => [

			'checkout_image_ratio' => [
				'label' => __('Image Ratio', 'blocksy-companion'),
				'type' => 'ct-ratio',
				'view' => 'inline',
				'value' => '1/1',
				'divider' => 'top',
				'sync' => blocksy_sync_whole_page([
					'prefix' => 'single_page',
					'loader_selector' => '.ct-order-review'
				]),
			],

			'checkout_image_size' => [
				'label' => __('Image Size', 'blocksy-companion'),
				'type' => 'ct-select',
				'value' => 'woocommerce_thumbnail',
				'view' => 'text',
				'design' => 'inline',
				'divider' => 'top',
				'choices' => blocksy_ordered_keys(
					blocksy_get_all_image_sizes()
				),
				'sync' => blocksy_sync_whole_page([
					'prefix' => 'single_page',
					'loader_selector' => '.ct-order-review'
				]),
			],

			blocksy_rand_md5() => [
				'type' => 'ct-divider',
			],
		],
	],

	'blocksy_has_quantity_toggle' => [
		'label' => __( 'Quantity Input', 'blocksy-companion' ),
		'type' => 'ct-switch',
		'value' => 'no',
		'divider' => 'bottom:full',
		'sync' => blocksy_sync_whole_page([
			'prefix' => 'single_page',
			'loader_selector' => '.ct-order-review'
		]),
	],
];
