<?php

namespace Blocksy\Extensions\WoocommerceExtra;

// require_once dirname(__FILE__) . '/helpers.php';

class AffiliateProduct {
	private $wish_list_slug = null;

	public function __construct() {
		add_filter(
			'blocksy_customizer_options:woocommerce:general:end',
			function ($opts) {
				$opts['has_product_affiliates_panel'] = blocksy_get_options(
					dirname(__FILE__) . '/options.php',
					[],
					false
				);

				return $opts;
			},
			50
		);

		add_filter('render_block_core/post-title', [$this, 'add_product_title_click_event_directives'], 10, 3);
		add_filter('render_block_woocommerce/product-image', [$this, 'add_product_image_click_event_directives'], 0, 3);

		add_filter('blocksy:woocommerce:product-card:title:link', function($args) {
			global $product;

			if (
				blc_theme_functions()->blocksy_get_theme_mod('woo_archive_affiliate_title_link', 'no') === 'no'
				||
				! $product->is_type('external')
			) {
				return $args;
			}

			$open_in_new_tab = blc_theme_functions()->blocksy_get_theme_mod(
				'woo_archive_affiliate_title_link_new_tab',
				'no'
			) === 'yes' ? '_blank' : '_self';

			return [
				'href' => $product->get_product_url(),
				'target' => $open_in_new_tab
			];
		});

		add_filter('woocommerce_loop_add_to_cart_args', function($args) {
			$product = $this->get_product($args['attributes']);

			if (! $product) {
				return $args;
			}

			$open_in_new_tab = blc_theme_functions()->blocksy_get_theme_mod(
				'woo_archive_affiliate_button_link_new_tab',
				'no'
			) === 'yes' ? '_blank' : '_self';

			$args['attributes']['target'] = $open_in_new_tab;

			return $args;
		});

		add_action('woocommerce_external_add_to_cart', function() {
			global $product;

			if (! $product->is_type('external')) {
				return;
			}

			remove_action(
				'woocommerce_external_add_to_cart',
				'woocommerce_external_add_to_cart',
				30
			);

			$open_in_new_tab = blc_theme_functions()->blocksy_get_theme_mod(
				'woo_single_affiliate_button_link_new_tab',
				'no'
			) === 'yes' ? '_blank' : '_self';

			blocksy_html_tag_e(
				'div',
				[
					'class' => 'cart'
				],
				blocksy_html_tag(
					'div',
					[
						'class' => 'ct-cart-actions'
					],
					blocksy_html_tag(
						'a',
						[
							'href' => $product->get_product_url(),
							'class' => 'single_add_to_cart_button button alt wp-element-button',
							'target' => $open_in_new_tab
						],
						$product->single_add_to_cart_text()
					)
				)
			);
		});

		add_filter('blocksy:woocommerce:image_additional_attributes', function($attributes) {
			global $product;

			if (! $product) {
				return $attributes;
			}

			if (
				blc_theme_functions()->blocksy_get_theme_mod('woo_single_affiliate_image_link', 'no') === 'yes'
				&&
				$product->is_type('external')
			) {
				$open_in_new_tab = blc_theme_functions()->blocksy_get_theme_mod(
					'woo_single_affiliate_image_link_new_tab',
					'no'
				) === 'yes' ? '_blank' : '_self';

				$attributes['tag_name'] = 'a';
				$attributes['html_atts'] = array_merge(
					$attributes['html_atts'],
					[
						'target' => $open_in_new_tab,
						'href' => $product->get_product_url()
					]
				);
			}

			return $attributes;
		});
	}

	public function add_product_image_click_event_directives($block_content, $block, $instance) {
		$product = wc_get_product($instance->context['postId']);

		if (
			! $product
			||
			! $product->is_type('external')
		) {
			return $block_content;
		}

		$is_link = $instance->attributes['showProductLink'] ?? false;
		$has_affiliate_link = blc_theme_functions()->blocksy_get_theme_mod('woo_archive_affiliate_image_link', 'no') === 'yes';

		if (
			! $is_link
			||
			! $has_affiliate_link
		) {
			return $block_content;
		}

		$open_in_new_tab = blc_theme_functions()->blocksy_get_theme_mod(
			'woo_archive_affiliate_image_link_new_tab',
			'no'
		) === 'yes' ? '_blank' : '_self';

		$p = new \WP_HTML_Tag_Processor($block_content);
		$is_anchor = $p->next_tag(['tag_name' => 'a']);

		if ($is_anchor) {
			$p->set_attribute('href', $product->get_product_url());
			$p->set_attribute('target', $open_in_new_tab);
		}

		$block_content = $p->get_updated_html();

		return $block_content;
	}

	public function add_product_title_click_event_directives($block_content, $block, $instance) {
		$product = wc_get_product($instance->context['postId']);

		if (
			! $product
			||
			! $product->is_type('external')
		) {
			return $block_content;
		}

		$namespace = $instance->attributes['__woocommerceNamespace'] ?? '';
		$is_product_title_block = 'woocommerce/product-query/product-title' === $namespace;
		$is_link = $instance->attributes['isLink'] ?? false;

		if (! $is_link) {
			return $block_content;
		}

		if ($is_product_title_block) {
			$p = new \WP_HTML_Tag_Processor($block_content);
			$p->next_tag(['class_name' => 'wp-block-post-title']);
			$is_anchor = $p->next_tag(['tag_name' => 'a']);

			if ($is_anchor) {
				$p->set_attribute('href', $product->get_product_url());
				$block_content = $p->get_updated_html();
			}
		}

		return $block_content;
	}

	public function get_product($attributes = []) {
		global $product;

		if (! $product) {
			if (! isset($attributes['data-product_id'])) {
				return false;
			}

			$product = wc_get_product($attributes['data-product_id']);
		}

		if (! $product->is_type('external')) {
			return false;
		}

		return $product;
	}
}

