<?php

namespace Blocksy\Extensions\CustomFonts;

class Storage {
	private $option_name = 'blocksy_ext_custom_fonts_settings';

	public function get_settings() {
		$custom_fonts = apply_filters(
			'blocksy_ext_custom_fonts:dynamic_fonts',
			[]
		);

		$default_value = [
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
					],
					'preloads' => [
						'variations' => ['n4'],
					]
				]
                 */
			]
		];

		$result = get_option($this->option_name, $default_value);

		if (! is_array($result)) {
			$result = $default_value;
		}

		foreach ($custom_fonts as $index => $custom_font) {
			$custom_fonts[$index]['__custom'] = true;
			$result['fonts'][] = $custom_fonts[$index];
		}

		if (! isset($result['stacks'])) {
			$result['stacks'] = [];
		}

		if (! isset($result['fonts'])) {
			$result['fonts'] = [];
		}

		return $result;
	}

	public function set_settings($value) {
		$fonts = [];

		$stacks = [];

		if (isset($value['stacks'])) {
			$stacks = $value['stacks'];
			unset($value['stacks']);
		}

		foreach ($value['fonts'] as $font) {
			if (! isset($font['__custom'])) {
				$fonts[] = $font;
			}
		}

		update_option($this->option_name, [
			'fonts' => $fonts,
			'stacks' => $stacks
		]);
	}

	public function get_normalized_fonts_list() {
		$settings = $this->get_settings();

		$fonts = [];

		foreach ($settings['fonts'] as $font) {
			foreach ($font['variations'] as $variation_index => $variation) {
				if (
					isset($variation['attachment_id'])
					&&
					! isset($variation['url'])
				) {
					$font['variations'][$variation_index]['url'] = wp_get_attachment_url(
						$variation['attachment_id']
					);
				} else {
					if (empty(
						$font['variations'][$variation_index]['url']
					)) {
						$font['variations'][$variation_index]['url'] = '';
					}
				}
			}

			$fonts[] = $font;
		}

		return $fonts;
	}

	public function get_font_stacks() {
		return apply_filters('blocksy_ext_custom_fonts:font_stacks', [
			'transitional' => "Charter, 'Bitstream Charter', 'Sitka Text', Cambria, serif",
			'old-style' => "'Iowan Old Style', 'Palatino Linotype', 'URW Palladio L', P052, serif",
			'humanist' => "Seravek, 'Gill Sans Nova', Ubuntu, Calibri, 'DejaVu Sans', source-sans-pro",
			'geometric-humanist' => "Avenir, Montserrat, Corbel, 'URW Gothic', source-sans-pro, sans-serif",
			'classical-humanist' => "Optima, Candara, 'Noto Sans', source-sans-pro, sans-serif",
			'neo-grotesque' => "Inter, Roboto, 'Helvetica Neue', 'Arial Nova', 'Nimbus Sans', Arial, sans-serif",
			'monospace-slab-serif' => "'Nimbus Mono PS', 'Courier New', monospace",
			'monospace-code' => "ui-monospace, 'Cascadia Code', 'Source Code Pro', Menlo, Consolas, 'DejaVu Sans Mono', monospace",
			'industrial' => "Bahnschrift, 'DIN Alternate', 'Franklin Gothic Medium', 'Nimbus Sans Narrow', sans-serif-condensed, sans-serif",
			'rounded-sans' => "ui-rounded, 'Hiragino Maru Gothic ProN', Quicksand, Comfortaa, Manjari, 'Arial Rounded MT', 'Arial Rounded MT Bold', Calibri, source-sans-pro, sans-serif",
			'slab-serif' => "Rockwell, 'Rockwell Nova', 'Roboto Slab', 'DejaVu Serif', 'Sitka Small', serif",
			'antique' => "Superclarendon, 'Bookman Old Style', 'URW Bookman', 'URW Bookman L', 'Georgia Pro', Georgia, serif",
			'didone' => "Didot, 'Bodoni MT', 'Noto Serif Display', 'URW Palladio L', P052, Sylfaen, serif",
			'handwritten' => "'Segoe Print', 'Bradley Hand', Chilanka, TSCu_Comic, casual, cursive"
		]);
	}
}

