<?php

namespace Blocksy\CustomPostType\Integrations;

class GenerateBlocks extends \Blocksy\CustomPostTypeRenderer {
	public function get_content($args = []) {
		return \Blocksy\CustomPostTypeRenderer::NOT_IMPLEMENTED;
	}

	public function pre_output() {
		add_filter(
			'generateblocks_do_content',
			function ($content) {
				$hook_post = get_post($this->id);
				return $content . $hook_post->post_content;
			}
		);
	}
}

