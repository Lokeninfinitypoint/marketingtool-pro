<?php

global $product;

global $post;

if (is_string($product)) {
	$product = wc_get_product();
}

if (! $product && $post) {
	$product = wc_get_product($post->ID);
}

$image_output = '';

$image_visibility = blocksy_visibility_classes(
	blc_theme_functions()->blocksy_get_theme_mod('floatingBarImageVisibility', [
		'desktop' => true,
		'tablet' => true,
		'mobile' => true,
	])
);

if ($product && $product->get_image_id()) {
	$image_output = blocksy_media([
		'attachment_id' => $product->get_image_id(),
		'size' => 'woocommerce_gallery_thumbnail',
		'ratio' => '1/1',
		'lazyload' => false,
		'tag_name' => 'div',
		'class' => $image_visibility
	]);
}

$class = 'ct-floating-bar';

$has_ajax_add_to_cart = blc_theme_functions()->blocksy_get_theme_mod(
	'has_ajax_add_to_cart',
	'yes'
);

if (
	function_exists('blocksy_has_product_specific_layer')
	&&
	blocksy_has_product_specific_layer('product_add_to_cart')
	&&
	$product
	&&
	! $product->is_type('external')
	&&
	$has_ajax_add_to_cart === 'yes'
	&&
	get_option('woocommerce_cart_redirect_after_add', 'no') === 'no'
) {
	$class .= ' ct-ajax-add-to-cart';
}

$class .= ' ' . blocksy_visibility_classes(
	blc_theme_functions()->blocksy_get_theme_mod('floatingBarVisibility', [
		'desktop' => true,
		'tablet' => true,
		'mobile' => true,
	])
);

$title_class = trim('product-title ' . blocksy_visibility_classes(
	blc_theme_functions()->blocksy_get_theme_mod('floatingBarTitleVisibility', [
		'desktop' => true,
		'tablet' => true,
		'mobile' => true,
	])
));

$price_stock_class = trim('product-price ' . blocksy_visibility_classes(
	blc_theme_functions()->blocksy_get_theme_mod('floatingBarPriceStockVisibility', [
		'desktop' => true,
		'tablet' => true,
		'mobile' => true,
	])
));

?>

<div
	class="<?php echo esc_attr(trim($class)) ?>"
	<?php
		if (
			is_customize_preview()
			&&
			function_exists('blocksy_attr_to_html')
		) {
			blocksy_attr_to_html_e([
				'data-shortcut' => 'border',
				'data-shortcut-location' => 'woocommerce_single:has_floating_bar'
			]);
		}
	?>
>
	<div class="ct-container">
		<section class="ct-floating-bar-content">
			<?php
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $image_output
			?>
			<div class="ct-floating-bar-item-title">
				<?php the_title( '<div class="' . $title_class . '">', '</div>' ); ?>

				<div <?php blocksy_attr_to_html_e(['class' => $price_stock_class]); ?>>
					<?php woocommerce_template_single_price(); ?>
					<?php
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						echo wc_get_stock_html($product);
					?>
				</div>
			</div>
		</section>

		<section class="ct-floating-bar-actions">
			<?php
				// woocommerce_template_single_price();

				$is_simple_product = blc_get_ext('woocommerce-extra')
					->utils
					->is_simple_product($product);

				global $blocksy_is_floating_cart;
				$blocksy_is_floating_cart = true;

				if ($is_simple_product['value']) {
					global $wp_filter;

					if (isset($wp_filter['woocommerce_before_add_to_cart_quantity'])) {
						$old_before = $wp_filter['woocommerce_before_add_to_cart_quantity'];
					}

					if (isset($wp_filter['woocommerce_after_add_to_cart_quantity'])) {
						$old = $wp_filter['woocommerce_after_add_to_cart_quantity'];
					}

					if (isset($wp_filter['woocommerce_before_add_to_cart_button'])) {
						$old_button = $wp_filter['woocommerce_before_add_to_cart_button'];
					}
					if (isset($wp_filter['woocommerce_before_add_to_cart_form'])) {
						$old_before_form = $wp_filter['woocommerce_before_add_to_cart_form'];
					}

					if (isset($wp_filter['woocommerce_after_add_to_cart_form'])) {
						$old_after_form = $wp_filter['woocommerce_after_add_to_cart_form'];
					}

					if (isset($wp_filter['woocommerce_after_add_to_cart_button'])) {
						$old_after_button = $wp_filter['woocommerce_after_add_to_cart_button'];
					}

					add_filter('woocommerce_get_stock_html', '__return_empty_string');

					unset($wp_filter['woocommerce_before_add_to_cart_quantity']);
					unset($wp_filter['woocommerce_after_add_to_cart_quantity']);
					unset($wp_filter['woocommerce_before_add_to_cart_button']);
					unset($wp_filter['woocommerce_before_add_to_cart_form']);
					unset($wp_filter['woocommerce_after_add_to_cart_form']);
					unset($wp_filter['woocommerce_after_add_to_cart_button']);

					woocommerce_simple_add_to_cart();

					remove_filter('woocommerce_get_stock_html', '__return_empty_string');

					if (isset($old_before)) {
						$wp_filter['woocommerce_before_add_to_cart_quantity'] = $old_before;
					}

					if (isset($old)) {
						$wp_filter['woocommerce_after_add_to_cart_quantity'] = $old;
					}

					if (isset($old_button)) {
						$wp_filter['woocommerce_before_add_to_cart_button'] = $old_button;
					}

					if (isset($old_after_form)) {
						$wp_filter['woocommerce_after_add_to_cart_form'] = $old_after_form;
					}

					if (isset($old_before_form)) {
						$wp_filter['woocommerce_before_add_to_cart_form'] = $old_before_form;
					}

					if (isset($old_after_button)) {
						$wp_filter['woocommerce_after_add_to_cart_button'] = $old_after_button;
					}
				} else {
					$is_simple_product = blc_get_ext('woocommerce-extra')
						->utils
						->is_simple_product($product);

					add_filter('wsa_sample_should_add_button', '__return_false');

					if (isset($is_simple_product['fake_type'])) {
						$product_classname = WC()
							->product_factory
							->get_product_classname(
								$product->get_id(), 'variable'
							);

						try {
							$GLOBALS['product'] = new $product_classname($product->get_id());
						} catch (Exception $e) {
						}
					}

					woocommerce_template_loop_add_to_cart();

					remove_filter('wsa_sample_should_add_button', '__return_false');

					$GLOBALS['product'] = $product;
				}
				$blocksy_is_floating_cart = false;
			?>
		</section>
	</div>
</div>
