<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class ProductWaitlist {
	public function get_dynamic_styles_data($args) {
		return [
			'path' => dirname(__FILE__) . '/dynamic-styles.php'
		];
	}

	public function __construct() {
		new ProductWaitlistDb();
		new ProductWaitlistLayer();

		new ProductWaitlistDashboard();
		new ProductWaitlistMailer();

		new ProductWaitlistAccount();

		add_filter(
			'blocksy_customizer_options:woocommerce:general:end',
			function ($opts) {
				$opts['has_product_waitlist_panel'] = blocksy_get_options(
					dirname(__FILE__) . '/customizer-options.php',
					[],
					false
				);

				return $opts;
			},
			55
		);

		add_filter(
			'blocksy:general:ct-scripts-localizations',
			function ($data) {
				$waitlist = ProductWaitlistDb::get_waitlist();

				if (!function_exists('get_plugin_data')) {
					require_once ABSPATH . 'wp-admin/includes/plugin.php';
				}

				$plugin_data = get_plugin_data(BLOCKSY__FILE__);

				$data['dynamic_styles']['waitlist'] = add_query_arg(
					'ver',
					$plugin_data['Version'],
					blocksy_cdn_url(
						BLOCKSY_URL .
							'framework/premium/extensions/woocommerce-extra/static/bundle/single-product-waitlist.min.css',
					)
				);

				$data['blc_ext_waitlist'] = [
					'user_logged_in' => is_user_logged_in() ? 'yes' : 'no',
					'waitlist_allow_backorders' => blc_theme_functions()->blocksy_get_theme_mod('waitlist_allow_backorders', 'no'),
					'list' => array_map(function ($item) {
						return $item->subscription_id;
					}, $waitlist),
				];

				return $data;
			}
		);

		add_action(
			'wp_login',
			function ($user_login, $user) {
				if (! $user) {
					return;
				}

				$user_id = $user->get('ID');

				ProductWaitlistDb::update_for_user($user_id);
			},
			10,
			2
		);

		add_action(
			'wp_enqueue_scripts',
			function () {
				if (!function_exists('get_plugin_data')) {
					require_once ABSPATH . 'wp-admin/includes/plugin.php';
				}

				$data = get_plugin_data(BLOCKSY__FILE__);

				$storage = new \Blocksy\Extensions\WoocommerceExtra\Storage();
				$settings = $storage->get_settings();

				wp_register_style(
					'blocksy-ext-woocommerce-extra-product-waitlist-styles',
					BLOCKSY_URL .
						'framework/premium/extensions/woocommerce-extra/static/bundle/single-product-waitlist.min.css',
					['blocksy-ext-woocommerce-extra-styles'],
					$data['Version']
				);

				if (
					is_admin()
					||
					! is_singular('product')
					||
					(
						is_singular('product')
						&&
						function_exists('blocksy_has_product_specific_layer')
						&&
						! blocksy_has_product_specific_layer('product_waitlist')
						&&
						! is_customize_preview()
					)
				) {
					return;
				}

				wp_enqueue_style('blocksy-ext-woocommerce-extra-product-waitlist-styles');
			},
			50
		);

		add_filter('blocksy:frontend:dynamic-js-chunks', function ($chunks) {
			if (!class_exists('WC_AJAX')) {
				return $chunks;
			}

			$chunks[] = [
				'id' => 'blocksy_ext_woo_extra_waitlist',
				'selector' => '.product .ct-product-waitlist',
				'trigger' => [
					[
						'trigger' => 'jquery-event',
						'events' => [
							'found_variation',
							'reset_data'
						],
						'selector' => '.product .ct-product-waitlist',
					],
				],
				'url' => blocksy_cdn_url(
					BLOCKSY_URL .
						'framework/premium/extensions/woocommerce-extra/static/bundle/product-waitlist.js'
				)
			];

			$chunks[] = [
				'id' => 'blocksy_ext_woo_extra_waitlist',
				'selector' => implode(', ', [
					'.product .ct-product-waitlist-form',
				]),
				'url' => blocksy_cdn_url(
					BLOCKSY_URL .
						'framework/premium/extensions/woocommerce-extra/static/bundle/product-waitlist.js'
				),
				'trigger' => 'submit',
			];

			$chunks[] = [
				'id' => 'blocksy_ext_woo_extra_waitlist',
				'selector' => implode(', ', [
					'.product .ct-waitlist-subscribed-state .unsubscribe',
					'.waitlist-product-actions .unsubscribe',
				]),
				'url' => blocksy_cdn_url(
					BLOCKSY_URL .
						'framework/premium/extensions/woocommerce-extra/static/bundle/product-waitlist.js'
				),
				'trigger' => 'click',
			];

			$cache_manager = new \Blocksy\CacheResetManager();

			if ($cache_manager->is_there_any_page_caching()) {
				$chunks[] = [
					'id' => 'blocksy_ext_woo_extra_waitlist',
					'selector' => implode(', ', [
						'.product .ct-product-waitlist-form',
					]),
					'url' => blocksy_cdn_url(
						BLOCKSY_URL .
							'framework/premium/extensions/woocommerce-extra/static/bundle/product-waitlist.js'
					),
				];
			}

			return $chunks;
		});

		add_filter(
			'blocksy-companion:pro:header:account:dropdown-items',
			function($layer_settings) {
				if (function_exists('wc_get_endpoint_url')) {
					$layer_settings['waitlist'] = [
						'label' => __('Waitlist', 'blocksy-companion'),
						'options' => [
							'label' => [
								'type' => 'text',
								'value' => __('Waitlist', 'blocksy-companion'),
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

        add_filter(
			'woocommerce_available_variation',
			function ($result, $product, $variation) {
				$maybe_user_email = '';

				if (is_user_logged_in()) {
					$maybe_user_email = wp_get_current_user()->user_email;
				}

				$product = wc_get_product($variation->get_id());

				$waitlist = ProductWaitlistDb::get_waitlist($product, $maybe_user_email, false);
				$count_data = ProductWaitlistLayer::get_users_count($product->get_id());

                $result['blocksy_waitlist'] = [
					'subscription_id' => ! empty($waitlist) ? $waitlist[0]->subscription_id : '',
					'unsubscribe_token' => ! empty($waitlist) ? $waitlist[0]->unsubscribe_token : '',
					'waitlist_users' => $count_data['waitlist_users'],
					'waitlist_users_message' => $count_data['message'],
				];

				$result['blocksy_stock_quantity'] = $variation->get_stock_quantity();

				return $result;
            },
			10,
			3
        );
	}
}
