<?php

$all_pages = get_posts([
	'post_type' => 'page',
	'numberposts' => -1,
]);

$pages_choices = [
	'' => __('Select a page', 'blocksy-companion'),
];

foreach ($all_pages as $page) {
	$pages_choices[$page->ID] = $page->post_title;
}

$attribute_taxonomies = wc_get_attribute_taxonomies();
$tax_choices = [];
$taxonomies_to_compare = [];

foreach ($attribute_taxonomies as $tax) {
	$tax_choices[$tax->attribute_name] = $tax->attribute_label;

	$taxonomies_to_compare[$tax->attribute_name] = [
		'label' => $tax->attribute_label,
	];
}

$initial_conditions = [
	[
		'type' => 'include',
		'rule' => 'everywhere'
	]
];

$options = [
	'label' => __('Compare', 'blocksy-companion'),
	'type' => 'ct-panel',
	'setting' => ['transport' => 'postMessage'],
	'inner-options' => [
		blocksy_rand_md5() => [
			'title' => __('General', 'blocksy-companion'),
			'type' => 'tab',
			'options' => [

				'compare_table_placement' => [
					'label' => __('Compare Placement', 'blocksy-companion'),
					'type' => 'ct-radio',
					'value' => 'modal',
					'view' => 'text',
					'design' => 'block',
					'choices' => [
						'modal' => __('Popup', 'blocksy-companion'),
						'page' => __('Page', 'blocksy-companion'),
					],
				],

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => ['compare_table_placement' => 'page'],
					'options' => [
						'woocommerce_compare_page' => [
							'label' => __('Select Page', 'blocksy-companion'),
							'type' => 'ct-select',
							'value' => '',
							'view' => 'text',
							'design' => 'inline',
							'divider' => 'top',
							'choices' => blocksy_ordered_keys($pages_choices),
							'desc' => __('Select a page where the compare table will be outputted.', 'blocksy-companion'),
						],
					]
				],

				'product_compare_layout' => [
					'label' => __('Compare Table Fields', 'blocksy-companion'),
					'type' => 'ct-layers',
					'divider' => 'top:full',
					'manageable' => true,
					'value' => apply_filters(
						'blocksy_woo_compare_layers:defaults',
						[
							[
								'id' => 'product_main',
								'enabled' => true,
							],
							[
								'id' => 'product_title',
								'enabled' => true,
							],
							[
								'id' => 'product_price',
								'enabled' => true,
							],
							[
								'id' => 'product_description',
								'enabled' => true,
							],
							[
								'id' => 'product_attributes',
								'enabled' => true,
							],
							[
								'id' => 'product_rating',
								'enabled' => false,
							],
							[
								'id' => 'product_sku',
								'enabled' => false,
							],
							[
								'id' => 'product_availability',
								'enabled' => true,
							],
							[
								'id' => 'product_add_to_cart',
								'enabled' => true,
							],
						]
					),
					'sync' => [
						[
							'selector' => '#ct-compare-modal .ct-compare-table',
							'render' => function() {
								// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								echo blc_get_ext('woocommerce-extra')
									->get_compare()
									->get_compare_table_content();
							},
							'container_inclusive' => false,
						],

						[
							// 'prefix' => 'woo_compare',
							'id' => 'woo_compare_layout_skip',
							'loader_selector' => 'skip',
							'container_inclusive' => false
						],
					],
					'settings' => apply_filters(
						'blocksy_woo_compare_layers:extra',
						[
							'product_main' => [
								'label' => __('Image', 'blocksy-companion'),
								'options' => [
									'compare_image_ratio' => [
										'label' => __('Image Ratio', 'blocksy-companion'),
										'type' => 'ct-ratio',
										'view' => 'inline',
										'value' => '3/4',
										'sync' => [
											'id' => 'woo_compare_layout_skip'
										]
									],

									'compare_image_size' => [
										'label' => __('Image Size', 'blocksy-companion'),
										'type' => 'ct-select',
										'value' => 'medium_large',
										'view' => 'text',
										'design' => 'inline',
										'choices' => blocksy_ordered_keys(
											blocksy_get_all_image_sizes()
										),
									],

									blocksy_rand_md5() => [
										'type' => 'ct-condition',
										'values_source' => 'global',
										'condition' => ['compare_table_placement' => '!page'],
										'options' => [
											'compare_row_sticky' => [
												'type'  => 'ct-switch',
												'label' => __( 'Sticky Row', 'blocksy-companion' ),
												'value' => 'no',
											],
										]
									]
								],
							],

							'product_title' => [
								'label' => __('Title', 'blocksy-companion'),
								'options' => [
									blocksy_rand_md5() => [
										'type' => 'ct-condition',
										'values_source' => 'global',
										'condition' => ['compare_table_placement' => '!page'],
										'options' => [
											'compare_row_sticky' => [
												'type'  => 'ct-switch',
												'label' => __( 'Sticky Row', 'blocksy-companion' ),
												'value' => 'no',
											],
										]
									]
								]
							],

							'product_price' => [
								'label' => __('Price', 'blocksy-companion'),
								'options' => [
									blocksy_rand_md5() => [
										'type' => 'ct-condition',
										'values_source' => 'global',
										'condition' => ['compare_table_placement' => '!page'],
										'options' => [
											'compare_row_sticky' => [
												'type'  => 'ct-switch',
												'label' => __( 'Sticky Row', 'blocksy-companion' ),
												'value' => 'no',
											],
										]
									]
								]
							],

							'product_add_to_cart' => [
								'label' => __('Add to Cart', 'blocksy-companion'),
								'options' => [
									blocksy_rand_md5() => [
										'type' => 'ct-condition',
										'values_source' => 'global',
										'condition' => ['compare_table_placement' => '!page'],
										'options' => [
											'compare_row_sticky' => [
												'type'  => 'ct-switch',
												'label' => __( 'Sticky Row', 'blocksy-companion' ),
												'value' => 'no',
											],
										]
									]
								]
							],

							'product_description' => [
								'label' => __('Description', 'blocksy-companion'),
								'options' => [
									'excerpt_length' => [
										'label' => __('Length', 'blocksy-companion'),
										'type' => 'ct-number',
										'design' => 'inline',
										'value' => 40,
										'min' => 1,
										'max' => 300,
									],

									blocksy_rand_md5() => [
										'type' => 'ct-condition',
										'values_source' => 'global',
										'condition' => ['compare_table_placement' => '!page'],
										'options' => [
											'compare_row_sticky' => [
												'type'  => 'ct-switch',
												'label' => __( 'Sticky Row', 'blocksy-companion' ),
												'value' => 'no',
											],
										]
									]
								],
							],

							'product_attributes' => [
								'label' => __('Attributes', 'blocksy-companion'),
								'options' => [
									'product_attributes_source' => [
										'type' => 'ct-radio',
										'label' => false,
										'value' => 'all',
										'design' => 'block',
										'disableRevertButton' => true,
										'choices' => [
											'all' => __('All', 'blocksy-companion'),
											'custom' => __('Custom', 'blocksy-companion'),
										],
									],

									blocksy_rand_md5() => [
										'type' => 'ct-condition',
										'condition' => ['product_attributes_source' => 'custom'],
										'options' => [
											'taxonomies_to_compare' => [
												'label' => false,
												'type' => 'ct-layers',
												'manageable' => true,
												'value' => [],
												'settings' => $taxonomies_to_compare
											]
										]
									],

									blocksy_rand_md5() => [
										'type' => 'ct-condition',
										'values_source' => 'global',
										'condition' => ['compare_table_placement' => '!page'],
										'options' => [
											'compare_row_sticky' => [
												'type'  => 'ct-switch',
												'label' => __( 'Sticky Row', 'blocksy-companion' ),
												'value' => 'no',
											],
										]
									]
								],
							],

							'product_rating' => [
								'label' => __('Rating', 'blocksy-companion'),
								'options' => [
									blocksy_rand_md5() => [
										'type' => 'ct-condition',
										'values_source' => 'global',
										'condition' => ['compare_table_placement' => '!page'],
										'options' => [
											'compare_row_sticky' => [
												'type'  => 'ct-switch',
												'label' => __( 'Sticky Row', 'blocksy-companion' ),
												'value' => 'no',
											],
										]
									]
								]
							],

							'product_sku' => [
								'label' => __('SKU', 'blocksy-companion'),
								'options' => [
									blocksy_rand_md5() => [
										'type' => 'ct-condition',
										'values_source' => 'global',
										'condition' => ['compare_table_placement' => '!page'],
										'options' => [
											'compare_row_sticky' => [
												'type'  => 'ct-switch',
												'label' => __( 'Sticky Row', 'blocksy-companion' ),
												'value' => 'no',
											],
										]
									]
								]
							],

							'product_availability' => [
								'label' => __('Availability', 'blocksy-companion'),
								'options' => [
									blocksy_rand_md5() => [
										'type' => 'ct-condition',
										'values_source' => 'global',
										'condition' => ['compare_table_placement' => '!page'],
										'options' => [
											'compare_row_sticky' => [
												'type'  => 'ct-switch',
												'label' => __( 'Sticky Row', 'blocksy-companion' ),
												'value' => 'no',
											],
										]
									]
								]
							],
						]
					),
				],
			],
		],

		blocksy_rand_md5() => [
			'title' => __('Design', 'blocksy-companion'),
			'type' => 'tab',
			'options' => [

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [ 'compare_table_placement' => 'modal' ],
					'options' => [

						'compare_modal_background' => [
							'label' => __( 'Popup Background', 'blocksy-companion' ),
							'type'  => 'ct-color-picker',
							'divider' => 'top',
							'responsive' => true,
							'setting' => [ 'transport' => 'postMessage' ],
							'value' => [
								'default' => [
									'color' => 'var(--theme-palette-color-8)'
								],
							],

							'pickers' => [
								[
									'title' => __( 'Initial', 'blocksy-companion' ),
									'id' => 'default',
								],
							],
						],

						'compare_modal_backdrop' => [
							'label' => __( 'Popup Backdrop', 'blocksy-companion' ),
							'type'  => 'ct-color-picker',
							'divider' => 'top',
							'responsive' => true,
							'setting' => [ 'transport' => 'postMessage' ],
							'value' => [
								'default' => [
									'color' => 'rgba(18, 21, 25, 0.8)'
								],
							],

							'pickers' => [
								[
									'title' => __( 'Initial', 'blocksy-companion' ),
									'id' => 'default',
								],
							],
						],

						'compare_modal_shadow' => [
							'label' => __( 'Popup Shadow', 'blocksy-companion' ),
							'type' => 'ct-box-shadow',
							'responsive' => true,
							'divider' => 'top:full',
							'sync' => 'live',
							'value' => blocksy_box_shadow_value([
								'enable' => true,
								'h_offset' => 0,
								'v_offset' => 50,
								'blur' => 100,
								'spread' => 0,
								'inset' => false,
								'color' => [
									'color' => 'rgba(18, 21, 25, 0.5)',
								],
							])
						],

						'compare_modal_radius' => [
							'label' => __( 'Popup Border Radius', 'blocksy-companion' ),
							'type' => 'ct-spacing',
							'divider' => 'top',
							'setting' => [ 'transport' => 'postMessage' ],
							'value' => blocksy_spacing_value(),
							'inputAttr' => [
								'placeholder' => '7'
							],
							'min' => 0,
							'responsive' => true
						],
					],
				],
			],
		],

		'product_compare_bar' => [
			'label' => __('Compare Bar', 'blocksy-companion'),
			'type' => 'ct-switch',
			'value' => 'no',
			'divider' => 'top:full'
		],

		blocksy_rand_md5() => [
			'type' => 'ct-condition',
			'condition' => [ 'product_compare_bar' => 'yes' ],
			'options' => [

				blocksy_rand_md5() => [
					'title' => __( 'General', 'blocksy-companion' ),
					'type' => 'tab',
					'options' => [

						'product_compare_bar_height' => [
							'label' => __( 'Container Height', 'blocksy-companion' ),
							'type' => 'ct-slider',
							'min' => 70,
							'max' => 150,
							'value' => 70,
							'responsive' => true,
							'setting' => [ 'transport' => 'postMessage' ],
						],

						'product_compare_bar_button_icon' => [
							'type' => 'icon-picker',
							'label' => __('Button Icon', 'blocksy-companion'),
							'design' => 'inline',
							'divider' => 'top',
							'value' => [
								'icon' => 'blc blc-compare',
							],
							'sync' => [
								'selector' => '.ct-compare-bar',
								'loader_selector' => '.ct-button',
								'render' => function () {
									blocksy_render_view_e(
										dirname(__FILE__) . '/views/bar.php',
										[]
									);
								}
							],

						],

						'product_compare_bar_button_label' => [
							'label' => __( 'Button Label', 'blocksy-companion' ),
							'type' => 'text',
							'divider' => 'top',
							'design' => 'block',
							'value' => __( 'Compare', 'blocksy-companion' ),
							'sync' => 'live',
						],

						'product_compare_bar_visibility' => [
							'label' => __( 'Visibility', 'blocksy-companion' ),
							'type' => 'ct-visibility',
							'design' => 'block',
							'divider' => 'top:full',
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

						'compare_bar_conditions' => [
							'label' => __('Display Conditions', 'blocksy-companion'),
							'type' => 'blocksy-display-condition',
							'divider' => 'top',
							'value' => $initial_conditions,
							'display' => 'modal',

							'modalTitle' => __('Compare Bar Display Conditions', 'blocksy-companion'),
							'modalDescription' => __('Add one or more conditions to display the Compare bar.', 'blocksy-companion'),
							'design' => 'block',
						],

					],
				],

				blocksy_rand_md5() => [
					'title' => __( 'Design', 'blocksy-companion' ),
					'type' => 'tab',
					'options' => [

						'product_compare_bar_button_font_color' => [
							'label' => __( 'Button Font Color', 'blocksy-companion' ),
							'type'  => 'ct-color-picker',
							'design' => 'block:right',
							'responsive' => true,
							'sync' => 'live',
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
									'inherit' => 'var(--theme-button-text-initial-color)',
								],

								[
									'title' => __( 'Hover', 'blocksy-companion' ),
									'id' => 'hover',
									'inherit' => 'var(--theme-button-text-hover-color)',
								],
							],
						],

						'product_compare_bar_button_background_color' => [
							'label' => __( 'Button Background Color', 'blocksy-companion' ),
							'type'  => 'ct-color-picker',
							'design' => 'block:right',
							'responsive' => true,
							'sync' => 'live',
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
									'inherit' => 'var(--theme-button-background-initial-color)'
								],

								[
									'title' => __( 'Hover', 'blocksy-companion' ),
									'id' => 'hover',
									'inherit' => 'var(--theme-button-background-hover-color)'
								],
							],
						],

						'product_compare_bar_background' => [
							'label' => __( 'Container Background', 'blocksy-companion' ),
							'type'  => 'ct-color-picker',
							'divider' => 'top',
							'responsive' => true,
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
									'inherit' => 'var(--theme-palette-color-4)'
								],
							],
						],

					],
				],

			],
		],
	],
];
