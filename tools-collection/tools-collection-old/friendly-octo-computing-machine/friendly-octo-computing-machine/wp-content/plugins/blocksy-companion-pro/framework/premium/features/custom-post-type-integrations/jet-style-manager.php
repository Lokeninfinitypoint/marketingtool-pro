<?php

namespace Blocksy\CustomPostType\Integrations;

class JetStyleManager extends \Blocksy\CustomPostTypeRenderer {
	public function get_content($args = []) {
		return \Blocksy\CustomPostTypeRenderer::NOT_IMPLEMENTED;
	}

	public function pre_output() {
		add_action(
			'wp_print_footer_scripts',
			function () {
				$inst = \JET_SM\Gutenberg\Style_Manager::get_instance();
				$inst->render_blocks_fonts($this->id);
			},
			9
		);

		add_action(
			'wp_print_footer_scripts',
			function () {
				$inst = \JET_SM\Gutenberg\Style_Manager::get_instance();
				$inst->render_blocks_style($this->id);
			}
		);
	}
}

