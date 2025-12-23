<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class CustomBadges {
	public function __construct() {
		add_action(
			'wp_enqueue_scripts',
			function () {
				if (!function_exists('get_plugin_data')) {
					require_once ABSPATH . 'wp-admin/includes/plugin.php';
				}

				$data = get_plugin_data(BLOCKSY__FILE__);

				if (is_admin()) {
					return;
				}

				wp_enqueue_style(
					'blocksy-ext-woocommerce-extra-custom-badges-styles',
					BLOCKSY_URL .
						'framework/premium/extensions/woocommerce-extra/static/bundle/custom-badges.min.css',
					['blocksy-ext-woocommerce-extra-styles'],
					$data['Version']
				);
			},
			50
		);

		add_filter(
			'blocksy_customizer_options:woocommerce:general:badges:options',
			function ($opts) {
				$opts[] = blocksy_get_options(
					dirname(__FILE__) . '/options.php',
					[],
					false
				);

				return $opts;
			},
			60
		);

		add_filter(
			'blocksy_customizer_options:woocommerce:general:badges:design:options',
			function ($opts) {
				$opts[] = blocksy_get_options(
					dirname(__FILE__) . '/design-options.php',
					[],
					false
				);

				return $opts;
			},
			60
		);

		add_filter(
			'blocksy:woocommerce:product-card:badges',
			function ($badges) {
				$has_new_badge = blc_theme_functions()->blocksy_get_theme_mod('woo_has_new_custom_badge', [
					'archive' => false,
					'single' => false,
				]);

				$has_featured_badge = blc_theme_functions()->blocksy_get_theme_mod(
					'woo_has_featured_custom_badge',
					[
						'archive' => false,
						'single' => false,
					]
				);

				if (
					!$has_new_badge['archive'] &&
					!$has_featured_badge['archive']
				) {
					return $badges;
				}

				if ($has_new_badge['archive']) {
					$badges[] = $this->render_new_badge();
				}

				if ($has_featured_badge['archive']) {
					$badges[] = $this->render_featured_badge();
				}

				return [
					blocksy_html_tag(
						'div',
						[
							'class' => 'ct-woo-badges',
						],
						implode('', $badges)
					),
				];
			}
		);

		add_filter('blocksy:woocommerce:single:after-sale-badge', function ($badges) {
			$has_new_badge = blc_theme_functions()->blocksy_get_theme_mod('woo_has_new_custom_badge', [
				'archive' => false,
				'single' => false,
			]);

			$has_featured_badge = blc_theme_functions()->blocksy_get_theme_mod(
				'woo_has_featured_custom_badge',
				[
					'archive' => false,
					'single' => false,
				]
			);

			global $blocksy_is_quick_view;
			$location_key = $blocksy_is_quick_view ? 'archive' : 'single';

			if (
				!$has_new_badge[$location_key] &&
				!$has_featured_badge[$location_key]
			) {
				return $badges;
			}

			if ($has_new_badge[$location_key]) {
				$badges[] = $this->render_new_badge();
			}

			if ($has_featured_badge[$location_key]) {
				$badges[] = $this->render_featured_badge();
			}

			return [
				blocksy_html_tag(
					'div',
					[
						'class' => 'ct-woo-badges',
					],
					implode('', $badges)
				),
			];
		});
	}

	public function render_featured_badge() {
		global $product;

		$is_checked =
			get_post_meta(
				$product->get_id(),
				'_ct_is_product_featured',
				true
			) === 'yes' || $product->is_featured();

		if (!$is_checked) {
			return '';
		}

		return blocksy_html_tag(
			'span',
			[
				'class' => 'ct-woo-badge-featured',
				'data-shape' => blc_theme_functions()->blocksy_get_theme_mod('sale_badge_shape', 'type-2'),
			],
			blc_theme_functions()->blocksy_get_theme_mod(
				'woo_has_featured_custom_badge_label',
				__('HOT', 'blocksy-companion')
			)
		);
	}

	public function render_new_badge() {
		global $product;

		$datetime_created = $product->get_date_created();

		if (! $datetime_created) {
			return '';
		}

		$timestamp_created = $datetime_created->getTimestamp();

		$timestamp_now = time();

		$time_delta = $timestamp_now - $timestamp_created;
		$duration =
			blc_theme_functions()->blocksy_get_theme_mod('woo_has_new_custom_badge_duration', 14) *
			24 *
			60 *
			60;

		if ($time_delta < $duration) {
			return blocksy_html_tag(
				'span',
				[
					'class' => 'ct-woo-badge-new',
					'data-shape' => blc_theme_functions()->blocksy_get_theme_mod('sale_badge_shape', 'type-2'),
				],
				blc_theme_functions()->blocksy_get_theme_mod(
					'woo_has_new_custom_badge_label',
					__('NEW', 'blocksy-companion')
				)
			);
		}

		return '';
	}
}
