<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class ProductWaitlistAccount {
    private $waitlist_slug = null;

    public function __construct() {
        add_action('init', function () {
			$this->waitlist_slug = apply_filters(
				'blocksy:pro:woocommerce-extra:waitlist-list:slug',
				'woo-waitlist-list'
			);

			add_rewrite_endpoint($this->waitlist_slug, EP_ROOT | EP_PAGES);

			add_action(
				'woocommerce_account_' . $this->waitlist_slug . '_endpoint',
				function () {
					blocksy_render_view_e(
						dirname(__FILE__) . '/views/table.php',
						[]
					);
				}
			);
		});

		add_action(
			'wp_enqueue_scripts',
			function () {
				if (
					! isset($_SERVER['REQUEST_URI'])
					||
					strpos(sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])), $this->waitlist_slug) === false
				) {
					return;
				}

				if (!function_exists('get_plugin_data')) {
					require_once ABSPATH . 'wp-admin/includes/plugin.php';
				}

				$data = get_plugin_data(BLOCKSY__FILE__);

				wp_enqueue_style(
					'blocksy-ext-woocommerce-extra-product-waitlist-styles',
					BLOCKSY_URL .
						'framework/premium/extensions/woocommerce-extra/static/bundle/waitlist-table.min.css',
					['blocksy-ext-woocommerce-extra-styles'],
					$data['Version']
				);
			},
			50
		);

		add_filter(
			'query_vars',
			function ($vars) {
				$vars[] = $this->waitlist_slug;
				return $vars;
			},
			0
		);

        add_action('woocommerce_account_menu_items', function ($items) {
			$logout = $items['customer-logout'];
			unset($items['customer-logout']);

			$items[$this->waitlist_slug] = __(
				'Waitlist',
				'blocksy-companion'
			);
			$items['customer-logout'] = $logout;

			return $items;
		});
    }
}
