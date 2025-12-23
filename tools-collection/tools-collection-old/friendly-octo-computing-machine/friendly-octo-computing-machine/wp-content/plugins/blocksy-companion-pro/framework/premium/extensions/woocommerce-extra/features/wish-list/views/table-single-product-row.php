<?php

$single_product_id = null;

if (
	isset($single_product['id'])
	&&
	is_numeric($single_product['id'])
) {
	$single_product_id = $single_product['id'];
} elseif (is_numeric($single_product)) {
	$single_product_id = $single_product;
}

if (! $single_product_id) {
	return;
}

$product = wc_get_product($single_product_id);

$status = $product->get_status();

if ($status === 'trash') {
	return;
}

if (
	$status === 'private'
	&&
	! current_user_can('read_private_products')
) {
	return;
}

$maybeVariations = null;

$product_permalink = $product->is_visible() ? $product->get_permalink() : '';

if (
	$product->is_type('variation')
	||
	(
		$product->is_type('variable')
		&&
		blc_theme_functions()->blocksy_manager()
		&&
		blc_theme_functions()->blocksy_manager()
			->woocommerce
			->retrieve_product_default_variation($product)
	)
) {
	$maybeVariations = $single_product;

	if (isset($maybeVariations['attributes'])) {
		$product_permalink = esc_url(
			add_query_arg(
				$maybeVariations['attributes'],
				$product_permalink
			)
		);
	}
}

$is_simple_product = blc_get_ext('woocommerce-extra')
	->utils
	->is_simple_product($product);

if (isset($is_simple_product['fake_type'])) {
	$product_classname = WC()
		->product_factory
		->get_product_classname(
			$single_product_id,
			'variable'
		);

	try {
		$product = new $product_classname($single_product_id);
	} catch (Exception $e) {
	}
}

global $post;

$previous_post = $post;

$GLOBALS['product'] = $product;
$GLOBALS['post'] = get_post($product->get_id());

if (! $product || ! $product->exists()) {
	return;
}

$class = '';

if (
	! $product->is_type('grouped')
	&&
	! $product->is_type('external')
) {
	$class .= 'ct-ajax-add-to-cart';
}

$columns = [
	[
		'attr' => [
			'class' => 'wishlist-product-thumbnail'
		],

		'result' => blocksy_render_view(
			dirname(__FILE__) . '/columns/product-thumbnail.php',
			[
				'product' => $product,
			]
		)
	],

	[
		'attr' => [
			'class' => 'wishlist-product-name'
		],

		'result' => blocksy_render_view(
			dirname(__FILE__) . '/columns/product-name.php',
			[
				'product' => $product,
				'single_product' => $single_product,
				'single_product_id' => $single_product_id,
				'product_permalink' => $product_permalink,
				'has_custom_user' => $has_custom_user,

				'maybeVariations' => $maybeVariations,
				'is_simple_product' => $is_simple_product,
			]
		)
	],

	[
		'attr' => [
			'class' => 'wishlist-product-actions'
		],

		'result' => blocksy_render_view(
			dirname(__FILE__) . '/columns/product-actions.php',
			[
				'product' => $product,
				'maybeVariations' => $maybeVariations,
				'is_simple_product' => $is_simple_product,
			]
		)
	]
];

$remove_button = blocksy_render_view(
	dirname(__FILE__) . '/columns/product-remove-button.php',
	[
		'single_product_id' => $single_product_id,
		'has_custom_user' => $has_custom_user,
	]
);

if (! empty($remove_button)) {
	$columns[] = [
		'attr' => [
			'class' => 'wishlist-product-remove'
		],

		'result' => $remove_button
	];
}

blocksy_html_tag_e(
	'tr',
	[
		'class' => $class
	],

	implode('', array_map(function ($column) {
		return blocksy_html_tag(
			'td',
			$column['attr'],
			$column['result']
		);
	}, $columns))
);

$GLOBALS['post'] = $previous_post;
