<?php

namespace Blocksy\CustomPostType\Integrations;

class PiotnetForms extends \Blocksy\CustomPostTypeRenderer {
	public function get_content($args = []) {
		return \Blocksy\CustomPostTypeRenderer::NOT_IMPLEMENTED;
	}

	public function pre_output() {
		if (
			get_post($this->id)
			&&
			has_shortcode(get_post($this->id)->post_content, 'piotnetforms')
		) {
			add_action('wp_enqueue_scripts', function () {
				wp_enqueue_script('piotnetforms-script');
				wp_enqueue_style('piotnetforms-style');
			}, 15);
		}
	}
}

