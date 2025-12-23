<?php

namespace Blocksy\CustomPostType\Integrations;

class CountdownBlock extends \Blocksy\CustomPostTypeRenderer {
	public function get_content($args = []) {
		return \Blocksy\CustomPostTypeRenderer::NOT_IMPLEMENTED;
	}

	public function pre_output() {
		$upload_dir = wp_upload_dir();

		if (file_exists($upload_dir['basedir'] . '/eb-style/eb-style-' . $this->id . '.min.css')) {
			wp_enqueue_style(
				'eb-block-style-' . $this->id,
				$upload_dir['baseurl'] . '/eb-style/eb-style-' . $this->id . '.min.css',
				[],
				substr(md5(microtime(true)), 0, 10)
			);
		}
	}
}

