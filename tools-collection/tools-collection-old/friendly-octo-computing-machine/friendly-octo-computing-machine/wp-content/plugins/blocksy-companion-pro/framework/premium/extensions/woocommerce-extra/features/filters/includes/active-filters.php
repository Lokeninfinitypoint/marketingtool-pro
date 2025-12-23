<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class ActiveFilters {
	public function __construct() {
		add_filter(
			'blocksy:options:woocommerce:archive:active-filters',
			function ($opts) {
				$opts[] = [
					'woo_has_active_filters' => [
						'label' => __('Active Filters', 'blocksy-companion'),
						'type' => 'ct-panel',
						'switch' => true,
						'value' => 'no',
						'sync' => blocksy_sync_whole_page([
							'prefix' => 'woo_categories',
							'loader_selector' => '.ct-container > section'
						]),
						'inner-options' => [
							'woo_has_active_filters_label' => [
								'label' => __( 'Active Filters Label', 'blocksy-companion' ),
								'type' => 'ct-switch',
								'value' => 'yes',
								'divider' => 'top',
							],

							blocksy_rand_md5() => [
								'type' => 'ct-condition',
								'condition' => [ 'woo_has_active_filters_label' => 'yes' ],
								'options' => [

									'woo_active_filters_label' => [
										'label' => false,
										'type' => 'text',
										'design' => 'block',
										'value' => __('Active Filters', 'blocksy-companion'),
										'disableRevertButton' => true,
										'sync' => 'live',
									],

								],
							],

						],
					]
				];

				return $opts;
			},
			50
		);

		add_action(
			'woocommerce_before_shop_loop',
			function () {
				if (blc_theme_functions()->blocksy_get_theme_mod('woo_has_active_filters', 'no') === 'no') {
					return;
				}

				add_action(
					'woocommerce_before_shop_loop',
					[$this, 'active_filters'],
					105
				);
			},
			0
		);

		add_action(
			'woocommerce_no_products_found',
			function () {
				if (blc_theme_functions()->blocksy_get_theme_mod('woo_has_active_filters', 'no') === 'no') {
					return;
				}

				add_action(
					'woocommerce_no_products_found',
					[$this, 'active_filters'],
					9
				);
			},
			0
		);

		add_action('init', [$this, 'blocksy_active_filters_block']);
		add_action('enqueue_block_editor_assets', [$this, 'enqueue_admin']);
	}

	public function blocksy_active_filters_block() {
		register_block_type('blocksy/active-filters', [
			'render_callback' => function ($attributes, $content, $block) {
				$attributes = wp_parse_args($attributes, [
					'layout' => 'list',
					'showResetButton' => 'yes',
					'has_label' => false
				]);

				ob_start();
				$this->active_filters($attributes);
				$filters = ob_get_clean();

				if (empty($filters)) {
					return '';
				}

				return $filters;
			},
		]);
	}

	public function enqueue_admin() {
		$data = get_plugin_data(BLOCKSY__FILE__);

		wp_enqueue_script(
			'blocksy/active-filters',
			BLOCKSY_URL .
				'framework/premium/extensions/woocommerce-extra/static/bundle/active-filters.js',
			['wp-blocks', 'wp-element', 'wp-block-editor'],
			$data['Version'],
			true
		);
	}

	public static function get_reset_url() {
		$params = FiltersUtils::get_query_params();

		$url = $params['url'];
		$params = $params['params'];

		$all_filters_params = [];

		foreach (Filters::get_filter_instance() as $filter) {
			$filter_params = $filter->get_query_params();

			if (empty($filter_params)) {
				continue;
			}

			$all_filters_params = array_merge(
				$all_filters_params,
				$filter_params
			);
		}

		foreach ($all_filters_params as $param) {
			unset($params[$param]);
		}

		$url = remove_query_arg(
			$all_filters_params,
			$url
		);

		return $url;
	}

	public function active_filters($attributes = []) {
		if ($attributes === '') {
			$attributes = [
				'layout' => 'inline',
				'showResetButton' => 'yes',
				'has_label' => true,
			];
		}

		$applied_filters = $this->get_applied_filters();

		if (count($applied_filters) < 1) {
			return;
		}

		$content = '';

		$template_path = dirname(__FILE__) . '/views/list-filter.php';

		if ($attributes['layout'] === 'inline') {
			$template_path = dirname(__FILE__) . '/views/inline-filter.php';
		}

		blocksy_render_view_e($template_path, array_merge($attributes, [
			'applied_filters' => $applied_filters,
			'reset_url' => self::get_reset_url()
		]));
	}

	public function get_applied_filters() {
		$result = [];

		foreach (Filters::get_filter_instance() as $filter) {
			$applied = $filter->get_applied_filters();

			if ($applied) {
				if (isset($applied['name'])) {
					$result[] = $applied;

					continue;
				}

				foreach ($applied as $applied_filter) {
					$result[] = $applied_filter;
				}
			}
		}

		return $result;
	}
}

