<?php


add_filter(
	'blocksy:woocommerce:default_product_ratio',
	function () {
		return blc_theme_functions()->blocksy_get_theme_mod(
			'woocommerce_quickview_gallery_ratio',
			'3/4'
		);
	},
	50
);

$panel_attr = [
	'id' => 'ct-quick-view-' . $id,
	'class' => 'ct-panel quick-view-modal',
	'data-behaviour' => 'modal'
];

$panel_content_attr = [
	'class' => 'ct-panel-content'
];

$has_arrows = blc_theme_functions()->blocksy_get_theme_mod('woocommerce_quickview_navigation', 'no');

if (isset($is_customize_preview) && $is_customize_preview) {
	$has_arrows = 'yes';
}

if ($has_arrows === 'yes') {
	$panel_content_attr['data-arrows'] = '';
}

?>

<div <?php blocksy_attr_to_html_e($panel_attr); ?>>
	<div <?php blocksy_attr_to_html_e($panel_content_attr); ?>>
		<?php if ($has_arrows === 'yes') { ?>
			<a href="#" class="ct-quick-view-nav-prev">
				<svg width="16" height="10" viewBox="0 0 16 10" fill="currentColor">
					<path d="M15.3 4.3h-13l2.8-3c.3-.3.3-.7 0-1-.3-.3-.6-.3-.9 0l-4 4.2-.2.2v.6c0 .1.1.2.2.2l4 4.2c.3.4.6.4.9 0 .3-.3.3-.7 0-1l-2.8-3h13c.2 0 .4-.1.5-.2s.2-.3.2-.5-.1-.4-.2-.5c-.1-.1-.3-.2-.5-.2z"></path>
				</svg>
			</a>
		<?php } ?>

		<div <?php wc_product_class('ct-container ct-quick-view-card single-product', $product->get_id()); ?>>
			<button <?php
				blocksy_attr_to_html_e([
					'class' => 'ct-toggle-close',
					'aria-label' => __('Close quick view', 'blocksy-companion'),
				]);
			?>>
				<svg class="ct-icon" width="12" height="12" viewBox="0 0 15 15">
					<path d="M1 15a1 1 0 01-.71-.29 1 1 0 010-1.41l5.8-5.8-5.8-5.8A1 1 0 011.7.29l5.8 5.8 5.8-5.8a1 1 0 011.41 1.41l-5.8 5.8 5.8 5.8a1 1 0 01-1.41 1.41l-5.8-5.8-5.8 5.8A1 1 0 011 15z"/>
				</svg>
			</button>

			<div class="ct-quick-view-content">
				<?php woocommerce_show_product_images(); ?>

				<div class="woocommerce-product-summary">
					<div class="entry-summary">
						<?php
							do_action('blocksy:woocommerce:quick-view:title:before');
							woocommerce_template_single_title();
							do_action('blocksy:woocommerce:quick-view:title:after');

							do_action('blocksy:woocommerce:quick-view:price:before');
							woocommerce_template_single_price();
							do_action('blocksy:woocommerce:quick-view:price:after');

							do_action('blocksy:woocommerce:quick-view:summary:before');
							woocommerce_template_single_excerpt();
							do_action('blocksy:woocommerce:quick-view:summary:after');

							do_action('blocksy:woocommerce:quick-view:add-to-cart:before');
							woocommerce_template_single_add_to_cart();
							do_action('blocksy:woocommerce:quick-view:add-to-cart:after');

							woocommerce_template_single_meta();
						?>

						<a href="<?php echo esc_url(get_permalink($variation ? $variation->get_id() : $product->get_id())); ?>" class="ct-button ct-quick-more has-text-align-center">
							<?php echo esc_html__('Go to product page', 'blocksy-companion'); ?>
						</a>
					</div>
				</div>
			</div>
		</div>

		<?php if ($has_arrows === 'yes') { ?>
			<a href="#" class="ct-quick-view-nav-next">
				<svg width="16" height="10" viewBox="0 0 16 10" fill="currentColor">
					<path d="M.2 4.5c-.1.1-.2.3-.2.5s.1.4.2.5c.1.1.3.2.5.2h13l-2.8 3c-.3.3-.3.7 0 1 .3.3.6.3.9 0l4-4.2.2-.2V5v-.3c0-.1-.1-.2-.2-.2l-4-4.2c-.3-.4-.6-.4-.9 0-.3.3-.3.7 0 1l2.8 3H.7c-.2 0-.4.1-.5.2z"></path>
				</svg>
			</a>
		<?php } ?>
	</div>
</div>

