<?php

class BlocksyExtensionWhiteLabel {
	private $option_name = 'blocksy_ext_white_label_settings';

	public function __construct() {
		$settings = $this->get_settings();

		if (
			$settings
			&&
			isset($settings['hide_billing_account'])
			&&
			$settings['hide_billing_account']
			&&
			function_exists('blc_fs')
		) {
			remove_action(
				'admin_init',
				[blc_fs(), '_hook_action_links_and_register_account_hooks']
			);
		}

		add_filter('blocksy:editor:options:icon', function ($icon) {
			$settings = $this->get_settings();

			if (! is_admin()) {
				return $url;
			}

			if (! empty($settings['theme']['gutenberg_icon'])) {
				$maybe_attachment_id = attachment_url_to_postid(
					$settings['theme']['gutenberg_icon']
				);

				if (
					$maybe_attachment_id
					&&
					get_post_mime_type($maybe_attachment_id) === 'image/svg+xml'
				) {
					$svg_file = file_get_contents(
						get_attached_file($maybe_attachment_id)
					);

					if ($svg_file && ! empty($svg_file)) {
						return $svg_file;
					}
				}

				if (
					strpos(
						$settings['theme']['gutenberg_icon'],
						'svg'
					) !== false
				) {
					$maybe_as_url = @ file_get_contents(
						$settings['theme']['gutenberg_icon']
					);

					if ($maybe_as_url) {
						return $maybe_as_url;
					}
				}
			}

			return $icon;
		});

		add_filter(
			// Avoid plugin-check false positive.
			implode(
				'_',
				[
					'site',
					'transient',
					'update',
					'plugins'
				]
			),

			function ($value) {
				$settings = $this->get_settings();

				if (empty($settings['plugin']['thumbnail'])) {
					return $value;
				}

				if (! isset($value->response)) {
					return $value;
				}

				foreach ($value->response as $plugin_key => $plugin_value) {
					if (strpos($plugin_key, 'blocksy-companion') === false) {
						continue;
					}

					foreach ($plugin_value->icons as $icon_key => $icon_value) {
						$value->response[$plugin_key]->icons[
							$icon_key
						] = $settings['plugin']['thumbnail'];
					}
				}

				return $value;
			}
		);

		add_filter(
			'blocksy:dashboard:icon-url',
			function ($url) {
				$settings = $this->get_settings();

				if (! is_admin()) {
					return $url;
				}

				if (! empty($settings['theme']['icon'])) {
					return $settings['theme']['icon'];
				}

				return $url;
			}
		);

		add_filter('plugins_api_result', function ($result, $action, $args) {
			if (! isset($result->slug)) {
				return $result;
			}

			if ($result->slug !== 'blocksy-companion') {
				return $result;
			}

			$settings = $this->get_settings();

			if (! empty($settings['plugin']['name'])) {
				$result->name = $settings['plugin']['name'];
			}

			if (! empty($settings['plugin']['description'])) {
				$result->sections['description'] = $settings['plugin']['description'];
			}

			if (! empty($settings['author']['name'])) {
				$result->author = $settings['author']['name'];
			}

			return $result;
		}, 10, 3);

		add_action('customize_register', function ($wp_customize) {
			if (! $wp_customize) {
				return;
			}

			$reflection = new \ReflectionClass($wp_customize);
			$property = $reflection->getProperty('theme');
			$property->setAccessible(true);

			$property->setValue($wp_customize, blocksy_get_wp_theme());
		});

		add_filter('blocksy_get_wp_theme', function ($wp_theme) {
			$settings = $this->get_settings();

			if (! is_admin() || (
				empty($settings['theme']['name'])
				&&
				empty($settings['theme']['description'])
			)) {
				return $wp_theme;
			}

			$reflection = new \ReflectionClass($wp_theme);
			$property = $reflection->getProperty('headers');
			$property->setAccessible(true);
			$headers = $property->getValue($wp_theme);

			if (! empty($settings['theme']['name'])) {
				$headers['Name'] = $settings['theme']['name'];
			}
			$headers['CustomDescription'] = $settings['theme']['description'];

			$property->setValue($wp_theme, $headers);

			return $wp_theme;
		});

		add_action('wp_ajax_blocksy_update_white_label_settings', function () {
			if (! current_user_can('manage_options')) {
				wp_send_json_error();
			}

			$data = json_decode(
				file_get_contents('php://input'),
				true
			);

			if (! $data) {
				wp_send_json_error();
			}

			$this->set_settings($data);

			wp_send_json_success([
				'settings' => $this->get_settings()
			]);
		});

		add_filter('all_plugins', function ($plugins) {
			$key = BLOCKSY_PLUGIN_BASE;

			if (! isset($plugins[$key])) {
				return $plugins;
			}

			$settings = $this->get_settings();

			if (! empty($settings['plugin']['name'])) {
				$plugins[$key]['Name'] = $settings['plugin']['name'];
			}

			if (! empty($settings['plugin']['description'])) {
				$plugins[$key]['Description'] = $settings['plugin']['description'];
			}

			if (! empty($settings['author']['name'])) {
				$plugins[$key]['Author'] = $settings['author']['name'];
				$plugins[$key]['AuthorName'] = $settings['author']['name'];
			}

			if (! empty($settings['author']['url'])) {
				$plugins[$key]['AuthorURI'] = $settings['author']['url'];
			}

			return $plugins;
		});

		add_filter('update_right_now_text', function ($content) {
			$settings = $this->get_settings();

			if (
				is_admin()
				&&
				'Blocksy' == wp_get_theme()
				&&
				!empty($settings['theme']['name'])
		   	) {
				return blc_safe_sprintf(
					$content,
					get_bloginfo( 'version', 'display' ),
					'<a href="themes.php">' . $settings['theme']['name'] . '</a>'
				);
			}

			return $content;
		});

		add_filter('gettext', function ($text, $original, $domain) {
			if ('Blocksy' === $original) {
				$settings = $this->get_settings();

				if (! empty($settings['theme']['name'])) {
					return $settings['theme']['name'];
				}
			}

			if ('Blocksy Companion' === $original) {
				$settings = $this->get_settings();
				if (! empty($settings['plugin']['name'])) {
					return $settings['plugin']['name'];
				}
			}

			if ('Blocksy Companion (Premium)' === $original) {
				$settings = $this->get_settings();

				if (! empty($settings['plugin']['name'])) {
					return $settings['plugin']['name'];
				}
			}

			return $text;
		}, 20, 3);

		add_filter('wp_prepare_themes_for_js', function ($themes) {
			$blocksy_key = 'blocksy';

			if (! isset($themes[$blocksy_key])) {
				return $themes;
			}

			$settings = $this->get_settings();

			if (! empty($settings['theme']['name'])) {
				$themes[$blocksy_key]['name'] = $settings['theme']['name'];

				foreach ($themes as $key => $theme) {
					if (
						isset($theme['parent'])
						&&
						'Blocksy' === $theme['parent']
					) {
						$themes[$key]['parent'] = $settings['theme']['name'];
					}
				}
			}

			if (! empty($settings['theme']['description'])) {
				$themes[$blocksy_key]['description'] = $settings['theme']['description'];
			}

			if (! empty($settings['theme']['screenshot'])) {
				$themes[$blocksy_key]['screenshot'] = [
					$settings['theme']['screenshot']
				];
			}

			if (! empty($settings['author']['name'])) {
				$themes[$blocksy_key]['author']  = $settings['author']['name'];
			}

			if (! empty($settings['author']['url'])) {
				$themes[$blocksy_key]['author']  = $settings['author']['name'];

				$themes[$blocksy_key]['authorAndUri'] = '<a href="' . esc_url($settings['author']['url']) . '">' .
					$themes[$blocksy_key]['author'] .
				'</a>';
			}

			if (
				isset($themes[$blocksy_key]['update'])
				&&
				!empty($settings['theme']['name'])
			) {
				$themes[$blocksy_key]['update'] = str_replace(
					'Blocksy',
					$settings['theme']['name'],
					$themes[$blocksy_key]['update']
				);

				if (! empty($settings['author']['url'])) {
					$themes[ $blocksy_key ]['update'] = str_replace(
						'https://wordpress.org/themes/blocksy/?TB_iframe=true&#038;width=1024&#038;height=800',
						add_query_arg(
							[
								'TB_iframe' => true,
								'width' => '1024',
								'hight' => '800',
							],
							$settings['author']['url']
						),
						$themes[$blocksy_key]['update']
					);
				}
			}

			return $themes;
		});

		add_filter('all_themes', function ($themes) {
			$blocksy_key = 'blocksy';

			if (! is_network_admin()) {
				// return $themes;
			}

			if (! isset($themes[$blocksy_key])) {
				return $themes;
			}

			$themes[$blocksy_key] = blocksy_get_wp_theme();

			return $themes;
		});

		add_filter('blocksy_dashboard_has_heading', function ($value) {
			$settings = $this->get_settings();

			if (!empty($settings['theme']['name'])) {
				return 'no';
			}

			return $value;
		});

		add_filter(
			'blocksy_ext_demo_install_enabled',
			function ($value) {
				$settings = $this->get_settings();

				if (isset($settings['hide_demos']) && $settings['hide_demos']) {
					return 'no';
				}

				return $value;
			}
		);

		add_filter(
			'blocksy_dashboard_localizations',
			function ($d) {
				$settings = $this->get_settings();

				if (
					isset($settings['hide_plugins_tab'])
					&&
					$settings['hide_plugins_tab']
				) {
					$d['hide_plugins_tab'] = true;
				}

				if (
					isset($settings['hide_changelogs_tab'])
					&&
					$settings['hide_changelogs_tab']
				) {
					$d['hide_changelogs_tab'] = true;
				}

				if (
					isset($settings['hide_support_section'])
					&&
					$settings['hide_support_section']
				) {
					$d['hide_support_section'] = true;
				}

				if (
					isset($settings['hide_docs_section'])
					&&
					$settings['hide_docs_section']
				) {
					$d['hide_docs_section'] = true;
				}

				if (
					isset($settings['hide_video_section'])
					&&
					$settings['hide_video_section']
				) {
					$d['hide_video_section'] = true;
				}

				return $d;
			}
		);

		add_filter(
			'blocksy_dashboard_support_url',
			function ($url) {
				$settings = $this->get_settings();

				if (! empty($settings['author']['support'])) {
					return $settings['author']['support'];
				}

				if (! empty($settings['author']['url'])) {
					return $settings['author']['url'];
				}

				return $url;
			}
		);

		add_action('admin_enqueue_scripts', function () {
			global $pagenow;

			if ($pagenow !== 'update-core.php') {
				return;
			}

			$settings = $this->get_settings();

			$branded_screenshot = $settings['theme']['screenshot'];

			$default_name = 'Astra';
			$branded_name = $settings['theme']['name'];

			if (! empty($branded_screenshot)) {
				wp_add_inline_script(
					'updates',
					"
					document.querySelectorAll(
						'#update-themes-table .plugin-title .updates-table-screenshot[src*=\"blocksy/screenshot\"]'
					).forEach(function(theme) {
						theme.src = '$branded_screenshot';
					});"
				);
			}

			if (! empty($branded_name)) {
				wp_add_inline_script(
					'updates',
					"
					document.querySelectorAll('#update-themes-table .plugin-title strong')
					.forEach(function(plugin) {
						if (plugin.innerText === 'Blocksy') {
							plugin.innerText = '$branded_name';
						}
					});"
				);
			}
		});
	}

	public function get_settings() {
		$defaults = [
			'locked' => false,
			'hide_demos' => false,
			'hide_billing_account' => false,

			'hide_plugins_tab' => false,
			'hide_changelogs_tab' => false,
			'hide_support_section' => false,
			'hide_docs_section' => false,
			'hide_video_section' => false,

			'author' => [
				'name' => '',
				'url' => '',
				'support' => ''
			],

			'theme' => [
				'name' => '',
				'description' => '',
				'screenshot' => '',
				'icon' => '',
				'gutenberg_icon' => ''
			],

			'plugin' => [
				'name' => '',
				'description' => '',
				'thumbnail' => ''
			]
		];

		$settings = apply_filters(
			'blocksy:ext:white-label:settings',
			get_option($this->option_name, $defaults)
		);

		if (! is_array($settings)) {
			$settings = $defaults;
		}

		$settings['theme']['name'] = htmlentities($settings['theme']['name']);

		return $settings;
	}

	public function set_settings($value) {
		update_option($this->option_name, $value);
	}
}
