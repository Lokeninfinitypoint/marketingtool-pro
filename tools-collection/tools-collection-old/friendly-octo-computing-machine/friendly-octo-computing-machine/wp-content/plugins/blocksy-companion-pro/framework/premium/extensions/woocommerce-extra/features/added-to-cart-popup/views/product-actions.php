<?php

if (
	$display_descriptor['show_cart']
	||
	$display_descriptor['show_continue']
	||
	$display_descriptor['show_checkout']
) {
	do_action('blocksy:ext:woocommerce-extra:added-to-cart:actions:before');

	blocksy_html_tag_e(
		'div',
		[
			'class' => 'ct-popup-actions',
		],
		(
			$display_descriptor['show_cart'] ? blocksy_html_tag(
				'a',
				[
					'class' => 'ct-button ct-added-to-cart-popup-cart',
					'href' => wc_get_cart_url(),
				],
				__('View Cart', 'blocksy-companion')
			) : ''
		) .
		(
			$display_descriptor['show_checkout'] ? blocksy_html_tag(
				'a',
				[
					'class' => 'ct-button ct-added-to-cart-popup-checkout',
					'href' => wc_get_checkout_url(),
				],
				__('Checkout', 'blocksy-companion')
			) : ''
		) .
		(
			$display_descriptor['show_continue'] ? blocksy_html_tag(
				'a',
				[
					'class' => 'ct-button-ghost button-close ct-added-to-cart-popup-continue',
					'href' => '#'
				],
				__('Continue Shopping', 'blocksy-companion')
			) : ''
		)
	);
}
