<?php


$quick_view_additional_actions = apply_filters(
	'blocksy_quick_view_options:after',
	[
		// ['id' => '...', 'label' => '...']
	]
);

$quick_view_additional_actions_options_before = [];
$quick_view_additional_actions_options_after = [];

if (! empty($quick_view_additional_actions)) {
	$quick_view_additional_actions_options_before = [
		blocksy_rand_md5() => [
			'type' => 'ct-title',
			// 'variation' => 'simple',
			'label' => __( 'Additional Actions', 'blocksy-companion' ),
		],
	];

	$quick_view_additional_actions_options_after = [
		'quick_view_additional_actions_type' => [
			'label' => __('Buttons Type', 'blocksy-companion'),
			'type' => 'ct-radio',
			'value' => 'link',
			'view' => 'text',
			'design' => 'block',
			'divider' => 'top',
			'choices' => [
				'link' => __('Link', 'blocksy-companion'),
				'button' => __('Button', 'blocksy-companion'),
			]
		],

		'has_quick_view_label_visibility' => [
			'label' => __('Label Visibility', 'blocksy-companion'),
			'type' => 'ct-visibility',
			'design' => 'block',
			'allow_empty' => true,
			'value' => blocksy_default_responsive_value([
				'desktop' => true,
				'tablet' => true,
				'mobile' => true,
			]),

			'choices' => blocksy_ordered_keys([
				'desktop' => __('Desktop', 'blocksy-companion'),
				'tablet' => __('Tablet', 'blocksy-companion'),
				'mobile' => __('Mobile', 'blocksy-companion'),
			]),
		],
	];
}

$options = [
	'label' => __( 'Quick View', 'blocksy-companion' ),
	'type' => 'ct-panel',
	'value' => 'no',
	'sync' => blocksy_sync_whole_page([
		'loader_selector' => '.woo-listing-top, #woo-filters-panel' // Replace
	]),
	'inner-options' => [
		blocksy_rand_md5() => [
			'title' => __( 'General', 'blocksy-companion' ),
			'type' => 'tab',
			'options' => [

				[
					'woocommerce_quick_view_trigger' => [
						'label' => __( 'Modal Trigger', 'blocksy-companion' ),
						'type' => 'ct-radio',
						'value' => 'button',
						'view' => 'text',
						'design' => 'block',
						'divider' => 'bottom',
						'setting' => [ 'transport' => 'postMessage' ],
						'choices' => [
							'button' => __( 'Button', 'blocksy-companion' ),
							'image' => __( 'Image', 'blocksy-companion' ),
							'card' => __( 'Card', 'blocksy-companion' ),
						],

						'sync' => blocksy_sync_whole_page([
							'loader_selector' => '[data-products] > li'
						]),
					],

					'woocommerce_quick_view_width' => [
						'label' => __( 'Modal Width', 'blocksy-companion' ),
						'type' => 'ct-slider',
						'min' => 500,
						'max' => 1500,
						'value' => 1050,
						'responsive' => true,
						'divider' => 'bottom',
						'setting' => [ 'transport' => 'postMessage' ],
					],

					'woocommerce_quickview_navigation' => [
						'label' => __( 'Product Navigation', 'blocksy-companion' ),
						'type' => 'ct-switch',
						'value' => 'no',
						'divider' => 'bottom:full',
						'setting' => [ 'transport' => 'postMessage' ],
						'sync' => 'live',
						'desc' => __( 'Display next/previous buttons that will help to easily navigate through products.', 'blocksy-companion' ),
					],

					'woocommerce_quickview_gallery_ratio' => [
						'label' => __('Image Ratio', 'blocksy-companion'),
						'type' => 'ct-ratio',
						'view' => 'inline',
						'value' => '3/4',
						// 'divider' => 'bottom:full',
						'sync' => 'live'
					],
				],

				$quick_view_additional_actions_options_before,

				apply_filters(
					'blocksy_quick_view_options:after',
					[]
				),

				$quick_view_additional_actions_options_after
			]
		],
		blocksy_rand_md5() => [
			'title' => __( 'Design', 'blocksy-companion' ),
			'type' => 'tab',
			'options' => [

				'quickViewProductTitleFont' => [
					'type' => 'ct-typography',
					'label' => __( 'Title Font', 'blocksy-companion' ),
					'value' => blocksy_typography_default_values([
						// 'size' => '30px',
					]),
					'setting' => [ 'transport' => 'postMessage' ],
				],

				'quick_view_title_color' => [
					'label' => __( 'Title Color', 'blocksy-companion' ),
					'type'  => 'ct-color-picker',
					'design' => 'inline',
					'divider' => 'bottom:full',
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
							'inherit' => 'var(--theme-heading-1-color, var(--theme-headings-color))'
						],
					],
				],

				'quickViewProductPriceFont' => [
					'type' => 'ct-typography',
					'label' => __( 'Price Font', 'blocksy-companion' ),
					'value' => blocksy_typography_default_values([
						// 'size' => '30px',
					]),
					'setting' => [ 'transport' => 'postMessage' ],
				],

				'quick_view_price_color' => [
					'label' => __( 'Price Color', 'blocksy-companion' ),
					'type'  => 'ct-color-picker',
					'design' => 'inline',
					'divider' => 'bottom:full',
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

				'quick_view_description_color' => [
					'label' => __( 'Description Color', 'blocksy-companion' ),
					'type'  => 'ct-color-picker',
					'design' => 'inline',
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


				blocksy_rand_md5() => [
					'type' => 'ct-title',
					'label' => __('Add To Cart Button', 'blocksy-companion'),
				],

				'quick_view_add_to_cart_text' => [
					'label' => __('Button Font Color', 'blocksy-companion'),
					'type'  => 'ct-color-picker',
					'design' => 'inline',
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
							'title' => __('Initial', 'blocksy-companion'),
							'id' => 'default',
							'inherit' => 'var(--theme-button-text-initial-color)',
						],

						[
							'title' => __('Hover', 'blocksy-companion'),
							'id' => 'hover',
							'inherit' => 'var(--theme-button-text-hover-color)',
						],
					],
				],

				'quick_view_add_to_cart_background' => [
					'label' => __('Button Background Color', 'blocksy-companion'),
					'type'  => 'ct-color-picker',
					'design' => 'inline',
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
							'title' => __('Initial', 'blocksy-companion'),
							'id' => 'default',
							'inherit' => 'var(--theme-button-background-initial-color)'
						],

						[
							'title' => __('Hover', 'blocksy-companion'),
							'id' => 'hover',
							'inherit' => 'var(--theme-button-background-hover-color)'
						],
					],
				],

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'values_source' => 'global',
					'condition' => ['has_ajax_add_to_cart' => 'yes'],
					'options' => [

						blocksy_rand_md5() => [
							'type' => 'ct-title',
							'variation' => 'small-divider',
							'label' => __('View Cart Button', 'blocksy-companion'),
						],

						'quick_view_view_cart_button_text' => [
							'label' => __('Button Font Color', 'blocksy-companion'),
							'type'  => 'ct-color-picker',
							'design' => 'inline',
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
									'title' => __('Initial', 'blocksy-companion'),
									'id' => 'default',
									'inherit' => 'var(--theme-text-color)',
								],

								[
									'title' => __('Hover', 'blocksy-companion'),
									'id' => 'hover',
									'inherit' => 'var(--theme-text-color)',
								],
							],
						],

						'quick_view_view_cart_button_background' => [
							'label' => __('Button Background Color', 'blocksy-companion'),
							'type'  => 'ct-color-picker',
							'design' => 'inline',
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
									'title' => __('Initial', 'blocksy-companion'),
									'id' => 'default',
									'inherit' => 'rgba(224,229,235,0.6)'
								],

								[
									'title' => __('Hover', 'blocksy-companion'),
									'id' => 'hover',
									'inherit' => 'rgba(224,229,235,1)'
								],
							],
						],

					],
				],


				blocksy_rand_md5() => [
					'type' => 'ct-title',
					'variation' => 'small-divider',
					'label' => __('Product Page Button', 'blocksy-companion'),
				],

				'quick_view_product_page_button_text' => [
					'label' => __('Button Font Color', 'blocksy-companion'),
					'type'  => 'ct-color-picker',
					'design' => 'inline',
					'sync' => 'live',
					'value' => [
						'default' => [
							'color' => 'var(--theme-text-color)',
						],

						'hover' => [
							'color' => 'var(--theme-text-color)',
						],
					],

					'pickers' => [
						[
							'title' => __('Initial', 'blocksy-companion'),
							'id' => 'default',
						],

						[
							'title' => __('Hover', 'blocksy-companion'),
							'id' => 'hover',
						],
					],
				],

				'quick_view_product_page_button_background' => [
					'label' => __('Button Background Color', 'blocksy-companion'),
					'type'  => 'ct-color-picker',
					'design' => 'inline',
					'sync' => 'live',
					'value' => [
						'default' => [
							'color' => 'rgba(224,229,235,0.6)',
						],

						'hover' => [
							'color' => 'rgba(224,229,235,1)',
						],
					],

					'pickers' => [
						[
							'title' => __('Initial', 'blocksy-companion'),
							'id' => 'default',
						],

						[
							'title' => __('Hover', 'blocksy-companion'),
							'id' => 'hover',
						],
					],
				],


				'quick_view_background' => [
					'label' => __( 'Modal Background', 'blocksy-companion' ),
					'type'  => 'ct-background',
					'design' => 'inline',
					'divider' => 'top:full',
					'setting' => [ 'transport' => 'postMessage' ],
					'value' => blocksy_background_default_value([
						'backgroundColor' => [
							'default' => [
								'color' => 'var(--theme-palette-color-8)'
							],
						],
					])
				],

				'quick_view_backdrop' => [
					'label' => __( 'Modal Backdrop', 'blocksy-companion' ),
					'type'  => 'ct-background',
					'design' => 'inline',
					'setting' => [ 'transport' => 'postMessage' ],
					'value' => blocksy_background_default_value([
						'backgroundColor' => [
							'default' => [
								'color' => 'rgba(18, 21, 25, 0.8)'
							],
						],
					])
				],

				'quick_view_shadow' => [
					'label' => __( 'Modal Shadow', 'blocksy-companion' ),
					'type' => 'ct-box-shadow',
					'design' => 'inline',
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

				'quick_view_radius' => [
					'label' => __( 'Modal Border Radius', 'blocksy-companion' ),
					'type' => 'ct-spacing',
					'setting' => [ 'transport' => 'postMessage' ],
					'value' => blocksy_spacing_value(),
					'inputAttr' => [
						'placeholder' => '7'
					],
					'min' => 0,
				],
			]
		]
	]
];
