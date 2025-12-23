<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class CartReservedTimer {
	public function get_dynamic_styles_data($args) {
		return [
			'path' => dirname(__FILE__) . '/dynamic-styles.php'
		];
	}

	public function __construct() {
		add_action(
			'wp_enqueue_scripts',
			function () {
				if (! function_exists('get_plugin_data')) {
					require_once ABSPATH . 'wp-admin/includes/plugin.php';
				}

				$data = get_plugin_data(BLOCKSY__FILE__);

				$render = new \Blocksy_Header_Builder_Render();
				$has_mini_cart = $render->contains_item('cart');

				if (
					is_admin()
					||
					(
						blc_theme_functions()->blocksy_get_theme_mod('woo_reserved_timer_in_cart', 'yes') === 'no'
						&&
						blc_theme_functions()->blocksy_get_theme_mod('woo_reserved_timer_in_mini_cart', 'no') === 'no'
					)
				) {
					return;
				}

				wp_enqueue_style(
					'blocksy-ext-woocommerce-extra-cart-reserved-timer-styles',
					BLOCKSY_URL .
						'framework/premium/extensions/woocommerce-extra/static/bundle/cart-reserved-timer.min.css',
					['blocksy-ext-woocommerce-extra-styles'],
					$data['Version']
				);
			},
			50
		);

		add_filter(
			'blocksy_customizer_options:woocommerce:general:end',
			function ($opts) {
				$opts['has_cart_reserved_timer_panel'] = blocksy_get_options(
					dirname(__FILE__) . '/options.php',
					[],
					false
				);

				return $opts;
			},
			55
		);

		add_filter('blocksy:frontend:dynamic-js-chunks', function ($chunks) {
			if (!class_exists('WC_AJAX')) {
				return $chunks;
			}

			$cache_manager = new \Blocksy\CacheResetManager();

			$trigger = [
				[
					// When page caching is active, we need to load on initial mount.
					// Otherwise, we can wait for a slight mouse movement.
					'trigger' => $cache_manager->is_there_any_page_caching() ? 'initial-mount' : 'slight-mousemove',
					'selector' => '[class*="ct-cart-reserved-timer"]',
				],

				[
					'selector' => '[class*="ct-cart-reserved-timer"]',
					'trigger' => 'jquery-event',
					'matchTarget' => false,
					'events' => [
						'added_to_cart',
						'removed_from_cart',
						'wc_fragments_refreshed',
						'updated_cart_totals',
					],
				]
			];

			$chunks[] = [
				'id' => 'blocksy_ext_woo_extra_cart_reserved_timer',
				'selector' => '[class*="ct-cart-reserved-timer"]',
				'mountOnLoad' => true,
				'trigger' => $trigger,
				'url' => blocksy_cdn_url(
					BLOCKSY_URL . 'framework/premium/extensions/woocommerce-extra/static/bundle/cart-reserved-timer.js'
				)
			];

			return $chunks;
		});

		add_action('wp', function () {
			if (
				blc_theme_functions()->blocksy_get_theme_mod('woo_reserved_timer_in_cart', 'yes') === 'yes'
				||
				blc_theme_functions()->blocksy_get_theme_mod('woo_reserved_timer_in_mini_cart', 'no') === 'yes'
			) {
				if ( ! WC()->session ) {
					return;
				}

				$last_modified = WC()->session->get('cart_last_modified');

				if ( ! $last_modified ) {
					return;
				}

				$woo_reserved_timer_time = blc_theme_functions()->blocksy_get_theme_mod('woo_reserved_timer_time', 10);

				$total_seconds = $woo_reserved_timer_time * 60;
				$expiration_time = $last_modified + $total_seconds - 1;

				if (current_time('timestamp', true) > $expiration_time) {
					WC()->cart->empty_cart();
					WC()->session->set('cart_last_modified', null);
				}
			}
		}, 20);

		add_action('wp', function () {
			if (blc_theme_functions()->blocksy_get_theme_mod('woo_reserved_timer_in_cart', 'yes') === 'yes') {
				add_action('woocommerce_cart_contents', function() {
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo $this->cart_page_render('cart');
				});
			}

			if (blc_theme_functions()->blocksy_get_theme_mod('woo_reserved_timer_in_mini_cart', 'no') === 'yes') {
				add_action('woocommerce_before_mini_cart', function() {
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo $this->cart_page_render('mini-cart');
				});
			}
		}, 25);

		if (
			blc_theme_functions()->blocksy_get_theme_mod('woo_reserved_timer_in_cart', 'yes') === 'yes'
			||
			blc_theme_functions()->blocksy_get_theme_mod('woo_reserved_timer_in_mini_cart', 'no') === 'yes'
		) {
			$hooks_to_listen = [
				'woocommerce_add_to_cart',
				'woocommerce_remove_cart_item',
				'woocommerce_cart_item_restored',
				'woocommerce_cart_item_removed',
				'woocommerce_after_cart_item_quantity_update',
			];

			foreach ($hooks_to_listen as $hook) {
				add_action($hook, function($cart_item_key, $product_id = null, $quantity = null) use ($hook) {
					if (! WC()->session) {
						return;
					}

					WC()->session->set('cart_last_modified', current_time('timestamp', true));
				}, 10, 3);
			}
		}

		add_action('wp_ajax_blc_ext_cart_reserved_timer_sync', [$this, 'sync_timers']);
		add_action('wp_ajax_nopriv_blc_ext_cart_reserved_timer_sync', [$this, 'sync_timers']);
	}

	public function sync_timers() {
		wp_send_json_success([
			'cart' => $this->cart_page_render('cart'),
			'mini_cart' => $this->cart_page_render('mini-cart'),
		]);
	}

	public function cart_page_render($class_suffix = 'cart') {

		$classes = [
			'ct-cart-reserved-timer-placeholder-' . $class_suffix
		];

		if (
			! WC()->session
			||
			WC()->cart->is_empty()
		) {
			return blocksy_html_tag(
				'div',
				['class' => join(' ', $classes)],
				''
			);
		}

		$last_modified = WC()->session->get('cart_last_modified');

		if (!$last_modified) {
			return blocksy_html_tag(
				'div',
				['class' => join(' ', $classes)],
				''
			);
		}

		$woo_reserved_timer_time = blc_theme_functions()->blocksy_get_theme_mod('woo_reserved_timer_time', 10);

		$total_seconds = $woo_reserved_timer_time * 60;
		$expiration_time = $last_modified + $total_seconds;

		$remaining = $total_seconds + $last_modified - current_time('timestamp', true);

		if ($remaining < 0) {
			$remaining = 0;
		}

		$remain_minutes = intdiv($remaining, 60);
		$remain_seconds = $remaining % 60;

		$date = gmdate('Y-m-d H:i:s', $expiration_time);

		$countdown = blocksy_html_tag(
			'time',
			[
				'datetime' => $date
			],
			blocksy_html_tag(
				'span',
				[],
				str_pad($remain_minutes, 2, '0', STR_PAD_LEFT)
			) .
			':' .
			blocksy_html_tag(
				'span',
				[],
				str_pad($remain_seconds, 2, '0', STR_PAD_LEFT)
			)
		);

		$message = str_replace(
			'{time}',
			$countdown,
			blc_theme_functions()->blocksy_get_theme_mod(
				'woo_reserved_timer_message',
				__('<p><strong>🔥 Hurry up! Your selected items are in high demand!</strong><br>Your cart will be reserved for {time} minutes.</p>', 'blocksy-companion'),
			)
		);

		$classes = [
			'ct-cart-reserved-timer-' . $class_suffix
		];

		return blocksy_html_tag(
			'div',
			[
				'class' => join(' ', $classes),
				'data-timeout' => $woo_reserved_timer_time,
			],
			blocksy_html_tag(
				'div',
				[
					'class' => 'ct-cart-reserved-timer-message'
				],
				$message
			)
		);
	}
}
