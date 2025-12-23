<?php

$options = [
	blocksy_rand_md5() => [
		'type' => 'tab',
		'title' => __('General', 'blocksy-companion'),
		'options' => [

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [ 'menu_item_level' => '1' ],
				'options' => [

					'has_mega_menu' => [
						'type' => 'ct-switch',
						'label' => __('Mega Menu Settings', 'blocksy-companion'),
						'value' => 'no',
						'wrapperAttr' => ['data-label' => 'heading-label'],
					],

					blocksy_rand_md5() => [
						'type' => 'ct-condition',
						'condition' => ['has_mega_menu' => 'yes'],
						'options' => [


							'mega_menu_width' => [
								'label' => __( 'Dropdown Width', 'blocksy-companion' ),
								'type' => 'ct-select',
								'value' => 'content',
								'view' => 'text',
								'design' => 'inline',
								'divider' => 'top',
								'choices' => blocksy_ordered_keys(
									[
										'content' => __( 'Content Width', 'blocksy-companion' ),
										'full_width' => __( 'Full Width', 'blocksy-companion' ),
										'custom' => __( 'Custom Width', 'blocksy-companion' ),
									]
								),
							],

							blocksy_rand_md5() => [
								'type' => 'ct-condition',
								'condition' => [ 'mega_menu_width' => 'full_width' ],
								'options' => [

									'mega_menu_content_width' => [
										'label' => __( 'Content Width', 'blocksy-companion' ),
										'type' => 'ct-select',
										'value' => 'default',
										'view' => 'text',
										'design' => 'inline',
										'divider' => 'top',
										'choices' => blocksy_ordered_keys(
											[
												'default' => __( 'Default Width', 'blocksy-companion' ),
												'full_width' => __( 'Full Width', 'blocksy-companion' ),
											]
										),
									],

								],
							],

							blocksy_rand_md5() => [
								'type' => 'ct-condition',
								'condition' => [ 'mega_menu_width' => 'custom' ],
								'options' => [

									'mega_menu_custom_width' => [
										'label' => __( 'Dropdown Custom Width', 'blocksy-companion' ),
										'type' => 'ct-slider',
										'value' => '400px',
										'units' => blocksy_units_config([
											[ 'unit' => 'px', 'min' => 0, 'max' => 1500 ],
										]),
										'divider' => 'top',
										'design' => 'inline',
									],

								],
							],

							'mega_menu_columns' => [
								'label' => __( 'Columns', 'blocksy-companion' ),
								'type' => 'ct-radio',
								'value' => '4',
								'view' => 'text',
								'design' => 'inline',
								'allow_empty' => true,
								'divider' => 'top',
								'choices' => [
									'1' => 1,
									'2' => 2,
									'3' => 3,
									'4' => 4,
									'5' => 5,
									'6' => 6,
								],
							],

							// 2 columns
							blocksy_rand_md5() => [
								'type' => 'ct-condition',
								'condition' => [ 'mega_menu_columns' => '2' ],
								'options' => [

									'2_columns_layout' => [
										'label' => false,
										'type' => 'ct-image-picker',
										'attr' => ['data-ratio' => '2:1'],
										'value' => 'repeat(2, 1fr)',
										'divider' => 'top',
										'design' => 'block',
										'disableRevertButton' => true,
										'wrapperAttr' => [ 'className' => 'full-width' ],
										'setting' => [ 'transport' => 'postMessage' ],
										'choices' => [
											'repeat(2, 1fr)' => [
												'src' => blocksy_image_picker_file( '1-1' ),
											],

											'2fr 1fr' => [
												'src' => blocksy_image_picker_file( '2-1' ),
											],

											'1fr 2fr' => [
												'src' => blocksy_image_picker_file( '1-2' ),
											],

											'3fr 1fr' => [
												'src' => blocksy_image_picker_file( '3-1' ),
											],

											// '1fr 3fr' => [
											// 	'src' => blocksy_image_picker_file( '1-3' ),
											// ],
										],
									],

								],
							],

							// 3 columns
							blocksy_rand_md5() => [
								'type' => 'ct-condition',
								'condition' => [ 'mega_menu_columns' => '3' ],
								'options' => [

									'3_columns_layout' => [
										'label' => false,
										'type' => 'ct-image-picker',
										'attr' => ['data-ratio' => '2:1'],
										'value' => 'repeat(3, 1fr)',
										'divider' => 'top',
										'design' => 'block',
										'disableRevertButton' => true,
										'wrapperAttr' => [ 'className' => 'full-width' ],
										'setting' => ['transport' => 'postMessage'],
										'choices' => [
											'repeat(3, 1fr)' => [
												'src' => blocksy_image_picker_file( '1-1-1' ),
											],

											'1fr 2fr 1fr' => [
												'src' => blocksy_image_picker_file( '1-2-1' ),
											],

											'2fr 1fr 1fr' => [
												'src' => blocksy_image_picker_file( '2-1-1' ),
											],

											'1fr 1fr 2fr' => [
												'src' => blocksy_image_picker_file( '1-1-2' ),
											],
										],
									],

								],
							],

							// 4 columns
							blocksy_rand_md5() => [
								'type' => 'ct-condition',
								'condition' => [
									'any' => [
										'mega_menu_columns' => '4',
										'mega_menu_columns:truthy' => 'no'
									]
								],
								'options' => [

									'4_columns_layout' => [
										'label' => false,
										'type' => 'ct-image-picker',
										'attr' => ['data-ratio' => '2:1'],
										'value' => 'repeat(4, 1fr)',
										'divider' => 'top',
										'design' => 'block',
										'disableRevertButton' => true,
										'wrapperAttr' => [ 'className' => 'full-width' ],
										'setting' => [ 'transport' => 'postMessage' ],
										'choices' => [
											'repeat(4, 1fr)' => [
												'src'   => blocksy_image_picker_file( '1-1-1-1' ),
											],

											'1fr 2fr 2fr 1fr' => [
												'src'   => blocksy_image_picker_file( '1-2-2-1' ),
											],

											'2fr 1fr 1fr 1fr' => [
												'src'   => blocksy_image_picker_file( '2-1-1-1' ),
											],

											'1fr 1fr 1fr 2fr' => [
												'src'   => blocksy_image_picker_file( '1-1-1-2' ),
											],
										],
									],

								],
							],

							// 5 columns
							blocksy_rand_md5() => [
								'type' => 'ct-condition',
								'condition' => [ 'mega_menu_columns' => '5' ],
								'options' => [

									'5_columns_layout' => [
										'label' => false,
										'type' => 'ct-image-picker',
										'attr' => ['data-ratio' => '2:1'],
										'value' => 'repeat(5, 1fr)',
										'divider' => 'top',
										'design' => 'block',
										'disableRevertButton' => true,
										'wrapperAttr' => [ 'className' => 'full-width' ],
										'setting' => [ 'transport' => 'postMessage' ],
										'choices' => [
											'repeat(5, 1fr)' => [
												'src'   => blocksy_image_picker_file( '1-1-1-1-1' ),
											],

											'2fr 1fr 1fr 1fr 1fr' => [
												'src'   => blocksy_image_picker_file( '2-1-1-1-1' ),
											],

											'1fr 1fr 1fr 1fr 2fr' => [
												'src'   => blocksy_image_picker_file( '1-1-1-1-2' ),
											],

											'1fr 1fr 2fr 1fr 1fr' => [
												'src'   => blocksy_image_picker_file( '1-1-2-1-1' ),
											],
										],
									],

								],
							],

							// 6 columns
							blocksy_rand_md5() => [
								'type' => 'ct-condition',
								'condition' => [ 'mega_menu_columns' => '6' ],
								'options' => [

									'6_columns_layout' => [
										'label' => false,
										'type' => 'ct-image-picker',
										'attr' => ['data-ratio' => '2:1'],
										'value' => 'repeat(6, 1fr)',
										'divider' => 'top',
										'design' => 'block',
										'disableRevertButton' => true,
										'wrapperAttr' => [ 'className' => 'full-width' ],
										'setting' => [ 'transport' => 'postMessage' ],
										'choices' => [
											'repeat(6, 1fr)' => [
												'src'   => blocksy_image_picker_file( '1-1-1-1-1-1' ),
											],

											'2fr 1fr 1fr 1fr 1fr 1fr' => [
												'src'   => blocksy_image_picker_file( '2-1-1-1-1-1' ),
											],

											'1fr 1fr 1fr 1fr 1fr 2fr' => [
												'src'   => blocksy_image_picker_file( '1-1-1-1-1-2' ),
											],

											'1fr 1fr 2fr 2fr 1fr 1fr' => [
												'src'   => blocksy_image_picker_file( '1-1-2-2-1-1' ),
											],
										],
									],

								],
							],

							blocksy_rand_md5() => [
								'type' => 'ct-divider',
								'attr' => [ 'data-type' => 'full-modal' ],
							],

							'has_ajax_loading' => [
								'type' => 'ct-switch',
								'label' => __('AJAX Content Loading', 'blocksy-companion'),
								'value' => 'no',
								'wrapperAttr' => [ 'data-label' => 'heading-label' ],
								'desc' => __('If you have complex data inside your mega menu you can enable this option in order to load the dropdown content with AJAX and improve the website loading time.', 'blocksy-companion'),
							],
						],
					],

					blocksy_rand_md5() => [
						'type' => 'ct-divider',
						'attr' => [ 'data-type' => 'full-modal' ],
					],

				],
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [ 'menu_item_level' => '!1' ],
				'options' => [

					blocksy_rand_md5() => [
						'type' => 'ct-title',
						'variation' => 'no-border',
						'label' => __( 'Custom Content', 'blocksy-companion' ),
					],

					'mega_menu_content_type' => [
						'label' => __( 'Content Type', 'blocksy-companion' ),
						'type' => 'ct-select',
						'value' => 'default',
						'view' => 'text',
						'design' => 'inline',
						'choices' => blocksy_ordered_keys(
							[
								'default' => __( 'Default (Menu Item)', 'blocksy-companion' ),
								'text' => __( 'Custom Text', 'blocksy-companion' ),
								'hook' => __( 'Content Block', 'blocksy-companion' ),
							]
						),
					],

					blocksy_rand_md5() => [
						'type' => 'ct-condition',
						'condition' => [ 'mega_menu_content_type' => 'text' ],
						'options' => [
							'mega_menu_text' => [
								'label' => false,
								'type' => 'wp-editor',
								'value' => '',
								'disableRevertButton' => true,
								'wrapperAttr' => [ 'className' => 'full-width' ],
								'quicktags' => false,
								'mediaButtons' => false,
								'tinymce' => [
									'toolbar1' => 'bold,italic,link,alignleft,aligncenter,alignright,undo,redo',
								],
							],
						],
					],

					blocksy_rand_md5() => [
						'type' => 'ct-condition',
						'condition' => ['mega_menu_content_type' => 'hook'],
						'options' => empty(blc_get_content_blocks()) ? [

							blocksy_rand_md5() => [
								'type' => 'html',
								'label' => __('Select Content Block', 'blocksy-companion'),
								'value' => '',
								'design' => 'inline',
								'html' => '<a href="' . admin_url('/edit.php?post_type=ct_content_block') .'" target="_blank" class="button" style="width: 100%; text-align: center;">' . __('Create a new content Block/Hook', 'blocksy-companion') . '</a>',
							],

						] : [
							'mega_menu_hook' => [
								'label' => __('Select Content Block', 'blocksy-companion'),
								'type' => 'ct-select',
								'value' => '',
								'design' => 'inline',
								'search' => true,
								'defaultToFirstItem' => false,
								'placeholder' => __('None', 'blocksy-companion'),
								'choices' => blocksy_ordered_keys(blc_get_content_blocks()),
							],
						],
					],

					blocksy_rand_md5() => [
						'type' => 'ct-condition',
						'condition' => [ 'mega_menu_content_type' => 'text|hook' ],
						'options' => [

							'menu_custom_content_visibility' => [
								'label' => __( 'Content Visibility', 'blocksy-companion' ),
								'type' => 'ct-checkboxes',
								'design' => 'inline',
								'view' => 'text',
								'allow_empty' => true,
								'value' => [
									'desktop_visible' => true,
									'mobile_visible' => false,
								],
								'choices' => blocksy_ordered_keys([
									'desktop_visible' => __( 'Desktop', 'blocksy-companion' ),
									'mobile_visible' => __( 'Mobile', 'blocksy-companion' ),
								]),
							],

						],
					],

					blocksy_rand_md5() => [
						'type' => 'ct-divider',
						'attr' => [ 'data-type' => 'full-modal' ],
					],

				],
			],

			blocksy_rand_md5() => [
				'type' => 'ct-title',
				'variation' => 'no-border',
				'label' => __( 'Item Label Settings', 'blocksy-companion' ),
			],

			'mega_menu_label' => [
				'type' => 'ct-radio',
				'label' => __( 'Item Label', 'blocksy-companion' ),
				'value' => 'default',
				'view' => 'text',
				'design' => 'inline',
				'conditions' => [
					'heading' => [
						'menu_item_level' => '!1',
						'parentData/has_mega_menu' => 'yes'
					],
				],
				'choices' => [
					'default' => __('Enabled', 'blocksy-companion'),
					'disabled' => __('Disabled', 'blocksy-companion'),
					'heading' => __('Heading', 'blocksy-companion'),
				],
			],

			'has_menu_item_link' => [
				'type' => 'ct-switch',
				'label' => __('Label Link', 'blocksy-companion'),
				'value' => 'yes',
				'divider' => 'top',
			],

			'menu_item_icon' => [
				'type' => 'icon-picker',
				'label' => __('Icon', 'blocksy-companion'),
				'design' => 'inline',
				'divider' => 'top',
				'value' => [
					'icon' => ''
				]
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [
					'any' => [
						'menu_item_icon/icon:truthy' => 'yes',
						'menu_item_icon/url:truthy' => 'yes'
					]
				],
				'options' => [

					'menu_item_icon_size' => [
						'label' => __( 'Icon Size', 'blocksy-companion' ),
						'type' => 'ct-slider',
						'design' => 'inline',
						'divider' => 'top',
						'min' => 5,
						'max' => 50,
						'value' => 15,
					],

				],
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [
					'all' => [
						'mega_menu_label' => '!disabled',
						'any' => [
							'menu_item_icon/icon:truthy' => 'yes',
							'menu_item_icon/url:truthy' => 'yes'
						]
					]
				],
				'options' => [

					'menu_item_position' => [
						'type' => 'ct-radio',
						'label' => __( 'Icon Position', 'blocksy-companion' ),
						'value' => 'left',
						'view' => 'text',
						'design' => 'inline',
						'divider' => 'top',

						'choices' => [
							'left' => __( 'Left', 'blocksy-companion' ),
							'right' => __( 'Right', 'blocksy-companion' ),
						],
					],

				],
			],


			blocksy_rand_md5() => [
				'type' => 'ct-divider',
				'attr' => [ 'data-type' => 'full-modal' ],
			],

			'has_menu_badge' => [
				'type' => 'ct-switch',
				'label' => __('Badge Settings', 'blocksy-companion'),
				'value' => 'no',
				'wrapperAttr' => [ 'data-label' => 'heading-label' ],
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [ 'has_menu_badge' => 'yes' ],
				'options' => [

					'menu_badge_text' => [
						'type' => 'text',
						'label' => __('Text', 'blocksy-companion'),
						'design' => 'inline',
						'value' => '',
					],

					'menu_badge_vertical_alignment' => [
						'label' => __( 'Vertical Alignment', 'blocksy-companion' ),
						'type' => 'ct-slider',
						'design' => 'inline',
						'value' => 0,
						'min' => -20,
						'max' => 20,
						'steps' => 'half',
					],

				],
			],

		]
	],

	blocksy_rand_md5() => [
		'type' => 'tab',
		'title' => __('Design', 'blocksy-companion'),
		'options' => [

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [
					'menu_item_level' => '1',
					'has_mega_menu' => 'yes',
				],
				'options' => [

					blocksy_rand_md5() => [
						'type' => 'ct-title',
						'variation' => 'no-border',
						'label' => __( 'Mega Menu Settings', 'blocksy-companion' ),
					],

					'mega_menu_background' => [
						'label' => __( 'Background', 'blocksy-companion' ),
						'type' => 'ct-background',
						'design' => 'inline',
						'divider' => 'bottom',
						'value' => blocksy_background_default_value([
							'backgroundColor' => [
								'default' => [
									'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT')
								],
							],
						])
					],

					'menu_items_links' => [
						'label' => __( 'Link Color', 'blocksy-companion' ),
						'type'  => 'ct-color-picker',
						'design' => 'inline',
						'divider' => 'bottom',
						'value' => [
							'default' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],

							'hover' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],

							'bg_hover' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],
						],

						'pickers' => [
							[
								'title' => __( 'Link Initial', 'blocksy-companion' ),
								'id' => 'default',
							],

							[
								'title' => __( 'Link Hover/Active', 'blocksy-companion' ),
								'id' => 'hover',
							],

							[
								'title' => __( 'Background Hover', 'blocksy-companion' ),
								'id' => 'bg_hover',
							],
						],
					],

					'menu_items_heading_font' => [
						'type' => 'ct-typography',
						'label' => __( 'Heading Font', 'blocksy-companion' ),
						'design' => 'inline',
						'value' => blocksy_typography_default_values([
							'size' => '15px',
							'variation' => 'n7',
						]),
					],

					'menu_items_heading' => [
						'label' => __( 'Heading Color', 'blocksy-companion' ),
						'type'  => 'ct-color-picker',
						'design' => 'inline',
						'divider' => 'bottom',
						'value' => [
							'default' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],
						],

						'pickers' => [
							[
								'title' => __( 'Initial Color', 'blocksy-companion' ),
								'id' => 'default',
							],
						],
					],

					'menu_items_text' => [
						'label' => __( 'Text Color', 'blocksy-companion' ),
						'type'  => 'ct-color-picker',
						'design' => 'inline',
						'divider' => 'bottom',
						'value' => [
							'default' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],
						],

						'pickers' => [
							[
								'title' => __( 'Initial Color', 'blocksy-companion' ),
								'id' => 'default',
							],
						],
					],

					'mega_menu_items_divider' => [
						'label' => __( 'Items Divider', 'blocksy-companion' ),
						'type' => 'ct-border',
						'design' => 'inline',
						'divider' => 'bottom',
						'value' => [
							'inherit' => true,
							'width' => 1,
							'style' => 'dashed',
							'color' => [
								'color' => 'rgba(255, 255, 255, 0.1)',
							],
						]
					],

					'mega_menu_columns_divider' => [
						'label' => __( 'Columns Divider', 'blocksy-companion' ),
						'type' => 'ct-border',
						'design' => 'inline',
						'divider' => 'bottom',
						'value' => [
							// 'inherit' => true,
							'width' => 1,
							'style' => 'solid',
							'color' => [
								'color' => 'rgba(255, 255, 255, 0.1)',
							],
						]
					],

					'mega_menu_shadow' => [
						'label' => __( 'Dropdown Shadow', 'blocksy-companion' ),
						'type' => 'ct-box-shadow',
						'design' => 'inline',
						'value' => blocksy_box_shadow_value([
							'inherit' => true,
							'enable' => false,
							'h_offset' => 0,
							'v_offset' => 10,
							'blur' => 20,
							'spread' => 0,
							'inset' => false,
							'color' => [
								'color' => 'rgba(41, 51, 61, 0.1)',
							],
						])
					],

					blocksy_rand_md5() => [
						'type' => 'ct-divider',
						'attr' => [ 'data-type' => 'full-modal' ],
					],

				],
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [ 'menu_item_level' => '2' ],
				'options' => [
					blocksy_rand_md5() => [
						'type' => 'ct-title',
						'variation' => 'no-border',
						'label' => __( 'Column Settings', 'blocksy-companion' ),
					],

					'menu_column_padding' => [
						'label' => __( 'Column Spacing', 'blocksy-companion' ),
						'type' => 'ct-spacing',
						'design' => 'inline',
						'setting' => [ 'transport' => 'postMessage' ],
						'wrapperAttr' => ['class' => 'ct-control ct-option-spacing-wrapper'],
						'value' => blocksy_spacing_value()
					],

					blocksy_rand_md5() => [
						'type' => 'ct-condition',
						'condition' => [ 'parentData/mega_menu_width' => '!full_width' ],
						'options' => [

							'mega_menu_column_background' => [
								'label' => __( 'Column Background', 'blocksy-companion' ),
								'type' => 'ct-background',
								'design' => 'inline',
								'divider' => 'top',
								'value' => blocksy_background_default_value([
									'backgroundColor' => [
										'default' => [
											'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT')
										],
									],
								])
							],

						],
					],

					blocksy_rand_md5() => [
						'type' => 'ct-divider',
						'attr' => [ 'data-type' => 'full-modal' ],
					],
				],
			],


			blocksy_rand_md5() => [
				'type' => 'ct-title',
				'variation' => 'no-border',
				'label' => __( 'Item Label Settings', 'blocksy-companion' ),
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [
					'menu_item_level' => '!1',
					'mega_menu_label' => 'heading'
				],
				'options' => [

					'menu_item_heading' => [
						'label' => __( 'Heading Color', 'blocksy-companion' ),
						'type'  => 'ct-color-picker',
						'design' => 'inline',
						'divider' => 'bottom',
						'value' => [
							'default' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],
						],

						'pickers' => [
							[
								'title' => __( 'Initial Color', 'blocksy-companion' ),
								'id' => 'default',
							],
						],
					],

				],
			],

			'menu_item_icon_color' => [
				'label' => __( 'Icon Color', 'blocksy-companion' ),
				'type'  => 'ct-color-picker',
				'design' => 'inline',

				'value' => [
					'default' => [
						'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
					],

					'hover' => [
						'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
					],

					'active' => [
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

					[
						'title' => __( 'Active', 'blocksy-companion' ),
						'id' => 'active',
						'inherit' => 'self:hover'
					],
				],
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [ 'has_menu_badge' => 'yes' ],
				'options' => [

					blocksy_rand_md5() => [
						'type' => 'ct-divider',
						'attr' => [ 'data-type' => 'full-modal' ],
					],

					blocksy_rand_md5() => [
						'type' => 'ct-title',
						'variation' => 'no-border',
						'label' => __('Badge Settings', 'blocksy-companion'),
					],

					'menu_badge_font_color' => [
						'label' => __( 'Font Color', 'blocksy-companion' ),
						'type'  => 'ct-color-picker',
						'design' => 'inline',
						'divider' => 'bottom',

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

					'menu_badge_background' => [
						'label' => __( 'Background Color', 'blocksy-companion' ),
						'type'  => 'ct-color-picker',
						'design' => 'inline',

						'value' => [
							'default' => [
								'color' => 'var(--theme-palette-color-1)',
							],
						],

						'pickers' => [
							[
								'title' => __( 'Initial', 'blocksy-companion' ),
								'id' => 'default',
							],
						],
					],

				],
			],


		]
	]
];

