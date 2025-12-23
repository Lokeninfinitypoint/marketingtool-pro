<?php

namespace Blocksy\CustomPostType\Integrations;

class Cwicly extends \Blocksy\CustomPostTypeRenderer {
	public function get_content($args = []) {
		return \Blocksy\CustomPostTypeRenderer::NOT_IMPLEMENTED;
	}

	public function pre_output() {
		if (is_admin() || ! $this->id) {
			return;
		}

		$maybe_file_path = wp_upload_dir()[
			'basedir'
		] . '/cwicly/css/cc-post-' . $this->id . '.css';

		if (file_exists($maybe_file_path)) {
			wp_enqueue_style(
				'cc-post-' . $this->id . '',
				wp_upload_dir()['baseurl'] . '/cwicly/css/cc-post-' . $this->id . '.css',
				[],
				filemtime($maybe_file_path)
			);
		}
	}
}

