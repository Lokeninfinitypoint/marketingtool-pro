<?php

if (!function_exists('blocksy_assemble_selector')) {
	return;
}

blocksy_output_spacing([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => blocksy_assemble_selector($root_selector),
	'important' => true,
	'value' => blocksy_default_akg(
		'margin',
		$atts,
		blocksy_spacing_value()
	)
]);
