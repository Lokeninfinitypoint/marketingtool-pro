<?php

namespace Blocksy\PluginIntegrations;

class Elementor {
	public function __construct() {
		// init is too early. we need to make this check after the query is ready
		add_action('template_redirect', function () {
			$is_preview_mode = \Elementor\Plugin::$instance->preview->is_preview_mode();

			if (! $is_preview_mode) {
				return;
			}

			$post = get_queried_object();

			if (! $post) {
				return;
			}

			if ($post->post_type !== 'ct_content_block') {
				return;
			}

			$should_disable_header_and_footer = false;

			if ($post->post_type === 'ct_content_block') {
				$template_type = get_post_meta($post->ID, 'template_type', true);

				$disabled_types = ['hook', 'popup', 'header', 'footer'];

				if (in_array($template_type, $disabled_types)) {
					$should_disable_header_and_footer = true;
				}

				$atts = blocksy_get_post_options($post->ID);

				$default_template_subtype = 'card';

				if ($template_type === 'single') {
					$default_template_subtype = 'canvas';
				}

				$template_subtype = blocksy_akg(
					'template_subtype',
					$atts,
					$default_template_subtype
				);

				if ($template_type === 'archive') {
					if ($template_subtype === 'card') {
						$should_disable_header_and_footer = true;
					}
				}

				if ($template_type === 'single') {
					if ($template_subtype === 'content') {
						$should_disable_header_and_footer = true;
					}
				}
			}

			if (
				$post->post_type === 'ct_product_tab'
				||
				$post->post_type === 'ct_size_guide'
			) {
				$should_disable_header_and_footer = true;
			}

			if ($should_disable_header_and_footer) {
				$this->disable_header_and_footer();
			}
		});
	}

	private function disable_header_and_footer() {
		add_filter('blocksy:builder:header:enabled', '__return_false');
		add_filter('blocksy:builder:footer:enabled', '__return_false');
	}
}
