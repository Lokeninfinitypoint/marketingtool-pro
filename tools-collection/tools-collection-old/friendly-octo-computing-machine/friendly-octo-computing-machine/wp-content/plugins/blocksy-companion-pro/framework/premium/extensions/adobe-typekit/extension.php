<?php

class BlocksyExtensionAdobeTypekit {
	private $option_name = 'blocksy_ext_adobe_typekit_settings';

	public function __construct() {
		add_action('wp_ajax_blocksy_get_adobe_typekit_settings', function () {
			if (! current_user_can('manage_options')) {
				wp_send_json_error();
			}

			wp_send_json_success([
				'settings' => $this->get_settings()
			]);
		});

		add_filter(
			'stackable_enqueue_font',
			function($do_enqueue, $font_name) {
				$settings = $this->get_settings();

				if (
					! isset($settings['fonts'])
					||
					empty($settings['fonts'])
				) {
					return $do_enqueue;
				}

				foreach ($settings['fonts'] as $family) {
					if ($family['css_names'][0] === $font_name) {
						return false;
					}
				}

				if (strpos($font_name, 'ct_typekit') !== false) {
					return false;
				}

				return $do_enqueue;
			},
			10, 2
		);

		add_filter('elementor/fonts/groups', function ($font_groups) {
			$font_groups['blocksy-typekit-fonts'] = __('Adobe Typekit', 'blocksy-companion');
			return $font_groups;
		});

		add_filter('elementor/fonts/additional_fonts', function ($fonts) {
			$settings = $this->get_settings();

			if (
				! isset($settings['fonts'])
				||
				empty($settings['fonts'])
			) {
				return $fonts;
			}

			foreach ($settings['fonts'] as $family) {
				$fonts[$family['css_names'][0]] = 'blocksy-typekit-fonts';
			}

			return $fonts;
		});

		add_action('wp_ajax_blocksy_update_adobe_typekit_settings', function () {
			if (! current_user_can('manage_options')) {
				wp_send_json_error();
			}

			$data = json_decode(file_get_contents('php://input'), true);

			if (! $data) {
				wp_send_json_error();
			}

			if (! isset($data['project_id'])) {
				wp_send_json_error();
			}

			$details = $this->maybe_get_project_details($data['project_id']);

			if (! $details) {
				wp_send_json_error();
			}

			$result = [
				'project_id' => $data['project_id'],
				'fonts' => $details
			];

			$this->set_settings($result);

			wp_send_json_success([
				'settings' => $this->get_settings()
			]);
		});

		add_filter('fl_theme_system_fonts', [$this, 'handle_beaver_fonts'] );
		add_filter('fl_builder_font_families_system', [$this, 'handle_beaver_fonts'] );

		add_filter('blocksy_typography_font_sources', function ($sources) {
			$settings = $this->get_settings();

			if (
				! isset($settings['fonts'])
				||
				empty($settings['fonts'])
			) {
				return $sources;
			}

			$font_families = [];

			foreach ($settings['fonts'] as $single_family) {
				if (! is_array($single_family['variations'])) {
					continue;
				}

				if (count($single_family['variations']) === 0) {
					continue;
				}

				$font_families[] = [
					'family' => 'ct_typekit_' . $single_family['css_names'][0],
					'display' => $single_family['name'],
					'source' => 'typekit',
					'kit' => $settings['project_id'],
					'variations' => [],
					'all_variations' => $single_family['variations']
				];
			}

			$sources['typekit'] = [
				'type' => 'typekit',
				'families' => $font_families
			];

			return $sources;
		});

		add_action('wp_enqueue_scripts', function () {
			if (! function_exists('get_plugin_data')) {
				require_once(ABSPATH . 'wp-admin/includes/plugin.php');
			}

			$data = get_plugin_data(BLOCKSY__FILE__);

			$settings = $this->get_settings();

			if (
				! isset($settings['project_id'])
				||
				empty($settings['project_id'])
			) {
				return;
			}

			wp_enqueue_style(
				'blocksy-typekit',
				str_replace(
					'#project_id#',
					$settings['project_id'],
					'https://use.typekit.net/#project_id#.css'
				),
				[],
				$data['Version']
			);
		});

		add_action('init', function () {
			if (function_exists('blocksy_add_early_inline_style_in_gutenberg')) {
				blocksy_add_early_inline_style_in_gutenberg(function () {
					$settings = $this->get_settings();

					if (
						! isset($settings['project_id'])
						||
						empty($settings['project_id'])
					) {
						return '';
					}

					return '@import url("' . str_replace(
						'#project_id#',
						$settings['project_id'],
						'https://use.typekit.net/#project_id#.css'
					) . '");';
				});
			}
		});

		add_filter(
			'blocksy:typography:theme-json-font-families',
			[$this, 'theme_json_fonts']
		);
	}

	public function theme_json_fonts($fonts_to_add) {
		$settings = $this->get_settings();

		if (! isset($settings['fonts'])) {
			return $fonts_to_add;
		}

		foreach ($settings['fonts'] as $single_family) {
			$fonts_to_add[] = [
				'fontFamily' => $single_family['name'],
				'name' => $single_family['name'],
				'slug' => $single_family['name'],
				'fontFace' => []
			];
		}

		return $fonts_to_add;
	}

	public function get_settings() {
		return apply_filters(
			'blocksy_ext_adobe_typekit:settings',
			get_option($this->option_name, [
				'project_id' => '',
				'fonts' => [
				/*
					[
						'name' => 'ProximaNova',
						'variations' => [
							[
								'variation' => 'n4',
								'attachment_id' => 2828,
							],

							[
								'variation' => 'n7',
								'attachment_id' => 2829,
							]
						]
					]
				 */
				]
			])
		);
	}

	public function set_settings($value) {
		update_option($this->option_name, $value);
	}

	public function maybe_get_project_details($project_id) {
		$typekit_uri = 'https://typekit.com/api/v1/json/kits/' . $project_id . '/published';

		$response = wp_remote_get($typekit_uri, [
			'timeout' => '30',
		]);

		if (
			is_wp_error($response)
			||
			wp_remote_retrieve_response_code($response) !== 200
		) {
			return null;
		}

		$info = json_decode(wp_remote_retrieve_body($response), true);

		if (! $info) {
			return null;
		}

		if (! isset($info['kit']['families'])) {
			return null;
		}

		return $info['kit']['families'];
	}

	public function handle_beaver_fonts($system_fonts) {
		$settings = $this->get_settings();

		if (
			! isset($settings['fonts'])
			||
			empty($settings['fonts'])
		) {
			return $system_fonts;
		}

		foreach ($settings['fonts'] as $single_family) {
			if (! is_array($single_family['variations'])) {
				continue;
			}

			if (count($single_family['variations']) === 0) {
				continue;
			}

			$all_variations = array_map(function ($variation) {
				if (isset($variation['variation'])) {
					$variation = $variation['variation'];
				}

				$initial_variation = $variation;

				$variation = str_replace('n', '', $variation);
				$variation = str_replace('i', '', $variation);
				$variation = intval($variation) * 100;

				if ($initial_variation[0] === 'i') {
					$variation .= 'i';
				}

				return $variation;
			}, $single_family['variations']);

			$system_fonts['ct_typekit_' . $single_family['css_names'][0]] = array(
				'fallback' => 'Verdana, Arial, sans-serif',
				'weights' => $all_variations
			);
		}

		return $system_fonts;
	}
}

