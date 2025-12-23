<?php

namespace Blocksy\Extensions\WoocommerceExtra;

require_once dirname(__FILE__) . '/helpers.php';

class WishList {
	private $wish_list_slug = null;

	public function get_dynamic_styles_data($args) {
		return [
			'path' => dirname(__FILE__) . '/dynamic-styles.php'
		];
	}

	public function __construct() {
		add_action(
			'wp_enqueue_scripts',
			function () {
				if (is_admin()) {
					return;
				}

				$maybe_page_id = blc_theme_functions()->blocksy_get_theme_mod('woocommerce_wish_list_page');

				if (!empty($maybe_page_id)) {
					$maybe_permalink = get_permalink($maybe_page_id);
				}

				if (
					(
						is_user_logged_in()
						&&
						blc_theme_functions()->blocksy_get_theme_mod('product_wishlist_display_for', 'logged_users') !== 'all_users'
						&&
						isset($_SERVER['REQUEST_URI'])
						&&
						strpos(sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])), $this->wish_list_slug) === false
					)
					||
					(
						blc_theme_functions()->blocksy_get_theme_mod('product_wishlist_display_for', 'logged_users') === 'all_users'
						&&
						$maybe_page_id !== get_the_ID()
						&&
						isset($_SERVER['REQUEST_URI'])
						&&
						strpos(sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])), $this->wish_list_slug) === false
					)
				) {
					return;
				}

				if (!function_exists('get_plugin_data')) {
					require_once ABSPATH . 'wp-admin/includes/plugin.php';
				}

				$data = get_plugin_data(BLOCKSY__FILE__);

				wp_enqueue_style(
					'blocksy-ext-woocommerce-extra-product-wishlist-table-styles',
					BLOCKSY_URL .
						'framework/premium/extensions/woocommerce-extra/static/bundle/wishlist-table.min.css',
					['blocksy-ext-woocommerce-extra-styles'],
					$data['Version']
				);
			},
			50
		);

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
					'blocksy-ext-woocommerce-extra-wishlist-styles',
					BLOCKSY_URL .
						'framework/premium/extensions/woocommerce-extra/static/bundle/wishlist.min.css',
					['blocksy-ext-woocommerce-extra-styles'],
					$data['Version']
				);
			},
			50
		);

		add_filter('blocksy:header:items-paths', function ($paths) {
			$paths[] = dirname(__FILE__) . '/header-items';
			return $paths;
		});

		add_filter('blocksy:frontend:dynamic-js-chunks', function ($chunks) {
			if (!class_exists('WC_AJAX')) {
				return $chunks;
			}

			$chunks[] = [
				'id' => 'blocksy_ext_woo_extra_wish_list',
				'selector' => implode(', ', [
					'[class*="ct-wishlist-button"]',
					'.ct-wishlist-remove',
					'.wishlist-product-remove > .remove',
					'.wishlist-product-name .product-mobile-actions > .remove',
				]),
				'url' => blocksy_cdn_url(
					BLOCKSY_URL .
						'framework/premium/extensions/woocommerce-extra/static/bundle/wish-list.js'
				),
				'trigger' => 'click',
				'has_loader' => [
					'type' => 'button',
				],
			];

			if (
				blc_theme_functions()->blocksy_get_theme_mod('has_variations_wishlist', 'no') === 'yes'
			) {
				$chunks[] = [
					'id' => 'blocksy_ext_woo_extra_variations_wish_list',
					'selector' => '.product .variations',
					'url' => blocksy_cdn_url(
						BLOCKSY_URL .
							'framework/premium/extensions/woocommerce-extra/static/bundle/variations-wish-list.js'
					),
					'deps' => [
						'underscore',
						'wc-add-to-cart-variation',
						'wp-util',
					],
					'trigger' => [
						'selector' => '.product .variations',
						'trigger' => 'jquery-event',
						'events' => [
							'found_variation',
							'reset_data'
						],
					],
				];
			}

			$cache_manager = new \Blocksy\CacheResetManager();

			if ($cache_manager->is_there_any_page_caching()) {
				$chunks[] = [
					'id' => 'blocksy_ext_woo_extra_wish_list',
					'selector' => implode(', ', [
						'.ct-header-wishlist',
						'[class*="ct-wishlist-button"]',
						'.ct-wishlist-remove',
						'.wishlist-product-remove > .remove',
						'.wishlist-product-name .product-mobile-actions > .remove',
					]),
					'url' => blocksy_cdn_url(
						BLOCKSY_URL .
							'framework/premium/extensions/woocommerce-extra/static/bundle/wish-list.js'
					),
				];
			}

			return $chunks;
		});

		add_filter('blocksy:header:selective_refresh', function (
			$selective_refresh
		) {
			$selective_refresh[] = [
				'id' => 'header_placements_item:wish-list',
				'fallback_refresh' => false,
				'container_inclusive' => true,
				'selector' => 'header [data-id="wish-list"]',
				'settings' => ['header_placements'],
				'render_callback' => function () {
					$header = new \Blocksy_Header_Builder_Render();
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo $header->render_single_item('wish-list');
				},
			];

			$selective_refresh[] = [
				'id' => 'header_placements_item:wish-list:offcanvas',
				'fallback_refresh' => false,
				'container_inclusive' => false,
				'selector' => '#offcanvas',
				'loader_selector' => '[data-id="wish-list"]',
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

		add_action('woocommerce_simple_add_to_cart', function () {
			global $product;

			if ($product->is_purchasable()) {
				return;
			}

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $this->get_wishlist_button_with_cart_actions();
		});

		add_filter(
			'blocksy_customizer_options:woocommerce:general:end',
			function ($opts) {
				$opts['has_wishlist_panel'] = blocksy_get_options(
					dirname(__FILE__) . '/options.php',
					[],
					false
				);

				return $opts;
			},
			50
		);

		$this->boot_wish_list();
	}

	public function boot_wish_list() {
		add_filter('blocksy:general:ct-scripts-localizations', function (
			$data
		) {
			$data['blc_ext_wish_list'] = [
				'user_logged_in' => is_user_logged_in() ? 'yes' : 'no',
				'list' => [
					'v' => 2,
					'items' => $this->get_current_wish_list(),
				],
			];

			return $data;
		});

		add_action('init', function () {
			$this->wish_list_slug = apply_filters(
				'blocksy:pro:woocommerce-extra:wish-list:slug',
				'woo-wish-list'
			);

			add_rewrite_endpoint($this->wish_list_slug, EP_ROOT | EP_PAGES);

			add_action(
				'woocommerce_account_' . $this->wish_list_slug . '_endpoint',
				function () {
					blocksy_render_view_e(
						dirname(__FILE__) . '/table.php',
						[]
					);
				}
			);
		});

		add_filter(
			'query_vars',
			function ($vars) {
				$vars[] = $this->wish_list_slug;
				return $vars;
			},
			0
		);

		add_action(
			'woocommerce_after_add_to_cart_button',
			function () {
				do_action('blocksy:pro:woo-extra:wishlist:button:output');
			},
			90
		);

		add_filter('the_content', function ($content) {
			if (
				blc_theme_functions()->blocksy_get_theme_mod(
					'product_wishlist_display_for',
					'logged_users'
				) === 'logged_users'
			) {
				return $content;
			}

			$maybe_page_id = blc_theme_functions()->blocksy_get_theme_mod(
				'woocommerce_wish_list_page'
			);

			if (empty($maybe_page_id)) {
				return $content;
			}

			if (!is_page($maybe_page_id)) {
				return $content;
			}

			return $content .
				blocksy_render_view(dirname(__FILE__) . '/table.php', []);
		});

		add_action('woocommerce_account_menu_items', function ($items) {
			$logout = $items['customer-logout'];
			unset($items['customer-logout']);

			$items[$this->wish_list_slug] = __(
				'Wishlist',
				'blocksy-companion'
			);
			$items['customer-logout'] = $logout;

			return $items;
		});

		add_filter(
			'woocommerce_account_menu_item_classes',
			function ($classes, $endpoint) {
				if ($endpoint === $this->wish_list_slug) {
					$classes[] = 'ct-wish-list';
				}

				return $classes;
			},
			10,
			2
		);

		add_action('wp_ajax_blc_ext_wish_list_sync_likes', [
			$this,
			'sync_wish_list',
		]);

		add_action('wp_ajax_nopriv_blc_ext_wish_list_sync_likes', [
			$this,
			'sync_wish_list',
		]);

		add_action('wp_ajax_blc_ext_wish_list_get_all_likes', [
			$this,
			'get_all_likes',
		]);

		add_action('wp_ajax_nopriv_blc_ext_wish_list_get_all_likes', [
			$this,
			'get_all_likes',
		]);

		add_action(
			'wp_login',
			function ($user_login, $user) {
				if (! $user) {
					return;
				}

				$cookie_value = $this->get_cookie_wish_list();
				$user_value = $this->get_user_wish_list($user->get('ID'));

				$final_value = [];

				foreach (array_merge($user_value, $cookie_value) as $entry) {
					$entry_is_repeated = false;

					foreach ($final_value as $final_entry) {
						// In this instance loose == is needed to account for
						// different keys order.
						if ($final_entry == $entry) {
							$entry_is_repeated = true;
						}
					}

					if (!$entry_is_repeated) {
						$final_value[] = $entry;
					}
				}

				update_user_meta($user->get('ID'), 'blc_products_wish_list', [
					'v' => 2,
					'items' => $final_value,
				]);

				setcookie('blc_products_wish_list', false);
			},
			10,
			2
		);

		add_filter('blocksy_woo_card_options:additional_actions', function (
			$actions
		) {
			$actions[] = [
				'id' => 'has_archive_wishlist',
				'label' => __('Wishlist Button', 'blocksy-companion'),
			];

			return $actions;
		}, 1);

		add_filter(
			'blocksy:woocommerce:single-product:additional-actions',
			function ($actions) {
				$actions[] = [
					'id' => 'has_wishlist',
					'label' => __('Wishlist', 'blocksy-companion'),
					'options' => [
						'label' => [
							'type' => 'text',
							'value' => __('Wishlist', 'blocksy-companion'),
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

		add_filter(
			'blocksy:woocommerce:single-product:additional-actions:content:has_wishlist',
			function ($content, $layer) {
				$content .= blocksy_output_add_to_wish_list('single', $layer);
				return $content;
			},
			10,
			2
		);

		add_filter(
			'blocksy-companion:pro:header:account:dropdown-items',
			function($layer_settings) {
				if (function_exists('wc_get_endpoint_url')) {
					$layer_settings['wishlist'] = [
						'label' => __('Wishlist', 'blocksy-companion'),
						'options' => [
							'label' => [
								'type' => 'text',
								'value' => __('Wishlist', 'blocksy-companion'),
								'design' => 'inline',
								'sync' => [
									'shouldSkip' => true,
								],
							],
						],
					];
				}

				return $layer_settings;
			}
		);
	}

	public function sync_wish_list() {
		if (!is_user_logged_in()) {
			wp_send_json_error();
		}

		$likes = json_decode(file_get_contents('php://input'), true);

		if (empty($likes)) {
			delete_user_meta(get_current_user_id(), 'blc_products_wish_list');
		} else {
			update_user_meta(
				get_current_user_id(),
				'blc_products_wish_list',
				$likes
			);
		}

		wp_send_json_success([]);
	}

	public function get_all_likes() {
		wp_send_json_success([
			'likes' => [
				'v' => 2,
				'items' => $this->get_current_wish_list(),
			],
			'user_logged_in' => is_user_logged_in() ? 'yes' : 'no',
		]);
	}

	public function get_current_wish_list() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if (is_user_logged_in() || isset($_GET['wish_list_id'])) {
			$user_id = get_current_user_id();

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if (isset($_GET['wish_list_id'])) {
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$user_id = sanitize_text_field(wp_unslash($_GET['wish_list_id']));
			}

			return $this->get_user_wish_list($user_id);
		}

		return $this->get_cookie_wish_list();
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

	private function get_user_wish_list($id) {
		$value = get_user_meta($id, 'blc_products_wish_list', true);

		if (!$value) {
			return [];
		}

		return $this->cleanup_wishlist($value);
	}

	private function get_cookie_wish_list() {
		if (!isset($_COOKIE['blc_products_wish_list'])) {
			return [];
		}

		$maybe_decoded = json_decode(
			stripslashes(sanitize_text_field(wp_unslash($_COOKIE['blc_products_wish_list']))),
			true
		);

		if (!$maybe_decoded) {
			return [];
		}

		if (!is_array($maybe_decoded) && is_numeric($maybe_decoded)) {
			return $this->cleanup_wishlist([intval($maybe_decoded)]);
		}

		return $this->cleanup_wishlist($maybe_decoded);
	}

	private function filter_existin_products($product_id) {
		$product = wc_get_product($product_id);

		if (!$product) {
			return false;
		}

		$status = $product->get_status();

		if ($status === 'trash') {
			return false;
		}

		if (
			$status === 'private' &&
			!current_user_can('read_private_products')
		) {
			return true;
		}

		return true;
	}

	private function cleanup_wishlist($input) {
		$normalized_input = $input;

		// Migrate from v2 to v1
		if (
			is_array($input) &&
			isset($input['v']) &&
			isset($input['items']) &&
			!empty($input['items'])
		) {
			return array_filter(
				$input['items'],

				function ($item) {
					if (
						! function_exists('wc_get_product')
						||
						! isset($item['id'])
					) {
						return false;
					}

					return $this->filter_existin_products($item['id']);
				}
			);
		}

		return array_map(
			function ($item) {
				return $item;
			},

			array_filter(
				$normalized_input,

				function ($item) {
					if (!function_exists('wc_get_product')) {
						return false;
					}

					return $this->filter_existin_products($item);
				}
			)
		);
	}

	public function get_wishlist_button_with_cart_actions() {
		$html = '';

		ob_start();
		do_action('blocksy:pro:woo-extra:wishlist:button:output');
		$after_add_to_cart_result = ob_get_clean();

		if (!empty($after_add_to_cart_result)) {
			ob_start();
			blocksy_woo_output_cart_action_open();
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $after_add_to_cart_result;
			echo '</div>';
			$html .= ob_get_clean();
		}

		return $html;
	}
}
