<?php

class BlocksyExtensionPostTypesExtraPreBoot {
	public function __construct() {
	}

	public function ext_action($payload) {
		$ext = \Blocksy\Plugin::instance()->extensions->get('post-types-extra');

		if (
			!isset($payload['type'])
			||
			! isset($payload['settings'])
			||
			$payload['type'] !== 'update-features'
			||
			! $ext
		) {
			return null;
		}

		update_option(
			'blocksy_ext_post_types_extra_settings',
			$payload['settings']
		);

		global $wp_rewrite;
		$wp_rewrite->flush_rules();

		do_action('blocksy:dynamic-css:refresh-caches');

		return $this->ext_data([
			'settings' => $payload['settings']
		]);
	}

	public function ext_data($args = []) {
		$storage = new \Blocksy\Extensions\PostTypesExtra\Storage();
		$settings = $storage->get_settings();

		return wp_parse_args($args, [
			'settings' => $settings
		]);
	}
}

