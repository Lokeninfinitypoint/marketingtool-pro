<?php

$size_guide_placement = blc_theme_functions()->blocksy_get_theme_mod('size_guide_placement', 'modal');

$size_guide_background_selector = '#ct-size-guide-modal .ct-container';

// modal
if ($size_guide_placement === 'modal') {

	blocksy_output_box_shadow([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => '#ct-size-guide-modal .ct-container',
		'value' => blc_theme_functions()->blocksy_get_theme_mod('size_guide_modal_shadow', blocksy_box_shadow_value([
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
		'responsive' => true
	]);

	blocksy_output_spacing([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => '#ct-size-guide-modal .ct-container',
		'property' => 'theme-border-radius',
		'value' => blc_theme_functions()->blocksy_get_theme_mod( 'size_guide_modal_radius',
			blocksy_spacing_value()
		),
		'empty_value' => 7
	]);
}


// panel
if ($size_guide_placement === 'panel') {

	$size_guide_background_selector = '#ct-size-guide-modal .ct-panel-inner';

	blocksy_output_responsive([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => '#ct-size-guide-modal',
		'variableName' => 'side-panel-width',
		'unit' => '',
		'value' => blc_theme_functions()->blocksy_get_theme_mod('size_guide_side_panel_width', [
			'desktop' => '700px',
			'tablet' => '65vw',
			'mobile' => '90vw',
		])
	]);

	blocksy_output_box_shadow([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => '#ct-size-guide-modal .ct-panel-inner',
		'value' => blc_theme_functions()->blocksy_get_theme_mod('size_guide_panel_shadow', blocksy_box_shadow_value([
			'enable' => true,
			'h_offset' => 0,
			'v_offset' => 0,
			'blur' => 70,
			'spread' => 0,
			'inset' => false,
			'color' => [
				'color' => 'rgba(0, 0, 0, 0.35)',
			],
		])),
		'responsive' => true
	]);
}

blocksy_output_background_css([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'responsive' => true,
	'selector' => $size_guide_background_selector,
	'value' => blc_theme_functions()->blocksy_get_theme_mod('size_guide_modal_background',
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
	'responsive' => true,
	'selector' => '#ct-size-guide-modal',
	'value' => blc_theme_functions()->blocksy_get_theme_mod('size_guide_modal_backdrop',
		blocksy_background_default_value([
			'backgroundColor' => [
				'default' => [
					'color' => 'rgba(18, 21, 25, 0.8)'
				],
			],
		])
	)
]);

// close button
$size_guide_close_button_icon_size = blc_theme_functions()->blocksy_get_theme_mod( 'size_guide_close_button_icon_size', 12 );

if ($size_guide_close_button_icon_size !== 12) {
	$css->put(
		'#ct-size-guide-modal .ct-toggle-close',
		'--theme-icon-size: ' . $size_guide_close_button_icon_size . 'px'
	);
}

$close_button_type = blc_theme_functions()->blocksy_get_theme_mod('size_guide_close_button_type', 'type-1');

blocksy_output_colors([
	'value' => blc_theme_functions()->blocksy_get_theme_mod('size_guide_close_button_color'),
	'default' => [
		'default' => [ 'color' => 'rgba(0, 0, 0, 0.5)' ],
		'hover' => [ 'color' => 'rgba(0, 0, 0, 0.8)' ],
	],
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'responsive' => true,
	'variables' => [
		'default' => [
			'selector' => '#ct-size-guide-modal .ct-toggle-close',
			'variable' => 'theme-icon-color'
		],

		'hover' => [
			'selector' => '#ct-size-guide-modal .ct-toggle-close:hover',
			'variable' => 'theme-icon-color'
		]
	],
]);

if ($close_button_type === 'type-2') {
	blocksy_output_colors([
		'value' => blc_theme_functions()->blocksy_get_theme_mod('size_guide_close_button_border_color'),
		'default' => [
			'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
			'hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		],
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'responsive' => true,
		'variables' => [
			'default' => [
				'selector' => '#ct-size-guide-modal .ct-toggle-close[data-type="type-2"]',
				'variable' => 'toggle-button-border-color'
			],

			'hover' => [
				'selector' => '#ct-size-guide-modal .ct-toggle-close[data-type="type-2"]:hover',
				'variable' => 'toggle-button-border-color'
			]
		],
	]);
}

if ($close_button_type === 'type-3') {
	blocksy_output_colors([
		'value' => blc_theme_functions()->blocksy_get_theme_mod('size_guide_close_button_shape_color'),
		'default' => [
			'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
			'hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		],
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'responsive' => true,
		'variables' => [
			'default' => [
				'selector' => '#ct-size-guide-modal .ct-toggle-close[data-type="type-3"]',
				'variable' => 'toggle-button-background'
			],

			'hover' => [
				'selector' => '#ct-size-guide-modal .ct-toggle-close[data-type="type-3"]:hover',
				'variable' => 'toggle-button-background'
			]
		],
	]);
}

if ($close_button_type !== 'type-1') {
	$size_guide_close_button_border_radius = blc_theme_functions()->blocksy_get_theme_mod( 'size_guide_close_button_border_radius', 5 );

	if ($size_guide_close_button_border_radius !== 5) {
		$css->put(
			'#ct-size-guide-modal .ct-toggle-close',
			'--toggle-button-radius: ' . $size_guide_close_button_border_radius . 'px'
		);
	}
}