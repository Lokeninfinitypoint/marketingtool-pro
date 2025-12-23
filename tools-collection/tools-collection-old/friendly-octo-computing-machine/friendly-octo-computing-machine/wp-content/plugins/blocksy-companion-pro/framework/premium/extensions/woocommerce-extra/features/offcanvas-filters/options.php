<?php

$options = [
	'label' => __( 'Filters Canvas', 'blocksy-companion' ),
	'type' => 'ct-panel',
	'switch' => true,
	'value' => 'no',
	'sync' => [
		[
			'id' => 'shortcuts_container_layers'
		],

		blocksy_sync_whole_page([
			'loader_selector' => '.woo-listing-top, #woo-filters-panel'
		])
	],
	'inner-options' => [

		blocksy_rand_md5() => [
			'title' => __( 'General', 'blocksy-companion' ),
			'type' => 'tab',
			'options' => [

				'woocommerce_filter_type' => [
					'label' => false,
					'type' => 'ct-image-picker',
					'value' => 'type-1',
					'choices' => [
						'type-1' => [
							'src' => blocksy_image_picker_url('woo-filters-type-1.svg'),
							'title' => __('Type 1', 'blocksy-companion'),
						],

						'type-2' => [
							'src' => blocksy_image_picker_url('woo-filters-type-2.svg'),
							'title' => __('Type 2', 'blocksy-companion'),
						],
					],
				],

				'woocommerce_filter_icon_type' => [
					'label' => __( 'Trigger Icon Type', 'blocksy-companion' ),
					'type' => 'ct-image-picker',
					'value' => 'type-1',
					'divider' => 'top:full',
					'attr' => [
						'data-type' => 'background',
						'data-columns' => '4',
					],
					'sync' => blocksy_sync_whole_page([
						'loader_selector' => '.ct-toggle-filter-panel'
					]),
					'choices' => [
						'type-1' => [
							'src'   => blocksy_image_picker_file( 'filter-1' ),
							'title' => __( 'Type 1', 'blocksy-companion' ),
						],

						'type-2' => [
							'src'   => blocksy_image_picker_file( 'filter-2' ),
							'title' => __( 'Type 2', 'blocksy-companion' ),
						],

						'type-3' => [
							'src'   => blocksy_image_picker_file( 'filter-3' ),
							'title' => __( 'Type 3', 'blocksy-companion' ),
						],

						'type-4' => [
							'src'   => blocksy_image_picker_file( 'filter-4' ),
							'title' => __( 'Type 4', 'blocksy-companion' ),
						],
					],
				],

				'woocommerce_filter_visibility' => [
					'label' => __( 'Trigger Visibility', 'blocksy-companion' ),
					'type' => 'ct-visibility',
					'design' => 'block',
					'divider' => 'top',
					'allow_empty' => true,
					'setting' => [ 'transport' => 'postMessage' ],
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

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [
						'any' => [
							'woocommerce_filter_visibility/desktop' => true,
							'woocommerce_filter_visibility/tablet' => true,
							'woocommerce_filter_visibility/mobile' => true,
						]
					],
					'options' => [
						'woocommerce_filter_label' => [
							'label' => __( 'Trigger Label', 'blocksy-companion' ),
							'type' => 'text',
							'divider' => 'top',
							'design' => 'block',
							'value' => __( 'Filter', 'blocksy-companion' ),
							'sync' => 'live',
						],
					]
				],

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [ 'woocommerce_filter_type' => 'type-1' ],
					'options' => [

						'filter_panel_position' => [
							'label' => __('Panel Reveal', 'blocksy-companion'),
							'type' => 'ct-radio',
							'value' => 'right',
							'view' => 'text',
							'design' => 'block',
							'divider' => 'top:full',
							'setting' => [ 'transport' => 'postMessage' ],
							'choices' => [
								'left' => __( 'Left Side', 'blocksy-companion' ),
								'right' => __( 'Right Side', 'blocksy-companion' ),
							],
						],

						'filter_panel_width' => [
							'label' => __( 'Panel Width', 'blocksy-companion' ),
							'type' => 'ct-slider',
							'value' => [
								'desktop' => '500px',
								'tablet' => '65vw',
								'mobile' => '90vw',
							],
							'units' => blocksy_units_config([
								[ 'unit' => 'px', 'min' => 0, 'max' => 1000 ],
							]),
							'divider' => 'top',
							'responsive' => true,
							'setting' => [ 'transport' => 'postMessage' ],
						],

						'filter_panel_content_vertical_alignment' => [
							'type' => 'ct-radio',
							'label' => __( 'Vertical Alignment', 'blocksy-companion' ),
							'view' => 'text',
							'design' => 'block',
							'divider' => 'top',
							'responsive' => true,
							'attr' => [ 'data-type' => 'vertical-alignment' ],
							'setting' => [ 'transport' => 'postMessage' ],
							'value' => 'flex-start',
							'choices' => [
								'flex-start' => '',
								'center' => '',
								'flex-end' => '',
							],
						],
					]
				],

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [ 'woocommerce_filter_type' => 'type-2' ],
					'options' => [

						'filter_panel_height_type' => [
							'label' => __('Panel Height', 'blocksy-companion'),
							'type' => 'ct-radio',
							'value' => 'auto',
							'view' => 'text',
							'design' => 'block',
							'divider' => 'top:full',
							'sync' => blocksy_sync_whole_page([
								'loader_selector' => '#woo-filters-panel'
							]),
							'choices' => [
								'auto' => __( 'Auto', 'blocksy-companion' ),
								'custom' => __( 'Custom', 'blocksy-companion' ),
							],
						],

						blocksy_rand_md5() => [
							'type' => 'ct-condition',
							'condition' => [ 'filter_panel_height_type' => 'custom' ],
							'options' => [

								'filter_panel_height' => [
									'label' => __( 'Custom Height', 'blocksy-companion' ),
									'type' => 'ct-slider',
									'value' => [
										'desktop' => '250px',
										'tablet' => '250px',
										'mobile' => '250px',
									],
									'units' => blocksy_units_config([
										[ 'unit' => 'px', 'min' => 0, 'max' => 1000 ],
									]),
									'responsive' => true,
									'setting' => [ 'transport' => 'postMessage' ],
								],

							],
						],

						'filter_panel_columns' => [
							'label' => __('Panel Columns', 'blocksy-companion'),
							'type' => 'ct-number',
							'value' => [
								'desktop' => 4,
								'tablet' => 2,
								'mobile' => 1
							],
							'min' => 1,
							'max' => 6,
							'design' => 'block',
							'divider' => 'top',
							'attr' => ['data-position' => 'right'],
							'sync' => 'live',
							'responsive' => true,
						],

						'filter_panel_behaviour' => [
							'label' => __('Panel Default State', 'blocksy-companion'),
							'type' => 'ct-radio',
							'value' => 'no',
							'view' => 'text',
							'design' => 'block',
							'divider' => 'top',
							'choices' => [
								'no' => __( 'Closed', 'blocksy-companion' ),
								'yes' => __( 'Opened', 'blocksy-companion' ),
							],
							'sync' => blocksy_sync_whole_page([
								'loader_selector' => '.woo-listing-top, #woo-filters-panel'
							]),
						],

						blocksy_rand_md5() => [
							'type' => 'ct-condition',
							'condition' => [ 'filter_panel_behaviour' => 'yes' ],
							'options' => [

								'filter_panel_visibility' => [
									'label' => __( 'Panel Visibility', 'blocksy-companion' ),
									'type' => 'ct-visibility',
									'design' => 'block',
									'divider' => 'top',
									'allow_empty' => true,
									'setting' => [ 'transport' => 'postMessage' ],
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
					]
				],
			],
		],

		blocksy_rand_md5() => [
			'title' => __( 'Design', 'blocksy-companion' ),
			'type' => 'tab',
			'options' => [

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [ 'woocommerce_filter_type' => 'type-1' ],
					'options' => [

						'filter_panel_background' => [
							'label' => __( 'Panel Background', 'blocksy-companion' ),
							'type'  => 'ct-background',
							'design' => 'block:right',
							'responsive' => true,
							'divider' => 'top',
							'setting' => [ 'transport' => 'postMessage' ],
							'value' => blocksy_background_default_value([
								'backgroundColor' => [
									'default' => [
										'color' => 'var(--theme-palette-color-8)'
									],
								],
							])
						],

						'filter_panel_backgrop' => [
							'label' => __( 'Panel Backdrop', 'blocksy-companion' ),
							'type'  => 'ct-background',
							'design' => 'block:right',
							'responsive' => true,
							'divider' => 'top',
							'setting' => [ 'transport' => 'postMessage' ],
							'value' => blocksy_background_default_value([
								'backgroundColor' => [
									'default' => [
										'color' => 'rgba(18, 21, 25, 0.6)'
									],
								],
							])
						],

						'filter_panel_shadow' => [
							'label' => __( 'Panel Shadow', 'blocksy-companion' ),
							'type' => 'ct-box-shadow',
							'design' => 'block',
							'divider' => 'top',
							'responsive' => true,
							'value' => blocksy_box_shadow_value([
								'enable' => true,
								'h_offset' => 0,
								'v_offset' => 0,
								'blur' => 70,
								'spread' => 0,
								'inset' => false,
								'color' => [
									'color' => 'rgba(0, 0, 0, 0.35)',
								],
							])
						],

						'filter_panel_close_button_type' => [
							'label' => __('Close Button Type', 'blocksy-companion'),
							'type' => 'ct-select',
							'value' => 'type-1',
							'view' => 'text',
							'design' => 'inline',
							'divider' => 'top:full',
							'setting' => [ 'transport' => 'postMessage' ],
							'choices' => blocksy_ordered_keys(
								[
									'type-1' => __( 'Simple', 'blocksy-companion' ),
									'type-2' => __( 'Border', 'blocksy-companion' ),
									'type-3' => __( 'Background', 'blocksy-companion' ),
								]
							),
						],

						'filter_panel_close_button_color' => [
							'label' => __( 'Icon Color', 'blocksy-companion' ),
							'type'  => 'ct-color-picker',
							'design' => 'block',
							'divider' => 'top',
							'responsive' => true,
							'setting' => [ 'transport' => 'postMessage' ],
							'value' => [
								'default' => [
									'color' => 'rgba(0, 0, 0, 0.5)',
								],

								'hover' => [
									'color' => 'rgba(0, 0, 0, 0.8)',
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

						blocksy_rand_md5() => [
							'type' => 'ct-condition',
							'condition' => [ 'filter_panel_close_button_type' => 'type-2' ],
							'options' => [

								'filter_panel_close_button_border_color' => [
									'label' => __( 'Border Color', 'blocksy-companion' ),
									'type'  => 'ct-color-picker',
									'design' => 'block',
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
											'inherit' => 'rgba(0, 0, 0, 0.5)'
										],

										[
											'title' => __( 'Hover', 'blocksy-companion' ),
											'id' => 'hover',
											'inherit' => 'rgba(0, 0, 0, 0.5)'
										],
									],
								],

							],
						],

						blocksy_rand_md5() => [
							'type' => 'ct-condition',
							'condition' => [ 'filter_panel_close_button_type' => 'type-3' ],
							'options' => [

								'filter_panel_close_button_shape_color' => [
									'label' => __( 'Background Color', 'blocksy-companion' ),
									'type'  => 'ct-color-picker',
									'design' => 'block',
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
											'inherit' => 'rgba(0, 0, 0, 0.5)'
										],

										[
											'title' => __( 'Hover', 'blocksy-companion' ),
											'id' => 'hover',
											'inherit' => 'rgba(0, 0, 0, 0.5)'
										],
									],
								],

							],
						],

						'filter_panel_close_button_icon_size' => [
							'label' => __( 'Icon Size', 'blocksy-companion' ),
							'type' => 'ct-number',
							'design' => 'inline',
							'value' => 12,
							'min' => 5,
							'max' => 50,
							'divider' => 'top',
							'setting' => [ 'transport' => 'postMessage' ],
						],

						blocksy_rand_md5() => [
							'type' => 'ct-condition',
							'condition' => [ 'filter_panel_close_button_type' => '!type-1' ],
							'options' => [

								'filter_panel_close_button_border_radius' => [
									'label' => __( 'Border Radius', 'blocksy-companion' ),
									'type' => 'ct-number',
									'design' => 'inline',
									'value' => 5,
									'min' => 0,
									'max' => 100,
									'divider' => 'top',
									'setting' => [ 'transport' => 'postMessage' ],
								],

							],
						],

					],
				],

			],
		],

		blocksy_rand_md5() => [
			'type' => 'ct-condition',
			'condition' => [
				'any' => [
					'woocommerce_filter_type' => 'type-1',
					'all' => [
						'woocommerce_filter_type' => 'type-2',
						'filter_panel_behaviour' => 'no',
					]
				]
			],
			'options' => [

				'filter_ajax_reveal' => [
					'label' => __('Panel AJAX Reveal', 'blocksy-companion'),
					'type' => 'ct-switch',
					'value' => 'no',
					'divider' => 'top',
					'sync' => blocksy_sync_whole_page([
						'loader_selector' => '#woo-filters-panel'
					]),
				],

			]
		],

		blocksy_rand_md5() => [
			'type' => 'ct-condition',
			'condition' => [ 'woocommerce_filter_type' => 'type-1' ],
			'options' => [

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => ['woo_filters_ajax' => 'yes'],
					'values_source' => 'global',
					'options' => [

						'filter_panel_close_on_select' => [
							'label' => __('Auto Close Panel', 'blocksy-companion'),
							'desc' => __('Automatically close the panel when a filter option is selected.', 'blocksy-companion'),
							'type' => 'ct-switch',
							'value' => 'no',
							'divider' => 'top',
						],

					]
				]
				
			]
		],

		'filter_source' => [
			'label' => __('Widget Area Source', 'blocksy-companion'),
			'type' => 'ct-select',
			'value' => 'sidebar-woocommerce-offcanvas-filters',
			'view' => 'text',
			'design' => 'block',
			'divider' => 'top:full',
			'choices' => blocksy_ordered_keys(
				[
					'sidebar-woocommerce' => __( 'WooCommerce Sidebar', 'blocksy-companion' ),
					'sidebar-woocommerce-offcanvas-filters' => __( 'WooCommerce Filters Canvas', 'blocksy-companion' ),
				]
			),
		],

		blocksy_rand_md5() => [
			'type' => 'ct-title',
			'label' => __( 'Filter Widgets', 'blocksy-companion' ),
		],

		blocksy_rand_md5() => [
			'title' => __( 'Widgets', 'blocksy-companion' ),
			'type' => 'tab',
			'options' => [

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [ 'filter_source' => 'sidebar-woocommerce-offcanvas-filters' ],
					'options' => [

						'widget' => [
							'type' => 'ct-widget-area',
							'sidebarId' => 'sidebar-woocommerce-offcanvas-filters'
						]

					],
				],

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [ 'filter_source' => 'sidebar-woocommerce' ],
					'options' => [

						'widget' => [
							'type' => 'ct-widget-area',
							'sidebarId' => 'sidebar-woocommerce'
						]

					],
				],

			]
		],

		blocksy_rand_md5() => [
			'title' => __( 'Design', 'blocksy-companion' ),
			'type' => 'tab',
			'options' => [

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [ 'woocommerce_filter_type' => 'type-1' ],
					'options' => [

						'panel_widgets_spacing' => [
							'label' => __( 'Widgets Vertical Spacing', 'blocksy-companion' ),
							'type' => 'ct-slider',
							'min' => 0,
							'max' => 100,
							'value' => 60,
							'responsive' => true,
							'setting' => [ 'transport' => 'postMessage' ],
						],

						blocksy_rand_md5() => [
							'type' => 'ct-divider',
						],

					],
				],

				'filter_panel_widgets_font' => [
					'type' => 'ct-typography',
					'label' => __( 'Widgets Font', 'blocksy-companion' ),
					'value' => blocksy_typography_default_values([
						// 'size' => '16px',
					]),
					'setting' => [ 'transport' => 'postMessage' ],
				],

				'filter_panel_widgets_font_color' => [
					'label' => __( 'Widgets Font Color', 'blocksy-companion' ),
					'type'  => 'ct-color-picker',
					'design' => 'block:right',
					'responsive' => true,
					'divider' => 'bottom',
					'setting' => [ 'transport' => 'postMessage' ],
					'value' => [
						'default' => [
							'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
						],

						'link_initial' => [
							'color' => 'var(--theme-text-color)',
						],

						'link_hover' => [
							'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
						],
					],

					'pickers' => [
						[
							'title' => __( 'Text Initial', 'blocksy-companion' ),
							'id' => 'default',
							'inherit' => 'var(--theme-text-color)'
						],

						[
							'title' => __( 'Link Initial', 'blocksy-companion' ),
							'id' => 'link_initial',
						],

						[
							'title' => __( 'Link Hover', 'blocksy-companion' ),
							'id' => 'link_hover',
							'inherit' => 'var(--theme-link-hover-color)'
						],
					],
				],
			]
		],

	],
];

