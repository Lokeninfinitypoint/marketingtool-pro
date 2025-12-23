<?php

namespace Blocksy;

class ContentBlocksTemplates {
	public function __construct() {
		add_action('wp', function () {
			if (! is_singular() && ! is_single()) {
				return;
			}

			if (
				function_exists('blc_get_content_block_that_matches')
				&&
				blc_get_content_block_that_matches([
					'template_type' => 'single',
					'template_subtype' => 'canvas'
				])
			) {
				global $blocksy_template_output;
				$blocksy_template_output = true;
			}
		});

		add_filter('blocksy:posts-listing:container:custom-output', function ($output) {
			if (
				blc_get_content_block_that_matches([
					'template_type' => 'archive'
				])
			) {
				$hook_id = blc_get_content_block_that_matches([
					'template_type' => 'archive'
				]);

				$atts = blocksy_get_post_options($hook_id);

				return [
					'has_default_layout' => blocksy_akg(
						'has_template_default_layout',
						$atts,
						'yes'
					) === 'yes'
				];
			}

			return $output;
		});

		add_filter(
			'blocksy:posts-listing:canvas:custom-output',
			function ($output) {
				if ((
					is_home()
					||
					is_archive()
					||
					is_search()
				) && ! have_posts()) {
					$maybe_nothing_found_hook = blc_get_content_block_that_matches([
						'template_type' => 'nothing_found'
					]);

					if ($maybe_nothing_found_hook) {
						return blc_render_content_block($maybe_nothing_found_hook);
					}
				}

				$maybe_hook_id = null;

				if (
					blc_get_content_block_that_matches([
						'template_type' => 'archive',
						'template_subtype' => 'canvas'
					])
				) {
					$maybe_hook_id = blc_get_content_block_that_matches([
						'template_type' => 'archive',
						'template_subtype' => 'canvas'
					]);
				}

				if (! $maybe_hook_id) {
					return $output;
				}

				global $blocksy_template_output;
				$blocksy_template_output = true;

				return blc_render_content_block($maybe_hook_id);
			}
		);

		add_filter(
			'blocksy:posts-listing:cards:custom-output',
			function ($output) {
				if (
					blc_get_content_block_that_matches([
						'template_type' => 'archive'
					])
				) {
					$hook_id = blc_get_content_block_that_matches([
						'template_type' => 'archive'
					]);

					$atts = blocksy_get_post_options($hook_id);

					$data = [
						'has_default_layout' => blocksy_akg(
							'has_template_default_layout',
							$atts,
							'yes'
						) === 'yes',
						'output' => blc_render_content_block($hook_id)
					];

					$content_block_renderer = new ContentBlocksRenderer(
						$hook_id
					);

					$data['output'] .= '<style>' . $content_block_renderer->get_inline_styles() . '</style>';

					return $data;
				}

				return $output;
			}
		);

		add_filter(
			'blocksy:global:page_structure',
			function ($page_structure) {
				global $blocksy_template_output;

				if (! isset($blocksy_template_output) || ! $blocksy_template_output) {
					return $page_structure;
				}

				$maybe_matching_template = null;

				if (is_singular()) {
					$maybe_matching_template = blc_get_content_block_that_matches([
						'template_type' => 'single',
						'template_subtype' => 'canvas'
					]);
				} else {
					$maybe_matching_template = blc_get_content_block_that_matches([
						'template_type' => 'archive',
						'template_subtype' => 'canvas'
					]);
				}

				if ($maybe_matching_template) {
					$atts = blocksy_get_post_options($maybe_matching_template);
					return blocksy_akg('content_block_structure', $atts, 'type-4');
				}

				return $page_structure;
			}
		);
	}
}

