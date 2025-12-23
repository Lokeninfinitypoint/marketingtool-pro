<?php

$options = [

	blocksy_rand_md5() => [
		'type' => 'ct-condition',
		'condition' => [ 'woocommerce_related_products_slideshow' => 'slider' ],
		'options' => [

			'related_upsell_slider_nav_arrow_color' => [
				'label' => __( 'Prev/Next Arrow', 'blocksy-companion' ),
				'type'  => 'ct-color-picker',
				'design' => 'inline',
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
						'inherit' => 'var(--theme-text-color)'
					],

					[
						'title' => __( 'Hover', 'blocksy-companion' ),
						'id' => 'hover',
						'inherit' => '#ffffff'
					],
				],
			],

			'related_upsell_slider_nav_background_color' => [
				'label' => __( 'Prev/Next Background', 'blocksy-companion' ),
				'type'  => 'ct-color-picker',
				'design' => 'inline',
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
						'inherit' => '#ffffff'
					],

					[
						'title' => __( 'Hover', 'blocksy-companion' ),
						'id' => 'hover',
						'inherit' => 'var(--theme-palette-color-1)'
					],
				],
			],

		],
	],

];