<?php

namespace Blocksy\Extensions\PostTypesExtra;

class EstimatedReadTime {
	public function __construct() {

		add_filter(
			'blocksy:options:meta:meta_elements',
			function ($layers, $prefix, $computed_cpt) {

				$read_time_options = apply_filters(
					'blocksy:general:card:options:icon',
					[],
					'blc blc-book'
				);

				$read_time_options_conditions = null;

				if (! empty($read_time_options)) {
					$read_time_options_conditions = [
						'meta_type' => 'icons',
					];

					$read_time_options = [
						blocksy_rand_md5() => [
							'type' => 'ct-condition',
							'condition' => [ 'meta_type' => 'icons' ],
							'values_source' => 'parent',
							'options' => $read_time_options
						]
					];
				}

				$layers['estimated_read_time'] = [
					'label' => __('Read Time', 'blocksy-companion'),
					'options' => $read_time_options,
					'options_condition' => $read_time_options_conditions,
				];

				return $layers;
			},
			10, 3
		);

		add_filter(
			'blocksy:options:meta:meta_default_elements',
			function ($layers, $prefix, $computed_cpt) {
				$layers[] = [
					'id' => 'estimated_read_time',
					'enabled' => false
				];

				return $layers;
			},
			10, 3
		);

		add_action(
			'blocksy:post-meta:render-meta',
			[$this, 'render_read_time'],
			10, 3
		);
	}

	public function render_read_time($id, $meta, $args) {
		if ($id !== 'estimated_read_time') {
			return;
		}

		$value = $this->get_read_time();

		if (empty(trim($value))) {
			return;
		}

		if ($args['meta_type'] === 'label') {
			$value = '<span>' . __('Read Time', 'blocksy-companion') . '</span>' . $value;
		}

		if ($args['meta_type'] === 'icons' || $args['force_icons']) {
			$value = blc_get_icon([
				'icon_descriptor' => blocksy_akg('icon', $meta, [
					'icon' => 'blc blc-book'
				]),
				'icon_container' => false
			]) . $value;
		}

		blocksy_html_tag_e(
			'li',
			[
				'class' => 'meta-read-time'
			],
			$value
		);
	}

	public function get_read_time() {
		global $post;

		if (! $post) {
			return '';
		}

		// Use strip_tags() instead of wp_strip_all_tags() for accurate word counting
		// wp_strip_all_tags() is more aggressive and may affect word count calculations
		// phpcs:ignore WordPress.WP.AlternativeFunctions.strip_tags_strip_tags
		$content = strip_tags(
			wp_encode_emoji(get_post_field('post_content', $post->ID))
		);

		$string_helpers = new \Blocksy\StringHelpers($content);
		$word_count = $string_helpers->count_words();

		if ($word_count === 0) {
			return '';
		}

		$image_count = substr_count($content, '<img');

		$reading_time = $word_count / 200;
		$image_time = ($image_count * 10) / 60;
		$total_time = round($reading_time + $image_time);

		return blc_safe_sprintf(
			// translators: %s is the number of minutes
			_n('%s min', '%s mins', $total_time, 'blocksy-companion'),
			number_format_i18n($total_time)
		);
	}
}

