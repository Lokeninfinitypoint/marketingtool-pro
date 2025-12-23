<?php

$all_pages = get_posts([
	'post_type' => 'page',
	'numberposts' => -1
]);

$pages_choices = [
	'' => __('Select a page', 'blocksy-companion')
];

foreach ($all_pages as $page) {
	$pages_choices[$page->ID] = $page->post_title;
}

$options = [
	'label' => __('Wishlist', 'blocksy-companion'),
	'type' => 'ct-panel',
	'setting' => ['transport' => 'postMessage'],
	'inner-options' => [

		'product_wishlist_display_for' => [
			'label' => __('Show Wishlist Page To', 'blocksy-companion'),
			'type' => 'ct-radio',
			'value' => 'logged_users',
			'view' => 'text',
			'design' => 'block',
			'choices' => [
				'logged_users' => __( 'Logged Users', 'blocksy-companion' ),
				'all_users' => __( 'All Users', 'blocksy-companion' ),
			],
		],

		blocksy_rand_md5() => [
			'type' => 'ct-condition',
			'condition' => [ 'product_wishlist_display_for' => 'all_users' ],
			'options' => [

				'woocommerce_wish_list_page' => [
					'label' => __('Wishlist Page', 'blocksy-companion'),
					'type' => 'ct-select',
					'value' => '',
					'view' => 'text',
					'design' => 'inline',
					'divider' => 'top',
					'desc' => __('The page you select here will display the wish list for your logged out users.', 'blocksy-companion'),
					'choices' => blocksy_ordered_keys($pages_choices)
				]

			],
		],

		blocksy_rand_md5() => [
			'type' => 'ct-divider',
		],

		'has_variations_wishlist' => [
			'label' => __( 'Specific Product Variation ', 'blocksy-companion' ),
			'type' => 'ct-switch',
			'value' => 'no',
			'desc' => __( 'This option will allow you to add a speciffic product variation to wishlist.', 'blocksy-companion' ),
		],

		'wishlist_image_ratio' => [
			'label' => __('Image Ratio', 'blocksy-companion'),
			'type' => 'ct-ratio',
			'view' => 'inline',
			'value' => '1/1',
			'divider' => 'top:full'
		],

		'wishlist_image_size' => [
			'label' => __('Image Size', 'blocksy-companion'),
			'type' => 'ct-select',
			'value' => 'woocommerce_thumbnail',
			'view' => 'text',
			'design' => 'inline',
			'divider' => 'top',
			'choices' => blocksy_ordered_keys(
				blocksy_get_all_image_sizes()
			),
		],

		blocksy_rand_md5() => [
			'type' => 'ct-condition',
			'condition' => [
				'product_wishlist_display_for' => 'all_users',
				'woocommerce_wish_list_page:truthy' => 'yes'
			],
			'options' => [
				blocksy_get_options('single-elements/post-share-box', [
					'display_style' => 'switch',
					'prefix' => 'wish_list',
					'sync_prefix' => 'single_page',
					'has_share_box_type' => false,
					'has_share_box_location1' => false,
					'has_bottom_share_box_spacing' => false,
					'has_share_items_border' => false,
					'has_forced_icons_spacing' => true
				])
			],
		],

	],
];
