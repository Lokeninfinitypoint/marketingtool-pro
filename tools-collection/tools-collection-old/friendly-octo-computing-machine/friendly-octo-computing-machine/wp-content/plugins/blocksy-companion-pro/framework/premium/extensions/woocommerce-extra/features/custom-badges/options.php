<?php

$options = [
	'woo_has_new_custom_badge' => [
		'label' => __( 'New Badge', 'blocksy-companion' ),
		'type' => 'ct-checkboxes',
		'design' => 'block',
		'view' => 'text',
		'allow_empty' => true,
		'value' => [
			'archive' => false,
			'single' => false,
		],
		'divider' => 'top:full',
		'choices' => blocksy_ordered_keys([
			'archive' => __( 'Archive', 'blocksy-companion' ),
			'single' => __( 'Single', 'blocksy-companion' ),
		]),
	],

	blocksy_rand_md5() => [
		'type' => 'ct-condition',
		'condition' => [
			'any' => [
				'woo_has_new_custom_badge/archive' => true,
				'woo_has_new_custom_badge/single' => true,
			]
		],
		'options' => [

			'woo_has_new_custom_badge_label' => [
				'label' => __( 'Badge Label', 'blocksy-companion' ),
				'type' => 'text',
				'design' => 'block',
				'value' => __('NEW', 'blocksy-companion'),
				'setting' => [ 'transport' => 'postMessage' ],
			],

			'woo_has_new_custom_badge_duration' => [
				'label' => __( 'Label Duration', 'blocksy-companion' ),
				'type' => 'ct-number',
				'design' => 'inline',
				'value' => 14,
				'min' => 1,
				'max' => 500,
				'desc' => __( 'How many days the products will be marked as "New" after creation.', 'blocksy-companion' ),
				'setting' => [ 'transport' => 'postMessage' ],
			],
		]
	],

	'woo_has_featured_custom_badge' => [
		'label' => __( 'Featured Badge', 'blocksy-companion' ),
		'type' => 'ct-checkboxes',
		'design' => 'block',
		'view' => 'text',
		'allow_empty' => true,
		'value' => [
			'archive' => false,
			'single' => false,
		],
		'divider' => 'top:full',
		'choices' => blocksy_ordered_keys([
			'archive' => __( 'Archive', 'blocksy-companion' ),
			'single' => __( 'Single', 'blocksy-companion' ),
		]),
	],

	blocksy_rand_md5() => [
		'type' => 'ct-condition',
		'condition' => [
			'any' => [
				'woo_has_featured_custom_badge/archive' => true,
				'woo_has_featured_custom_badge/single' => true,
			]
		],
		'options' => [
			'woo_has_featured_custom_badge_label' => [
				'label' => __( 'Badge Label', 'blocksy-companion' ),
				'type' => 'text',
				'design' => 'block',
				'value' => __('HOT', 'blocksy-companion'),
				'setting' => [ 'transport' => 'postMessage' ],
			],
		]
	],
];