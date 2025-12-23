<?php

namespace Blocksy\CustomPostType\Integrations;

class Stackable extends \Blocksy\CustomPostTypeRenderer {
	public function get_content($args = []) {
		return \Blocksy\CustomPostTypeRenderer::NOT_IMPLEMENTED;
	}

	public function pre_output() {
		$post = get_post($this->id);

		if ($post) {
			$contentpost = $post->post_content;

			$contentpost = str_replace(
				'<!-- wp:post-content /-->',
				'',
				$contentpost
			);

			if (has_blocks($contentpost)) {
				$blocks = parse_blocks($contentpost);

				foreach ($blocks as $block) {
					if (! empty($block['blockName'])) {
						$block_namespace = explode('/', $block['blockName'])[0];

						// If this is not a Stackable block and it has no inner blocks, skip it.
						// This prevents render blocks that are not relevant for
						// Stackable assets enqueueing process.
						if (
							empty($block['innerBlocks'])
							&&
							$block_namespace !== 'stackable'
						) {
							continue;
						}
					}

					render_block($block);
				}
			}
		}
	}
}

