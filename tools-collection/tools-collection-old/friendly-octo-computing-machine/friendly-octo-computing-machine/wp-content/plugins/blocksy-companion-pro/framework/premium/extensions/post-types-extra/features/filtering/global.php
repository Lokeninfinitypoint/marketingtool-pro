<?php

$options_prefix = $prefix;

if ($options_prefix === 'categories') {
	$options_prefix = 'blog';
}

$has_archive_filtering = blc_theme_functions()->blocksy_get_theme_mod(
	$options_prefix . '_has_archive_filtering',
	'no'
);

if ($has_archive_filtering === 'no') {
	return;
}

$type = blc_theme_functions()->blocksy_get_theme_mod(
	$options_prefix . '_filter_type',
	'simple'
);


$items_horizontal_spacing = blc_theme_functions()->blocksy_get_theme_mod(
	$options_prefix . '_filter_items_horizontal_spacing',
	30
);

if ($items_horizontal_spacing !== 30) {
	blocksy_output_responsive([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => blocksy_prefix_selector('.ct-dynamic-filter', $prefix),
		'variableName' => 'items-horizontal-spacing',
		'unit' => 'px',
		'value' => $items_horizontal_spacing,
	]);
}


$items_vertical_spacing = blc_theme_functions()->blocksy_get_theme_mod(
	$options_prefix . '_filter_items_vertical_spacing',
	10
);

if ($items_vertical_spacing !== 10) {
	blocksy_output_responsive([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => blocksy_prefix_selector('.ct-dynamic-filter', $prefix),
		'variableName' => 'items-vertical-spacing',
		'unit' => 'px',
		'value' => $items_vertical_spacing,
	]);
}


$container_spacing = blc_theme_functions()->blocksy_get_theme_mod(
	$options_prefix . '_filter_container_spacing',
	40
);

if ($container_spacing !== 40) {
	blocksy_output_responsive([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => blocksy_prefix_selector('.ct-dynamic-filter', $prefix),
		'variableName' => 'container-spacing',
		'value' => $container_spacing,
	]);
}


$alignment = blc_theme_functions()->blocksy_get_theme_mod(
	$options_prefix . '_horizontal_alignment',
	'center'
);

if ($alignment !== 'center') {
	blocksy_output_responsive([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => blocksy_prefix_selector('.ct-dynamic-filter', $prefix),
		'variableName' => 'filter-items-alignment',
		'value' => $alignment,
		'unit' => '',
	]);
}


blocksy_output_font_css([
	'font_value' => blc_theme_functions()->blocksy_get_theme_mod(
		$options_prefix . '_filter_font',
		blocksy_typography_default_values([
			'size' => '12px',
			'variation' => 'n6',
			'text-transform' => 'uppercase',
		])
	),
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => blocksy_prefix_selector('.ct-dynamic-filter', $prefix),
]);


blocksy_output_colors([
	'value' => blc_theme_functions()->blocksy_get_theme_mod($options_prefix . '_filter_font_color'),
	'default' => [
		'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		'hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],

		'default_2' => [ 'color' => '#ffffff' ],
		'hover_2' => [ 'color' => '#ffffff' ],
	],
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'variables' => [
		'default' => [
			'selector' => blocksy_prefix_selector('.ct-dynamic-filter[data-type="simple"]', $prefix),
			'variable' => 'theme-link-initial-color'
		],

		'hover' => [
			'selector' => blocksy_prefix_selector('.ct-dynamic-filter[data-type="simple"]', $prefix),
			'variable' => 'theme-link-hover-color'
		],

		'default_2' => [
			'selector' => blocksy_prefix_selector('.ct-dynamic-filter[data-type="buttons"]', $prefix),
			'variable' => 'theme-link-initial-color'
		],

		'hover_2' => [
			'selector' => blocksy_prefix_selector('.ct-dynamic-filter[data-type="buttons"]', $prefix),
			'variable' => 'theme-link-hover-color'
		],
	],
	'responsive' => true,
]);

if ($type === 'buttons' || is_customize_preview()) {
	blocksy_output_colors([
		'value' => blc_theme_functions()->blocksy_get_theme_mod($options_prefix . '_filter_button_color'),
		'default' => [
			'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
			'hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		],
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'variables' => [
			'default' => [
				'selector' => blocksy_prefix_selector('.ct-dynamic-filter[data-type="buttons"]', $prefix),
				'variable' => 'theme-button-background-initial-color'
			],

			'hover' => [
				'selector' => blocksy_prefix_selector('.ct-dynamic-filter[data-type="buttons"]', $prefix),
				'variable' => 'theme-button-background-hover-color'
			],
		],
		'responsive' => true,
	]);

	blocksy_output_spacing([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => blocksy_prefix_selector('.ct-dynamic-filter[data-type="buttons"]', $prefix),
		'property' => 'padding',
		'value' => blc_theme_functions()->blocksy_get_theme_mod(
			$options_prefix . '_filter_button_padding',
			blocksy_spacing_value([
				// 'top' => '8px',
				// 'left' => '15px',
				// 'right' => '15px',
				// 'bottom' => '8px',
			])
		)
	]);

	blocksy_output_spacing([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => blocksy_prefix_selector('.ct-dynamic-filter[data-type="buttons"]', $prefix),
		'property' => 'theme-border-radius',
		'value' => blc_theme_functions()->blocksy_get_theme_mod(
			$options_prefix . '_filter_button_border_radius',
			blocksy_spacing_value()
		),
		'empty_value' => 3,
	]);
}
