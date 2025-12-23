<?php


$options = [
	'label' => __('Affiliate Products', 'blocksy-companion'),
	'type' => 'ct-panel',
	'setting' => ['transport' => 'postMessage'],
	'inner-options' => [

		blocksy_rand_md5() => [
			'type' => 'ct-title',
			'label' => __( 'Product Archive', 'blocksy-companion' ),
		],
		
		'woo_archive_affiliate_image_link' => [
			'label' => __( 'Image Affiliate Link', 'blocksy-companion' ),
			'type' => 'ct-switch',
			'value' => 'no',
		],

		blocksy_rand_md5() => [
			'type' => 'ct-condition',
			'condition' => [ 'woo_archive_affiliate_image_link' => 'yes' ],
			'options' => [

				'woo_archive_affiliate_image_link_new_tab' => [
					'label' => __( 'Open In New Tab', 'blocksy-companion' ),
					'type' => 'ct-switch',
					'value' => 'no',
				],

			],
		],

		'woo_archive_affiliate_title_link' => [
			'label' => __( 'Title Affiliate Link', 'blocksy-companion' ),
			'type' => 'ct-switch',
			'value' => 'no',
			'divider' => 'top',
		],

		blocksy_rand_md5() => [
			'type' => 'ct-condition',
			'condition' => [ 'woo_archive_affiliate_title_link' => 'yes' ],
			'options' => [

				'woo_archive_affiliate_title_link_new_tab' => [
					'label' => __( 'Open In New Tab', 'blocksy-companion' ),
					'type' => 'ct-switch',
					'value' => 'no',
				],

			],
		],

		'woo_archive_affiliate_button_link_new_tab' => [
			'label' => __( 'Open Button Link In New Tab', 'blocksy-companion' ),
			'type' => 'ct-switch',
			'value' => 'no',
			'divider' => 'top',
		],


		blocksy_rand_md5() => [
			'type' => 'ct-title',
			'label' => __( 'Single Product', 'blocksy-companion' ),
		],

		'woo_single_affiliate_image_link' => [
			'label' => __( 'Image Affiliate Link', 'blocksy-companion' ),
			'type' => 'ct-switch',
			'value' => 'no',
		],

		blocksy_rand_md5() => [
			'type' => 'ct-condition',
			'condition' => [ 'woo_single_affiliate_image_link' => 'yes' ],
			'options' => [

				'woo_single_affiliate_image_link_new_tab' => [
					'label' => __( 'Open In New Tab', 'blocksy-companion' ),
					'type' => 'ct-switch',
					'value' => 'no',
				],

			],
		],

		'woo_single_affiliate_button_link_new_tab' => [
			'label' => __( 'Open Button Link In New Tab', 'blocksy-companion' ),
			'type' => 'ct-switch',
			'value' => 'no',
			'divider' => 'top',
		],

	],
];