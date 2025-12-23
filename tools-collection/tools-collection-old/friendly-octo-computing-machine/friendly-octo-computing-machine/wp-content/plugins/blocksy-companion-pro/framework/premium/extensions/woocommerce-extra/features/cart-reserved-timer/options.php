<?php

$options = [
	'label' => __('Cart Reserved Timer', 'blocksy-companion'),
	'type' => 'ct-panel',
	'setting' => ['transport' => 'postMessage'],
	'inner-options' => [

		blocksy_rand_md5() => [
			'title' => __( 'General', 'blocksy-companion' ),
			'type' => 'tab',
			'options' => [

				'woo_reserved_timer_in_cart' => [
					'label' => __( 'Show In Cart Page', 'blocksy-companion' ),
					'type' => 'ct-switch',
					'value' => 'yes',
				],

				'woo_reserved_timer_in_mini_cart' => [
					'label' => __( 'Show In Mini Cart', 'blocksy-companion' ),
					'type' => 'ct-switch',
					'value' => 'no',
					'divider' => 'bottom:full'
				],

				'woo_reserved_timer_time' => [
					'label' => __('Countdown Duration', 'blocksy-companion'),
					'type' => 'ct-number',
					'design' => 'inline',
					'value' => 10,
					'min' => 1,
					'max' => 300,
					'desc' => __('Determine for how long items in the cart are reserved before being removed.', 'blocksy-companion'),
				],

				'woo_reserved_timer_message' => [
					'label' => __('Message', 'blocksy-companion'),
					'type' => 'wp-editor',
					'value' => __('<p><strong>🔥 Hurry up! Your selected items are in high demand!</strong><br>Your cart will be reserved for {time} minutes.</p>', 'blocksy-companion'),
					'setting' => [ 'transport' => 'postMessage' ],
					'quicktags' => false,
					'mediaButtons' => false,
					'divider' => 'top:full',
					'tinymce' => [
						'toolbar1' => 'bold,italic,link,alignleft,aligncenter,alignright,undo,redo',
					],
					'desc' => __( 'You can use dynamic code tags such as {time} inside this option.', 'blocksy-companion' ),
				],

			],
		],

		blocksy_rand_md5() => [
			'title' => __( 'Design', 'blocksy-companion' ),
			'type' => 'tab',
			'options' => [

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [ 'woo_reserved_timer_in_cart' => 'yes' ],
					'options' => [

						blocksy_rand_md5() => [
							'type' => 'ct-title',
							'label' => __( 'Cart Page', 'blocksy-companion' ),
						],

						'woo_reserved_timer_cart_text_color' => [
							'label' => __( 'Text Color', 'blocksy-companion' ),
							'type'  => 'ct-color-picker',
							'design' => 'inline',
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

						'woo_reserved_timer_cart_background_color' => [
							'label' => __( 'Background Color', 'blocksy-companion' ),
							'type'  => 'ct-color-picker',
							'design' => 'inline',
							'setting' => [ 'transport' => 'postMessage' ],
							'value' => [
								'default' => [
									'color' => '#F0F1F3',
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

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [ 'woo_reserved_timer_in_mini_cart' => 'yes' ],
					'options' => [

						blocksy_rand_md5() => [
							'type' => 'ct-title',
							'label' => __( 'Mini Cart', 'blocksy-companion' ),
						],

						'woo_reserved_timer_mini_cart_text_color' => [
							'label' => __( 'Text Color', 'blocksy-companion' ),
							'type'  => 'ct-color-picker',
							'design' => 'inline',
							'setting' => [ 'transport' => 'postMessage' ],
							'value' => [
								'default' => [
									'color' => Blocksy_Css_Injector::get_skip_rule_keyword(),
								],
							],

							'pickers' => [
								[
									'title' => __( 'Initial', 'blocksy-companion' ),
									'id' => 'default',
								],
							],
						],

						'woo_reserved_timer_mini_cart_background_color' => [
							'label' => __( 'Background Color', 'blocksy-companion' ),
							'type'  => 'ct-color-picker',
							'design' => 'inline',
							'setting' => [ 'transport' => 'postMessage' ],
							'value' => [
								'default' => [
									'color' => 'rgba(214, 214, 214, 0.3)',
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
		],

	]
];