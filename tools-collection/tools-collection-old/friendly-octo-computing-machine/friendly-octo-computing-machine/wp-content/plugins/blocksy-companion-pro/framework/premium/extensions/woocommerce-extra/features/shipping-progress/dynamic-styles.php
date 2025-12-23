<?php

blocksy_output_colors([
	'value' => blc_theme_functions()->blocksy_get_theme_mod('shipping_progress_bar_color'),
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
			'selector' => '[class*="ct-shipping-progress"]',
			'variable' => 'product-progress-bar-initial-color'
		],
		'active' => [
			'selector' => '[class*="ct-shipping-progress"]',
			'variable' => 'product-progress-bar-active-color'
		],

		'active_2' => [
			'selector' => '[class*="ct-shipping-progress"]',
			'variable' => 'product-progress-bar-active-color-2'
		],
	],
]);