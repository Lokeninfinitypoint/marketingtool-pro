<?php

class BlocksyExtensionWoocommerceExtra {
	public $utils = null;
	public $filters = null;

	private $wish_list = null;
	private $compare = null;

	private $booted_features = '__NOT_BOOTED__';

	public function get_wish_list() {
		return $this->wish_list;
	}

	public function get_compare() {
		return $this->compare;
	}

	public function get_filters() {
		return $this->filters;
	}

	public function __construct() {
		$plugin_path = trailingslashit(WP_PLUGIN_DIR) . 'woocommerce/woocommerce.php';

		$requirement_check = (
			(
				function_exists('wp_get_active_and_valid_plugins')
				&&
				in_array($plugin_path, wp_get_active_and_valid_plugins())
			) || (
				function_exists('wp_get_active_network_plugins')
				&&
				in_array($plugin_path, wp_get_active_network_plugins())
			)
		);

		if (! $requirement_check) {
			return;
		}

		$this->init();
	}

	public function init() {
		$this->utils = new \Blocksy\Extensions\WoocommerceExtra\Utils();

		$this->boot_features();

		new \Blocksy\Extensions\WoocommerceExtra\CartPage();
		new \Blocksy\Extensions\WoocommerceExtra\ArchiveCard();
		new \Blocksy\Extensions\WoocommerceExtra\Checkout();
		new \Blocksy\Extensions\WoocommerceExtra\OffcanvasCart();

		new \Blocksy\Extensions\WoocommerceExtra\CustomBadges();
		new \Blocksy\Extensions\WoocommerceExtra\ProductSaleCountdown();

		new \Blocksy\Extensions\WoocommerceExtra\WooTermsImportExport();
		new \Blocksy\Extensions\WoocommerceExtra\WooHelpers();

		$this->define_cart_options();

		add_filter('blocksy:header:items-paths', function ($paths) {
			$paths[] = dirname(__FILE__) . '/header-items';
			return $paths;
		});

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
					'blocksy-ext-woocommerce-extra-styles',
					BLOCKSY_URL .
						'framework/premium/extensions/woocommerce-extra/static/bundle/main.min.css',
					['ct-main-styles'],
					$data['Version']
				);
			},
			50
		);

		add_action('customize_preview_init', function () {
			if (!function_exists('get_plugin_data')) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			$data = get_plugin_data(BLOCKSY__FILE__);

			wp_enqueue_script(
				'blocksy-woocommerce-extra-customizer-sync',
				BLOCKSY_URL .
					'framework/premium/extensions/woocommerce-extra/static/bundle/sync.js',
				['customize-preview', 'ct-scripts'],
				$data['Version'],
				true
			);
		});

		add_action('admin_enqueue_scripts', function () {
			if (!function_exists('get_plugin_data')) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			$data = get_plugin_data(BLOCKSY__FILE__);

			wp_enqueue_style(
				'blocksy-ext-woocommerce-extra-admin-styles',
				BLOCKSY_URL .
					'framework/premium/extensions/woocommerce-extra/static/bundle/admin.min.css',
				[],
				$data['Version']
			);
		});

		add_filter('blocksy:hooks-manager:woocommerce-archive-hooks', function (
			$hooks
		) {
			$hooks[] = [
				'hook' => 'blocksy:woocommerce:quick-view:title:before',
				'title' => __('Quick view title before', 'blocksy-companion'),
			];

			$hooks[] = [
				'hook' => 'blocksy:woocommerce:quick-view:title:after',
				'title' => __('Quick view title after', 'blocksy-companion'),
			];

			$hooks[] = [
				'hook' => 'blocksy:woocommerce:quick-view:price:before',
				'title' => __('Quick view price before', 'blocksy-companion'),
			];

			$hooks[] = [
				'hook' => 'blocksy:woocommerce:quick-view:price:after',
				'title' => __('Quick view price after', 'blocksy-companion'),
			];

			$hooks[] = [
				'hook' => 'blocksy:woocommerce:quick-view:summary:before',
				'title' => __('Quick view summary before', 'blocksy-companion'),
			];

			$hooks[] = [
				'hook' => 'blocksy:woocommerce:quick-view:summary:after',
				'title' => __('Quick view summary after', 'blocksy-companion'),
			];

			return $hooks;
		});

		// Allow did_action() checks more than once
		// https://pluginrepublic.com/wordpress-plugins/woocommerce-product-add-ons-ultimate/
		add_filter('pewc_check_did_action', function ($count) {
			return 5;
		});

		add_action(
			'blocksy:global-dynamic-css:enqueue',
			'BlocksyExtensionWoocommerceExtra::add_global_styles',
			10,
			3
		);
	}

	public function boot_features() {
		$this->booted_features = [];

		$storage = new \Blocksy\Extensions\WoocommerceExtra\Storage();
		$settings = $storage->get_settings();

		if (
			isset($settings['features']['floating-cart']) &&
			$settings['features']['floating-cart']
		) {
			$this->booted_features[] = new \Blocksy\Extensions\WoocommerceExtra\FloatingCart();
		}

		if (
			isset($settings['features']['quick-view']) &&
			$settings['features']['quick-view']
		) {
			$this->booted_features[] = new \Blocksy\Extensions\WoocommerceExtra\QuickView();
		}

		if (
			isset($settings['features']['filters']) &&
			$settings['features']['filters']
		) {
			$this->booted_features[] = new \Blocksy\Extensions\WoocommerceExtra\OffcanvasFilters();

			$this->filters = new \Blocksy\Extensions\WoocommerceExtra\Filters();
			$this->booted_features[] = $this->filters;
		}

		if (
			isset($settings['features']['wishlist']) &&
			$settings['features']['wishlist']
		) {
			$this->wish_list = new \Blocksy\Extensions\WoocommerceExtra\WishList();
			$this->booted_features[] = $this->wish_list;
		}

		if (
			isset($settings['features']['compareview']) &&
			$settings['features']['compareview']
		) {
			$this->compare = new \Blocksy\Extensions\WoocommerceExtra\CompareView();
			$this->booted_features[] = $this->compare;
		}

		if (
			isset($settings['features']['single-product-share-box']) &&
			$settings['features']['single-product-share-box']
		) {
			$this->booted_features[] = new \Blocksy\Extensions\WoocommerceExtra\ShareBoxLayer();
		}

		if (
			isset($settings['features']['advanced-gallery']) &&
			$settings['features']['advanced-gallery']
		) {
			$this->booted_features[] = new \Blocksy\Extensions\WoocommerceExtra\ProductGallery();
		}

		if (
			isset($settings['features']['search-by-sku']) &&
			$settings['features']['search-by-sku']
		) {
			$this->booted_features[] = new \Blocksy\Extensions\WoocommerceExtra\SkuSearch();
		}

		if (
			isset($settings['features']['stock-scarcity']) &&
			$settings['features']['stock-scarcity']
		) {
			$this->booted_features[] = new \Blocksy\Extensions\WoocommerceExtra\StockScarcity();
		}

		if (
			isset($settings['features']['free-shipping']) &&
			$settings['features']['free-shipping']
		) {
			$this->booted_features[] = new \Blocksy\Extensions\WoocommerceExtra\ShippingProgress();
		}

		if (
			isset($settings['features']['variation-swatches']) &&
			$settings['features']['variation-swatches']
		) {
			$this->booted_features[] = new \Blocksy\Extensions\WoocommerceExtra\Swatches();
		}

		if (
			isset($settings['features']['product-brands']) &&
			$settings['features']['product-brands']
		) {
			$this->booted_features[] = new \Blocksy\Extensions\WoocommerceExtra\Brands();
		}

		if (
			isset($settings['features']['product-affiliates']) &&
			$settings['features']['product-affiliates']
		) {
			$this->booted_features[] = new \Blocksy\Extensions\WoocommerceExtra\AffiliateProduct();
		}

		if (
			isset($settings['features']['product-custom-tabs']) &&
			$settings['features']['product-custom-tabs']
		) {
			$this->booted_features[] = new \Blocksy\Extensions\WoocommerceExtra\CustomTabs();
		}

		if (
			isset($settings['features']['product-size-guide']) &&
			$settings['features']['product-size-guide']
		) {
			$this->booted_features[] = new \Blocksy\Extensions\WoocommerceExtra\SizeGuide();
		}

		if (
			isset($settings['features']['product-custom-thank-you-page']) &&
			$settings['features']['product-custom-thank-you-page']
		) {
			$this->booted_features[] = new \Blocksy\Extensions\WoocommerceExtra\CustomThankYouPage();
			$this->booted_features[] = new \Blocksy\Extensions\WoocommerceExtra\OrderDetailsBlock();
		}

		if (
			isset($settings['features']['product-advanced-reviews']) &&
			$settings['features']['product-advanced-reviews']
		) {
			$this->booted_features[] = new \Blocksy\Extensions\WoocommerceExtra\AdvancedReviews();
		}

		if (
			isset($settings['features']['added-to-cart-popup']) &&
			$settings['features']['added-to-cart-popup']
		) {
			$this->booted_features[] = new \Blocksy\Extensions\WoocommerceExtra\AddedToCartPopup();
		}

		if (
			isset($settings['features']['product-waitlist']) &&
			$settings['features']['product-waitlist']
		) {
			$this->booted_features[] = new \Blocksy\Extensions\WoocommerceExtra\ProductWaitlist();
		}

		if (
			isset($settings['features']['suggested-products']) &&
			$settings['features']['suggested-products']
		) {
			$this->booted_features[] = new \Blocksy\Extensions\WoocommerceExtra\CartSuggestedProducts();
		}

		if (
			isset($settings['features']['cart-reserved-timer']) &&
			$settings['features']['cart-reserved-timer']
		) {
			$this->booted_features[] = new \Blocksy\Extensions\WoocommerceExtra\CartReservedTimer();
		}

		$this->booted_features[] = new \Blocksy\Extensions\WoocommerceExtra\SKULayer();
		$this->booted_features[] = new \Blocksy\Extensions\WoocommerceExtra\AttributesLayer();
		$this->booted_features[] = new \Blocksy\Extensions\WoocommerceExtra\RelatedSlideshow();
	}

	public static function add_global_styles($args) {
		blocksy_theme_get_dynamic_styles(
			array_merge(
				[
					'path' => dirname(__FILE__) . '/global.php',
					'chunk' => 'global',
				],
				$args
			)
		);

		foreach (blc_get_ext('woocommerce-extra')->booted_features as $feature) {
			if (! method_exists($feature, 'get_dynamic_styles_data')) {
				continue;
			}

			$data = $feature->get_dynamic_styles_data($args);

			blocksy_theme_get_dynamic_styles(
				array_merge(
					[
						'path' => $data['path'],
						'chunk' => 'global',
					],
					$args
				)
			);
		}
	}

	public static function onDeactivation() {
		remove_action(
			'blocksy:global-dynamic-css:enqueue',
			'BlocksyExtensionWoocommerceExtra::add_global_styles',
			10,
			3
		);
	}

	public function define_cart_options() {
		add_filter(
			'blocksy_customizer_options:woocommerce:general:end',
			function ($opts) {
				$cart_options_general = apply_filters(
					'blocsky:pro:woocommerce-extra:cart-options:general',
					[]
				);

				$cart_options_design = apply_filters(
					'blocsky:pro:woocommerce-extra:cart-options:design',
					[]
				);

				if (
					empty($cart_options_general) &&
					empty($cart_options_design)
				) {
					return $opts;
				}

				$opts[] = [
					blocksy_rand_md5() => [
						'label' => __('Cart Page', 'blocksy-companion'),
						'type' => 'ct-panel',
						'setting' => ['transport' => 'postMessage'],
						'inner-options' => $cart_options_general,
					],
				];

				return $opts;
			}
		);
	}
}
