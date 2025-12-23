<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class SwatchesLoopVariableProduct {
	public function __construct() {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if (isset($_POST['blocksy_archive_add_to_cart'])) {
			add_action(
				'wc_ajax_add_to_cart',
				[$this, 'custom_add_to_cart'],
				5
			);
		}

		add_filter(
			'blocksy:woocommerce:product-card:thumbnail:descriptor',
			function ($descriptor) {
				global $product;

				if (
					! $product->is_type('variable')
					||
					! $this->is_swatches_enabled()
				) {
					return $descriptor;
				}

				$maybe_current_variation = null;

				if (blc_theme_functions()->blocksy_manager()) {
					$maybe_current_variation = blc_theme_functions()->blocksy_manager()
						->woocommerce
						->retrieve_product_default_variation($product);
				}

				if ($maybe_current_variation) {
					$descriptor['container_attr'][
						'data-current-variation'
					] = $maybe_current_variation->get_id();

					$descriptor['gallery_images'] = blocksy_product_get_gallery_images(
						$maybe_current_variation,
						[
							'enforce_first_image_replace' => true
						]
					);
				}

				return $descriptor;
			}
		);

		add_filter(
			'blocksy:woocommerce:product-card:price',
			function ($price) {
				global $product;

				if (
					! $product->is_type('variable')
					||
					! $this->is_swatches_enabled()
				) {
					return $price;
				}

				$maybe_current_variation = null;

				if (blc_theme_functions()->blocksy_manager()) {
					$maybe_current_variation = blc_theme_functions()->blocksy_manager()
						->woocommerce
						->retrieve_product_default_variation($product);
				}

				if ($maybe_current_variation) {
					return blocksy_html_tag(
						'span',
						['class' => 'price'],
						$maybe_current_variation->get_price_html()
					);
				}

				return $price;
			}
		);

		add_action(
			'blocksy:woocommerce:product-card:actions:before',
			function () {
				add_filter(
					'woocommerce_loop_add_to_cart_link',
					[$this, 'transform_loop_add_to_cart'],
					10, 3
				);
			}
		);

		add_action(
			'blocksy:woocommerce:product-card:actions:after',
			function () {
				remove_filter(
					'woocommerce_loop_add_to_cart_link',
					[$this, 'transform_loop_add_to_cart'],
					10, 3
				);
			}
		);
	}

	public function custom_add_to_cart() {
		ob_start();

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if (! isset($_POST['product_id'])) {
			return;
		}

		$product_id = apply_filters(
			'woocommerce_add_to_cart_product_id',
			// phpcs:ignore WordPress.Security.NonceVerification.Missing
			absint($_POST['product_id'])
		);

		$product = wc_get_product($product_id);

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$quantity = ! empty($_POST['quantity']) ? wc_stock_amount(absint(wp_unslash($_POST['quantity']))) : 1;

		$passed_validation = apply_filters(
			'woocommerce_add_to_cart_validation',
			true,
			$product_id,
			$quantity
		);
		$product_status = get_post_status($product_id);
		$variation_id = 0;
		$variation = array();

		if (
			$product
			&&
			'variation' === $product->get_type()
		) {
			$variation_id = $product_id;
			$product_id   = $product->get_parent_id();
			$variation    = $product->get_variation_attributes();

			foreach ($variation as $attr => $value) {
				if (
					$value === ''
					&&
					// phpcs:ignore WordPress.Security.NonceVerification.Missing
					isset($_POST[$attr])
				) {
					// phpcs:ignore WordPress.Security.NonceVerification.Missing
					$variation[$attr] = sanitize_text_field(wp_unslash($_POST[$attr]));
				}
			}
		}

		if (
			$passed_validation
			&&
			false !== WC()->cart->add_to_cart(
				$product_id, $quantity, $variation_id, $variation
			)
			&&
			'publish' === $product_status
		) {
			do_action('woocommerce_ajax_added_to_cart', $product_id);

			if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
				wc_add_to_cart_message(
					array($product_id => $quantity),
					true
				);
			}

			\WC_AJAX::get_refreshed_fragments();
		} else {
			// If there was an error adding to the cart,
			// redirect to the product page to show any errors.
			wp_send_json([
				'error' => true,
				'product_url' => apply_filters(
					'woocommerce_cart_redirect_after_error',
					get_permalink($product_id),
					$product_id
				)
			]);
		}
	}

	public function is_swatches_enabled() {
		if (
			! function_exists('blocksy_get_woo_archive_layout_defaults')
			||
			! function_exists('blocksy_normalize_layout')
		) {
			return false;
		}

		$default_product_layout = blocksy_get_woo_archive_layout_defaults();

		$render_layout_config = blc_theme_functions()->blocksy_get_theme_mod(
			'woo_card_layout',
			$default_product_layout
		);

		$render_layout_config = blocksy_normalize_layout(
			$render_layout_config,
			$default_product_layout
		);

		foreach ($render_layout_config as $layer) {
			if (! $layer['enabled']) {
				continue;
			}

			if ($layer['id'] === 'product_swatches') {
				return true;
			}
		}

		return false;
	}

	public function transform_loop_add_to_cart($link, $product, $args) {
		global $blocksy_is_floating_cart;

		if (
			$blocksy_is_floating_cart
			||
			! $product->is_type('variable')
			||
			! $this->is_swatches_enabled()
		) {
			return $link;
		}

		$maybe_current_variation = null;

		if (blc_theme_functions()->blocksy_manager()) {
			$maybe_current_variation = blc_theme_functions()->blocksy_manager()
				->woocommerce
				->retrieve_product_default_variation($product);
		}

		if (! $maybe_current_variation) {
			return $link;
		}

		$simple = new \WC_Product_Simple($product->get_id());

		$args = [
			'quantity'   => 1,
			'class' => implode(
				' ',
				array_filter(
					[
						'button',
						'product_type_' . $maybe_current_variation->get_type(),
						(
							$maybe_current_variation->is_purchasable()
							&&
							$product->is_in_stock()
						) ? 'add_to_cart_button' : '',
						(
							$simple->supports('ajax_add_to_cart')
							&&
							$maybe_current_variation->is_purchasable()
							&&
							$maybe_current_variation->is_in_stock()
						) ? 'ajax_add_to_cart' : '',
					]
				)
			),
			'attributes' => [
				'data-product_id' => $maybe_current_variation->get_id(),
				'data-product_sku' => $maybe_current_variation->get_sku(),
				'aria-label' => $maybe_current_variation->add_to_cart_description(),
				'rel' => 'nofollow'
			]
		];

		$args = apply_filters(
			'woocommerce_loop_add_to_cart_args',
			$args,
			$product
		);

		return blc_safe_sprintf(
			'<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
			esc_url($maybe_current_variation->add_to_cart_url()),
			esc_attr(isset($args['quantity']) ? $args['quantity'] : 1),
			esc_attr(isset($args['class']) ? $args['class'] : 'button'),
			isset($args['attributes']) ? wc_implode_html_attributes($args['attributes']) : '',
			esc_html($simple->add_to_cart_text())
		);
	}
}
