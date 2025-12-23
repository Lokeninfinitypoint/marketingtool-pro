<?php

blocksy_output_responsive([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => '.ct-quick-view-card',
	'variableName' => 'theme-normal-container-max-width',
	'value' => blc_theme_functions()->blocksy_get_theme_mod('woocommerce_quick_view_width', 1050),
	'unit' => 'px'
]);

blocksy_output_font_css([
	'font_value' => blc_theme_functions()->blocksy_get_theme_mod(
		'quickViewProductTitleFont',
		blocksy_typography_default_values([
			// 'size' => '30px',
		])
	),
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => '.ct-quick-view-card .product_title'
]);

blocksy_output_colors([
	'value' => blc_theme_functions()->blocksy_get_theme_mod('quick_view_title_color'),
	'default' => [
		'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
	],
	'css' => $css,
	'variables' => [
		'default' => [
			'selector' => '.ct-quick-view-card .entry-summary .product_title',
			'variable' => 'theme-heading-color'
		],
	],
]);

blocksy_output_font_css([
	'font_value' => blc_theme_functions()->blocksy_get_theme_mod(
		'quickViewProductPriceFont',
		blocksy_typography_default_values([
			// 'size' => '30px',
		])
	),
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => '.ct-quick-view-card .entry-summary .price'
]);

blocksy_output_colors([
	'value' => blc_theme_functions()->blocksy_get_theme_mod('quick_view_price_color'),
	'default' => [
		'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
	],
	'css' => $css,
	'variables' => [
		'default' => [
			'selector' => '.ct-quick-view-card .entry-summary .price',
			'variable' => 'theme-text-color'
		],
	],
]);

blocksy_output_colors([
	'value' => blc_theme_functions()->blocksy_get_theme_mod('quick_view_description_color'),
	'default' => [
		'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
	],
	'css' => $css,
	'variables' => [
		'default' => [
			'selector' => '.ct-quick-view-card .woocommerce-product-details__short-description',
			'variable' => 'theme-text-color'
		],
	],
]);



blocksy_output_colors([
	'value' => blc_theme_functions()->blocksy_get_theme_mod('quick_view_add_to_cart_text'),
	'default' => [
		'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		'hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
	],
	'css' => $css,
	'variables' => [
		'default' => [
			'selector' => '.ct-quick-view-card .entry-summary .single_add_to_cart_button',
			'variable' => 'theme-button-text-initial-color'
		],

		'hover' => [
			'selector' => '.ct-quick-view-card .entry-summary .single_add_to_cart_button',
			'variable' => 'theme-button-text-hover-color'
		],
	],
]);

blocksy_output_colors([
	'value' => blc_theme_functions()->blocksy_get_theme_mod('quick_view_add_to_cart_background'),
	'default' => [
		'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		'hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
	],
	'css' => $css,
	'variables' => [
		'default' => [
			'selector' => '.ct-quick-view-card .entry-summary .single_add_to_cart_button',
			'variable' => 'theme-button-background-initial-color'
		],

		'hover' => [
			'selector' => '.ct-quick-view-card .entry-summary .single_add_to_cart_button',
			'variable' => 'theme-button-background-hover-color'
		],
	],
]);

blocksy_output_colors([
	'value' => blc_theme_functions()->blocksy_get_theme_mod('quick_view_view_cart_button_text'),
	'default' => [
		'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		'hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
	],
	'css' => $css,
	'variables' => [
		'default' => [
			'selector' => '.ct-quick-view-card .entry-summary .ct-cart-actions .added_to_cart',
			'variable' => 'theme-button-text-initial-color'
		],

		'hover' => [
			'selector' => '.ct-quick-view-card .entry-summary .ct-cart-actions .added_to_cart',
			'variable' => 'theme-button-text-hover-color'
		],
	],
]);

blocksy_output_colors([
	'value' => blc_theme_functions()->blocksy_get_theme_mod('quick_view_view_cart_button_background'),
	'default' => [
		'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		'hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
	],
	'css' => $css,
	'variables' => [
		'default' => [
			'selector' => '.ct-quick-view-card .entry-summary .ct-cart-actions .added_to_cart',
			'variable' => 'theme-button-background-initial-color'
		],

		'hover' => [
			'selector' => '.ct-quick-view-card .entry-summary .ct-cart-actions .added_to_cart',
			'variable' => 'theme-button-background-hover-color'
		],
	],
]);

blocksy_output_colors([
	'value' => blc_theme_functions()->blocksy_get_theme_mod('quick_view_product_page_button_text'),
	'default' => [
		'default' => [ 'color' => 'var(--theme-text-color)' ],
		'hover' => [ 'color' => 'var(--theme-text-color)' ],
	],
	'css' => $css,
	'variables' => [
		'default' => [
			'selector' => '.ct-quick-view-card .entry-summary .ct-quick-more',
			'variable' => 'theme-button-text-initial-color'
		],

		'hover' => [
			'selector' => '.ct-quick-view-card .entry-summary .ct-quick-more',
			'variable' => 'theme-button-text-hover-color'
		],
	],
]);

blocksy_output_colors([
	'value' => blc_theme_functions()->blocksy_get_theme_mod('quick_view_product_page_button_background'),
	'default' => [
		'default' => [ 'color' => 'rgba(224,229,235,0.6)' ],
		'hover' => [ 'color' => 'rgba(224,229,235,1)' ],
	],
	'css' => $css,
	'variables' => [
		'default' => [
			'selector' => '.ct-quick-view-card .entry-summary .ct-quick-more',
			'variable' => 'theme-button-background-initial-color'
		],

		'hover' => [
			'selector' => '.ct-quick-view-card .entry-summary .ct-quick-more',
			'variable' => 'theme-button-background-hover-color'
		],
	],
]);



blocksy_output_box_shadow([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => '.ct-quick-view-card',
	'value' => blc_theme_functions()->blocksy_get_theme_mod('quick_view_shadow', blocksy_box_shadow_value([
		'enable' => true,
		'h_offset' => 0,
		'v_offset' => 50,
		'blur' => 100,
		'spread' => 0,
		'inset' => false,
		'color' => [
			'color' => 'rgba(18, 21, 25, 0.5)',
		],
	])),
]);

blocksy_output_spacing([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => '.ct-quick-view-card',
	'property' => 'theme-border-radius',
	'value' => blc_theme_functions()->blocksy_get_theme_mod( 'quick_view_radius',
		blocksy_spacing_value()
	),
	'empty_value' => 7
]);

blocksy_output_background_css([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => '.ct-quick-view-card',
	'value' => blc_theme_functions()->blocksy_get_theme_mod('quick_view_background',
		blocksy_background_default_value([
			'backgroundColor' => [
				'default' => [
					'color' => 'var(--theme-palette-color-8)'
				],
			],
		])
	)
]);

blocksy_output_background_css([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => '.quick-view-modal',
	'value' => blc_theme_functions()->blocksy_get_theme_mod('quick_view_backdrop',
		blocksy_background_default_value([
			'backgroundColor' => [
				'default' => [
					'color' => 'rgba(18, 21, 25, 0.8)'
				],
			],
		])
	)
]);