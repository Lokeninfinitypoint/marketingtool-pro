<?php

$hooks_manager = new \Blocksy\HooksManager();

$choices = [];

$choices[] = [
	'key' => '',
	'value' => __('None', 'blocksy-companion')
];

foreach ($hooks_manager->get_all_hooks() as $hook) {
	$choices[] = array_merge([
		'key' => $hook['hook'],
		'value' => isset($hook['title']) ? $hook['title'] : $hook['hook']
	], isset($hook['group']) ? [
		'group' => $hook['group']
	] : []);
}

$choices[] = [
	'key' => 'custom_hook',
	'value' => __('Custom Hook', 'blocksy-companion'),
	'group' => __('Other', 'blocksy-companion')
];

$options = [
	[
		'has_inline_code_editor' => [
			// 'type' => 'ct-switch',
			'type' => 'hidden',
			'value' => 'no'
		],

		'conditions' => [
			'label' => __('Display Conditions', 'blocksy-companion'),
			'type' => 'blocksy-display-condition',
			'sectionAttr' => [ 'class' => 'ct-content-blocks-conditions' ],
			'display' => 'modal',
			'modalTitle' => __('Content Block Display Conditions', 'blocksy-companion'),
			'modalDescription' => __('Choose where you want this content block to be displayed.', 'blocksy-companion'),

			'value' => [
				[
					'type' => 'include',
					'rule' => 'singulars',
					'payload' => []
				]
			],

			'filter' => 'content_block_hook',

			'value' => [],
			'design' => 'block',
			'divider' => 'bottom:full',
		],

		blocksy_rand_md5() => [
			'type' => 'ct-group',
			'label' => __( 'Location & Priority', 'blocksy-companion' ),
			'attr' => [ 'class' => 'ct-condition-location' ],
			'hasGroupRevertButton' => true,
			'options' => [

				blocksy_rand_md5() => [
					'type' => 'ct-group',
					'label' => false,
					'options' => [

						'location' => [
							'label' => false,
							'type' => 'blocksy-hooks-select',
							'value' => '',
							'design' => 'none',
							'defaultToFirstItem' => true,
							'choices' => $choices,
							'placeholder' => __('None', 'blocksy-companion'),
							'search' => true
						],

						'priority' => [
							'label' => false,
							'type' => 'ct-number',
							'value' => 10,
							'min' => 1,
							'max' => 100,
							'design' => 'none',
							'attr' => [ 'data-width' => 'full' ],
						],

					],

				],

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [ 'location' => 'custom_hook' ],
					'options' => [
						'custom_location' => [
							'label' => __('Custom Hook', 'blocksy-companion'),
							'type' => 'text',
							'value' => '',
							// 'divider' => 'bottom',
							'wrapperAttr' => [ 'data-location' => 'custom-hook' ],
						],
					]
				],

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [
						'location' => 'blocksy:single:content:paragraphs-number',
					],
					'options' => [
						'paragraphs_count' => [
							'label' => __('After Block Number', 'blocksy-companion'),
							'type' => 'ct-number',
							'value' => '3',
							'design' => 'inline',
							// 'divider' => 'bottom',
							// 'wrapperAttr' => [ 'data-location' => 'block' ],
							'attr' => [ 'data-width' => 'full' ],
						],
					]
				],

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [
						'location' => 'blocksy:single:content:headings-number',
					],
					'options' => [
						'headings_count' => [
							'label' => __('Before Heading Number', 'blocksy-companion'),
							'type' => 'text',
							'value' => '3',
							'design' => 'inline',
							'attr' => [ 'data-width' => 'full' ],
						],
					]
				],

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [
						'location' => 'blocksy:loop:card:cards-number',
					],
					'options' => [
						'cards_count' => [
							'label' => __('After Card Number', 'blocksy-companion'),
							'type' => 'ct-number',
							'value' => '3',
							'design' => 'inline',
							'attr' => [ 'data-width' => 'full' ],
						],

						'repeat_for_every_card' => [
							'label' => __('Repeat', 'blocksy-companion'),
							'type' => 'ct-switch',
							'value' => 'no',
							'design' => 'inline',
							'wrapperAttr' => [ 'data-location' => 'block' ],
						],
					]
				],

				'additional_locations' => [
					'type' => 'blocksy-multiple-locations-select',
					'choices' => $choices,
					'design' => 'none',
					'value' => []
				],

			],
		],

		blocksy_rand_md5() => [
			'type' => 'ct-condition',
			'condition' => [ 'has_inline_code_editor' => 'no' ],
			'options' => [
				'has_content_block_structure' => [
					'label' => __( 'Container Structure', 'blocksy-companion' ),
					'type' => 'ct-radio',
					'value' => 'no',
					'view' => 'text',
					'design' => 'block',
					'divider' => 'top:full',
					'choices' => [
						'no' => __( 'Default', 'blocksy-companion' ),
						'plain' => __( 'None', 'blocksy-companion' ),
						'yes' => __( 'Custom', 'blocksy-companion' ),
					],
				],
			]
		],

		blocksy_rand_md5() => [
			'type' => 'ct-condition',
			'condition' => [
				'has_inline_code_editor' => 'no',
				'has_content_block_structure' => 'yes'
			],
			'options' => [
				blocksy_rand_md5() => [
					'title' => __( 'General', 'blocksy-companion' ),
					'type' => 'tab',
					'options' => [
						'content_block_structure' => [
							'label' => false,
							'type' => 'ct-image-picker',
							'value' => 'type-4',
							'choices' => [
								'type-3' => [
									'src' => blocksy_image_picker_url('narrow.svg'),
									'title' => __('Narrow Width', 'blocksy-companion'),
								],

								'type-4' => [
									'src' => blocksy_image_picker_url('normal.svg'),
									'title' => __('Normal Width', 'blocksy-companion'),
								],
							],
						],

						'content_style' => [
							'label' => __('Content Area Style', 'blocksy-companion'),
							'type' => 'ct-radio',
							'value' => 'wide',
							'view' => 'text',
							'design' => 'block',
							'divider' => 'top',
							'responsive' => true,
							'choices' => [
								'wide' => __( 'Wide', 'blocksy-companion' ),
								'boxed' => __( 'Boxed', 'blocksy-companion' ),
							],
						],

						'content_block_spacing' => [
							'label' => __('Content Area Vertical Spacing', 'blocksy-companion'),
							'type' => 'ct-radio',
							'value' => 'both',
							'divider' => 'top',
							'view' => 'text',
							'design' => 'block',
							'disableRevertButton' => true,
							'attr' => [ 'data-type' => 'content-spacing' ],
							'choice_attr' => [ 'data-tooltip-reveal' => 'top' ],
							'setting' => [ 'transport' => 'postMessage' ],
							'choices' => [
								'both'   => '<span></span>
								<i class="ct-tooltip">' . __( 'Top & Bottom', 'blocksy-companion' ) . '</i>',

								'top'    => '<span></span>
								<i class="ct-tooltip">' . __( 'Only Top', 'blocksy-companion' ) . '</i>',

								'bottom' => '<span></span>
								<i class="ct-tooltip">' . __( 'Only Bottom', 'blocksy-companion' ) . '</i>',

								'none'   => '<span></span>
								<i class="ct-tooltip">' . __( 'Disabled', 'blocksy-companion' ) . '</i>',
							]
						],

					],
				],

				blocksy_rand_md5() => [
					'title' => __( 'Design', 'blocksy-companion' ),
					'type' => 'tab',
					'options' => [

						blocksy_get_options('single-elements/structure-design', [
							// 'has_background' => false
						])

					]
				],
			]
		],


		blocksy_rand_md5() => [
			'type' => 'ct-condition',
			'condition' => [ 'has_inline_code_editor' => 'no' ],
			'options' => [
				blocksy_rand_md5() => [
					'type' => 'ct-divider',
				],
			]
		],
	],

	blocksy_rand_md5() => [
		'type' => 'ct-condition',
		'condition' => ['has_inline_code_editor' => 'no'],
		'options' => [

			'previewedPost' => [
				'label' => __( 'Dynamic Content Preview', 'blocksy-companion' ),
				'type' => 'blocksy-previewed-post',
				'value' => [
					'post_id' => '',
					'post_type' => 'post'
				],
				'desc' => __('Select a post/page to preview it\'s content inside the editor while building the hook.', 'blocksy-companion'),
			],
		],
	],

	blocksy_rand_md5() => [
		'type' => 'ct-condition',
		'condition' => [
			'has_inline_code_editor' => 'no',
			'has_content_block_structure' => '!plain'
		],
		'options' => [
			blocksy_rand_md5() => [
				'type' => 'ct-divider',
			],

			'visibility' => [
				'label' => __( 'Visibility', 'blocksy-companion' ),
				'type' => 'ct-visibility',
				'design' => 'block',

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

			'custom_class' => [
				'label' => __('Custom Class', 'blocksy-companion'),
				'type' => 'text',
				'value' => '',
				'desc' => __('Separate multiple classes with spaces.', 'blocksy-companion'),
				'divider' => 'top:full',
			],

			'custom_attributes' => [
				'label' => __('Custom Attribute', 'blocksy-companion'),
				'type' => 'text',
				'value' => '',
				'desc' => __('Separate multiple attributes with spaces.', 'blocksy-companion'),
				'divider' => 'top',
			],
		]
	],
];

