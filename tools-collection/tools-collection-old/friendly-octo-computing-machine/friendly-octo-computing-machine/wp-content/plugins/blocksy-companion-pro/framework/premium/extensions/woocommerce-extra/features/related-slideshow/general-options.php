<?php

$options = [
	'woocommerce_related_products_slideshow' => [
		'label' => __('Type', 'blocksy-companion'),
		'type' => 'ct-radio',
		'value' => 'default',
		'view' => 'text',
		'design' => 'block',
		'divider' => 'bottom',
		'choices' => [
			'default' => __('Default', 'blocksy-companion'),
			'slider' => __('Slider', 'blocksy-companion'),
		],
		'sync' => blocksy_sync_whole_page([
			'prefix' => 'product',
			'loader_selector' => '[class*="post"] .products',
		]),
	],

	blocksy_rand_md5() => [
		'type' => 'ct-condition',
		'condition' => [
			'woocommerce_related_products_slideshow' => 'slider',
		],
		'options' => [

			blocksy_rand_md5() => [
				'type' => 'ct-group',
				'label' => __('Columns & Products', 'blocksy-companion'),
				'attr' => ['data-columns' => '2:medium'],
				'responsive' => true,
				'hasGroupRevertButton' => true,
				'options' => [

					'woocommerce_related_products_slideshow_columns' => [
						'label' => false,
						'desc' => __('Number of columns', 'blocksy-companion'),
						'type' => 'ct-number',
						'value' => [
							'desktop' => 4,
							'tablet' => 3,
							'mobile' => 1,
							'__changed' => ['tablet', 'mobile']
						],
						'min' => 1,
						'max' => 6,
						'design' => 'block',
						'attr' => ['data-width' => 'full'],
						'responsive' => true,
						'skipResponsiveControls' => true,
						'sync' => 'live',
					],

					'woocommerce_related_products_slideshow_number_of_items' => [
						'label' => false,
						'desc' => __('Number of products', 'blocksy-companion'),
						'type' => 'ct-number',
						'value' => 6,
						'min' => 1,
						'max' => 50,
						'design' => 'block',
						'attr' => ['data-width' => 'full'],
						'markAsAutoFor' => ['tablet', 'mobile'],
						'sync' => blocksy_sync_whole_page([
							'prefix' => 'product',
							'loader_selector' => '[class*="post"] .products',
						]),
					],
				],
			],

			'woocommerce_related_products_slideshow_autoplay' => [
				'type' => 'ct-switch',
				'label' => __('Autoplay', 'blocksy-companion'),
				'value' => 'no',
				'divider' => 'top',
				'sync' => blocksy_sync_whole_page([
					'prefix' => 'product',
					'loader_selector' => '[class*="post"] .products',
				]),
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [
					'woocommerce_related_products_slideshow_autoplay' => 'yes',
				],
				'options' => [
					'woocommerce_related_products_slideshow_autoplay_speed' => [
						'label' => __('Delay (Seconds)', 'blocksy-companion'),
						'desc' => __('Specify the amount of time (in seconds) to delay between automatically cycling an item.', 'blocksy-companion'),
						'type' => 'ct-number',
						'value' => 3,
						'min' => 1,
						'max' => 10,
						'design' => 'inline',
						'sync' => blocksy_sync_whole_page([
							'prefix' => 'product',
							'loader_selector' => '[class*="post"] .products',
						]),
					],
				],
			],

			blocksy_rand_md5() => [
				'type' => 'ct-divider',
			],
		],
	],
];
