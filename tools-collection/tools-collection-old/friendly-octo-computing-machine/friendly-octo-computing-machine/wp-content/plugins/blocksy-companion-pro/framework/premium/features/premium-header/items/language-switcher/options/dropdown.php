<?php

$top_level_common_options = blocksy_get_variables_from_file(
	dirname(__FILE__) . '/common.php',
	[
		'common_options' => [],
		'design_options' => []
	],
	[
		'sync_id' => $sync_id
	]
);

$dropdown_options = blocksy_get_variables_from_file(
	dirname(__FILE__) . '/common.php',
	[
		'common_options' => [],
	],
	[
		'sync_id' => $sync_id,
		'prefix' => 'dropdown'
	],
	false
);

$general_options = [
	$top_level_common_options['common_options']
];

$design_options = $top_level_common_options['design_options'];

$bottom_options = [
	blocksy_rand_md5() => [
		'type' => 'ct-title',
		'label' => __('Dropdown Options', 'blocksy-companion'),
	],

	blocksy_rand_md5() => [
		'title' => __('General', 'blocksy-companion'),
		'type' => 'tab',
		'options' => [

			$dropdown_options['common_options'],

			'ls_dropdown_offset' => [
				'label' => __('Dropdown Top Offset', 'blocksy-companion'),
				'type' => 'ct-slider',
				'value' => 15,
				'min' => 0,
				'max' => 50,
				'divider' => 'top',
			],

			'ls_dropdown_items_spacing' => [
				'label' => __('Items Vertical Spacing', 'blocksy-companion'),
				'type' => 'ct-slider',
				'value' => 15,
				'min' => 5,
				'max' => 30,
			],

			'ls_dropdown_arrow' => [
				'label' => __('Dropdown Arrow', 'blocksy-companion'),
				'type' => 'ct-switch',
				'value' => 'no',
				'divider' => 'top',
				'sync' => [
					'id' => $sync_id
				]
			],

		],
	],

	blocksy_rand_md5() => [
		'title' => __('Design', 'blocksy-companion'),
		'type' => 'tab',
		'options' => [
			'ls_dropdown_font' => [
				'type' => 'ct-typography',
				'label' => __('Font', 'blocksy-companion'),
				'value' => blocksy_typography_default_values(),
				'setting' => [ 'transport' => 'postMessage' ],
			],

			'ls_dropdown_font_color' => [
				'label' => __('Font Color', 'blocksy-companion'),
				'type'  => 'ct-color-picker',
				'design' => 'inline',
				'divider' => 'bottom',

				'value' => [
					'default' => [
						'color' => '#ffffff',
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
						'title' => __('Hover', 'blocksy-companion'),
						'id' => 'hover',
						'inherit' => 'var(--theme-link-hover-color)'
					],
				],
			],

			'ls_dropdown_background' => [
				'label' => __('Background Color', 'blocksy-companion'),
				'type'  => 'ct-color-picker',
				'design' => 'inline',
				'divider' => 'bottom',

				'value' => [
					'default' => [
						'color' => '#29333C',
					],
				],

				'pickers' => [
					[
						'title' => __('Initial', 'blocksy-companion'),
						'id' => 'default',
					],
				],
			],

			'ls_dropdown_divider' => [
				'label' => __('Items Divider', 'blocksy-companion'),
				'type' => 'ct-border',
				'design' => 'inline',
				'divider' => 'bottom',
				'value' => [
					'width' => 1,
					'style' => 'dashed',
					'color' => [
						'color' => 'rgba(255, 255, 255, 0.1)',
					],
				]
			],

			'ls_dropdown_shadow' => [
				'label' => __('Shadow', 'blocksy-companion'),
				'type' => 'ct-box-shadow',
				'design' => 'inline',
				// 'responsive' => true,
				'divider' => 'bottom',
				'value' => blocksy_box_shadow_value([
					'enable' => true,
					'h_offset' => 0,
					'v_offset' => 10,
					'blur' => 20,
					'spread' => 0,
					'inset' => false,
					'color' => [
						'color' => 'rgba(41, 51, 61, 0.1)',
					],
				])
			],

			'ls_dropdown_radius' => [
				'label' => __('Border Radius', 'blocksy-companion'),
				'type' => 'ct-spacing',
				'value' => blocksy_spacing_value(),
				'inputAttr' => [
					'placeholder' => '2'
				],
				'min' => 0,
			],

		],
	],
];
