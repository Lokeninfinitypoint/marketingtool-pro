<?php

namespace Blocksy;

class ContentBlocksPopupsLogic {
	public function __construct() {
		add_action('save_post', function ($post_id) {
			if (get_post_type($post_id) !== 'ct_content_block') {
				return;
			}

			if (get_post_meta($post_id, 'template_type', true) !== 'popup') {
				return;
			}

			$timestamp = get_transient('blocksy-content-blocks-timestamp');

			if (is_array($timestamp) && isset($timestamp[$post_id])) {
				unset($timestamp[$post_id]);
				set_transient('blocksy-content-blocks-timestamp', $timestamp);
			}
		});

		add_action(
			'wp_ajax_blc_retrieve_popup_content',
			[$this, 'blc_retrieve_popup_content']
		);

		add_action(
			'wp_ajax_nopriv_blc_retrieve_popup_content',
			[$this, 'blc_retrieve_popup_content']
		);

		// Migrate old popup_trigger_once option
		add_filter(
			'blocksy:posts:meta:values',
			function ($atts) {
				if (! isset($atts['popup_trigger_once'])) {
					return $atts;
				}

				unset($atts['popup_trigger_once']);

				$atts['popup_relaunch_strategy'] = 'relaunch_x_times';
				$atts['relaunch_x_times_value'] = 1;

				return $atts;
			}
		);

		add_filter('blocksy:frontend:dynamic-js-chunks', function ($chunks) {
			$chunks[] = [
				'id' => 'blocksy_pro_micro_popups',
				'selector' => '.ct-popup',
				'url' => blocksy_cdn_url(
					BLOCKSY_URL . 'framework/premium/static/bundle/micro-popups.js'
				)
			];

			return $chunks;
		});

		add_action('blocksy:content-blocks:display-hooks', function () {
			$this->display_popups();
		});
	}

	public function display_popups() {
		$all_popups = array_keys(blc_get_content_blocks([
			'template_type' => 'popup'
		]));

		foreach ($all_popups as $hook_id) {
			if (
				! \Blocksy\Plugin::instance()
					->premium
					->content_blocks
					->is_hook_eligible_for_display($hook_id)
			) {
				continue;
			}

			\Blocksy\Plugin::instance()
				->premium
				->content_blocks
				->assets_manager
				->enqueue_hook($hook_id);

			add_filter(
				'blocksy:footer:offcanvas-drawer',
				function ($els, $payload) use ($hook_id) {
					if ($payload['location'] === 'start') {
						$els[] = $this->render_popup($hook_id);
					}

					return $els;
				},
				10,
				2
			);
		}
	}

	public function render_popup($hook_id, $should_skip = true) {
		$values = blocksy_migrate_values(
			blocksy_get_post_options($hook_id),
			[
				'migrations' => ['popups_new_close_actions']
			]
		);

		$placement = blocksy_akg('popup_size', $values, 'medium');

		$overflow = blocksy_akg('popup_container_overflow', $values, 'scroll');
		$popup_scroll_lock = blocksy_akg('popup_scroll_lock', $values, 'no');

		$attr = [
			'id' => 'ct-popup-' . $hook_id,
			'data-popup-size' => $placement
		];

		if ($placement !== 'full') {
			$attr['data-popup-position'] = blocksy_akg(
				'popup_position',
				$values,
				'bottom:right'
			);
		}

		if ($popup_scroll_lock === 'yes') {
			$attr['data-scroll-lock'] = 'yes';
		}

		if ($overflow !== 'visible') {
			$attr['data-popup-overflow'] = $overflow;
		}

		$popup_backdrop_background = blocksy_akg(
			'popup_backdrop_background',
			$values,
			blocksy_background_default_value([
				'backgroundColor' => [
					'default' => [
						'color' => 'CT_CSS_SKIP_RULE'
					],
				],
			])
		);

		$attr['data-popup-backdrop'] = $popup_backdrop_background[
			'backgroundColor'
		]['default']['color'] !== 'CT_CSS_SKIP_RULE' ? 'yes' : 'no';

		$attr['data-popup-animation'] = blocksy_akg(
			'popup_open_animation',
			$values,
			'fade-in'
		);

		$popup_trigger_condition = blocksy_akg(
			'popup_trigger_condition',
			$values,
			'default'
		);

		$close_strategies = [];

		$has_close_button = blocksy_akg(
			'popup_close_button',
			$values,
			'yes'
		) === 'yes';

		$popup_close_with_esc = blocksy_akg(
			'popup_close_with_esc',
			$values,
			'yes'
		) === 'yes';

		if ($popup_close_with_esc) {
			$close_strategies = [
				'esc' => true
			];
		}

		$popup_close_with_backdrop_click = blocksy_akg(
			'popup_close_with_backdrop_click',
			$values,
			'yes'
		) === 'yes';

		$popup_backdrop = blocksy_akg(
			'popup_backdrop_background/backgroundColor/default/color',
			$values,
			\Blocksy_Css_Injector::get_skip_rule_keyword()
		) !== \Blocksy_Css_Injector::get_skip_rule_keyword();

		if ($popup_backdrop && $popup_close_with_backdrop_click) {
			$close_strategies['backdrop'] = true;
		}

		$popup_custom_close = blocksy_akg(
			'popup_custom_close',
			$values,
			'no'
		) === 'yes';

		if ($popup_custom_close) {
			$popup_custom_close_strategy = blocksy_akg(
				'popup_custom_close_strategy',
				$values,
				'form_submit'
			);

			$popup_custom_close_action_delay = blocksy_akg(
				'popup_custom_close_action_delay',
				$values,
				0
			);

			if ($popup_custom_close_strategy === 'form_submit') {
				$close_strategies['form_submit'] = true;

				if (intval($popup_custom_close_action_delay) > 0) {
					$close_strategies['form_submit'] = [
						'close_delay' => $popup_custom_close_action_delay
					];
				}
			}

			if ($popup_custom_close_strategy === 'button_click') {
				$popup_custom_close_button_selector = blocksy_akg(
					'popup_custom_close_button_selector',
					$values,
					''
				);

				if (! empty($popup_custom_close_button_selector)) {
					$close_strategies['button_click'] = [
						'selector' => $popup_custom_close_button_selector
					];

					if ($popup_custom_close_action_delay > 0) {
						$close_strategies['button_click']['close_delay'] = $popup_custom_close_action_delay;
					}
				}
			}
		}

		if (count($close_strategies) > 0) {
			$attr['data-popup-close-strategy'] = json_encode($close_strategies);
		}

		if ($popup_trigger_condition !== 'default') {
			$attr['data-popup-mode'] = $popup_trigger_condition;

			if (blocksy_akg('popup_trigger_once', $values, 'no') === 'yes') {
				$attr['data-popup-mode'] .= '_once';
			}

			if ($popup_trigger_condition === 'after_x_time') {
				$attr['data-popup-mode'] .= ':' . blocksy_akg(
					'x_time_value',
					$values,
					10
				);
			}

			if ($popup_trigger_condition === 'after_x_pages') {
				$attr['data-popup-mode'] .= ':' . blocksy_akg(
					'x_pages_value',
					$values,
					3
				);
			}

			if ($popup_trigger_condition === 'after_inactivity') {
				$attr['data-popup-mode'] .= ':' . blocksy_akg(
					'inactivity_value',
					$values,
					10
				);
			}

			if ($popup_trigger_condition === 'element_reveal') {
				$scroll_to_element = blocksy_akg(
					'scroll_to_element',
					$values,
					''
				);

				if (! empty($scroll_to_element)) {
					$attr['data-popup-mode'] .= ':' . $scroll_to_element;
				} else {
					unset($attr['data-popup-mode']);
				}
			}

			if ($popup_trigger_condition === 'element_click') {
				$click_to_element = blocksy_akg(
					'click_to_element',
					$values,
					''
				);

				if (! empty($click_to_element)) {
					$attr['data-popup-mode'] .= ':' . $click_to_element;
				} else {
					unset($attr['data-popup-mode']);
				}
			}

			if ($popup_trigger_condition === 'scroll') {
				$attr['data-popup-mode'] .= ':' . blocksy_akg(
					'scroll_value',
					$values,
					'200px'
				);

				$scroll_direction = blocksy_akg(
					'scroll_direction',
					$values,
					'down'
				);

				if ($scroll_direction === 'up') {
					$attr['data-popup-mode'] .= ':up';
				}

				// Close popup back on scroll back
				if (blocksy_akg('close_on_scroll_back', $values, 'no') === 'yes') {
					$attr['data-popup-mode'] .= ':close-back';
				}
			}

			$popup_relaunch_strategy = blocksy_akg(
				'popup_relaunch_strategy',
				$values,
				'default'
			);

			if ($popup_trigger_condition === 'element_click') {
				$popup_relaunch_strategy = 'always';
			}

			if ($popup_relaunch_strategy !== 'default') {
				$attr['data-popup-relaunch'] = $popup_relaunch_strategy;
			}

			if ($popup_relaunch_strategy === 'custom') {
				$time_after_close_value = blocksy_akg(
					'days_after_close_value',
					$values,
					[
						'days' => 14,
						'hours' => 0,
						'minutes' => 0,
					]
				);

				if (! is_array($time_after_close_value)) {
					$time_after_close_value = [
						'days' => $time_after_close_value,
						'hours' => 0,
						'minutes' => 0,
					];
				}

				$time_after_close_value['days'] = $time_after_close_value['days'] * 24 * 60;
				$time_after_close_value['hours'] = $time_after_close_value['hours'] * 60;
				$time_after_close_value['minutes'] = $time_after_close_value['minutes'];
				$time_after_close_value = array_sum($time_after_close_value);

				$attr['data-popup-relaunch'] .= ':' . $time_after_close_value;

				if (
					isset($close_strategies['form_submit'])
					||
					isset($close_strategies['button_click'])
				) {
					$time_after_success_value = blocksy_akg(
						'days_after_success_value',
						$values,
						[
							'days' => 30,
							'hours' => 0,
							'minutes' => 0,
						]
					);

					if (! is_array($time_after_success_value)) {
						$time_after_success_value = [
							'days' => $time_after_success_value,
							'hours' => 0,
							'minutes' => 0,
						];
					}

					$time_after_success_value['days'] = $time_after_success_value['days'] * 24 * 60;
					$time_after_success_value['hours'] = $time_after_success_value['hours'] * 60;
					$time_after_success_value['minutes'] = $time_after_success_value['minutes'];
					$time_after_success_value = array_sum($time_after_success_value);

					$attr['data-popup-relaunch'] .= ':' . $time_after_success_value;
				}
			}
		}

		$output_hook_args = [
			'layout' => true,
			'hook_class' => 'ct-popup',
			'hook_attr' => $attr,
			'article_wrapper' => [
				'class' => 'ct-popup-inner'
			],
			'entry_content_attr' => [
				'class' => 'ct-popup-content'
			]
		];

		if (
			blocksy_akg(
				'load_content_with_ajax',
				$values,
				'no'
			) === 'yes'
			&&
			$should_skip
		) {
			$output_hook_args['skip_content'] = true;

			if (blocksy_akg('fetch_popup_content', $values, 'never') === 'never') {
				$timestamp = get_transient('blocksy-content-blocks-timestamp');

				if (
					false === $timestamp
					||
					is_array($timestamp) && ! isset($timestamp[$hook_id])
				) {
					if ($timestamp === false) {
						$timestamp = [];
					}

					$timestamp[$hook_id] = time();

					set_transient('blocksy-content-blocks-timestamp', $timestamp);
				}

				$theme = blocksy_get_wp_parent_theme();

				$persistence_key = substr(md5(
					get_current_blog_id()
					.
					'_'
					.
					get_site_url(get_current_blog_id(), '/')
					.
					get_template()
					.
					$timestamp[$hook_id]
					.
					$theme->get('Version')
				), 0, 6);

				$output_hook_args['hook_attr']['data-cache-key'] = $persistence_key;
			}
		}

		if ($has_close_button) {
			$close_button_type = blocksy_akg(
				'close_button_type',
				$values,
				'outside'
			);

			if ($close_button_type === 'none') {
				$close_button_type = 'outside';
			}

			$output_hook_args['article_inside'] = '<button class="ct-toggle-close" data-location="' . $close_button_type . '" data-type="type-3" aria-label="' . __('Close popup', 'blocksy-companion') . '">
				<svg class="ct-icon" width="12" height="12" viewBox="0 0 15 15">
				<path d="M1 15a1 1 0 01-.71-.29 1 1 0 010-1.41l5.8-5.8-5.8-5.8A1 1 0 011.7.29l5.8 5.8 5.8-5.8a1 1 0 011.41 1.41l-5.8 5.8 5.8 5.8a1 1 0 01-1.41 1.41l-5.8-5.8-5.8 5.8A1 1 0 011 15z"></path>
				</svg>
				</button>';
		}

		$popup = \Blocksy\Plugin::instance()
			->premium
			->content_blocks
			->output_hook(
				$hook_id,
				$output_hook_args
			);

		if (
			strpos($popup, 'youtube') !== false
			||
			strpos($popup, 'youtu.be') !== false
		) {
			$popup = str_replace(
				'?feature=oembed',
				'?feature=oembed&enablejsapi=1&version=3&playerapiid=ytplayer',
				$popup
			);
		}

		return $popup;
	}

	public function blc_retrieve_popup_content() {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if (! isset($_POST['popup_id'])) {
			wp_send_json_error();
		}

		$post_id = null;

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if (isset($_POST['post_id'])) {
			// phpcs:ignore WordPress.Security.NonceVerification.Missing
			$post_id = sanitize_text_field(wp_unslash($_POST['post_id']));
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$popup_id = sanitize_text_field(wp_unslash($_POST['popup_id']));

		if ($post_id) {
			global $post;
			$post = get_post($post_id);
			setup_postdata($post);
		}

		$content = $this->render_popup($popup_id, false);

		if ($post_id) {
			wp_reset_postdata();
		}

		wp_send_json_success([
			'content' => $content
		]);
	}
}
