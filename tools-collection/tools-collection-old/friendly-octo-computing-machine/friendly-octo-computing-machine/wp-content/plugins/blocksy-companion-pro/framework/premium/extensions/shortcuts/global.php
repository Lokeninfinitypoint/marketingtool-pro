<?php

$type = blc_theme_functions()->blocksy_get_theme_mod('shortcuts_bar_type', 'type-1');
$interaction = blc_theme_functions()->blocksy_get_theme_mod('shortcuts_interaction', 'none');

// Container height
$container_height = blocksy_expand_responsive_value(
	blc_theme_functions()->blocksy_get_theme_mod('shortcuts_container_height', 70)
);

if ($type === 'type-2') {
	$container_height['desktop'] = intval($container_height['desktop']) + 30;
	$container_height['tablet'] = intval($container_height['tablet']) + 30;
	$container_height['mobile'] = intval($container_height['mobile']) + 30;
}

$shortcuts_bar_visibility = blc_theme_functions()->blocksy_get_theme_mod('shortcuts_bar_visibility', [
	'desktop' => true,
	'tablet' => true,
	'mobile' => true,
]);

$shortcuts_bar_visibility = blocksy_expand_responsive_value($shortcuts_bar_visibility);

if (! $shortcuts_bar_visibility['desktop']) {
	$container_height['desktop'] = '0';
}

if (! $shortcuts_bar_visibility['tablet']) {
	$container_height['tablet'] = '0';
}

if (! $shortcuts_bar_visibility['mobile']) {
	$container_height['mobile'] = '0';
}

blocksy_output_responsive([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => '.ct-drawer-canvas[data-shortcuts-bar]',
	'variableName' => 'shortcuts-bar-height',
	'value' => $container_height
]);

// Container max width
if ($type === 'type-2' || is_customize_preview()) {
	$container_width = blc_theme_functions()->blocksy_get_theme_mod('shortcuts_container_width', '100%');

	if ($container_width !== '100%') {
		blocksy_output_responsive([
			'css' => $css,
			'tablet_css' => $tablet_css,
			'mobile_css' => $mobile_css,
			'selector' => '.ct-shortcuts-bar-items',
			'variableName' => 'shortcuts-bar-width',
			'value' => $container_width,
			'unit' => ''
		]);
	}
}

// Icon size
$icon_size = blc_theme_functions()->blocksy_get_theme_mod('shortcuts_icon_size', 15);

if ($icon_size !== 15) {
	blocksy_output_responsive([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => '.ct-shortcuts-bar-items',
		'variableName' => 'theme-icon-size',
		'value' => $icon_size
	]);
}

blocksy_output_font_css([
	'font_value' => blc_theme_functions()->blocksy_get_theme_mod( 'shortcuts_font',
		blocksy_typography_default_values([
			'size' => '12px',
			'variation' => 'n5',
			'text-transform' => 'uppercase',
		])
	),
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => '.ct-shortcuts-bar-items',
]);

blocksy_output_colors([
	'value' => blc_theme_functions()->blocksy_get_theme_mod('shortcuts_font_color'),
	'default' => [
		'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		'hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
	],
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'variables' => [
		'default' => [
			'selector' => '.ct-shortcuts-bar-items a',
			'variable' => 'theme-link-initial-color'
		],

		'hover' => [
			'selector' => '.ct-shortcuts-bar-items a',
			'variable' => 'theme-link-hover-color'
		],
	],
	'responsive' => true,
]);

blocksy_output_colors([
	'value' => blc_theme_functions()->blocksy_get_theme_mod('shortcuts_icon_color'),
	'default' => [
		'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		'hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
	],
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'variables' => [
		'default' => [
			'selector' => '.ct-shortcuts-bar-items',
			'variable' => 'theme-icon-color'
		],

		'hover' => [
			'selector' => '.ct-shortcuts-bar-items',
			'variable' => 'theme-icon-hover-color'
		],
	],
	'responsive' => true,
]);

blocksy_output_colors([
	'value' => blc_theme_functions()->blocksy_get_theme_mod('shortcuts_item_color'),
	'default' => [
		'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		'hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
	],
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'variables' => [
		'default' => [
			'selector' => '.ct-shortcuts-bar-items',
			'variable' => 'item-color'
		],

		'hover' => [
			'selector' => '.ct-shortcuts-bar-items',
			'variable' => 'item-hover-color'
		],
	],
	'responsive' => true,
]);

blocksy_output_colors([
	'value' => blc_theme_functions()->blocksy_get_theme_mod('shortcuts_cart_badge_color'),
	'default' => [
		'background' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		'text' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
	],
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'variables' => [
		'background' => [
			'selector' => '.ct-shortcuts-bar-items [data-shortcut="cart"]',
			'variable' => 'theme-cart-badge-background'
		],

		'text' => [
			'selector' => '.ct-shortcuts-bar-items [data-shortcut="cart"]',
			'variable' => 'theme-cart-badge-text'
		],
	],
	'responsive' => true,
]);

blocksy_output_colors([
	'value' => blc_theme_functions()->blocksy_get_theme_mod('shortcuts_wishlist_badge_color'),
	'default' => [
		'background' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		'text' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
	],
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'variables' => [
		'background' => [
			'selector' => '.ct-shortcuts-bar-items [data-shortcut="wishlist"]',
			'variable' => 'theme-cart-badge-background'
		],

		'text' => [
			'selector' => '.ct-shortcuts-bar-items [data-shortcut="wishlist"]',
			'variable' => 'theme-cart-badge-text'
		],
	],
	'responsive' => true,
]);

blocksy_output_colors([
	'value' => blc_theme_functions()->blocksy_get_theme_mod('shortcuts_compare_badge_color'),
	'default' => [
		'background' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		'text' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
	],
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'variables' => [
		'background' => [
			'selector' => '.ct-shortcuts-bar-items [data-shortcut="compare"]',
			'variable' => 'theme-cart-badge-background'
		],

		'text' => [
			'selector' => '.ct-shortcuts-bar-items [data-shortcut="compare"]',
			'variable' => 'theme-cart-badge-text'
		],
	],
	'responsive' => true,
]);

blocksy_output_border([
	'css' => $css,
	'selector' => '.ct-shortcuts-bar-items',
	'variableName' => 'shortcuts-divider',
	'value' => blc_theme_functions()->blocksy_get_theme_mod('shortcuts_divider'),
	'skip_none' => true,
	'default' => [
		'width' => 1,
		'style' => 'dashed',
		'color' => [
			'color' => 'var(--theme-palette-color-5)',
		],
	],
]);

$divider_height = blc_theme_functions()->blocksy_get_theme_mod( 'shortcuts_divider_height', 40 );

$divider_style = blc_theme_functions()->blocksy_get_theme_mod('shortcuts_divider', [
	'width' => 1,
	'style' => 'dashed',
	'color' => [
		'color' => 'var(--theme-palette-color-5)',
	],
]);

if (
	(
		$divider_height !== 40
		&&
		is_array($divider_style)
		&&
		isset($divider_style['style'])
		&&
		$divider_style['style'] !== 'none'
	) || is_customize_preview()
) {

	$css->put(
		'.ct-shortcuts-bar-items',
		'--shortcuts-divider-height: ' . $divider_height . '%'
	);
}

blocksy_output_background_css([
	'selector' => '.ct-shortcuts-bar-items',
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'value' => blc_theme_functions()->blocksy_get_theme_mod(
		'shortcuts_container_background',
		blocksy_background_default_value([
			'backgroundColor' => [
				'default' => [
					'color' => 'var(--theme-palette-color-8)'
				],
			],
		])
	),
	'responsive' => true,
]);


$shortcuts_container_blur = blc_theme_functions()->blocksy_get_theme_mod('shortcuts_container_blur', 0);

if($shortcuts_container_blur !== 0) {
	blocksy_output_responsive([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => '.ct-shortcuts-bar-items',
		'variableName' => 'theme-backdrop-blur',
		'value' => $shortcuts_container_blur,
		'unit' => 'px',
		'should_skip_output' => false
	]);
}


blocksy_output_box_shadow([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => '.ct-shortcuts-bar-items',
	'should_skip_output' => false,
	'value' => blc_theme_functions()->blocksy_get_theme_mod(
		'shortcuts_container_shadow',
		blocksy_box_shadow_value([
			'enable' => true,
			'h_offset' => 0,
			'v_offset' => -10,
			'blur' => 20,
			'spread' => 0,
			'inset' => false,
			'color' => [
				'color' => 'rgba(44,62,80,0.04)',
			],
		])
	),
	'responsive' => true
]);

if ($type === 'type-2') {
	blocksy_output_spacing([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => '.ct-shortcuts-bar-items',
		'property' => 'theme-border-radius',
		'value' => blc_theme_functions()->blocksy_get_theme_mod(
			'shortcuts_container_border_radius',
			blocksy_spacing_value()
		),
		'empty_value' => 7,
	]);
}
