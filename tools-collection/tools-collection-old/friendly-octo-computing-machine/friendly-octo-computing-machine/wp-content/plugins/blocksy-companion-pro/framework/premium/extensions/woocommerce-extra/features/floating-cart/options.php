<?php

$options = [
	'label' => __('Floating Cart', 'blocksy-companion'),
	'type' => 'ct-panel',
	'inner-options' => [

		blocksy_rand_md5() => [
			'title' => __( 'General', 'blocksy-companion' ),
			'type' => 'tab',
			'options' => [

				'floating_bar_position' => [
					'type' => 'ct-radio',
					'label' => __( 'Position', 'blocksy-companion' ),
					'view' => 'text',
					'design' => 'block',
					'value' => 'top',
					'choices' => [
						'top' => __( 'Top', 'blocksy-companion' ),
						'bottom' => __( 'Bottom', 'blocksy-companion' ),
					],
				],

				blocksy_rand_md5() => [
					'type' => 'ct-divider',
				],

				'floatingBarVisibility' => [
					'label' => __('Floating Cart Visibility', 'blocksy-companion'),
					'type' => 'ct-visibility',
					'design' => 'block',
					'setting' => ['transport' => 'postMessage'],
					'divider' => 'bottom',
					'allow_empty' => true,
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

				'floatingBarImageVisibility' => [
					'label' => __('Product Image Visibility', 'blocksy-companion'),
					'type' => 'ct-visibility',
					'design' => 'block',
					'setting' => ['transport' => 'postMessage'],
					'divider' => 'bottom',
					'allow_empty' => true,
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

				'floatingBarTitleVisibility' => [
					'label' => __('Product Title Visibility', 'blocksy-companion'),
					'type' => 'ct-visibility',
					'design' => 'block',
					'setting' => ['transport' => 'postMessage'],
					'divider' => 'bottom',
					'allow_empty' => true,
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

				'floatingBarPriceStockVisibility' => [
					'label' => __('Product Price & Stock Visibility', 'blocksy-companion'),
					'type' => 'ct-visibility',
					'design' => 'block',
					'setting' => ['transport' => 'postMessage'],
					'allow_empty' => true,
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

			],
		],

		blocksy_rand_md5() => [
			'title' => __( 'Design', 'blocksy-companion' ),
			'type' => 'tab',
			'options' => [

				'floatingBarFontColor' => [
					'label' => __( 'Font Color', 'blocksy-companion' ),
					'type'  => 'ct-color-picker',
					'design' => 'block:right',
					'responsive' => true,
					'setting' => [ 'transport' => 'postMessage' ],
					'value' => [
						'default' => [
							'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
						],
					],

					'pickers' => [
						[
							'title' => __( 'Initial', 'blocksy-companion' ),
							'id' => 'default',
							'inherit' => 'var(--theme-text-color)'
						],
					],
				],

				'floatingBarBackground' => [
					'label' => __( 'Background Color', 'blocksy-companion' ),
					'type' => 'ct-background',
					'design' => 'block:right',
					'responsive' => true,
					'divider' => 'top',
					'sync' => 'live',
					'value' => blocksy_background_default_value([
						'backgroundColor' => [
							'default' => [
								'color' => 'var(--theme-palette-color-8)',
							],
						],
					])
				],

				'floatingBarShadow' => [
					'label' => __( 'Shadow', 'blocksy-companion' ),
					'type' => 'ct-box-shadow',
					'responsive' => true,
					'divider' => 'top',
					'value' => blocksy_box_shadow_value([
						'enable' => true,
						'h_offset' => 0,
						'v_offset' => 10,
						'blur' => 20,
						'spread' => 0,
						'inset' => false,
						'color' => [
							'color' => 'rgba(44,62,80,0.15)',
						],
					]),
					'setting' => [ 'transport' => 'postMessage' ],
				],

			],
		],

	],
];
