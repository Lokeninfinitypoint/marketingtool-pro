<?php

namespace Blocksy\CustomPostType\Integrations;

class KadenceBlocks extends \Blocksy\CustomPostTypeRenderer {
	public function get_content($args = []) {
		return \Blocksy\CustomPostTypeRenderer::NOT_IMPLEMENTED;
	}

	public function pre_output() {
		add_action('wp_enqueue_scripts', function () {
			$k = \Kadence_Blocks_Frontend::get_instance();

			$post = get_post($this->id);
			$k->frontend_build_css($post);
		}, 20);
	}
}

