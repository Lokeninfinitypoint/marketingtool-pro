<?php

$root_product_cart_item = $cart_items[0]['cart_item'];

$product_id = $root_product_cart_item['product_id'];

if (isset($root_product_cart_item['variation_id']) && $root_product_cart_item['variation_id']) {
	$product_id = $root_product_cart_item['variation_id'];
}

$product = wc_get_product($product_id);

$close_button_type = blc_theme_functions()->blocksy_get_theme_mod(
	'added_to_cart_popup_close_button_type',
	'type-1'
);

$is_cutomize_preview = false;

// phpcs:ignore WordPress.Security.NonceVerification.Recommended
if (isset($_REQUEST['wp_customize'])) {
	$is_cutomize_preview = true;
}

$popup_animation = blc_theme_functions()->blocksy_get_theme_mod(
	'added_to_cart_popup_open_animation',
	'slide-right'
);

$popup_size = blc_theme_functions()->blocksy_get_theme_mod(
	'added_to_cart_popup_size',
	'medium'
);

$popup_position = blc_theme_functions()->blocksy_get_theme_mod(
	'added_to_cart_popup_position',
	'bottom:right'
);

$display_descriptor = [
	'show_image' => blc_theme_functions()->blocksy_get_theme_mod('added_to_cart_popup_show_image', 'yes'),
	'show_price' => blc_theme_functions()->blocksy_get_theme_mod('added_to_cart_popup_show_price', 'yes'),
	'show_description' => blc_theme_functions()->blocksy_get_theme_mod('added_to_cart_popup_show_description', 'no'),
	'show_shipping' => blc_theme_functions()->blocksy_get_theme_mod('added_to_cart_popup_show_shipping', 'yes'),
	'show_tax' => blc_theme_functions()->blocksy_get_theme_mod('added_to_cart_popup_show_tax', 'no'),
	'show_total' => blc_theme_functions()->blocksy_get_theme_mod('added_to_cart_popup_show_total', 'yes'),
	'show_attributes' => blc_theme_functions()->blocksy_get_theme_mod('added_to_cart_popup_show_attributes', 'yes'),
	'show_cart' => blc_theme_functions()->blocksy_get_theme_mod('added_to_cart_popup_show_cart', 'yes'),
	'show_checkout' => blc_theme_functions()->blocksy_get_theme_mod('added_to_cart_popup_show_checkout', 'no'),
	'show_continue' => blc_theme_functions()->blocksy_get_theme_mod('added_to_cart_popup_show_continue', 'yes'),
	'suggested_products' => blc_theme_functions()->blocksy_get_theme_mod('cart_popup_suggested_products', 'yes'),
];

foreach ($display_descriptor as $key => $value) {
	if ($is_cutomize_preview) {
		$display_descriptor[$key] = true;
	} else {
		$display_descriptor[$key] = $value === 'yes';
	}
}

$added_product = blocksy_render_view(
	dirname(__FILE__) . '/views/added-product.php',
	[
		'display_descriptor' => $display_descriptor,
		'product' => $product,
		'cart_item' => $root_product_cart_item,
		'cart_items' => $cart_items
	]
);

$popup_actions = blocksy_render_view(
	dirname(__FILE__) . '/views/product-actions.php',
	[
		'display_descriptor' => $display_descriptor,
	]
);

$products_content = '';

if ($display_descriptor['suggested_products']) {
	$products_content = apply_filters('blocksy:ext:woocommerce-extra:added-to-cart:suggested-products', '', $product_id);
}

$panel_attr = [
	'id' => 'ct-added-to-cart-popup',
	'class' => 'ct-popup',
	'data-popup-size' => $popup_size,
	'data-popup-position' => $popup_position,
	'data-popup-overflow' => 'scroll',
	'data-popup-backdrop' => 'yes',
	'data-scroll-lock' => 'yes',
	'data-popup-animation' => $popup_animation
];

if ($display_descriptor['show_continue']) {
	$panel_attr['data-popup-close-strategy'] = json_encode([
		'backdrop' => true,
		'esc' => true,
		'button_click' => [
			'selector' => '.button-close'
		]
	]);
}

$panel_heading = blocksy_html_tag(
	'div',
	[
		'class' => 'ct-added-to-cart-message'
	],

	'<svg class="ct-check-icon" width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M23 19h-2v-2c0-.6-.4-1-1-1s-1 .4-1 1v2h-2c-.6 0-1 .4-1 1s.4 1 1 1h2v2c0 .6.4 1 1 1s1-.4 1-1v-2h2c.6 0 1-.4 1-1s-.4-1-1-1ZM21 6h-3c0-3.3-2.7-6-6-6S6 2.7 6 6H3C1.3 6 0 7.3 0 9v10c0 2.8 2.2 5 5 5h7c.6 0 1-.4 1-1s-.4-1-1-1H5c-1.7 0-3-1.3-3-3V9c0-.6.4-1 1-1h3v2c0 .6.4 1 1 1s1-.4 1-1V8h8v2c0 .6.4 1 1 1s1-.4 1-1V8h3c.6 0 1 .4 1 1v4.1c0 .6.4 1 1 1s1-.4 1-1V9c0-1.7-1.3-3-3-3ZM8 6c0-2.2 1.8-4 4-4s4 1.8 4 4H8Z"/></svg>' .

	__('Product successfully added to your cart!', 'blocksy-companion') .

	blocksy_html_tag(
		'button',
		[
			'class' => 'ct-toggle-close',
			'data-type' => $close_button_type,
			'aria-label' => __('Close Modal', 'blocksy-companion'),
		],
		'<svg class="ct-icon" width="12" height="12" viewBox="0 0 15 15">
			<path d="M1 15a1 1 0 01-.71-.29 1 1 0 010-1.41l5.8-5.8-5.8-5.8A1 1 0 011.7.29l5.8 5.8 5.8-5.8a1 1 0 011.41 1.41l-5.8 5.8 5.8 5.8a1 1 0 01-1.41 1.41l-5.8-5.8-5.8 5.8A1 1 0 011 15z"></path>
		</svg>'
	)
);

$panel_content = blocksy_html_tag(
	'div',
	[
		'class' => 'ct-popup-inner',
	],
	blocksy_html_tag(
		'article',
		[],
		$panel_heading .
		blocksy_html_tag(
			'div',
			[
				'class' => 'ct-popup-content'
			],
			$added_product .
			$popup_actions .
			$products_content
		)
	)
);

blocksy_html_tag_e('div', $panel_attr, $panel_content);
