<?php

if (! $entry->product_id) {
	return;
}

$product = wc_get_product($entry->product_id);

$status = $product->get_status();

if (
	$status === 'trash'
	||
	(
		$status === 'private'
		&&
		! current_user_can('read_private_products')
	)
) {
	return;
}

$columns = [];


$columns[] = blocksy_html_tag(
	'td',
	[
		'class' => 'waitlist-product-thumbnail'
	],
	blocksy_render_view(
		dirname(__FILE__) . '/product-thumbnail.php',
		[
			'product' => $product,
		]
	)
);

$columns[] = blocksy_html_tag(
	'td',
	[
		'class' => 'waitlist-product-name'
	],
	blocksy_render_view(
		dirname(__FILE__) . '/product-name.php',
		[
			'product' => $product,
		]
	)
);

$columns[] = blocksy_html_tag(
	'td',
	[
		'class' => 'waitlist-product-status'
	],
	__('Out of Stock', 'blocksy-companion')
);

$columns[] = blocksy_html_tag(
	'td',
	[
		'class' => 'waitlist-subscription-status'
	],
	$entry->confirmed ? __('Yes', 'blocksy-companion') : __('No', 'blocksy-companion')
);

$columns[] = blocksy_html_tag(
	'td',
	[
		'class' => 'waitlist-product-actions'
	],
	blocksy_html_tag(
		'div',
		[

		],
		blocksy_html_tag(
			'button',
			[
				'class' => 'button unsubscribe',
				'type' => 'submit',
				'data-token' => $entry->unsubscribe_token,
				'data-id' => $entry->subscription_id,
			],
			__('Unsubscribe', 'blocksy-companion')
		)
	)
);

blocksy_html_tag_e(
	'tr',
	[
		'class' => 'ct-woocommerce-waitlist-table-row',
	],

	implode('', $columns)
);
