<?php

if (! function_exists('blocksy_assemble_selector')) {
	return;
}

blocksy_output_colors([
	'value' => blocksy_akg('accent_color', $atts),
	'default' => [
		'default' => [
			'color' => Blocksy_Css_Injector::get_skip_rule_keyword()
		],

		'hover' => [
			'color' => Blocksy_Css_Injector::get_skip_rule_keyword()
		],

		'background_initial' => [
			'color' => Blocksy_Css_Injector::get_skip_rule_keyword()
		],

		'background_hover' => [
			'color' => Blocksy_Css_Injector::get_skip_rule_keyword()
		],
	],

	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'important' => true,
	'variables' => [
		'default' => [
			'selector' => blocksy_assemble_selector($root_selector),
			'variable' => 'theme-link-initial-color'
		],

		'hover' => [
			'selector' => blocksy_assemble_selector($root_selector),
			'variable' => 'theme-link-hover-color'
		],

		'background_initial' => [
			'selector' => blocksy_assemble_selector($root_selector),
			'variable' => 'theme-button-background-initial-color'
		],

		'background_hover' => [
			'selector' => blocksy_assemble_selector($root_selector),
			'variable' => 'theme-button-background-hover-color'
		]
	],
]);

