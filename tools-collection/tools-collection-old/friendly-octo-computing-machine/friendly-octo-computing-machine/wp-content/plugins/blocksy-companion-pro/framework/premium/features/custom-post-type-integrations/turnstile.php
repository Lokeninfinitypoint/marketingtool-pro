<?php

namespace Blocksy\CustomPostType\Integrations;

class Turnstile extends \Blocksy\CustomPostTypeRenderer {
	public function get_content($args = []) {
		return \Blocksy\CustomPostTypeRenderer::NOT_IMPLEMENTED;
	}

	public function pre_output() {
		do_action("cfturnstile_enqueue_scripts");
	}
}

