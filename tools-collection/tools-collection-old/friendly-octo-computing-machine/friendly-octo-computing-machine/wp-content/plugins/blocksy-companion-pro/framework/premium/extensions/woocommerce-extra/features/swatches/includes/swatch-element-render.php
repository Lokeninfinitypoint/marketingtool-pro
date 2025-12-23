<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class SwatchElementRender {
	private $element_descriptor = [];

	public function __construct($element_descriptor) {
		$this->element_descriptor = $element_descriptor;
	}

	public function get_output($skip_tooltip = false) {
		$picker = '';

		$tooltip_type = blocksy_akg(
			'tooltip_type',
			$this->element_descriptor['element_atts'],
			'default'
		);

		$tooltip_mask = blocksy_akg(
			'tooltip_mask',
			$this->element_descriptor['element_atts'],
			'{term_name}'
		);

		$tooltip_image = blocksy_akg(
			'tooltip_image',
			$this->element_descriptor['element_atts'],
			''
		);

		if (
			! $skip_tooltip
			&&
			$tooltip_type !== 'none'
		) {
			if (
				$tooltip_type === 'default'
				||
				empty($tooltip_image)
				||
				! isset($tooltip_image['attachment_id'])
			) {
				$tooltip_message = str_replace(
					'{term_name}',
					esc_attr($this->element_descriptor['element_label']) . (! $this->element_descriptor['is_out_of_stock'] ? '' : ' - ' . __('Out of Stock', 'blocksy-companion')),
					$tooltip_mask
				);

				if (! empty($tooltip_message)) {
					$picker .= '<span class="ct-tooltip">' . $tooltip_message . '</span>';
				}
			} else {
				$picker .= blocksy_html_tag(
					'span',
					[
						'class' => 'ct-tooltip',
						'data-tooltip-type' => 'image'
					],
					blocksy_media(
						[
							'attachment_id' => $tooltip_image['attachment_id'],
							'size' => 'thumbnail',
							'ratio' => '1/1',
							'tag_name' => 'div',
						]
					)
				);
			}
		}

		if ($this->element_descriptor['element_type'] === 'color') {
			$picker .= $this->get_color_output();
		}

		if ($this->element_descriptor['element_type'] === 'image') {
			$picker .= $this->get_image_output();
		}

		if ($this->element_descriptor['element_type'] === 'button') {
			$picker .= $this->get_button_output();
		}

		if ($this->element_descriptor['element_type'] === 'mixed') {
			$picker .= $this->get_mixed_output();
		}

		$class = [
			'ct-swatch-container'
		];

		if ($this->element_descriptor['is_selected']) {
			$class[] = 'active';
		}

		if ($this->element_descriptor['is_limited']) {
			$class[] = 'ct-limited';
		}

		if ($this->element_descriptor['is_out_of_stock']) {
			$woocommerce_hide_out_of_stock_items = get_option(
				'woocommerce_hide_out_of_stock_items'
			) === 'yes';

			if ($woocommerce_hide_out_of_stock_items) {
				$class[] = 'ct-hidden';
			} else {
				$class[] = 'ct-out-of-stock';
			}
		}

		if ($this->element_descriptor['is_invalid']) {
			$class[] = 'ct-hidden';
		}

		$out = '';

		$content = apply_filters('woocommerce_swatches_picker_html', $picker, $this);

		if (! empty($content)) {
			$out = blocksy_html_tag(
				'div',
				[
					'class' => implode(' ', $class),
					'data-value' => wp_slash(esc_attr($this->element_descriptor['element_slug'])),
				],
				$content
			);
		}

		return $out;
	}

	public function get_mixed_output() {
		$subtype = blocksy_akg('mixed_subtype', $this->element_descriptor['element_atts'], 'color');

		if ($subtype === 'color') {
			return $this->get_color_output();
		}

		if ($subtype === 'image') {
			return $this->get_image_output();
		}

		return '';
	}

	public function get_image_output() {
		$thumbnail_id = null;

		$element_atts = $this->element_descriptor['element_atts'];

		if (isset($element_atts['image']['attachment_id'])) {
			$thumbnail_id = $element_atts['image']['attachment_id'];
		}

		return blocksy_media(
			[
				'attachment_id' => $thumbnail_id,
				'ratio' => '1/1',
				'size' => 'thumbnail',
				'tag_name' => 'span',
				'class' => 'ct-swatch',
				'img_atts' => ['class' => 'ct-swatch-content'],
			]
		);
	}

	public function get_color_output() {
		$primary = '#FFF';
		$secondary = 'CT_CSS_SKIP_RULE';

		$element_atts = $this->element_descriptor['element_atts'];
		$color_type = blocksy_akg('color_type', $element_atts, 'simple');


		if (
			isset($element_atts['accent_color']['default']['color'])
			&&
			$element_atts['accent_color']['default']['color'] !== 'CT_CSS_SKIP_RULE'
		) {
			$primary = $element_atts['accent_color']['default']['color'];
		}

		if (
			$color_type === 'dual'
			&&
			isset($element_atts['accent_color']['secondary']['color'])
			&&
			$element_atts['accent_color']['secondary']['color'] !== 'CT_CSS_SKIP_RULE'
		) {
			$secondary = $element_atts['accent_color']['secondary']['color'];
		}

		if (
			$primary === 'CT_CSS_SKIP_RULE'
			&&
			$secondary === 'CT_CSS_SKIP_RULE'
		) {
			return '';
		}

		$color_css = 'background-color: ' . $primary . ';';

		if ($secondary !== 'CT_CSS_SKIP_RULE') {

			$color_css = 'background-image: linear-gradient(-45deg, ' . $secondary . ' 0%, ' . $secondary . ' 50%, ' . $primary . ' 50%, ' . $primary . ' 100%)';
		}

		return blocksy_html_tag(
			'span',
			['class' => 'ct-swatch'],
			blocksy_html_tag(
				'span',
				[
					'class' => 'ct-swatch-content',
					'style' => $color_css
				],
			)
		);
	}

	public function get_button_output() {
		$button_label = $this->element_descriptor['element_label'];

		$maybe_short_name = blocksy_akg(
			implode('/', [
				'element_atts',
				'short_name'
			]),
			$this->element_descriptor,
			''
		);

		if (! empty($maybe_short_name)) {
			$button_label = $maybe_short_name;
		}

		return blocksy_html_tag(
			'span',
			['class' => 'ct-swatch'],
			blocksy_html_tag(
				'span',
				['class' => 'ct-swatch-content'],
				$button_label
			)
		);
	}
}

