<?php

$selectors = [
	'mini_cart_suggested_'=>'.ct-suggested-products--mini-cart',
	'checkout_suggested_'=>'.ct-suggested-products--checkout',
	'cart_popup_suggested_'=>'.ct-suggested-products--cart-popup',
	'cart_suggested_'=>'.ct-suggested-products--cart',
];

$loader_selector = $selectors[$prefix];

$defaults = \Blocksy\Extensions\WoocommerceExtra\CartSuggestedProducts::get_option_defaults();

$options = [
	blocksy_rand_md5() => [
		'title' => __( 'General', 'blocksy-companion' ),
		'type' => 'tab',
		'options' => [

			[
				$prefix . 'products_type' => [
					'label' => false,
					'type' => 'ct-image-picker',
					'value' => 'inline',
					'attr' => ['data-type' => 'background'],
					'divider' => 'bottom',
					'choices' => [
						'inline' => [
							'src' => blocksy_image_picker_url('suggested-products-type-1.svg'),
							'title' => __('Inline', 'blocksy-companion'),
						],

						'block' => [
							'src' => blocksy_image_picker_url('suggested-products-type-2.svg'),
							'title' => __('Block', 'blocksy-companion'),
						],
					],
					'sync' => 'live',
				],

				$prefix . 'products_source' => [
					'label' => __('Source', 'blocksy-companion'),
					'type' => 'ct-select',
					'value' => 'related',
					'design' => 'block',
					'divider' => 'bottom',
					'choices' => blocksy_ordered_keys(
						[
							'related' => __('Related Products', 'blocksy-companion'),
							'recent' => __('Recently Viewed Products', 'blocksy-companion'),
							'upsell' => __('Upsell Products', 'blocksy-companion'),
							'cross_sell' => __('Cross-sell Products', 'blocksy-companion'),
						]
					),

					'sync' => blocksy_sync_whole_page([
						'prefix' => 'single_page',
						'loader_selector' => $loader_selector
					]),
				],

				blocksy_rand_md5() => [
					'type' => 'ct-group',
					'label' => __('Columns & Products', 'blocksy-companion'),
					'attr' => ['data-columns' => '2:medium'],
					'responsive' => true,
					'hasGroupRevertButton' => true,
					'options' => [

						$prefix . 'products_columns' => [
							'label' => false,
							'desc' => __('Number of columns', 'blocksy-companion'),
							'type' => 'ct-number',
							'value' => $defaults[$prefix]['products_columns'],
							'min' => 1,
							'max' => 6,
							'design' => 'block',
							'attr' => ['data-width' => 'full'],
							'responsive' => true,
							'skipResponsiveControls' => true,
							'sync' => 'live',
						],

						$prefix . 'products_number_of_items' => [
							'label' => false,
							'desc' => __('Number of products', 'blocksy-companion'),
							'type' => 'ct-number',
							'value' => 6,
							'min' => 1,
							'max' => 15,
							'design' => 'block',
							'attr' => ['data-width' => 'full'],
							'markAsAutoFor' => ['tablet', 'mobile'],
							'sync' => blocksy_sync_whole_page([
								'prefix' => 'single_page',
								'loader_selector' => $loader_selector
							]),
						],
					],
				],

				$prefix . 'products_autoplay' => [
					'type' => 'ct-switch',
					'label' => __('Autoplay', 'blocksy-companion'),
					'value' => 'yes',
					'divider' => 'top',
					'sync' => blocksy_sync_whole_page([
						'prefix' => 'single_page',
						'loader_selector' => $loader_selector
					]),
				],

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [
						$prefix . 'products_autoplay' => 'yes',
					],
					'options' => [
						$prefix . 'products_autoplay_speed' => [
							'label' => __('Delay (Seconds)', 'blocksy-companion'),
							'desc' => __('Specify the amount of time (in seconds) to delay between automatically cycling an item.', 'blocksy-companion'),
							'type' => 'ct-number',
							'value' => 3,
							'min' => 1,
							'max' => 10,
							'design' => 'inline',
							'sync' => blocksy_sync_whole_page([
								'prefix' => 'single_page',
								'loader_selector' => $loader_selector
							]),
						],
					],
				],

				$prefix . 'products_image_ratio' => [
					'label' => __('Image Ratio', 'blocksy-companion'),
					'type' => 'ct-ratio',
					'view' => 'inline',
					'value' => '1/1',
					'divider' => 'top:full',
					'sync' => 'live',
				],

				$prefix . 'products_image_size' => [
					'label' => __('Image Size', 'blocksy-companion'),
					'type' => 'ct-select',
					'value' => 'thumbnail',
					'view' => 'text',
					'design' => 'block',
					'divider' => 'top',
					'choices' => blocksy_ordered_keys(
						blocksy_get_all_image_sizes()
					),
					'sync' => blocksy_sync_whole_page([
						'prefix' => 'single_page',
						'loader_selector' => $loader_selector
					]),
				],

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [
						$prefix . 'products_type' => 'inline',
					],
					'options' => [
						$prefix . 'products_image_width' => [
							'label' => __( 'Image Width', 'blocksy-companion' ),
							'type' => 'ct-slider',
							'value' => $defaults[$prefix]['products_image_width'],
							'units' => blocksy_units_config([
								['unit' => '%', 'min' => 1, 'max' => 50],
								['unit' => 'px', 'min' => 10, 'max' => 200],
								['unit' => 'pt', 'min' => 10, 'max' => 200],
								['unit' => 'em', 'min' => 10, 'max' => 100],
								['unit' => 'rem', 'min' => 10, 'max' => 100],
								['unit' => 'vw', 'min' => 0, 'max' => 50],
								['unit' => 'vh', 'min' => 0, 'max' => 50],
								['unit' => '', 'type' => 'custom'],
							]),
							// 'min' => 10,
							// 'max' => 50,
							// 'defaultUnit' => '%',
							'responsive' => true,
							'divider' => 'top',
							'setting' => [ 'transport' => 'postMessage' ],
						],
					]
				],

				$prefix . 'products_show_price' => [
					'type' => 'ct-switch',
					'label' => __('Price', 'blocksy-companion'),
					'value' => 'yes',
					'divider' => 'top',
					'sync' => blocksy_sync_whole_page([
						'prefix' => 'single_page',
						'loader_selector' => $loader_selector
					]),
				],

				$prefix . 'products_show_add_to_cart' => [
					'type' => 'ct-switch',
					'label' => __('Add to Cart Button', 'blocksy-companion'),
					'value' => $defaults[$prefix]['products_show_add_to_cart'],
					'divider' => 'top',
					'sync' => blocksy_sync_whole_page([
						'prefix' => 'single_page',
						'loader_selector' => $loader_selector
					]),
				],
			],

			(
				$prefix === 'cart_suggested_' ? [
					'cart_suggested_position' => [
						'label' => __('Module Placement', 'blocksy-companion'),
						'type' => 'ct-select',
						'value' => 'totals',
						'divider' => 'top:full',
						'choices' => blocksy_ordered_keys(
							[
								'totals' => __('Inside Cart Totals', 'blocksy-companion'),
								'table' => __('After Products Table', 'blocksy-companion'),
								'below' => __('Below Cart Module', 'blocksy-companion'),
							]
						),

						'sync' => blocksy_sync_whole_page([
							'prefix' => 'single_page',
							'loader_selector' => $loader_selector
						]),
					],
				] : []
			),

			[
				$prefix . 'products_visibility' => [
					'label' => __('Module Visibility', 'blocksy-companion'),
					'type' => 'ct-visibility',
					'design' => 'block',
					'divider' => 'top',
					'setting' => ['transport' => 'postMessage'],
					'allow_empty' => true,

					'value' => blocksy_default_responsive_value([
						'desktop' => true,
						'tablet' => true,
						'mobile' => true,
					]),

					'choices' => blocksy_ordered_keys([
						'desktop' => __( 'Desktop', 'blocksy-companion' ),
						'tablet' => __( 'Tablet', 'blocksy-companion' ),
						'mobile' => __( 'Mobile', 'blocksy-companion' ),
					]),
				],
			],

		],
	],

	blocksy_rand_md5() => [
		'title' => __( 'Design', 'blocksy-companion' ),
		'type' => 'tab',
		'options' => [

			$prefix . 'products_title_font' => [
				'type' => 'ct-typography',
				'label' => __( 'Title Font', 'blocksy-companion' ),
				'value' => blocksy_typography_default_values([
					'size' => '14px',
					'variation' => 'n6',
				]),
				'setting' => [ 'transport' => 'postMessage' ],
			],

			$prefix . 'products_title_color' => [
				'label' => __( 'Title Color', 'blocksy-companion' ),
				'type'  => 'ct-color-picker',
				'design' => 'inline',
				'divider' => 'bottom',
				'setting' => [ 'transport' => 'postMessage' ],
				'value' => [
					'default' => [
						'color' => 'var(--theme-text-color)',
					],

					'hover' => [
						'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
					],
				],

				'pickers' => [
					[
						'title' => __( 'Initial', 'blocksy-companion' ),
						'id' => 'default',
						// 'inherit' => 'var(--theme-text-color)'
					],

					[
						'title' => __( 'Hover', 'blocksy-companion' ),
						'id' => 'hover',
						'inherit' => 'var(--theme-link-hover-color)'
					],
				],
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [
					$prefix . 'products_show_price' => 'yes',
				],
				'options' => [
					$prefix . 'products_price_font' => [
						'type' => 'ct-typography',
						'label' => __( 'Price Font', 'blocksy-companion' ),
						'value' => blocksy_typography_default_values([
							'size' => '13px',
						]),
						'setting' => [ 'transport' => 'postMessage' ],
					],

					$prefix . 'products_price_color' => [
						'label' => __( 'Price Color', 'blocksy-companion' ),
						'type'  => 'ct-color-picker',
						'design' => 'inline',
						'divider' => 'bottom',
						'setting' => [ 'transport' => 'postMessage' ],
						'value' => [
							'default' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],
						],

						'pickers' => [
							[
								'title' => __( 'Initial', 'blocksy-companion' ),
								'id' => 'default',
								'inherit' => 'var(--theme-text-color)'
							],
						],
					],
				]
			],

			$prefix . 'products_image_radius' => [
				'label' => __( 'Image Border Radius', 'blocksy-companion' ),
				'type' => 'ct-spacing',
				'value' => blocksy_spacing_value(),
				'inputAttr' => [
					'placeholder' => '3'
				],
				'min' => 0,
				'sync' => 'live',
			],

		],
	],
];
