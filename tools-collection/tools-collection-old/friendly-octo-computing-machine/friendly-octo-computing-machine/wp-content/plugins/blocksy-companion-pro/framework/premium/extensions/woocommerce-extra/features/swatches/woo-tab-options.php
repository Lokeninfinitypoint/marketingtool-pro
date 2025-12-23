<?php

if (! isset($option_design)) {
	$option_design = false;
}

$color_options = [
	'color_type' => [
		'label' => __('Color Mode', 'blocksy-companion'),
		'type' => 'ct-radio',
		'view' => 'text',
		'value' => 'simple',
		'design' => $option_design ? 'inline:start' : 'inline',
		'sync' => 'live',
		'setting' => [ 'transport' => 'postMessage' ],
		'choices' => [
			'simple' => __('One Color', 'blocksy-companion'),
			'dual' => __( 'Dual Color', 'blocksy-companion' ),
		]
	],

	'accent_color' => [
		'label' => [
			__('Color', 'blocksy-companion') => [
				'color_type' => 'simple'
			],

			__('Colors', 'blocksy-companion') => [
				'color_type' => 'dual'
			]
		],
		'type' => 'ct-color-picker',
		'design' => $option_design ? 'inline:start' : 'inline',
		'value' => [
			'default' => [
				'color' => 'CT_CSS_SKIP_RULE',
			],

			'secondary' => [
				'color' => 'CT_CSS_SKIP_RULE',
			],
		],
		'pickers' => [
			[
				'title' => [
					__('Color', 'blocksy-companion') => [
						'color_type' => 'simple'
					],

					__('Color 1', 'blocksy-companion') => [
						'color_type' => 'dual'
					]
				],
				'id' => 'default',
			],

			[
				'title' => __('Color 2', 'blocksy-companion'),
				'id' => 'secondary',
				'condition' => [ 'color_type' => 'dual' ]
			],
		]
	],
];

$image_options = [
	'image' => [
		'label' => __('Image', 'blocksy-companion' ),
		'type' => 'ct-image-uploader',
		'design' => $option_design ? 'inline:start' : 'inline',
		'value' => '',
		'emptyLabel' => __('Select Image', 'blocksy-companion'),
	]
];

$button_options = [
	'short_name' => [
		'label' => __('Short Name', 'blocksy-companion'),
		'type' => 'text',
		'design' => $option_design ? 'inline:start' : 'inline',
		'value' => '',
		// 'desc' => __('This will be used as a label for the swatch.', 'blocksy-companion'),
	]
];

$tooltip_options = [
	'tooltip_type' => [
		'label' => __('Tooltip', 'blocksy-companion'),
		'type' => 'ct-radio',
		'view' => 'text',
		'value' => 'default',
		'divider' => $option_design ? 'top:full' : 'top',
		'design' => $option_design ? 'inline:start' : 'inline',
		'sync' => 'live',
		'setting' => [ 'transport' => 'postMessage' ],
		'choices' => [
			'none' => __('None', 'blocksy-companion'),
			'default' => __( 'Text', 'blocksy-companion' ),
			'image' => __( 'Image', 'blocksy-companion' ),
		]
	],

	blocksy_rand_md5() => [
		'type' => 'ct-condition',
		'condition' => [ 'tooltip_type' => 'default' ],
		'options' => [
			'tooltip_mask' => [
				'label' => __( 'Tooltip Text', 'blocksy-companion' ),
				'type' => 'text',
				'design' => $option_design ? 'inline:start' : 'inline',
				'value' => __('{term_name}', 'blocksy-companion'),
			],
		]
	],

	blocksy_rand_md5() => [
		'type' => 'ct-condition',
		'condition' => [ 'tooltip_type' => 'image' ],
		'options' => [

			'tooltip_image' => [
				'label' => __('Tooltip Image', 'blocksy-companion'),
				'type' => 'ct-image-uploader',
				'design' => $option_design ? 'inline:start' : 'inline',
				'value' => '',
				'emptyLabel' => __('Upload Image', 'blocksy-companion'),
			]

		]
	],
];

$mixed_options = [
	'mixed_subtype' => [
		'label' => __('Subtype', 'blocksy-companion'),
		'type' => 'ct-radio',
		'view' => 'text',
		'design' => $option_design ? 'inline:start' : 'inline',
		'divider' => $option_design ? 'bottom:full' : 'bottom',
		'value' => 'color',
		'choices' => [
			'color' => __('Color', 'blocksy-companion'),
			'image' => __('Image', 'blocksy-companion'),
		]
	],

	blocksy_rand_md5() => [
		'type' => 'ct-condition',
		'condition' => [ 'mixed_subtype' => 'color' ],
		'options' => [

			$color_options

		],
	],

	blocksy_rand_md5() => [
		'type' => 'ct-condition',
		'condition' => [ 'mixed_subtype' => 'image' ],
		'options' => [

			$image_options

		],
	],
];

$inherit_options = [
	'message' => [
		'type' => 'ct-notification',
		'text' => sprintf(
			'This attribute is set to inherit the global settings, you can edit it from <a href="%s" target="_blank">%s</a> or change the type and edit it locally.',
			admin_url('term.php?taxonomy={attribute_slug}&post_type=product&tag_ID={tag_id}'),
			__('here', 'blocksy-companion')
		),
	]
];