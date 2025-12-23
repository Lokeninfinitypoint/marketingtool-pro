<?php

// filter canvas
blocksy_output_font_css([
	'font_value' => blc_theme_functions()->blocksy_get_theme_mod( 'filter_panel_widgets_font',
		blocksy_typography_default_values([
			// 'size' => '18px',
		])
	),
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => '#woo-filters-panel .ct-widget > *:not(.widget-title)',
]);


blocksy_output_colors([
	'value' => blc_theme_functions()->blocksy_get_theme_mod('filter_panel_widgets_font_color'),
	'default' => [
		'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		'link_initial' => [ 'color' => 'var(--theme-text-color)' ],
		'link_hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
	],
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'variables' => [
		'default' => [
			'selector' => '#woo-filters-panel .ct-sidebar > *',
			'variable' => 'theme-text-color'
		],

		'link_initial' => [
			'selector' => '#woo-filters-panel .ct-sidebar',
			'variable' => 'theme-link-initial-color'
		],

		'link_hover' => [
			'selector' => '#woo-filters-panel .ct-sidebar',
			'variable' => 'theme-link-hover-color'
		],
	],
	'responsive' => true
]);


$vertical_alignment = blc_theme_functions()->blocksy_get_theme_mod( 'filter_panel_content_vertical_alignment', 'flex-start' );

if ($vertical_alignment !== 'flex-start') {
	blocksy_output_responsive([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => '#woo-filters-panel',
		'variableName' => 'vertical-alignment',
		'unit' => '',
		'value' => $vertical_alignment,
	]);
}

$woocommerce_filter_type = blc_theme_functions()->blocksy_get_theme_mod( 'woocommerce_filter_type', 'type-1' );

if ($woocommerce_filter_type === 'type-1') {
	blocksy_output_responsive([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => '#woo-filters-panel[data-behaviour*="side"]',
		'variableName' => 'side-panel-width',
		'responsive' => true,
		'unit' => '',
		'value' => blc_theme_functions()->blocksy_get_theme_mod('filter_panel_width', [
			'desktop' => '500px',
			'tablet' => '65vw',
			'mobile' => '90vw',
		])
	]);


	blocksy_output_background_css([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'responsive' => true,
		'selector' => '#woo-filters-panel[data-behaviour*="side"] .ct-panel-inner',
		'value' => blc_theme_functions()->blocksy_get_theme_mod('filter_panel_background',
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
		'selector' => '#woo-filters-panel[data-behaviour*="side"]',
		'value' => blc_theme_functions()->blocksy_get_theme_mod('filter_panel_backgrop',
			blocksy_background_default_value([
				'backgroundColor' => [
					'default' => [
						'color' => 'rgba(18, 21, 25, 0.6)'
					],
				],
			])
		)
	]);


	$close_button_type = blc_theme_functions()->blocksy_get_theme_mod('filter_panel_close_button_type', 'type-1');

	blocksy_output_colors([
		'value' => blc_theme_functions()->blocksy_get_theme_mod('filter_panel_close_button_color'),
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
				'selector' => '#woo-filters-panel .ct-toggle-close',
				'variable' => 'theme-icon-color'
			],

			'hover' => [
				'selector' => '#woo-filters-panel .ct-toggle-close:hover',
				'variable' => 'theme-icon-color'
			]
		],
	]);


	if ($close_button_type === 'type-2') {
		blocksy_output_colors([
			'value' => blc_theme_functions()->blocksy_get_theme_mod('filter_panel_close_button_border_color'),
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
					'selector' => '#woo-filters-panel .ct-toggle-close[data-type="type-2"]',
					'variable' => 'toggle-button-border-color'
				],

				'hover' => [
					'selector' => '#woo-filters-panel .ct-toggle-close[data-type="type-2"]:hover',
					'variable' => 'toggle-button-border-color'
				]
			],
		]);
	}


	if ($close_button_type === 'type-3') {
		blocksy_output_colors([
			'value' => blc_theme_functions()->blocksy_get_theme_mod('filter_panel_close_button_shape_color'),
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
					'selector' => '#woo-filters-panel .ct-toggle-close[data-type="type-3"]',
					'variable' => 'toggle-button-background'
				],

				'hover' => [
					'selector' => '#woo-filters-panel .ct-toggle-close[data-type="type-3"]:hover',
					'variable' => 'toggle-button-background'
				]
			],
		]);
	}


	if ($close_button_type !== 'type-1') {
		$filter_panel_close_button_border_radius = blc_theme_functions()->blocksy_get_theme_mod( 'filter_panel_close_button_border_radius', 5 );

		if ($filter_panel_close_button_border_radius !== 5) {
			$css->put(
				'#woo-filters-panel .ct-toggle-close',
				'--toggle-button-radius: ' . $filter_panel_close_button_border_radius . 'px'
			);
		}
	}


	$filter_panel_close_button_icon_size = blc_theme_functions()->blocksy_get_theme_mod( 'filter_panel_close_button_icon_size', 12 );

	if ($filter_panel_close_button_icon_size !== 12) {
		$css->put(
			'#woo-filters-panel .ct-toggle-close',
			'--theme-icon-size: ' . $filter_panel_close_button_icon_size . 'px'
		);
	}


	blocksy_output_box_shadow([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => '#woo-filters-panel[data-behaviour*="side"]',
		'value' => blc_theme_functions()->blocksy_get_theme_mod('filter_panel_shadow', blocksy_box_shadow_value([
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

	$panel_widgets_spacing = blc_theme_functions()->blocksy_get_theme_mod( 'panel_widgets_spacing', 60 );

	if ($panel_widgets_spacing !== 60) {
		blocksy_output_responsive([
			'css' => $css,
			'tablet_css' => $tablet_css,
			'mobile_css' => $mobile_css,
			'selector' => '#woo-filters-panel .ct-sidebar',
			'variableName' => 'sidebar-widgets-spacing',
			'value' => $panel_widgets_spacing,
		]);
	}
}


// filter type - drop-down
if ($woocommerce_filter_type === 'type-2') {
	$filter_panel_height_type = blc_theme_functions()->blocksy_get_theme_mod( 'filter_panel_height_type', 'auto' );

	if ($filter_panel_height_type === 'custom') {
		blocksy_output_responsive([
			'css' => $css,
			'tablet_css' => $tablet_css,
			'mobile_css' => $mobile_css,
			'selector' => '#woo-filters-panel[data-behaviour="drop-down"]',
			'variableName' => 'filter-panel-height',
			'responsive' => true,
			'unit' => '',
			'value' => blc_theme_functions()->blocksy_get_theme_mod('filter_panel_height', [
				'desktop' => '250px',
				'tablet' => '250px',
				'mobile' => '250px',
			])
		]);
	}

	$filter_panel_columns = blocksy_expand_responsive_value(blc_theme_functions()->blocksy_get_theme_mod(
		'filter_panel_columns',
		[
			'desktop' => 4,
			'tablet' => 2,
			'mobile' => 1
		]
	));

	$columns_for_output = [
		'desktop' => 'repeat(' . $filter_panel_columns['desktop'] . ', 1fr)',
		'tablet' => 'repeat(' . $filter_panel_columns['tablet'] . ', 1fr)',
		'mobile' => 'repeat(' . $filter_panel_columns['mobile'] . ', 1fr)'
	];

	blocksy_output_responsive([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => '#woo-filters-panel[data-behaviour="drop-down"]',
		'variableName' => 'grid-template-columns',
		'value' => $columns_for_output,
		'unit' => ''
	]);
}