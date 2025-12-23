<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class ShareBoxLayer {
	public function get_dynamic_styles_data($args) {
		return [
			'path' => dirname(__FILE__) . '/dynamic-styles.php'
		];
	}

	public function __construct() {
		add_filter('blocksy_woo_single_options_layers:defaults', [
			$this,
			'register_layer_sharebox_defaults',
		]);

		add_filter('blocksy_woo_single_right_options_layers:defaults', [
			$this,
			'register_layer_sharebox_defaults',
		]);

		add_filter('blocksy_woo_single_options_layers:extra', [
			$this,
			'register_layer_options',
		]);

		add_action('blocksy:woocommerce:product:custom:layer', [
			$this,
			'render_layer',
		]);

		add_filter(
			'blocksy:options:single_product:elements:design_tab:end',
			function ($opt) {
				$options = [
					'product_share_items_icon_color' => [
						'label' => __('Share Box Icons Color', 'blocksy-companion'),
						'type'  => 'ct-color-picker',
						'design' => 'inline',

						'sync' => 'live',
						'divider' => 'top:full',
						'value' => [
							'default' => [
								'color' => \Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],

							'hover' => [
								'color' => \Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],
						],

						'pickers' => [
							[
								'title' => __('Initial', 'blocksy-companion'),
								'id' => 'default',
								'inherit' => 'var(--theme-text-color)'
							],

							[
								'title' => __('Hover', 'blocksy-companion'),
								'id' => 'hover',
								'inherit' => 'var(--theme-palette-color-2)'
							],
						],
					]
				];

				return array_merge(
					$opt,

					[
						blocksy_rand_md5() => [
							'type' => 'ct-condition',
							'condition' => [
								'woo_single_layout:array-ids:product_sharebox:enabled:yes' => '!no'
							],
							'computed_fields' => ['woo_single_layout'],
							'options' => $options
						],
					]
				);
			}
		);

		add_filter('blocksy:single:has-share-box', function ($value) {
			if (function_exists('is_product') && is_product()) {
				$layout = blc_theme_functions()->blocksy_get_theme_mod(
					'woo_single_layout',
					[]
				);

				$product_view_type = blc_theme_functions()->blocksy_get_theme_mod(
					'product_view_type',
					'default-gallery'
				);

				if (
					$product_view_type === 'top-gallery'
					||
					$product_view_type === 'columns-top-gallery'
				) {
					$woo_single_split_layout_defults = [
						'left' => [],
						'right' => []
					];

					if (function_exists('blocksy_get_woo_single_layout_defaults')) {
						$woo_single_split_layout_defults = [
							'left' => blocksy_get_woo_single_layout_defaults('left'),
							'right' => blocksy_get_woo_single_layout_defaults('right')
						];
					}

					$woo_single_split_layout = blc_theme_functions()->blocksy_get_theme_mod(
						'woo_single_split_layout',
						$woo_single_split_layout_defults
					);

					$layout = blocksy_normalize_layout(
						array_merge(
							$woo_single_split_layout['left'],
							$woo_single_split_layout['right']
						),

						array_merge(
							$woo_single_split_layout_defults['left'],
							$woo_single_split_layout_defults['right']
						)
					);
				}

				foreach ($layout as $layer) {
					if (! $layer['enabled']) {
						continue;
					}

					if ($layer['id'] === 'product_sharebox') {
						return true;
					}
				}
			}

			return $value;
		});
	}

	public function render_layer($layer) {
		if (
			$layer['id'] === 'product_sharebox'
			&&
			function_exists('blocksy_get_social_share_box')
		) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo blocksy_get_social_share_box([
				'html_atts' => [
					'data-type' => 'type-3',
				],
				'strategy' => [
					'strategy' => $layer,
				],
				'links_wrapper_attr' => [
					'data-icons-type' => 'simple',
				]
			]);
		}
	}

	public function register_layer_sharebox_defaults($opt) {
		return array_merge($opt, [
			[
				'id' => 'product_sharebox',
				'enabled' => true
			],
		]);
	}

	public function register_layer_options($opt) {
		return array_merge($opt, [
			'product_sharebox' => [
				'label' => __('Share Box', 'blocksy-companion'),
				'options' => [
					blocksy_rand_md5() => [
						'type' => 'ct-group',
						'label' => false,
						'options' => [
							blocksy_get_options(
								'single-elements/post-share-box',
								[
									'has_share_box_wrapper_attr' => true,
									'display_style' => 'general_only',
									'has_share_box_type' => false,
									'has_share_box_location1' => false,
									'has_bottom_share_box_spacing' => false,
									'has_share_items_border' => false,
									'has_forced_icons_spacing' => true,
									'has_module_title_design' => 'block',
									'has_module_title_tag' => false,
									'skip_sync_id' => [
										'id' => 'woo_single_layout_skip'
									]
								]
							),

							blocksy_rand_md5() => [
								'type' => 'ct-divider',
							],

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
				],
			],
		]);
	}
}
