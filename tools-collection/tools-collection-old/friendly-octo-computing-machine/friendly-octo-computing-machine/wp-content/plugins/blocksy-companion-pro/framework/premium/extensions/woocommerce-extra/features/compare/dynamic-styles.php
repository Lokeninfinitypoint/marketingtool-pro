<?php


blocksy_output_box_shadow([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => '#ct-compare-modal',
	'value' => blc_theme_functions()->blocksy_get_theme_mod('compare_modal_shadow', blocksy_box_shadow_value([
		'enable' => true,
		'h_offset' => 0,
		'v_offset' => 50,
		'blur' => 100,
		'spread' => 0,
		'inset' => false,
		'color' => [
			'color' => 'rgba(18, 21, 25, 0.5)',
		],
	])),
	'responsive' => true
]);

blocksy_output_spacing([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => '#ct-compare-modal',
	'property' => 'theme-border-radius',
	'value' => blc_theme_functions()->blocksy_get_theme_mod( 'compare_modal_radius',
		blocksy_spacing_value()
	),
	'empty_value' => 7
]);



blocksy_output_colors([
	'value' => blocksy_get_theme_mod('compare_modal_background'),
	'default' => [
		'default' => [ 'color' => 'var(--theme-palette-color-8)' ],
	],
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'variables' => [
		'default' => [
			'selector' => '#ct-compare-modal',
			'variable' => 'modal-background-color'
		],
	],
	'responsive' => true
]);

blocksy_output_colors([
	'value' => blocksy_get_theme_mod('compare_modal_backdrop'),
	'default' => [
		'default' => [ 'color' => 'rgba(18, 21, 25, 0.8)' ],
	],
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'variables' => [
		'default' => [
			'selector' => '#ct-compare-modal',
			'variable' => 'modal-backdrop-color'
		],
	],
	'responsive' => true
]);


// compare table layers
$render_layout_config = blc_theme_functions()->blocksy_get_theme_mod('product_compare_layout', [
	[
		'id' => 'product_main',
		'enabled' => true,
	],
	[
		'id' => 'product_title',
		'enabled' => true,
	],
	[
		'id' => 'product_price',
		'enabled' => true,
	],
	[
		'id' => 'product_description',
		'enabled' => true,
	],
	[
		'id' => 'product_attributes',
		'enabled' => true,
		'product_attributes_source' => 'all',
	],
	[
		'id' => 'product_availability',
		'enabled' => true,
	],
	[
		'id' => 'product_add_to_cart',
		'enabled' => true,
	],
]);

foreach ($render_layout_config as $layer) {
	if (! $layer['enabled'] ) {
		continue;
	}

	$selectors_map = [
		'product_brands' => '.ct-compare-column > .ct-product-brands',
	];

	if ($layer['id'] === 'product_brands') {
		$brand_logo_size = blocksy_akg('brand_logo_size', $layer, 60);

		if ($brand_logo_size !== 60) {
			blocksy_output_responsive([
				'css' => $css,
				'tablet_css' => $tablet_css,
				'mobile_css' => $mobile_css,
				'selector' => $selectors_map[$layer['id']],
				'variableName' => 'product-brand-logo-size',
				'value' => $brand_logo_size,
			]);
		}

		$brand_logo_gap = blocksy_akg('brand_logo_gap', $layer, 10);

		if ($brand_logo_gap !== 10) {
			blocksy_output_responsive([
				'css' => $css,
				'tablet_css' => $tablet_css,
				'mobile_css' => $mobile_css,
				'selector' => $selectors_map[$layer['id']],
				'variableName' => 'product-brands-gap',
				'value' => $brand_logo_gap,
			]);
		}
	}
}


// compare bar
$has_product_compare_bar = blc_theme_functions()->blocksy_get_theme_mod('product_compare_bar', 'no');

if ($has_product_compare_bar === 'yes') {
	$product_compare_bar_height = blocksy_expand_responsive_value(
		blc_theme_functions()->blocksy_get_theme_mod('product_compare_bar_height', 70)
	);

	$product_compare_bar_visibility = blocksy_expand_responsive_value(blc_theme_functions()->blocksy_get_theme_mod(
		'product_compare_bar_visibility',
		[
			'desktop' => true,
			'tablet' => true,
			'mobile' => true,
		]
	));

	if (
		! isset($product_compare_bar_visibility['desktop'])
		||
		! $product_compare_bar_visibility['desktop']
	) {
		$product_compare_bar_height['desktop'] = '0';
	}

	if (
		! isset($product_compare_bar_visibility['tablet'])
		||
		! $product_compare_bar_visibility['desktop']
	) {
		$product_compare_bar_height['tablet'] = '0';
	}

	if (
		! isset($product_compare_bar_visibility['mobile'])
		||
		! $product_compare_bar_visibility['desktop']
	) {
		$product_compare_bar_height['mobile'] = '0';
	}

	blocksy_output_responsive([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => '.ct-drawer-canvas[data-compare-bar]',
		'variableName' => 'compare-bar-height',
		'value' => $product_compare_bar_height
	]);

	blocksy_output_colors([
		'value' => blc_theme_functions()->blocksy_get_theme_mod('product_compare_bar_button_font_color'),
		'default' => [
			'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
			'hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		],
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'variables' => [
			'default' => [
				'selector' => '.ct-compare-bar',
				'variable' => 'theme-button-text-initial-color'
			],

			'hover' => [
				'selector' => '.ct-compare-bar',
				'variable' => 'theme-button-text-hover-color'
			],
		],
		'responsive' => true
	]);

	blocksy_output_colors([
		'value' => blc_theme_functions()->blocksy_get_theme_mod('product_compare_bar_button_background_color'),
		'default' => [
			'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
			'hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		],
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'variables' => [
			'default' => [
				'selector' => '.ct-compare-bar',
				'variable' => 'theme-button-background-initial-color'
			],

			'hover' => [
				'selector' => '.ct-compare-bar',
				'variable' => 'theme-button-background-hover-color'
			],
		],
		'responsive' => true
	]);

	blocksy_output_colors([
		'value' => blocksy_get_theme_mod('product_compare_bar_background'),
		'default' => [
			'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		],
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'variables' => [
			'default' => [
				'selector' => '.ct-compare-bar',
				'variable' => 'compare-bar-background-color'
			],
		],
		'responsive' => true
	]);
}