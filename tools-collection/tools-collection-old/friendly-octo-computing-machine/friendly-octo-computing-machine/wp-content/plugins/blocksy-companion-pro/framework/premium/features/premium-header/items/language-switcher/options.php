<?php

$sync_id = 'header_placements_item:language-switcher';

if (isset($panel_type) && $panel_type === 'footer') {
	$sync_id = 'footer_placements_item:language-switcher';
}

if (! isset($panel_type)) {
	$panel_type = 'header';
}

$current_plugin = null;

if (function_exists('icl_object_id') && function_exists('icl_disp_language')) {
	$current_plugin = 'wpml';
}

if (function_exists('pll_the_languages')) {
	$current_plugin = 'polylang';
}

if (class_exists('TRP_Translate_Press')) {
	$current_plugin = 'translate-press';
}

if (function_exists('weglot_get_current_language')) {
	$current_plugin = 'weglot';
}

$hide_missing_language = [];

if ($current_plugin === 'wpml' || $current_plugin === 'polylang') {
	$hide_missing_language = [
		'hide_missing_language' => [
			'label' => __('Hide Missing Language', 'blocksy-companion'),
			'type' => 'ct-switch',
			'design' => 'inline',
			'divider' => 'top',
			'disableRevertButton' => true,
			'value' => 'no',
			'sync' => [
				'id' => $sync_id
			]
		],
	];
}

$inline_options = blc_theme_functions()->blocksy_get_variables_from_file(
	dirname(__FILE__) . '/options/inline.php',
	[
		'general_options' => [],
		'design_options' => []
	],

	[
		'sync_id' => $sync_id
	]
);

$dropdown_options = blc_theme_functions()->blocksy_get_variables_from_file(
	dirname(__FILE__) . '/options/dropdown.php',
	[
		'general_options' => [],
		'design_options' => [],
		'bottom_options' => [],
	],

	[
		'sync_id' => $sync_id
	]
);

$general_options = [];
$design_options = [];
$bottom_options = [];

if ($panel_type === 'footer') {
	$general_options = [
		$inline_options['general_options'],
		$hide_missing_language,

		[
			blocksy_rand_md5() => [
				'type' => 'ct-divider',
			],

			'footer_ls_horizontal_alignment' => [
				'type' => 'ct-radio',
				'label' => __( 'Horizontal Alignment', 'blocksy-companion' ),
				'view' => 'text',
				'design' => 'block',
				'responsive' => true,
				'attr' => [ 'data-type' => 'alignment' ],
				'value' => 'CT_CSS_SKIP_RULE',
				'choices' => [
					'flex-start' => '',
					'center' => '',
					'flex-end' => '',
				],
			],

			'footer_ls_vertical_alignment' => [
				'type' => 'ct-radio',
				'label' => __( 'Vertical Alignment', 'blocksy-companion' ),
				'view' => 'text',
				'design' => 'block',
				'divider' => 'top',
				'responsive' => true,
				'attr' => [ 'data-type' => 'vertical-alignment' ],
				'value' => 'CT_CSS_SKIP_RULE',
				'choices' => [
					'flex-start' => '',
					'center' => '',
					'flex-end' => '',
				],
			],

			'footer_visibility' => [
				'label' => __('Element Visibility', 'blocksy-companion'),
				'type' => 'ct-visibility',
				'design' => 'block',
				'divider' => 'top',
				'sync' => 'live',
				'value' => blocksy_default_responsive_value([
					'desktop' => true,
					'tablet' => true,
					'mobile' => true,
				]),
				'choices' => blocksy_ordered_keys([
					'desktop' => __( 'Desktop', 'blocksy-companion' ),
					'tablet' => __( 'Tablet', 'blocksy-companion' ),
					'mobile' => __( 'Mobile', 'blocksy-companion' ),
				]),
			],

		]
	];

	$design_options = [
		$inline_options['design_options'],
	];
}

if ($panel_type === 'header') {
	$general_options = [
		[
			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => ['row' => 'offcanvas'],
				'options' => $inline_options['general_options']
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => ['row' => '!offcanvas'],
				'options' => [
					'ls_type' => [
						'label' => false,
						'type' => $panel_type === 'header' ? 'ct-image-picker' : 'hidden',
						'value' => 'inline',
						'choices' => [
							'inline' => [
								'src' => blocksy_image_picker_url('ls-inline.svg'),
								'title' => __('Inline', 'blocksy-companion'),
							],

							'dropdown' => [
								'src' => blocksy_image_picker_url('ls-dropdown.svg'),
								'title' => __('Dropdown', 'blocksy-companion'),
							],
						],

						'sync' => [
							'id' => $sync_id
						]
					],

					blocksy_rand_md5() => [
						'type' => 'ct-condition',
						'condition' => [ 'ls_type' => 'inline' ],
						'options' => $inline_options['general_options'],
					],

					blocksy_rand_md5() => [
						'type' => 'ct-condition',
						'condition' => ['ls_type' => 'dropdown'],
						'options' => $dropdown_options['general_options']
					],
				]
			],

		],

		$hide_missing_language
	];

	$design_options = [

		blocksy_rand_md5() => [
			'type' => 'ct-condition',
			'condition' => ['row' => 'offcanvas'],
			'options' => $inline_options['design_options']
		],

		blocksy_rand_md5() => [
			'type' => 'ct-condition',
			'condition' => ['row' => '!offcanvas'],
			'options' => [
				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => ['ls_type' => 'inline'],
					'options' => $inline_options['design_options'],
				],

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => ['ls_type' => 'dropdown'],
					'options' => $dropdown_options['design_options']
				],
			]
		],
	];

	$bottom_options = [
		blocksy_rand_md5() => [
			'type' => 'ct-condition',
			'condition' => ['row' => '!offcanvas'],
			'options' => [
				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [ 'ls_type' => 'dropdown' ],
					'options' => [
						$dropdown_options['bottom_options']
					]
				],
			]
		],

		blocksy_rand_md5() => [
			'type' => 'ct-condition',
			'condition' => [
				'wp_customizer_current_view' => 'tablet|mobile'
			],
			'options' => [
				blocksy_rand_md5() => [
					'type' => 'ct-divider',
				],

				'visibility' => [
					'label' => __( 'Element Visibility', 'blocksy-companion' ),
					'type' => 'ct-visibility',
					'design' => 'block',
					'allow_empty' => true,
					'value' => blocksy_default_responsive_value([
						'tablet' => true,
						'mobile' => true,
					]),

					'choices' => blocksy_ordered_keys([
						'tablet' => __( 'Tablet', 'blocksy-companion' ),
						'mobile' => __( 'Mobile', 'blocksy-companion' ),
					]),
				],
			],
		]
	];
}

$options = [
	[
		blocksy_rand_md5() => [
			'type' => 'ct-title',
			'label' => __( 'Top Level Options', 'blocksy-companion' ),
		],

		blocksy_rand_md5() => [
			'title' => __( 'General', 'blocksy-companion' ),
			'type' => 'tab',
			'options' => $general_options,
		],

		blocksy_rand_md5() => [
			'title' => __( 'Design', 'blocksy-companion' ),
			'type' => 'tab',
			'options' => $design_options
		],
	],

	$bottom_options
];
