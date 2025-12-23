<?php

class BlocksyExtensionCustomFonts {
	public $storage = null;

	public function __construct() {
		$this->storage = new \Blocksy\Extensions\CustomFonts\Storage();

		add_filter(
			'gspb_local_font_array',
			function ($localfont) {
				$localfonts = [];

				if (! empty($localfont)) {
					$localfonts = json_decode($localfont, true);
				}

				$settings = $this->storage->get_settings();

				$themefonts = [];

				foreach ($settings['fonts'] as $family) {
					if (empty($family['variations'])) {
						continue;
					}

					$themefonts[$this->get_family_for_name($family['name'])] = [
						'ttf' => '',
						'woff2' => '',
						'woff' => '',
						'label' => $family['name']
					];
				}

				foreach ($settings['stacks'] as $stack) {
					$themefonts["var(--theme-font-stack-" . $stack . ")"] = [
						'ttf' => '',
						'woff2' => '',
						'woff' => '',
						'label' => ucwords(str_replace('-', ' ', $stack)),
					];
				}

				$mergefonts = array_merge($localfonts, $themefonts);

				return json_encode($mergefonts);
			}
		);

		add_filter('stackable_enqueue_font', function ($do, $font_name) {
			if (strpos($font_name, 'ct_font_') !== false) {
				return false;
			}

			return $do;
		}, 10, 2);

		add_filter('fl_theme_system_fonts', [$this, 'handle_beaver_fonts'] );
		add_filter('fl_builder_font_families_system', [$this, 'handle_beaver_fonts']);

		add_filter('blocksy_typography_font_sources', function ($sources) {
			$font_families = [];
			$fonts = $this->storage->get_normalized_fonts_list();

			foreach ($fonts as $single_family) {
				if (! is_array($single_family['variations'])) {
					continue;
				}

				if (count($single_family['variations']) === 0) {
					continue;
				}

				$all_variations= array_map(function ($variation) {
					return $variation['variation'];
				}, $single_family['variations']);

				$is_variable = false;

				if (
					isset($single_family['fontType'])
					&&
					$single_family['fontType'] === 'variable'
				) {
					$all_variations = $this->get_all_variations(
						!! $single_family['variations'][1]['url']
					);

					$is_variable = true;
				}

				$font_families[] = [
					'family' => $this->get_family_for_name($single_family['name']),
					'display' => $single_family['name'],
					'source' => 'file',
					'variations' => [],
					'all_variations' => $all_variations,
					'variable' => $is_variable
				];
			}

			$settings = $this->storage->get_settings();

			$stacks = $this->storage->get_font_stacks();

			foreach ($settings['stacks'] as $stack) {
				if (! isset($stacks[$stack])) {
					continue;
				}

				$font_family = "var(--theme-font-stack-" . $stack . ")";

				$font_families[] = [
					'family' => $font_family,
					'display' => ucwords(str_replace('-', ' ', $stack)),
					'source' => 'file',
					'variations' => [],
					'all_variations' => $this->get_all_variations(true),
					'variable' => false
				];
			}

			if (! empty($font_families)) {
				$sources['file'] = [
					'type' => 'file',
					'families' => $font_families
				];
			}

			return $sources;
		});

		add_filter(
			'wp_check_filetype_and_ext',
			function ($types, $file, $filename, $mimes) {
				if (false !== strpos($filename, '.woff2')) {
					$types['ext'] = 'woff2';
					$types['type'] = 'font/woff2|application/octet-stream|font/x-woff2';
				}

				if (false !== strpos($filename, '.ttf')) {
					$types['ext'] = 'ttf';
					$types['type'] = 'application/x-font-ttf';
				}

				return $types;
			},
			10, 4
		);

		add_filter('upload_mimes', function ($mimes) {
			$mimes['woff2'] = 'font/woff2|application/octet-stream|font/x-woff2';
			$mimes['ttf'] = 'application/x-font-ttf';

			return $mimes;
		});

		add_action('blocksy:global-dynamic-css:enqueue:admin', function ($args) {
			$typography = new \Blocksy\FontsManager();

			$font_faces = $this->get_final_css();

			if (! empty($font_faces)) {
				$args['css']->put(
					\Blocksy_Css_Injector::get_inline_keyword(),
					$font_faces
				);
			}
		});

		add_action('blocksy:global-dynamic-css:enqueue', function ($args) {
			$typography = new \Blocksy\FontsManager();

			$should_add_dynamic_css = blocksy_dynamic_styles_should_call(
				array_merge([
					'chunk' => 'global'
				], $args)
			);

			if (! $should_add_dynamic_css) {
				return;
			}

			$font_faces = $this->get_final_css();

			if (! empty($font_faces)) {
				$args['css']->put(
					\Blocksy_Css_Injector::get_inline_keyword(),
					$font_faces
				);
			}
		});

		add_action('init', function () {
			if (function_exists('blocksy_add_early_inline_style_in_gutenberg')) {
				blocksy_add_early_inline_style_in_gutenberg(function () {
					return $this->get_final_css();
				});
			}
		});

		add_filter('elementor/fonts/groups', function ($font_groups) {
			$font_groups['blocksy-custom-fonts'] = __('Custom Fonts', 'blocksy-companion');
			return $font_groups;
		});

		add_filter('elementor/fonts/additional_fonts', function ($fonts) {
			$settings = $this->storage->get_settings();

			foreach ($settings['fonts'] as $family) {
				if (empty($family['variations'])) {
					continue;
				}

				$fonts[$this->get_family_for_name($family['name'])] = 'blocksy-custom-fonts';
			}

			foreach ($settings['stacks'] as $stack) {
				$fonts["var(--theme-font-stack-" . $stack . ")"] = 'blocksy-custom-fonts';
			}

			return $fonts;
		});

		add_filter(
			'blocksy:typography:theme-json-font-families',
			[$this, 'theme_json_fonts']
		);
	}

	public function theme_json_fonts($fonts_to_add) {
		$settings = $this->storage->get_settings();

		$fonts = $this->storage->get_normalized_fonts_list();

		foreach ($fonts as $single_family) {
			if (! is_array($single_family['variations'])) {
				continue;
			}

			if (count($single_family['variations']) === 0) {
				continue;
			}

			$fonts_to_add[] = [
				'fontFamily' => $this->get_family_for_name($single_family['name']),
				'name' => $single_family['name'],
				'slug' => $single_family['name'],
				'fontFace' => []
			];
		}

		$stacks = $this->storage->get_font_stacks();

		foreach ($settings['stacks'] as $stack) {
			if (! isset($stacks[$stack])) {
				continue;
			}

			$font_family = "var(--theme-font-stack-" . $stack . ")";

			$fonts_to_add[] = [
				'fontFamily' => $font_family,
				'name' => ucwords(str_replace('-', ' ', $stack)),
				'slug' => $stack,
				'fontFace' => []
			];
		}

		return $fonts_to_add;
	}

	private function get_format_for_url($s) {
		$map = [
			'woff2' => 'woff2',
			'ttf' => 'truetype'
		];

		$n = strrpos($s,".");
		$ext = ($n===false) ? "" : substr($s,$n+1);

		if (! isset($map[$ext])) {
			return $ext;
		}

		return $map[$ext];
	}

	private function get_final_css() {
		$font_faces = $this->get_font_faces_for();
		$stacks_css = $this->get_font_stacks_css();

		if (empty($font_faces) && empty($stacks_css)) {
			return '';
		}

		return $stacks_css . $font_faces;
	}

	private function get_font_stacks_css() {
		$settings = $this->storage->get_settings();

		if (
			! isset($settings['stacks'])
			&&
			empty($settings['stacks'])
		) {
			return '';
		}

		$result = [];

		$stacks = $this->storage->get_font_stacks();

		foreach ($settings['stacks'] as $stack) {
			if (! isset($stacks[$stack])) {
				continue;
			}

			$result[] = '--theme-font-stack-' . $stack . ': ' . $stacks[$stack] . ';';
		}

		return ":root {" . implode('', $result) . "}";
	}

	private function get_font_faces_for() {
		$to_enqueue = [];

		$settings = $this->storage->get_settings();

		$fonts = $this->storage->get_normalized_fonts_list();

		foreach ($fonts as $single_font) {
			$single_family = $this->get_family_for_name($single_font['name']);

			foreach ($single_font['variations'] as $single_variation) {
				if (! isset($to_enqueue[$single_family])) {
					$to_enqueue[$single_family] = [$single_variation['variation']];
				} else {
					$to_enqueue[$single_family][] = $single_variation['variation'];
				}
			}
		}

		if (empty($to_enqueue)) {
			return '';
		}

		$font_faces = '';

		foreach ($to_enqueue as $family => $variations) {
			$family_descriptor = $this->get_family_descriptor($family);

			if (
				isset($family_descriptor['fontType'])
				&&
				$family_descriptor['fontType'] === 'variable'
			) {
				$font_faces .= $this->get_variable_font_face($family_descriptor);
				continue;
			}

			foreach ($variations as $variation) {
				$variation_descriptor = $this->get_variation_descriptor(
					$family, $variation
				);

				if (! $variation_descriptor) {
					continue;
				}

				$url = $variation_descriptor['url'];
				$url = blc_normalize_site_url($url);

				if (empty($url)) {
					continue;
				}

				$variation_css = blocksy_get_css_for_variation($variation);
				$format = $this->get_format_for_url($url);

				$font_faces .= '@font-face {';
				$font_faces .= 'font-family: ' . $family . ';';
				$font_faces .= "font-style: " . $variation_css['style'] . ";";
				$font_faces .= "font-weight: " . $variation_css['weight'] . ";";
				$font_faces .= "font-display: swap;";
				$font_faces .= "src: url('" . $url . "') format('" . $format . "');";
				$font_faces .= '}';
			}
		}

		return $font_faces;
	}

	public function get_variable_font_face($family_descriptor) {
		$regular_url = $family_descriptor['variations'][0]['url'];

		$italic_url = '';

		if (
			isset($family_descriptor['variations'][1])
			&&
			isset($family_descriptor['variations'][1]['url'])
		) {
			$italic_url = $family_descriptor['variations'][1]['url'];
		}

		if (empty($regular_url)) {
			return '';
		}

		$font_face = '';

		$format = $this->get_format_for_url($regular_url);

		$font_face .= '@font-face {';
		$font_face .= 'font-family: ' . $this->get_family_for_name($family_descriptor['name']) . ';';

		if (empty($italic_url)) {
			$font_face .= "font-style: oblique 0deg 5deg;";
		} else {
			$font_face .= "font-style: normal;";
		}

		$regular_url = blc_normalize_site_url($regular_url);

		$font_face .= "font-weight: 100 900;";
		$font_face .= "font-display: swap;";
		$font_face .= "src: url('" . $regular_url . "') format('" . $format . "');";
		$font_face .= '}';

		if (! empty($italic_url)) {
			$italic_url = blc_normalize_site_url($italic_url);

			$format = $this->get_format_for_url($italic_url);
			$font_face .= '@font-face {';
			$font_face .= 'font-family: ' . $this->get_family_for_name($family_descriptor['name']) . ';';
			$font_face .= "font-style: italic;";
			$font_face .= "font-weight: 100 900;";
			$font_face .= "font-display: swap;";
			$font_face .= "src: url('" . $italic_url . "') format('" . $format . "');";
			$font_face .= '}';
		}

		return $font_face;
	}

	public function get_family_descriptor($family) {
		$fonts = $this->storage->get_normalized_fonts_list();

		foreach ($fonts as $font_descriptor) {
			if (
				strtolower(
					str_replace(
						' ',
						'',
						$font_descriptor['name']
					)
				) !== strtolower(
					str_replace(
						' ',
						'',
						str_replace(
							'_', '',
							str_replace(
								'ct_font_',
								'',
								$family
							)
						)
					)
				)
			) {
				continue;
			}

			return $font_descriptor;
		}

		return null;
	}

	public function get_variation_descriptor($family, $variation) {
		$fonts = $this->storage->get_normalized_fonts_list();

		foreach ($fonts as $font_descriptor) {
			if (
				strtolower(
					str_replace(
						' ',
						'',
						$font_descriptor['name']
					)
				) !== strtolower(
					str_replace(
						' ',
						'',
						str_replace(
							'_', '',
							str_replace(
								'ct_font_',
								'',
								$family
							)
						)
					)
				)
			) {
				continue;
			}

			foreach ($font_descriptor['variations'] as $variation_descriptor) {
				if ($variation !== $variation_descriptor['variation']) {
					continue;
				}

				return $variation_descriptor;
			}
		}

		return null;
	}

	private function get_all_variations($has_italic = true) {
		if ($has_italic) {
			return [
				'n1', 'i1', 'n2',
				'i2', 'n3', 'i3',
				'n4', 'i4', 'n5',
				'i5', 'n6', 'i6',
				'n7', 'i7', 'n8',
				'i8', 'n9', 'i9',
			];
		}

		return [
			'n1', 'n2', 'n3',
			'n4', 'n5', 'n6',
			'n7', 'n8', 'n9'
		];
	}

	public function handle_beaver_fonts($system_fonts) {
		$font_families = [];
		$fonts = $this->storage->get_normalized_fonts_list();

		if (! isset($fonts)) {
			return $system_fonts;
		}

		foreach ($fonts as $single_family) {
			if (! is_array($single_family['variations'])) {
				continue;
			}

			if (count($single_family['variations']) === 0) {
				continue;
			}

			$all_variations = array_map(function ($variation) {
				$variation = $variation['variation'];

				$initial_variation = $variation;

				$variation = str_replace('n', '', $variation);
				$variation = str_replace('i', '', $variation);
				$variation = intval($variation) * 100;

				if ($initial_variation[0] === 'i') {
					$variation .= 'i';
				}

				return $variation;
			}, $single_family['variations']);

			$system_fonts[$this->get_family_for_name($single_family['name'])] = array(
				'fallback' => 'Verdana, Arial, sans-serif',
				'weights' => $all_variations
			);
		}

		return $system_fonts;
	}

	private function get_family_for_name($name) {
		return str_replace(' ', '_', 'ct_font_' . strtolower(
			preg_replace('/(?<!^)[A-Z]/', '_$0', $name)
		));
	}
}

