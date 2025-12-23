<?php

$divider_size = blocksy_akg( 'divider_height', $atts, '100' );

if ($divider_size !== '100') {
	blocksy_output_responsive([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => blocksy_assemble_selector($root_selector),
		'variableName' => 'divider-size',
		'value' => $divider_size,
		'unit' => '%',
		'responsive' => true
	]);
}

blocksy_output_spacing([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => blocksy_assemble_selector($root_selector),
	'important' => true,
	'value' => blocksy_default_akg(
		'divider_horizontal_margin',
		$atts,
		blocksy_spacing_value([
			'top' => 'auto',
			'bottom' => 'auto',
		])
	)
]);

blocksy_output_spacing([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => blocksy_assemble_selector($root_selector),
	'important' => true,
	'value' => blocksy_default_akg(
		'divider_vertical_margin',
		$atts,
		blocksy_spacing_value([
			'left' => 'auto',
			'right' => 'auto',
		])
	)
]);

blocksy_output_border([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => blocksy_assemble_selector($root_selector),
	'variableName' => 'divider-style',
	'value' => blocksy_akg('header_divider', $atts),
	'default' => [
		'width' => 1,
		'style' => 'solid',
		'color' => [ 'color' => 'rgba(44,62,80,0.2)' ],
	],
	'responsive' => true
]);

// transparent state
if (isset($has_transparent_header) && $has_transparent_header) {
	blocksy_output_border([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => blocksy_assemble_selector(blocksy_mutate_selector([
			'selector' => $root_selector,
			'operation' => 'between',
			'to_add' => '[data-transparent-row="yes"]'
		])),
		'variableName' => 'divider-style',
		'value' => blocksy_akg('transparent_header_divider', $atts),
		'default' => [
			'width' => 1,
			'style' => 'solid',
			'color' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		],
		'responsive' => true
	]);
}

// sticky state
if (isset($has_sticky_header) && $has_sticky_header) {
	blocksy_output_border([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => blocksy_assemble_selector(blocksy_mutate_selector([
			'selector' => $root_selector,
			'operation' => 'between',
			'to_add' => '[data-sticky*="yes"]'
		])),
		'variableName' => 'divider-style',
		'value' => blocksy_akg('sticky_header_divider', $atts),
		'default' => [
			'width' => 1,
			'style' => 'solid',
			'color' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		],
		'responsive' => true
	]);
}

