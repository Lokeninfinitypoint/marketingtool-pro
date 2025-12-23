<?php

class BlocksyExtensionWhiteLabelPreBoot {
	private $option_name = 'blocksy_ext_white_label_settings';

	public function __construct() {
		add_action('admin_enqueue_scripts', function () {
			if (! function_exists('get_plugin_data')) {
				require_once(ABSPATH . 'wp-admin/includes/plugin.php');
			}

			$data = get_plugin_data(BLOCKSY__FILE__);

			if (! function_exists('blocksy_is_dashboard_page')) return;
			if (! blocksy_is_dashboard_page()) return;

			wp_enqueue_script(
				'blocksy-ext-white-label-admin-dashboard-scripts',
				BLOCKSY_URL . 'framework/premium/extensions/white-label/dashboard-static/bundle/main.js',
				['ct-options-scripts', 'ct-dashboard-scripts'],
				$data['Version'],
				false
			);
		});

		add_action('wp_ajax_blocksy_get_white_label_settings', function () {
			if (! current_user_can('manage_options')) {
				wp_send_json_error([
					'error' => 'not_allowed'
				]);
			}

			wp_send_json_success([
				'settings' => $this->get_settings()
			]);
		});

		add_action('wp_ajax_blocksy_white_label_maybe_unlock', function () {
			if (! current_user_can('manage_options')) {
				wp_send_json_error();
			}

			$settings = $this->get_settings();

			if (!isset($settings['locked'])) {
				return wp_send_json_error();
			}

			if (!$settings['locked']) {
				return wp_send_json_error();
			}

			$settings['locked'] = false;

			$this->set_settings($settings);

			wp_send_json_success([
				'settings' => $this->get_settings()
			]);
		});
	}

	public function ext_data() {
		return $this->get_settings();
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

		if (defined('BLOCKSY_WHITE_LABEL_LOCKED')) {
			$settings['locked'] = BLOCKSY_WHITE_LABEL_LOCKED;
		}

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

