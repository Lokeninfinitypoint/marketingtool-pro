<?php

namespace Blocksy\CustomPostType\Integrations;

class Spectra extends \Blocksy\CustomPostTypeRenderer {
	public function get_content($args = []) {
		return \Blocksy\CustomPostTypeRenderer::NOT_IMPLEMENTED;
	}

	public function pre_output() {
		$current_post_assets = new \UAGB_Post_Assets(intval($this->id));
		$current_post_assets->enqueue_scripts();
	}
}

