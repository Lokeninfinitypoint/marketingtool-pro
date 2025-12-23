<?php

namespace Blocksy\CustomPostType\Integrations;

class Gutentor extends \Blocksy\CustomPostTypeRenderer {
	public function get_content($args = []) {
		return \Blocksy\CustomPostTypeRenderer::NOT_IMPLEMENTED;
	}

	public function pre_output() {
		$p = get_post($this->id);
		global $post;

		$prev_post = $post;

		$post = $p;
		setup_postdata($post);

		$plugin_hooks = gutentor_hooks();
		$plugin_hooks->load_lib_assets();

		wp_reset_postdata();
		$post = $prev_post;

		add_action('wp_head', function () {
			/*for some backward compatible CSS*/
			gutentor_dynamic_css()->backward_dynamic_css();

			$singularCSS = $combineCSS = '';

			$p = get_post($this->id);
			global $post;

			$prev_post = $post;

			$post = $p;
			setup_postdata($post);

			if ('file' == apply_filters( 'gutentor_dynamic_style_location', 'head' ) ) {
				global $wp_customize;
				$upload_dir = wp_upload_dir();


				$cssPrefix = gutentor_dynamic_css()->css_prefix($post);

				if (isset($wp_customize) || ! file_exists($upload_dir['basedir'] . '/gutentor/p-' . $cssPrefix . '.css')) {
					$singularCSS = gutentor_dynamic_css()->get_singular_dynamic_css( $post );
					$combineCSS .= $singularCSS;
				}

				// Render CSS in the head
				if ( ! empty( $combineCSS ) ) {
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo "<!-- Gutentor Dynamic CSS -->\n<style type=\"text/css\" id='gutentor-dynamic-css'>\n" . wp_strip_all_tags( $combineCSS ) . "\n</style>";
				}
			} else {
				$singularCSS .= gutentor_dynamic_css()->get_singular_dynamic_css($post);
				$combineCSS = $singularCSS;
				// Render CSS in the head
				if ( ! empty( $combineCSS ) ) {
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo "<!-- Gutentor Dynamic CSS -->\n<style type=\"text/css\" id='gutentor-dynamic-css'>\n" . wp_strip_all_tags( $combineCSS ) . "\n</style>";
				}
			}

			wp_reset_postdata();
			$post = $prev_post;
		}, 99);

		add_action('wp_enqueue_scripts', function () {
			// If File is not selected
			if ( 'file' != apply_filters( 'gutentor_dynamic_style_location', 'head' ) ) {
				return false;
			}

			$p = get_post($this->id);
			global $post;

			$prev_post = $post;

			global $wp_customize;
			$upload_dir = wp_upload_dir();

			// Render CSS from the custom file
			if (isset($wp_customize)) {
				return;
			}

			$cssPrefix = gutentor_dynamic_css()->css_prefix($post);
			$singularCSS = gutentor_dynamic_css()->get_singular_dynamic_css($post);
			if (! empty($singularCSS) && file_exists($upload_dir['basedir'] . '/gutentor/p-' . $cssPrefix . '.css')) {
				$css_info = get_post_meta($post->ID, 'gutentor_css_info', true);
				wp_enqueue_style('gutentor-dynamic-' . $cssPrefix, trailingslashit( $upload_dir['baseurl'] ) . 'gutentor/p-' . $cssPrefix . '.css', false, isset( $css_info['saved_version'] ) ? $css_info['saved_version'] : '' );
				/*Lets fix RTL If needed*/
				if (isset($css_info['is_rtl']) && is_rtl() !== $css_info['is_rtl']) {
					gutentor_dynamic_css()->fix_rtl($post->ID);
				}
			}

			wp_reset_postdata();
			$post = $prev_post;
		}, 9999 );
	}
}

