<?php

namespace Blocksy;

class CopyOptions {
	public function __construct() {
		add_filter(
			'blocksy:options:prefix-global-actions',
			function ($result, $args) {
				$result[blocksy_rand_md5()] = [
					'type' => 'ct-customize-section-title-actions',
					'prefix' => $args['prefix'],
					'areas' => $args['areas']
				];

				return $result;
			},
			10, 2
		);
	}
}
