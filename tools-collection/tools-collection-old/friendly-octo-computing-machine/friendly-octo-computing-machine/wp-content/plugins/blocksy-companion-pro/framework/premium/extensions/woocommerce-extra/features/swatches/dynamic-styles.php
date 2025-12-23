<?php

$default_product_layout = blocksy_get_woo_archive_layout_defaults();

$render_layout_config = blc_theme_functions()->blocksy_get_theme_mod(
	'woo_card_layout',
	$default_product_layout
);

if (function_exists('blocksy_normalize_layout')) {
	$render_layout_config = blocksy_normalize_layout(
		$render_layout_config,
		$default_product_layout
	);
}

$has_swatches = false;

foreach ($render_layout_config as $layer) {
	if (! $layer['enabled']) {
		continue;
	}

	if ($layer['id'] === 'product_swatches') {
		$has_swatches = true;
	}
}

if ($has_swatches) {
	$archive_color_swatch_size = blc_theme_functions()->blocksy_get_theme_mod('archive_color_swatch_size', 25);

	blocksy_output_responsive([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => '.ct-card-variation-swatches [data-swatches-type="color"]',
		'variableName' => 'swatch-size',
		'value' => $archive_color_swatch_size
	]);


	$archive_image_swatch_size = blc_theme_functions()->blocksy_get_theme_mod('archive_image_swatch_size', 25);

	blocksy_output_responsive([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => '.ct-card-variation-swatches [data-swatches-type="image"]',
		'variableName' => 'swatch-size',
		'value' => $archive_image_swatch_size
	]);


	$archive_button_swatch_size = blc_theme_functions()->blocksy_get_theme_mod('archive_button_swatch_size', 25);

	blocksy_output_responsive([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => '.ct-card-variation-swatches [data-swatches-type="button"]',
		'variableName' => 'swatch-size',
		'value' => $archive_button_swatch_size
	]);

	$archive_mixed_swatch_size = blc_theme_functions()->blocksy_get_theme_mod('archive_mixed_swatch_size', 25);

	blocksy_output_responsive([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => '.ct-card-variation-swatches [data-swatches-type="mixed"]',
		'variableName' => 'swatch-size',
		'value' => $archive_mixed_swatch_size
	]);
}


$single_color_swatch_size = blc_theme_functions()->blocksy_get_theme_mod('single_color_swatch_size', 30);

blocksy_output_responsive([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => '.variations_form.cart [data-swatches-type="color"]',
	'variableName' => 'swatch-size',
	'value' => $single_color_swatch_size
]);


$filter_widget_color_swatch_size = blc_theme_functions()->blocksy_get_theme_mod('filter_widget_color_swatch_size', 25);

blocksy_output_responsive([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => '.ct-filter-widget[data-swatches-type="color"]',
	'variableName' => 'swatch-size',
	'value' => $filter_widget_color_swatch_size
]);


$single_image_swatch_size = blc_theme_functions()->blocksy_get_theme_mod('single_image_swatch_size', 35);

blocksy_output_responsive([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => '.variations_form.cart [data-swatches-type="image"]',
	'variableName' => 'swatch-size',
	'value' => $single_image_swatch_size
]);


$filter_widget_image_swatch_size = blc_theme_functions()->blocksy_get_theme_mod('filter_widget_image_swatch_size', 35);

blocksy_output_responsive([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => '.ct-filter-widget[data-swatches-type="image"]',
	'variableName' => 'swatch-size',
	'value' => $filter_widget_image_swatch_size
]);


$single_button_swatch_size = blc_theme_functions()->blocksy_get_theme_mod('single_button_swatch_size', 35);

blocksy_output_responsive([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => '.variations_form.cart [data-swatches-type="button"]',
	'variableName' => 'swatch-size',
	'value' => $single_button_swatch_size
]);


$filter_widget_button_swatch_size = blc_theme_functions()->blocksy_get_theme_mod('filter_widget_button_swatch_size', 30);

blocksy_output_responsive([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => '.ct-filter-widget[data-swatches-type="button"]',
	'variableName' => 'swatch-size',
	'value' => $filter_widget_button_swatch_size
]);


$single_mixed_swatch_size = blc_theme_functions()->blocksy_get_theme_mod('single_mixed_swatch_size', 30);

blocksy_output_responsive([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => '.variations_form.cart [data-swatches-type="mixed"]',
	'variableName' => 'swatch-size',
	'value' => $single_mixed_swatch_size
]);


$filter_widget_mixed_swatch_size = blc_theme_functions()->blocksy_get_theme_mod('filter_widget_mixed_swatch_size', 25);

blocksy_output_responsive([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => '.ct-filter-widget[data-swatches-type="mixed"]',
	'variableName' => 'swatch-size',
	'value' => $filter_widget_mixed_swatch_size
]);



blocksy_output_colors([
	'value' => blc_theme_functions()->blocksy_get_theme_mod('color_swatch_border_color'),
	'default' => [
		'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		'hover' => [ 'color' => 'rgba(0, 0, 0, 0.2)' ],
		'active' => [ 'color' => 'rgba(0, 0, 0, 0.2)' ],
	],
	'css' => $css,
	'variables' => [
		'default' => [
			'selector' => '[data-swatches-type="color"] .ct-swatch',
			'variable' => 'swatch-border-color'
		],

		'hover' => [
			'selector' => '[data-swatches-type="color"] > *:not(.ct-out-of-stock):hover .ct-swatch',
			'variable' => 'swatch-border-color'
		],

		'active' => [
			'selector' => '[data-swatches-type="color"] > *.active .ct-swatch',
			'variable' => 'swatch-border-color'
		]
	],
]);


blocksy_output_colors([
	'value' => blc_theme_functions()->blocksy_get_theme_mod('image_swatch_border_color'),
	'default' => [
		'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		'hover' => [ 'color' => 'var(--theme-palette-color-1)' ],
		'active' => [ 'color' => 'var(--theme-palette-color-1)' ],
	],
	'css' => $css,
	'variables' => [
		'default' => [
			'selector' => '[data-swatches-type="image"] .ct-swatch',
			'variable' => 'swatch-border-color'
		],

		'hover' => [
			'selector' => '[data-swatches-type="image"] > *:not(.ct-out-of-stock):hover .ct-swatch',
			'variable' => 'swatch-border-color'
		],

		'active' => [
			'selector' => '[data-swatches-type="image"] > *.active .ct-swatch',
			'variable' => 'swatch-border-color'
		]
	],
]);


blocksy_output_colors([
	'value' => blc_theme_functions()->blocksy_get_theme_mod('button_swatch_text_color'),
	'default' => [
		'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		'hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		'active' => [ 'color' => '#ffffff' ],
	],
	'css' => $css,
	'variables' => [
		'default' => [
			'selector' => '[data-swatches-type="button"] .ct-swatch',
			'variable' => 'swatch-button-text-color'
		],

		'hover' => [
			'selector' => '[data-swatches-type="button"] > *:not(.ct-out-of-stock):hover .ct-swatch',
			'variable' => 'swatch-button-text-color'
		],

		'active' => [
			'selector' => '[data-swatches-type="button"] > *.active .ct-swatch',
			'variable' => 'swatch-button-text-color'
		]
	],
]);


blocksy_output_colors([
	'value' => blc_theme_functions()->blocksy_get_theme_mod('button_swatch_border_color'),
	'default' => [
		'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		'hover' => [ 'color' => 'var(--theme-palette-color-1)' ],
		'active' => [ 'color' => 'var(--theme-palette-color-1)' ],
	],
	'css' => $css,
	'variables' => [
		'default' => [
			'selector' => '[data-swatches-type="button"] .ct-swatch',
			'variable' => 'swatch-button-border-color'
		],

		'hover' => [
			'selector' => '[data-swatches-type="button"] > *:not(.ct-out-of-stock):hover .ct-swatch',
			'variable' => 'swatch-button-border-color'
		],

		'active' => [
			'selector' => '[data-swatches-type="button"] > *.active .ct-swatch',
			'variable' => 'swatch-button-border-color'
		]
	],
]);


blocksy_output_colors([
	'value' => blc_theme_functions()->blocksy_get_theme_mod('button_swatch_background_color'),
	'default' => [
		'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		'hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		'active' => [ 'color' => 'var(--theme-palette-color-1)' ],
	],
	'css' => $css,
	'variables' => [
		'default' => [
			'selector' => '[data-swatches-type="button"] .ct-swatch',
			'variable' => 'swatch-button-background-color'
		],

		'hover' => [
			'selector' => '[data-swatches-type="button"] > *:hover .ct-swatch',
			'variable' => 'swatch-button-background-color'
		],

		'active' => [
			'selector' => '[data-swatches-type="button"] > *.active .ct-swatch',
			'variable' => 'swatch-button-background-color'
		]
	],
]);


blocksy_output_colors([
	'value' => blc_theme_functions()->blocksy_get_theme_mod('mixed_swatch_border_color'),
	'default' => [
		'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		'hover' => [ 'color' => 'rgba(0, 0, 0, 0.2)' ],
		'active' => [ 'color' => 'rgba(0, 0, 0, 0.2)' ],
	],
	'css' => $css,
	'variables' => [
		'default' => [
			'selector' => '[data-swatches-type="mixed"] .ct-swatch',
			'variable' => 'swatch-border-color'
		],

		'hover' => [
			'selector' => '[data-swatches-type="mixed"] > *:hover .ct-swatch',
			'variable' => 'swatch-border-color'
		],

		'active' => [
			'selector' => '[data-swatches-type="mixed"] > *.active .ct-swatch',
			'variable' => 'swatch-border-color'
		]
	],
]);