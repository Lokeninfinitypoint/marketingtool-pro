<?php

class BlocksyExtensionPostTypesExtra {
	public $dynamic_data = null;
	public $taxonomies_customization = null;

	public function __construct() {
		$this->boot_features();

		add_action('wp_enqueue_scripts', function () {
			if (! function_exists('get_plugin_data')) {
				require_once(ABSPATH . 'wp-admin/includes/plugin.php');
			}

			$data = get_plugin_data(BLOCKSY__FILE__);

			if (is_admin()) {
				return;
			}

			wp_enqueue_style(
				'blocksy-ext-post-types-extra-styles',
				BLOCKSY_URL . 'framework/premium/extensions/post-types-extra/static/bundle/main.min.css',
				['ct-main-styles'],
				$data['Version']
			);
		}, 50);

		add_action(
			'customize_preview_init',
			function () {
				if (! function_exists('get_plugin_data')) {
					require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
				}

				$data = get_plugin_data(BLOCKSY__FILE__);

				wp_enqueue_script(
					'blocksy-ext-post-types-extra-customizer-sync',
					BLOCKSY_URL . 'framework/premium/extensions/post-types-extra/static/bundle/sync.js',
					['customize-preview', 'ct-scripts'],
					$data['Version'],
					true
				);
			}
		);
	}

	public function boot_features() {
		$storage = new \Blocksy\Extensions\PostTypesExtra\Storage();
		$settings = $storage->get_settings();

		if (
			isset($settings['features']['filtering'])
			&&
			$settings['features']['filtering']
		) {
			new \Blocksy\Extensions\PostTypesExtra\Filtering();
		}

		if (
			isset($settings['features']['taxonomies-customization'])
			&&
			$settings['features']['taxonomies-customization']
		) {
			$this->taxonomies_customization = new \Blocksy\Extensions\PostTypesExtra\TaxonomiesCustomization();
		}

		if (
			isset($settings['features']['read-time'])
			&&
			$settings['features']['read-time']
		) {
			new \Blocksy\Extensions\PostTypesExtra\ReadTime();
		}

		if (
			isset($settings['features']['dynamic-data'])
			&&
			$settings['features']['dynamic-data']
		) {
			$this->dynamic_data = new \Blocksy\Extensions\PostTypesExtra\DynamicData();
		}

		new \Blocksy\Extensions\PostTypesExtra\RelatedSlideshow();
	}
}

