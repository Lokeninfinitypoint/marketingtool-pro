<?php

$prefix = 'wish_list';

$selector = '.ct-woo-account .ct-share-box';

if (blc_theme_functions()->blocksy_get_theme_mod($prefix . '_has_share_box', 'no') === 'yes') {
	$share_box_icon_size = blc_theme_functions()->blocksy_get_theme_mod($prefix . '_share_box_icon_size', '15px');

	if ($share_box_icon_size !== '15px') {
		blocksy_output_responsive([
			'css' => $css,
			'tablet_css' => $tablet_css,
			'mobile_css' => $mobile_css,
			'selector' => $selector,
			'variableName' => 'theme-icon-size',
			'value' => $share_box_icon_size,
			'unit' => ''
		]);
	}

	$share_box_icons_spacing = blc_theme_functions()->blocksy_get_theme_mod($prefix . '_share_box_icons_spacing', '15px');

	if ($share_box_icons_spacing !== '15px') {
		blocksy_output_responsive([
			'css' => $css,
			'tablet_css' => $tablet_css,
			'mobile_css' => $mobile_css,
			'selector' => $selector,
			'variableName' => 'items-spacing',
			'value' => $share_box_icons_spacing,
			'unit' => ''
		]);
	}


	blocksy_output_colors([
		'value' => blc_theme_functions()->blocksy_get_theme_mod($prefix . '_share_items_icon_color', []),
		'default' => [
			'default' => [ 'color' => 'var(--theme-text-color)' ],
			'hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		],
		'css' => $css,
		'variables' => [
			'default' => [
				'selector' => $selector,
				'variable' => 'theme-icon-color'
			],
			'hover' => [
				'selector' => $selector,
				'variable' => 'theme-icon-hover-color'
			],
		],
	]);
}

