<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class QuickViewIntegrations {
	public function __construct() {
		// https://www.themehigh.com/product/woocommerce-product-variation-swatches/
		add_filter('thwvsf_is_quick_view_plugin_active', '__return_true');

		// https://yithemes.com/themes/plugins/yith-woocommerce-gift-cards/
		add_filter('yith_ywgc_do_eneuque_frontend_scripts', '__return_true');
	}
}
