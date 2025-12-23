<?php

foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
	$_product = apply_filters(
		'woocommerce_cart_item_product',
		$cart_item['data'],
		$cart_item,
		$cart_item_key
	);

	if (
		! $_product
		||
		! $_product->exists()
		||
		intval($cart_item['quantity']) === 0
		||
		! apply_filters(
			'woocommerce_checkout_cart_item_visible',
			true,
			$cart_item,
			$cart_item_key
		)
	) {
		continue;
	}

    $thumbnail = '';

	if (blc_theme_functions()->blocksy_get_theme_mod('blocksy_has_image_toggle', 'no') === 'yes') {

		$image_size = blc_theme_functions()->blocksy_get_theme_mod('checkout_image_size', 'woocommerce_thumbnail');
		$image_ratio = blc_theme_functions()->blocksy_get_theme_mod('checkout_image_ratio', '1/1');

		if (function_exists('blocksy_media')) {
			$thumbnail = blocksy_media([
				'no_image_type' => 'woo',
				'attachment_id' => $cart_item['data']->get_image_id(),
				'size' => $image_size,
				'ratio' => $image_ratio,
				'tag_name' => 'figure',
			]);
		}
	}

	$cart_item_name = wp_kses_post(
		apply_filters(
			'woocommerce_cart_item_name',
			$_product->get_name(),
			$cart_item,
			$cart_item_key
		)
	);

	$cart_item_name_wrapper = blocksy_html_tag(
		'div',
		[
			'class' => 'ct-checkout-cart-item-title'
		],
		$cart_item_name
	);

	$quantity = apply_filters(
		'woocommerce_checkout_cart_item_quantity',
		' <strong class="product-quantity">' . blc_safe_sprintf(
			'&times;&nbsp;%s',
			$cart_item['quantity']
		) . '</strong>',
		$cart_item,
		$cart_item_key
	);

	$cart_item_data = wc_get_formatted_cart_item_data($cart_item);

	$content = blocksy_html_tag(
		'span',
		[],
		$cart_item_name_wrapper .
		$quantity
	) .
	$cart_item_data;

	$has_simple_quantity = strpos($quantity, 'product-quantity') !== false;

	if (blc_theme_functions()->blocksy_get_theme_mod('blocksy_has_quantity_toggle', 'no') === 'yes') {
		$content =
		$cart_item_name_wrapper .
		$cart_item_data .
		$quantity;
	}

	if ($has_simple_quantity) {
		$content = blocksy_html_tag(
			'div',
			[
				'class' => 'ct-checkout-cart-item-title'
			],
			$cart_item_name . $quantity
		) .
		$cart_item_data;
	}

?>
	<tr class="<?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
		<td class="product-name">
			<?php
				do_action(
					'blocksy:woocommerce:checkout:cart-item:before',
					$cart_item,
					$cart_item_key,
					$cart_item_data
				);

				blocksy_html_tag_e(
					'div',
					[
						'class' => 'ct-checkout-cart-item'
					],
					$thumbnail .
					blocksy_html_tag(
						'div',
						[
							'class' => 'ct-checkout-cart-item-content'
						],
						$content
					)
				);

				do_action(
					'blocksy:woocommerce:checkout:cart-item:after',
					$cart_item,
					$cart_item_key,
					$cart_item_data
				);
			?>
		</td>

		<td class="product-total">
			<?php
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo apply_filters(
					'woocommerce_cart_item_subtotal',
					WC()->cart->get_product_subtotal($_product, $cart_item['quantity']),
					$cart_item,
					$cart_item_key
				);
			?>
		</td>
	</tr>
<?php

}
