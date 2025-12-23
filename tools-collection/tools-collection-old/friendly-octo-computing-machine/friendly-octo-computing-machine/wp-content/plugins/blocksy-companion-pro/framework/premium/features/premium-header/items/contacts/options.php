<?php

$direction_option = [
	'contacts_items_direction' => [
		'type' => 'ct-radio',
		'label' => __( 'Items Direction', 'blocksy-companion' ),
		'view' => 'text',
		'design' => 'block',
		'divider' => 'top:full',
		'responsive' => true,
		'value' => 'column',
		'choices' => [
			'column' => __( 'Vertical', 'blocksy-companion' ),
			'row' => __( 'Horizontal', 'blocksy-companion' ),
		],
	],
];

$visibility_option = [
	'visibility' => [
		'label' => __( 'Item Visibility', 'blocksy-companion' ),
		'type' => 'ct-visibility',
		'design' => 'block',
		// 'allow_empty' => true,
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

		'sync' => [
			'shouldSkip' => 'skip'
		],
	],
];

$options = [
	blocksy_rand_md5() => [
		'title' => __( 'General', 'blocksy-companion' ),
		'type' => 'tab',
		'options' => [
			[
				'contact_items' => [
					'label' => false,
					'type' => 'ct-layers',
					'manageable' => true,
					'value' => [
						[
							'id' => 'address',
							'enabled' => true,
							'title' => __('Address:', 'blocksy-companion'),
							'content' => 'Street Name, NY 38954',
							'link' => '',
						],

						[
							'id' => 'phone',
							'enabled' => true,
							'title' => __('Phone:', 'blocksy-companion'),
							'content' => '578-393-4937',
							'link' => 'tel:578-393-4937',
						],

						[
							'id' => 'mobile',
							'enabled' => true,
							'title' => __('Mobile:', 'blocksy-companion'),
							'content' => '578-393-4937',
							'link' => 'tel:578-393-4937',
						],

					],

					'settings' => [
						'address' => [
							'label' => __( 'Address', 'blocksy-companion' ),
							'options' => [
								[
									'title' => [
										'type' => 'text',
										'label' => __('Title', 'blocksy-companion'),
										'value' => __('Address:', 'blocksy-companion'),
										'design' => 'block',
										'sync' => [
											'shouldSkip' => 'skip'
										],
									],

									'content' => [
										'type' => 'text',
										'label' => __('Content', 'blocksy-companion'),
										'value' => 'Street Name, NY 38954',
										'design' => 'block',
										'sync' => [
											'shouldSkip' => 'skip'
										],
									],

									'link' => [
										'type' => 'text',
										'label' => __('Link (optional)', 'blocksy-companion'),
										'value' => '',
										'design' => 'block',
									],

									'icon' => [
										'type' => 'icon-picker',
										'label' => __('Icon', 'blocksy-companion'),
										'design' => 'inline',
										'value' => [
											'icon' => 'blc blc-map-pin'
										]
									],
								],

								$visibility_option
							]
						],

						'phone' => [
							'label' => __( 'Phone', 'blocksy-companion' ),
							'options' => [
								[
									'title' => [
										'type' => 'text',
										'label' => __('Title', 'blocksy-companion'),
										'value' => __('Phone:', 'blocksy-companion'),
										'design' => 'block',
										'sync' => [
											'shouldSkip' => 'skip'
										],
									],

									'content' => [
										'type' => 'text',
										'label' => __('Content', 'blocksy-companion'),
										'value' => '578-393-4937',
										'design' => 'block',
										'sync' => [
											'shouldSkip' => 'skip'
										],
									],

									'link' => [
										'type' => 'text',
										'label' => __('Link (optional)', 'blocksy-companion'),
										'value' => 'tel:578-393-4937',
										'design' => 'block',
									],

									'icon' => [
										'type' => 'icon-picker',
										'label' => __('Icon', 'blocksy-companion'),
										'design' => 'inline',
										'value' => [
											'icon' => 'blc blc-phone'
										]
									],
								],

								$visibility_option
							]
						],

						'mobile' => [
							'label' => __( 'Mobile', 'blocksy-companion' ),
							'options' => [
								[
									'title' => [
										'type' => 'text',
										'label' => __('Title', 'blocksy-companion'),
										'value' => __('Mobile:', 'blocksy-companion'),
										'design' => 'block',
										'sync' => [
											'shouldSkip' => 'skip'
										],
									],

									'content' => [
										'type' => 'text',
										'label' => __('Content', 'blocksy-companion'),
										'value' => '578-393-4937',
										'design' => 'block',
										'sync' => [
											'shouldSkip' => 'skip'
										],
									],

									'link' => [
										'type' => 'text',
										'label' => __('Link (optional)', 'blocksy-companion'),
										'value' => 'tel:578-393-4937',
										'design' => 'block',
									],

									'icon' => [
										'type' => 'icon-picker',
										'label' => __('Icon', 'blocksy-companion'),
										'design' => 'inline',
										'value' => [
											'icon' => 'blc blc-mobile-phone'
										]
									],
								],

								$visibility_option
							]
						],

						'hours' => [
							'label' => __( 'Work Hours', 'blocksy-companion' ),
							'options' => [
								[
									'title' => [
										'type' => 'text',
										'label' => __('Title', 'blocksy-companion'),
										'value' => __('Opening hours:', 'blocksy-companion'),
										'design' => 'block',
										'sync' => [
											'shouldSkip' => 'skip'
										],
									],

									'content' => [
										'type' => 'text',
										'label' => __('Content', 'blocksy-companion'),
										'value' => '9AM - 5PM',
										'design' => 'block',
										'sync' => [
											'shouldSkip' => 'skip'
										],
									],

									'link' => [
										'type' => 'text',
										'label' => __('Link (optional)', 'blocksy-companion'),
										'value' => '',
										'design' => 'block',
									],

									'icon' => [
										'type' => 'icon-picker',
										'label' => __('Icon', 'blocksy-companion'),
										'design' => 'inline',
										'value' => [
											'icon' => 'blc blc-clock'
										]
									],
								],

								$visibility_option

							]
						],

						'fax' => [
							'label' => __( 'Fax', 'blocksy-companion' ),
							'options' => [
								[
									'title' => [
										'type' => 'text',
										'label' => __('Title', 'blocksy-companion'),
										'value' => __('Fax:', 'blocksy-companion'),
										'design' => 'block',
										'sync' => [
											'shouldSkip' => 'skip'
										],
									],

									'content' => [
										'type' => 'text',
										'label' => __('Content', 'blocksy-companion'),
										'value' => '578-393-4937',
										'design' => 'block',
										'sync' => [
											'shouldSkip' => 'skip'
										],
									],

									'link' => [
										'type' => 'text',
										'label' => __('Link (optional)', 'blocksy-companion'),
										'value' => 'tel:578-393-4937',
										'design' => 'block',
									],

									'icon' => [
										'type' => 'icon-picker',
										'label' => __('Icon', 'blocksy-companion'),
										'design' => 'inline',
										'value' => [
											'icon' => 'blc blc-fax'
										]
									],
								],

								$visibility_option
							]
						],

						'email' => [
							'label' => __( 'Email', 'blocksy-companion' ),
							'options' => [
								[
									'title' => [
										'type' => 'text',
										'label' => __('Title', 'blocksy-companion'),
										'value' => __('Email:', 'blocksy-companion'),
										'design' => 'block',
										'sync' => [
											'shouldSkip' => 'skip'
										],
									],

									'content' => [
										'type' => 'text',
										'label' => __('Content', 'blocksy-companion'),
										'value' => 'contact@yourwebsite.com',
										'design' => 'block',
										'sync' => [
											'shouldSkip' => 'skip'
										],
									],

									'link' => [
										'type' => 'text',
										'label' => __('Link (optional)', 'blocksy-companion'),
										'value' => 'mailto:contact@yourwebsite.com',
										'design' => 'block',
									],

									'icon' => [
										'type' => 'icon-picker',
										'label' => __('Icon', 'blocksy-companion'),
										'design' => 'inline',
										'value' => [
											'icon' => 'blc blc-email'
										]
									],
								],

								$visibility_option
							]
						],

						'website' => [
							'label' => __( 'Website', 'blocksy-companion' ),
							'options' => [
								[
									'title' => [
										'type' => 'text',
										'label' => __('Title', 'blocksy-companion'),
										'value' => __('Website:', 'blocksy-companion'),
										'design' => 'block',
										'sync' => [
											'shouldSkip' => 'skip'
										],
									],

									'content' => [
										'type' => 'text',
										'label' => __('Content', 'blocksy-companion'),
										'value' => 'creativethemes.com',
										'design' => 'block',
										'sync' => [
											'shouldSkip' => 'skip'
										],
									],

									'link' => [
										'type' => 'text',
										'label' => __('Link (optional)', 'blocksy-companion'),
										'value' => 'https://creativethemes.com',
										'design' => 'block',
									],

									'icon' => [
										'type' => 'icon-picker',
										'label' => __('Icon', 'blocksy-companion'),
										'design' => 'inline',
										'value' => [
											'icon' => 'blc blc-globe'
										]
									],
								],

								$visibility_option
							]
						],
					],
				],

				blocksy_rand_md5() => [
					'type' => 'ct-divider',
				],

				'link_target' => [
					'type'  => 'ct-switch',
					'label' => __( 'Open links in new tab', 'blocksy-companion' ),
					'value' => 'no',
				],

				'link_nofollow' => [
					'type'  => 'ct-switch',
					'label' => __( 'Set links to nofollow', 'blocksy-companion' ),
					'value' => 'no',
				],

				'link_icons' => [
					'type'  => 'ct-switch',
					'label' => __( 'Link Icons', 'blocksy-companion' ),
					'value' => 'no',
				],

				blocksy_rand_md5() => [
					'type' => 'ct-divider',
				],

				'contacts_icon_size' => [
					'label' => __( 'Icons Size', 'blocksy-companion' ),
					'type' => 'ct-slider',
					'min' => 5,
					'max' => 50,
					'value' => 15,
					'responsive' => true,
				],

				'contacts_spacing' => [
					'label' => __( 'Items Spacing', 'blocksy-companion' ),
					'type' => 'ct-slider',
					'min' => 0,
					'max' => 100,
					'value' => 15,
					'responsive' => true,
					'divider' => 'bottom',
				],

				'contacts_icon_shape' => [
					'label' => __('Icons Shape Type', 'blocksy-companion'),
					'type' => 'ct-radio',
					'value' => 'rounded',
					'view' => 'text',
					'design' => 'block',
					'choices' => [
						'simple' => __( 'None', 'blocksy-companion' ),
						'rounded' => __( 'Rounded', 'blocksy-companion' ),
						'square' => __( 'Square', 'blocksy-companion' ),
					],
					'sync' => 'live',
				],

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [ 'contacts_icon_shape' => '!simple' ],
					'options' => [

						'contacts_icon_fill_type' => [
							'label' => __('Shape Fill Type', 'blocksy-companion'),
							'type' => 'ct-radio',
							'value' => 'outline',
							'view' => 'text',
							'design' => 'block',
							'sync' => 'live',
							'choices' => [
								'outline' => __( 'Outline', 'blocksy-companion' ),
								'solid' => __( 'Solid', 'blocksy-companion' ),
							],
						],

					],
				],
			],

			$panel_type === 'header' ? [
				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => ['wp_customizer_current_view' => 'tablet|mobile'],
					'options' => [
						'visibility' => [
							'label' => __('Element Visibility', 'blocksy-companion'),
							'type' => 'ct-visibility',
							'design' => 'block',
							'divider' => 'top:full',
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
			] : [],

			$panel_type === 'footer' ? [
				$direction_option,

				'footer_contacts_horizontal_alignment' => [
					'type' => 'ct-radio',
					'label' => __( 'Horizontal Alignment', 'blocksy-companion' ),
					'view' => 'text',
					'design' => 'block',
					'divider' => 'top:full',
					'responsive' => true,
					'attr' => [ 'data-type' => 'alignment' ],
					'value' => 'CT_CSS_SKIP_RULE',
					'choices' => [
						'flex-start' => '',
						'center' => '',
						'flex-end' => '',
					],
				],

				'footer_contacts_vertical_alignment' => [
					'type' => 'ct-radio',
					'label' => __( 'Vertical Alignment', 'blocksy-companion' ),
					'view' => 'text',
					'design' => 'block',
					'divider' => 'top',
					'responsive' => true,
					'attr' => [ 'data-type' => 'vertical-alignment' ],
					'value' => 'CT_CSS_SKIP_RULE',
					'choices' => [
						'flex-start' => '',
						'center' => '',
						'flex-end' => '',
					],
				],

				'footer_contacts_visibility' => [
					'label' => __( 'Element Visibility', 'blocksy-companion' ),
					'type' => 'ct-visibility',
					'design' => 'block',
					'divider' => 'top:full',
					// 'allow_empty' => true,
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
			] : [
				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [
						'row' => 'offcanvas',
					],
					'options' => $direction_option
				]
			]
		]
	],

	blocksy_rand_md5() => [
		'title' => __( 'Design', 'blocksy-companion' ),
		'type' => 'tab',
		'options' => [

			'contacts_font' => [
				'type' => 'ct-typography',
				'label' => __( 'Font', 'blocksy-companion' ),
				'value' => blocksy_typography_default_values([
					'size' => '13px',
				])
			],

			blocksy_rand_md5() => [
				'type' => 'ct-labeled-group',
				'label' => __( 'Font Color', 'blocksy-companion' ),
				'responsive' => true,
				'choices' => [
					[
						'id' => 'contacts_font_color',
						'label' => __('Default State', 'blocksy-companion')
					],

					[
						'id' => 'transparent_contacts_font_color',
						'label' => __('Transparent State', 'blocksy-companion'),
						'condition' => [
							'row' => '!offcanvas',
							'builderSettings/has_transparent_header' => 'yes',
						],
					],

					[
						'id' => 'sticky_contacts_font_color',
						'label' => __('Sticky State', 'blocksy-companion'),
						'condition' => [
							'row' => '!offcanvas',
							'builderSettings/has_sticky_header' => 'yes',
						],
					],
				],
				'options' => [

					'contacts_font_color' => [
						'label' => __( 'Font Color', 'blocksy-companion' ),
						'type'  => 'ct-color-picker',
						'design' => 'block:right',
						'divider' => 'bottom',
						'responsive' => true,
						'value' => [
							'default' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],

							'link_initial' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
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
								'inherit' => 'self:default'
							],

							[
								'title' => __( 'Link Hover', 'blocksy-companion' ),
								'id' => 'link_hover',
								'inherit' => 'var(--theme-link-hover-color)'
							],
						],
					],

					'transparent_contacts_font_color' => [
						'label' => __( 'Font Color', 'blocksy-companion' ),
						'type'  => 'ct-color-picker',
						'design' => 'block:right',
						'divider' => 'bottom',
						'responsive' => true,
						'value' => [
							'default' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],

							'link_initial' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],

							'link_hover' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],
						],

						'pickers' => [
							[
								'title' => __( 'Text Initial', 'blocksy-companion' ),
								'id' => 'default',
							],

							[
								'title' => __( 'Link Initial', 'blocksy-companion' ),
								'id' => 'link_initial',
								'inherit' => 'self:default'
							],

							[
								'title' => __( 'Link Hover', 'blocksy-companion' ),
								'id' => 'link_hover',
							],
						],
					],

					'sticky_contacts_font_color' => [
						'label' => __( 'Font Color', 'blocksy-companion' ),
						'type'  => 'ct-color-picker',
						'design' => 'block:right',
						'divider' => 'bottom',
						'responsive' => true,
						'value' => [
							'default' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],

							'link_initial' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],

							'link_hover' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],
						],

						'pickers' => [
							[
								'title' => __( 'Text Initial', 'blocksy-companion' ),
								'id' => 'default',
							],

							[
								'title' => __( 'Link Initial', 'blocksy-companion' ),
								'id' => 'link_initial',
								'inherit' => 'self:default'
							],

							[
								'title' => __( 'Link Hover', 'blocksy-companion' ),
								'id' => 'link_hover',
							],
						],
					],

				],
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [
					'builderSettings/has_transparent_header' => 'yes',
					'builderSettings/has_sticky_header' => 'yes',
					'row' => '!offcanvas',
				],
				'options' => [
					blocksy_rand_md5() => [
						'type' => 'ct-divider',
					],
				],
			],

			blocksy_rand_md5() => [
				'type' => 'ct-labeled-group',
				'label' => __( 'Icons Color', 'blocksy-companion' ),
				'responsive' => true,
				'choices' => [
					[
						'id' => 'contacts_icon_color',
						'label' => __('Default State', 'blocksy-companion')
					],

					[
						'id' => 'transparent_contacts_icon_color',
						'label' => __('Transparent State', 'blocksy-companion'),
						'condition' => [
							'row' => '!offcanvas',
							'builderSettings/has_transparent_header' => 'yes',
						],
					],

					[
						'id' => 'sticky_contacts_icon_color',
						'label' => __('Sticky State', 'blocksy-companion'),
						'condition' => [
							'row' => '!offcanvas',
							'builderSettings/has_sticky_header' => 'yes',
						],
					],
				],
				'options' => [

					'contacts_icon_color' => [
						'label' => __( 'Icons Color', 'blocksy-companion' ),
						'type'  => 'ct-color-picker',
						'design' => 'block:right',
						'responsive' => true,

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

					'transparent_contacts_icon_color' => [
						'label' => __( 'Icons Color', 'blocksy-companion' ),
						'type'  => 'ct-color-picker',
						'design' => 'block:right',
						'responsive' => true,

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

					'sticky_contacts_icon_color' => [
						'label' => __( 'Icons Color', 'blocksy-companion' ),
						'type'  => 'ct-color-picker',
						'design' => 'block:right',
						'responsive' => true,

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
				'type' => 'ct-condition',
				'condition' => [
					'builderSettings/has_transparent_header' => 'yes',
					'builderSettings/has_sticky_header' => 'yes',
					'row' => '!offcanvas',
				],
				'options' => [
					blocksy_rand_md5() => [
						'type' => 'ct-divider',
					],
				],
			],


			blocksy_rand_md5() => [
				'type' => 'ct-labeled-group',
				'label' => [
					__('Icons Background Color', 'blocksy-companion') => [
						'contacts_icon_fill_type' => 'solid'
					],

					__('Icons Border Color', 'blocksy-companion') => [
						'contacts_icon_fill_type' => 'outline'
					]
				],
				'responsive' => true,
				'choices' => [
					[
						'id' => 'contacts_icon_background',
						'label' => __('Default State', 'blocksy-companion'),
						'condition' => [
							'contacts_icon_shape' => '!simple'
						],
					],

					[
						'id' => 'transparent_contacts_icon_background',
						'label' => __('Transparent State', 'blocksy-companion'),
						'condition' => [
							'row' => '!offcanvas',
							'contacts_icon_shape' => '!simple',
							'builderSettings/has_transparent_header' => 'yes',
						],
					],

					[
						'id' => 'sticky_contacts_icon_background',
						'label' => __('Sticky State', 'blocksy-companion'),
						'condition' => [
							'row' => '!offcanvas',
							'contacts_icon_shape' => '!simple',
							'builderSettings/has_sticky_header' => 'yes',
						],
					],
				],
				'options' => [

					'contacts_icon_background' => [
						'label' => [
							__('Icons Background Color', 'blocksy-companion') => [
								'contacts_icon_fill_type' => 'solid'
							],

							__('Icons Border Color', 'blocksy-companion') => [
								'contacts_icon_fill_type' => 'outline'
							]
						],
						'type'  => 'ct-color-picker',
						'design' => 'block:right',
						'responsive' => true,
						'divider' => 'top',

						'value' => [
							'default' => [
								'color' => 'rgba(218, 222, 228, 0.5)',
							],

							'hover' => [
								'color' => 'rgba(218, 222, 228, 0.7)',
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

					'transparent_contacts_icon_background' => [
						'label' => [
							__('Icons Background Color', 'blocksy-companion') => [
								'contacts_icon_fill_type' => 'solid'
							],

							__('Icons Border Color', 'blocksy-companion') => [
								'contacts_icon_fill_type' => 'outline'
							]
						],
						'type'  => 'ct-color-picker',
						'design' => 'block:right',
						'responsive' => true,
						'divider' => 'top',

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

					'sticky_contacts_icon_background' => [
						'label' => [
							__('Icons Background Color', 'blocksy-companion') => [
								'contacts_icon_fill_type' => 'solid'
							],

							__('Icons Border Color', 'blocksy-companion') => [
								'contacts_icon_fill_type' => 'outline'
							]
						],
						'type'  => 'ct-color-picker',
						'design' => 'block:right',
						'responsive' => true,
						'divider' => 'top',

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

			'contacts_margin' => [
				'label' => __( 'Margin', 'blocksy-companion' ),
				'type' => 'ct-spacing',
				'value' => blocksy_spacing_value(),
				'responsive' => true
			],

		],
	],
];

