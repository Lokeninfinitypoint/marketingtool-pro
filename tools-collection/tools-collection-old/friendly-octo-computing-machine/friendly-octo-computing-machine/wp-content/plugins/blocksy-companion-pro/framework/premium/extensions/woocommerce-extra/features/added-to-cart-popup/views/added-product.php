<?php

$display_descriptor['show_attributes'] = (
	$display_descriptor['show_attributes']
	&&
	$product->get_type() === 'variation'
);

$product_name = $product->get_title();

$product_image = '';
$product_title = '';
$product_description = '';
$product_price = '';
$product_attributes = '';
$product_totals = '';

if ($display_descriptor['show_image']) {
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

	$product_image = blocksy_media([
		'no_image_type' => 'woo',
		'attachment_id' => $gallery_images[0],
		'post_id' => $product->get_id(),
		'size' => blc_theme_functions()->blocksy_get_theme_mod(
			'added_to_cart_popup_image_size',
			'medium'
		),
		'ratio' => blc_theme_functions()->blocksy_get_theme_mod(
			'added_to_cart_popup_image_ratio',
			'3/4'
		),
		'tag_name' => 'a',
		'html_atts' => $html_atts,
		'lazyload' => false
	]);
}

if ($display_descriptor['show_price']) {
	$product_price = blocksy_html_tag(
		'span',
		[
			'class' => 'price',
		],
		WC()->cart->get_product_price($product)
	);
}

$product_title_price = blocksy_html_tag(
	'div',
	[
		'class' => 'ct-product-title-price',
	],
	blocksy_html_tag(
		'h2',
		[
			'class' => 'woocommerce-loop-product__title'
		],
		$product_name
	) .
	$product_price
);

if ($display_descriptor['show_description']) {
	$product_description = $product->get_short_description();

	if ($product->get_type() === 'variation') {
		$product_description = $product->get_description();
	}

	$description = blocksy_entry_excerpt([
		'length' => intval(blc_theme_functions()->blocksy_get_theme_mod('added_to_cart_popup_description_length', 20)),
		'source' => 'custom',
		'custom_exceprt' => $product_description,
		'skip_container' => true
	]);

	$product_description = blocksy_html_tag(
		'div',
		[
			'class' => 'ct-product-description',
		],
		$description
	);
}

$item_data = [];

if ($product->is_type('variation') && is_array($cart_item['variation'])) {
	foreach ($cart_item['variation'] as $name => $value) {
		if ('' === $value) {
			continue;
		}

		$taxonomy = wc_attribute_taxonomy_name(str_replace('attribute_pa_', '', urldecode($name)));

		if (taxonomy_exists($taxonomy)) {
			$term = get_term_by('slug', $value, $taxonomy);

			if (! is_wp_error($term) && $term && $term->name) {
				$value = $term->name;
			}

			$label = wc_attribute_label($taxonomy);

			$labels = get_taxonomy_labels(
				get_taxonomy(str_replace('attribute_', '', urldecode($name)))
			);

			if (isset($labels->singular_name)) {
				$label = $labels->singular_name;
			}
		} else {
			$value = apply_filters(
				'woocommerce_variation_option_name',
				$value,
				null,
				$taxonomy,
				$cart_item['data']
			);

			$label = wc_attribute_label(str_replace('attribute_', '', $name));
		}

		$item_data[] = array(
			'key'   => $label,
			'value' => $value,
		);
	}
}

$item_data = apply_filters('woocommerce_get_item_data', $item_data, $cart_item);

if (
	$display_descriptor['show_attributes']
	&&
	count($item_data) > 0
) {
	foreach ($item_data as $key => $data) {
		if (! empty($data['hidden'])) {
			unset($item_data[$key]);

			continue;
		}

		$item_data[$key]['key'] = ! empty($data['key']) ? $data['key'] : $data['name'];
		$item_data[$key]['display'] = ! empty($data['display']) ? $data['display'] : $data['value'];
	}

	$attributes_html = [];

	foreach ($item_data as $data) {
		$attributes_html[] = blocksy_html_tag(
			'li',
			[],
			esc_html($data['key']) .
			blocksy_html_tag(
				'span',
				[],
				wp_kses_post($data['display'])
			)
		);
	}

	$product_attributes = blocksy_html_tag(
		'ul',
		[
			'class' => 'ct-product-attributes',
		],
		join('', $attributes_html)
	);
}

if (
	$display_descriptor['show_shipping']
	||
	$display_descriptor['show_tax']
	||
	$display_descriptor['show_total']
) {

	$shipping_total = wc_price(WC()->cart->get_shipping_total());

	if (
		(float) WC()->cart->get_shipping_tax() > 0
		&&
		WC()->cart->display_prices_including_tax()
	) {
		$shipping_total = blocksy_html_tag(
			'span',
			[],
			wc_price(
				WC()->cart->get_shipping_total() + WC()->cart->get_shipping_tax()
			) . ' ' . WC()->countries->inc_tax_or_vat()
		);
	}

	$product_totals = blocksy_html_tag(
		'ul',
		[
			'class' => 'ct-product-totals',
		],
		(
			$display_descriptor['show_shipping'] ? blocksy_html_tag(
				'li',
				[
					'class' => 'ct-added-to-cart-popup-shipping',
				],
				__('Shipping Cost', 'blocksy-companion') . ' ' . $shipping_total
			) : ''
		) .
		(
			$display_descriptor['show_tax'] ? blocksy_html_tag(
				'li',
				[
					'class' => 'ct-added-to-cart-popup-tax',
				],
				__('Tax Amount', 'blocksy-companion') . ' ' . wc_price(WC()->cart->get_total_tax())
			) : ''
		) .
		(
			$display_descriptor['show_total'] ? blocksy_html_tag(
				'li',
				[
					'class' => 'ct-added-to-cart-popup-total',
				],
				__('Cart Total', 'blocksy-companion') . ' ' . wc_price(WC()->cart->get_total('edit'))
			) : ''
		)
	);
}

$additional_products = blocksy_render_view(
	dirname(__FILE__) . '/additional-products.php',
	[
		'cart_items' => $cart_items
	]
);

do_action('blocksy:ext:woocommerce-extra:added-to-cart:product:before');

blocksy_html_tag_e(
	'div',
	[
		'class' => 'ct-added-to-cart-product' . (! $display_descriptor['show_image'] ? ' no-image' : ''),
	],
	$product_image .

	blocksy_html_tag(
		'section',
		[],
		$product_title_price .
		$product_description .
		$product_attributes .
		$additional_products .
		$product_totals
	)
);
