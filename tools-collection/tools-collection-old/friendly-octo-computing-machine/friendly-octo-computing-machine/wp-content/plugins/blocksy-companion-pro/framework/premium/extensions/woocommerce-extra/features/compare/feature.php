<?php

namespace Blocksy\Extensions\WoocommerceExtra;

require_once dirname(__FILE__) . '/helpers.php';

class CompareView {
	public function get_dynamic_styles_data($args) {
		return [
			'path' => dirname(__FILE__) . '/dynamic-styles.php'
		];
	}

	public function __construct() {
		add_filter('blocksy:header:items-paths', function ($paths) {
			$paths[] = dirname(__FILE__) . '/header-items';
			return $paths;
		});

		add_filter('blocksy:frontend:dynamic-js-chunks', function ($chunks) {
			if (! class_exists('WC_AJAX')) {
				return $chunks;
			}

			$chunks[] = [
				'id' => 'blocksy_ext_woo_extra_compare_list',
				'selector' => implode(', ', [
					'.ct-compare-remove',
					'[class*="ct-compare-button"]',
				]),
				'url' => blocksy_cdn_url(
					BLOCKSY_URL . 'framework/premium/extensions/woocommerce-extra/static/bundle/compare.js'
				),
				'trigger' => 'click',
				'has_loader' => [
					'type' => 'button'
				]
			];

			$chunks[] = [
				'id' => 'blocksy_ext_woo_extra_compare_modal',
				'selector' =>
					'[href="#ct-compare-modal"][data-behaviour="modal"], [data-shortcut="compare"][data-behaviour="modal"]',
				'url' => blocksy_cdn_url(
					BLOCKSY_URL . 'framework/premium/extensions/woocommerce-extra/static/bundle/compare-modal.js'
				),
				'trigger' => 'click',
				'has_loader' => [
					'type' => 'button',
					'id' => 'ct-compare-modal',
				]
			];

			$cache_manager = new \Blocksy\CacheResetManager();

			if ($cache_manager->is_there_any_page_caching()) {
				$chunks[] = [
					'id' => 'blocksy_ext_woo_extra_compare_list',
					'selector' => implode(', ', [
						'.ct-compare-bar',
						'.ct-header-compare',
						'[class*="ct-compare-button"]',
					]),
					'url' => blocksy_cdn_url(
						BLOCKSY_URL . 'framework/premium/extensions/woocommerce-extra/static/bundle/compare.js'
					),
				];
			}

			return $chunks;
		});


		add_filter('blocksy:frontend:dynamic-js-chunks', function ($chunks) {
			if (! class_exists('WC_AJAX')) {
				return $chunks;
			}

			$chunks[] = [
				'id' => 'blocksy_ext_woo_extra_compare_bar_tooltip',
				'selector' => '.ct-compare-bar',
				'url' => blocksy_cdn_url(
					BLOCKSY_URL .
					'framework/premium/extensions/woocommerce-extra/static/bundle/compare-bar-tooltip.js'
				),
				'trigger' => 'hover',
			];

			return $chunks;
		});

		add_action('wp_enqueue_scripts', function () {
			if (! function_exists('get_plugin_data')) {
				require_once(ABSPATH . 'wp-admin/includes/plugin.php');
			}

			$data = get_plugin_data(BLOCKSY__FILE__);

			if (blc_theme_functions()->blocksy_get_theme_mod('product_compare_bar', 'no') === 'no') {
				return;
			}

			wp_enqueue_style(
				'blocksy-ext-compare-bar',
				BLOCKSY_URL . 'framework/premium/extensions/woocommerce-extra/static/bundle/compare-bar.min.css',
				['ct-main-styles'],
				$data['Version']
			);
		});

		add_filter('blocksy:footer:offcanvas-drawer', function ($els, $payload) {
			if (
				$payload['location'] !== 'end'
				||
				blc_theme_functions()->blocksy_get_theme_mod('product_compare_bar', 'no') === 'no'
				||
				(
					defined('REST_REQUEST')
					&&
					REST_REQUEST
				)
			) {
				return $els;
			}

			$initial_conditions = [
				[
					'type' => 'include',
					'rule' => 'everywhere'
				]
			];

			$conditions = blc_theme_functions()->blocksy_get_theme_mod(
				'compare_bar_conditions',
				$initial_conditions
			);

			$conditions_manager = new \Blocksy\ConditionsManager();

			if (! $conditions_manager->condition_matches($conditions)) {
				return $els;
			}

			$content = blocksy_render_view(
				dirname(__FILE__) . '/views/bar.php',
				[]
			);

			$els[] = [
				'attr' => strpos($content, 'ct-container') === false ? [] : [
					'data-compare-bar' => '',
				],
				'content' => $content
			];

			return $els;
		}, 10, 2);

		add_filter('blocksy:header:selective_refresh', function ($selective_refresh) {
			$selective_refresh[] = [
				'id' => 'header_placements_item:compare',
				'fallback_refresh' => false,
				'container_inclusive' => true,
				'selector' => 'header [data-id="compare"]',
				'settings' => ['header_placements'],
				'render_callback' => function () {
					$header = new \Blocksy_Header_Builder_Render();
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo $header->render_single_item('compare');
				},
			];

			$selective_refresh[] = [
				'id' => 'header_placements_item:compare:offcanvas',
				'fallback_refresh' => false,
				'container_inclusive' => false,
				'selector' => '#offcanvas',
				'loader_selector' => '[data-id="compare"]',
				'settings' => ['header_placements'],
				'render_callback' => function () {
					$elements = new \Blocksy_Header_Builder_Elements();

					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo $elements->render_offcanvas([
						'has_container' => false,
					]);
				},
			];

			return $selective_refresh;
		});

		add_filter(
			'blocksy_customizer_options:woocommerce:general:end',
			function ($opts) {
				$opts['has_compare_panel'] = blocksy_get_options(
					dirname(__FILE__) . '/options.php',
					[],
					false
				);

				return $opts;
			},
			50
		);

		$this->boot_compare();

		add_action('wp_ajax_blocksy_get_woo_compare', [
			$this,
			'blocksy_get_woo_compare',
		]);

		add_action('wp_ajax_nopriv_blocksy_get_woo_compare', [
			$this,
			'blocksy_get_woo_compare',
		]);

		add_action('wp_ajax_blocksy_get_woo_compare_bar', [
			$this,
			'blocksy_get_woo_compare_bar',
		]);

		add_action('wp_ajax_nopriv_blocksy_get_woo_compare_bar', [
			$this,
			'blocksy_get_woo_compare_bar',
		]);

		add_filter(
			'blocksy_woo_card_options:additional_actions',
			function ($actions) {
				$actions[] = [
					'id' => 'has_archive_compare',
					'label' => __( 'Compare Button', 'blocksy-companion' )
				];

				return $actions;
			},
			2
		);

		add_filter(
			'blocksy:woocommerce:single-product:additional-actions',
			function ($actions) {
				$actions[] = [
					'id' => 'has_compare',
					'label' => __('Compare', 'blocksy-companion'),
					'options' => [
						'label' => [
							'type' => 'text',
							'value' => __('Compare', 'blocksy-companion'),
							'design' => 'block',
							'sync' => [
								'shouldSkip' => true,
							],
						],
					],
				];

				return $actions;
			}
		);
	}

	public function blocksy_get_woo_compare() {
		$content = $this->blocksy_get_woo_compare_output();

		wp_send_json_success([
			'content' => $content,
		]);
	}

	public function blocksy_get_woo_compare_output() {
		$table_html = CompareTable::render();

		$panel_attr = [
			'id' => 'ct-compare-modal',
			'class' => 'ct-panel',
			'data-behaviour' => 'modal',
			'role' => 'dialog',
			'aria-label' => __('Compare products modal', 'blocksy-companion'),
			'inert' => ''
		];

		$panel_heading = blocksy_html_tag(
			'div',
			[
				'class' => 'ct-panel-heading'
			],
			__('Compare Products', 'blocksy-companion') .

			blocksy_html_tag(
				'button',
				[
					'class' => 'ct-toggle-close',
					'aria-label' => __('Close Compare Modal', 'blocksy-companion'),
				],
				'<svg class="ct-icon" width="12" height="12" viewBox="0 0 15 15">
					<path d="M1 15a1 1 0 01-.71-.29 1 1 0 010-1.41l5.8-5.8-5.8-5.8A1 1 0 011.7.29l5.8 5.8 5.8-5.8a1 1 0 011.41 1.41l-5.8 5.8 5.8 5.8a1 1 0 01-1.41 1.41l-5.8-5.8-5.8 5.8A1 1 0 011 15z"></path>
				</svg>'
			)
		);

		$content = blocksy_html_tag(
			'div',
			$panel_attr,
			blocksy_html_tag(
				'div',
				[
					'class' => 'ct-panel-content',
				],
				blocksy_html_tag(
					'div',
					[
						'class' => 'ct-container',
					],
					$panel_heading .
					$table_html
				)
			)
		);

		return $content;
	}

	public function blocksy_get_woo_compare_bar() {
		$table_html = blocksy_render_view(
			dirname(__FILE__) . '/views/bar.php',
			[]
		);

		wp_send_json_success([
			'content' => $table_html,
			'items_to_compare' => $this->get_current_compare_list(),
		]);
	}

	public function boot_compare() {
		add_filter(
			'blocksy:general:ct-scripts-localizations',
			function ($data) {
				$data['blc_ext_compare_list'] = [
					'list' => $this->get_current_compare_list(),
				];

				return $data;
			}
		);

		add_filter('the_content', function ($content) {

			if (blc_theme_functions()->blocksy_get_theme_mod('compare_table_placement', 'modal') !== 'page') {
				return $content;
			}

			$maybe_page_id = blc_theme_functions()->blocksy_get_theme_mod('woocommerce_compare_page');

			if (empty($maybe_page_id)) {
				return $content;
			}

			if (! is_page($maybe_page_id)) {
				return $content;
			}

			$table_html = CompareTable::render();

			return $content . $table_html;
		});

		add_filter('blocksy:general:ct-scripts-localizations', function ($data) {
			$data['dynamic_styles_selectors'][] = [
				'selector' => '#ct-compare-modal',
				'url' => blocksy_cdn_url(
					BLOCKSY_URL . 'framework/premium/extensions/woocommerce-extra/static/bundle/compare.min.css'
				)
			];

			return $data;
		});

		add_action(
			'wp_enqueue_scripts',
			function() {
				$maybe_page_id = blc_theme_functions()->blocksy_get_theme_mod('woocommerce_compare_page');

				if (
					! empty($maybe_page_id)
					&&
					get_the_id() === $maybe_page_id
				) {
					if (! function_exists('get_plugin_data')) {
						require_once(ABSPATH . 'wp-admin/includes/plugin.php');
					}

					$data = get_plugin_data(BLOCKSY__FILE__);

					wp_enqueue_style(
						'blocksy-compare-list',
						BLOCKSY_URL . 'framework/premium/extensions/woocommerce-extra/static/bundle/compare.min.css',
						[],
						$data['Version']
					);
				}
			}
		);

		add_filter(
			'blocksy:woocommerce:single-product:additional-actions:content:has_compare',
			function ($content, $layer) {
				$content .= blocksy_output_add_to_compare('single', $layer);
				return $content;
			},
			10, 2
		);
	}

	public function get_current_compare_list() {
		return $this->get_cookie_compare_list();
	}

	private function normalize_list($list) {
		$new_list = [];

		foreach ($list as $item) {
			if (gettype($item) !== 'integer') {
				$new_list[] = $item;

				continue;
			}

			$new_list[] = (object) ['id' => $item];
		}

		return $new_list;
	}

	private function get_cookie_compare_list() {
		if (! isset($_COOKIE['blc_products_compare_list'])) {
			return [];
		}

		$maybe_decoded = json_decode(
			sanitize_text_field(wp_unslash($_COOKIE['blc_products_compare_list'])),
			true
		);

		if (! $maybe_decoded) {
			return [];
		}

		if (!is_array($maybe_decoded) && is_numeric($maybe_decoded)) {
			return $this->cleanup_compare([intval($maybe_decoded)]);
		}

		return $this->cleanup_compare($maybe_decoded);
	}

	private function cleanup_compare($input) {
		$input = array_intersect_key(
			$input,
			array_unique(
				array_column($input, 'id')
			)
		);

		return array_map(
			function ($item) {
				return $item;
			},

			array_filter(
				$input,

				function ($item) {
					if (! function_exists('wc_get_product')) {
						return false;
					}

					if (! isset($item['id'])) {
						return false;
					}

					return !! wc_get_product($item['id']) &&
					(
						wc_get_product($item['id'])->get_status() === 'publish'
						||
						(
							wc_get_product($item['id'])->get_status() === 'private'
							&&
							current_user_can('read_private_products')
						)
					);
				}
			)
		);
	}
}
