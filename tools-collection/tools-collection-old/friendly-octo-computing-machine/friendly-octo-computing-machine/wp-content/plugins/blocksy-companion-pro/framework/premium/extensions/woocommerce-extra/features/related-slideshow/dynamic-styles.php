<?php

$woocommerce_related_products_slideshow = blc_theme_functions()->blocksy_get_theme_mod(
	'woocommerce_related_products_slideshow',
	'default'
);

// related slideshow columns
if ($woocommerce_related_products_slideshow === 'slider') {
	$columns = blocksy_expand_responsive_value(
		blc_theme_functions()->blocksy_get_theme_mod(
			'woocommerce_related_products_slideshow_columns',
			[
				'desktop' => 4,
				'tablet' => 3,
				'mobile' => 1,
			]
		)
	);

	$columns_selectors = [
		'desktop' => '',
		'tablet' => '',
		'mobile' => ''
	];

	foreach ($columns_selectors as $device => $selector) {
		$columns_selectors[$device] = blocksy_assemble_selector(
			blocksy_mutate_selector([
				'selector' => blocksy_mutate_selector([
					'selector' => [':is(.related, .upsells)'],
					'operation' => 'suffix',
					'to_add' => '[data-flexy="no"] [data-products]'
				]),
				'operation' => 'suffix',
				'to_add' => '.flexy-item:nth-child(n + ' . (intval($columns[$device]) + 1) . ')'
			])
		);
	}

	blocksy_output_responsive([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,

		'selector' => $columns_selectors,

		'variableName' => 'height',
		'variableType' => 'property',
		'value' => '1'
	]);

	$columns['desktop'] = 'calc(100% / ' . $columns['desktop'] . ')';
	$columns['tablet'] = 'calc(100% / ' . $columns['tablet'] . ')';
	$columns['mobile'] = 'calc(100% / ' . $columns['mobile'] . ')';

	blocksy_output_responsive([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => '.related [data-products], .upsells [data-products]',
		'variableName' => 'grid-columns-width',
		'value' => $columns,
		'unit' => ''
	]);
}
