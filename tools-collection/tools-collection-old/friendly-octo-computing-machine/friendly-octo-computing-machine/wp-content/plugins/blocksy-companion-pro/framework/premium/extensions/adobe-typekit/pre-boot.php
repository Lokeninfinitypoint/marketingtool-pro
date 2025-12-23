<?php

class BlocksyExtensionAdobeTypekitPreBoot {
	public function __construct() {
		add_action('admin_enqueue_scripts', function () {
			if (! function_exists('get_plugin_data')) {
				require_once(ABSPATH . 'wp-admin/includes/plugin.php');
			}

			$data = get_plugin_data(BLOCKSY__FILE__);

			if (! function_exists('blocksy_is_dashboard_page')) return;
			if (! blocksy_is_dashboard_page()) return;

			wp_enqueue_script(
				'blocksy-ext-adobe-typekit-admin-dashboard-scripts',
				BLOCKSY_URL . 'framework/premium/extensions/adobe-typekit/dashboard-static/bundle/dashboard.js',
				['ct-events','ct-options-scripts', 'ct-dashboard-scripts'],
				$data['Version'],
				false
			);
		});
	}
}

