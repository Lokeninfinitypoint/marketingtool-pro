<?php

namespace Blocksy;

class ContentBlocksRenderer extends CustomPostTypeRenderer {
	public function get_content($args = []) {
		$id = $this->id;

		$template_type = get_post_meta($id, 'template_type', true);

		return parent::get_content([
			'switch_global_post' => (
				$template_type === 'single'
				||
				$template_type === 'archive'
			)
		]);
	}

	public function pre_output() {
		if (! $this->should_pre_output()) {
			return;
		}

		$id = $this->id;

		do_action('blocksy:pro:content-blocks:pre-output', $id);

		$atts = blocksy_get_post_options($id);
		$template_type = get_post_meta($id, 'template_type', true);

		if ($template_type === 'popup') {
			add_action('wp_enqueue_scripts', function () use ($id) {
				if (! function_exists('get_plugin_data')){
					require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
				}

				$data = get_plugin_data(BLOCKSY__FILE__);

				if (is_admin()) return;

				$atts = blocksy_get_post_options($id);

				$load_content_with_ajax = blocksy_akg(
					'load_content_with_ajax',
					$atts,
					'no'
				);

				if ($load_content_with_ajax === 'yes') {
					$content_post = get_post($id);

					$blocks = parse_blocks($content_post->post_content);

					foreach ($blocks as $block) {
						render_block($block);
					}
				}

				wp_enqueue_style(
					'blocksy-pro-popup-styles',
					BLOCKSY_URL . 'framework/premium/static/bundle/popups.min.css',
					['ct-main-styles'],
					$data['Version']
				);
			}, 50);

			add_action(
				'blocksy:global-dynamic-css:enqueue:inline',
				function ($args) use ($id) {
					$atts = blocksy_get_post_options($id);

					blocksy_theme_get_dynamic_styles(array_merge([
						'path' => dirname(__FILE__) . '/popup-dynamic-styles.php',
						'chunk' => 'hooks',
						'id' => $id,
						'atts' => $atts
					], $args));
				},
				10, 3
			);
		}

		add_action(
			'blocksy:global-dynamic-css:enqueue:inline',
			function ($args) use ($id) {
				$atts = blocksy_get_post_options($id);

				blocksy_theme_get_dynamic_styles(array_merge([
					'path' => dirname(__FILE__) . '/dynamic-styles.php',
					'chunk' => 'hooks',
					'id' => $id,
					'atts' => $atts
				], $args));
			},
			10, 3
		);

		return parent::pre_output();
	}
}

