<?php

namespace Blocksy\Extensions\WoocommerceExtra;

use \Automattic\WooCommerce\Internal\ProductAttributesLookup\Filterer;
use \Automattic\WooCommerce\Internal\ProductAttributesLookup\DataRegenerator;

class PriceFilter extends BaseFilter {
	public function get_filter_id() {
		return 'price_filter';
	}

	public function get_filtered_price() {
		$request = $this->get_filtered_request();

		if (! $request) {
			return null;
		}

		$wc_filters = new \Automattic\WooCommerce\StoreApi\Utilities\ProductQueryFilters();

		$prices = $wc_filters->get_filtered_price($request);

		return [
			'min' => floor($prices->min_price),
			'max' => ceil($prices->max_price)
		];
	}

	public function get_reset_url($attributes = []) {
		$prices = $this->get_filtered_price();

		if (! $prices) {
			return false;
		}

		if (
			(
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				isset($_GET['min_price'])
				&&
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$_GET['min_price'] !== $prices['min']
			) ||
			(
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				isset($_GET['max_price'])
				&&
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$_GET['max_price'] !== $prices['max']
			)
		) {
			return remove_query_arg(self::get_query_params());
		}
	 
		return false;
	}

	public function render($attributes = []) {
		$prices = $this->get_filtered_price();

		if (! $prices) {
			return '';
		}

		$max_range = $prices['max'] - $prices['min'];

		if (intval($max_range) === 0) {
			return '';
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$min_price = max(blocksy_akg('min_price', $_GET, $prices['min']), $prices['min']);
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$max_price = min(blocksy_akg('max_price', $_GET, $prices['max']), $prices['max']);

		$leftStylePos = max(0, (($min_price - $prices['min']) / $max_range) * 100);
		$rightStylePos = min(100, (($max_price - $prices['min']) / $max_range) * 100);

		$currency = get_woocommerce_currency_symbol();
		$price_format = get_woocommerce_price_format();
		$currency_pos = get_option('woocommerce_currency_pos');

		$thousand_separator = wc_get_price_thousand_separator();

		$min_price = number_format($min_price, 0, wc_get_price_decimal_separator(), $thousand_separator);
		$max_price = number_format($max_price, 0, wc_get_price_decimal_separator(), $thousand_separator);

		$min_price_html = blocksy_html_tag(
			'span',
			['class' => 'ct-price-filter-min'],
			blocksy_safe_sprintf($price_format, $currency, $min_price)
		);

		$max_price_html = blocksy_html_tag(
			'span',
			['class' => 'ct-price-filter-max'],
			blocksy_safe_sprintf($price_format, $currency, $max_price)
		);

		$min_price_input_html = blocksy_html_tag(
			'div',
			[
				'class' => 'ct-price-filter-input-min',				
			],
			__('Minimum:', 'blocksy-companion') .
			blocksy_html_tag(
				'div',
				[
					'class' => 'ct-price-filter-input ct-pseudo-input'
				],
				blocksy_html_tag(
					'small',
					[],
					$currency
				) .
				blocksy_html_tag(
					'input',
					[
						'type' => 'number',
						'value' =>  isset($_GET['min_price']) ? sanitize_text_field(wp_unslash($_GET['min_price'])) : $prices['min'],
						'min' => $prices['min'],
						'max' => $prices['max'],
						'step' => 1,
						'name' => 'min_price',
					],
					''
				),
			),
		);

		$max_price_input_html = blocksy_html_tag(
			'div',
			[
				'class' => 'ct-price-filter-input-max',				
			],
			__('Maximum:', 'blocksy-companion') .
			blocksy_html_tag(
				'div',
				[
					'class' => 'ct-price-filter-input ct-pseudo-input'
				],
				blocksy_html_tag(
					'small',
					[],
					$currency
				) .
				blocksy_html_tag(
					'input',
					[
						'type' => 'number',
						'value' => isset($_GET['max_price']) ? sanitize_text_field(wp_unslash($_GET['max_price'])) : $prices['max'],
						'min' => $prices['min'],
						'max' => $prices['max'],
						'step' => 1,
						'name' => 'max_price',
					],
					''
				),
			),
		);

		return blocksy_html_tag(
			'div',
			[
				'class' => 'ct-price-filter',
			],
			blocksy_html_tag(
				'div',
				[
					'class' => 'ct-price-filter-slider'
				],
				blocksy_html_tag(
					'div',
					[
						'class' => 'ct-price-filter-range-track',
						'style' => '--start: ' . $leftStylePos . '%; --end: ' . ($rightStylePos) . '%;'
					],
					''
				) .
				blocksy_html_tag(
					'input',
					[
						'type' => 'range',
						// phpcs:ignore WordPress.Security.NonceVerification.Recommended
						'value' => isset($_GET['min_price']) ? sanitize_text_field(wp_unslash($_GET['min_price'])) : $prices['min'],
						'min' => $prices['min'],
						'max' => $prices['max'],
						'step' => 1,
						'name' => 'min_price',
					],
					''
				) .
				blocksy_html_tag(
					'span',
					[
						'class' => 'ct-price-filter-range-handle-min',
						'style' => 'inset-inline-start: ' . $leftStylePos . '%',
					],
					(
						$attributes['showTooltips'] ? blocksy_html_tag(
							'span',
							[
								'class' => 'ct-tooltip'
							],
							blocksy_safe_sprintf($price_format, $currency, $min_price)
						) : ''
					)
				) .
				blocksy_html_tag(
					'input',
					[
						'type' => 'range',
						// phpcs:ignore WordPress.Security.NonceVerification.Recommended
						'value' => isset($_GET['max_price']) ? sanitize_text_field(wp_unslash($_GET['max_price'])) : $prices['max'],
						'min' => $prices['min'],
						'max' => $prices['max'],
						'step' => 1,
						'name' => 'max_price',
					],
					''
				) .
				blocksy_html_tag(
					'span',
					[
						'class' => 'ct-price-filter-range-handle-max',
						'style' => 'inset-inline-start: ' . $rightStylePos . '%',
					],
					(
						$attributes['showTooltips'] ? blocksy_html_tag(
							'span',
							[
								'class' => 'ct-tooltip'
							],
							blocksy_safe_sprintf($price_format, $currency, $max_price)
						) : ''
					)
				)
			).
			(
				$attributes['showPrices'] && ! $attributes['showInputs'] ? blocksy_html_tag(
					'div',
					[
						'class' => 'ct-price-filter-values',
					],
					blocksy_html_tag(
						'span',
						[],
						__('Price', 'blocksy-companion') . ':&nbsp;'
					) .
					$min_price_html .
					blocksy_html_tag(
						'span',
						[],
						'&nbsp;-&nbsp;'
					) .
					$max_price_html
				) : ''
			) . 
			(
				$attributes['showInputs'] ? blocksy_html_tag(
					'div',
					[
						'class' => 'ct-price-filter-inputs',
						'data-currency-position' => $currency_pos,
					],
					$min_price_input_html . $max_price_input_html 
				) : ''
			)
		);
	}

	public static function get_query_params() {
		return ['min_price', 'max_price'];
	}

	public function get_applied_filters() {
		$prices = $this->get_filtered_price();

		if (
			! $prices
			||
			! $this->get_reset_url()
		) {
			return [];
		}

		return [
			'name' => __('Price', 'blocksy-companion'),
			'items' => [
				[
					'name' => blocksy_safe_sprintf(
						'%s - %s',
						wc_price(
							// phpcs:ignore WordPress.Security.NonceVerification.Recommended
							max(blocksy_akg('min_price', $_GET, $prices['min']), $prices['min']),
							[
								'decimals' => 0
							]
						),
						wc_price(
							// phpcs:ignore WordPress.Security.NonceVerification.Recommended
							min(blocksy_akg('max_price', $_GET, $prices['max']), $prices['max']),
							[
								'decimals' => 0
							]
						)
					),
					'value' => '',
					'href' => $this->get_reset_url()
				]
			]
		];
	}

	public function get_filtered_request() {
		$apply_filters = new ApplyFilters();

		$params = FiltersUtils::get_query_params();
		$filter_params = $this->get_query_params();

		$params = $params['params'];

		foreach ($filter_params as $param) {
			unset($params[$param]);
		}

		$products_query = $apply_filters->get_custom_query_for($params);

		$products = $products_query->posts;

		if (empty($products)) {
			return null;
		}

		$wc_filters = new \Automattic\WooCommerce\StoreApi\Utilities\ProductQueryFilters();

		$request = new \WP_REST_Request('GET', '/wp/v2/posts');

		$request->set_param('include', $products);

		return $request;
	}
}

