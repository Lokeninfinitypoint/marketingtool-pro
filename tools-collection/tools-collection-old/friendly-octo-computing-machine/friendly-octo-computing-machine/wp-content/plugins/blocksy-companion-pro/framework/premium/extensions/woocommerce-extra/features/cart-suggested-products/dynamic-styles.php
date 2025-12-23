<?php

$storage = new \Blocksy\Extensions\WoocommerceExtra\Storage();

$has_added_to_cart_popup = $storage->get_settings()['features']['added-to-cart-popup'];

$selectors = [
	'mini_cart_suggested_' => '.ct-suggested-products--mini-cart',
	'checkout_suggested_' => '.ct-suggested-products--checkout',
	'cart_suggested_' => '.ct-suggested-products--cart',
];

if ($has_added_to_cart_popup) {
	$selectors['cart_popup_suggested_'] = '.ct-suggested-products--cart-popup';
}

$defaults = \Blocksy\Extensions\WoocommerceExtra\CartSuggestedProducts::get_option_defaults();

foreach ($selectors as $prefix => $selector) {
	if (blc_theme_functions()->blocksy_get_theme_mod($prefix . 'products', 'yes') !== 'yes') {
		continue;
	}

	$slideshow_columns = blc_theme_functions()->blocksy_get_theme_mod(
		$prefix . 'products_columns',
		$defaults[$prefix]['products_columns']
	);

	$slideshow_columns = blocksy_expand_responsive_value($slideshow_columns);

	$dynamic_height_selectors = [
		'desktop' => '',
		'tablet' => '',
		'mobile' => ''
	];

	foreach ($dynamic_height_selectors as $device => $_selector) {
		$dynamic_height_selectors[$device] = blocksy_assemble_selector(
			blocksy_mutate_selector([
				'selector' => blocksy_mutate_selector([
					'selector' => [$selector],
					'operation' => 'container-suffix',
					'to_add' => '[data-flexy*="no"]'
				]),
				'operation' => 'suffix',
				'to_add' => '.flexy-item:nth-child(n + ' . (intval($slideshow_columns[$device]) + 1) . ')'
			])
		);
	}

	blocksy_output_responsive([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => $dynamic_height_selectors,
		'variableName' => 'height',
		'variableType' => 'property',
		'value' => '1'
	]);

	$slideshow_columns['desktop'] = 'calc(100% / ' . $slideshow_columns['desktop'] . ')';
	$slideshow_columns['tablet'] = 'calc(100% / ' . $slideshow_columns['tablet'] . ')';
	$slideshow_columns['mobile'] = 'calc(100% / ' . $slideshow_columns['mobile'] . ')';

	blocksy_output_responsive([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => $selector,
		'variableName' => 'grid-columns-width',
		'value' => $slideshow_columns,
		'unit' => ''
	]);

	blocksy_output_responsive([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => $selector,
		'variableName' => 'product-image-width',
		'value' => blc_theme_functions()->blocksy_get_theme_mod($prefix . 'products_image_width', $defaults[$prefix]['products_image_width']),
		'unit' => ''
	]);

	blocksy_output_font_css([
		'font_value' => blc_theme_functions()->blocksy_get_theme_mod(
			$prefix . 'products_title_font',
			blocksy_typography_default_values([
				'size' => '14px',
				'variation' => 'n6',
			])
		),
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => $selector . ' [data-products] .ct-product-title'
	]);

	blocksy_output_colors([
		'value' => blc_theme_functions()->blocksy_get_theme_mod($prefix . 'products_title_color'),
		'default' => [
			'default' => [ 'color' => 'var(--theme-text-color)' ],
			'hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		],
		'css' => $css,
		'variables' => [
			'default' => [
				'selector' => $selector . ' [data-products] .ct-product-title',
				'variable' => 'theme-link-initial-color'
			],

			'hover' => [
				'selector' => $selector . ' [data-products] .ct-product-title',
				'variable' => 'theme-link-hover-color'
			],
		],
	]);

	blocksy_output_font_css([
		'font_value' => blc_theme_functions()->blocksy_get_theme_mod(
			$prefix . 'products_price_font',
			blocksy_typography_default_values([
				'size' => '13px',
			])
		),
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => $selector . ' [data-products] .price'
	]);

	blocksy_output_colors([
		'value' => blc_theme_functions()->blocksy_get_theme_mod($prefix . 'products_price_color'),
		'default' => [
			'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		],
		'css' => $css,
		'variables' => [
			'default' => [
				'selector' => $selector . ' [data-products] .price',
				'variable' => 'theme-text-color'
			],
		],
	]);

	blocksy_output_spacing([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => $selector,
		'property' => 'theme-border-radius',
		'value' => blc_theme_functions()->blocksy_get_theme_mod(
			$prefix . 'products_image_radius',
			blocksy_spacing_value(),
		),
		'empty_value' => 3,
	]);
}