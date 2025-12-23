<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class Storage {
	public function get_settings() {
		$default = [
			'features' => [
				'floating-cart' => false,
				'quick-view' => false,
				'filters' => false,
				'wishlist' => false,
				'compareview' => false,
				'single-product-share-box' => false,
				'advanced-gallery' => false,
				'search-by-sku' => false,
				'free-shipping' => false,
				'variation-swatches' => false,
				'product-brands' => false,
				'product-affiliates' => false,
				'product-custom-tabs' => false,
				'product-size-guide' => false,
				'product-custom-thank-you-page' => false,
				'product-advanced-reviews' => false,
				'stock-scarcity' => false,
				'added-to-cart-popup' => false,
				'product-waitlist' => false,
				'suggested-products' => false,
			],

			'product-brands-slug' => 'brand'
		];

		$settings = get_option(
			'blocksy_ext_woocommerce_extra_settings',
			$default
		);

		if (! is_array($settings)) {
			$settings = $default;
		}

		if (! isset($settings['features'])) {
			$settings['features'] = [];
		}

		$settings['features'] = array_merge(
			$default['features'],
			$settings['features']
		);

		return array_merge($default, $settings);
	}
}
