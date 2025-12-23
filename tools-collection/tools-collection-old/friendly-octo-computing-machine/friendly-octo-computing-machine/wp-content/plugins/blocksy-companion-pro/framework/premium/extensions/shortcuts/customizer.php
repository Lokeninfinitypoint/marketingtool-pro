<?php

$initial_conditions = [
	[
		'type' => 'include',
		'rule' => 'everywhere'
	]
];

$layer_settings = [
	'home' => [
		'label' => __('Home', 'blocksy-companion'),
		'options' => [
			'icon' => [
				'type' => 'icon-picker',
				'design' => 'inline',
				'value' => [
					'icon' => 'blc blc-home'
				]
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [
					'any' => [
						'shortcuts_label_visibility/desktop' => true,
						'shortcuts_label_visibility/tablet' => true,
						'shortcuts_label_visibility/mobile' => true,
						'shortcuts_tooltip_visibility/desktop' => true,
						'shortcuts_tooltip_visibility/tablet' => true,
						'shortcuts_tooltip_visibility/mobile' => true,
					]
				],
				'values_source' => 'parent',
				'options' => [
					'label' => [
						'type' => 'text',
						'value' => __('Home', 'blocksy-companion'),
						'design' => 'inline',
						'sync' => [
							'id' => 'shortcuts_container'
						],
					],
				],
			],

			'item_visibility' => [
				'label' => __( 'Visibility', 'blocksy-companion' ),
				'type' => 'ct-visibility',
				'design' => 'block',
				'sync' => [
					'id' => 'shortcuts_container'
				],

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
		]
	],

	'phone' => [
		'label' => __('Phone', 'blocksy-companion'),
		'options' => [
			'icon' => [
				'type' => 'icon-picker',
				'design' => 'inline',
				'value' => [
					'icon' => 'blc blc-phone'
				]
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [
					'any' => [
						'shortcuts_label_visibility/desktop' => true,
						'shortcuts_label_visibility/tablet' => true,
						'shortcuts_label_visibility/mobile' => true,
						'shortcuts_tooltip_visibility/desktop' => true,
						'shortcuts_tooltip_visibility/tablet' => true,
						'shortcuts_tooltip_visibility/mobile' => true,
					]
				],
				'values_source' => 'parent',
				'options' => [
					'label' => [
						'type' => 'text',
						'value' => __('Phone', 'blocksy-companion'),
						'design' => 'inline',
						'sync' => [
							'id' => 'shortcuts_container'
						],
					],
				],
			],

			'phone_number' => [
				'type' => 'text',
				'value' => '#',
				'design' => 'inline',
			],

			'item_visibility' => [
				'label' => __( 'Visibility', 'blocksy-companion' ),
				'type' => 'ct-visibility',
				'design' => 'block',
				'sync' => [
					'id' => 'shortcuts_container'
				],

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
		]
	],

	'email' => [
		'label' => __('Email', 'blocksy-companion'),
		'options' => [
			'icon' => [
				'type' => 'icon-picker',
				'design' => 'inline',
				'value' => [
					'icon' => 'blc blc-email'
				]
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [
					'any' => [
						'shortcuts_label_visibility/desktop' => true,
						'shortcuts_label_visibility/tablet' => true,
						'shortcuts_label_visibility/mobile' => true,
						'shortcuts_tooltip_visibility/desktop' => true,
						'shortcuts_tooltip_visibility/tablet' => true,
						'shortcuts_tooltip_visibility/mobile' => true,
					]
				],
				'values_source' => 'parent',
				'options' => [
					'label' => [
						'type' => 'text',
						'value' => __('Email', 'blocksy-companion'),
						'design' => 'inline',
						'sync' => [
							'id' => 'shortcuts_container'
						],
					],
				],
			],

			'email' => [
				'type' => 'text',
				'value' => '#',
				'design' => 'inline',
			],

			'item_visibility' => [
				'label' => __( 'Visibility', 'blocksy-companion' ),
				'type' => 'ct-visibility',
				'design' => 'block',
				'sync' => [
					'id' => 'shortcuts_container'
				],

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
		]
	],

	'scroll_top' => [
		'label' => __('Scroll Top', 'blocksy-companion'),
		'options' => [
			'icon' => [
				'type' => 'icon-picker',
				'design' => 'inline',
				'value' => [
					'icon' => 'blc blc-arrow-up-circle'
				]
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [
					'any' => [
						'shortcuts_label_visibility/desktop' => true,
						'shortcuts_label_visibility/tablet' => true,
						'shortcuts_label_visibility/mobile' => true,
						'shortcuts_tooltip_visibility/desktop' => true,
						'shortcuts_tooltip_visibility/tablet' => true,
						'shortcuts_tooltip_visibility/mobile' => true,
					]
				],
				'values_source' => 'parent',
				'options' => [
					'label' => [
						'type' => 'text',
						'value' => __('Scroll Top', 'blocksy-companion'),
						'design' => 'inline',
						'sync' => [
							'id' => 'shortcuts_container'
						],
					],
				],
			],

			'item_visibility' => [
				'label' => __( 'Visibility', 'blocksy-companion' ),
				'type' => 'ct-visibility',
				'design' => 'block',
				'sync' => [
					'id' => 'shortcuts_container'
				],

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
		]
	],

	'custom_link' => [
		'label' => blc_safe_sprintf('<%%= label || "%s" %%>', __('Custom', 'blocksy-companion')),
		'clone' => 4,
		'options' => [
			'icon' => [
				'type' => 'icon-picker',
				'design' => 'inline',
				'value' => [
					'icon' => 'far fa-smile'
				]
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [
					'any' => [
						'shortcuts_label_visibility/desktop' => true,
						'shortcuts_label_visibility/tablet' => true,
						'shortcuts_label_visibility/mobile' => true,
						'shortcuts_tooltip_visibility/desktop' => true,
						'shortcuts_tooltip_visibility/tablet' => true,
						'shortcuts_tooltip_visibility/mobile' => true,
					]
				],
				'values_source' => 'parent',
				'options' => [
					'label' => [
						'type' => 'text',
						'value' => __('Custom', 'blocksy-companion'),
						'design' => 'inline',
						'sync' => [
							'id' => 'shortcuts_container'
						],
					],
				],
			],

			'link' => [
				'type' => 'text',
				'value' => '#',
				'design' => 'inline',
				'sync' => [
					'id' => 'shortcuts_container'
				],
			],

			'link_target' => [
				'type'  => 'ct-switch',
				'label' => __( 'Open link in new tab', 'blocksy-companion' ),
				'value' => 'no',
				'disableRevertButton' => true,
			],

			'link_nofollow' => [
				'type'  => 'ct-switch',
				'label' => __( 'Set link to nofollow', 'blocksy-companion' ),
				'value' => 'no',
			],

			'class' => [
				'type' => 'text',
				'label' => __( 'Custom class', 'blocksy-companion' ),
				'value' => '',
				'design' => 'inline',
			],

			'item_visibility' => [
				'label' => __( 'Visibility', 'blocksy-companion' ),
				'type' => 'ct-visibility',
				'design' => 'block',

				'value' => blocksy_default_responsive_value([
					'desktop' => true,
					'tablet' => true,
					'mobile' => true,
				]),

				'sync' => [
					'id' => 'shortcuts_container'
				],

				'choices' => blocksy_ordered_keys([
					'desktop' => __( 'Desktop', 'blocksy-companion' ),
					'tablet' => __( 'Tablet', 'blocksy-companion' ),
					'mobile' => __( 'Mobile', 'blocksy-companion' ),
				]),
			],
		]
	]
];

if (class_exists('WooCommerce')) {
	$layer_settings['cart'] = [
		'label' => __('Cart', 'blocksy-companion'),
		'options' => [
			'icon' => [
				'type' => 'icon-picker',
				'design' => 'inline',
				'value' => [
					'icon' => 'blc blc-cart'
				]
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [
					'any' => [
						'shortcuts_label_visibility/desktop' => true,
						'shortcuts_label_visibility/tablet' => true,
						'shortcuts_label_visibility/mobile' => true,
						'shortcuts_tooltip_visibility/desktop' => true,
						'shortcuts_tooltip_visibility/tablet' => true,
						'shortcuts_tooltip_visibility/mobile' => true,
					]
				],
				'values_source' => 'parent',
				'options' => [
					'label' => [
						'type' => 'text',
						'value' => __('Cart', 'blocksy-companion'),
						'design' => 'inline',
						'sync' => [
							'id' => 'shortcuts_container'
						],
					],
				],
			],

			'item_visibility' => [
				'label' => __( 'Visibility', 'blocksy-companion' ),
				'type' => 'ct-visibility',
				'design' => 'block',
				'sync' => [
					'id' => 'shortcuts_container'
				],

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
		]
	];

	$layer_settings['shop'] = [
		'label' => __('Shop', 'blocksy-companion'),
		'options' => [
			'icon' => [
				'type' => 'icon-picker',
				'design' => 'inline',
				'value' => [
					'icon' => 'blc blc-shop'
				]
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [
					'any' => [
						'shortcuts_label_visibility/desktop' => true,
						'shortcuts_label_visibility/tablet' => true,
						'shortcuts_label_visibility/mobile' => true,
						'shortcuts_tooltip_visibility/desktop' => true,
						'shortcuts_tooltip_visibility/tablet' => true,
						'shortcuts_tooltip_visibility/mobile' => true,
					]
				],
				'values_source' => 'parent',
				'options' => [
					'label' => [
						'type' => 'text',
						'value' => __('Shop', 'blocksy-companion'),
						'design' => 'inline',
						'sync' => [
							'id' => 'shortcuts_container'
						],
					],
				],
			],

			'item_visibility' => [
				'label' => __( 'Visibility', 'blocksy-companion' ),
				'type' => 'ct-visibility',
				'design' => 'block',
				'sync' => [
					'id' => 'shortcuts_container'
				],

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
		]
	];

	$storage = new \Blocksy\Extensions\WoocommerceExtra\Storage();
	$settings = $storage->get_settings();

	if (
		isset($settings['features']['filters']) &&
		$settings['features']['filters'] &&
		\Blocksy\Plugin::instance()->extensions->get('woocommerce-extra')
	) {
		$layer_settings['filters_canvas'] = [
			'label' => __('Filters Canvas', 'blocksy-companion'),
			'condition' => [
				'has_woo_offcanvas_filter' => 'yes'
			],
			'values_source' => 'global',
			'options' => [
				'icon' => [
					'type' => 'icon-picker',
					'design' => 'inline',
					'value' => [
						'icon' => 'blc blc-filter'
					]
				],

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [
						'any' => [
							'shortcuts_label_visibility/desktop' => true,
							'shortcuts_label_visibility/tablet' => true,
							'shortcuts_label_visibility/mobile' => true,
							'shortcuts_tooltip_visibility/desktop' => true,
							'shortcuts_tooltip_visibility/tablet' => true,
							'shortcuts_tooltip_visibility/mobile' => true,
						]
					],
					'values_source' => 'parent',
					'options' => [
						'label' => [
							'type' => 'text',
							'value' => __('Filter', 'blocksy-companion'),
							'design' => 'inline',
							'sync' => [
								'id' => 'shortcuts_container'
							],
						],
					],
				],

				'item_visibility' => [
					'label' => __( 'Visibility', 'blocksy-companion' ),
					'type' => 'ct-visibility',
					'design' => 'block',
					'sync' => [
						'id' => 'shortcuts_container'
					],

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
			]
		];
	}

	if (
		isset($settings['features']['wishlist']) &&
		$settings['features']['wishlist'] &&
		\Blocksy\Plugin::instance()->extensions->get('woocommerce-extra')
	) {
		$layer_settings['wishlist'] = [
			'label' => __('Wishlist', 'blocksy-companion'),
			'options' => [
				'icon' => [
					'type' => 'icon-picker',
					'design' => 'inline',
					'value' => [
						'icon' => 'blc blc-heart'
					]
				],

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [
						'any' => [
							'shortcuts_label_visibility/desktop' => true,
							'shortcuts_label_visibility/tablet' => true,
							'shortcuts_label_visibility/mobile' => true,
							'shortcuts_tooltip_visibility/desktop' => true,
							'shortcuts_tooltip_visibility/tablet' => true,
							'shortcuts_tooltip_visibility/mobile' => true,
						]
					],
					'values_source' => 'parent',
					'options' => [
						'label' => [
							'type' => 'text',
							'value' => __('Wishlist', 'blocksy-companion'),
							'design' => 'inline',
							'sync' => [
								'id' => 'shortcuts_container'
							],
						],
					],
				],

				'item_visibility' => [
					'label' => __( 'Visibility', 'blocksy-companion' ),
					'type' => 'ct-visibility',
					'design' => 'block',
					'sync' => [
						'id' => 'shortcuts_container'
					],

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
			]
		];
	}

	if (
		isset($settings['features']['product-waitlist']) &&
		$settings['features']['product-waitlist'] &&
		\Blocksy\Plugin::instance()->extensions->get('woocommerce-extra')
	) {
		$layer_settings['waitlist'] = [
			'label' => __('Waitlist', 'blocksy-companion'),
			'options' => [
				'icon' => [
					'type' => 'icon-picker',
					'design' => 'inline',
					'value' => [
						'icon' => 'blc blc-file'
					]
				],

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [
						'any' => [
							'shortcuts_label_visibility/desktop' => true,
							'shortcuts_label_visibility/tablet' => true,
							'shortcuts_label_visibility/mobile' => true,
							'shortcuts_tooltip_visibility/desktop' => true,
							'shortcuts_tooltip_visibility/tablet' => true,
							'shortcuts_tooltip_visibility/mobile' => true,
						]
					],
					'values_source' => 'parent',
					'options' => [
						'label' => [
							'type' => 'text',
							'value' => __('Waitlist', 'blocksy-companion'),
							'design' => 'inline',
							'sync' => [
								'id' => 'shortcuts_container'
							],
						],
					],
				],

				'item_visibility' => [
					'label' => __( 'Visibility', 'blocksy-companion' ),
					'type' => 'ct-visibility',
					'design' => 'block',
					'sync' => [
						'id' => 'shortcuts_container'
					],

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
			]
		];
	}

	if (
		isset($settings['features']['compareview']) &&
		$settings['features']['compareview'] &&
		\Blocksy\Plugin::instance()->extensions->get('woocommerce-extra')
	) {
		$layer_settings['compare'] = [
			'label' => __('Compare', 'blocksy-companion'),
			'options' => [
				'icon' => [
					'type' => 'icon-picker',
					'design' => 'inline',
					'value' => [
						'icon' => 'blc blc-compare'
					]
				],

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [
						'any' => [
							'shortcuts_label_visibility/desktop' => true,
							'shortcuts_label_visibility/tablet' => true,
							'shortcuts_label_visibility/mobile' => true,
							'shortcuts_tooltip_visibility/desktop' => true,
							'shortcuts_tooltip_visibility/tablet' => true,
							'shortcuts_tooltip_visibility/mobile' => true,
						]
					],
					'values_source' => 'parent',
					'options' => [
						'label' => [
							'type' => 'text',
							'value' => __('Compare', 'blocksy-companion'),
							'design' => 'inline',
							'sync' => [
								'id' => 'shortcuts_container'
							],
						],
					],
				],

				'item_visibility' => [
					'label' => __( 'Visibility', 'blocksy-companion' ),
					'type' => 'ct-visibility',
					'design' => 'block',
					'sync' => [
						'id' => 'shortcuts_container'
					],

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
			]
		];
	}

	$initial_conditions[] = [
			'type' => 'exclude',
			'rule' => 'page_ids',
			'payload' => [
				'post_id' => intval(get_option('woocommerce_cart_page_id'))
			]
	];

	$initial_conditions[] = [
		'type' => 'exclude',
		'rule' => 'page_ids',
		'payload' => [
			'post_id' => intval(get_option('woocommerce_checkout_page_id'))
		]
	];
}

$options = [
	//  translators: This is a brand name. Preferably to not be translated
	'title' => _x('Shortcuts Bar', 'Extension Brand Name', 'blocksy-companion'),
	'container' => [ 'priority' => 8 ],
	'options' => [

		'shortcuts_section_options' => [
			'type' => 'ct-options',
			'setting' => [ 'transport' => 'postMessage' ],
			'inner-options' => [

				blocksy_rand_md5() => [
					'title' => __( 'General', 'blocksy-companion' ),
					'type' => 'tab',
					'options' => [
						'shortcuts_bar_type' => [
							'label' => __('Type', 'blocksy-companion'),
							'type' => 'ct-image-picker',
							'value' => 'type-1',
							'design' => 'block',
							'sync' => 'live',
							'choices' => [
								'type-1' => [
									'src' => blocksy_image_picker_url('shortcuts-type-1.svg'),
									'title' => __('Type 1', 'blocksy-companion'),
								],

								'type-2' => [
									'src' => blocksy_image_picker_url('shortcuts-type-2.svg'),
									'title' => __('Type 2', 'blocksy-companion'),
								],
							],
						],

						'shortcuts_bar_items' => [
							'label' => __('Shortcuts', 'blocksy-companion' ),
							'type' => 'ct-layers',
							'value' => [
								[
									'id' => 'home',
									'enabled' => true,
									'label' => __('Home', 'blocksy-companion'),
									'icon' => [
										'icon' => 'blc blc-home'
									]
								],

								[
									'id' => 'phone',
									'enabled' => true,
									'label' => __('Phone', 'blocksy-companion'),
									'icon' => [
										'icon' => 'blc blc-phone'
									]
								]
							],

							'manageable' => true,
							'sync' => [
								[
									'id' => 'shortcuts_container_layers',
									'selector' => '.ct-shortcuts-bar',
									'render' => function () {
										blocksy_render_view_e(
											dirname(__FILE__) . '/views/bar.php',
											[]
										);
									}
								],

								[
									'id' => 'shortcuts_container',
									'selector' => '.ct-shortcuts-bar',
									'loader_selector' => 'skip',
									'render' => function () {
										blocksy_render_view_e(
											dirname(__FILE__) . '/views/bar.php',
											[]
										);
									}
								],
							],
							'settings' => $layer_settings
						],

						blocksy_rand_md5() => [
							'type' => 'ct-divider',
						],

						'shortcuts_label_visibility' => [
							'label' => __( 'Label Visibility', 'blocksy-companion' ),
							'type' => 'ct-visibility',
							'design' => 'block',
							'allow_empty' => true,
							'setting' => [ 'transport' => 'postMessage' ],
							'value' => blocksy_default_responsive_value([
								'desktop' => false,
								'tablet' => false,
								'mobile' => false,
							]),

							'sync' => [
								'selector' => '.ct-shortcuts-bar',
								'render' => function () {
									blocksy_render_view_e(
										dirname(__FILE__) . '/views/bar.php',
										[]
									);
								}
							],

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
									'shortcuts_label_visibility/desktop' => true,
									'shortcuts_label_visibility/tablet' => true,
									'shortcuts_label_visibility/mobile' => true,
								]
							],
							'options' => [
								'shortcuts_label_position' => [
									'type' => 'ct-radio',
									'label' => __( 'Label Position', 'blocksy-companion' ),
									'value' => 'bottom',
									'view' => 'text',
									'divider' => 'top',
									'design' => 'block',
									'sync' => 'live',
									'choices' => [
										'left' => __( 'Left', 'blocksy-companion' ),
										'right' => __( 'Right', 'blocksy-companion' ),
										'bottom' => __( 'Bottom', 'blocksy-companion' ),
									],
								],
							],
						],

						'shortcuts_tooltip_visibility' => [
							'label' => __( 'Tooltip Visibility', 'blocksy-companion' ),
							'type' => 'ct-visibility',
							'design' => 'block',
							'allow_empty' => true,
							'divider' => 'top:full',
							'setting' => [ 'transport' => 'postMessage' ],
							'value' => blocksy_default_responsive_value([
								'desktop' => false,
								'tablet' => false,
								'mobile' => false,
							]),

							'sync' => [
								'selector' => '.ct-shortcuts-bar',
								'render' => function () {
									blocksy_render_view_e(
										dirname(__FILE__) . '/views/bar.php',
										[]
									);
								}
							],

							'choices' => blocksy_ordered_keys([
								'desktop' => __( 'Desktop', 'blocksy-companion' ),
								'tablet' => __( 'Tablet', 'blocksy-companion' ),
								'mobile' => __( 'Mobile', 'blocksy-companion' ),
							]),
						],

						'shortcuts_icon_size' => [
							'label' => __( 'Icon Size', 'blocksy-companion' ),
							'type' => 'ct-slider',
							'min' => 5,
							'max' => 50,
							'value' => 15,
							'responsive' => true,
							'divider' => 'top:full',
							'setting' => [ 'transport' => 'postMessage' ],
						],

						'shortcuts_container_height' => [
							'label' => __( 'Container Height', 'blocksy-companion' ),
							'type' => 'ct-slider',
							'min' => 30,
							'max' => 150,
							'value' => 70,
							'divider' => 'top',
							'responsive' => true,
							'setting' => [ 'transport' => 'postMessage' ],
						],

						blocksy_rand_md5() => [
							'type' => 'ct-condition',
							'condition' => [ 'shortcuts_bar_type' => 'type-2' ],
							'options' => [
								'shortcuts_container_width' => [
									'label' => __( 'Container Max Width', 'blocksy-companion' ),
									'type' => 'ct-slider',
									'value' => '100%',
									'divider' => 'top',
									'responsive' => true,
									'units' => blocksy_units_config([
										[ 'unit' => '%', 'min' => 0, 'max' => 100 ],
										[ 'unit' => 'px', 'min' => 0, 'max' => 1500 ],
										[ 'unit' => 'pt', 'min' => 0, 'max' => 1500 ],
										[ 'unit' => 'em', 'min' => 0, 'max' => 200 ],
										[ 'unit' => 'rem', 'min' => 0, 'max' => 200 ],
										[ 'unit' => 'vw', 'min' => 0, 'max' => 100 ],
										[ 'unit' => 'vh', 'min' => 0, 'max' => 100 ],
									]),
									'setting' => [ 'transport' => 'postMessage' ],
								],
							],
						],

						blocksy_rand_md5() => [
							'type' => 'ct-divider',
						],

						'shortcuts_interaction' => [
							'label' => __('Scroll Interaction', 'blocksy-companion'),
							'type' => 'ct-radio',
							'value' => 'none',
							'view' => 'text',
							'choices' => [
								'none' => __('None', 'blocksy-companion'),
								'scroll' => __('Hide', 'blocksy-companion'),
							],

							'sync' => [
								'selector' => '.ct-shortcuts-bar',
								'render' => function () {
									blocksy_render_view_e(
										dirname(__FILE__) . '/views/bar.php',
										[]
									);
								}
							],
						],

						'shortcuts_bar_visibility' => [
							'label' => __( 'Visibility', 'blocksy-companion' ),
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

						'shortcuts_bar_conditions' => [
							'label' => __('Display Conditions', 'blocksy-companion'),
							'type' => 'blocksy-display-condition',
							'divider' => 'top',
							'value' => $initial_conditions,
							'display' => 'modal',

							'modalTitle' => __('Shortcuts Bar Display Conditions', 'blocksy-companion'),
							'modalDescription' => __('Add one or more conditions to display the shortcuts bar.', 'blocksy-companion'),
							'design' => 'block',
							'sync' => 'live'
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
									'shortcuts_label_visibility/desktop' => true,
									'shortcuts_label_visibility/tablet' => true,
									'shortcuts_label_visibility/mobile' => true,
								]
							],
							'options' => [
								'shortcuts_font' => [
									'type' => 'ct-typography',
									'label' => __( 'Font', 'blocksy-companion' ),
									'value' => blocksy_typography_default_values([
										'size' => '12px',
										'variation' => 'n5',
										'text-transform' => 'uppercase',
									]),
									'setting' => [ 'transport' => 'postMessage' ],
								],

								'shortcuts_font_color' => [
									'label' => __( 'Font Color', 'blocksy-companion' ),
									'type'  => 'ct-color-picker',
									'design' => 'block:right',
									'divider' => 'top',
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
											'inherit' => 'var(--theme-text-color)'
										],

										[
											'title' => __( 'Hover', 'blocksy-companion' ),
											'id' => 'hover',
											'inherit' => 'var(--theme-link-hover-color)'
										],
									],
								],
							],
						],

						'shortcuts_icon_color' => [
							'label' => __( 'Icons Color', 'blocksy-companion' ),
							'type'  => 'ct-color-picker',
							'design' => 'block:right',
							'divider' => 'top',
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
									'inherit' => 'var(--theme-text-color)'
								],

								[
									'title' => __( 'Hover', 'blocksy-companion' ),
									'id' => 'hover',
									'inherit' => 'var(--theme-palette-color-2)'
								],
							],
						],

						'shortcuts_item_color' => [
							'label' => __( 'Item Background Color', 'blocksy-companion' ),
							'type'  => 'ct-color-picker',
							'design' => 'block:right',
							'divider' => 'top',
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
									'inherit' => 'rgba(0, 0, 0, 0)'
								],

								[
									'title' => __( 'Hover', 'blocksy-companion' ),
									'id' => 'hover',
									'inherit' => 'rgba(0, 0, 0, 0.03)'
								],
							],
						],

						blocksy_rand_md5() => [
							'type' => 'ct-condition',
							'condition' => [
								'shortcuts_bar_items:array-ids:cart:enabled' => '!no'
							],
							'options' => class_exists('WooCommerce') ? [

								'shortcuts_cart_badge_color' => [
									'label' => __( 'Cart Badge Color', 'blocksy-companion' ),
									'type'  => 'ct-color-picker',
									'design' => 'block:right',
									'divider' => 'top',
									'responsive' => true,
									'sync' => 'live',
									'value' => [
										'background' => [
											'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
										],

										'text' => [
											'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
										],
									],

									'pickers' => [
										[
											'title' => __( 'Background', 'blocksy-companion' ),
											'id' => 'background',
											'inherit' => 'var(--theme-palette-color-1)',
										],

										[
											'title' => __( 'Text', 'blocksy-companion' ),
											'id' => 'text',
											'inherit' => '#ffffff',
										],
									],
								],

							] : [],
						],

						blocksy_rand_md5() => [
							'type' => 'ct-condition',
							'condition' => [
								'shortcuts_bar_items:array-ids:wishlist:enabled' => '!no'
							],
							'options' => [

								'shortcuts_wishlist_badge_color' => [
									'label' => __( 'Wishlist Badge Color', 'blocksy-companion' ),
									'type'  => 'ct-color-picker',
									'design' => 'block:right',
									'divider' => 'top',
									'responsive' => true,
									'sync' => 'live',
									'value' => [
										'background' => [
											'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
										],

										'text' => [
											'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
										],
									],

									'pickers' => [
										[
											'title' => __( 'Background', 'blocksy-companion' ),
											'id' => 'background',
											'inherit' => 'var(--theme-palette-color-1)',
										],

										[
											'title' => __( 'Text', 'blocksy-companion' ),
											'id' => 'text',
											'inherit' => '#ffffff',
										],
									],
								],

							],
						],

						blocksy_rand_md5() => [
							'type' => 'ct-condition',
							'condition' => [
								'shortcuts_bar_items:array-ids:compare:enabled' => '!no'
							],
							'options' => [

								'shortcuts_compare_badge_color' => [
									'label' => __( 'Compare Badge Color', 'blocksy-companion' ),
									'type'  => 'ct-color-picker',
									'design' => 'block:right',
									'divider' => 'top',
									'responsive' => true,
									'sync' => 'live',
									'value' => [
										'background' => [
											'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
										],

										'text' => [
											'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
										],
									],

									'pickers' => [
										[
											'title' => __( 'Background', 'blocksy-companion' ),
											'id' => 'background',
											'inherit' => 'var(--theme-palette-color-1)',
										],

										[
											'title' => __( 'Text', 'blocksy-companion' ),
											'id' => 'text',
											'inherit' => '#ffffff',
										],
									],
								],

							],
						],

						'shortcuts_divider' => [
							'label' => __( 'Items Divider', 'blocksy-companion' ),
							'type' => 'ct-border',
							'sync' => 'live',
							'design' => 'inline',
							'divider' => 'top',
							'value' => [
								'width' => 1,
								'style' => 'dashed',
								'color' => [
									'color' => 'var(--theme-palette-color-5)',
								],
							]
						],

						blocksy_rand_md5() => [
							'type' => 'ct-condition',
							'condition' => [ 'shortcuts_divider/style' => '!none' ],
							'options' => [

								'shortcuts_divider_height' => [
									'label' => __( 'Items Divider Height', 'blocksy-companion' ),
									'type' => 'ct-slider',
									'value' => 40,
									'min' => 10,
									'max' => 100,
									'defaultUnit' => '%',
									'divider' => 'top',
									'setting' => [ 'transport' => 'postMessage' ],
								],

							],
						],

						'shortcuts_container_shadow' => [
							'label' => __( 'Shadow', 'blocksy-companion' ),
							'type' => 'ct-box-shadow',
							'responsive' => true,
							'divider' => 'top',
							'value' => blocksy_box_shadow_value([
								'enable' => true,
								'h_offset' => 0,
								'v_offset' => -10,
								'blur' => 20,
								'spread' => 0,
								'inset' => false,
								'color' => [
									'color' => 'rgba(44,62,80,0.04)',
								],
							]),
							'setting' => [ 'transport' => 'postMessage' ],
						],

						'shortcuts_container_background' => [
							'label' => __( 'Container Background', 'blocksy-companion' ),
							'type' => 'ct-background',
							'design' => 'block:right',
							'responsive' => true,
							'divider' => 'top',
							'sync' => 'live',
							'value' => blocksy_background_default_value([
								'backgroundColor' => [
									'default' => [
										'color' => 'var(--theme-palette-color-8)',
									],
								],
							])
						],

						'shortcuts_container_blur' => [
							'label' => __( 'Container Backdrop Blur', 'blocksy-companion' ),
							'type' => 'ct-number',
							'design' => 'block:right',
							'value' => 0,
							'min' => 0,
							'max' => 100,
							'responsive' => true,
							'divider' => 'top',
							'setting' => [ 'transport' => 'postMessage' ],
						],

						blocksy_rand_md5() => [
							'type' => 'ct-condition',
							'condition' => [ 'shortcuts_bar_type' => 'type-2' ],
							'options' => [

								'shortcuts_container_border_radius' => [
									'label' => __( 'Container Border Radius', 'blocksy-companion' ),
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
			]
		]
	]
];
