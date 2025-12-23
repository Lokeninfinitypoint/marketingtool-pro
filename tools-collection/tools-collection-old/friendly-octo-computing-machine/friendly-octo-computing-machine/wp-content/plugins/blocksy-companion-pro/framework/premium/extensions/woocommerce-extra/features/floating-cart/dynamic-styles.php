<?php

$position = blocksy_expand_responsive_value(blc_theme_functions()->blocksy_get_theme_mod('floating_bar_position', 'top'));

$visibility = blocksy_expand_responsive_value(
	blc_theme_functions()->blocksy_get_theme_mod(
		'floatingBarVisibility',
		[
			'desktop' => true,
			'tablet' => true,
			'mobile' => true,
		]
	)
);

$floating_bar_height = [
	'desktop' => '70',
	'tablet' => '70',
	'mobile' => '70'
];

if (! $visibility['desktop']) {
	$floating_bar_height['desktop'] = '0';
}

if (! $visibility['tablet']) {
	$floating_bar_height['tablet'] = '0';
}

if (! $visibility['mobile']) {
	$floating_bar_height['mobile'] = '0';
}

blocksy_output_responsive([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => '.ct-drawer-canvas[data-floating-bar]',
	'variableName' => 'floating-bar-height',
	'value' => $floating_bar_height
]);

if (
	$position['desktop'] !== 'top'
	||
	$position['tablet'] !== 'top'
	||
	$position['mobile'] !== 'top'
) {
	blocksy_output_responsive([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => '.ct-floating-bar',
		'variableName' => 'top-position-override',
		'value' => [
			'desktop' => $position['desktop'] === 'top' ? 'var(--top-position)' : 'var(--false)',
			'tablet' => $position['tablet'] === 'top' ? 'var(--top-position)' : 'var(--false)',
			'mobile' => $position['mobile'] === 'top' ? 'var(--top-position)' : 'var(--false)'
		],
		'unit' => '',
	]);

	blocksy_output_responsive([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => '.ct-floating-bar',
		'variableName' => 'translate-offset',
		'value' => [
			'desktop' => $position['desktop'] === 'top' ? '-70px' : '70px',
			'tablet' => $position['tablet'] === 'top' ? '-70px' : '70px',
			'mobile' => $position['mobile'] === 'top' ? '-70px' : '70px'
		],
		'unit' => '',
	]);
}

blocksy_output_colors([
	'value' => blc_theme_functions()->blocksy_get_theme_mod('floatingBarFontColor'),
	'default' => [
		'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
	],
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'responsive' => true,
	'variables' => [
		'default' => [
			'selector' => '.ct-floating-bar .product-title, .ct-floating-bar .price',
			'variable' => 'theme-text-color'
		],
	],
]);

blocksy_output_background_css([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'responsive' => true,
	'selector' => '.ct-floating-bar',
	'value' => blc_theme_functions()->blocksy_get_theme_mod('floatingBarBackground',
		blocksy_background_default_value([
			'backgroundColor' => [
				'default' => [
					'color' => 'var(--theme-palette-color-8)'
				],
			],
		])
	)
]);

blocksy_output_box_shadow([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => '.ct-floating-bar',
	'should_skip_output' => false,
	'value' => blc_theme_functions()->blocksy_get_theme_mod(
		'floatingBarShadow',
		blocksy_box_shadow_value([
			'enable' => true,
			'h_offset' => 0,
			'v_offset' => 10,
			'blur' => 20,
			'spread' => 0,
			'inset' => false,
			'color' => [
				'color' => 'rgba(44,62,80,0.15)',
			],
		])
	),
	'responsive' => true
]);
