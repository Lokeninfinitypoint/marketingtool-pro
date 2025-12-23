<?php

$options = [
	'label' => __('Free Shipping Bar', 'blocksy-companion'),
	'type' => 'ct-panel',
	'setting' => ['transport' => 'postMessage'],
	'inner-options' => [

		blocksy_rand_md5() => [
			'title' => __( 'General', 'blocksy-companion' ),
			'type' => 'tab',
			'options' => [

				'woo_shipping_progress_in_cart' => [
					'label' => __( 'Show In Cart Page', 'blocksy-companion' ),
					'type' => 'ct-switch',
					'value' => 'no',
				],

				'woo_shipping_progress_in_checkout' => [
					'label' => __( 'Show In Checkout Page', 'blocksy-companion' ),
					'type' => 'ct-switch',
					'value' => 'no',
				],

				'woo_shipping_progress_in_mini_cart' => [
					'label' => __( 'Show In Mini Cart', 'blocksy-companion' ),
					'type' => 'ct-switch',
					'value' => 'no',

				],

				'woo_count_method' => [
					'label' => __('Calculation Method', 'blocksy-companion'),
					'type' => 'ct-radio',
					'value' => 'custom',
					'view' => 'text',
					'design' => 'block',
					'divider' => 'top:full',
					'choices' => [
						'custom' => __('Custom', 'blocksy-companion'),
						'woo' => __('WooCommerce', 'blocksy-companion'),
					],
				],

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [ 'woo_count_method' => 'custom' ],
					'options' => [

						'woo_custom_count_criteria' => [
							'label' => __('Count Criteria', 'blocksy-companion'),
							'type' => 'ct-radio',
							'value' => 'price',
							'view' => 'text',
							'design' => 'block',
							// 'divider' => 'top',
							'choices' => [
								'price' => __('Price', 'blocksy-companion'),
								'items' => __('Items', 'blocksy-companion'),
							],
						],

						blocksy_rand_md5() => [
							'type' => 'ct-condition',
							'condition' => [ 'woo_custom_count_criteria' => 'price' ],
							'options' => [
								'woo_count_progress_amount' => [
									'label' => __('Goal Amount', 'blocksy-companion'),
									'type' => 'ct-number',
									'design' => 'inline',
									'value' => 100,
									'blockDecimal' => false,
									'decimalPlaces' => 2,
									'min' => 1,
									'desc' => __('Amount the client has to reach in order to get free shipping.', 'blocksy-companion'),
								],
							]
						],

						blocksy_rand_md5() => [
							'type' => 'ct-condition',
							'condition' => [ 'woo_custom_count_criteria' => 'items' ],
							'options' => [
								'woo_count_progress_items' => [
									'label' => __('Goal Items', 'blocksy-companion'),
									'type' => 'ct-number',
									'design' => 'inline',
									'value' => 2,
									'desc' => __('Amount of items the client has to buy in order to get free shipping.', 'blocksy-companion'),
								],
							]
						],
					]
				],

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [ 'woo_count_method' => 'woo' ],
					'options' => [

						blocksy_rand_md5() => [
							'type' => 'ct-notification',
							'attr' => [ 'data-type' => 'option-description' ],
							'text' => __( 'The calculation method will be based on WooCommerce zones.', 'blocksy-companion' ),
						],
					]
				],

				'woo_shipping_count_non_physical' => [
					'label' => __('Count Non-Physical Products', 'blocksy-companion'),
					'type' => 'ct-switch',
					'value' => 'yes',
					'divider' => 'top:full',
					'desc' => __('Include or exclude virtual and downloadable products in calculating the shipping progress.', 'blocksy-companion'),
				],

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [
						'any' => [
							'woo_count_method' => 'woo',
							'woo_custom_count_criteria' => 'price'
						]
					],
					'options' => [

						'woo_count_with_discount' => [
							'label' => __('Discount Calculation', 'blocksy-companion'),
							'type' => 'ct-switch',
							'value' => 'yes',
							'divider' => 'top',
							'desc' => __('Include or exclude the discount code when calculating the shipping progress.', 'blocksy-companion'),
						],
						
						'free_not_enought_message' => [
							'label' => __( 'Default Message', 'blocksy-companion' ),
							'type' => 'wp-editor',
							'value' => __('Add {price} more to get free shipping!', 'blocksy-companion'),
							'setting' => [ 'transport' => 'postMessage' ],
							'quicktags' => false,
							'mediaButtons' => false,
							'divider' => 'top:full',
							'tinymce' => [
								'toolbar1' => 'bold,italic,link,alignleft,aligncenter,alignright,undo,redo',
							],
							'desc' => __( 'You can use dynamic code tags such as {price} inside this option.', 'blocksy-companion' ),
						],
					]
				],

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [
						'all' => [
							'woo_count_method' => 'custom',
							'woo_custom_count_criteria' => 'items'
						]
					],
					'options' => [
						'free_not_enought_items_message' => [
							'label' => __( 'Default Message', 'blocksy-companion' ),
							'type' => 'wp-editor',
							'value' => __('Add {items} more items to get free shipping!', 'blocksy-companion'),
							'setting' => [ 'transport' => 'postMessage' ],
							'quicktags' => false,
							'mediaButtons' => false,
							'divider' => 'top:full',
							'tinymce' => [
								'toolbar1' => 'bold,italic,link,alignleft,aligncenter,alignright,undo,redo',
							],
							'desc' => __( 'You can use dynamic code tags such as {items} inside this option.', 'blocksy-companion' ),
						],
					]
				],

				'free_enought_message' => [
					'label' => __( 'Success Message', 'blocksy-companion' ),
					'type' => 'wp-editor',
					'value' => __('Congratulations! You got free shipping 🎉', 'blocksy-companion'),
					'divider' => 'top',
					'setting' => [ 'transport' => 'postMessage' ],
					'quicktags' => false,
					'mediaButtons' => false,
					'tinymce' => [
						'toolbar1' => 'bold,italic,link,alignleft,aligncenter,alignright,undo,redo',
					],
				],

			],
		],

		blocksy_rand_md5() => [
			'title' => __( 'Design', 'blocksy-companion' ),
			'type' => 'tab',
			'options' => [

				'shipping_progress_bar_color' => [
					'label' => __( 'Bar Color', 'blocksy-companion' ),
					'type'  => 'ct-color-picker',
					'design' => 'inline',
					'setting' => [ 'transport' => 'postMessage' ],
					'value' => [
						'default' => [
							'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
						],

						'active' => [
							'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
						],

						'active_2' => [
							'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
						],
					],

					'pickers' => [
						[
							'title' => __( 'Initial', 'blocksy-companion' ),
							'id' => 'default',
							'inherit' => 'var(--theme-border-color)'
						],

						[
							'title' => __( 'Active', 'blocksy-companion' ),
							'id' => 'active',
							'inherit' => 'var(--theme-palette-color-1)'
						],

						[
							'title' => __( 'Active', 'blocksy-companion' ),
							'id' => 'active_2',
							'inherit' => 'self:active'
						],
					],
				],

			],
		],
		
	],
];