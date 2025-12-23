<?php

namespace Blocksy\CustomPostType\Integrations;

class NectarBlocks extends \Blocksy\CustomPostTypeRenderer {
	public function get_content($args = []) {
		return \Blocksy\CustomPostTypeRenderer::NOT_IMPLEMENTED;
	}

	public function pre_output() {
		add_action('wp_enqueue_scripts', function () {
            global $post;
            $original_post = $post;

            $post = get_post($this->id);

			$renderer = new \Nectar\Render\Render;
			$renderer->frontend_render_styles();
			$renderer->frontend_render_scripts();
            
            $post = $original_post;
		}, 20);
	}
}

