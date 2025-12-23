<?php

$options = [
	'conditions' => [
		'label' => __('Display Conditions', 'blocksy-companion'),
		'type' => 'blocksy-display-condition',
		'value' => [
			[
				'type' => 'include',
				'rule' => 'everywhere',
			]
		],
		'display' => 'modal',
		'filter' => 'maintenance-mode',
		'modalTitle' => __('Maintenance Block Display Conditions', 'blocksy-companion'),
		'modalDescription' => __('Add one or more conditions to display the Maintenance block.', 'blocksy-companion'),
		'design' => 'block',
	],

	'has_inline_code_editor' => [
		'type' => 'hidden',
		'value' => 'no'
	],

	blocksy_rand_md5() => [
		'type' => 'ct-condition',
		'condition' => [ 'has_inline_code_editor' => 'no' ],
		'options' => [
			'has_content_block_structure' => [
				'label' => __( 'Container Structure', 'blocksy-companion' ),
				'type' => 'hidden',
				'value' => 'yes',
				'design' => 'none'
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
				'type' => 'ct-title',
				'label' => __( 'Page Structure', 'blocksy-companion' ),
			],

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
						'value' => 'none',
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
];

