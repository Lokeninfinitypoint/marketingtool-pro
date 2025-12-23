<?php

namespace Blocksy\Extensions\WoocommerceExtra;

require_once dirname(__FILE__) . '/helpers.php';

class OffcanvasFilters {
    private $is_ajax = false;

	public function get_dynamic_styles_data($args) {
		return [
			'path' => dirname(__FILE__) . '/dynamic-styles.php'
		];
	}

	public function __construct() {
		add_action(
			'wp_enqueue_scripts',
			function () {
				if ($this->has_filter_ajax_reveal()) {
					wp_enqueue_style(
						'blocksy-ext-woocommerce-extra-variation-swatches-styles'
					);
				}
			},
			60
		);

		add_filter('blocksy:frontend:dynamic-js-chunks', function ($chunks) {
			if (! $this->has_filter_ajax_reveal()) {
				return $chunks;
			}

			$chunks[] = [
				'id' => 'blocksy_ext_woo_extra_filters_ajax_reveal',
				'selector' => '[class*="toggle-filter-panel"]',
				'url' => blocksy_cdn_url(
					BLOCKSY_URL . 'framework/premium/extensions/woocommerce-extra/static/bundle/ajax-filter-reveal.js'
				),
				'trigger' => 'click',
				'has_loader' => [
					'type' => 'button'
				]
			];

			return $chunks;
		});

		add_action(
			'wp_enqueue_scripts',
			function () {
				if (!function_exists('get_plugin_data')) {
					require_once ABSPATH . 'wp-admin/includes/plugin.php';
				}

				$data = get_plugin_data(BLOCKSY__FILE__);

				if (
					is_admin()
					||
					is_singular()
					||
					(
						blc_theme_functions()->blocksy_get_theme_mod('has_woo_offcanvas_filter', 'no') === 'no'
						&&
						! is_customize_preview()
					)
					||
					(
						function_exists('is_woocommerce')
						&&
						! is_woocommerce()
					)
				) {
					return;
				}

				wp_enqueue_style(
					'blocksy-ext-woocommerce-extra-off-canvas-filters-styles',
					BLOCKSY_URL .
						'framework/premium/extensions/woocommerce-extra/static/bundle/off-canvas-filter.min.css',
					['blocksy-ext-woocommerce-extra-styles'],
					$data['Version']
				);

				$sidebars = get_option('sidebars_widgets', []);

				$filter_source = blc_theme_functions()->blocksy_get_theme_mod('filter_source', 'sidebar-woocommerce-offcanvas-filters');

				if (class_exists('BlocksySidebarsManager')) {
					$manager = new \BlocksySidebarsManager();

					$maybe_sidebar = $manager->maybe_get_sidebar_that_matches();

					if ($maybe_sidebar) {
						$filter_source = $maybe_sidebar;
					}
				}

				if (
					empty($sidebars)
					||
					! isset($sidebars[$filter_source])
				) {
					return;
				}

				$widgets = $sidebars[$filter_source];
				$blocks_data = get_option('widget_block');

				foreach ($widgets as $key => $value) {
					if (! isset($blocks_data[str_replace('block-', '', $value)])) {
						continue;
					}

					$blocks = parse_blocks($blocks_data[str_replace('block-', '', $value)]['content']);

					if (empty($blocks)) {
						continue;
					}

					$block = $blocks[0];

					if ($block['blockName']) {
						if (strpos($block['blockName'], 'blocksy/') !== 0) {
							continue;
						}

						$scripts = ['blocksy-block-' . explode('/', $block['blockName'])[1] . '-styles'];

						if (isset($block['innerBlocks'])) {
							foreach ($block['innerBlocks'] as $innerBlock) {
								if (strpos($innerBlock['blockName'], 'blocksy/') !== 0) {
									continue;
								}

								array_push($scripts, 'blocksy-block-' . explode('/', $innerBlock['blockName'])[1] . '-styles');
							}
						}

						foreach ($scripts as $script) {
							if (wp_style_is($script, 'registered')) {
								wp_enqueue_style($script);
							}
						}
					}
				}
			},
			50
		);

		add_action(
			'blocksy:widgets_init',
			function ($sidebar_title_tag) {
				register_sidebar(
					[
						'name' => esc_html__(
							'WooCommerce Filters Canvas',
							'blocksy-companion'
						),
						'id' => 'sidebar-woocommerce-offcanvas-filters',
						'description' => esc_html__(
							'Add widgets here.',
							'blocksy-companion'
						),
						'before_widget' => '<div class="ct-widget %2$s" id="%1$s">',
						'after_widget' => '</div>',
						'before_title' => '<' . $sidebar_title_tag . ' class="widget-title">',
						'after_title' => '</' . $sidebar_title_tag . '>',
					]
				);
			}
		);

		add_action(
			'woocommerce_before_shop_loop',
			function () {
				if (
					! is_shop()
					&&
					! is_product_category()
					&&
					! is_product_tag()
					&&
					! is_woocommerce()
				) {
					return;
				}

				global $has_woo_offcanvas_filter;
				$has_woo_offcanvas_filter = true;

				if (blc_theme_functions()->blocksy_get_theme_mod('has_woo_offcanvas_filter', 'no') === 'no') {
					return;
				}

				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo blc_get_woo_offcanvas_trigger(
					$this->has_filter_ajax_reveal()
				);
			},
			18
		);

		add_action('woocommerce_no_products_found', [$this, 'render_filters_panel'], 8);
		add_action('woocommerce_before_shop_loop', [$this, 'render_filters_panel'], 99);

		add_filter(
			'blocksy:footer:offcanvas-drawer',
			function ($els, $payload) {
				if (
					$this->has_filter_ajax_reveal()
					&&
					! (
						$this->has_filter_ajax_reveal()
						&&
						blc_is_xhr()
					)
				) {
					return $els;
				}

				if ($payload['location'] !== 'start') {
					return $els;
				}

				if (blc_theme_functions()->blocksy_get_theme_mod('woocommerce_filter_type', 'type-1') !== 'type-1') {
					return $els;
				}

				global $has_woo_offcanvas_filter;

				if (
					blc_theme_functions()->blocksy_get_theme_mod('has_woo_offcanvas_filter', 'no') === 'no'
					&&
					! is_customize_preview()
				) {
					return $els;
				}

				if (
					! is_shop()
					&&
					! is_product_category()
					&&
					! is_product_tag()
					&&
					! is_woocommerce()
					&&
					! $has_woo_offcanvas_filter
				) {
					return $els;
				}

				if (
					! blc_theme_functions()->blocksy_manager()
					||
					blc_theme_functions()->blocksy_manager()->screen->is_product()
				) {
					return $els;
				}

				$els[] = blocksy_render_view(
					dirname(__FILE__) . '/views/panel.php',
					[]
				);

				return $els;
			},
			10, 2
		);

		add_filter(
			'blocksy:options:woocommerce:archive:filters-canvas',
			function ($opts) {
				$opts['has_woo_offcanvas_filter'] = blocksy_get_options(
					dirname(__FILE__) . '/options.php',
					[],
					false
				);

				return $opts;
			}
		);
	}

	public function render_filters_panel() {
		if (
			blc_theme_functions()->blocksy_get_theme_mod('woocommerce_filter_type', 'type-1') === 'type-1'
			||
			blc_theme_functions()->blocksy_get_theme_mod('has_woo_offcanvas_filter', 'no') === 'no'
		) {
			return;
		}

		blocksy_render_view_e(
			dirname(__FILE__) . '/views/inline.php',
			[
				'has_filter_ajax_reveal' => $this->has_filter_ajax_reveal(),
				'is_ajax' => $this->is_ajax,
			]
		);
	}

	public function has_filter_ajax_reveal() {
		$filter_ajax_reveal = blc_theme_functions()->blocksy_get_theme_mod(
			'filter_ajax_reveal',
			'no'
		);

		if (blc_theme_functions()->blocksy_get_theme_mod(
			'filter_panel_behaviour',
			'no'
		) === 'yes') {
			$filter_ajax_reveal = 'no';
		}

		return $filter_ajax_reveal === 'yes';
	}
}
