<?php

$options = [
	'label' => __('Waitlist', 'blocksy-companion'),
	'type' => 'ct-panel',
	'setting' => ['transport' => 'postMessage'],
	'inner-options' => [

		blocksy_rand_md5() => [
			'title' => __('General', 'blocksy-companion'),
			'type' => 'tab',
			'options' => [

				'waitlist_type' => [
					'label' => __('Form Type', 'blocksy-companion'),
					'type' => 'ct-radio',
					'value' => 'boxed',
					'view' => 'text',
					'design' => 'block',
					'divider' => 'top:full',
					'choices' => [
						'boxed' => __('Boxed', 'blocksy-companion'),
						'simple' => __('Simple', 'blocksy-companion'),
					],
					'sync' => 'live',
				],

				'waitlist_container_max_width' => [
					'label' => __('Form Max Width', 'blocksy-companion'),
					'type' => 'ct-slider',
					'value' => 100,
					'min' => 10,
					'max' => 100,
					'defaultUnit' => '%',
					'responsive' => true,
					'divider' => 'top',
					'setting' => [ 'transport' => 'postMessage' ],
				],

				'waitlist_allow_backorders' => [
					'label' => __('Enable For Backorders', 'blocksy-companion'),
					'desc' => __('Allow users to join the waitlist even if the product is on backorder.', 'blocksy-companion'),
					'type'  => 'ct-switch',
					'value' => 'no',
					'divider' => 'top:full',
					'sync' => [
						blocksy_sync_whole_page([
							'prefix' => 'product',
							'loader_selector' => '.entry-summary-items'
						]),
					],
				],

				'waitlist_show_users_number' => [
					'label' => __('Show Users Count', 'blocksy-companion'),
					'desc' => __('Display a counter that reflects the current number of users on the waitlist.', 'blocksy-companion'),
					'type'  => 'ct-switch',
					'value' => 'no',
					'divider' => 'top',
					'sync' => [
						blocksy_sync_whole_page([
							'prefix' => 'product',
							'loader_selector' => '.entry-summary-items'
						]),
					],
				],

				'waitlist_user_visibility' => [
					'label' => __('Logged In Users Only', 'blocksy-companion'),
					'desc' => __('Display the waitlist feature exclusively to users who are logged in.', 'blocksy-companion'),
					'type'  => 'ct-switch',
					'value' => 'no',
					'divider' => 'top',
					'sync' => [
						blocksy_sync_whole_page([
							'prefix' => 'product',
							'loader_selector' => '.entry-summary-items'
						]),
					],
				],

				'waitlist_user_confirmation' => [
					'label' => __('Subscription Confirmation', 'blocksy-companion'),
					'desc' => __('Specify which users should verify their waitlist subscription through email confirmation.', 'blocksy-companion'),
					'type' => 'ct-checkboxes',
					'design' => 'block',
					'view' => 'text',
					'allow_empty' => true,
					'value' => [
						'logged_in' => true,
						'logged_out' => true,
					],
					'divider' => 'top:full',
					'choices' => blocksy_ordered_keys([
						'logged_in' => __('Logged In', 'blocksy-companion'),
						'logged_out' => __('Logged Out', 'blocksy-companion'),
					]),
					'sync' => 'live',
				],

				'waitlist_conditions' => [
					'label' => __('Display Conditions', 'blocksy-companion'),
					'type' => 'blocksy-display-condition',
					'filter' => 'product_waitlist',
					'sectionAttr' => [ 'class' => 'ct-content-blocks-conditions' ],
					'display' => 'modal',
					'modalTitle' => __('Waitlist Form Display Conditions', 'blocksy-companion'),
					'modalDescription' => __('Choose where you want this Waitlist Form to be displayed.', 'blocksy-companion'),
					'value' => [
						[
							'type' => 'include',
							'rule' => 'everywhere',
							'payload' => []
						]
					],
					'design' => 'block',
					'divider' => 'top:full',
				],

			],
		],

		blocksy_rand_md5() => [
			'title' => __('Design', 'blocksy-companion'),
			'type' => 'tab',
			'options' => [

				'waitlist_title_font' => [
					'type' => 'ct-typography',
					'label' => __('Title Font', 'blocksy-companion'),
					'value' => blocksy_typography_default_values([
						'size' => '16px',
					]),
					'setting' => [ 'transport' => 'postMessage' ],
				],

				'waitlist_title_color' => [
					'label' => __('Title Color', 'blocksy-companion'),
					'type'  => 'ct-color-picker',
					'design' => 'inline',
					'divider' => 'bottom',
					'setting' => [ 'transport' => 'postMessage' ],
					'value' => [
						'default' => [
							'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
						],
					],

					'pickers' => [
						[
							'title' => __('Initial', 'blocksy-companion'),
							'id' => 'default',
							'inherit' => 'var(--theme-heading-5-color, var(--theme-headings-color))'
						],
					],
				],

				'waitlist_message_font' => [
					'type' => 'ct-typography',
					'label' => __('Message Font', 'blocksy-companion'),
					'value' => blocksy_typography_default_values([
						'size' => '15px',
					]),
					'setting' => [ 'transport' => 'postMessage' ],
				],

				'waitlist_message_color' => [
					'label' => __('Message Color', 'blocksy-companion'),
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
							'title' => __('Initial', 'blocksy-companion'),
							'id' => 'default',
							'inherit' => 'var(--theme-text-color)'
						],
					],
				],

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [ 'waitlist_type' => 'boxed' ],
					'options' => [

						'waitlist_form_border' => [
							'label' => __('Container Border', 'blocksy-companion'),
							'type' => 'ct-border',
							'sync' => 'live',
							'design' => 'block',
							'divider' => 'top:full',
							'value' => [
								'width' => 2,
								'style' => 'solid',
								'color' => [
									'color' => 'var(--theme-border-color)',
								],
							],
							'responsive' => true,
						],

						'waitlist_form_background' => [
							'label' => __('Container Background', 'blocksy-companion'),
							'type' => 'ct-background',
							'design' => 'block:right',
							'responsive' => true,
							'divider' => 'top',
							'sync' => 'live',
							'value' => blocksy_background_default_value([
								'backgroundColor' => [
									'default' => [
										'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
									],
								],
							])
						],

						'waitlist_form_padding' => [
							'label' => __('Container Padding', 'blocksy-companion'),
							'type' => 'ct-spacing',
							'divider' => 'top',
							'value' => blocksy_spacing_value(),
							'inputAttr' => [
								'placeholder' => '30'
							],
							'min' => 0,
							'responsive' => true,
							'setting' => [ 'transport' => 'postMessage' ],
						],

						'waitlist_form_border_radius' => [
							'label' => __('Container Border Radius', 'blocksy-companion'),
							'sync' => 'live',
							'type' => 'ct-spacing',
							'divider' => 'top',
							'value' => blocksy_spacing_value(),
							'inputAttr' => [
								'placeholder' => '7'
							],
							'min' => 0,
							'responsive' => true,
							'setting' => [ 'transport' => 'postMessage' ],
						],

					],
				],

			],
		],
	]
];