<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class ArchiveCard {
	public function __construct() {
		add_filter(
			'blocksy:options:woocommerce:archive:card-type:choices',
			function ($choises) {
				return array_merge($choises, [
					'type-3' => [
						'src' => blocksy_image_picker_url('woo-type-3.svg'),
						'title' => __('Type 3', 'blocksy-companion'),
					],
				]);
			}
		);

		add_filter(
			'blocksy:options:conditions:overrides',
			function ($overrides) {
				unset($overrides['shop_cards_type']);
				return $overrides;
			}
		);

		add_filter(
			'woocommerce_get_script_data',
			function ($params, $handle) {
				$shop_cards_type = blc_theme_functions()->blocksy_get_theme_mod(
					'shop_cards_type',
					'type-1'
				);

				if ($shop_cards_type !== 'type-3') {
					return $params;
				}

				if ($handle === 'wc-add-to-cart') {
					$params['i18n_view_cart_with_icon'] =
						'<svg class="ct-icon" aria-hidden="true" width="15" height="15" viewBox="0 0 15 15"><path d="M11.2 3.5V1.8c0-1-.8-1.8-1.8-1.8h-4c-1 0-1.8.8-1.8 1.8v1.8H0v9.8c0 1 .8 1.8 1.8 1.8h11.5c1 0 1.8-.8 1.8-1.8V3.5h-3.9zm-6-1.7c0-.1.1-.2.2-.2h4c.1 0 .2.1.2.2v1.8H5.2V1.8zm5.1 6.4-2.8 3c-.3.3-.7.3-1 0L4.8 9.8c-.4-.3-.4-.8-.1-1.1s.7-.3 1.1-.1l1.1 1 2.3-2.5c.3-.3.8-.3 1.1 0 .3.3.3.8 0 1.1z"/></svg><span class="ct-tooltip">' .
						$params['i18n_view_cart'] .
						'</span>';
				}

				return $params;
			},
			10,
			2
		);

		add_filter('blocksy_woo_card_options:additional_options', function ($opts) {
			$opts[] = [
				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [ 'shop_cards_type' => 'type-3' ],
					'options' => [
						'has_archive_add_to_cart' => [
							'label' => __('Add to cart', 'blocksy-companion'),
							'type' => 'ct-switch',
							'value' => 'yes',
							'sync' => blocksy_sync_whole_page([
								'loader_selector' => '[data-products]'
							]),
						]
					]
				]
			];

			return $opts;
		}, 0);

		add_filter(
			'blocksy:options:woocommerce:archive:card-type:output_product_toolbar',
			function ($components) {
				$shop_cards_type = blc_theme_functions()->blocksy_get_theme_mod('shop_cards_type', 'type-1');

				if (
					$shop_cards_type !== 'type-3'
					||
					blc_theme_functions()->blocksy_get_theme_mod('has_archive_add_to_cart', 'yes') === 'no'
				) {
					return $components;
				}

				add_filter(
					'woocommerce_loop_add_to_cart_link',
					[$this, 'transform_add_to_cart_link'],
					50
				);

				ob_start();
				woocommerce_template_loop_add_to_cart();
				$add_to_cart = ob_get_clean();

				remove_filter(
					'woocommerce_loop_add_to_cart_link',
					[$this, 'transform_add_to_cart_link'],
					50
				);

				if (!empty($add_to_cart)) {
					$components[] = $add_to_cart;
				}

				return $components;
			}
		);
	}

	public function transform_add_to_cart_link($link) {
		global $blocksy_is_floating_cart;

		if ($blocksy_is_floating_cart) {
			return $link;
		}

		$shop_cards_type = blc_theme_functions()->blocksy_get_theme_mod('shop_cards_type', 'type-1');

		if ($shop_cards_type !== 'type-3') {
			return $link;
		}

		$contents =
			'<a $1><svg class="ct-icon" aria-hidden="true" width="15" height="15" viewBox="0 0 15 15"><path d="M11.2,3.5V1.8c0-1-0.8-1.8-1.8-1.8h-4c-1,0-1.8,0.8-1.8,1.8v1.8H0v9.8c0,1,0.8,1.8,1.8,1.8h11.5c1,0,1.8-0.8,1.8-1.8V3.5H11.2zM5.2,1.8c0-0.1,0.1-0.2,0.2-0.2h4c0.1,0,0.2,0.1,0.2,0.2v1.8H5.2V1.8z M13.5,13.2c0,0.1-0.1,0.2-0.2,0.2H1.8c-0.1,0-0.2-0.1-0.2-0.2V5h12V13.2zM5.5,8c0.4,0,0.8-0.3,0.8-0.8S5.9,6.5,5.5,6.5S4.8,6.8,4.8,7.2C4.8,7.7,5.1,8,5.5,8zM9.5,8c0.4,0,0.8-0.3,0.8-0.8S9.9,6.5,9.5,6.5S8.8,6.8,8.8,7.2C8.8,7.7,9.1,8,9.5,8z"></path></svg><span class="ct-tooltip">$2</span></a>';

		$link = preg_replace(
			'/<a\s(.+?)>(.+?)<\/a>/is',
			$contents,
			$link
		);

		return $link;

	}
}
