<?php
// Options cloned from Swatches: you can change

$options = [
	'label' => __('Size Guides', 'blocksy-companion'),
	'type' => 'ct-panel',
	'setting' => ['transport' => 'postMessage'],
	'inner-options' => [

		blocksy_rand_md5() => [
			'title' => __( 'General', 'blocksy-companion' ),
			'type' => 'tab',
			'options' => [

				'size_guide_placement' => [
					'label' => __('Size Guide Placement', 'blocksy-companion'),
					'type' => 'ct-radio',
					'value' => 'modal',
					'view' => 'text',
					'design' => 'block',
					'choices' => [
						'modal' => __('Popup', 'blocksy-companion'),
						'panel' => __('Side Panel', 'blocksy-companion'),
					],
				],

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [ 'size_guide_placement' => 'panel' ],
					'options' => [

						'size_guide_side_panel_position' => [
							'label' => __('Reveal From', 'blocksy-companion'),
							'type' => 'ct-radio',
							'value' => 'right',
							'view' => 'text',
							'design' => 'block',
							'choices' => [
								'left' => __( 'Left Side', 'blocksy-companion' ),
								'right' => __( 'Right Side', 'blocksy-companion' ),
							],
						],

						'size_guide_side_panel_width' => [
							'label' => __( 'Panel Width', 'blocksy-companion' ),
							'type' => 'ct-slider',
							'value' => [
								'desktop' => '700px',
								'tablet' => '65vw',
								'mobile' => '90vw',
							],
							'units' => blocksy_units_config([
								[ 'unit' => 'px', 'min' => 0, 'max' => 1000 ],
							]),
							'responsive' => true,
							'divider' => 'top',
							'setting' => [ 'transport' => 'postMessage' ],
						],

					],
				],
			],
		],

		blocksy_rand_md5() => [
			'title' => __( 'Design', 'blocksy-companion' ),
			'type' => 'tab',
			'options' => [

				'size_guide_modal_background' => [
					'label' => [
						__('Popup Background', 'blocksy-companion') => [
							'size_guide_placement' => 'modal'
						],

						__('Panel Background', 'blocksy-companion') => [
							'size_guide_placement' => 'panel'
						]
					],
					'type'  => 'ct-background',
					'design' => 'block:right',
					'responsive' => true,
					'divider' => 'top',
					'setting' => [ 'transport' => 'postMessage' ],
					'value' => blocksy_background_default_value([
						'backgroundColor' => [
							'default' => [
								'color' => 'var(--theme-palette-color-8)'
							],
						],
					])
				],

				'size_guide_modal_backdrop' => [
					'label' => [
						__('Popup Backdrop', 'blocksy-companion') => [
							'size_guide_placement' => 'modal'
						],

						__('Panel Backdrop', 'blocksy-companion') => [
							'size_guide_placement' => 'panel'
						]
					],
					'type'  => 'ct-background',
					'design' => 'block:right',
					'responsive' => true,
					'divider' => 'top',
					'setting' => [ 'transport' => 'postMessage' ],
					'value' => blocksy_background_default_value([
						'backgroundColor' => [
							'default' => [
								'color' => 'rgba(18, 21, 25, 0.8)'
							],
						],
					])
				],

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [ 'size_guide_placement' => 'modal' ],
					'options' => [

						'size_guide_modal_shadow' => [
							'label' => __( 'Popup Shadow', 'blocksy-companion' ),
							'type' => 'ct-box-shadow',
							'responsive' => true,
							'divider' => 'top:full',
							'sync' => 'live',
							'value' => blocksy_box_shadow_value([
								'enable' => true,
								'h_offset' => 0,
								'v_offset' => 50,
								'blur' => 100,
								'spread' => 0,
								'inset' => false,
								'color' => [
									'color' => 'rgba(18, 21, 25, 0.5)',
								],
							])
						],

						'size_guide_modal_radius' => [
							'label' => __( 'Popup Border Radius', 'blocksy-companion' ),
							'type' => 'ct-spacing',
							'divider' => 'top',
							'setting' => [ 'transport' => 'postMessage' ],
							'value' => blocksy_spacing_value(),
							'inputAttr' => [
								'placeholder' => '7'
							],
							'min' => 0,
							'responsive' => true
						],

					],
				],

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [ 'size_guide_placement' => 'panel' ],
					'options' => [

						'size_guide_panel_shadow' => [
							'label' => __( 'Panel Shadow', 'blocksy-companion' ),
							'type' => 'ct-box-shadow',
							'design' => 'block',
							'responsive' => true,
							'divider' => 'top',
							'value' => blocksy_box_shadow_value([
								'enable' => true,
								'h_offset' => 0,
								'v_offset' => 0,
								'blur' => 70,
								'spread' => 0,
								'inset' => false,
								'color' => [
									'color' => 'rgba(0, 0, 0, 0.35)',
								],
							])
						],

					],
				],

				'size_guide_close_button_type' => [
					'label' => __('Close Button Type', 'blocksy-companion'),
					'type' => 'ct-select',
					'value' => 'type-1',
					'view' => 'text',
					'design' => 'inline',
					'divider' => 'top:full',
					'setting' => [ 'transport' => 'postMessage' ],
					'choices' => blocksy_ordered_keys(
						[
							'type-1' => __( 'Simple', 'blocksy-companion' ),
							'type-2' => __( 'Border', 'blocksy-companion' ),
							'type-3' => __( 'Background', 'blocksy-companion' ),
						]
					),
				],

				'size_guide_close_button_icon_size' => [
					'label' => __( 'Icon Size', 'blocksy-companion' ),
					'type' => 'ct-number',
					'design' => 'inline',
					'value' => 12,
					'min' => 5,
					'max' => 50,
					'divider' => 'top',
					'setting' => [ 'transport' => 'postMessage' ],
				],

				'size_guide_close_button_color' => [
					'label' => __( 'Icon Color', 'blocksy-companion' ),
					'type'  => 'ct-color-picker',
					'design' => 'block',
					'divider' => 'top',
					'responsive' => true,
					'setting' => [ 'transport' => 'postMessage' ],
					'value' => [
						'default' => [
							'color' => 'rgba(0, 0, 0, 0.5)',
						],

						'hover' => [
							'color' => 'rgba(0, 0, 0, 0.8)',
						],
					],

					'pickers' => [
						[
							'title' => __( 'Initial', 'blocksy-companion' ),
							'id' => 'default',
						],

						[
							'title' => __( 'Hover', 'blocksy-companion' ),
							'id' => 'hover',
						],
					],
				],

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [ 'size_guide_close_button_type' => 'type-2' ],
					'options' => [

						'size_guide_close_button_border_color' => [
							'label' => __( 'Border Color', 'blocksy-companion' ),
							'type'  => 'ct-color-picker',
							'design' => 'block',
							'divider' => 'top',
							'responsive' => true,
							'setting' => [ 'transport' => 'postMessage' ],

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
									'title' => __( 'Initial', 'blocksy-companion' ),
									'id' => 'default',
									'inherit' => 'rgba(0, 0, 0, 0.5)'
								],

								[
									'title' => __( 'Hover', 'blocksy-companion' ),
									'id' => 'hover',
									'inherit' => 'rgba(0, 0, 0, 0.5)'
								],
							],
						],

					],
				],

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [ 'size_guide_close_button_type' => 'type-3' ],
					'options' => [

						'size_guide_close_button_shape_color' => [
							'label' => __( 'Background Color', 'blocksy-companion' ),
							'type'  => 'ct-color-picker',
							'design' => 'block',
							'divider' => 'top',
							'responsive' => true,
							'setting' => [ 'transport' => 'postMessage' ],

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
									'title' => __( 'Initial', 'blocksy-companion' ),
									'id' => 'default',
									'inherit' => 'rgba(0, 0, 0, 0.5)'
								],

								[
									'title' => __( 'Hover', 'blocksy-companion' ),
									'id' => 'hover',
									'inherit' => 'rgba(0, 0, 0, 0.5)'
								],
							],
						],

					],
				],

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [ 'size_guide_close_button_type' => '!type-1' ],
					'options' => [

						'size_guide_close_button_border_radius' => [
							'label' => __( 'Border Radius', 'blocksy-companion' ),
							'type' => 'ct-number',
							'design' => 'inline',
							'value' => 5,
							'min' => 0,
							'max' => 100,
							'divider' => 'top',
							'setting' => [ 'transport' => 'postMessage' ],
						],

					],
				],

			],
		],

	],
];
