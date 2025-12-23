<?php

namespace Blocksy;

class ContentBlocksLayer {
	public function __construct() {
		add_filter('blocksy_woo_single_options_layers:defaults', [
			$this,
			'register_layer_content_block_defaults',
		]);

		add_filter('blocksy_woo_single_right_options_layers:defaults', [
			$this,
			'register_layer_content_block_defaults',
		]);

		add_filter('blocksy_woo_single_options_layers:extra', [
			$this,
			'register_layer_options',
		]);

		add_action('blocksy:woocommerce:product:custom:layer', [
			$this,
			'render_layer',
		]);

		add_action('blocksy:woocommerce:product-card:custom:layer', [
			$this,
			'render_layer',
		]);

		add_filter('blocksy_woo_card_options_layers:defaults', [
			$this,
			'register_layer_content_block_defaults',
		]);

		add_filter('blocksy_woo_card_options_layers:extra', [
			$this,
			'register_layer_options',
		]);

		// Blog Posts
		add_filter('blocksy:posts-listing:archive-order:default', function ($default, $prefix) {
			$default = $this->register_layer_content_block_defaults($default);

			return $default;
		}, 15, 2);

		add_filter(
			'blocksy:options:posts-listing-archive-order',
			function ($option, $prefix, $skip_sync_id) {
				$option['settings'] = $this->register_simple_layer_options(
					$option['settings'],
					[
						'skip_sync_id' => $skip_sync_id
					]
				);

				$option['value'] = $this->register_layer_content_block_defaults($option['value']);

				return $option;
			},
			15, 3
		);

		add_filter(
			'blocksy:options:posts-listing-related-order',
			function ($option, $prefix, $skip_sync_id) {
				$option['settings'] = $this->register_simple_layer_options($option['settings'], [
					'skip_sync_id' => $skip_sync_id
				]);

				$option['value'] = $this->register_layer_content_block_defaults($option['value']);

				return $option;
			},
			15, 3
		);

		add_filter('blocksy:archive:render-card-layer', function ($output, $layer) {
			if (
				'content-block' === $layer['id']
				&&
				! empty($layer['hook_id'])
			) {
				ob_start();
				$this->render_layer($layer, 'ct-entry-content-block');
				$output .= ob_get_clean();
			}

			return $output;
		}, 10, 2);

		add_filter('blocksy:related:render-card-layer', function ($output, $layer) {
			if (
				'content-block' === $layer['id']
				&&
				! empty($layer['hook_id'])
			) {
				ob_start();
				$this->render_layer($layer, 'ct-entry-content-block');
				$output .= ob_get_clean();
			}

			return $output;
		}, 10, 2);

		// Page title
		add_filter('blocksy:options:page-title:hero-elements', function ($option, $prefix) {
			$option['settings'] = $this->register_simple_layer_options(
				$option['settings'],
				[
					'has_spacing' => false
				]
			);

			$option['value'] = $this->register_layer_content_block_defaults($option['value']);

			return $option;
		}, 15, 2);

		add_action('blocksy:hero:element:render', function($layer) {
			if (
				$layer['id'] === 'content-block'
				&&
				! empty($layer['hook_id'])
			) {
				$this->render_layer($layer, 'ct-entry-content-block');
			}
		});
	}

	public function render_layer($layer, $class = 'ct-product-content-block') {
		if (
			$layer['id'] === 'content-block'
			&&
			! empty($layer['hook_id'])
			&&
			\Blocksy\Plugin::instance()
				->premium
				->content_blocks
				->is_hook_eligible_for_display($layer['hook_id'], [
					'match_conditions' => false
				])
		) {
			$atts = blocksy_get_post_options($layer['hook_id']);

			$class = trim(
				$class . ' ' . blocksy_visibility_classes(
					blocksy_default_akg(
						'visibility',
						$atts,
						[
							'desktop' => true,
							'tablet' => true,
							'mobile' => true,
						]
					)
				)
			);

			$content = \Blocksy\Plugin::instance()
				->premium
				->content_blocks
				->output_hook($layer['hook_id'], [
					'layout' => false,
					'hook_class' => $class,
					'raw_content' => true
				]);

			blocksy_html_tag_e(
				'div',
				[
					'class' => $class,
					'data-id' => $layer['__id'],
				],
				$content
			);

		}
	}

	public function register_layer_content_block_defaults($opt) {
		return array_merge($opt, [
			[
				'id' => 'content-block',
				'enabled' => false,
			],
		]);
	}

	public function register_simple_layer_options($opt, $args = []) {
		$args = wp_parse_args($args, [
			'has_spacing' => true,
			'skip_sync_id' => ''
		]);

		return array_merge($opt, [
			'content-block' => [
				'label' => __('Content Block', 'blocksy-companion'),
				'clone' => 5,
				'options' => [
					empty(blc_get_content_blocks())
						? [
							blocksy_rand_md5() => [
								'type' => 'html',
								'label' => __('Select Content Block', 'blocksy-companion'),
								'value' => '',
								'design' => 'block',
								'html' => '<a href="' . admin_url('/edit.php?post_type=ct_content_block') .'" target="_blank" class="button" style="width: 100%; text-align: center;">' . __('Create a new content Block/Hook', 'blocksy-companion') . '</a>',
							]
						]
						: [
							'hook_id' => [
								'label' => __('Select Content Block', 'blocksy-companion'),
								'type' => 'ct-select',
								'value' => '',
								'view' => 'text',
								'search' => true,
								'defaultToFirstItem' => false,
								'placeholder' => __('None', 'blocksy-companion'),
								'choices' => blocksy_ordered_keys(
									blc_get_content_blocks()
								),
							],
						],

					(
						$args['has_spacing'] ? [
							'spacing' => [
								'label' => __('Bottom Spacing', 'blocksy-companion'),
								'type' => 'ct-slider',
								'min' => 0,
								'max' => 100,
								'value' => 20,
								'responsive' => true,
								'sync' => $args['skip_sync_id'] ? [
									'id' => $args['skip_sync_id']
								] : [],
							],
						] : []
					)
				],
			]
		]);
	}

	public function register_layer_options($opt) {
		return array_merge($opt, [
			'content-block' => [
				'label' => __('Content Block', 'blocksy-companion'),
				'clone' => 5,
				'options' => [
					empty(blc_get_content_blocks())
						? [
							blocksy_rand_md5() => [
								'type' => 'html',
								'label' => __('Select Content Block', 'blocksy-companion'),
								'value' => '',
								'design' => 'block',
								'html' => '<a href="' . admin_url('/edit.php?post_type=ct_content_block') .'" target="_blank" class="button" style="width: 100%; text-align: center;">' . __('Create a new content Block/Hook', 'blocksy-companion') . '</a>',
							]
						]
						: [
							'hook_id' => [
								'label' => __('Select Content Block', 'blocksy-companion'),
								'type' => 'ct-select',
								'value' => '',
								'view' => 'text',
								'search' => true,
								'defaultToFirstItem' => false,
								'placeholder' => __('None', 'blocksy-companion'),
								'choices' => blocksy_ordered_keys(
									blc_get_content_blocks()
								),
							],
						],

					[
						'spacing' => [
							'label' => __('Bottom Spacing', 'blocksy-companion'),
							'type' => 'ct-slider',
							'min' => 0,
							'max' => 100,
							'value' => 10,
							'responsive' => true,
							'sync' => [
								'id' => 'woo_single_layout_skip',
							],
						],
					],
				],
			]
		]);
	}
}

