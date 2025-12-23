<?php


$options = [
	'label' => __('Advanced Reviews', 'blocksy-companion'),
	'type' => 'ct-panel',
	'setting' => ['transport' => 'postMessage'],
	'inner-options' => [

		'woo_advanced_reviews_order' => [
			'label' => __('Reviews Order', 'blocksy-companion'),
			'type' => 'ct-select',
			'value' => 'old',
			'design' => 'block',
			'setting' => [ 'transport' => 'postMessage' ],
			'choices' => blocksy_ordered_keys(
				[
					'old' => __('Oldest First', 'blocksy-companion'),
					'new' => __('Newest First', 'blocksy-companion'),
					'rating_low' => __('Low Rating First', 'blocksy-companion'),
					'rating_high' => __('High Rating First', 'blocksy-companion'),
					'most_relevant' => __('Most Relevant', 'blocksy-companion'),
				]
			),
			'sync' => blocksy_sync_whole_page([
				'prefix' => 'product',
				'loader_selector' => '.woocommerce-Reviews'
			]),
		],

		'woo_advanced_reviews_summary' => [
			'label' => __('Average Score', 'blocksy-companion'),
			'desc' => __('Display an average score for all reviews.', 'blocksy-companion'),
			'type' => 'ct-switch',
			'value' => 'no',
			'divider' => 'top:full',
			'sync' => blocksy_sync_whole_page([
				'prefix' => 'product',
				'loader_selector' => '.woocommerce-Reviews'
			]),
		],

		'woo_advanced_reviews_title' => [
			'label' => __('Review Title', 'blocksy-companion'),
			'desc' => __('Allow users to add a title when leaving a review.', 'blocksy-companion'),
			'type' => 'ct-switch',
			'value' => 'no',
			'divider' => 'top:full',
			'sync' => blocksy_sync_whole_page([
				'prefix' => 'product',
				'loader_selector' => '.woocommerce-Reviews'
			]),
		],

		'woo_advanced_reviews_images' => [
			'label' => __('Image Upload', 'blocksy-companion'),
			'desc' => __('Allow users to upload images when leaving a review.', 'blocksy-companion'),
			'type' => 'ct-switch',
			'value' => 'no',
			'divider' => 'top:full',
			'sync' => blocksy_sync_whole_page([
				'prefix' => 'product',
				'loader_selector' => '.woocommerce-Reviews'
			]),
		],

		blocksy_rand_md5() => [
			'type' => 'ct-condition',
			'condition' => [ 'woo_advanced_reviews_images' => 'yes' ],
			'options' => [

				'woo_advanced_reviews_lightbox' => [
					'label' => __('Image Lightbox', 'blocksy-companion'),
					'desc' => __('Allow users to open attached review images in lightbox.', 'blocksy-companion'),
					'type' => 'ct-switch',
					'value' => 'no',
					'divider' => 'top',
					'sync' => blocksy_sync_whole_page([
						'prefix' => 'product',
						'loader_selector' => '.woocommerce-Reviews'
					]),
				],

			],
		],

		'woo_advanced_reviews_votes' => [
			'label' => __('Review Voting', 'blocksy-companion'),
			'desc' => __('Allow users to upvote reviews.', 'blocksy-companion'),
			'type' => 'ct-switch',
			'value' => 'no',
			'divider' => 'top:full',
			'sync' => blocksy_sync_whole_page([
				'prefix' => 'product',
				'loader_selector' => '.woocommerce-Reviews'
			]),
		],

		blocksy_rand_md5() => [
			'type' => 'ct-condition',
			'condition' => [ 'woo_advanced_reviews_votes' => 'yes' ],
			'options' => [

				'woo_advanced_reviews_votes_allowed_roles' => [
					'label' => __( 'Allowed Users', 'blocksy-companion' ),
					'desc' => __( 'Set which users are allowed to vote.', 'blocksy-companion' ),
					'type' => 'ct-checkboxes',
					'design' => 'block',
					'view' => 'text',
					'divider' => 'top',
					'value' => [
						'logged_in' => true,
						'logged_out' => false,
					],
					'choices' => blocksy_ordered_keys([
						'logged_in' => __( 'Logged In', 'blocksy-companion' ),
						'logged_out' => __( 'Logged Out', 'blocksy-companion' ),
					]),
					'sync' => blocksy_sync_whole_page([
						'prefix' => 'product',
						'loader_selector' => '.woocommerce-Reviews'
					]),
				],

			],
		],

	],
];