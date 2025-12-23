<?php

namespace Blocksy;

class ContentBlocksAssetsManager {
	private $hooks_to_enqueue = [];

	public function __construct() {
		add_action('wp', function () {
			if (is_admin()) {
				return;
			}

			$this->run_pre_output();
		}, 900000);
	}

	public function enqueue_hook($hook_id) {
		if (! $hook_id) {
			return;
		}

		$this->hooks_to_enqueue[] = $hook_id;
		$this->hooks_to_enqueue = array_unique($this->hooks_to_enqueue);
	}

	private function run_pre_output() {
		$this->enqueue_hook(
			blc_get_content_block_that_matches([
				'template_type' => 'header'
			])
		);

		$this->enqueue_hook(
			blc_get_content_block_that_matches([
				'template_type' => 'single',
				'template_subtype' => 'canvas'
			])
		);

		$this->enqueue_hook(
			blc_get_content_block_that_matches([
				'template_type' => 'single',
				'template_subtype' => 'content'
			])
		);

		$this->enqueue_hook(
			blc_get_content_block_that_matches([
				'template_type' => 'archive',
				'template_subtype' => 'canvas'
			])
		);

		$all_blocks = array_keys(blc_get_content_blocks([
			'template_type' => 'archive'
		]));

		foreach ($all_blocks as $block_id) {
			$values = blocksy_get_post_options($block_id);

			$conditions = blocksy_default_akg('conditions', $values, []);

			$template_subtype = blocksy_default_akg(
				'template_subtype',
				$values,
				'card'
			);

			if ($template_subtype === 'card') {
				$this->enqueue_hook($block_id);
			}
		}

		$this->enqueue_hook(
			blc_get_content_block_that_matches([
				'template_type' => 'footer'
			])
		);

		if (is_404()) {
			$this->enqueue_hook(
				blc_get_content_block_that_matches([
					'template_type' => '404',
					'match_conditions' => false
				])
			);
		}

		if ((
			is_home()
			||
			is_archive()
			||
			is_search()
		) && ! have_posts()) {
			$this->enqueue_hook(
				blc_get_content_block_that_matches([
					'template_type' => 'nothing_found'
				])
			);
		}

		foreach ($this->hooks_to_enqueue as $hook_id) {
			$content_block_renderer = new ContentBlocksRenderer($hook_id);
			$content_block_renderer->pre_output();
		}
	}
}

