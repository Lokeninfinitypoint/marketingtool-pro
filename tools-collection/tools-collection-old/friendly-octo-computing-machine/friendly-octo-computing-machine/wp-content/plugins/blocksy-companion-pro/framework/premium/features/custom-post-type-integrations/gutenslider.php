<?php

namespace Blocksy\CustomPostType\Integrations;

class Gutenslider extends \Blocksy\CustomPostTypeRenderer {
	public function get_content($args = []) {
		return \Blocksy\CustomPostTypeRenderer::NOT_IMPLEMENTED;
	}

	public function pre_output() {
		if (has_block('eedee/block-gutenslider', $this->id)) {
			if (! is_admin()) {
				wp_enqueue_script('eedee-gutenslider-front');
			}

			wp_enqueue_style('eedee-gutenslider-block');
		}
	}
}

