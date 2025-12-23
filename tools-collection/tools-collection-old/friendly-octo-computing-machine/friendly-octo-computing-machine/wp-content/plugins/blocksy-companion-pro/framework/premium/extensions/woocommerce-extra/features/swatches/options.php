<?php

$options = [
	'label' => __('Variation Swatches', 'blocksy-companion'),
	'type' => 'ct-panel',
	'setting' => ['transport' => 'postMessage'],
	'inner-options' => [

		blocksy_rand_md5() => [
			'label' => __( 'Color Swatches', 'blocksy-companion' ),
			'type' => 'ct-panel',
			'panelSecondLevel' => true,
			'setting' => [ 'transport' => 'postMessage' ],
			'inner-options' => [

				blocksy_rand_md5() => [
					'title' => __( 'General', 'blocksy-companion' ),
					'type' => 'tab',
					'options' => [

						'color_swatch_shape' => [
							'label' => __('Swatch Shape', 'blocksy-companion'),
							'type' => 'ct-radio',
							'value' => 'round',
							'view' => 'text',
							'design' => 'block',
							'choices' => [
								'round' => __('Round', 'blocksy-companion'),
								'square' => __('Square', 'blocksy-companion'),
							],
							'sync' => 'live',
						],

						'single_color_swatch_size' => [
							'label' => __('Single Page Swatch Size', 'blocksy-companion'),
							'type' => 'ct-slider',
							'value' => 30,
							'min' => 10,
							'max' => 100,
							'sync' => 'live',
						],

						'filter_widget_color_swatch_size' => [
							'label' => __('Widget Swatch Size', 'blocksy-companion'),
							'type' => 'ct-slider',
							'value' => 25,
							'min' => 10,
							'max' => 100,
							'sync' => 'live',
						],

						blocksy_rand_md5() => [
							'type' => 'ct-condition',
							'condition' => [ 'woo_card_layout:array-ids:product_swatches:enabled' => '!no' ],
							'values_source' => 'global',
							'options' => [

								'archive_color_swatch_size' => [
									'label' => __('Archive Cards Swatch Size', 'blocksy-companion'),
									'type' => 'ct-slider',
									'value' => 25,
									'min' => 10,
									'max' => 100,
									'sync' => 'live',
								],

							],
						],

					],
				],

				blocksy_rand_md5() => [
					'title' => __( 'Design', 'blocksy-companion' ),
					'type' => 'tab',
					'options' => [

						'color_swatch_border_color' => [
							'label' => __( 'Border Color', 'blocksy-companion' ),
							'type'  => 'ct-color-picker',
							'design' => 'inline',
							'setting' => [ 'transport' => 'postMessage' ],

							'value' => [
								'default' => [
									'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
								],

								'hover' => [
									'color' => 'rgba(0, 0, 0, 0.2)',
								],

								'active' => [
									'color' => 'rgba(0, 0, 0, 0.2)',
								],
							],

							'pickers' => [
								[
									'title' => __( 'Initial', 'blocksy-companion' ),
									'id' => 'default',
									'inherit' => 'var(--theme-border-color)'
								],

								[
									'title' => __( 'Hover', 'blocksy-companion' ),
									'id' => 'hover',
								],

								[
									'title' => __( 'Active', 'blocksy-companion' ),
									'id' => 'active',
								],
							],
						],

					],
				],

			],
		],


		blocksy_rand_md5() => [
			'label' => __( 'Image Swatches', 'blocksy-companion' ),
			'type' => 'ct-panel',
			'panelSecondLevel' => true,
			'setting' => [ 'transport' => 'postMessage' ],
			'inner-options' => [

				blocksy_rand_md5() => [
					'title' => __( 'General', 'blocksy-companion' ),
					'type' => 'tab',
					'options' => [

						'image_swatch_shape' => [
							'label' => __('Swatch Shape', 'blocksy-companion'),
							'type' => 'ct-radio',
							'value' => 'round',
							'view' => 'text',
							'design' => 'block',
							'choices' => [
								'round' => __('Round', 'blocksy-companion'),
								'square' => __('Square', 'blocksy-companion'),
							],
							'sync' => 'live',
						],

						'single_image_swatch_size' => [
							'label' => __('Single Page Swatch Size', 'blocksy-companion'),
							'type' => 'ct-slider',
							'value' => 35,
							'min' => 10,
							'max' => 100,
							'sync' => 'live',
						],

						'filter_widget_image_swatch_size' => [
							'label' => __('Widget Swatch Size', 'blocksy-companion'),
							'type' => 'ct-slider',
							'value' => 35,
							'min' => 10,
							'max' => 100,
							'sync' => 'live',
						],

						blocksy_rand_md5() => [
							'type' => 'ct-condition',
							'condition' => [ 'woo_card_layout:array-ids:product_swatches:enabled' => '!no' ],
							'values_source' => 'global',
							'options' => [

								'archive_image_swatch_size' => [
									'label' => __('Archive Cards Swatch Size', 'blocksy-companion'),
									'type' => 'ct-slider',
									'value' => 25,
									'min' => 10,
									'max' => 100,
									'sync' => 'live',
								],

							],
						],

						'image_swatch_fallback' => [
							'label' => __('Use Variation Image', 'blocksy-companion'),
							'type' => 'ct-switch',
							'value' => 'no',
							'divider' => 'top:full',
							'desc' => __('Automatically use the product variation image when no attribute term image is assigned.', 'blocksy-companion'),
						],

					],
				],

				blocksy_rand_md5() => [
					'title' => __( 'Design', 'blocksy-companion' ),
					'type' => 'tab',
					'options' => [

						'image_swatch_border_color' => [
							'label' => __( 'Border Color', 'blocksy-companion' ),
							'type'  => 'ct-color-picker',
							'design' => 'inline',
							'setting' => [ 'transport' => 'postMessage' ],

							'value' => [
								'default' => [
									'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
								],

								'hover' => [
									'color' => 'var(--theme-palette-color-1)',
								],

								'active' => [
									'color' => 'var(--theme-palette-color-1)',
								],
							],

							'pickers' => [
								[
									'title' => __( 'Initial', 'blocksy-companion' ),
									'id' => 'default',
									'inherit' => 'var(--theme-border-color)'
								],

								[
									'title' => __( 'Hover', 'blocksy-companion' ),
									'id' => 'hover',
								],

								[
									'title' => __( 'Active', 'blocksy-companion' ),
									'id' => 'active',
								],
							],
						],

					],
				],

			],
		],


		blocksy_rand_md5() => [
			'label' => __( 'Button Swatches', 'blocksy-companion' ),
			'type' => 'ct-panel',
			'panelSecondLevel' => true,
			'setting' => [ 'transport' => 'postMessage' ],
			'inner-options' => [

				blocksy_rand_md5() => [
					'title' => __( 'General', 'blocksy-companion' ),
					'type' => 'tab',
					'options' => [

						'button_swatch_shape' => [
							'label' => __('Swatch Shape', 'blocksy-companion'),
							'type' => 'ct-radio',
							'value' => 'round',
							'view' => 'text',
							'design' => 'block',
							'choices' => [
								'round' => __('Round', 'blocksy-companion'),
								'square' => __('Square', 'blocksy-companion'),
							],
							'sync' => 'live',
						],

						'single_button_swatch_size' => [
							'label' => __('Single Page Swatch Size', 'blocksy-companion'),
							'type' => 'ct-slider',
							'value' => 35,
							'min' => 10,
							'max' => 100,
							'sync' => 'live',
						],

						'filter_widget_button_swatch_size' => [
							'label' => __('Widget Swatch Size', 'blocksy-companion'),
							'type' => 'ct-slider',
							'value' => 30,
							'min' => 10,
							'max' => 100,
							'sync' => 'live',
						],

						blocksy_rand_md5() => [
							'type' => 'ct-condition',
							'condition' => [ 'woo_card_layout:array-ids:product_swatches:enabled' => '!no' ],
							'values_source' => 'global',
							'options' => [

								'archive_button_swatch_size' => [
									'label' => __('Archive Cards Swatch Size', 'blocksy-companion'),
									'type' => 'ct-slider',
									'value' => 25,
									'min' => 10,
									'max' => 100,
									'sync' => 'live',
								],

							],
						],

					],
				],

				blocksy_rand_md5() => [
					'title' => __( 'Design', 'blocksy-companion' ),
					'type' => 'tab',
					'options' => [

						'button_swatch_text_color' => [
							'label' => __( 'Text Color', 'blocksy-companion' ),
							'type'  => 'ct-color-picker',
							'design' => 'inline',
							'setting' => [ 'transport' => 'postMessage' ],

							'value' => [
								'default' => [
									'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
								],

								'hover' => [
									'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
								],

								'active' => [
									'color' => '#ffffff',
								],
							],

							'pickers' => [
								[
									'title' => __( 'Initial', 'blocksy-companion' ),
									'id' => 'default',
									'inherit' => 'var(--theme-text-color)'
								],

								[
									'title' => __( 'Hover', 'blocksy-companion' ),
									'id' => 'hover',
									'inherit' => 'var(--theme-text-color)'
								],

								[
									'title' => __( 'Active', 'blocksy-companion' ),
									'id' => 'active',
								],
							],
						],

						'button_swatch_border_color' => [
							'label' => __( 'Border Color', 'blocksy-companion' ),
							'type'  => 'ct-color-picker',
							'design' => 'inline',
							'setting' => [ 'transport' => 'postMessage' ],

							'value' => [
								'default' => [
									'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
								],

								'hover' => [
									'color' => 'var(--theme-palette-color-1)',
								],

								'active' => [
									'color' => 'var(--theme-palette-color-1)',
								],
							],

							'pickers' => [
								[
									'title' => __( 'Initial', 'blocksy-companion' ),
									'id' => 'default',
									'inherit' => 'var(--theme-border-color)'
								],

								[
									'title' => __( 'Hover', 'blocksy-companion' ),
									'id' => 'hover',
								],

								[
									'title' => __( 'Active', 'blocksy-companion' ),
									'id' => 'active',
								],
							],
						],

						'button_swatch_background_color' => [
							'label' => __( 'Background Color', 'blocksy-companion' ),
							'type'  => 'ct-color-picker',
							'design' => 'inline',
							'setting' => [ 'transport' => 'postMessage' ],

							'value' => [
								'default' => [
									'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
								],

								'hover' => [
									'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
								],

								'active' => [
									'color' => 'var(--theme-palette-color-1)',
								],
							],

							'pickers' => [
								[
									'title' => __( 'Initial', 'blocksy-companion' ),
									'id' => 'default',
									'inherit' => 'rgba(0, 0, 0, 0)'
								],

								[
									'title' => __( 'Hover', 'blocksy-companion' ),
									'id' => 'hover',
									'inherit' => 'rgba(0, 0, 0, 0)'
								],

								[
									'title' => __( 'Active', 'blocksy-companion' ),
									'id' => 'active',
								],
							],
						],

					],
				],

			],
		],


		blocksy_rand_md5() => [
			'label' => __( 'Mixed Swatches', 'blocksy-companion' ),
			'type' => 'ct-panel',
			'panelSecondLevel' => true,
			'setting' => [ 'transport' => 'postMessage' ],
			'inner-options' => [

				blocksy_rand_md5() => [
					'title' => __( 'General', 'blocksy-companion' ),
					'type' => 'tab',
					'options' => [

						'mixed_swatch_shape' => [
							'label' => __('Swatch Shape', 'blocksy-companion'),
							'type' => 'ct-radio',
							'value' => 'round',
							'view' => 'text',
							'design' => 'block',
							'choices' => [
								'round' => __('Round', 'blocksy-companion'),
								'square' => __('Square', 'blocksy-companion'),
							],
							'sync' => 'live',
						],

						'single_mixed_swatch_size' => [
							'label' => __('Single Page Swatch Size', 'blocksy-companion'),
							'type' => 'ct-slider',
							'value' => 30,
							'min' => 10,
							'max' => 100,
							'sync' => 'live',
						],

						'filter_widget_mixed_swatch_size' => [
							'label' => __('Widget Swatch Size', 'blocksy-companion'),
							'type' => 'ct-slider',
							'value' => 25,
							'min' => 10,
							'max' => 100,
							'sync' => 'live',
						],

						blocksy_rand_md5() => [
							'type' => 'ct-condition',
							'condition' => [ 'woo_card_layout:array-ids:product_swatches:enabled' => '!no' ],
							'values_source' => 'global',
							'options' => [

								'archive_mixed_swatch_size' => [
									'label' => __('Archive Cards Swatch Size', 'blocksy-companion'),
									'type' => 'ct-slider',
									'value' => 25,
									'min' => 10,
									'max' => 100,
									'sync' => 'live',
								],

							],
						],

					],
				],

				blocksy_rand_md5() => [
					'title' => __( 'Design', 'blocksy-companion' ),
					'type' => 'tab',
					'options' => [

						'mixed_swatch_border_color' => [
							'label' => __( 'Border Color', 'blocksy-companion' ),
							'type'  => 'ct-color-picker',
							'design' => 'inline',
							'setting' => [ 'transport' => 'postMessage' ],

							'value' => [
								'default' => [
									'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
								],

								'hover' => [
									'color' => 'rgba(0, 0, 0, 0.2)',
								],

								'active' => [
									'color' => 'rgba(0, 0, 0, 0.2)',
								],
							],

							'pickers' => [
								[
									'title' => __( 'Initial', 'blocksy-companion' ),
									'id' => 'default',
									'inherit' => 'var(--theme-border-color)'
								],

								[
									'title' => __( 'Hover', 'blocksy-companion' ),
									'id' => 'hover',
								],

								[
									'title' => __( 'Active', 'blocksy-companion' ),
									'id' => 'active',
								],
							],
						],

					],
				],

			],
		],

		blocksy_rand_md5() => [
			'type' => 'ct-divider',
		],

		'limit_number_of_swatches' => [
			'label' => __('Attribute Terms Limit', 'blocksy-companion'),
			'type' => 'ct-panel',
			'panelSecondLevel' => true,
			'switch' => true,
			'value' => 'no',
			'inner-options' => [

				'archive_limit_number_of_swatches_number' => [
					'label' => __('Archive Limit', 'blocksy-companion'),
					'type' => 'ct-number',
					'design' => 'inline',
					'value' => '',
					'min' => 1,
					'max' => 100,
					'desc' => __('Limit the number of swatches shown on archive pages.', 'blocksy-companion'),
				],

				'single_limit_number_of_swatches_number' => [
					'label' => __('Product Page Limit', 'blocksy-companion'),
					'type' => 'ct-number',
					'design' => 'inline',
					'value' => '',
					'min' => 1,
					'max' => 100,
					'divider' => 'top',
					'desc' => __('Limit the number of swatches shown on single product pages.', 'blocksy-companion'),
				],

				'limit_number_of_swatches_more_button' => [
					'label' => __('Expand Button', 'blocksy-companion'),
					'type' => 'ct-switch',
					'value' => 'no',
					'divider' => 'top',
					'desc' => __('Reveal hidden swatches when the limit is exceeded.', 'blocksy-companion'),
				],
			]
		],

		'has_swatches_url' => [
			'type'  => 'ct-switch',
			'label' => __( 'Generate Variation URL', 'blocksy-companion' ),
			'value' => 'no',
			'disableRevertButton' => true,
			'sync' => 'live',
			'divider' => 'top:full',
			// 'desc' => __( 'Generate a sharable url based on the selected attributes.', 'blocksy-companion' ),
			'desc' => __( 'Generate a shareable single product page URL with pre-selected variation attributes.', 'blocksy-companion' ),
		],

		'out_of_stock_swatch_type' => [
			'label' => __('Out of Stock Swatch Type', 'blocksy-companion'),
			'type' => 'ct-radio',
			'value' => 'faded',
			'view' => 'text',
			'design' => 'block',
			'divider' => 'top:full',
			'choices' => [
				'faded' => __('Faded', 'blocksy-companion'),
				'crossed' => __('Crossed', 'blocksy-companion'),
			],
			'sync' => 'live',
		],

	],
];