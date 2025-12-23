<?php

namespace Blocksy\CustomPostType\Integrations;

class MaxiBlocks extends \Blocksy\CustomPostTypeRenderer {
	public function get_content($args = []) {
		return \Blocksy\CustomPostTypeRenderer::NOT_IMPLEMENTED;
	}

	public function pre_output() {
		add_action('wp_enqueue_scripts', function () {
            global $post;
            $original_post = $post;

            $post = get_post($this->id);

			$k = new \MaxiBlocks_Styles();
            $post_content = $k->process_content_frontend();
            
            $post = $original_post;
		}, 20);
	}
}

