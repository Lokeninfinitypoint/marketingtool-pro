<?php

namespace Blocksy\CustomPostType\Integrations;

class UltimateProduct extends \Blocksy\CustomPostTypeRenderer {
	public function get_content($args = []) {
		return \Blocksy\CustomPostTypeRenderer::NOT_IMPLEMENTED;
	}

	public function pre_output() {
		add_action('wp_enqueue_scripts', function () {
			if (method_exists('\WOPB\Initialization', 'register_scripts_common')) {
				$init = new \WOPB\Initialization();
				$init->register_scripts_common();
			} elseif (function_exists('wopb_function')) {
				wopb_function()->register_scripts_common();
			}
		});

		add_action('wp_head', function () {
			$post_id = $this->id;

			if (
				! $post_id
				||
				! function_exists('wopb_function')
				||
				! method_exists(wopb_function(), 'get_reusable_ids')
			) {
				return;
			}

			$upload_dir_url = wp_get_upload_dir();
			$upload_css_dir_url = trailingslashit($upload_dir_url['basedir']);
			$css_dir_path = $upload_css_dir_url . "product-blocks/wopb-css-{$post_id}.css";

			// Reusable CSS
			$reusable_css = '';
			$reusable_id = wopb_function()->get_reusable_ids($post_id);

			foreach ($reusable_id as $id) {
				$reusable_dir_path = $upload_css_dir_url."product-blocks/wopb-css-{$id}.css";

				if (file_exists($reusable_dir_path)) {
					$reusable_css .= file_get_contents($reusable_dir_path);
				}else{
					$reusable_css .= get_post_meta($id, '_wopb_css', true);
				}
			}

			if (file_exists($css_dir_path)) {
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '<style type="text/css">' . file_get_contents($css_dir_path) . $reusable_css.'</style>';
			} else {
				$css = get_post_meta($post_id, '_wopb_css', true);

				if ($css) {
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo '<style type="text/css">' . $css . $reusable_css . '</style>';
				}
			}
		}, 100);
	}
}

