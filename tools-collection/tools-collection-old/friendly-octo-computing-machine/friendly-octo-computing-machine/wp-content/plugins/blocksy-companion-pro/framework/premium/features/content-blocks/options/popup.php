<?php

global $post;

$post_id = $post->ID;

$template_type = get_post_meta($post_id, 'template_type', true);

$hooks_manager = new \Blocksy\HooksManager();

$choices = [];

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
	'value' => 'Custom Hook',
	'group' => __('Other', 'blocksy-companion')
];

$options = [
	blocksy_rand_md5() => [
		'title' => __( 'General', 'blocksy-companion' ),
		'type' => 'tab',
		'options' => [

			[
				'has_inline_code_editor' => [
					'type' => 'hidden',
					'value' => 'no'
				],

				'has_content_block_structure' => [
					'label' => __( 'Container Structure', 'blocksy-companion' ),
					'type' => 'hidden',
					'value' => 'no',
					'design' => 'none'
				],
			],

			'conditions' => [
				'label' => __('Display Conditions', 'blocksy-companion'),
				'type' => 'blocksy-display-condition',
				'sectionAttr' => [ 'class' => 'ct-content-blocks-conditions' ],
				'display' => 'modal',
				'modalTitle' => __('Popup Display Conditions', 'blocksy-companion'),
				'modalDescription' => __('Choose where you want this popup to be displayed.', 'blocksy-companion'),
				'value' => [
					[
						'type' => 'include',
						'rule' => 'singulars',
						'payload' => []
					]
				],

				'value' => [],
				'design' => 'block',
			],


			'popup_trigger_condition' => [
				'label' => __('Launch Trigger', 'blocksy-companion' ),
				'type' => 'ct-select',
				'value' => 'default',
				'design' => 'block',
				'divider' => 'top:full',
				'choices' => blocksy_ordered_keys([
					'default' => __('None', 'blocksy-companion'),
					'scroll' => __('On scroll', 'blocksy-companion'),
					'element_reveal' => __('On scroll to element', 'blocksy-companion'),
					'element_click' => __('On element click', 'blocksy-companion'),
					'page_load' => __('On page load', 'blocksy-companion'),
					'after_inactivity' => __('After inactivity', 'blocksy-companion'),
					'after_x_time' => __('After x time', 'blocksy-companion'),
					'after_x_pages' => __('After x pages', 'blocksy-companion'),
					'exit_intent' => __('On page exit intent', 'blocksy-companion'),
				]),
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [ 'popup_trigger_condition' => 'element_reveal' ],
				'options' => [

					'scroll_to_element' => [
						'label' => __( 'Element Class', 'blocksy-companion' ),
						'type' => 'text',
						'design' => 'block',
						'value' => '',
						'attr' => ['placeholder' => '.my-element-class'],
						'sync' => 'live',
						'desc' => __('Separate each class by comma if you have multiple elements.', 'blocksy-companion' ),
					],

				],
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [ 'popup_trigger_condition' => 'element_click' ],
				'options' => [

					'click_to_element' => [
						'label' => __( 'Element Class', 'blocksy-companion' ),
						'type' => 'text',
						'design' => 'block',
						'value' => '',
						'attr' => ['placeholder' => '.my-element-class'],
						'sync' => 'live',
						'desc' => __('Separate each class by comma if you have multiple elements.', 'blocksy-companion' ),
					],

				],
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [ 'popup_trigger_condition' => 'scroll' ],
				'options' => [

					'scroll_direction' => [
						'label' => __('Scroll Direction', 'blocksy-companion' ),
						'type' => 'ct-select',
						'value' => 'down',
						'design' => 'block',
						'choices' => blocksy_ordered_keys([
							'down' => __('Scroll Down', 'blocksy-companion'),
							'up' => __('Scroll Up', 'blocksy-companion')
						]),
					],

					'scroll_value' => [
						'label' => __( 'Scroll Distance', 'blocksy-companion' ),
						'type' => 'ct-slider',
						'value' => '200px',
						'design' => 'block',
						'units' => [
							[ 'unit' => 'px','min' => 0, 'max' => 5000 ],
							[ 'unit' => '%','min' => 0, 'max' => 100 ],
							[ 'unit' => 'vh', 'min' => 0, 'max' => 100 ],
						],
						'desc' => __('Set the scroll distance till the popup block will appear.', 'blocksy-companion' ),
					],

					'close_on_scroll_back' => [
						'label' => __('Close Popup On Scroll Back', 'blocksy-companion' ),
						'type' => 'ct-switch',
						'value' => 'no',
					],

				],
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [ 'popup_trigger_condition' => 'after_inactivity' ],
				'options' => [

					'inactivity_value' => [
						'label' => __( 'Inactivity Time', 'blocksy-companion' ),
						'type' => 'ct-number',
						'design' => 'inline',
						'value' => 10,
						'min' => 0,
						'max' => 5000,
						'desc' => __('Set the inactivity time (in seconds) till the popup block will appear.', 'blocksy-companion' ),
					],

				],
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [ 'popup_trigger_condition' => 'after_x_time' ],
				'options' => [

					'x_time_value' => [
						'label' => __( 'After X Time', 'blocksy-companion' ),
						'type' => 'ct-number',
						'design' => 'inline',
						'value' => 10,
						'min' => 0,
						'max' => 5000,
						'desc' => __('Set after how much time (in seconds) the popup block will appear.', 'blocksy-companion' ),
					],

				],
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [ 'popup_trigger_condition' => 'after_x_pages' ],
				'options' => [

					'x_pages_value' => [
						'label' => __( 'After X Pages', 'blocksy-companion' ),
						'type' => 'ct-number',
						'design' => 'inline',
						'value' => 3,
						'min' => 1,
						'max' => 15,
						'desc' => __('Set after how many visited pages the popup block will appear.', 'blocksy-companion' ),
					],

				],
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [ 'popup_trigger_condition' => '! default | element_click' ],
				'options' => [
					'popup_relaunch_strategy' => [
						'label' => __('Relaunch Trigger', 'blocksy-companion' ),
						'type' => 'ct-select',
						'value' => 'default',
						'design' => 'block',
						'divider' => 'top:full',
						'choices' => blocksy_ordered_keys([
							'default' => __('Never relaunch', 'blocksy-companion'),
							'always' => __('Always relaunch', 'blocksy-companion'),
							'custom' => __('Custom interval', 'blocksy-companion'),
						]),
					],

					blocksy_rand_md5() => [
						'type' => 'ct-condition',
						'condition' => [ 'popup_relaunch_strategy' => 'custom' ],
						'options' => [
							'days_after_close_value' => [
								'label' => __( 'Days After Close', 'blocksy-companion' ),
								'type' => 'ct-timer',
								'design' => 'block',
								'value' => [
									'days' => 14,
									'hours' => 0,
									'minutes' => 0,
								],
								'desc' => __('Number of days before the popup will automatically relaunch again.', 'blocksy-companion'),
							],

							blocksy_rand_md5() => [
								'type' => 'ct-migrate-values',
								'migrations' => [
									'popups_new_close_actions'
								],
								'options' => [
									blocksy_rand_md5() => [
										'type' => 'ct-condition',
										'condition' => [
											'popup_custom_close' => 'yes'
										],
										'options' => [

											'days_after_success_value' => [
												'label' => [
													__('Days After Form Submit', 'blocksy-companion') => [
														'popup_custom_close_strategy' => 'form_submit'
													],

													__('Days After Button Click', 'blocksy-companion') => [
														'popup_custom_close_strategy' => 'button_click'
													]
												],
												'type' => 'ct-timer',
												'design' => 'block',
												'divider' => 'top',
												'value' => [
													'days' => 30,
													'hours' => 0,
													'minutes' => 0,
												],
												'desc' => __('Days before the popup relaunches after the additional close trigger is activated.', 'blocksy-companion'),
											]

										],
									],
								]
							]
						],
					],

				],
			],

			blocksy_rand_md5() => [
				'type' => 'ct-title',
				'variation' => 'simple-small-heading',
				'label' => __( 'Close Actions', 'blocksy-companion' ),
			],

			blocksy_rand_md5() => [
				'type' => 'ct-migrate-values',
				'migrations' => [
					'popups_new_close_actions'
				],
				'options' => [
					'popup_close_button' => [
						'label' => __( 'On Close Button Click', 'blocksy-companion' ),
						'type' => 'ct-switch',
						'value' => 'yes',
						// 'divider' => 'top:full',
						// 'desc' => __('Enable this option if you want to load the popup content using AJAX.', 'blocksy-companion' ),
					],

					'popup_close_with_esc' => [
						'label' => __( 'On ESC Button Press', 'blocksy-companion' ),
						'type' => 'ct-switch',
						'value' => 'yes'
					],

					blocksy_rand_md5() => [
						'type' => 'ct-condition',
						'condition' => [
							'popup_backdrop_background/backgroundColor/default/color' => "!" . Blocksy_Css_Injector::get_skip_rule_keyword()
						],
						'options' => [
							'popup_close_with_backdrop_click' => [
								'label' => __('On Backdrop Click', 'blocksy-companion'),
								'type' => 'ct-switch',
								'value' => 'yes'
							]
						]
					],

					'popup_custom_close' => [
						'label' => __( 'On Custom Action', 'blocksy-companion' ),
						'type' => 'ct-switch',
						'value' => 'no',
					],

					blocksy_rand_md5() => [
						'type' => 'ct-condition',
						'condition' => ['popup_custom_close' => 'yes'],
						'options' => [
							'popup_custom_close_strategy' => [
								'label' => false,
								'type' => 'ct-select',
								'value' => 'form_submit',
								'design' => 'block',
								'choices' => blocksy_ordered_keys([
									'form_submit' => __('Form Submit', 'blocksy-companion'),
									'button_click' => __('Button click', 'blocksy-companion')
								]),
							],

							blocksy_rand_md5() => [
								'type' => 'ct-condition',
								'condition' => ['popup_custom_close_strategy' => 'button_click'],
								'options' => [
									'popup_custom_close_button_selector' => [
										'label' => __('Button Class Selector', 'blocksy-companion'),
										'type' => 'text',
										'value' => '',
										'attr' => ['placeholder' => '.my-element-class'],
										'desc' => __('Button class selector that will trigger popup to close.', 'blocksy-companion'),
									],
								]
							],

							'popup_custom_close_action_delay' => [
								'label' => __('Delay', 'blocksy-companion'),
								'type' => 'ct-number',
								'design' => 'inline',
								'value' => 0,
								'min' => 0,
								'max' => 1000,
								'step' => 0.1,
								'desc' => __('Close delay time (in seconds) after the form submit action is detected.', 'blocksy-companion'),
								'blockDecimal' => false
							],
						]
					],
				]
			],

			'popup_open_animation' => [
				'label' => __('Popup Animation', 'blocksy-companion' ),
				'type' => 'ct-select',
				'value' => 'fade-in',
				'design' => 'block',
				'divider' => 'top:full',
				'choices' => blocksy_ordered_keys([
					'fade-in' => __('Fade in fade out', 'blocksy-companion'),
					'zoom-in' => __('Zoom in zoom out', 'blocksy-companion'),
					'slide-left' => __('Slide in from left', 'blocksy-companion'),
					'slide-right' => __('Slide in from right', 'blocksy-companion'),
					'slide-top' => __('Slide in from top', 'blocksy-companion'),
					'slide-bottom' => __('Slide in from bottom', 'blocksy-companion'),
				]),
			],

			'popup_entrance_speed' => [
				'label' => __( 'Animation Speed', 'blocksy-companion' ),
				'type' => 'ct-number',
				'design' => 'inline',
				'value' => 0.3,
				'min' => 0,
				'max' => 10,
				'step' => 0.1,
				'blockDecimal' => false
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [
					'popup_open_animation' => 'slide-left|slide-right|slide-top|slide-bottom',
				],
				'options' => [

					'popup_entrance_value' => [
						'label' => __( 'Entrance Value', 'blocksy-companion' ),
						'type' => 'ct-number',
						'design' => 'inline',
						'value' => 50,
						'min' => 0,
						'max' => 500,
					],

				],
			],

			'load_content_with_ajax' => [
				'label' => __( 'Load Content With AJAX', 'blocksy-companion' ),
				'type' => 'ct-switch',
				'value' => 'no',
				'divider' => 'top:full',
				'desc' => __('Enable this option if you want to load the popup content using AJAX.', 'blocksy-companion' ),
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => ['load_content_with_ajax' => 'yes'],
				'options' => [

					'fetch_popup_content' => [
						'label' => __('Reload Content', 'blocksy-companion' ),
						'type' => 'ct-radio',
						'value' => 'never',
						'view' => 'text',
						'design' => 'block',
						'divider' => 'top',
						'choices' => [
							'never' => __('Never', 'blocksy-companion'),
							'always' => __('Always', 'blocksy-companion')
						],
						'desc' => __('Set this option to always if you have dynamic content inside the popup in order to keep everything up to date.', 'blocksy-companion' ),
					],
				]
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => ['has_inline_code_editor' => 'no'],
				'options' => [
					blocksy_rand_md5() => [
						'type' => 'ct-divider',
					],

					'previewedPost' => [
						'label' => __( 'Dynamic Content Preview', 'blocksy-companion' ),
						'type' => 'blocksy-previewed-post',
						'value' => [
							'post_id' => '',
							'post_type' => 'post'
						],
						'desc' => __('Select a post/page to preview it\'s content inside the editor while building the popup.', 'blocksy-companion'),
					],
				],
			],

			'visibility' => [
				'label' => __( 'Popup Visibility', 'blocksy-companion' ),
				'type' => 'ct-visibility',
				'design' => 'block',
				'divider' => 'top:full',
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

			'popup_size' => [
				'label' => __('Popup Size', 'blocksy-companion' ),
				'type' => 'ct-select',
				'value' => 'medium',
				'design' => 'block',
				'divider' => 'top:full',
				'choices' => blocksy_ordered_keys([
					'small' => __('Small Size', 'blocksy-companion'),
					'medium' => __('Medium Size', 'blocksy-companion'),
					'large' => __('Large Size', 'blocksy-companion'),
					'custom' => __('Custom Size', 'blocksy-companion'),
				]),
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => ['popup_size' => 'custom'],
				'options' => [

					'popup_max_width' => [
						'label' => __( 'Max Width', 'blocksy-companion' ),
						'type' => 'ct-slider',
						'value' => '400px',
						'design' => 'block',
						'units' => [
							[ 'unit' => 'px','min' => 0, 'max' => 1500 ],
							[ 'unit' => 'vw', 'min' => 0, 'max' => 100 ],
							[ 'unit' => 'vh', 'min' => 0, 'max' => 100 ],
							[ 'unit' => 'em', 'min' => 0, 'max' => 100 ],
							[ 'unit' => 'rem', 'min' => 0, 'max' => 100 ],
						],
						'responsive' => true,
						'sync' => 'live'
					],

					'popup_max_height' => [
						'label' => __( 'Max Height', 'blocksy-companion' ),
						'type' => 'ct-slider',
						'value' => 'CT_CSS_SKIP_RULE',
						'design' => 'block',
						'units' => [
							[ 'unit' => 'px','min' => 0, 'max' => 1500 ],
							[ 'unit' => 'vw', 'min' => 0, 'max' => 100 ],
							[ 'unit' => 'vh', 'min' => 0, 'max' => 100 ],
							[ 'unit' => 'em', 'min' => 0, 'max' => 100 ],
							[ 'unit' => 'rem', 'min' => 0, 'max' => 100 ],
						],
						'responsive' => true,
						'sync' => 'live'
					],

				]
			],

			'popup_position' => [
				'label' => __('Popup Position', 'blocksy-companion' ),
				'type' => 'blocksy-position',
				'value' => 'bottom:right',
				'design' => 'block',
				'divider' => 'top:full',
			],

			'popup_scroll_lock' => [
				'label' => __( 'Scroll Lock', 'blocksy-companion' ),
				'type' => 'ct-switch',
				'value' => 'no',
				'divider' => 'top:full',
				'desc' => __('Lock the page scroll while the popup is triggered/oppened.', 'blocksy-companion' ),
			],

			'popup_edges_offset' => [
				'label' => __( 'Popup Offset', 'blocksy-companion' ),
				'type' => 'ct-slider',
				'min' => 0,
				'max' => 300,
				'value' => 25,
				'responsive' => true,
				'divider' => 'top:full',
			],

			'popup_shadow' => [
				'label' => __( 'Shadow', 'blocksy-companion' ),
				'type' => 'ct-box-shadow',
				'divider' => 'top:full',
				'responsive' => true,
				'value' => blocksy_box_shadow_value([
					'enable' => true,
					'h_offset' => 0,
					'v_offset' => 10,
					'blur' => 20,
					'spread' => 0,
					'inset' => false,
					'color' => [
						'color' => 'rgba(41, 51, 61, 0.1)',
					],
				])
			],

			'popup_padding' => [
				'label' => __( 'Padding', 'blocksy-companion' ),
				'type' => 'ct-spacing',
				'divider' => 'top:full',
				'value' => blocksy_spacing_value(),
				'inputAttr' => [
					'placeholder' => '30'
				],
				'responsive' => true
			],

			'popup_border_radius' => [
				'label' => __( 'Border Radius', 'blocksy-companion' ),
				'sync' => 'live',
				'type' => 'ct-spacing',
				'divider' => 'top:full',
				'value' => blocksy_spacing_value(),
				'inputAttr' => [
					'placeholder' => '7'
				],
				'min' => 0,
				'responsive' => true
			],

			'popup_container_overflow' => [
				'label' => __('Container Overflow', 'blocksy-companion'),
				'type' => 'ct-radio',
				'value' => 'scroll',
				'view' => 'text',
				'design' => 'block',
				'divider' => 'top:full',
				'choices' => [
					'hidden' => __( 'Hidden', 'blocksy-companion' ),
					'visible' => __( 'Visible', 'blocksy-companion' ),
					'scroll' => __('Scroll', 'blocksy-companion'),
				],
				'desc' => __('Control what happens to the content that is too big to fit into the popup.', 'blocksy-companion'),
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [ 'popup_close_button' => 'yes' ],
				'options' => [
					'close_button_type' => [
						'label' => __('Close Button Position', 'blocksy-companion'),
						'type' => 'ct-radio',
						'value' => 'outside',
						'view' => 'text',
						'design' => 'block',
						'divider' => 'top:full',
						'choices' => [
							'inside' => __('Inside', 'blocksy-companion'),
							'outside' => __( 'Outside', 'blocksy-companion' ),
						],
					],

					'popup_close_button_color' => [
						'label' => __( 'Close Icon Color', 'blocksy-companion' ),
						'type'  => 'ct-color-picker',
						'design' => 'inline',
						'divider' => 'top',
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
								'inherit' => 'rgba(255, 255, 255, 0.7)'
							],

							[
								'title' => __( 'Hover', 'blocksy-companion' ),
								'id' => 'hover',
								'inherit' => '#ffffff'
							],
						],
					],

					'popup_close_button_shape_color' => [
						'label' => __( 'Close Icon Background', 'blocksy-companion' ),
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

			'popup_background' => [
				'label' => __( 'Popup Background', 'blocksy-companion' ),
				'type'  => 'ct-background',
				'design' => 'inline',
				'divider' => 'top:full',
				'value' => blocksy_background_default_value([
					'backgroundColor' => [
						'default' => [
							'color' => '#ffffff'
						],
					],
				])
			],

			'popup_backdrop_background' => [
				'label' => __( 'Popup Backdrop Background', 'blocksy-companion' ),
				'type'  => 'ct-background',
				'design' => 'inline',
				'divider' => 'top:full',
				'has_no_color' => true,
				'default_inherit_color' => 'rgba(18, 21, 25, 0.5)',
				'value' => blocksy_background_default_value([
					'backgroundColor' => [
						'default' => [
							'color' => 'CT_CSS_SKIP_RULE'
						],
					],
				])
			],

		],
	],
];

