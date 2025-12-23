<?php

namespace Blocksy\CustomPostType\Integrations;

class ZionBuilder extends \Blocksy\CustomPostTypeRenderer {
	public function get_content($args = []) {
		$post_instance = \ZionBuilder\Plugin::instance()
			->post_manager
			->get_post_instance($this->id);

		if ($post_instance->is_built_with_zion()) {
			return \ZionBuilder\Plugin::instance()->renderer->get_content(
				$this->id
			);
		}

		return \Blocksy\CustomPostTypeRenderer::NOT_IMPLEMENTED;
	}

	public function pre_output() {
		$post_instance = \ZionBuilder\Plugin::instance()
			->post_manager
			->get_post_instance($this->id);

		if ($post_instance->is_built_with_zion()) {
			$post_template_data = $post_instance->get_template_data();

			\ZionBuilder\Plugin::instance()
				->renderer
				->register_area($this->id, $post_template_data);
		}
	}
}

