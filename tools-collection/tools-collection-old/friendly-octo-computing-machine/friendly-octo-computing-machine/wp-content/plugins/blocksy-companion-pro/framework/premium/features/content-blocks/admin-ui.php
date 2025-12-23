<?php

namespace Blocksy;

class ContentBlocksAdminUi {
	private $shortcode = 'blocksy-content-block';
	private $post_type = 'ct_content_block';

	private $used_hooks = [
		'hooks' => [],
		'popups' => [],
		'templates' => []
	];

	public function __construct() {
		add_action('wp_footer', [$this, 'localize_menu'], PHP_INT_MAX);

		add_action('blocksy:pro:content-blocks:output-hook:start', function ($id) {
			$template_type = get_post_meta($id, 'template_type', true);

			$hook_data = [
				'title' => get_the_title($id),
				'href' => get_edit_post_link($id),
				'type' => $template_type
			];

			if ($template_type === 'popup') {
				$this->used_hooks['popups'][$id] = $hook_data;
			}

			if ($template_type === 'hook') {
				$this->used_hooks['hooks'][$id] = $hook_data;
			}

			if ($template_type !== 'popup' && $template_type !== 'hook') {
				$this->used_hooks['templates'][$id] = $hook_data;
			}
		});

		add_filter('blocksy:editor:post_meta_options', function ($options, $post_type) {
			if ($post_type !== $this->post_type) {
				return $options;
			}

			global $post;

			$post_id = $post->ID;

			$current_screen = get_current_screen();

			if (
				$current_screen
				&&
				$current_screen->action === 'add'
				&&
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				isset($_GET['trid'])
				&&
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				intval($_GET['trid']) > 0
			) {
				$post_id = \SitePress::get_original_element_id_by_trid(
					// phpcs:ignore WordPress.Security.NonceVerification.Recommended
					sanitize_text_field(wp_unslash($_GET['trid']))
				);
			}

			$template_type = get_post_meta($post_id, 'template_type', true);

			return blocksy_akg(
				'options',
				blc_theme_functions()->blocksy_get_variables_from_file(
					dirname(
						__FILE__
					) . '/options/' . $template_type . '.php',
					['options' => []]
				)
			);
		}, 10, 2);

		add_filter(
			'blocksy:editor:post_types_for_rest_field',
			function ($post_types) {
				$post_types[] = $this->post_type;
				return $post_types;
			}
		);

		add_filter('removable_query_args', function ($qs) {
			$qs[] = 'ct_enabled_hooks';
			$qs[] = 'ct_disabled_hooks';

			return $qs;
		});

		add_action('wp_ajax_blocksy_content_blocksy_create', function () {
			$capability = blc_get_capabilities()->get_wp_capability_by(
				'custom_post_type',
				[
					'post_type' => $this->post_type
				]
			);

			if (! current_user_can($capability)) {
				wp_send_json_error();
			}

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if (! isset($_REQUEST['name'])) {
				wp_send_json_error();
			}

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if (! isset($_REQUEST['type'])) {
				wp_send_json_error();
			}

			$post = [];

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$post['post_title'] = sanitize_text_field(wp_unslash($_REQUEST['name']));
			$post['post_status'] = 'publish';
			$post['post_type'] = $this->post_type;

			$post_id = wp_insert_post($post);

			if (! $post_id) {
				wp_send_json_error();
			}

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			update_post_meta($post_id, 'template_type', sanitize_text_field(wp_unslash($_REQUEST['type'])));

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ($_REQUEST['type'] === 'maintenance') {
				$post = get_post($post_id);

				$post->post_content = '<!-- wp:group {"style":{"dimensions":{"minHeight":"100vh"},"spacing":{}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center","verticalAlignment":"center"}} -->
				<div class="wp-block-group" style="min-height:100vh"><!-- wp:heading {"textAlign":"center"} -->
				<h2 class="wp-block-heading has-text-align-center" id="site-under-construction">Site Under Construction</h2>
				<!-- /wp:heading -->

				<!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|40"}}},"fontSize":"medium"} -->
				<p class="has-text-align-center has-medium-font-size" style="margin-bottom:var(--wp--preset--spacing--40)">Our website is currently undergoing scheduled maintenance.<br>Thank you for your understanding.</p>
				<!-- /wp:paragraph -->

				<!-- wp:social-links {"iconColor":"palette-color-3","iconColorValue":"var(\u002d\u002dtheme-palette-color-3, #365951)","showLabels":true,"size":"has-small-icon-size","className":"is-style-logos-only","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|60"}}},"layout":{"type":"flex","orientation":"horizontal","justifyContent":"center"}} -->
				<ul class="wp-block-social-links has-small-icon-size has-visible-labels has-icon-color is-style-logos-only"><!-- wp:social-link {"url":"#","service":"facebook"} /-->

				<!-- wp:social-link {"url":"#","service":"twitter"} /-->

				<!-- wp:social-link {"url":"#","service":"linkedin"} /-->

				<!-- wp:social-link {"url":"#","service":"mail"} /--></ul>
				<!-- /wp:social-links --></div>
				<!-- /wp:group -->';

				wp_update_post($post);
			}

			if (
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				isset($_GET['predefined_hook'])
				&&
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				is_string($_GET['predefined_hook'])
			) {
				$predefined_values = explode(
					'::',
					// phpcs:ignore WordPress.Security.NonceVerification.Recommended
					sanitize_text_field(wp_unslash($_GET['predefined_hook']))
				);

				$default_hook = $predefined_values[0];
				$default_priority = 10;

				if (count($predefined_values) > 1) {
					$default_priority = intval($predefined_values[1]);
				}

				$value = [
					'location' => $default_hook,
					'priority' => $default_priority
				];

				update_post_meta($post_id, 'blocksy_post_meta_options', $value);
			}

			wp_send_json_success([
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				'name' => sanitize_text_field(wp_unslash($_REQUEST['name'])),
				'url' => get_edit_post_link($post_id, '&')
			]);
		});

		add_filter('bulk_actions-edit-ct_content_block', function ($bulk_actions) {
			$bulk_actions['ct_enable'] = __('Enable', 'blocksy-companion');
			$bulk_actions['ct_disable'] = __('Disable', 'blocksy-companion');

			return $bulk_actions;
		});

		add_filter(
			'handle_bulk_actions-edit-ct_content_block',
			function ($redirect_to, $doaction, $post_ids) {
				if ($doaction === 'ct_enable') {
					foreach ($post_ids as $post_id) {
						$atts = blocksy_get_post_options($post_id);
						$atts['is_hook_enabled'] = 'yes';
						update_post_meta($post_id, 'blocksy_post_meta_options', $atts);
					}

					$redirect_to = add_query_arg(
						'ct_enabled_hooks',
						count($post_ids),
						$redirect_to
					);
				}

				if ($doaction === 'ct_disable') {
					foreach ($post_ids as $post_id) {
						$atts = blocksy_get_post_options($post_id);
						$atts['is_hook_enabled'] = 'no';
						update_post_meta($post_id, 'blocksy_post_meta_options', $atts);
					}

					$redirect_to = add_query_arg(
						'ct_disabled_hooks',
						count($post_ids),
						$redirect_to
					);
				}

				return $redirect_to;
			},
			10, 3
		);

		add_action('admin_notices', function () {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if (! empty($_REQUEST['ct_enabled_hooks'])) {
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$count = intval($_REQUEST['ct_enabled_hooks']);

				blocksy_html_tag_e(
					'div',
					[
						'id' => 'message',
						'class' => 'updated notice is-dismissible'
					],
					blocksy_html_tag(
						'p',
						[],
						blc_safe_sprintf(
							// translators: %s is the number of content blocks
							_n(
								'Enabled %s content block.',
								'Enabled %s content blocks.',
								$count,
								'blocksy-companion'
							),
							$count
						)
					)
				);
			}

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if (! empty($_REQUEST['ct_disabled_hooks'])) {
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$count = intval($_REQUEST['ct_disabled_hooks']);

				blocksy_html_tag_e(
					'div',
					[
						'id' => 'message',
						'class' => 'updated notice is-dismissible'
					],
					blocksy_html_tag(
						'p',
						[],
						blc_safe_sprintf(
							// translators: %s is the number of content blocks
							_n(
								'Disabled %s content block.',
								'Disabled %s content blocks.',
								$count,
								'blocksy-companion'
							),
							$count
						)
					)
				);
			}

		});

		add_action(
			'restrict_manage_posts',
			function () {
				if (
					// phpcs:ignore WordPress.Security.NonceVerification.Recommended
					! isset($_GET['post_type'])
					||
					// phpcs:ignore WordPress.Security.NonceVerification.Recommended
					$_GET['post_type'] !== 'ct_content_block'
				) {
					return;
				}

				$values = [
					__('Custom Content/Hooks', 'blocksy-companion') => 'hook',
					__('Popup', 'blocksy-companion') => 'popup',
					__('404 Page', 'blocksy-companion') => '404',
					__('Header', 'blocksy-companion') => 'header',
					__('Footer', 'blocksy-companion') => 'footer',
					__('Archive', 'blocksy-companion') => 'archive',
					__('Single', 'blocksy-companion') => 'single',
					__('Nothing Found', 'blocksy-companion') => 'nothing_found',
					__('Maintenance', 'blocksy-companion') => 'maintenance',
				];

				echo '<select name="block_type">';

				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '<option value="">' . __('All types', 'blocksy-companion') . '</option>';

				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$current_v = isset($_GET['block_type']) ? sanitize_text_field(wp_unslash($_GET['block_type'])) : '';

				foreach ($values as $label => $value) {
					if ($value === '404') {
						echo '<optgroup label="Templates">';
					}

					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					printf(
						'<option value="%s"%s>%s</option>',
						esc_attr($value),
						$value === $current_v ? ' selected="selected"' : '',
						esc_html($label)
					);

					if ($value === 'single') {
						echo '</optgroup>';
					}
				}

				echo '</select>';
			}
		);

		add_action('pre_get_posts', function ($query) {
			if (
				! is_admin()
				||
				! $query->is_main_query()
				||
				! isset($query->query['post_type'])
				||
				$query->query['post_type'] !== 'ct_content_block'
				||
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				! isset($_REQUEST['block_type'])
			) {
				return $query;
			}

			$screen = get_current_screen();

			if ($screen->id !== 'edit-ct_content_block' ) {
				return $query;
			}

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$slug = sanitize_text_field(wp_unslash($_REQUEST['block_type']));

			if ($slug === 'all') {
				return $query;
			}

			if (empty($slug)) {
				return $query;
			}

			// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			$query->query_vars['meta_query'] = [
				[
					'key' => 'template_type',
					'value' => $slug
				]
			];

			return $query;
		});

		add_filter('manage_ct_content_block_posts_columns', function ($columns) {
			$columns['template_type'] = __('Type', 'blocksy-companion');
			$columns['location'] = __('Location/Trigger', 'blocksy-companion');
			$columns['conditions'] = __('Conditions', 'blocksy-companion');
			$columns['shortcode'] = __('Output', 'blocksy-companion');
			$columns['actions'] = __('Enable/Disable', 'blocksy-companion');

			return $columns;
		});

		add_action(
			'manage_ct_content_block_posts_custom_column',
			function ($column, $post_id) {
				$template_type = get_post_meta($post_id, 'template_type', '');

				if (is_array($template_type) && isset($template_type[0])) {
					$template_type = $template_type[0];
				}

				$atts = blocksy_get_post_options($post_id);

				if ($column === 'location') {
					if ($template_type === 'popup') {
						$popup_trigger_condition = blocksy_akg(
							'popup_trigger_condition',
							$atts,
							'default'
						);

						$humanized_triggers = [
							'default' => __('None', 'blocksy-companion'),
							'scroll' => __('On scroll', 'blocksy-companion'),
							'element_reveal' => __('On scroll to element', 'blocksy-companion'),
							'element_click' => __('On click to element', 'blocksy-companion'),
							'page_load' => __('On page load', 'blocksy-companion'),
							'after_inactivity' => __('After inactivity', 'blocksy-companion'),
							'after_x_time' => __('After x time', 'blocksy-companion'),
							'after_x_pages' => __('After x pages', 'blocksy-companion'),
							'exit_intent' => __('On page exit intent', 'blocksy-companion'),
						];

						$humanized_direction = [
							'down' => __('Down', 'blocksy-companion'),
							'up' => __('Up', 'blocksy-companion')
						];

						$once_text = '';

						if (
							$popup_trigger_condition !== 'default'
							&&
							blocksy_akg('popup_trigger_once', $atts, 'no') === 'yes'
						) {
							$once_text = esc_html(', once');
						}

						if (isset($humanized_triggers[$popup_trigger_condition])) {
							// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							echo $humanized_triggers[$popup_trigger_condition];

							if ($popup_trigger_condition === 'element_reveal') {
								$scroll_to_element = blocksy_akg(
									'scroll_to_element',
									$atts,
									''
								);

								if (! empty($scroll_to_element)) {
									// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									echo ' (' . esc_html($scroll_to_element) . $once_text . ')';
								}
							}

							if ($popup_trigger_condition === 'element_click') {
								$click_to_element = blocksy_akg(
									'click_to_element',
									$atts,
									''
								);

								if (! empty($click_to_element)) {
									// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									echo ' (' . esc_html($click_to_element) . $once_text . ')';
								}
							}

							if (
								$popup_trigger_condition === 'page_load'
								||
								$popup_trigger_condition === 'exit_intent'
							) {
								if (! empty($once_text)) {
									// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									echo ' (' . trim(str_replace(',', '', $once_text)) . ')';
								}
							}

							if ($popup_trigger_condition === 'after_inactivity') {
								// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								echo ' (' . blocksy_akg('inactivity_value', $atts, '10') . 's' . $once_text . ')';
							}

							if ($popup_trigger_condition === 'after_x_time') {
								// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								echo ' (' . blocksy_akg('x_time_value', $atts, '10') . 's' . $once_text . ')';
							}

							if ($popup_trigger_condition === 'after_x_pages') {
								// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								echo ' (' . blocksy_akg('x_pages_value', $atts, '3') . $once_text . ')';
							}

							if ($popup_trigger_condition === 'scroll') {
								// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								echo ' (' . $humanized_direction[blocksy_akg('scroll_direction', $atts, 'down')] . ' ' . blocksy_akg('scroll_value', $atts, '200px') . $once_text . ')';
							}
						}
					}

					if ($template_type === 'hook') {
						$locations = array_merge([
							[
								'location' => blocksy_default_akg('location', $atts, ''),
								'priority' => blocksy_default_akg('priority', $atts, '10'),
								'custom_location' => blocksy_default_akg('custom_location', $atts, ''),
								'paragraphs_count' => blocksy_default_akg('paragraphs_count', $atts, '5'),
								'headings_count' => blocksy_default_akg('headings_count', $atts, '5'),
							]
						], blocksy_default_akg('additional_locations', $atts, []));

						$hooks_manager = new HooksManager();

						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						echo implode(
							'<br>',
							// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							$hooks_manager->humanize_locations($locations)
						);
					}
				}

				if ($column === 'conditions') {
					$default_conditions = [];

					if (
						$template_type === 'maintenance'
						||
						$template_type === 'nothing_found'
					) {
						$default_conditions = [
							[
								'type' => 'include',
								'rule' => 'everywhere',
							]
						];
					}

					$conditions = blocksy_default_akg(
						'conditions',
						$atts,
						$default_conditions
					);

					$conditions_manager = new ConditionsManager();

					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo implode(
						'<br>',
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						$conditions_manager->humanize_conditions($conditions)
					);
				}

				if ($column === 'shortcode') {
					if (is_array($template_type) && isset($template_type[0])) {
						$template_type = $template_type[0];
					}

					if ($template_type === 'hook' || $template_type === 'popup') {
						$shortcode_column_value = '[' . $this->shortcode . ' id="' . $post_id . '"]';

						if ($template_type === 'popup') {
							$shortcode_column_value = '#ct-popup-' . $post_id;
						}

						blocksy_html_tag_e(
							'input',
							[
								'class' => 'blocksy-shortcode',
								'type' => 'text',
								'readonly' => '',
								'onfocus' => 'this.select()',
								'value' => htmlspecialchars($shortcode_column_value)
							],
							false
						);
					}
				}

				if ($column === 'template_type') {
					$template_type = get_post_meta($post_id, 'template_type', '');

					if (is_array($template_type) && isset($template_type[0])) {
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						echo str_replace('_', ' ', ucfirst($template_type[0]));
					}
				}

				if ($column === 'actions') {
					$switch_class = 'ct-content-block-switch ct-option-switch';

					$atts = blocksy_get_post_options($post_id);

					if (blocksy_akg('is_hook_enabled', $atts, 'yes') === 'yes') {
						$switch_class .= ' ct-active';
					}

					$attr = [
						'class' => $switch_class,
						'data-post-id' => $post_id
					];

					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo '<div ' . blocksy_attr_to_html($attr) . '><span></div>';
				}
			}, 10, 2
		);

		add_action('wp_ajax_blocksy_content_blocksy_toggle', function () {
			$capability = blc_get_capabilities()->get_wp_capability_by(
				'custom_post_type',
				[
					'post_type' => $this->post_type
				]
			);

			if (! current_user_can($capability)) {
				wp_send_json_error();
			}

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if (! isset($_REQUEST['post_id'])) {
				wp_send_json_error();
			}

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if (! isset($_REQUEST['enabled'])) {
				wp_send_json_error();
			}

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$post_id = intval($_REQUEST['post_id']);
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$enabled = sanitize_text_field(wp_unslash($_REQUEST['enabled']));

			if ($enabled !== 'yes' && $enabled !== 'no') {
				wp_send_json_error();
			}

			if (! $post_id) {
				wp_send_json_error();
			}

			$atts = blocksy_get_post_options($post_id);
			$atts['is_hook_enabled'] = $enabled;
			update_post_meta($post_id, 'blocksy_post_meta_options', $atts);

			wp_send_json_success([]);
		});

		add_filter('blocksy:frontend:dynamic-js-chunks', function ($chunks) {
				$chunks[] = [
					'id' => 'blocksy_ext_content_blocks_navigation',
					'selector' => '#wp-admin-bar-blocksy_preview_hooks',
					'trigger' => [
						[
							'trigger' => 'hover',
							'selector' => '#wp-admin-bar-blocksy_preview_hooks'
						],
						[
							'trigger' => 'click',
							'selector' => '.blocksy-hook-indicator > span'
						]
					],
					'url' => blocksy_cdn_url(
						BLOCKSY_URL .
							'framework/premium/static/bundle/content-blocks-admin-ui.js'
					)
				];

				return $chunks;
		});

		add_action(
			'admin_bar_menu',
			function ($wp_admin_bar) {
				if (is_admin()) {
					return;
				}

				$capability = blc_get_capabilities()->get_wp_capability_by(
					'custom_post_type',
					[
						'post_type' => $this->post_type
					]
				);

				if (! current_user_can($capability)) {
					return;
				}

				if (! apply_filters(
					'blocksy:content-blocks:has-actions-debugger',
					true
				)) {
					return;
				}

				$wp_admin_bar->add_menu([
					'title' => __('Content Blocks', 'blocksy-companion'),
					'id' => 'blocksy_preview_hooks',
				]);

				$menu_id = 'blocksy-content-blocks';
				$wp_admin_bar->add_menu([
                    'id' => $menu_id . '_hooks',
                    'title' => __('Custom Content/Hooks', 'blocksy-companion'),
                    'href' => admin_url('edit.php?post_type=' . $this->post_type),
					'parent' => 'blocksy_preview_hooks',
					'meta' => [
						'class' => 'ct-hidden'
					]
                ]);

				$wp_admin_bar->add_menu([
					'id' => $menu_id . '_hooks_placeholder',
					'title' => '<span class="ab-icon"></span>',
					'href' => '#',
					'parent' => $menu_id . '_hooks',
				]);

				$wp_admin_bar->add_menu([
					'id' => $menu_id . '_popups',
					'title' => __('Popups', 'blocksy-companion'),
					'href' => admin_url('edit.php?post_type=' . $this->post_type),
					'parent' => 'blocksy_preview_hooks',
					'meta' => [
						'class' => 'ct-hidden'
					]
				]);

				$wp_admin_bar->add_menu([
					'id' => $menu_id . '_popups_placeholder',
					'title' => '<span class="ab-icon"></span>',
					'href' => '#',
					'parent' => $menu_id . '_popups',
				]);

				$wp_admin_bar->add_menu([
					'id' => $menu_id . '_templates',
					'title' => __('Custom Templates', 'blocksy-companion'),
					'href' => admin_url('edit.php?post_type=' . $this->post_type),
					'parent' => 'blocksy_preview_hooks',
					'meta' => [
						'class' => 'ct-hidden'
					]
				]);

				$wp_admin_bar->add_menu([
					'id' => $menu_id . '_templates_placeholder',
					'title' => '<span class="ab-icon"></span>',
					'href' => '#',
					'parent' => $menu_id . '_templates',
				]);

				$components = [];

				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				if (isset($_GET['blocksy_preview_hooks'])) {
					// phpcs:ignore WordPress.Security.NonceVerification.Recommended
					$current_value = sanitize_text_field(wp_unslash($_GET['blocksy_preview_hooks']));

					if (! empty($current_value)) {
						$components = explode(':', $current_value);
					}
				}

				$theme_components = $components;
				$woo_components = $components;

				if (in_array('theme', $components)) {
					$theme_components = array_filter($theme_components, function ($el) {
						return $el !== 'theme';
					});
				} else {
					$theme_components[] = 'theme';
				}

				$theme_url = count($theme_components) > 0 ? add_query_arg(
					'blocksy_preview_hooks',
					implode(':', $theme_components)
				) : remove_query_arg('blocksy_preview_hooks');

				if (in_array('woo', $components)) {
					$woo_components = array_filter($woo_components, function ($el) {
						return $el !== 'woo';
					});
				} else {
					$woo_components[] = 'woo';
				}

				$woo_url = count($woo_components) > 0 ? add_query_arg(
					'blocksy_preview_hooks',
					implode(':', $woo_components)
				) : remove_query_arg('blocksy_preview_hooks');

				$wp_admin_bar->add_menu([
					'title' => in_array('theme', $components) ? (
						__('Hide Theme Hooks', 'blocksy-companion')
					) : __('Show Theme Hooks', 'blocksy-companion'),
					'parent' => 'blocksy_preview_hooks',
					'id' => 'blocksy_preview_hooks_theme',
					'href' => $theme_url
				]);

				if (function_exists('is_woocommerce')) {
					$wp_admin_bar->add_menu([
						'title' => in_array('woo', $components) ? (
							__('Hide WooCommerce Hooks', 'blocksy-companion')
						) : __('Show WooCommerce Hooks', 'blocksy-companion'),
						'id' => 'blocksy_preview_hooks_woo',
						'parent' => 'blocksy_preview_hooks',
						'href' => $woo_url
					]);
				}
			},
			2000
		);

		add_action('blocksy:content-blocks:display-hooks', function () {
			$this->maybe_display_hooks_preview();
		});
	}

	public function localize_menu() {
		?>
        <script>
            window.blocksyContentBlocks = <?php echo wp_json_encode($this->used_hooks); ?>;
        </script>
        <?php
    }

	public function maybe_display_hooks_preview() {
		if (
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			! isset($_GET['blocksy_preview_hooks'])
			&&
			(
				! wp_doing_ajax()
				||
				empty($_SERVER['HTTP_REFERER'])
				||
				strpos(
					esc_url_raw(wp_unslash($_SERVER['HTTP_REFERER'])),
					'blocksy_preview_hooks'
				) === false
			)
		) {
			return;
		}

		$hooks_manager = new HooksManager();

		$components = [];

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if (isset($_GET['blocksy_preview_hooks'])) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$current_value = sanitize_text_field(wp_unslash($_GET['blocksy_preview_hooks']));

			if (strlen($current_value) > 0) {
				$components = explode(':', $current_value);
			}
		} else {
			if (! empty($_SERVER['HTTP_REFERER'])) {
				parse_str(
					wp_parse_url(esc_url_raw(wp_unslash($_SERVER['HTTP_REFERER'])), PHP_URL_QUERY),
					$query_string
				);

				if (
					isset($query_string['blocksy_preview_hooks'])
					&&
					strlen($query_string['blocksy_preview_hooks']) > 0
				) {
					$components = explode(
						':',
						$query_string['blocksy_preview_hooks']
					);
				}
			}
		}

		foreach ($hooks_manager->get_all_hooks() as $hook) {
			if (isset($hook['visual']) && ! $hook['visual']) {
				continue;
			}

			if ($hook['type'] !== 'action') {
				continue;
			}

			if (strpos($hook['group'], __('WooCommerce', 'blocksy-companion')) !== false) {
				if (! in_array('woo', $components)) {
					continue;
				}
			} else {
				if (! in_array('theme', $components)) {
					continue;
				}
			}

			$priority = 10;

			if (isset($hook['priority'])) {
				$priority = $hook['priority'];
			}

			add_action(
				$hook['hook'],
				function () use ($hook, $priority) {
					$class = 'blocksy-hook-indicator';

					if (isset($hook['group']) && $hook['group'] === __('WooCommerce', 'blocksy-companion')) {
						$class .= ' blocksy-woo-indicator';
					}

					if (! isset($hook['attr'])) {
						$hook['attr'] = ['class' => $class];
					} else {
						$old = $hook['attr'];
						$hook['attr'] = [];

						$hook['attr']['class'] = 'blocksy-hook-indicator';

						$hook['attr'] = array_merge(
							$hook['attr'],
							$old
						);
					}

					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo '<div ' . blocksy_attr_to_html($hook['attr']) . '>';
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo $hook['hook'];
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo '<span data-hook="' . $hook['hook'] . '::' . $priority . '"></span>';
					echo '</div>';
				},
				$priority
			);
		}

		if (function_exists('WC')) {
			add_filter(
				'woocommerce_add_to_cart_form_action',
				function ($url) use ($components) {
					return add_query_arg(
						'blocksy_preview_hooks',
						implode(':', $components),
						$url
					);
				}
			);
		}
	}
}
