<?php

namespace Blocksy\Extensions\PostTypesExtra;

class ReadProgress {
	public function __construct() {
		add_filter('blocksy:frontend:dynamic-js-chunks', function ($chunks) {
			$chunks[] = [
				'id' => 'blocksy_adv_cpt_read_progress',
				'selector' => '.ct-read-progress-bar',
				// 'trigger' => 'scroll',
				'url' => blocksy_cdn_url(
					BLOCKSY_URL . 'framework/premium/extensions/post-types-extra/static/bundle/read-progress.js'
				),
			];

			return $chunks;
		});

		add_filter(
			'blocksy_single_posts_end_customizer_options',
			function ($opts, $prefix) {
				$opts[$prefix . '_has_read_progress'] = blocksy_get_options(
					dirname(__FILE__) . '/read-progress/customizer.php',
					[
						'prefix' => $prefix
					], false
				);

				return $opts;
			},
			10, 2
		);

		add_action('blocksy:global-dynamic-css:enqueue', function ($args) {
			if (! blc_theme_functions()->blocksy_manager()) {
				return;
			}

			blocksy_theme_get_dynamic_styles(array_merge([
				'path' => dirname(__FILE__) . '/read-progress/global.php',
				'chunk' => 'global',
				'prefixes' => blc_theme_functions()->blocksy_manager()->screen->get_single_prefixes()
			], $args));
		}, 10, 3);

		add_filter(
			'blocksy:footer:offcanvas-drawer',
			function ($els, $payload) {
				if ($payload['location'] !== 'start') {
					return $els;
				}

				if (! blc_theme_functions()->blocksy_manager()) {
					return $els;
				}

				$prefix = blc_theme_functions()->blocksy_manager()->screen->get_prefix();

				$class = 'ct-read-progress-bar';

				$class .= ' ' . blocksy_visibility_classes(
					blc_theme_functions()->blocksy_get_theme_mod($prefix . '_read_progress_visibility', [
						'desktop' => true,
						'tablet' => true,
						'mobile' => false,
					])
				);

				if (blc_theme_functions()->blocksy_get_theme_mod($prefix . '_has_auto_hide', 'no') === 'yes') {
					$class .= ' ct-auto-hide';
				}

				if (blc_theme_functions()->blocksy_get_theme_mod($prefix . '_has_read_progress', 'no') === 'no') {
					return $els;
				}

				$els[] = '<div class="' . $class . '"></div>';

				return $els;
			},
			10,
			2
		);
	}
}

