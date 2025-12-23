<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class PriceBlock {
	public function __construct() {
		add_action('init', [$this, 'blocksy_price_filter_block']);

		add_filter('blocksy:general:ct-scripts-localizations', function ($data) {
			$data['blocksy_woo_extra_price_filters'] = [
				'currency' => html_entity_decode(get_woocommerce_currency_symbol()),
				'priceFormat' => html_entity_decode(get_woocommerce_price_format()),
				'delimiter' => wc_get_price_decimal_separator(),
				'thousand' => wc_get_price_thousand_separator(),
			];

			return $data;
		});

		add_filter('blocksy:frontend:dynamic-js-chunks', function ($chunks) {
			if (!class_exists('WC_AJAX')) {
				return $chunks;
			}

			$chunks[] = [
				'id' => 'blocksy_ext_woo_extra_price_filters',
				'selector' => '.ct-price-filter',
				'trigger' => [
					[
						'trigger' => 'dom-event',
						'events' => [
							'change',
							'input',
						],
						'selector' => '.ct-price-filter-slider input[type="range"]',
					],

					

					[
						'trigger' => 'dom-event',
						'events' => [
							'blur',
							'focus',
							'keypress'
						],
						'selector' => '.ct-price-filter-inputs input[type="number"]',
					],

					[
						'trigger' => 'click',
						'selector' => '.ct-price-filter-range-track',
					]
				],
				'url' => blocksy_cdn_url(
					BLOCKSY_URL .
						'framework/premium/extensions/woocommerce-extra/static/bundle/price-filter-public.js'
				),
			];

			return $chunks;
		});
	}

	public function blocksy_price_filter_block() {
		register_block_type('blocksy/woocommerce-price-filter', [
			'render_callback' => function ($attributes, $content, $block) {
				if (
					! is_woocommerce()
					&&
					! wp_doing_ajax()
					||
					is_singular()
				) {
					return '';
				}

				$attributes = wp_parse_args($attributes, [
					'showTooltips' => true,
					'showPrices' => true,
					'showInputs' => false,
					'showResetButton' => false
				]);

				$filter = Filters::get_filter_instance('price_filter');

				$presenter = new FilterPresenter($filter);
				return $presenter->render($attributes);
			},
		]);
	}
}
