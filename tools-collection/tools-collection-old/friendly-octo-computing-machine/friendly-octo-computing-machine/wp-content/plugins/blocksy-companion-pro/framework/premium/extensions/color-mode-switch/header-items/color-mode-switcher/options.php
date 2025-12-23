<?php

$options = [

	blocksy_rand_md5() => [
		'title' => __( 'General', 'blocksy-companion' ),
		'type' => 'tab',
		'options' => [

			'color_switch_icon_type' => [
				'label' => false,
				'type' => 'ct-image-picker',
				'value' => 'type-1',
				'attr' => [
					'data-type' => 'background',
					'data-columns' => '3',
				],
				'divider' => 'bottom',
				'setting' => [ 'transport' => 'postMessage' ],
				'choices' => [
					'type-1' => [
						'src'   => blocksy_image_picker_file( 'color-switch-1' ),
						'title' => __( 'Type 1', 'blocksy-companion' ),
					],

					'type-2' => [
						'src'   => blocksy_image_picker_file( 'color-switch-2' ),
						'title' => __( 'Type 2', 'blocksy-companion' ),
					],

					'type-3' => [
						'src'   => blocksy_image_picker_file( 'color-switch-3' ),
						'title' => __( 'Type 3', 'blocksy-companion' ),
					],
				],
			],

			'color_switch_icon_state' => [
				'label' => __( 'Reverse Icon State', 'blocksy-companion' ),
				'type' => 'ct-switch',
				'value' => 'no',
				'setting' => [ 'transport' => 'postMessage' ],
			],

			'icon_size' => [
				'label' => __( 'Icon Size', 'blocksy-companion' ),
				'type' => 'ct-slider',
				'min' => 5,
				'max' => 50,
				'value' => 15,
				'responsive' => true,
				'divider' => 'top',
				'setting' => [ 'transport' => 'postMessage' ],
			],

			'color_switch_label_visibility' => [
				'label' => __( 'Label Visibility', 'blocksy-companion' ),
				'type' => 'ct-visibility',
				'design' => 'block',
				'divider' => 'top',
				'allow_empty' => true,
				'setting' => [ 'transport' => 'postMessage' ],
				'value' => blocksy_default_responsive_value([
					'desktop' => false,
					'tablet' => false,
					'mobile' => false,
				]),

				'choices' => blocksy_ordered_keys([
					'desktop' => __( 'Desktop', 'blocksy-companion' ),
					'tablet' => __( 'Tablet', 'blocksy-companion' ),
					'mobile' => __( 'Mobile', 'blocksy-companion' ),
				]),
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [
					'any' => [
						'color_switch_label_visibility/desktop' => true,
						'color_switch_label_visibility/tablet' => true,
						'color_switch_label_visibility/mobile' => true,
					]
				],
				'options' => [
					'color_switch_label_position' => [
						'type' => 'ct-radio',
						'label' => __( 'Label Position', 'blocksy-companion' ),
						'value' => 'left',
						'view' => 'text',
						'divider' => 'top',
						'design' => 'block',
						'responsive' => [ 'tablet' => 'skip' ],
						'choices' => [
							'left' => __( 'Left', 'blocksy-companion' ),
							'right' => __( 'Right', 'blocksy-companion' ),
							'bottom' => __( 'Bottom', 'blocksy-companion' ),
						],
					],

					'dark_mode_label' => [
						'label' => __( 'Dark Mode Label', 'blocksy-companion' ),
						'type' => 'text',
						'divider' => 'top',
						'design' => 'block',
						'value' => __( 'Dark Mode', 'blocksy-companion' ),
						'sync' => 'live',
						'responsive' => [
							'tablet' => 'skip'
						],
					],

					'light_mode_label' => [
						'label' => __( 'Light Mode Label', 'blocksy-companion' ),
						'type' => 'text',
						'divider' => 'top',
						'design' => 'block',
						'value' => __( 'Light Mode', 'blocksy-companion' ),
						'sync' => 'live',
						'responsive' => [
							'tablet' => 'skip'
						],
					],
				],
			],

			'default_color_mode' => [
				'label' => __('Default Color Mode', 'blocksy-companion'),
				'type' => 'ct-radio',
				'value' => 'light',
				'view' => 'text',
				'design' => 'block',
				'divider' => 'top:full',
				'setting' => [ 'transport' => 'postMessage' ],
				'choices' => [
					'light' => __( 'Light', 'blocksy-companion' ),
					'dark' => __( 'Dark', 'blocksy-companion' ),
					'system' => __( 'OS Aware', 'blocksy-companion' ),
				],
				'desc' => __('Choose the default color mode that a user will see when it visits your site.', 'blocksy-companion'),
			],

		],
	],

	blocksy_rand_md5() => [
		'title' => __( 'Design', 'blocksy-companion' ),
		'type' => 'tab',
		'options' => [

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [
					'any' => [
						'color_switch_label_visibility/desktop' => true,
						'color_switch_label_visibility/tablet' => true,
						'color_switch_label_visibility/mobile' => true,
					]
				],
				'options' => [
					'color_switch_label_font' => [
						'type' => 'ct-typography',
						'label' => __( 'Label Font', 'blocksy-companion' ),
						'value' => blocksy_typography_default_values([
							'size' => '12px',
							'variation' => 'n6',
							'text-transform' => 'uppercase',
						]),
						'setting' => [ 'transport' => 'postMessage' ],
					],

					blocksy_rand_md5() => [
						'type' => 'ct-labeled-group',
						'label' => __( 'Label Font Color', 'blocksy-companion' ),
						'responsive' => true,
						'choices' => [
							[
								'id' => 'header_color_switch_font_color',
								'label' => __('Default State', 'blocksy-companion')
							],

							[
								'id' => 'transparent_header_color_switch_font_color',
								'label' => __('Transparent State', 'blocksy-companion'),
								'condition' => [
									'row' => '!offcanvas',
									'builderSettings/has_transparent_header' => 'yes',
								],
							],

							[
								'id' => 'sticky_header_color_switch_font_color',
								'label' => __('Sticky State', 'blocksy-companion'),
								'condition' => [
									'row' => '!offcanvas',
									'builderSettings/has_sticky_header' => 'yes',
								],
							],
						],
						'options' => [
							'header_color_switch_font_color' => [
								'label' => __( 'Font Color', 'blocksy-companion' ),
								'type'  => 'ct-color-picker',
								'design' => 'block:right',
								'responsive' => true,
								'setting' => [ 'transport' => 'postMessage' ],
								'value' => [
									'default' => [
										'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
									],

									'hover' => [
										'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
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
										'inherit' => 'var(--theme-link-hover-color)'
									],
								],
							],

							'transparent_header_color_switch_font_color' => [
								'label' => __( 'Font Color', 'blocksy-companion' ),
								'type'  => 'ct-color-picker',
								'design' => 'block:right',
								'responsive' => true,
								'setting' => [ 'transport' => 'postMessage' ],
								'value' => [
									'default' => [
										'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
									],

									'hover' => [
										'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
									],
								],

								'pickers' => [
									[
										'title' => __( 'Initial', 'blocksy-companion' ),
										'id' => 'default',
									],

									[
										'title' => __( 'Hover', 'blocksy-companion' ),
										'id' => 'hover',
									],
								],
							],

							'sticky_header_color_switch_font_color' => [
								'label' => __( 'Font Color', 'blocksy-companion' ),
								'type'  => 'ct-color-picker',
								'design' => 'block:right',
								'responsive' => true,
								'setting' => [ 'transport' => 'postMessage' ],
								'value' => [
									'default' => [
										'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
									],

									'hover' => [
										'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
									],
								],

								'pickers' => [
									[
										'title' => __( 'Initial', 'blocksy-companion' ),
										'id' => 'default',
									],

									[
										'title' => __( 'Hover', 'blocksy-companion' ),
										'id' => 'hover',
									],
								],
							],
						],
					],

					blocksy_rand_md5() => [
						'type' => 'ct-divider',
					],
				],
			],

			blocksy_rand_md5() => [
				'type' => 'ct-labeled-group',
				'label' => __( 'Icon Color', 'blocksy-companion' ),
				'responsive' => true,
				'choices' => [
					[
						'id' => 'header_color_switch_icon_color',
						'label' => __('Default State', 'blocksy-companion')
					],

					[
						'id' => 'transparent_header_color_switch_icon_color',
						'label' => __('Transparent State', 'blocksy-companion'),
						'condition' => [
							'row' => '!offcanvas',
							'builderSettings/has_transparent_header' => 'yes',
						],
					],

					[
						'id' => 'sticky_header_color_switch_icon_color',
						'label' => __('Sticky State', 'blocksy-companion'),
						'condition' => [
							'row' => '!offcanvas',
							'builderSettings/has_sticky_header' => 'yes',
						],
					],
				],
				'options' => [
					'header_color_switch_icon_color' => [
						'label' => __( 'Icon Color', 'blocksy-companion' ),
						'type'  => 'ct-color-picker',
						'design' => 'block:right',
						'responsive' => true,
						'setting' => [ 'transport' => 'postMessage' ],
						'value' => [
							'default' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],

							'hover' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],
						],

						'pickers' => [
							[
								'title' => __( 'Initial', 'blocksy-companion' ),
								'id' => 'default',
								'inherit' => 'var(--theme-text-color)',
							],

							[
								'title' => __( 'Hover', 'blocksy-companion' ),
								'id' => 'hover',
								'inherit' => 'var(--theme-palette-color-2)',
							],
						],
					],

					'transparent_header_color_switch_icon_color' => [
						'label' => __( 'Icon Color', 'blocksy-companion' ),
						'type'  => 'ct-color-picker',
						'design' => 'block:right',
						'responsive' => true,
						'setting' => [ 'transport' => 'postMessage' ],
						'value' => [
							'default' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],

							'hover' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],
						],

						'pickers' => [
							[
								'title' => __( 'Initial', 'blocksy-companion' ),
								'id' => 'default',
							],

							[
								'title' => __( 'Hover', 'blocksy-companion' ),
								'id' => 'hover',
							],
						],
					],

					'sticky_header_color_switch_icon_color' => [
						'label' => __( 'Icon Color', 'blocksy-companion' ),
						'type'  => 'ct-color-picker',
						'design' => 'block:right',
						'responsive' => true,
						'setting' => [ 'transport' => 'postMessage' ],
						'value' => [
							'default' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],

							'hover' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],
						],

						'pickers' => [
							[
								'title' => __( 'Initial', 'blocksy-companion' ),
								'id' => 'default',
							],

							[
								'title' => __( 'Hover', 'blocksy-companion' ),
								'id' => 'hover',
							],
						],
					],
				],
			],


			'container_margin' => [
				'label' => __( 'Margin', 'blocksy-companion' ),
				'type' => 'ct-spacing',
				'divider' => 'top',
				'setting' => [ 'transport' => 'postMessage' ],
				'value' => blocksy_spacing_value(),
				'responsive' => true
			],

		],
	],

	blocksy_rand_md5() => [
		'type' => 'ct-condition',
		'condition' => [ 'wp_customizer_current_view' => 'tablet|mobile' ],
		'options' => [

			blocksy_rand_md5() => [
				'type' => 'ct-divider',
			],

			'header_color_switch_visibility' => [
				'label' => __( 'Element Visibility', 'blocksy-companion' ),
				'type' => 'ct-visibility',
				'design' => 'block',
				'setting' => [ 'transport' => 'postMessage' ],
				'allow_empty' => true,
				'value' => blocksy_default_responsive_value([
					'tablet' => true,
					'mobile' => true,
				]),

				'choices' => blocksy_ordered_keys([
					'tablet' => __( 'Tablet', 'blocksy-companion' ),
					'mobile' => __( 'Mobile', 'blocksy-companion' ),
				]),
			],

		],
	],

];
