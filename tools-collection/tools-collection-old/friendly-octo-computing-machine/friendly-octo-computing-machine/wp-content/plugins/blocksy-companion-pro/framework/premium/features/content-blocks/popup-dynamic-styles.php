<?php

$popup_size = blocksy_default_akg('popup_size', $atts, 'medium');

if ($popup_size === 'custom') {
	$popup_max_width = blocksy_default_akg('popup_max_width', $atts, '400px');

	if ($popup_max_width !== '400px') {
		blocksy_output_responsive([
			'css' => $css,
			'tablet_css' => $tablet_css,
			'mobile_css' => $mobile_css,
			'selector' => blocksy_prefix_selector('', 'block:' . $id),
			'variableName' => 'popup-max-width',
			'unit' => '',
			'value' => $popup_max_width,
		]);
	}

	$popup_max_height = blocksy_default_akg('popup_max_height', $atts, 'CT_CSS_SKIP_RULE');

	if ($popup_max_height !== 'CT_CSS_SKIP_RULE') {
		blocksy_output_responsive([
			'css' => $css,
			'tablet_css' => $tablet_css,
			'mobile_css' => $mobile_css,
			'selector' => blocksy_prefix_selector('', 'block:' . $id),
			'variableName' => 'popup-max-height',
			'unit' => '',
			'value' => $popup_max_height,
		]);

		$css->put(
			blocksy_prefix_selector('', 'block:' . $id),
			'--popup-height: 100%'
		);
	}
}

$popup_entrance_value = blocksy_default_akg('popup_entrance_value', $atts, 50);

if ($popup_entrance_value !== 50) {
	$css->put(
		blocksy_prefix_selector('', 'block:' . $id),
		'--popup-entrance-value: ' . $popup_entrance_value . 'px'
	);
}

$popup_entrance_speed = blocksy_default_akg('popup_entrance_speed', $atts, 0.3);

if ($popup_entrance_speed !== 0.3) {
	$css->put(
		blocksy_prefix_selector('', 'block:' . $id),
		'--popup-entrance-speed: ' . $popup_entrance_speed . 's'
	);
}

blocksy_output_spacing([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => blocksy_prefix_selector('', 'block:' . $id),
	'property' => 'popup-padding',
	'value' => blocksy_default_akg(
		'popup_padding',
		$atts,
		blocksy_spacing_value()
	),
	'empty_value' => 30
]);

blocksy_output_spacing([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => blocksy_prefix_selector('', 'block:' . $id),
	'property' => 'popup-border-radius',
	'value' => blocksy_default_akg(
		'popup_border_radius',
		$atts,
		blocksy_spacing_value()
	),
	'empty_value' => 7
]);

blocksy_output_box_shadow([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => blocksy_prefix_selector('', 'block:' . $id),
	'value' => blocksy_default_akg('popup_shadow', $atts, blocksy_box_shadow_value([
		'enable' => true,
		'h_offset' => 0,
		'v_offset' => 10,
		'blur' => 20,
		'spread' => 0,
		'inset' => false,
		'color' => [
			'color' => 'rgba(41, 51, 61, 0.1)',
		],
	])),
	'variableName' => 'popup-box-shadow',
	'responsive' => true
]);

$popup_edges_offset = blocksy_default_akg('popup_edges_offset', $atts, 25);

if ($popup_edges_offset !== 25) {
	blocksy_output_responsive([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => blocksy_prefix_selector('', 'block:' . $id),
		'variableName' => 'popup-edges-offset',
		'value' => $popup_edges_offset
	]);
}

blocksy_output_colors([
	'value' => blocksy_akg('popup_close_button_color', $atts),
	'default' => [
		'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		'hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
	],
	'css' => $css,

	'variables' => [
		'default' => [
			'selector' => blocksy_prefix_selector(' .ct-toggle-close', 'block:' . $id),
			'variable' => 'theme-icon-color'
		],

		'hover' => [
			'selector' => blocksy_prefix_selector(' .ct-toggle-close:hover', 'block:' . $id),
			'variable' => 'theme-icon-color'
		]
	],
]);

blocksy_output_colors([
	'value' => blocksy_akg('popup_close_button_shape_color', $atts),
	'default' => [
		'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		'hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
	],
	'css' => $css,

	'variables' => [
		'default' => [
			'selector' => blocksy_prefix_selector(' .ct-toggle-close', 'block:' . $id),
			'variable' => 'toggle-button-background'
		],

		'hover' => [
			'selector' => blocksy_prefix_selector(' .ct-toggle-close:hover', 'block:' . $id),
			'variable' => 'toggle-button-background'
		]
	],
]);

blocksy_output_background_css([
	'selector' => blocksy_prefix_selector(' .ct-popup-inner > article', 'block:' . $id),
	'css' => $css,
	'value' => blocksy_default_akg(
		'popup_background',
		$atts,
		blocksy_background_default_value([
			'backgroundColor' => [
				'default' => [
					'color' => '#ffffff'
				],
			],
		])
	)
]);

blocksy_output_background_css([
	'selector' => blocksy_prefix_selector('', 'block:' . $id),
	'css' => $css,
	'value' => blocksy_default_akg('popup_backdrop_background', $atts,
	blocksy_background_default_value([
		'backgroundColor' => [
			'default' => [
				'color' => 'CT_CSS_SKIP_RULE'
			],
		],
	])
	)
]);
