<?php

$options = [
	'label' => __('Read Progress', 'blocksy-companion'),
	'type' => 'ct-panel',
	'switch' => true,
	'value' => 'no',
	'sync' => [
		'selector' => '.ct-drawer-canvas',
		'container_inclusive' => true,
		'render' => function () {
			if (function_exists('blocksy_output_drawer_canvas')) {
				blocksy_output_drawer_canvas();
			}
		}
	],
	'inner-options' => [
		blocksy_rand_md5() => [
			'title' => __( 'General', 'blocksy-companion' ),
			'type' => 'tab',
			'options' => [

				$prefix . '_read_progress_height' => [
					'label' => __( 'Indicator Height', 'blocksy-companion' ),
					'type' => 'ct-number',
					'design' => 'inline',
					'value' => 3,
					'min' => 1,
					'max' => 10,
					'setting' => [ 'transport' => 'postMessage' ],
				],

				$prefix . '_has_auto_hide' => [
					'label' => __( 'Auto Hide', 'blocksy-companion' ),
					'type' => 'ct-switch',
					'value' => 'no',
					'divider' => 'top',
					'desc' => __( 'Automatically hide the read progress bar once you arrive at the bottom of the article.', 'blocksy-companion' ),
					'setting' => [ 'transport' => 'postMessage' ],
				],

				$prefix . '_read_progress_visibility' => [
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

				$prefix . '_progress_bar_filled_color' => [
					'label' => __( 'Main Color', 'blocksy-companion' ),
					'type'  => 'ct-color-picker',
					'design' => 'inline',
					'sync' => 'live',

					'value' => [
						'default' => [
							'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
						],
					],

					'pickers' => [
						[
							'title' => __( 'Initial', 'blocksy-companion' ),
							'id' => 'default',
							'inherit' => 'var(--theme-palette-color-1)'
						],
					],
				],

				$prefix . '_progress_bar_background_color' => [
					'label' => __( 'Background Color', 'blocksy-companion' ),
					'type'  => 'ct-color-picker',
					'design' => 'inline',
					'sync' => 'live',

					'value' => [
						'default' => [
							'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
						],
					],

					'pickers' => [
						[
							'title' => __( 'Initial', 'blocksy-companion' ),
							'id' => 'default',
						],
					],
				],

			],
		],
	],
];
