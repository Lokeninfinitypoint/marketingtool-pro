<?php

if (
	! function_exists('blocksy_assemble_selector')
	||
	! blc_theme_functions()->blocksy_manager()
	||
	! isset(blc_theme_functions()->blocksy_manager()->colors)
) {
	return;
}

$palette = blc_theme_functions()->blocksy_manager()->colors->get_color_palette([
	'id' => 'darkColorPalette',
	'default' => [
		'color1' => [
			'color' => '#006466',
		],

		'color2' => [
			'color' => '#065A60',
		],

		'color3' => [
			'color' => '#7F8C9A',
		],

		'color4' => [
			'color' => '#ffffff',
		],

		'color5' => [
			'color' => '#0E141B',
		],

		'color6' => [
			'color' => '#141b22',
		],

		'color7' => [
			'color' => '#1B242C',
		],

		'color8' => [
			'color' => '#1B242C',
		],
	]
]);

$paletteDefaults = [];
$paletteVariables = [];

foreach ($palette as $paletteKey => $paletteValue) {
	$paletteDefaults[$paletteKey] = [
		'color' => $paletteValue['color'],
	];

	$paletteVariables[$paletteKey] = [
		'variable' => $paletteValue['variable'],
		'selector' => ':root[data-color-mode*="dark"]'
	];
}

blocksy_output_colors([
	'value' => $palette,
	'default' => $paletteDefaults,
	'css' => $css,
	'variables' => $paletteVariables
]);

$default_color_mode = blocksy_akg(
	'default_color_mode',
	$atts,
	'light'
);

if ($default_color_mode === 'system' || is_customize_preview()) {
	$paletteDefaults = [];
	$paletteVariables = [];

	foreach ($palette as $paletteKey => $paletteValue) {
		$paletteDefaults[$paletteKey] = [
			'color' => $paletteValue['color'],
		];

		$paletteVariables[$paletteKey] = [
			'variable' => $paletteValue['variable'],
			'selector' => ':root[data-color-mode*="os-default"]'
		];
	}

	$os_aware_css = new Blocksy_Css_Injector();

	blocksy_output_colors([
		'value' => $palette,
		'default' => $paletteDefaults,
		'css' => $os_aware_css,
		'variables' => $paletteVariables
	]);

	$css->put(
		\Blocksy_Css_Injector::get_inline_keyword(),
		'@media (prefers-color-scheme: dark) { ' . trim($os_aware_css->build_css_structure()) . '}'
	);
}

$icon_size = blocksy_akg('icon_size', $atts, 15);

if ($icon_size !== 15) {
	blocksy_output_responsive([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => blocksy_assemble_selector($root_selector),
		'variableName' => 'theme-icon-size',
		'value' => $icon_size
	]);
}


blocksy_output_font_css([
	'font_value' => blocksy_akg( 'color_switch_label_font', $atts,
		blocksy_typography_default_values([
			'size' => '12px',
			'variation' => 'n6',
			'text-transform' => 'uppercase',
		])
	),
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => blocksy_assemble_selector(
		blocksy_mutate_selector([
			'selector' => [$root_selector[0]],
			'operation' => 'suffix',
			'to_add' => '.ct-color-switch .ct-label'
		])
	),
]);


blocksy_output_colors([
	'value' => blocksy_akg('header_color_switch_font_color', $atts),
	'default' => [
		'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		'hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
	],
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'variables' => [
		'default' => [
			'selector' => blocksy_assemble_selector($root_selector),
			'variable' => 'theme-link-initial-color'
		],

		'hover' => [
			'selector' => blocksy_assemble_selector($root_selector),
			'variable' => 'theme-link-hover-color'
		],
	],
	'responsive' => true
]);


blocksy_output_colors([
	'value' => blocksy_akg('header_color_switch_icon_color', $atts),
	'default' => [
		'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		'hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
	],
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'variables' => [
		'default' => [
			'selector' => blocksy_assemble_selector($root_selector),
			'variable' => 'theme-icon-color'
		],

		'hover' => [
			'selector' => blocksy_assemble_selector($root_selector),
			'variable' => 'theme-icon-hover-color'
		],
	],
	'responsive' => true
]);


// transparent state
if (isset($has_transparent_header) && $has_transparent_header) {
	blocksy_output_colors([
		'value' => blocksy_akg('transparent_header_color_switch_font_color', $atts),
		'default' => [
			'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
			'hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		],
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,

		'variables' => [
			'default' => [
				'selector' => blocksy_assemble_selector(blocksy_mutate_selector([
					'selector' => $root_selector,
					'operation' => 'between',
					'to_add' => '[data-transparent-row="yes"]'
				])),
				'variable' => 'theme-link-initial-color'
			],

			'hover' => [
				'selector' => blocksy_assemble_selector(blocksy_mutate_selector([
					'selector' => $root_selector,
					'operation' => 'between',
					'to_add' => '[data-transparent-row="yes"]'
				])),
				'variable' => 'theme-link-hover-color'
			],
		],
		'responsive' => true
	]);

	blocksy_output_colors([
		'value' => blocksy_akg('transparent_header_color_switch_icon_color', $atts),
		'default' => [
			'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
			'hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		],
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,

		'variables' => [
			'default' => [
				'selector' => blocksy_assemble_selector(blocksy_mutate_selector([
					'selector' => $root_selector,
					'operation' => 'between',
					'to_add' => '[data-transparent-row="yes"]'
				])),
				'variable' => 'theme-icon-color'
			],

			'hover' => [
				'selector' => blocksy_assemble_selector(blocksy_mutate_selector([
					'selector' => $root_selector,
					'operation' => 'between',
					'to_add' => '[data-transparent-row="yes"]'
				])),
				'variable' => 'theme-icon-hover-color'
			],
		],
		'responsive' => true
	]);
}


// sticky state
if (isset($has_sticky_header) && $has_sticky_header) {
	blocksy_output_colors([
		'value' => blocksy_akg('sticky_header_color_switch_font_color', $atts),
		'default' => [
			'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
			'hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		],
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,

		'variables' => [
			'default' => [
				'selector' => blocksy_assemble_selector(blocksy_mutate_selector([
					'selector' => $root_selector,
					'operation' => 'between',
					'to_add' => '[data-sticky*="yes"]'
				])),
				'variable' => 'theme-link-initial-color'
			],

			'hover' => [
				'selector' => blocksy_assemble_selector(blocksy_mutate_selector([
					'selector' => $root_selector,
					'operation' => 'between',
					'to_add' => '[data-sticky*="yes"]'
				])),
				'variable' => 'theme-link-hover-color'
			],
		],
		'responsive' => true
	]);

	blocksy_output_colors([
		'value' => blocksy_akg('sticky_header_color_switch_icon_color', $atts),
		'default' => [
			'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
			'hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		],
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,

		'variables' => [
			'default' => [
				'selector' => blocksy_assemble_selector(blocksy_mutate_selector([
					'selector' => $root_selector,
					'operation' => 'between',
					'to_add' => '[data-sticky*="yes"]'
				])),
				'variable' => 'theme-icon-color'
			],

			'hover' => [
				'selector' => blocksy_assemble_selector(blocksy_mutate_selector([
					'selector' => $root_selector,
					'operation' => 'between',
					'to_add' => '[data-sticky*="yes"]'
				])),
				'variable' => 'theme-icon-hover-color'
			],
		],
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
		'container_margin',
		$atts,
		blocksy_spacing_value()
	)
]);
