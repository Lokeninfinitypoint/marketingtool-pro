<?php

$woo_reserved_timer_in_cart = blocksy_get_theme_mod('woo_reserved_timer_in_cart', 'yes');

if ($woo_reserved_timer_in_cart === 'yes') {
	blocksy_output_colors([
		'value' => blc_theme_functions()->blocksy_get_theme_mod('woo_reserved_timer_cart_text_color'),
		'default' => [
			'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		],
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		// 'responsive' => true,
		'variables' => [
			'default' => [
				'selector' => '.ct-cart-reserved-timer-cart',
				'variable' => 'cart-reserved-timer-text-color'
			],
		],
	]);

	blocksy_output_colors([
		'value' => blc_theme_functions()->blocksy_get_theme_mod('woo_reserved_timer_cart_background_color'),
		'default' => [
			'default' => [ 'color' => '#F0F1F3' ],
		],
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		// 'responsive' => true,
		'variables' => [
			'default' => [
				'selector' => '.ct-cart-reserved-timer-cart',
				'variable' => 'cart-reserved-timer-background-color'
			],
		],
	]);
}


$woo_reserved_timer_in_mini_cart = blocksy_get_theme_mod('woo_reserved_timer_in_mini_cart', 'yes');

if ($woo_reserved_timer_in_mini_cart === 'yes') {
	blocksy_output_colors([
		'value' => blc_theme_functions()->blocksy_get_theme_mod('woo_reserved_timer_mini_cart_text_color'),
		'default' => [
			'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword() ],
		],
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		// 'responsive' => true,
		'variables' => [
			'default' => [
				'selector' => '.ct-cart-reserved-timer-mini-cart',
				'variable' => 'cart-reserved-timer-text-color'
			],
		],
	]);

	blocksy_output_colors([
		'value' => blc_theme_functions()->blocksy_get_theme_mod('woo_reserved_timer_mini_cart_background_color'),
		'default' => [
			'default' => [ 'color' => 'rgba(214, 214, 214, 0.3)' ],
		],
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		// 'responsive' => true,
		'variables' => [
			'default' => [
				'selector' => '.ct-cart-reserved-timer-mini-cart',
				'variable' => 'cart-reserved-timer-background-color'
			],
		],
	]);
}