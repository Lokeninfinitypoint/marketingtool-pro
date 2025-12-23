<?php

namespace Blocksy;

class SocialsExtra {
	public function __construct() {
		add_filter(
			'blocksy:socials:options:icon',
			function($opts) {
				foreach ($opts as $id => $network) {
					$opts[$id]['options'] = [
						'icon_source' => [
							'label' => __( 'Icon Source', 'blocksy-companion' ),
							'type' => 'ct-radio',
							'value' => 'default',
							'view' => 'text',
							'design' => 'block',
							'setting' => [ 'transport' => 'postMessage' ],
							'choices' => [
								'default' => __( 'Default', 'blocksy-companion' ),
								'custom' => __( 'Custom', 'blocksy-companion' ),
							],
						],

						blocksy_rand_md5() => [
							'type' => 'ct-condition',
							'condition' => ['icon_source' => 'custom'],
							'options' => [
								'icon' => [
									'type' => 'icon-picker',
									'label' => __('Icon', 'blocksy-companion'),
									'design' => 'inline',
									'value' => [
										'icon' => 'blc blc-email'
									]
								]
							]
						],

						'url_source' => [
							'label' => __( 'URL Source', 'blocksy-companion' ),
							'type' => 'ct-radio',
							'value' => 'default',
							'view' => 'text',
							'design' => 'block',
							'setting' => [ 'transport' => 'postMessage' ],
							'choices' => [
								'default' => __( 'Default', 'blocksy-companion' ),
								'custom' => __( 'Custom', 'blocksy-companion' ),
							],
						],

						blocksy_rand_md5() => [
							'type' => 'ct-condition',
							'condition' => ['url_source' => 'custom'],
							'options' => [
								'custom_url' => [
									'type' => 'text',
									'label' => __('Custom URL', 'blocksy-companion'),
									'design' => 'block',
									'value' => ''
								]
							]
						],
					];
				}

				return $opts;
			}
		);
    }
}
