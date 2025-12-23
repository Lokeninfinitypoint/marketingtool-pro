<?php

$cpt_choices = [
	'post' => __('Posts', 'blocksy-companion'),
	'page' => __('Pages', 'blocksy-companion')
];

$cpt_options = [
	'post' => true,
	'page' => true
];

if (class_exists('WooCommerce')) {
	$cpt_choices['product'] = __('Products', 'blocksy-companion');
	$cpt_options['product'] = true;
}

$all_cpts = [];

if (blc_theme_functions()->blocksy_manager()) {
	$all_cpts = blc_theme_functions()->blocksy_manager()->post_types->get_supported_post_types();
}

if (function_exists('is_bbpress')) {
	$all_cpts[] = 'forum';
	$all_cpts[] = 'topic';
	$all_cpts[] = 'reply';
}

if (class_exists('Tribe__Events__Main')) {
	$all_cpts[] = 'tribe_events';
}

foreach ($all_cpts as $single_cpt) {
	if (get_post_type_object($single_cpt)) {
		$cpt_choices[$single_cpt] = get_post_type_labels(
			get_post_type_object($single_cpt)
		)->singular_name;
	} else {
		$cpt_choices[$single_cpt] = ucfirst($single_cpt);
	}

	$cpt_options[$single_cpt] = false;
}

foreach ($cpt_choices as $cpt => $value) {
	$post_type_object = get_post_type_object($cpt);

	if ($post_type_object && isset($post_type_object->show_in_rest) && $post_type_object->show_in_rest) {
		continue;
	}

	unset($cpt_choices[$cpt]);
	unset($cpt_options[$cpt]);
}

$options = [
	blocksy_rand_md5() => [
		'title' => __( 'General', 'blocksy-companion' ),
		'type' => 'tab',
		'options' => array_merge([

			'search_box_placeholder' => [
				'label' => __( 'Placeholder Text', 'blocksy-companion' ),
				'type' => 'text',
				'design' => 'block',
				'value' => __( 'Search', 'blocksy-companion' ),
				'setting' => [
					'transport' => 'postMessage'
				],
			],

			'searchBoxMaxWidth' => [
				'label' => __( 'Input Maximum Width', 'blocksy-companion' ),
				'type' => 'ct-slider',
				'min' => 10,
				'max' => 100,
				'value' => 25,
				'responsive' => true,
				'divider' => 'top',
				'defaultUnit' => '%',
				'setting' => [ 'transport' => 'postMessage' ],
			],

			'headerSearchBoxHeight' => [
				'label' => __( 'Input Height', 'blocksy-companion' ),
				'type' => 'ct-slider',
				'min' => 20,
				'max' => 80,
				'value' => 40,
				'responsive' => true,
				'divider' => 'top',
				'setting' => [ 'transport' => 'postMessage' ],
			],

			'icon' => [
				'type' => 'icon-picker',
				'label' => __('Icon', 'blocksy-companion'),
				'design' => 'inline',
				'divider' => 'top',
				'value' => [
					'icon' => 'blc blc-search'
				]
			],

			'enable_live_results' => [
				'label' => __( 'Live Results', 'blocksy-companion' ),
				'type' => 'ct-switch',
				'value' => 'no',
				'divider' => 'top:full',
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [ 'enable_live_results' => 'yes' ],
				'options' => [

					'live_results_images' => [
						'label' => __( 'Live Results Images', 'blocksy-companion' ),
						'type' => 'ct-switch',
						'value' => 'yes',
						'divider' => 'top',
					],

					blocksy_rand_md5() => [
						'type' => 'ct-condition',
						'condition' => [ 'search_through/product' => true ],
						'options' => [
							'searchHeaderProductPrice' => [
								'label' => __( 'Live Results Product Price', 'blocksy-companion' ),
								'type' => 'ct-switch',
								'value' => 'no',
								'divider' => 'top',
								'setting' => [ 'transport' => 'postMessage' ],
							],

							'searchHeaderProductStatus' => [
								'label' => __( 'Live Results Product Status', 'blocksy-companion' ),
								'type' => 'ct-switch',
								'value' => 'no',
								'divider' => 'top',
								'setting' => [ 'transport' => 'postMessage' ],
							],
						]
					],

				],
			],

			'has_taxonomy_filter' => [
				'label' => __( 'Taxonomy Filter', 'blocksy-companion' ),
				'type' => 'ct-switch',
				'value' => 'no',
				'divider' => 'top:full',
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [ 'has_taxonomy_filter' => 'yes' ],
				'options' => [

					'taxonomy_filter_visibility' => [
						'label' => __( 'Filter Visibility', 'blocksy-companion' ),
						'type' => 'ct-visibility',
						'design' => 'block',
						// 'allow_empty' => true,
						'divider' => 'top',
						'setting' => [ 'transport' => 'postMessage' ],
						'value' => blocksy_default_responsive_value([
							'desktop' => true,
							'tablet' => true,
							'mobile' => false,
						]),

						'choices' => blocksy_ordered_keys([
							'desktop' => __( 'Desktop', 'blocksy-companion' ),
							'tablet' => __( 'Tablet', 'blocksy-companion' ),
							'mobile' => __( 'Mobile', 'blocksy-companion' ),
						]),
					],

					'has_taxonomy_children' => [
						'label' => __( 'Taxonomy Children', 'blocksy-companion' ),
						'type' => 'ct-switch',
						'value' => 'no',
						'divider' => 'top',
					],

				],
			],

			blocksy_rand_md5() => [
				'type' => 'ct-title',
				'label' => __('Search Through Criteria', 'blocksy-companion'),
				'desc' => __(
					'Chose in which post types do you want to perform searches.',
					'blocksy-companion'
				)
			],

			'search_through' => [
				'label' => false,
				'type' => 'ct-checkboxes',
				'attr' => ['data-columns' => '2'],
				'disableRevertButton' => true,
				'choices' => blocksy_ordered_keys($cpt_choices),
				'value' => $cpt_options
			],

			'search_through_taxonomy' => [
				'label' => __('Search Through Taxonomies', 'blocksy-companion'),
				'type' => 'ct-switch',
				'value' => 'no',
				'divider' => 'top',
				'desc' => __('Search through taxonomies from selected custom post types.', 'blocksy-companion'),
			],

		], $panel_type === 'footer' ? [

			blocksy_rand_md5() => [
				'type' => 'ct-divider',
			],

			'footer_search_box_horizontal_alignment' => [
				'type' => 'ct-radio',
				'label' => __( 'Horizontal Alignment', 'blocksy-companion' ),
				'view' => 'text',
				'design' => 'block',
				'responsive' => true,
				'attr' => [ 'data-type' => 'alignment' ],
				'setting' => [ 'transport' => 'postMessage' ],
				'value' => 'CT_CSS_SKIP_RULE',
				'choices' => [
					'flex-start' => '',
					'center' => '',
					'flex-end' => '',
				],
			],

			'footer_search_box_vertical_alignment' => [
				'type' => 'ct-radio',
				'label' => __( 'Vertical Alignment', 'blocksy-companion' ),
				'view' => 'text',
				'design' => 'block',
				'divider' => 'top',
				'responsive' => true,
				'attr' => [ 'data-type' => 'vertical-alignment' ],
				'setting' => [ 'transport' => 'postMessage' ],
				'value' => 'CT_CSS_SKIP_RULE',
				'choices' => [
					'flex-start' => '',
					'center' => '',
					'flex-end' => '',
				],
			],

			'footer_visibility' => [
				'label' => __('Element Visibility', 'blocksy-companion'),
				'type' => 'ct-visibility',
				'design' => 'block',
				'divider' => 'top',
				'sync' => 'live',
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

		] : []),
	],

	blocksy_rand_md5() => [
		'title' => __( 'Design', 'blocksy-companion' ),
		'type' => 'tab',
		'options' => [

			blocksy_rand_md5() => [
				'type' => 'ct-labeled-group',
				'label' => __( 'Input Font Color', 'blocksy-companion' ),
				'responsive' => true,
				'choices' => [
					[
						'id' => 'sb_font_color',
						'label' => __('Default State', 'blocksy-companion')
					],

					[
						'id' => 'transparent_sb_font_color',
						'label' => __('Transparent State', 'blocksy-companion'),
						'condition' => [
							'row' => '!offcanvas',
							'builderSettings/has_transparent_header' => 'yes',
						],
					],

					[
						'id' => 'sticky_sb_font_color',
						'label' => __('Sticky State', 'blocksy-companion'),
						'condition' => [
							'row' => '!offcanvas',
							'builderSettings/has_sticky_header' => 'yes',
						],
					],
				],
				'options' => [

					'sb_font_color' => [
						'label' => __( 'Input Font Color', 'blocksy-companion' ),
						'type'  => 'ct-color-picker',
						'design' => 'block:right',
						'responsive' => true,
						'setting' => [ 'transport' => 'postMessage' ],

						'value' => [
							'default' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],

							'focus' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],
						],

						'pickers' => [
							[
								'title' => __( 'Initial', 'blocksy-companion' ),
								'id' => 'default',
								'inherit' => 'var(--theme-form-text-initial-color, var(--theme-text-color))'
							],

							[
								'title' => __( 'Focus', 'blocksy-companion' ),
								'id' => 'focus',
								'inherit' => 'var(--theme-form-text-focus-color, var(--theme-text-color))'
							],
						],
					],

					'transparent_sb_font_color' => [
						'label' => __( 'Input Font Color', 'blocksy-companion' ),
						'type'  => 'ct-color-picker',
						'design' => 'block:right',
						'responsive' => true,
						'setting' => [ 'transport' => 'postMessage' ],

						'value' => [
							'default' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],

							'focus' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],
						],

						'pickers' => [
							[
								'title' => __( 'Initial', 'blocksy-companion' ),
								'id' => 'default',
							],

							[
								'title' => __( 'Focus', 'blocksy-companion' ),
								'id' => 'focus',
							],
						],
					],

					'sticky_sb_font_color' => [
						'label' => __( 'Input Font Color', 'blocksy-companion' ),
						'type'  => 'ct-color-picker',
						'design' => 'block:right',
						'responsive' => true,
						'setting' => [ 'transport' => 'postMessage' ],

						'value' => [
							'default' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],

							'focus' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],
						],

						'pickers' => [
							[
								'title' => __( 'Initial', 'blocksy-companion' ),
								'id' => 'default',
							],

							[
								'title' => __( 'Focus', 'blocksy-companion' ),
								'id' => 'focus',
							],
						],
					],

				],
			],

			blocksy_rand_md5() => [
				'type' => 'ct-divider',
				'attr' => [ 'data-type' => 'small' ],
			],

			blocksy_rand_md5() => [
				'type' => 'ct-labeled-group',
				'label' => __( 'Input Icon Color', 'blocksy-companion' ),
				'responsive' => true,
				'choices' => [
					[
						'id' => 'sb_icon_color',
						'label' => __('Default State', 'blocksy-companion')
					],

					[
						'id' => 'transparent_sb_icon_color',
						'label' => __('Transparent State', 'blocksy-companion'),
						'condition' => [
							'row' => '!offcanvas',
							'builderSettings/has_transparent_header' => 'yes',
						],
					],

					[
						'id' => 'sticky_sb_icon_color',
						'label' => __('Sticky State', 'blocksy-companion'),
						'condition' => [
							'row' => '!offcanvas',
							'builderSettings/has_sticky_header' => 'yes',
						],
					],
				],
				'options' => [

					'sb_icon_color' => [
						'label' => __( 'Input Icon Color', 'blocksy-companion' ),
						'type'  => 'ct-color-picker',
						'design' => 'block:right',
						'responsive' => true,
						'setting' => [ 'transport' => 'postMessage' ],

						'value' => [
							'default' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],

							'focus' => [
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
								'title' => __( 'Focus', 'blocksy-companion' ),
								'id' => 'focus',
								'inherit' => 'var(--theme-text-color)'
							],
						],
					],

					'transparent_sb_icon_color' => [
						'label' => __( 'Input Icon Color', 'blocksy-companion' ),
						'type'  => 'ct-color-picker',
						'design' => 'block:right',
						'responsive' => true,
						'setting' => [ 'transport' => 'postMessage' ],

						'value' => [
							'default' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],

							'focus' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],
						],

						'pickers' => [
							[
								'title' => __( 'Initial', 'blocksy-companion' ),
								'id' => 'default',
							],

							[
								'title' => __( 'Focus', 'blocksy-companion' ),
								'id' => 'focus',
							],
						],
					],

					'sticky_sb_icon_color' => [
						'label' => __( 'Input Icon Color', 'blocksy-companion' ),
						'type'  => 'ct-color-picker',
						'design' => 'block:right',
						'responsive' => true,
						'setting' => [ 'transport' => 'postMessage' ],

						'value' => [
							'default' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],

							'focus' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],
						],

						'pickers' => [
							[
								'title' => __( 'Initial', 'blocksy-companion' ),
								'id' => 'default',
							],

							[
								'title' => __( 'Focus', 'blocksy-companion' ),
								'id' => 'focus',
							],
						],
					],

				],
			],

			blocksy_rand_md5() => [
				'type' => 'ct-divider',
				'attr' => [ 'data-type' => 'small' ],
			],

			blocksy_rand_md5() => [
				'type' => 'ct-labeled-group',
				'label' => __( 'Input Border Color', 'blocksy-companion' ),
				'responsive' => true,
				'choices' => [
					[
						'id' => 'sb_border_color',
						'label' => __('Default State', 'blocksy-companion')
					],

					[
						'id' => 'transparent_sb_border_color',
						'label' => __('Transparent State', 'blocksy-companion'),
						'condition' => [
							'row' => '!offcanvas',
							'builderSettings/has_transparent_header' => 'yes',
						],
					],

					[
						'id' => 'sticky_sb_border_color',
						'label' => __('Sticky State', 'blocksy-companion'),
						'condition' => [
							'row' => '!offcanvas',
							'builderSettings/has_sticky_header' => 'yes',
						],
					],
				],
				'options' => [

					'sb_border_color' => [
						'label' => __( 'Input Border Color', 'blocksy-companion' ),
						'type'  => 'ct-color-picker',
						'design' => 'block:right',
						'responsive' => true,
						'setting' => [ 'transport' => 'postMessage' ],

						'value' => [
							'default' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],

							'focus' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],
						],

						'pickers' => [
							[
								'title' => __( 'Initial', 'blocksy-companion' ),
								'id' => 'default',
								'inherit' => 'var(--theme-form-field-border-initial-color)'
							],

							[
								'title' => __( 'Focus', 'blocksy-companion' ),
								'id' => 'focus',
								'inherit' => 'var(--theme-form-field-border-focus-color)'
							],
						],
					],

					'transparent_sb_border_color' => [
						'label' => __( 'Input Border Color', 'blocksy-companion' ),
						'type'  => 'ct-color-picker',
						'design' => 'block:right',
						'responsive' => true,
						'setting' => [ 'transport' => 'postMessage' ],

						'value' => [
							'default' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],

							'focus' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],
						],

						'pickers' => [
							[
								'title' => __( 'Initial', 'blocksy-companion' ),
								'id' => 'default',
							],

							[
								'title' => __( 'Focus', 'blocksy-companion' ),
								'id' => 'focus',
							],
						],
					],

					'sticky_sb_border_color' => [
						'label' => __( 'Input Border Color', 'blocksy-companion' ),
						'type'  => 'ct-color-picker',
						'design' => 'block:right',
						'responsive' => true,
						'setting' => [ 'transport' => 'postMessage' ],

						'value' => [
							'default' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],

							'focus' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],
						],

						'pickers' => [
							[
								'title' => __( 'Initial', 'blocksy-companion' ),
								'id' => 'default',
							],

							[
								'title' => __( 'Focus', 'blocksy-companion' ),
								'id' => 'focus',
							],
						],
					],

				],
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => ['forms_type' => 'classic-forms'],
				'values_source' => 'global',
				'options' => [

					blocksy_rand_md5() => [
						'type' => 'ct-divider',
						'attr' => [ 'data-type' => 'small' ],
					],

					blocksy_rand_md5() => [
						'type' => 'ct-labeled-group',
						'label' => __( 'Input Background Color', 'blocksy-companion' ),
						'responsive' => true,
						'choices' => [
							[
								'id' => 'sb_background',
								'label' => __('Default State', 'blocksy-companion'),
							],

							[
								'id' => 'transparent_sb_background',
								'label' => __('Transparent State', 'blocksy-companion'),
								'condition' => [
									'row' => '!offcanvas',
									'builderSettings/has_transparent_header' => 'yes',
								],
							],

							[
								'id' => 'sticky_sb_background',
								'label' => __('Sticky State', 'blocksy-companion'),
								'condition' => [
									'row' => '!offcanvas',
									'builderSettings/has_sticky_header' => 'yes',
								],
							],
						],
						'options' => [
							'sb_background' => [
								'label' => __( 'Input Background Color', 'blocksy-companion' ),
								'type'  => 'ct-color-picker',
								'design' => 'block:right',
								'responsive' => true,
								'setting' => [ 'transport' => 'postMessage' ],

								'value' => [
									'default' => [
										'color' => Blocksy_Css_Injector::get_skip_rule_keyword(),
									],

									'focus' => [
										'color' => Blocksy_Css_Injector::get_skip_rule_keyword(),
									],
								],

								'pickers' => [
									[
										'title' => __( 'Initial', 'blocksy-companion' ),
										'id' => 'default',
									],

									[
										'title' => __( 'Focus', 'blocksy-companion' ),
										'id' => 'focus',
									],
								],
							],

							'transparent_sb_background' => [
								'label' => __( 'Input Background Color', 'blocksy-companion' ),
								'type'  => 'ct-color-picker',
								'design' => 'block:right',
								'responsive' => true,
								'setting' => [ 'transport' => 'postMessage' ],

								'value' => [
									'default' => [
										'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
									],

									'focus' => [
										'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
									],
								],

								'pickers' => [
									[
										'title' => __( 'Initial', 'blocksy-companion' ),
										'id' => 'default',
									],

									[
										'title' => __( 'Focus', 'blocksy-companion' ),
										'id' => 'focus',
									],
								],
							],

							'sticky_sb_background' => [
								'label' => __( 'Input Background Color', 'blocksy-companion' ),
								'type'  => 'ct-color-picker',
								'design' => 'block:right',
								'responsive' => true,
								'setting' => [ 'transport' => 'postMessage' ],

								'value' => [
									'default' => [
										'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
									],

									'focus' => [
										'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
									],
								],

								'pickers' => [
									[
										'title' => __( 'Initial', 'blocksy-companion' ),
										'id' => 'default',
									],

									[
										'title' => __( 'Focus', 'blocksy-companion' ),
										'id' => 'focus',
									],
								],
							],

						],
					],
				],
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => ['forms_type' => 'classic-forms'],
				'values_source' => 'global',
				'options' => [

					blocksy_rand_md5() => [
						'type' => 'ct-divider',
					],

					'sb_radius' => [
						'label' => __( 'Input Border Radius', 'blocksy-companion' ),
						'type' => 'ct-spacing',
						'value' => blocksy_spacing_value(),
						'min' => 0,
						'responsive' => true
					],
				],
			],

			'sb_margin' => [
				'label' => __( 'Margin', 'blocksy-companion' ),
				'type' => 'ct-spacing',
				'divider' => 'top',
				'value' => blocksy_spacing_value(),
				'responsive' => true
			],


			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [ 'enable_live_results' => 'yes' ],
				'options' => [

					blocksy_rand_md5() => [
						'type' => 'ct-divider',
					],

					'sb_dropdown_font' => [
						'type' => 'ct-typography',
						'label' => __( 'Dropdown Font', 'blocksy-companion' ),
						'value' => blocksy_typography_default_values([
							'size' => '14px',
							'variation' => 'n5',
							'line-height' => '1.4',
						]),
						'setting' => [ 'transport' => 'postMessage' ],
					],

					'sb_dropdown_text' => [
						'label' => __( 'Dropdown Text Color', 'blocksy-companion' ),
						'type'  => 'ct-color-picker',
						'design' => 'block:right',
						'responsive' => true,
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
							],

							[
								'title' => __( 'Hover', 'blocksy-companion' ),
								'id' => 'hover',
								'inherit' => 'var(--theme-link-hover-color)'
							],
						],
					],

					'sb_dropdown_background' => [
						'label' => __( 'Dropdown Background', 'blocksy-companion' ),
						'type'  => 'ct-color-picker',
						'design' => 'block:right',
						'divider' => 'top',
						'responsive' => true,
						'setting' => [ 'transport' => 'postMessage' ],

						'value' => [
							'default' => [
								'color' => '#ffffff',
							],
						],

						'pickers' => [
							[
								'title' => __( 'Initial', 'blocksy-companion' ),
								'id' => 'default',
							],
						],
					],

					'sb_dropdown_shadow' => [
						'label' => __( 'Dropdown Shadow', 'blocksy-companion' ),
						'type' => 'ct-box-shadow',
						'divider' => 'top',
						'responsive' => true,
						'value' => blocksy_box_shadow_value([
							'enable' => true,
							'h_offset' => 0,
							'v_offset' => 50,
							'blur' => 70,
							'spread' => 0,
							'inset' => false,
							'color' => [
								'color' => 'rgba(210, 213, 218, 0.4)',
							],
						])
					],

					'sb_dropdown_divider' => [
						'label' => __( 'Items Divider', 'blocksy-companion' ),
						'type' => 'ct-border',
						'design' => 'inline',
						'divider' => 'top',
						'value' => [
							'width' => 1,
							'style' => 'dashed',
							'color' => [
								'color' => 'rgba(0, 0, 0, 0.05)',
							],
						]
					],

				],
			],

		],
	],
];

if ($panel_type === 'header') {
	$options[blocksy_rand_md5()] = [
		'type' => 'ct-condition',
		'condition' => [
			'wp_customizer_current_view' => 'tablet|mobile'
		],
		'options' => [
			blocksy_rand_md5() => [
				'type' => 'ct-divider',
			],

			'visibility' => [
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
	];
}
