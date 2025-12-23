<?php

$options = [
	'label' => __('Stock Scarcity', 'blocksy-companion'),
	'type' => 'ct-panel',
	'setting' => ['transport' => 'postMessage'],
	'inner-options' => [

		blocksy_rand_md5() => [
			'title' => __( 'General', 'blocksy-companion' ),
			'type' => 'tab',
			'options' => [

				'product_stock_scarcity_min' => [
					'label' => __('Stock Threshold', 'blocksy-companion'),
					'type' => 'ct-number',
					'min' => 0,
					'value' => 50,
					'design' => 'inline',
					'sync' => [
						'id' => 'woo_single_layout_skip',
					],
					'desc' => __('Show the stock scarcity module when product stock is below this number.', 'blocksy-companion'),
				],

				'product_stock_scarcity_title' => [
					'label' => __( 'Message', 'blocksy-companion' ),
					'type' => 'wp-editor',
					'value' => __('🚨 Hurry up! Only {items} units left in stock!', 'blocksy-companion'),
					'divider' => 'top',
					'setting' => [ 'transport' => 'postMessage' ],
					'quicktags' => false,
					'mediaButtons' => false,
					'tinymce' => [
						'toolbar1' => 'bold,italic,link,alignleft,aligncenter,alignright,undo,redo',
					],
					'sync' => [
						'id' => 'woo_single_layout_skip',
					],
				],

				'stock_scarcity_bar_height' => [
					'label' => __( 'Bar Height', 'blocksy-companion' ),
					'type' => 'ct-slider',
					'min' => 1,
					'max' => 20,
					'value' => 5,
					'responsive' => true,
					'divider' => 'top:full',
					'setting' => [ 'transport' => 'postMessage' ],
				],

			],
		],

		blocksy_rand_md5() => [
			'title' => __( 'Design', 'blocksy-companion' ),
			'type' => 'tab',
			'options' => [

				'stock_scarcity_bar_color' => [
					'label' => __( 'Bar Color', 'blocksy-companion' ),
					'type'  => 'ct-color-picker',
					'design' => 'inline',
					'setting' => [ 'transport' => 'postMessage' ],
					'value' => [
						'default' => [
							'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
						],

						'active' => [
							'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
						],

						'active_2' => [
							'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
						],
					],

					'pickers' => [
						[
							'title' => __( 'Initial', 'blocksy-companion' ),
							'id' => 'default',
							'inherit' => 'var(--theme-border-color)'
						],

						[
							'title' => __( 'Active', 'blocksy-companion' ),
							'id' => 'active',
							'inherit' => 'var(--theme-palette-color-1)'
						],

						[
							'title' => __( 'Active', 'blocksy-companion' ),
							'id' => 'active_2',
							'inherit' => 'self:active'
						],
					],
				],

			],
		],
		
	],
];