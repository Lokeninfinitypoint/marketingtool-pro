<?php

namespace Blocksy;

class RecentlyViewedProducts {
	private $is_tracking = false;

	public function start_tracking() {
		if ($this->is_tracking) {
			return;
		}

		$this->is_tracking = true;

		remove_action('template_redirect', 'wc_track_product_view', 20);
		add_action('template_redirect', [$this, 'track_product_view'], 20);
	}

	static public function get_recently_viewed_products() {
		if (empty($_COOKIE['woocommerce_recently_viewed'])) {
			return [];
		}

		return wp_parse_id_list(
			(array) explode(
				'|',
				// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- WooCommerce cookie, sanitized by wp_parse_id_list()
				wp_unslash($_COOKIE['woocommerce_recently_viewed'])
			)
		);
	}

	public function track_product_view() {
		if (! is_singular('product')) {
			return;
		}

		global $post;

		$viewed_products = self::get_recently_viewed_products();

		// Unset if already in viewed products list.
		$keys = array_flip($viewed_products);

		if (isset($keys[ $post->ID ])) {
			unset($viewed_products[ $keys[ $post->ID ] ]);
		}

		$viewed_products[] = $post->ID;

		if (count($viewed_products) > 15) {
			array_shift($viewed_products);
		}

		// Store for session only.
		wc_setcookie('woocommerce_recently_viewed', implode('|', $viewed_products));
	}
}
