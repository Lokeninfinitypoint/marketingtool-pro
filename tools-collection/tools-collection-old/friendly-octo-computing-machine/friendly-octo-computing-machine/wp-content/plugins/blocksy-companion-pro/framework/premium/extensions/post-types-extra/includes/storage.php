<?php

namespace Blocksy\Extensions\PostTypesExtra;

class Storage {
	public function get_settings() {
		$default = [
			'features' => [
				'read-time' => false,
				'dynamic-data' => false,
				'filtering' => false,
				'taxonomies-customization' => false
			],
		];

		$settings = get_option(
			'blocksy_ext_post_types_extra_settings',
			$default
		);

		if (! is_array($settings)) {
			$settings = $default;
		}

		if (! isset($settings['features'])) {
			$settings['features'] = [];
		}

		$settings['features'] = array_merge(
			$default['features'],
			$settings['features']
		);

		return array_merge($default, $settings);
	}
}

