<?php

namespace Blocksy\CustomPostType\Integrations;

class GhostKit extends \Blocksy\CustomPostTypeRenderer {
	public function get_content($args = []) {
		return \Blocksy\CustomPostTypeRenderer::NOT_IMPLEMENTED;
	}

	public function pre_output() {
		$post = get_post($this->id);

		\GhostKit_Parse_Blocks::maybe_parse_blocks_from_custom_location(
			$post->post_content
		);
	}
}
