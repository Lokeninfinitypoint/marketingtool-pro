<?php

namespace Blocksy;

class ExtendCoreTypographyOptions {
	public function __construct() {
		add_filter(
			'wp_theme_json_data_theme',
			[$this, 'filter_theme_json_theme']
		);
	}

	public function filter_theme_json_theme($theme_json) {
		$fonts_to_add = apply_filters(
			'blocksy:typography:theme-json-font-families',
			[]
		);

		if (empty($fonts_to_add)) {
			return $theme_json;
		}

		$theme_data = $theme_json->get_data();

		$font_data = $theme_data['settings']['typography']['fontFamilies']['theme'] ?? [];

		foreach ($fonts_to_add as $font_to_add) {
			$found = false;

			foreach ($font_data as $font) {
				if (
					isset($font['name'])
					&&
					$font['name'] === $font_to_add['name']
				) {
					$found = true;
					break;
				}
			}

			if (! $found) {
				$font_data[] = $font_to_add;
			}
		}

		$new_data = [
			'version'  => 1,
			'settings' => [
				'typography' => [
					'fontFamilies' => [
						'theme' => $font_data
					]
				]
			]
		];

		$theme_json->update_with($new_data);

		return $theme_json;
	}
}
