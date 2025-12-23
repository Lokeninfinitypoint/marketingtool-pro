<?php

namespace Blocksy\Extensions\WoocommerceExtra;

$consent = '';

if (function_exists('blocksy_ext_cookies_checkbox')) {
	$consent = blocksy_ext_cookies_checkbox('waitlist');
}
ob_start();
wp_nonce_field('blocksy_waitlist_subscribe');
do_action('blocksy:ext:woocommerce-extra:waitlist:subscribe:fields');
$custom_fields = ob_get_clean();

$loading_icon = '<svg class="ct-button-loader" width="16" height="16" viewBox="0 0 24 24">
				<circle cx="12" cy="12" r="10" opacity="0.2" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="2.5"></circle>

				<path d="m12,2c5.52,0,10,4.48,10,10" fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2.5">
					<animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="0.6s" from="0 12 12" to="360 12 12" repeatCount="indefinite"></animateTransform>
				</path>
			</svg>';

$form = blocksy_html_tag(
	'form',
	[
		'class' => 'ct-product-waitlist-form',
		'action' => esc_url(admin_url('admin-post.php')),
		'method' => 'post',
	],
	blocksy_html_tag(
		'input',
		[
			'type' => 'email',
			'name' => 'email',
			'value' => is_user_logged_in() ? wp_get_current_user()->user_email : '',
			'placeholder' => __(
				'Enter your email',
				'blocksy-companion'
			),
			'required' => true,
		]
	) .
	blocksy_html_tag(
		'button',
		[
			'class' => 'ct-button',
			'type' => 'submit',
		],
		blocksy_html_tag(
			'span',
			[],
			__('Join Waitlist', 'blocksy-companion')
		) .
		$loading_icon
	) .
	$consent .
	$custom_fields
);

$products_type = blc_theme_functions()->blocksy_get_theme_mod('waitlist_type', 'boxed');

$section_title = blocksy_html_tag(
	'h5',
	[
		'class' => 'ct-waitlist-title'
	],
	__('This product is currently sold out!', 'blocksy-companion')
);

$section_message = blocksy_html_tag(
	'p',
	[
		'class' => 'ct-waitlist-message'
	],
	__('No worries! Please enter your e-mail address and we will promptly notify you as soon as the item is back in stock.', 'blocksy-companion')
);

$section_success_message = blocksy_html_tag(
	'p',
	[
		'class' => 'ct-waitlist-message'
	],
	__('Great! You have been added to the waitlist for this product. Please check your inbox and confirm the subscription to this waitlist.', 'blocksy-companion')
);

$need_confirmation = blc_theme_functions()->blocksy_get_theme_mod('waitlist_user_confirmation', [
	'logged_in' => true,
	'logged_out' => true,
]);

if (
	(
		is_user_logged_in()
		&&
		! $need_confirmation['logged_in']
	)
	||
	(
		! is_user_logged_in()
		&&
		! $need_confirmation['logged_out']
	)
	||
	$state === 'subscribed-confirmed'
) {
	$section_success_message = blocksy_html_tag(
		'p',
		[
			'class' => 'ct-waitlist-message'
		],
		__('Great! You have been added to the waitlist for this product. You will receive an email as soon as the item is back in stock.', 'blocksy-companion')
	);
}

if ($state === 'subscribed-confirmed') {
	$state = 'subscribed';
}

$section_subscribed_users = '';

if (blc_theme_functions()->blocksy_get_theme_mod('waitlist_show_users_number', 'no') === 'yes') {
	$count_data = ProductWaitlistLayer::get_users_count($product_id);

	$section_subscribed_users = blocksy_html_tag(
		'p',
		[
			'class' => 'ct-waitlist-users',
			'data-count' => $count_data['waitlist_users'],
		],
		$count_data['message']
	);
}

blocksy_html_tag_e(
	'div',
	[
		'class' => 'ct-product-waitlist',
		'data-type' => $products_type,
		'data-state' => $state
	],
	blocksy_html_tag(
		'div',
		[
			'class' => 'ct-waitlist-initial-state'
		],
		$section_title .
		$section_message .
		$form .
		$section_subscribed_users
	) .
	blocksy_html_tag(
		'div',
		[
			'class' => 'ct-waitlist-subscribed-state'
		],
		$section_success_message .
		blocksy_html_tag(
			'button',
			[
				'class' => 'ct-button unsubscribe',
				'type' => 'submit',
				'data-token' => $unsubscribe_token,
				'data-id' => $subscription_id,
			],
			blocksy_html_tag(
				'span',
				[],
				__('Unsubscribe', 'blocksy-companion')
			) .
			$loading_icon
		) .
		$section_subscribed_users
	),

);
