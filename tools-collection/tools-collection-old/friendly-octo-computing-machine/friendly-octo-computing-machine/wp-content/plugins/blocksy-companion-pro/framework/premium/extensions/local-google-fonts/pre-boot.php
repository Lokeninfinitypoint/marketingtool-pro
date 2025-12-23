<?php

class BlocksyExtensionLocalGoogleFontsPreBoot {
	public function __construct() {
		add_action('admin_enqueue_scripts', function () {
			if (! function_exists('get_plugin_data')) {
				require_once(ABSPATH . 'wp-admin/includes/plugin.php');
			}

			$data = get_plugin_data(BLOCKSY__FILE__);

			if (! function_exists('blocksy_is_dashboard_page')) return;
			if (! blocksy_is_dashboard_page()) return;

			wp_enqueue_script(
				'blocksy-ext-local-google-fonts-admin-dashboard-scripts',
				BLOCKSY_URL . 'framework/premium/extensions/local-google-fonts/dashboard-static/bundle/main.js',
				['ct-options-scripts', 'ct-dashboard-scripts'],
				$data['Version'],
				false
			);
		});
	}

	public function ext_action($payload) {
		$ext = \Blocksy\Plugin::instance()->extensions->get('local-google-fonts');

		if (
			! isset($payload['type'])
			||
			! isset($payload['settings'])
			||
			! $ext
		) {
			return;
		}

		if ($payload['type'] === 'start-persistence') {
			$ext->start_fonts_persistence($payload['settings']);
		}

		if ($payload['type'] === 'persist-single-font') {
			$ext->persist_fonts_for($payload['settings']);
		}

		if ($payload['type'] === 'conclude-persistence') {
			$payload['settings'] = $ext->conclude_fonts_persistence($payload['settings']);
			do_action('blocksy:dynamic-css:refresh-caches');
		}

		if ($payload['type'] === 'preload-urls') {
			$ext->preload_urls($payload['settings']);
		}


		return $this->ext_data([
			'settings' => $payload['settings']
		]);
	}

	public function ext_data($args = []) {
		$all_google_fonts = [];
		$all_google_fonts_map = [];

		if (class_exists('\Blocksy\FontsManager')) {
			$m = new \Blocksy\FontsManager();

			$all_google_fonts = $m->get_googgle_fonts();

			foreach ($all_google_fonts as $font) {
				$all_google_fonts_map[$font['family']] = [
					'label' => $font['family']
				];
			}
		}

		$settings = get_option('blocksy_ext_local_google_fonts_settings', [
			'cached_css' => '',
			'fonts' => [],
			'last_saved' => null
		]);

		if (! $settings) {
			$settings = [
				'cached_css' => '',
				'fonts' => [],
				'last_saved' => null
			];
		}

		return wp_parse_args($args, [
			'settings' => $settings,
			'all_google_fonts' => $all_google_fonts,
			'all_google_fonts_map' => $all_google_fonts_map
		]);
	}
}


