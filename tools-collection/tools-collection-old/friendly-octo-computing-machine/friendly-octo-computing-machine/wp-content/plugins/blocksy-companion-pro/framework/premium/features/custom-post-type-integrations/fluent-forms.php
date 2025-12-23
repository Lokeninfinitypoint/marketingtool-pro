<?php

namespace Blocksy\CustomPostType\Integrations;

class FluentForms extends \Blocksy\CustomPostTypeRenderer {
	public function get_content($args = []) {
		return \Blocksy\CustomPostTypeRenderer::NOT_IMPLEMENTED;
	}

	public function pre_output() {
		$hook_post = get_post($this->id);

		if (! has_blocks($hook_post)) {
			return;
		}

		$blocks = json_encode(parse_blocks($hook_post->post_content));

		if (strpos($blocks, 'fluentfom') === false) {
			return;
		}

		wp_enqueue_style('fluent-form-styles');
		wp_enqueue_style('fluentform-public-default');
		do_action('fluentform_pre_load_scripts', $hook_post);
		wp_enqueue_script('fluent-form-submission');
	}
}

