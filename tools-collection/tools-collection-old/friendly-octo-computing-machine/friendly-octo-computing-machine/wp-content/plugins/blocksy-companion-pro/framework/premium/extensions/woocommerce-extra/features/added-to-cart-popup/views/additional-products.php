<?php

if (
	empty($cart_items)
	||
	count($cart_items) < 2
) {
	return;
}

$additional_products = '';

array_shift($cart_items);

$cart_items_html = [];

foreach ($cart_items as $cart_item) {
	$product = wc_get_product($cart_item['cart_item']['product_id']);

	if (! $product) {
		continue;
	}

	$cart_items_html[] = blocksy_html_tag(
		'li',
		[],
		esc_html($product->get_name()) .
		blocksy_html_tag(
			'span',
			[],
			$cart_item['cart_item']['data']->get_price_html()
		)
	);
}

blocksy_html_tag_e(
	'ul',
	[
		'class' => 'ct-product-bundles'
	],
	join('', $cart_items_html)
);
