<?php

if (! $prefix) {
	return;
}

if (blc_theme_functions()->blocksy_get_theme_mod($prefix . 'products', 'yes') !== 'yes') {
	return;
}

$defaults = \Blocksy\Extensions\WoocommerceExtra\CartSuggestedProducts::get_option_defaults();

$products_type = blc_theme_functions()->blocksy_get_theme_mod($prefix . 'products_type', 'inline');
$products_source = blc_theme_functions()->blocksy_get_theme_mod($prefix . 'products_source', 'related');
$number_of_items = blc_theme_functions()->blocksy_get_theme_mod($prefix . 'products_number_of_items', 6);

$products_content = '';

$product_ids = [];

add_filter('woocommerce_product_related_posts_shuffle', '__return_false');

foreach ($added_products as $product_id) {
	$product_id = intval($product_id);
	$product = wc_get_product($product_id);

	if ($product->get_type() === 'variation') {
		$product_id = $product->get_parent_id();
	}

	if ($products_source === 'related') {
		$product_ids[] = wc_get_related_products($product_id, intval($number_of_items));
	} else if ($products_source === 'recent') {
		$product_ids[] = \Blocksy\RecentlyViewedProducts::get_recently_viewed_products();
	} else if ($products_source === 'upsell') {
		$product_ids[] = $product->get_upsell_ids();

		if ($product->get_type() === 'variation') {
			$parent = wc_get_product($product->get_parent_id());
			$product_ids[] = $parent->get_upsell_ids();
		}
	} else if ($products_source === 'cross_sell') {
		$product_ids[] = $product->get_cross_sell_ids();
	}
}

remove_filter('woocommerce_product_related_posts_shuffle', '__return_false');

$product_ids = array_merge(...$product_ids);
$product_ids = array_diff($product_ids, $added_products);
$product_ids = array_slice($product_ids, 0, $number_of_items);

$products_section_title = __('Suggested Products', 'blocksy-companion');

$slideshow_columns = blc_theme_functions()->blocksy_get_theme_mod(
	$prefix . 'products_columns',
	$defaults[$prefix]['products_columns']
);

$slideshow_columns = blocksy_expand_responsive_value($slideshow_columns);

$arrows_classes = ['ct-slider-arrows'];

if (count($product_ids) <= intval($slideshow_columns['desktop'])) {
	$arrows_classes[] = 'ct-hidden-lg';
}

if (count($product_ids) <= intval($slideshow_columns['tablet'])) {
	$arrows_classes[] = 'ct-hidden-md';
}

if (count($product_ids) <= intval($slideshow_columns['mobile'])) {
	$arrows_classes[] = 'ct-hidden-sm';
}

$arrows = '<span class="' . implode(' ', $arrows_classes) . '">
	<span class="ct-arrow-prev">
	<svg width="8" height="8" fill="currentColor" viewBox="0 0 8 8">
	<path d="M5.05555,8L1.05555,4,5.05555,0l.58667,1.12-2.88,2.88,2.88,2.88-.58667,1.12Z"></path>
	</svg>
	</span>

	<span class="ct-arrow-next">
	<svg width="8" height="8" fill="currentColor" viewBox="0 0 8 8">
	<path d="M2.35778,6.88l2.88-2.88L2.35778,1.12,2.94445,0l4,4-4,4-.58667-1.12Z"></path>
	</svg>
	</span>
</span>';

$products_section_title = blocksy_html_tag(
	'div',
	[
		'class' => 'ct-module-title'
	],
	$products_section_title .
	$arrows
);

$products_loop = array_reduce(
	$product_ids,
	function ($html, $product_id) use ($products_type, $prefix, $defaults) {
		$product = wc_get_product($product_id);

		if (! $product) {
			return $html;
		}

		$html_atts = [
			'href' => apply_filters(
				'woocommerce_loop_product_link',
				get_permalink($product->get_id()),
				$product
			),
			'aria-label' => wp_strip_all_tags($product->get_name()),
		];

		if (
			blc_theme_functions()->blocksy_get_theme_mod('woo_archive_affiliate_image_link', 'no') === 'yes'
			&&
			$product->get_type() === 'external'
		) {
			$open_in_new_tab = blc_theme_functions()->blocksy_get_theme_mod(
				'woo_archive_affiliate_image_link_new_tab',
				'no'
			) === 'yes' ? '_blank' : '_self';

			$html_atts['href'] = $product->get_product_url();
			$html_atts['target'] = $open_in_new_tab;
		}

		$gallery_images = blocksy_product_get_gallery_images($product);

		if ($product->get_type() === 'variation') {
			$variation_main_image = $product->get_image_id();

			if ($variation_main_image) {
				if (! in_array($variation_main_image, $gallery_images)) {
					$gallery_images[0] = $variation_main_image;
				}

				$gallery_images = array_merge(
					[$variation_main_image],
					array_diff($gallery_images, [$variation_main_image])
				);
			}
		}

		$image_size = blc_theme_functions()->blocksy_get_theme_mod($prefix . 'products_image_size', 'thumbnail');
		$image_ratio = blc_theme_functions()->blocksy_get_theme_mod($prefix . 'products_image_ratio', '1/1');

		$product_image = blocksy_media([
			'no_image_type' => 'woo',
			'attachment_id' => $gallery_images[0],
			'post_id' => $product->get_id(),
			'size' => $image_size,
			'ratio' => $image_ratio,
			'tag_name' => 'a',
			'html_atts' => $html_atts,
			'lazyload' => false
		]);

		$product_title = blocksy_html_tag(
			'a',
			[
				'href' => $product->get_permalink(),
				'class' => 'ct-product-title',
			],
			$product->get_name()
		);

		$GLOBALS['product'] = $product;

		$product_price = '';
		$product_add_to_cart = '';

		if (blc_theme_functions()->blocksy_get_theme_mod($prefix . 'products_show_price', 'yes') === 'yes') {
			$product_price = blocksy_html_tag(
				'span',
				[
					'class' => 'price',
				],
				$product->get_price_html()
			);
		}

		if (
			blc_theme_functions()->blocksy_get_theme_mod(
				$prefix . 'products_show_add_to_cart',
				$defaults[$prefix]['products_show_add_to_cart']
			) === 'yes'
		) {
			ob_start();
			woocommerce_template_loop_add_to_cart();
			$product_add_to_cart = ob_get_clean();
		}

		$html .= blocksy_html_tag(
			'div',
			[
				'class' => 'flexy-item',
			],
			$product_image .
			blocksy_html_tag(
				'section',
				[],
				$product_title .
				$product_price .
				$product_add_to_cart
			)
		);

		return $html;
	},
	''
);

$products_loop = blocksy_html_tag(
	'div',
	[
		'class' => 'flexy',
	],
	blocksy_html_tag(
		'div',
		[
			'class' => 'flexy-view',
			'data-flexy-view' => 'boxed',
		],
		blocksy_html_tag(
			'div',
			[
				'data-products' => $products_type,
				'class' => 'flexy-items',
			],
			$products_loop
		)
	)
);

do_action('blocksy:ext:woocommerce-extra:added-to-cart:suggested_products:before');

$suffix = [
	'mini_cart_suggested_' => '--mini-cart',
	'checkout_suggested_' => '--checkout',
	'cart_popup_suggested_' => '--cart-popup',
	'cart_suggested_' => '--cart',
];

$classes = [
	'ct-suggested-products' . $suffix[$prefix],
	blocksy_visibility_classes(
		blc_theme_functions()->blocksy_get_theme_mod($prefix . 'products_visibility', [
			'desktop' => true,
			'tablet' => true,
			'mobile' => true,
		])
	),
];

$content = '';
$container_attr = [];

if (! empty($product_ids)) {
	$content = $products_section_title . $products_loop;
	$classes[] = 'flexy-container';

	$container_attr = [
		'class' => trim(implode(' ', $classes)),
		'data-flexy' => 'no'
	];

	if (blc_theme_functions()->blocksy_get_theme_mod($prefix . 'products_autoplay', 'yes') === 'yes') {
		$container_attr['data-autoplay'] = blc_theme_functions()->blocksy_get_theme_mod(
			$prefix . 'products_autoplay_speed',
			3
		);
	}
}

blocksy_html_tag_e('div', $container_attr, $content);

do_action('blocksy:ext:woocommerce-extra:added-to-cart:suggested_products:after');
