<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class RelatedSlideshow {
	public function get_dynamic_styles_data($args) {
		return [
			'path' => dirname(__FILE__) . '/dynamic-styles.php'
		];
	}

	public function __construct() {
		add_filter(
			'blocksy_customizer_options:woocommerce:related:slider_general',
			function ($opts) {
				$opts[] = blocksy_get_options(
					dirname(__FILE__) . '/general-options.php',
					[],
					false
				);

				return $opts;
			}
		);

		add_filter(
			'blocksy_customizer_options:woocommerce:related:slider_design',
			function ($opts) {
				$opts[] = blocksy_get_options(
					dirname(__FILE__) . '/design-options.php',
					[],
					false
				);

				return $opts;
			}
		);

		add_filter(
			'woocommerce_product_loop_start',
			function ($content) {

				if (blc_theme_functions()->blocksy_get_theme_mod('woocommerce_related_products_slideshow', 'default') !== 'slider') {
					return $content;
				}

				$classes = ['products'];
				$is_related = wc_get_loop_prop('name', 'default') === 'related' || wc_get_loop_prop('name', 'default') === 'up-sells';

				if ($is_related) {
					$classes[] = 'flexy-items';
				}

				$content = str_replace(
					'class="products',
					'class="' . implode(' ', $classes),
					$content
				);

				$attr = [
					'class' => 'flexy-container',
					'data-flexy' => 'no',
				];

				if (blc_theme_functions()->blocksy_get_theme_mod('woocommerce_related_products_slideshow_autoplay', 'no') === 'yes') {
					$attr['data-autoplay'] = blc_theme_functions()->blocksy_get_theme_mod('woocommerce_related_products_slideshow_autoplay_speed', 3);
				}

				if ($is_related) {
					$content = str_replace(
						'<ul',
						'<div ' . blocksy_attr_to_html($attr) . '>
							<div class="flexy">
								<div class="flexy-view" data-flexy-view="boxed">
									<div',
						$content
					);
				}

				return $content;
			},
			999
		);

		add_filter(
			'woocommerce_product_loop_end',
			function ($content) {
				if (blc_theme_functions()->blocksy_get_theme_mod('woocommerce_related_products_slideshow', 'default') !== 'slider') {
					return $content;
				}

				$is_related = wc_get_loop_prop('name', 'default') === 'related' || wc_get_loop_prop('name', 'default') === 'up-sells';

				if ($is_related) {
					$arrow_icons = apply_filters(
						'blocksy:flexy:arrows',
						[
							'prev' => '<svg width="16" height="10" fill="currentColor" viewBox="0 0 16 10"><path d="M15.3 4.3h-13l2.8-3c.3-.3.3-.7 0-1-.3-.3-.6-.3-.9 0l-4 4.2-.2.2v.6c0 .1.1.2.2.2l4 4.2c.3.4.6.4.9 0 .3-.3.3-.7 0-1l-2.8-3h13c.2 0 .4-.1.5-.2s.2-.3.2-.5-.1-.4-.2-.5c-.1-.1-.3-.2-.5-.2z"></path></svg>',
							'next' => '<svg width="16" height="10" fill="currentColor" viewBox="0 0 16 10"><path d="M.2 4.5c-.1.1-.2.3-.2.5s.1.4.2.5c.1.1.3.2.5.2h13l-2.8 3c-.3.3-.3.7 0 1 .3.3.6.3.9 0l4-4.2.2-.2V5v-.3c0-.1-.1-.2-.2-.2l-4-4.2c-.3-.4-.6-.4-.9 0-.3.3-.3.7 0 1l2.8 3H.7c-.2 0-.4.1-.5.2z"></path></svg>'
						]
					);

					$content = str_replace(
						'</ul>',
						'</div></div>
							<span class="' . trim('flexy-arrow-prev' . ' ' . '') . '">' . $arrow_icons['prev'] . '</span>
							<span class="' . trim('flexy-arrow-next' . ' ' . '') . '">' . $arrow_icons['next'] . '</span>
						</div></div>',
						$content
					);
				}

				return $content;
			},
			999
		);

		add_action(
			'woocommerce_before_template_part',
			function ($template_name, $template_path, $located, $args) {
				if (
					$template_name !== 'single-product/related.php'
					&&
					$template_name !== 'single-product/up-sells.php'
				) {
					return;
				}

				add_action(
					'blocksy:woocommerce:product-card:before',
					[$this, 'blc_get_product_loop_start'],
					1,
					1
				);

				add_action(
					'blocksy:woocommerce:product-card:after',
					[$this, 'blc_get_product_loop_end'],
					100,
					1
				);
			},
			1,
			4
		);

		add_action(
			'woocommerce_after_template_part',
			function ($template_name, $template_path, $located, $args) {
				if (
					$template_name !== 'single-product/related.php'
					&&
					$template_name !== 'single-product/up-sells.php'
				) {
					return;
				}

				remove_action(
					'blocksy:woocommerce:product-card:before',
					[$this, 'blc_get_product_loop_start'],
					1,
					1
				);

				remove_Action(
					'blocksy:woocommerce:product-card:after',
					[$this, 'blc_get_product_loop_end'],
					100,
					1
				);
			},
			1,
			4
		);

		add_filter('woocommerce_output_related_products_args', [$this, 'blc_woocommerce_upsell_display_args'], 999);
		add_filter('woocommerce_upsell_display_args', [$this, 'blc_woocommerce_upsell_display_args'], 999);

		add_action('wp', function() {
			if (! $this->blc_is_related_slideshow()) {
				remove_action('woocommerce_output_related_products_args', [$this, 'blc_woocommerce_upsell_display_args'], 999);
				remove_action('woocommerce_upsell_display_args', [$this, 'blc_woocommerce_upsell_display_args'], 999);
			}
		});
	}

	public function blc_is_related_slideshow() {
		return blc_theme_functions()->blocksy_get_theme_mod('woocommerce_related_products_slideshow', 'default') === 'slider';
	}

	public function blc_woocommerce_upsell_display_args($args) {
		if ($this->blc_is_related_slideshow()) {
			return array_merge(
				$args,
				[
					'posts_per_page' => blc_theme_functions()->blocksy_get_theme_mod('woocommerce_related_products_slideshow_number_of_items', 6)
				]
			);
		}
	}

	public function blc_get_product_loop_start($template_name) {
		if (!$this->blc_is_related_slideshow()) {
			return;
		}

		ob_start();
	}

	public function blc_get_product_loop_end($template_name) {
		if (!$this->blc_is_related_slideshow()) {
			return;
		}

		$result = ob_get_clean();

		$result = preg_replace('/^<li/', '<div', trim($result));
		$result = preg_replace('/<\/li>$/', '</div>', trim($result));

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<div class="flexy-item">' . $result . '</div>';
	}
}
