<?php

if (! function_exists('blocksy_assemble_selector')) {
	return;
}

$has_mega_menu = blocksy_akg('has_mega_menu', $atts, 'no' );
$mega_menu_width = blocksy_akg('mega_menu_width', $atts, 'content' );

blocksy_output_colors([
	'value' => blocksy_akg('menu_items_text', $atts),
	'default' => [
		'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
	],
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'important' => true,

	'variables' => [
		'default' => [
			'selector' => blocksy_assemble_selector(
				blocksy_mutate_selector([
					'selector' => $root_selector,
					'operation' => 'container-suffix',
					'to_add' => '[class*="ct-mega-menu"] .sub-menu'
				])
			),
			'variable' => 'theme-text-color'
		],
	],
]);

// column
blocksy_output_spacing([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => blocksy_assemble_selector(
		blocksy_mutate_selector([
			'selector' => $root_selector,
			'operation' => 'prefix',
			'to_add' => '.sub-menu'
		])
	),
	'property' => 'columns-padding',
	'value' => blocksy_default_akg(
		'menu_column_padding',
		$atts,
		blocksy_spacing_value()
	)
]);

$parent_mega_menu_width = blocksy_akg(
	'mega_menu_width',
	$parent_atts,
	'content'
);
if ($parent_mega_menu_width !== 'full_width') {
	blocksy_output_background_css([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => blocksy_assemble_selector(
			blocksy_mutate_selector([
				'selector' => $root_selector,
				'operation' => 'prefix',
				'to_add' => '.sub-menu'
			])
		),
		'value' => blocksy_akg(
			'mega_menu_column_background',
			$atts,
			blocksy_background_default_value([
				'backgroundColor' => [
					'default' => [
						'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT')
					],
				],
			])
		)
	]);
}

blocksy_output_colors([
	'value' => blocksy_akg('menu_items_links', $atts),
	'default' => [
		'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		'hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		'bg_hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
	],
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	// 'important' => true,
	'variables' => [
		'default' => [
			'selector' => blocksy_assemble_selector(
				blocksy_mutate_selector([
					'selector' => blocksy_mutate_selector([
						'selector' => $root_selector,
						'operation' => 'container-suffix',
						'to_add' => '[class*="ct-mega-menu"] .sub-menu .ct-menu-link'
					]),
					'operation' => 'prefix',
					'to_add' => '.menu'
				])
			),
			'variable' => 'theme-link-initial-color'
		],

		'hover' => [
			'selector' => blocksy_assemble_selector(
				blocksy_mutate_selector([
					'selector' => blocksy_mutate_selector([
						'selector' => $root_selector,
						'operation' => 'container-suffix',
						'to_add' => '[class*="ct-mega-menu"] .sub-menu .ct-menu-link'
					]),
					'operation' => 'prefix',
					'to_add' => '.menu'
				])
			),
			'variable' => 'theme-link-hover-color'
		],

		'bg_hover' => [
			'selector' => blocksy_assemble_selector(
				blocksy_mutate_selector([
					'selector' => blocksy_mutate_selector([
						'selector' => $root_selector,
						'operation' => 'container-suffix',
						'to_add' => '[class*="ct-mega-menu"] .sub-menu .ct-menu-link'
					]),
					'operation' => 'prefix',
					'to_add' => '.menu'
				])
			),
			'variable' => 'dropdown-background-hover-color'
		],
	],
]);

// heading
if($has_heading_childs) {
	blocksy_output_font_css([
		'font_value' => blocksy_akg(
			'menu_items_heading_font',
			$atts,
			blocksy_typography_default_values([
				'size' => '15px',
				'variation' => 'n7',
			])
		),
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => blocksy_assemble_selector(
			blocksy_mutate_selector([
				'selector' => blocksy_mutate_selector([
					'selector' => $root_selector,
					'operation' => 'container-suffix',
					'to_add' => '[class*="ct-mega-menu"] .sub-menu .ct-column-heading'
				]),
				'operation' => 'prefix',
				'to_add' => '.menu'
			])
		),
	]);

	blocksy_output_colors([
		'value' => blocksy_akg('menu_items_heading', $atts),
		'default' => [
			'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		],
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		// 'important' => true,
		'variables' => [
			'default' => [
				'selector' => blocksy_assemble_selector(
					blocksy_mutate_selector([
						'selector' => blocksy_mutate_selector([
							'selector' => $root_selector,
							'operation' => 'container-suffix',
							'to_add' => '[class*="ct-mega-menu"] .sub-menu .ct-column-heading'
						]),
						'operation' => 'prefix',
						'to_add' => '.menu'
					])
				),
				'variable' => 'theme-link-initial-color'
			],
		],
	]);
}

if (isset($parent) && $parent) {
	blocksy_output_colors([
		'value' => blocksy_akg('menu_item_heading', $atts),
		'default' => [
			'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		],
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'important' => true,

		'variables' => [
			'default' => [
				'selector' => blocksy_assemble_selector(
					blocksy_mutate_selector([
						'selector' => $root_selector,
						'operation' => 'suffix',
						'to_add' => '> .ct-column-heading'
					])
				),
				'variable' => 'theme-link-initial-color'
			],
		],
	]);
}

$mega_menu_background_selector = '[class*="ct-mega-menu"] > .sub-menu';

if ($mega_menu_width === 'full_width') {
	$mega_menu_background_selector = '[class*="ct-mega-menu"] > .sub-menu:after';
}

blocksy_output_background_css([
	'selector' => blocksy_assemble_selector(
		blocksy_mutate_selector([
			'selector' => $root_selector,
			'operation' => 'container-suffix',
			'to_add' => $mega_menu_background_selector
		])
	),
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'value' => blocksy_akg('mega_menu_background', $atts,
		blocksy_background_default_value([
			'backgroundColor' => [
				'default' => [
					'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT')
				],
			],
		])
	)
]);

// shadow

blocksy_output_box_shadow([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => blocksy_assemble_selector(
		blocksy_mutate_selector([
			'selector' => $root_selector,
			'operation' => 'container-suffix',
			'to_add' => '[class*="ct-mega-menu"] > .sub-menu'
		])
	),
	'should_skip_output' => false,
	'important' => true,
	'value' => blocksy_akg(
		'mega_menu_shadow',
		$atts,
		blocksy_box_shadow_value([
			'inherit' => true,
			'enable' => false,
			'h_offset' => 0,
			'v_offset' => 10,
			'blur' => 20,
			'spread' => 0,
			'inset' => false,
			'color' => [
				'color' => 'rgba(41, 51, 61, 0.1)',
			],
		])
	)
]);

// divider
$mega_menu_columns_divider_default = [
	'inherit' => true,
	'width' => 1,
	'style' => 'dashed',
	'color' => [
		'color' => 'rgba(255, 255, 255, 0.1)',
	]
];

$mega_menu_items_divider = blocksy_akg(
	'mega_menu_items_divider',
	$atts,
	$mega_menu_columns_divider_default
);

blocksy_output_border([
	'css' => $css,
	'selector' => blocksy_assemble_selector(
		blocksy_mutate_selector([
			'selector' => blocksy_mutate_selector([
				'selector' => $root_selector,
				'operation' => 'container-suffix',
				'to_add' => '[class*="ct-mega-menu"] .sub-menu'
			]),
			'operation' => 'prefix',
			'to_add' => 'nav > ul >'
		])
	),
	'variableName' => 'dropdown-divider',
	'important' => true,
	'value' => $mega_menu_items_divider,
	'default' => $mega_menu_columns_divider_default
]);

if ($mega_menu_items_divider['style'] === 'none') {
	$css->put(
		blocksy_assemble_selector(
			blocksy_mutate_selector([
				'selector' => blocksy_mutate_selector([
					'selector' => $root_selector,
					'operation' => 'container-suffix',
					'to_add' => '[class*="ct-mega-menu"] .sub-menu'
				]),
				'operation' => 'prefix',
				'to_add' => 'nav > ul >'
			])
		),
		'--dropdown-divider-margin: 0px'
	);
} else {
	if (! $mega_menu_items_divider['inherit']) {
		$css->put(
			blocksy_assemble_selector(
				blocksy_mutate_selector([
					'selector' => blocksy_mutate_selector([
						'selector' => $root_selector,
						'operation' => 'container-suffix',
						'to_add' => '[class*="ct-mega-menu"] .sub-menu'
					]),
					'operation' => 'prefix',
					'to_add' => 'nav > ul >'
				])
			),
			'--dropdown-divider-margin: calc(var(--dropdown-items-spacing, 13px) - 3px)'
		);
	}
}


if ($has_mega_menu !== 'no') {
	blocksy_output_border([
		'css' => $css,
		'selector' => blocksy_assemble_selector(
			blocksy_mutate_selector([
				'selector' => blocksy_mutate_selector([
					'selector' => $root_selector,
					'operation' => 'container-suffix',
					'to_add' => '[class*="ct-mega-menu"] > .sub-menu'
				]),
				'operation' => 'prefix',
				'to_add' => 'nav > ul >'
			])
		),
		'variableName' => 'dropdown-columns-divider',
		'value' => blocksy_akg('mega_menu_columns_divider', $atts),
		'default' => [
			// 'inherit' => true,
			'width' => 1,
			'style' => 'solid',
			'color' => [
				'color' => 'rgba(255, 255, 255, 0.1)',
			],
		]
	]);
}

// icon
$menu_item_icon_size = blocksy_akg( 'menu_item_icon_size', $atts, 15 );

if ($menu_item_icon_size !== 15) {
	blocksy_output_responsive([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => blocksy_assemble_selector(
			blocksy_mutate_selector([
				'selector' => $root_selector,
				'operation' => 'suffix',
				'to_add' => '.ct-menu-link .ct-icon-container'
			])
		),
		'variableName' => 'theme-icon-size',
		'value' => $menu_item_icon_size,
	]);
}

blocksy_output_colors([
	'value' => blocksy_akg('menu_item_icon_color', $atts),
	'default' => [
		'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		'hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		'active' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
	],
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,

	'variables' => [
		'default' => [
			'selector' => blocksy_assemble_selector(
				blocksy_mutate_selector([
					'selector' => $root_selector,
					'operation' => 'suffix',
					'to_add' => '> .ct-menu-link .ct-icon-container'
				])
			),
			'variable' => 'theme-icon-color'
		],

		'hover' => [
			'selector' => blocksy_assemble_selector(
				blocksy_mutate_selector([
					'selector' => $root_selector,
					'operation' => 'suffix',
					'to_add' => '> .ct-menu-link .ct-icon-container'
				])
			),
			'variable' => 'theme-icon-hover-color'
		],

		'active' => [
			'selector' => blocksy_assemble_selector(
				blocksy_mutate_selector([
					'selector' => $root_selector,
					'operation' => 'suffix',
					'to_add' => '> .ct-menu-link .ct-icon-container'
				])
			),
			'variable' => 'theme-icon-active-color'
		],
	],
	// 'important' => true,
]);


// badge
$menu_badge_vertical_alignment = blocksy_akg( 'menu_badge_vertical_alignment', $atts, 0 );

if ($menu_badge_vertical_alignment !== 0) {
	$css->put(
		blocksy_assemble_selector(
			blocksy_mutate_selector([
				'selector' => $root_selector,
				'operation' => 'suffix',
				'to_add' => '.ct-menu-badge'
			])
		),
		'--margin-top: ' . $menu_badge_vertical_alignment . 'px'
	);
}

$has_menu_badge = blocksy_akg( 'has_menu_badge', $atts, 'no' );

if ($has_menu_badge !== 'no' ) {
	blocksy_output_colors([
		'value' => blocksy_akg('menu_badge_font_color', $atts),
		'default' => [
			'default' => [ 'color' => '#ffffff' ],
		],
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,

		'variables' => [
			'default' => [
				'selector' => blocksy_assemble_selector(
					blocksy_mutate_selector([
						'selector' => $root_selector,
						'operation' => 'suffix',
						'to_add' => '> *:first-child .ct-menu-badge'
					])
				),
				'variable' => 'theme-text-color'
			],
		],
	]);

	blocksy_output_colors([
		'value' => blocksy_akg('menu_badge_background', $atts),
		'default' => [
			'default' => [ 'color' => 'var(--theme-palette-color-1)' ],
		],
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,

		'variables' => [
			'default' => [
				'selector' => blocksy_assemble_selector(
					blocksy_mutate_selector([
						'selector' => $root_selector,
						'operation' => 'suffix',
						'to_add' => '> *:first-child .ct-menu-badge'
					])
				),
				'variable' => 'background-color'
			],
		],
	]);
}


$mega_menu_columns = blocksy_akg('mega_menu_columns', $atts, '4');

if (
	$has_mega_menu === 'yes'
	&&
	intval($mega_menu_columns) !== 1
) {
	$css->put(
		blocksy_assemble_selector(
			blocksy_mutate_selector([
				'selector' => blocksy_mutate_selector([
					'selector' => $root_selector,
					'operation' => 'container-suffix',
					'to_add' => '[class*="ct-mega-menu"] > .sub-menu'
				]),
				'operation' => 'prefix',
				'to_add' => 'nav > ul >'
			])
		),
		'--grid-template-columns: ' . blocksy_akg(
			$mega_menu_columns . '_columns_layout',
			$atts,
			'repeat(' . $mega_menu_columns . ', 1fr)'
		)
	);
}


// custom width
if ($has_mega_menu !== 'no' && $mega_menu_width === 'custom') {
	$css->put(
		blocksy_assemble_selector(
			blocksy_mutate_selector([
				'selector' => $root_selector,
				'operation' => 'container-suffix',
				'to_add' => '.ct-mega-menu-custom-width .sub-menu'
			])
		),
		'--mega-menu-max-width: ' . blocksy_akg( 'mega_menu_custom_width', $atts, '400px' )
	);
}
