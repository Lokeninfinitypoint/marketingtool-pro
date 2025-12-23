<?php

if (! isset($is_mobile)) {
	$is_mobile = false;
}

$maybeVariationsAttrs = $product->get_attributes();

if (
	isset($maybeVariations['attributes'])
	&&
	! empty($maybeVariations['attributes'])
) {
	$maybeVariationsAttrs = array_merge(
		$maybeVariationsAttrs,
		$maybeVariations['attributes']
	);
}

$product->set_attributes($maybeVariationsAttrs);

$previous_product = $product;

if ($product->get_type() === 'variation') {
	$parent_is_simple_product = blc_get_ext('woocommerce-extra')
		->utils
		->is_simple_product(
			wc_get_product($product->get_parent_id())
		);

	// Check if parent product is decorated by 3rd parties
	if (
		! $parent_is_simple_product['value']
		&&
		isset($parent_is_simple_product['fake_type'])
	) {
		$is_simple_product = $parent_is_simple_product;
		$GLOBALS['product'] = wc_get_product($product->get_parent_id());
	}
}

if (
	$is_simple_product['value']
	&&
	! $is_mobile
) {
	do_action('woocommerce_simple_add_to_cart');
} else {
	woocommerce_template_loop_add_to_cart();
}

$GLOBALS['product'] = $previous_product;
