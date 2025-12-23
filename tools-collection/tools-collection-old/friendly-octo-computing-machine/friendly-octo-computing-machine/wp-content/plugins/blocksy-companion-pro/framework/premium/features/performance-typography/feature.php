<?php

namespace Blocksy;

class PerformanceTypography {
	private $option_name = 'blocksy_preload_fonts_settings';

	public function __construct() {
		add_filter(
			'blocksy_performance_after_emojis_customizer_options',
			[$this, 'register_typography_options']
		);

		add_action('wp_head', [$this, 'preconnect_google_fonts']);

		add_action('wp_ajax_blocksy_get_custom_fonts_list', function () {
			if (! current_user_can('edit_theme_options')) {
				wp_send_json_error();
			}

			$this->storage = new \Blocksy\Extensions\CustomFonts\Storage();

			wp_send_json_success([
				'fonts' => $this->storage->get_normalized_fonts_list()
			]);
		});
	}

	public function get_settings() {
		$default_value = [
			'custom' => [],
			'local_google_fonts' => [],
		];

		$result = get_option($this->option_name, $default_value);

		if (! is_array($result)) {
			$result = $default_value;
		}

		return $result;
	}

	public function set_settings($value) {
		$settings = $this->get_settings();

		if (! is_array($value)) {
			$value = [];
		}

		$value = array_merge($settings, $value);

		update_option($this->option_name, $value);
	}

	public function preconnect_google_fonts() {
		$custom_fonts_enabled = blc_get_ext('custom-fonts');
		$adobe_typekit_enabled = blc_get_ext('adobe-typekit');
		$local_google_fonts_enabled = blc_get_ext('local-google-fonts');

		if (
			blc_theme_functions()->blocksy_get_theme_mod('preconnect_google_fonts', 'no') === 'yes'
			&&
			!$local_google_fonts_enabled
		) {
			echo '<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />';
			echo '<link rel="preconnect" href="https://fonts.googleapis.com/" crossorigin />';
		}

		if (
			blc_theme_functions()->blocksy_get_theme_mod('preconnect_adobe_typekit', 'no') === 'yes'
			&&
			$adobe_typekit_enabled
		) {
			echo '<link rel="preconnect" href="https://use.typekit.net/" crossorigin />';
		}

		if (
			$custom_fonts_enabled
			||
			$local_google_fonts_enabled
		) {
			$allowed_exts = ['woff2', 'woff', 'ttf', 'otf'];
			$settings = $this->get_settings();

			$fonts_to_preload = [];

			if ($custom_fonts_enabled) {
				$fonts_to_preload = array_merge(
					$fonts_to_preload,
					blocksy_akg('custom', $settings, [])
				);
			}

			if ($local_google_fonts_enabled) {
				$fonts_to_preload = array_merge(
					$fonts_to_preload,
					blocksy_akg('local_google_fonts', $settings, [])
				);
			}

			$fonts_to_preload = array_values(array_unique($fonts_to_preload));

			if (! empty($fonts_to_preload)) {
				foreach ($fonts_to_preload as $key => $font) {
					$ext = pathinfo($font, PATHINFO_EXTENSION);

					if (!in_array($ext, $allowed_exts)) {
						continue;
					}

					echo '<link rel="preload" href="' . esc_url($font) . '" as="font" type="font/' . esc_attr($ext) . '" crossorigin="anonymous">';
				}
			}
		}
	}

	public function register_typography_options($opt) {
		$custom_fonts_enabled = blc_get_ext('custom-fonts');
		$adobe_typekit_enabled = blc_get_ext('adobe-typekit');
		$local_google_fonts_enabled = blc_get_ext('local-google-fonts');

		$adobe_options = [];
		$google_fonts_options = [];

		if (!$local_google_fonts_enabled) {
			$google_fonts_options = [
				'preconnect_google_fonts' => [
					'type' => 'ct-switch',
					'label' => __('Preconnect Google Fonts', 'blocksy-companion'),
					'value' => 'no',
					'divider' => 'bottom:full',
				]
			];
		}

		if ($adobe_typekit_enabled) {
			$adobe_options = [
				'preconnect_adobe_typekit' => [
					'type' => 'ct-switch',
					'label' => __('Preconnect Adobe Typekit Fonts', 'blocksy-companion'),
					'value' => 'no',
					'divider' => 'bottom:full',
				]
			];
		}

		return array_merge(
			$opt,
			$google_fonts_options,
			$adobe_options,
		);
	}
}
