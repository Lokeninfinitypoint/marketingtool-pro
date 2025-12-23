<?php

$image_width = blc_theme_functions()->blocksy_get_theme_mod('added_to_cart_popup_image_width', 20);

if ($image_width !== 20) {
	$css->put(
		'#ct-added-to-cart-popup',
		'--product-image-width: ' . $image_width . '%'
	);
}

blocksy_output_font_css([
	'font_value' => blc_theme_functions()->blocksy_get_theme_mod(
		'added_to_cart_popup_title_font',
		blocksy_typography_default_values([
			'size' => '16px',
		])
	),
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => '.ct-added-to-cart-product .woocommerce-loop-product__title'
]);

blocksy_output_colors([
	'value' => blc_theme_functions()->blocksy_get_theme_mod('added_to_cart_popup_title_color'),
	'default' => [
		'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
	],
	'css' => $css,
	'variables' => [
		'default' => [
			'selector' => '.ct-added-to-cart-product .woocommerce-loop-product__title',
			'variable' => 'theme-heading-color'
		],
	],
]);

blocksy_output_font_css([
	'font_value' => blc_theme_functions()->blocksy_get_theme_mod(
		'added_to_cart_popup_price_font',
		blocksy_typography_default_values([
			'size' => '15px',
			'variation' => 'n7',
		])
	),
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => '.ct-added-to-cart-product .price'
]);

blocksy_output_colors([
	'value' => blc_theme_functions()->blocksy_get_theme_mod('added_to_cart_popup_price_color'),
	'default' => [
		'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
	],
	'css' => $css,
	'variables' => [
		'default' => [
			'selector' => '.ct-added-to-cart-product .price',
			'variable' => 'theme-text-color'
		],
	],
]);

blocksy_output_spacing([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => '.ct-added-to-cart-product .ct-media-container',
	'property' => 'theme-border-radius',
	'value' => blc_theme_functions()->blocksy_get_theme_mod(
		'added_to_cart_popup_image_radius',
		blocksy_spacing_value()
	),
	'empty_value' => 3,
]);

// popup
$popup_size = blc_theme_functions()->blocksy_get_theme_mod('added_to_cart_popup_size', 'large');

if ($popup_size === 'custom') {
	$popup_max_width = blc_theme_functions()->blocksy_get_theme_mod('added_to_cart_popup_max_width', '900px');

	blocksy_output_responsive([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => '#ct-added-to-cart-popup[data-popup-size="custom"]',
		'variableName' => 'popup-max-width',
		'unit' => '',
		'value' => $popup_max_width,
	]);

	$popup_max_height = blc_theme_functions()->blocksy_get_theme_mod('added_to_cart_popup_max_height', '700px');

	blocksy_output_responsive([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => '#ct-added-to-cart-popup[data-popup-size="custom"]',
		'variableName' => 'popup-max-height',
		'unit' => '',
		'value' => $popup_max_height,
	]);

	$css->put(
		'#ct-added-to-cart-popup[data-popup-size="custom"]',
		'--popup-height: 100%'
	);
}

$popup_entrance_speed = blc_theme_functions()->blocksy_get_theme_mod('added_to_cart_popup_entrance_speed', 0.2);

$css->put(
	'#ct-added-to-cart-popup',
	'--popup-entrance-speed: ' . $popup_entrance_speed . 's'
);


$popup_entrance_value = blc_theme_functions()->blocksy_get_theme_mod('added_to_cart_popup_entrance_value', 50);

if ($popup_entrance_value !== 50) {
	$css->put(
		'#ct-added-to-cart-popup',
		'--popup-entrance-value: ' . $popup_entrance_value . 'px'
	);
}

$popup_edges_offset = blc_theme_functions()->blocksy_get_theme_mod('added_to_cart_popup_edges_offset', 25);

if ($popup_edges_offset !== 25) {
	blocksy_output_responsive([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => '#ct-added-to-cart-popup',
		'variableName' => 'popup-edges-offset',
		'value' => $popup_edges_offset,
		'unit' => 'px'
	]);
}

blocksy_output_spacing([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => '#ct-added-to-cart-popup',
	'property' => 'popup-padding',
	'value' => blc_theme_functions()->blocksy_get_theme_mod(
		'added_to_cart_popup_padding',
		blocksy_spacing_value()
	),
	'empty_value' => 30
]);

blocksy_output_spacing([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => '#ct-added-to-cart-popup',
	'property' => 'popup-border-radius',
	'value' => blc_theme_functions()->blocksy_get_theme_mod(
		'added_to_cart_popup_border_radius',
		blocksy_spacing_value()
	),
	'empty_value' => 7
]);

blocksy_output_box_shadow([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => '#ct-added-to-cart-popup',
	'value' => blc_theme_functions()->blocksy_get_theme_mod('added_to_cart_popup_shadow',
		blocksy_box_shadow_value([
			'enable' => true,
			'h_offset' => 0,
			'v_offset' => 10,
			'blur' => 20,
			'spread' => 0,
			'inset' => false,
			'color' => [
				'color' => 'rgba(41, 51, 61, 0.1)',
			],
		])),
	'variableName' => 'popup-box-shadow',
	'responsive' => true
]);

$icon_size = blc_theme_functions()->blocksy_get_theme_mod( 'added_to_cart_popup_close_button_icon_size', 12 );

$css->put(
	'#ct-added-to-cart-popup .ct-toggle-close',
	'--theme-icon-size: ' . $icon_size . 'px'
);

blocksy_output_colors([
	'value' => blc_theme_functions()->blocksy_get_theme_mod('added_to_cart_popup_close_button_color'),
	'default' => [
		'default' => [ 'color' => 'rgba(0, 0, 0, 0.5)' ],
		'hover' => [ 'color' => 'rgba(0, 0, 0, 0.8)' ],
	],
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'responsive' => true,
	'variables' => [
		'default' => [
			'selector' => '#ct-added-to-cart-popup .ct-toggle-close',
			'variable' => 'theme-icon-color'
		],

		'hover' => [
			'selector' => '#ct-added-to-cart-popup .ct-toggle-close:hover',
			'variable' => 'theme-icon-color'
		]
	],
]);

blocksy_output_background_css([
	'selector' => '#ct-added-to-cart-popup .ct-popup-inner > article',
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'responsive' => true,
	'value' => blc_theme_functions()->blocksy_get_theme_mod('added_to_cart_popup_background',
		blocksy_background_default_value([
			'backgroundColor' => [
				'default' => [
					'color' => 'var(--theme-palette-color-8)'
				],
			],
		])
	)
]);

blocksy_output_background_css([
	'selector' => '#ct-added-to-cart-popup',
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'responsive' => true,
	'value' => blc_theme_functions()->blocksy_get_theme_mod('added_to_cart_popup_backdrop_background',
		blocksy_background_default_value([
			'backgroundColor' => [
				'default' => [
					'color' => 'rgba(18, 21, 25, 0.5)'
				],
			],
		])
	)
]);