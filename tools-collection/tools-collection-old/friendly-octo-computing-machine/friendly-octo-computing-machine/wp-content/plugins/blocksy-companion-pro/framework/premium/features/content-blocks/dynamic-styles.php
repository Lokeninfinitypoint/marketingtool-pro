<?php

$atts['content_style_source'] = 'custom';
$template_type = get_post_meta($id, 'template_type', true);

$default_content_block_structure = 'yes';

if ($template_type === 'hook') {
	$default_content_block_structure = 'no';
}

if (blocksy_akg(
	'has_content_block_structure',
	$atts,
	$default_content_block_structure
) === 'yes') {
	blocksy_output_background_css([
		'selector' => blocksy_prefix_selector('', 'block:' . $id),
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'value' => blocksy_default_akg(
			'background',
			$atts,
			blocksy_background_default_value([
				'backgroundColor' => [
					'default' => [
						'color' => Blocksy_Css_Injector::get_skip_rule_keyword()
					],
				],
			])
		),
		'responsive' => true,
	]);

	blocksy_theme_get_dynamic_styles([
		'name' => 'global/single-content',
		'css' => $css,
		'mobile_css' => $mobile_css,
		'tablet_css' => $tablet_css,
		'context' => $context,
		'chunk' => $chunk,
		'prefix' => 'block:' . $id,
		'source' => [
			'strategy' => $atts
		],
	]);
}

