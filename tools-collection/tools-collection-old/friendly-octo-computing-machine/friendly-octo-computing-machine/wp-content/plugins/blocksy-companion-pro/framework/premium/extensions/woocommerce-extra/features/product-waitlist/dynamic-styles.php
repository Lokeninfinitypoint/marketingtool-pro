<?php

$container_max_width = blc_theme_functions()->blocksy_get_theme_mod('waitlist_container_max_width', 100);

if ($container_max_width !== 100) {
	blocksy_output_responsive([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => '.ct-product-waitlist',
		'variableName' => 'container-max-width',
		'value' => $container_max_width,
		'unit' => '%'
	]);
}

blocksy_output_font_css([
	'font_value' => blc_theme_functions()->blocksy_get_theme_mod(
		'waitlist_title_font',
		blocksy_typography_default_values([
			'size' => '16px',
		])
	),
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => '.ct-waitlist-title'
]);

blocksy_output_colors([
	'value' => blc_theme_functions()->blocksy_get_theme_mod('waitlist_title_color'),
	'default' => [
		'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
	],
	'css' => $css,
	'variables' => [
		'default' => [
			'selector' => '.ct-waitlist-title',
			'variable' => 'theme-heading-color'
		],
	],
]);

blocksy_output_font_css([
	'font_value' => blc_theme_functions()->blocksy_get_theme_mod(
		'waitlist_message_font',
		blocksy_typography_default_values([
			'size' => '15px',
		])
	),
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => '.ct-waitlist-message'
]);

blocksy_output_colors([
	'value' => blc_theme_functions()->blocksy_get_theme_mod('waitlist_message_color'),
	'default' => [
		'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
	],
	'css' => $css,
	'variables' => [
		'default' => [
			'selector' => '.ct-product-waitlist p',
			'variable' => 'theme-text-color'
		],
	],
]);

blocksy_output_border([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => '.ct-product-waitlist',
	'variableName' => 'container-border',
	'value' => blc_theme_functions()->blocksy_get_theme_mod('waitlist_form_border'),
	'skip_none' => true,
	'default' => [
		'width' => 2,
		'style' => 'solid',
		'color' => [
			'color' => 'var(--theme-border-color)',
		],
	],
	'responsive' => true,
]);

blocksy_output_background_css([
	'selector' => '.ct-product-waitlist',
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'value' => blc_theme_functions()->blocksy_get_theme_mod(
		'waitlist_form_background',
		blocksy_background_default_value([
			'backgroundColor' => [
				'default' => [
					'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
				],
			],
		])
	),
	'responsive' => true,
]);

blocksy_output_spacing([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => '.ct-product-waitlist',
	'property' => 'container-padding',
	'value' => blc_theme_functions()->blocksy_get_theme_mod(
		'waitlist_form_padding',
		blocksy_spacing_value()
	),
	'empty_value' => 30
]);

blocksy_output_spacing([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => '.ct-product-waitlist',
	'property' => 'container-border-radius',
	'value' => blc_theme_functions()->blocksy_get_theme_mod(
		'waitlist_form_border_radius',
		blocksy_spacing_value()
	),
	'empty_value' => 7
]);