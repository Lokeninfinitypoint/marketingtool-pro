<?php

namespace Blocksy;

class MaintenanceMode {
	private $block = null;

	public function __construct() {
		add_action('get_header', [$this, 'blc_maintenance_mode']);
		add_action('template_include', [$this, 'blc_maintenance_mode_template'], PHP_INT_MAX);

		add_action('wp', function() {
			$maybe_content_block = blc_get_content_block_that_matches([
				'template_type' => 'maintenance',
				'match_conditions' => true,
			]);

			if ($maybe_content_block) {
				$content_block_renderer = new ContentBlocksRenderer(
					$maybe_content_block
				);

				$content_block_renderer->pre_output();
			}
		});
	}

	public function blc_maintenance_mode_template($template) {
		$current_user = wp_get_current_user();

		if (in_array('administrator', $current_user->roles)) {
			return $template;
		}

		$maybe_content_block = blc_get_content_block_that_matches([
			'template_type' => 'maintenance',
			'match_conditions' => true,
		]);

		if (! $maybe_content_block) {
			return $template;
		}

		$template = dirname(__FILE__) . '/maintenance-mode/view.php';

		return $template;
	}

	public function blc_maintenance_mode() {

		$current_user = wp_get_current_user();

		if (in_array('administrator', $current_user->roles)) {
			return;
		}

		$maybe_content_block = blc_get_content_block_that_matches([
			'template_type' => 'maintenance',
			'match_conditions' => true,
		]);

		if (! $maybe_content_block) {
			return;
		}

		add_filter('blocksy:builder:header:enabled', function() {
			return false;
		});

		add_filter('blocksy:builder:footer:enabled', function() {
			return false;
		});

		add_filter('blocksy:footer:offcanvas-drawer', function() {
			return [];
		}, PHP_INT_MAX);

		remove_all_actions('blocksy:template:after');
		remove_all_actions('blocksy:content:bottom');
		remove_all_actions('blocksy:header:before');
		remove_all_actions('blocksy:header:after');
		remove_all_actions('blocksy:footer:before');
		remove_all_actions('blocksy:footer:after');
	}
}


