<?php

namespace Blocksy\CustomPostType\Integrations;

class GreenShift extends \Blocksy\CustomPostTypeRenderer {
	public function get_content($args = []) {
		return \Blocksy\CustomPostTypeRenderer::NOT_IMPLEMENTED;
	}

	public function pre_output() {
		wp_enqueue_style('wp-block-library');

		add_action(
			'wp_enqueue_scripts',
			function () {
				$content_post = get_post($this->id);
				$blocks = parse_blocks($content_post->post_content);

				$gspb_css_content = gspb_get_inline_styles_blocks($blocks);
				$gspb_css_content = get_post_meta(
					$this->id,
					'_gspb_post_css',
					true
				);

				foreach ($blocks as $block) {
					if (empty($block['attrs']['inlineCssStyles'])) {
						continue;
					}

					$dynamic_style = $block['attrs']['inlineCssStyles'];
					$dynamic_style = gspb_get_final_css($dynamic_style);
					$dynamic_style = gspb_quick_minify_css($dynamic_style);

					$dynamic_style = htmlspecialchars_decode($dynamic_style);

					$gspb_css_content .= $dynamic_style;
				}

				if (empty($gspb_css_content)) {
					return;
				}

				$gspb_saved_css_content = gspb_get_final_css($gspb_css_content);
				$final_css = $gspb_saved_css_content;

				$css_id = 'greenshift-post-css-' . $this->id;

				// phpcs:disable WordPress.WP.EnqueuedResourceParameters.MissingVersion
				wp_register_style($css_id, false);
				// phpcs:enable WordPress.WP.EnqueuedResourceParameters.MissingVersion

				wp_enqueue_style($css_id);
				wp_add_inline_style($css_id, $final_css);
			}
		);
	}

	public function compute_inline_styles() {
		$gspb_css_content = get_post_meta(
			$this->id,
			'_gspb_post_css',
			true
		);

		if (
			! $gspb_css_content
			&&
			get_post($this->id)
		) {
			$content_post = get_post($this->id);

			$blocks = parse_blocks($content_post->post_content);

			$gspb_css_content = gspb_get_inline_styles_blocks($blocks);

			foreach ($blocks as $block) {
				if (empty($block['attrs']['inlineCssStyles'])) {
					continue;
				}

				$dynamic_style = $block['attrs']['inlineCssStyles'];
				$dynamic_style = gspb_get_final_css($dynamic_style);
				$dynamic_style = gspb_quick_minify_css($dynamic_style);
				$dynamic_style = htmlspecialchars_decode($dynamic_style);
				$gspb_css_content .= $dynamic_style;
			}
		}

		return $gspb_css_content;
	}
}
