<?php

blocksy_output_colors([
	'value' => blc_theme_functions()->blocksy_get_theme_mod('product_share_items_icon_color', []),
	'default' => [
		'default' => [ 'color' => 'var(--theme-text-color)' ],
		'hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
	],
	'css' => $css,
	'variables' => [
		'default' => [
			'selector' => blocksy_prefix_selector('.ct-share-box', 'product'),
			'variable' => 'theme-icon-color'
		],
		'hover' => [
			'selector' => blocksy_prefix_selector('.ct-share-box', 'product'),
			'variable' => 'theme-icon-hover-color'
		],
	],
]);