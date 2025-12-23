<?php

if (isset($prefix)) {
	$prefix .= '_';
} else {
	$prefix = '';
}

$additional_options = [];

if (
	class_exists('TRP_Translate_Press')
	&&
	$prefix !== 'dropdown_'
) {	
	$additional_options[] = [
		$prefix . 'ls_flag_aspect_ratio' => [
			'label' => __('Flag Shape', 'blocksy-companion'),
			'type' => 'ct-radio',
			'value' => '4x3',
			'design' => 'block',
			'divider' => 'top',
			'setting' => [ 'transport' => 'postMessage' ],
			'choices' => [
				'4x3' => __( 'Rectangle', 'blocksy-companion' ),
				'1x1' => __( 'Square', 'blocksy-companion' ),
			],
		],
	];
}

$common_options = [
	$prefix . 'language_type' => [
		'label' => __( 'Display Type', 'blocksy-companion' ),
		'type' => 'ct-checkboxes',
		'design' => 'block',
		'view' => 'text',
		'divider' => 'top',
		'value' => [
			'icon' => true,
			'label' => true,
		],

		'choices' => blocksy_ordered_keys([
			'icon' => __( 'Flag', 'blocksy-companion' ),
			'label' => __( 'Label', 'blocksy-companion' ),
		]),

		'sync' => [
			'id' => $sync_id
		]
	],

	blocksy_rand_md5() => [
		'type' => 'ct-condition',
		'condition' => [
			$prefix . 'language_type/icon' => true
		],
		'options' => $additional_options,
	],

	blocksy_rand_md5() => [
		'type' => 'ct-condition',
		'condition' => [
			$prefix . 'language_type/label' => true
		],
		'options' => [
			$prefix . 'language_label' => [
				'label' => __( 'Label Style', 'blocksy-companion' ),
				'type' => 'ct-radio',
				'value' => 'long',
				'view' => 'text',
				'design' => 'block',
				'divider' => 'top',
				'choices' => [
					'long' => __( 'Long', 'blocksy-companion' ),
					'short' => __( 'Short', 'blocksy-companion' ),
				],
				'sync' => [
					'id' => $sync_id
				]
			],

			$prefix . 'language_label_position' => [
				'type' => 'ct-radio',
				'label' => __( 'Label Position', 'blocksy-companion' ),
				'value' => 'right',
				'view' => 'text',
				'divider' => 'top',
				'design' => 'block',
				'responsive' => [ 'tablet' => 'skip' ],
				'choices' => [
					'left' => __( 'Left', 'blocksy-companion' ),
					'right' => __( 'Right', 'blocksy-companion' ),
					'bottom' => __( 'Bottom', 'blocksy-companion' ),
				],
			],
		],
	],

];

$design_options = [

	blocksy_rand_md5() => [
		'type' => 'ct-condition',
		'condition' => [ $prefix . 'language_type/label' => true ],
		'options' => [

			'ls_font' => [
				'type' => 'ct-typography',
				'label' => __('Font', 'blocksy-companion'),
				'value' => blocksy_typography_default_values([
					'size' => '12px',
					'variation' => 'n6',
					'text-transform' => 'uppercase',
				])
			],

			blocksy_rand_md5() => [
				'type' => 'ct-labeled-group',
				'label' => __('Font Color', 'blocksy-companion'),
				'divider' => 'bottom',
				'responsive' => true,
				'choices' => [
					[
						'id' => 'ls_label_color',
						'label' => __('Default State', 'blocksy-companion')
					],

					[
						'id' => 'transparent_ls_label_color',
						'label' => __('Transparent State', 'blocksy-companion'),
						'condition' => [
							'row' => '!offcanvas',
							'builderSettings/has_transparent_header' => 'yes',
						],
					],

					[
						'id' => 'sticky_ls_label_color',
						'label' => __('Sticky State', 'blocksy-companion'),
						'condition' => [
							'row' => '!offcanvas',
							'builderSettings/has_sticky_header' => 'yes',
						],
					],
				],
				'options' => [

					'ls_label_color' => [
						'label' => __('Font Color', 'blocksy-companion'),
						'type'  => 'ct-color-picker',
						'design' => 'block:right',
						'responsive' => true,

						'value' => [
							'default' => [
								'color' => 'var(--theme-text-color)',
							],

							'hover' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],
						],

						'pickers' => [
							[
								'title' => __('Initial', 'blocksy-companion'),
								'id' => 'default',
							],

							[
								'title' => __('Hover/Active', 'blocksy-companion'),
								'id' => 'hover',
								'inherit' => 'var(--theme-link-hover-color)'
							],
						],
					],

					'transparent_ls_label_color' => [
						'label' => __('Font Color', 'blocksy-companion'),
						'type'  => 'ct-color-picker',
						'design' => 'block:right',
						'responsive' => true,

						'value' => [
							'default' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],

							'hover' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],
						],

						'pickers' => [
							[
								'title' => __('Initial', 'blocksy-companion'),
								'id' => 'default',
							],

							[
								'title' => __('Hover/Active', 'blocksy-companion'),
								'id' => 'hover',
							],
						],
					],

					'sticky_ls_label_color' => [
						'label' => __('Font Color', 'blocksy-companion'),
						'type'  => 'ct-color-picker',
						'design' => 'block:right',
						'responsive' => true,

						'value' => [
							'default' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],

							'hover' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],
						],

						'pickers' => [
							[
								'title' => __('Initial', 'blocksy-companion'),
								'id' => 'default',
							],

							[
								'title' => __('Hover/Active', 'blocksy-companion'),
								'id' => 'hover',
							],
						],
					],

				],
			],

		],
	],

	blocksy_rand_md5() => [
		'type' => 'ct-condition',
		'condition' => [ $prefix . 'language_type/icon' => true ],
		'options' => [

			'ls_border_radius' => [
				'label' => __('Flag Border Radius', 'blocksy-companion'),
				'type' => 'ct-spacing',
				'divider' => 'bottom',
				'setting' => ['transport' => 'postMessage'],
				'value' => blocksy_spacing_value(),
				'min' => 0,
				'responsive' => true
			],

		],
	],

	'ls_margin' => [
		'label' => __('Margin', 'blocksy-companion'),
		'type' => 'ct-spacing',
		// 'divider' => 'top',
		'value' => blocksy_spacing_value(),
		'responsive' => true
	],
];