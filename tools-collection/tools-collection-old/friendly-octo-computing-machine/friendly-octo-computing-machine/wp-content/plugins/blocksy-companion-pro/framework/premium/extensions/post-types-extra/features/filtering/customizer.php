<?php

$taxonomies = array_values(array_diff(
	get_object_taxonomies($post_type),
	['post_format']
));

$taxonomies_choices = [];

foreach ($taxonomies as $taxonomy) {
	$taxonomy_object = get_taxonomy($taxonomy);

	if (! $taxonomy_object) {
		continue;
	}

	if (! $taxonomy_object->public) {
		continue;
	}

	$taxonomies_choices[$taxonomy] = $taxonomy_object->label;
}

$options = [
	'label' => __('Posts Filter', 'blocksy-companion'),
	'type' => 'ct-panel',
	'switch' => true,
	'value' => 'no',
	'sync' => blocksy_sync_whole_page([
		'prefix' => $prefix,
	]),
	'inner-options' => [

		blocksy_rand_md5() => [
			'title' => __( 'General', 'blocksy-companion' ),
			'type' => 'tab',
			'options' => [
				[
					$prefix . '_filter_type' => [
						'label' => false,
						'type' => 'ct-image-picker',
						'value' => 'simple',
						'setting' => [ 'transport' => 'postMessage' ],
						'choices' => [

							'simple' => [
								'src' => blocksy_image_picker_url( 'filter-type-1.svg' ),
								'title' => __( 'Type 1', 'blocksy-companion' ),
							],

							'buttons' => [
								'src' => blocksy_image_picker_url( 'filter-type-2.svg' ),
								'title' => __( 'Type 2', 'blocksy-companion' ),
							],

						],
					],
					$prefix . '_filter_behavior' => [
						'label' => __( 'Filtering Behavior', 'blocksy-companion' ),
						'type' => 'ct-select',
						'value' => 'ajax',
						'view' => 'text',
						'design' => 'inline',
						'divider' => 'top',
						'choices' => blocksy_ordered_keys(
							[
								'ajax' => __( 'Instant Reload', 'blocksy-companion' ),
								'reload' => __( 'Page Reload', 'blocksy-companion' ),
							]
						),
					],
				],

				count($taxonomies_choices) <= 1 ? [
					$prefix . '_filter_source' => [
						'type' => 'hidden',
						'value' => blocksy_maybe_get_matching_taxonomy($post_type),
					],
				] : [
					$prefix . '_filter_source' => [
						'label' => __('Filter Source', 'blocksy-companion'),
						'type' => 'ct-select',
						'value' => blocksy_maybe_get_matching_taxonomy($post_type),
						'divider' => 'top',
						'design' => 'inline',
						'choices' => blocksy_ordered_keys($taxonomies_choices)
					],
				],

				$prefix . '_has_counters' => [
					'type'  => 'ct-switch',
					'label' => __( 'Items Counter', 'blocksy-companion' ),
					'value' => 'no',
					'divider' => 'top',
					'sync' => blocksy_sync_whole_page([
						'prefix' => $prefix,
						'loader_selector' => '.ct-dynamic-filter'
					]),
				],


				$prefix . '_filter_items_horizontal_spacing' => [
					'label' => __( 'Items Horizontal Spacing', 'blocksy-companion' ),
					'type' => 'ct-slider',
					'value' => 30,
					'min' => 0,
					'max' => 100,
					'defaultUnit' => 'px',
					'responsive' => true,
					'divider' => 'top',
					'setting' => [ 'transport' => 'postMessage' ],
				],

				$prefix . '_filter_items_vertical_spacing' => [
					'label' => __( 'Items Vertical Spacing', 'blocksy-companion' ),
					'type' => 'ct-slider',
					'value' => 10,
					'min' => 0,
					'max' => 100,
					'defaultUnit' => 'px',
					'responsive' => true,
					'divider' => 'top',
					'setting' => [ 'transport' => 'postMessage' ],
				],

				$prefix . '_filter_container_spacing' => [
					'label' => __( 'Container Bottom Spacing', 'blocksy-companion' ),
					'type' => 'ct-slider',
					'value' => 40,
					'min' => 0,
					'max' => 300,
					'responsive' => true,
					'divider' => 'top',
					'setting' => [ 'transport' => 'postMessage' ],
				],

				$prefix . '_horizontal_alignment' => [
					'type' => 'ct-radio',
					'label' => __( 'Horizontal Alignment', 'blocksy-companion' ),
					'view' => 'text',
					'design' => 'block',
					'responsive' => true,
					'divider' => 'top',
					'attr' => [ 'data-type' => 'alignment' ],
					'setting' => [ 'transport' => 'postMessage' ],
					'value' => 'center',
					'choices' => [
						'left' => '',
						'center' => '',
						'right' => '',
					],
				],

				$prefix . '_filter_visibility' => [
					'label' => __( 'Visibility', 'blocksy-companion' ),
					'type' => 'ct-visibility',
					'design' => 'block',
					'divider' => 'top',
					'setting' => [ 'transport' => 'postMessage' ],

					'value' => blocksy_default_responsive_value([
						'desktop' => true,
						'tablet' => true,
						'mobile' => false,
					]),

					'choices' => blocksy_ordered_keys([
						'desktop' => __( 'Desktop', 'blocksy-companion' ),
						'tablet' => __( 'Tablet', 'blocksy-companion' ),
						'mobile' => __( 'Mobile', 'blocksy-companion' ),
					]),
				],

			],
		],

		blocksy_rand_md5() => [
			'title' => __( 'Design', 'blocksy-companion' ),
			'type' => 'tab',
			'options' => [

				$prefix . '_filter_font' => [
					'type' => 'ct-typography',
					'label' => __( 'Font', 'blocksy-companion' ),
					'value' => blocksy_typography_default_values([
						'size' => '12px',
						'variation' => 'n6',
						'text-transform' => 'uppercase',
					]),
					'design' => 'block',
					'sync' => 'live'
				],

				$prefix . '_filter_font_color' => [
					'label' => __( 'Font Color', 'blocksy-companion' ),
					'type'  => 'ct-color-picker',
					'design' => 'inline',
					'sync' => 'live',

					'value' => [
						'default' => [
							'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
						],

						'hover' => [
							'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
						],

						'default_2' => [
							'color' => '#ffffff',
						],

						'hover_2' => [
							'color' => '#ffffff',
						],
					],

					'pickers' => [
						[
							'title' => __( 'Initial', 'blocksy-companion' ),
							'id' => 'default',
							'inherit' => 'var(--theme-text-color)',
							'condition' => [ $prefix . '_filter_type' => 'simple' ],
						],

						[
							'title' => __( 'Hover/Active', 'blocksy-companion' ),
							'id' => 'hover',
							'inherit' => 'var(--theme-link-hover-color)',
							'condition' => [ $prefix . '_filter_type' => 'simple' ],
						],

						[
							'title' => __( 'Initial', 'blocksy-companion' ),
							'id' => 'default_2',
							'condition' => [ $prefix . '_filter_type' => 'buttons' ],
						],

						[
							'title' => __( 'Hover/Active', 'blocksy-companion' ),
							'id' => 'hover_2',
							'condition' => [ $prefix . '_filter_type' => 'buttons' ],
						],
					],
				],

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [ $prefix . '_filter_type' => 'buttons' ],
					'options' => [

						$prefix . '_filter_button_color' => [
							'label' => __( 'Button Color', 'blocksy-companion' ),
							'type'  => 'ct-color-picker',
							'design' => 'inline',
							'sync' => 'live',

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
									'inherit' => 'var(--theme-button-background-initial-color)'
								],

								[
									'title' => __( 'Hover/Active', 'blocksy-companion' ),
									'id' => 'hover',
									'inherit' => 'var(--theme-button-background-hover-color)'
								],
							],
						],

						$prefix . '_filter_button_padding' => [
							'label' => __( 'Button Padding', 'blocksy-companion' ),
							'type' => 'ct-spacing',
							'divider' => 'top',
							'value' => blocksy_spacing_value([
								// 'top' => '8px',
								// 'left' => '15px',
								// 'right' => '15px',
								// 'bottom' => '8px',
							]),
							'min' => 0,
							'responsive' => true,
							'sync' => 'live',
						],

						$prefix . '_filter_button_border_radius' => [
							'label' => __( 'Border Radius', 'blocksy-companion' ),
							'type' => 'ct-spacing',
							'divider' => 'top',
							'setting' => [ 'transport' => 'postMessage' ],
							'value' => blocksy_spacing_value(),
							'inputAttr' => [
								'placeholder' => '3'
							],
							'min' => 0,
							'responsive' => true
						],

					],
				],

			],
		],
	],

];

