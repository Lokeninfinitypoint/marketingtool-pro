<?php


// Single product type 2
$product_view_stacked_columns = blc_theme_functions()->blocksy_get_theme_mod('product_view_stacked_columns', 2);

if ($product_view_stacked_columns !== 2) {
	blocksy_output_responsive([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => '.ct-stacked-gallery .ct-stacked-gallery-container',
		'variableName' => 'columns',
		'value' => $product_view_stacked_columns,
		'unit' => ''
	]);
}

$product_view_columns_top = blc_theme_functions()->blocksy_get_theme_mod('product_view_columns_top', 3);

if ($product_view_columns_top !== 3) {
	blocksy_output_responsive([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => '.ct-columns-top-gallery .woocommerce-product-gallery',
		'variableName' => 'columns',
		'value' => $product_view_columns_top,
		'unit' => ''
	]);
}


// new badge
blocksy_output_colors([
	'value' => blc_theme_functions()->blocksy_get_theme_mod('newBadgeColor'),
	'default' => [
		'text' => [ 'color' => '#ffffff' ],
		'background' => [ 'color' => '#35a236' ],
	],
	'css' => $css,
	'variables' => [
		'text' => [
			'selector' => '.ct-woo-badge-new',
			'variable' => 'badge-text-color'
		],

		'background' => [
			'selector' => '.ct-woo-badge-new',
			'variable' => 'badge-background-color'
		],
	],
]);

// featured badge
blocksy_output_colors([
	'value' => blc_theme_functions()->blocksy_get_theme_mod('featuredBadgeColor'),
	'default' => [
		'text' => [ 'color' => '#ffffff' ],
		'background' => [ 'color' => '#de283f' ],
	],
	'css' => $css,
	'variables' => [
		'text' => [
			'selector' => '.ct-woo-badge-featured',
			'variable' => 'badge-text-color'
		],

		'background' => [
			'selector' => '.ct-woo-badge-featured',
			'variable' => 'badge-background-color'
		],
	],
]);


// product archive additional action buttons
blocksy_output_colors([
	'value' => blc_theme_functions()->blocksy_get_theme_mod('additional_actions_button_icon_color'),
	'default' => [
		'default' => [ 'color' => 'var(--theme-text-color)' ],
		'hover' => [ 'color' => '#ffffff' ],

		'default_2' => [ 'color' => 'var(--theme-text-color)' ],
		'hover_2' => [ 'color' => 'var(--theme-palette-color-1)' ],
	],
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'responsive' => true,
	'variables' => [
		'default' => [
			'selector' => '.ct-woo-card-extra[data-type="type-1"]',
			'variable' => 'theme-button-text-initial-color'
		],
		'hover' => [
			'selector' => '.ct-woo-card-extra[data-type="type-1"]',
			'variable' => 'theme-button-text-hover-color'
		],

		'default_2' => [
			'selector' => '.ct-woo-card-extra[data-type="type-2"]',
			'variable' => 'theme-button-text-initial-color'
		],
		'hover_2' => [
			'selector' => '.ct-woo-card-extra[data-type="type-2"]',
			'variable' => 'theme-button-text-hover-color'
		],
	],
]);

blocksy_output_colors([
	'value' => blc_theme_functions()->blocksy_get_theme_mod('additional_actions_button_background_color'),
	'default' => [
		'default' => [ 'color' => '#ffffff' ],
		'hover' => [ 'color' => 'var(--theme-palette-color-1)' ],

		'default_2' => [ 'color' => '#ffffff' ],
		'hover_2' => [ 'color' => '#ffffff' ],
	],
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'responsive' => true,
	'variables' => [
		'default' => [
			'selector' => '.ct-woo-card-extra[data-type="type-1"]',
			'variable' => 'theme-button-background-initial-color'
		],
		'hover' => [
			'selector' => '.ct-woo-card-extra[data-type="type-1"]',
			'variable' => 'theme-button-background-hover-color'
		],

		'default_2' => [
			'selector' => '.ct-woo-card-extra[data-type="type-2"]',
			'variable' => 'theme-button-background-initial-color'
		],
		'hover_2' => [
			'selector' => '.ct-woo-card-extra[data-type="type-2"]',
			'variable' => 'theme-button-background-hover-color'
		],
	],
]);