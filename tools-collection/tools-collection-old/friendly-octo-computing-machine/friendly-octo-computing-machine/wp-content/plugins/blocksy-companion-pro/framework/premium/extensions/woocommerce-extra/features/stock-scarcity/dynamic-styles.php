<?php

blocksy_output_responsive([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => '.ct-product-stock-scarcity',
	'variableName' => 'product-progress-bar-height',
	'value' => blc_theme_functions()->blocksy_get_theme_mod('stock_scarcity_bar_height', 5),
	'unit' => 'px'
]);

blocksy_output_colors([
	'value' => blc_theme_functions()->blocksy_get_theme_mod('stock_scarcity_bar_color'),
	'default' => [
		'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		'active' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		'active_2' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
	],
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	// 'responsive' => true,
	'variables' => [
		'default' => [
			'selector' => '.ct-product-stock-scarcity',
			'variable' => 'product-progress-bar-initial-color'
		],
		'active' => [
			'selector' => '.ct-product-stock-scarcity',
			'variable' => 'product-progress-bar-active-color'
		],

		'active_2' => [
			'selector' => '.ct-product-stock-scarcity',
			'variable' => 'product-progress-bar-active-color-2'
		],
	],
]);