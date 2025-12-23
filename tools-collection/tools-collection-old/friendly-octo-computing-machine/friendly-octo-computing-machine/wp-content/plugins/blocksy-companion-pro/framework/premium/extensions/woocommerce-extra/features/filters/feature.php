<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class Filters {
	public $lookup_table = null;
	private static $filters = [];

	public function __construct() {
		$this->lookup_table = new FiltersTaxonomiesProductsLookupTable();

		$this->register_filter_type(new CommonWCFilter());
		$this->register_filter_type(new AttributesFilter());
		$this->register_filter_type(new PriceFilter());
		$this->register_filter_type(new TaxonomiesFilter());
		$this->register_filter_type(new StatusFilter());
		$this->register_filter_type(new RatingFilter());

		new ActiveFilters();

		new FiltersBlock();
		new PriceBlock();
		new StatusBlock();
		new RatingBlock();

		$apply_filters = new ApplyFilters();

		if (! is_admin()) {
			$apply_filters->mount_entry_point();
		}

		add_action(
			'wp_enqueue_scripts',
			function () {
				if (!function_exists('get_plugin_data')) {
					require_once ABSPATH . 'wp-admin/includes/plugin.php';
				}

				$data = get_plugin_data(BLOCKSY__FILE__);

				wp_register_style(
					'blocksy-ext-woocommerce-extra-filters-styles',
					BLOCKSY_URL .
						'framework/premium/extensions/woocommerce-extra/static/bundle/filters.min.css',
					['blocksy-ext-woocommerce-extra-styles'],
					$data['Version']
				);

				wp_register_style(
					'blocksy-ext-woocommerce-extra-ajax-filtering-styles',
					BLOCKSY_URL .
						'framework/premium/extensions/woocommerce-extra/static/bundle/ajax-filtering.min.css',
					['blocksy-ext-woocommerce-extra-styles'],
					$data['Version']
				);

				wp_register_style(
					'blocksy-ext-woocommerce-extra-active-filters-styles',
					BLOCKSY_URL .
						'framework/premium/extensions/woocommerce-extra/static/bundle/active-filters.min.css',
					['blocksy-ext-woocommerce-extra-styles'],
					$data['Version']
				);

				if (
					is_admin()
					||
					is_singular()
					||
					(
						function_exists('is_woocommerce')
						&&
						! is_woocommerce()
					)
				) {
					return;
				}

				if (is_customize_preview()) {
					wp_enqueue_style(
						'blocksy-ext-woocommerce-extra-filters-styles'
					);
				}

				if (blc_theme_functions()->blocksy_get_theme_mod('woo_filters_ajax', 'no') === 'yes') {
					wp_enqueue_style(
						'blocksy-ext-woocommerce-extra-ajax-filtering-styles'
					);
				}

				if (blc_theme_functions()->blocksy_get_theme_mod('filter_ajax_reveal', 'no') === 'yes') {
					wp_enqueue_style(
						'blocksy-ext-woocommerce-extra-filters-styles'
					);
				}

				if (blc_theme_functions()->blocksy_get_theme_mod('woo_has_active_filters', 'no') === 'yes') {
					wp_enqueue_style(
						'blocksy-ext-woocommerce-extra-active-filters-styles'
					);
				}

				add_filter(
					'render_block',
					function ($block_content, $block) {
						if (
							$block['blockName'] === 'blocksy/woocommerce-filters'
							||
							$block['blockName'] === 'blocksy/woocommerce-price-filter'
							||
							$block['blockName'] === 'blocksy/woocommerce-status-filter'
						) {
							wp_enqueue_style(
								'blocksy-ext-woocommerce-extra-filters-styles'
							);
						}

						if ($block['blockName'] === 'blocksy/active-filters') {
							wp_enqueue_style(
								'blocksy-ext-woocommerce-extra-active-filters-styles'
							);
						}

						return $block_content;
					},
					10,
					2
				);
			},
			50
		);

		add_filter(
			'blocksy:options:woocommerce:archive:ajax-filtering',
			function ($opts) {
				$opts[] = [
					'woo_filters_ajax' => [
						'label' => __('AJAX Filtering', 'blocksy-companion'),
						'type' => 'ct-panel',
						'switch' => true,
						'value' => 'no',
						'inner-options' => [
							'woo_filters_scroll_to_top' => [
								'label' => __( 'Scroll to Top', 'blocksy-companion' ),
								'desc' => __( 'Automatically scroll page to top after user interaction.', 'blocksy-companion' ),
								'type' => 'ct-switch',
								'value' => 'no',
							],
						]
					],
				];

				return $opts;
			},
			50
		);

		add_action('wp_ajax_blc_get_lookup_progress', function() {
			wp_send_json_success(blc_get_ext('woocommerce-extra')->get_filters()->lookup_table->get_regeneration_state());
		});

		add_action('wp_ajax_nopriv_blc_get_lookup_progress', function() {
			wp_send_json_success(blc_get_ext('woocommerce-extra')->get_filters()->lookup_table->get_regeneration_state());
		});

		add_filter('blocksy:general:body-attr', function ($attr) {
			if (blc_theme_functions()->blocksy_get_theme_mod('woo_filters_ajax', 'no') === 'yes') {
				$attr['data-ajax-filters'] = 'yes';

				if (blc_theme_functions()->blocksy_get_theme_mod('woo_filters_scroll_to_top', 'no') === 'yes') {
					$attr['data-ajax-filters'] = 'yes:scroll';
				}
			}

			return $attr;
		});

		add_filter('blocksy:frontend:dynamic-js-chunks', function ($chunks) {
			if (!class_exists('WC_AJAX')) {
				return $chunks;
			}

			if (
				blc_theme_functions()->blocksy_get_theme_mod('woo_filters_ajax', 'no') === 'yes'
				&&
				(
					is_shop()
					||
					is_product_category()
					||
					is_product_tag()
					||
					is_product_taxonomy()
				)
			) {
				$chunks[] = [
					'id' => 'blocksy_ext_woo_extra_ajax_filters',
					'selector' => '[data-ajax-filters*="yes"]',
					'trigger' => [
						[
							'trigger' => 'click',
							'selector' => implode(', ', [
								'[data-ajax-filters*="yes"] .ct-filter-widget a',
								'[data-ajax-filters*="yes"] .ct-active-filters a',
								'[data-ajax-filters*="yes"] .ct-products-container a.page-numbers',
								'[data-ajax-filters*="yes"] .ct-filter-reset a'
							])
						],

						[
							'trigger' => 'submit',
							'selector' => '[data-ajax-filters*="yes"] .woocommerce-ordering'
						],

						[
							'trigger' => 'dom-event',
							'events' => [
								'change'
							],
							'selector' => '[data-ajax-filters*="yes"] .woocommerce-ordering select',
						],

						[
							'trigger' => 'dom-event',
							'events' => [
								'change'
							],
							'selector' => '[data-ajax-filters*="yes"] .ct-filter-item [type="checkbox"]',
						],

						[
							'trigger' => 'window-event',
							'eventName' => 'popstate',
							'selector' => '[data-ajax-filters*="yes"]',
						]
					],
					'url' => blocksy_cdn_url(
						BLOCKSY_URL .
							'framework/premium/extensions/woocommerce-extra/static/bundle/ajax-filter-public.js'
					),
				];
			} else {
				$chunks[] = [
					'id' => 'blocksy_ext_woo_extra_ajax_filters',
					'selector' => 'body:not([data-ajax-filters*="yes"])',
					'trigger' => [
						[
							'trigger' => 'dom-event',
							'events' => [
								'change'
							],
							'selector' => '.ct-filter-item [type="checkbox"]',
						],

						[
							'trigger' => 'click',
							'selector' => '.ct-filter-item a',
						],
					],
					'url' => blocksy_cdn_url(
						BLOCKSY_URL .
							'framework/premium/extensions/woocommerce-extra/static/bundle/ajax-filter-public.js'
					),
				];
			}

			$chunks[] = [
				'id' => 'blocksy_ext_woo_extra_filters_search',
				'selector' => '.ct-filter-widget-wrapper input[type="search"]',
				'trigger' => [
					'trigger' => 'dom-event',
					'events' => [
						'input',
					],
					'selector' => '.ct-filter-widget-wrapper input[type="search"]',
				],
				'url' => blocksy_cdn_url(
					BLOCKSY_URL .
						'framework/premium/extensions/woocommerce-extra/static/bundle/filter-search.js'
				),
			];

			$chunks[] = [
				'id' => 'blocksy_ext_woo_extra_filters_lookup_progress',
				'selector' => '.ct-filter-widget-admin-notice',
				'trigger' => 'slight-mousemove',
				'url' => blocksy_cdn_url(
					BLOCKSY_URL .
						'framework/premium/extensions/woocommerce-extra/static/bundle/filter-lookup-progress.js'
				),
			];

			return $chunks;
		});

		add_action(
			'woocommerce_before_shop_loop',
			[$this, 'products_loop_container_start'],
			100
		);

		add_action(
			'woocommerce_after_shop_loop',
			[$this, 'products_loop_container_end'],
			50
		);

		add_action(
			'woocommerce_no_products_found',
			[$this, 'products_loop_container_start'],
			9
		);

		add_action(
			'woocommerce_no_products_found',
			[$this, 'products_loop_container_end'],
			20
		);
	}

	private function register_filter_type($filter) {
		self::$filters[$filter->get_filter_id()] = $filter;
	}

	public static function get_filter_instance($filter_id = null) {
		if ($filter_id !== null) {
			return self::$filters[$filter_id];
		}

		return self::$filters;
	}

	public function products_loop_container_start() {
		if (
			blc_theme_functions()->blocksy_get_theme_mod('woo_filters_ajax', 'no') !== 'yes'
			||
			(
				! is_shop()
				&&
				! is_product_category()
				&&
				! is_product_tag()
				&&
				! is_product_taxonomy()
			)
		) {
			return;
		}

		echo '<div class="ct-products-container">';

		blocksy_html_tag_e(
			'span',
			[
				'class' => 'ct-filters-loading',
			],
			'<svg width="23" height="23" viewBox="0 0 40 40">
			<path opacity=".2" fill="currentColor" d="M20.201 5.169c-8.254 0-14.946 6.692-14.946 14.946 0 8.255 6.692 14.946 14.946 14.946s14.946-6.691 14.946-14.946c-.001-8.254-6.692-14.946-14.946-14.946zm0 26.58c-6.425 0-11.634-5.208-11.634-11.634 0-6.425 5.209-11.634 11.634-11.634 6.425 0 11.633 5.209 11.633 11.634 0 6.426-5.208 11.634-11.633 11.634z"/>

			<path fill="currentColor" d="m26.013 10.047 1.654-2.866a14.855 14.855 0 0 0-7.466-2.012v3.312c2.119 0 4.1.576 5.812 1.566z">
			<animateTransform attributeName="transform" type="rotate" from="0 20 20" to="360 20 20" dur="0.5s" repeatCount="indefinite"/>
			</path>
			</svg>'
		);
	}

	public function products_loop_container_end() {
		if (
			blc_theme_functions()->blocksy_get_theme_mod('woo_filters_ajax', 'no') !== 'yes'
			||
			(
				! is_shop()
				&&
				! is_product_category()
				&&
				! is_product_tag()
				&&
				! is_product_taxonomy()
			)
		) {
			return;
		}

		echo '</div>';
	}
}
