<?php

class BlocksyExtensionWoocommerceExtraPreBoot {
	public function __construct() {
	}

	public function ext_action($payload) {
		$ext = \Blocksy\Plugin::instance()->extensions->get('woocommerce-extra');

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
			'blocksy_ext_woocommerce_extra_settings',
			$payload['settings']
		);

		global $wp_rewrite;
		$wp_rewrite->flush_rules();

		blc_get_ext('woocommerce-extra')->boot_features();

		do_action('blocksy:dynamic-css:refresh-caches');

		return $this->ext_data([
			'settings' => $payload['settings']
		]);
	}

	public function ext_data($args = []) {
		$storage = new \Blocksy\Extensions\WoocommerceExtra\Storage();
		$settings = $storage->get_settings();

		return wp_parse_args($args, [
			'settings' => $settings
		]);
	}
}
