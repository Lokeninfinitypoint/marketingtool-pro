<?php

class BlocksyExtensionShortcuts {
	public function __construct() {
		add_action('wp_enqueue_scripts', function () {
			if (! function_exists('get_plugin_data')) {
				require_once(ABSPATH . 'wp-admin/includes/plugin.php');
			}

			$data = get_plugin_data(BLOCKSY__FILE__);

			if (is_admin()) {
				return;
			}

			wp_enqueue_style(
				'blocksy-ext-shortcuts-styles',
				BLOCKSY_URL . 'framework/premium/extensions/shortcuts/static/bundle/main.min.css',
				['ct-main-styles'],
				$data['Version']
			);
		}, 50);

		add_action(
			'customize_preview_init',
			function () {
				if (! function_exists('get_plugin_data')) {
					require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
				}

				$data = get_plugin_data(BLOCKSY__FILE__);

				wp_enqueue_script(
					'blocksy-ext-shortcuts-customizer-sync',
					BLOCKSY_URL . 'framework/premium/extensions/shortcuts/static/bundle/sync.js',
					[ 'customize-preview', 'ct-scripts' ],
					$data['Version'],
					true
				);
			}
		);

		add_filter(
			'blocksy_extensions_customizer_options',
			function ($opts) {
				$opts['shortcuts_ext'] = blocksy_get_options(
					dirname(__FILE__) . '/customizer.php',
					[],
					false
				);

				return $opts;
			}
		);

		add_filter(
			'blocksy:footer:offcanvas-drawer',
			function ($els, $payload) {
				if (
					$payload['location'] !== 'end'
					||
					! $this->has_shortcuts_bar()
				) {
					return $els;
				}

				$shortcuts_flag = '';

				if (blc_theme_functions()->blocksy_get_theme_mod('shortcuts_interaction', 'none') === 'scroll') {
					$shortcuts_flag = 'scroll:yes';
				}

				$els[] = [
					'attr' => [
						'data-shortcuts-bar' => $shortcuts_flag
					],
					'content' => blocksy_render_view(
						dirname(__FILE__) . '/views/bar.php',
						[]
					)
				];

				return $els;
			},
			50,
			2
		);

		add_filter('blocksy:frontend:dynamic-js-chunks', function ($chunks) {
			$chunks[] = [
				'id' => 'blocksy_shortcuts_auto_hide',
				'selector' => '[data-shortcuts-bar*="scroll"] .ct-shortcuts-bar',
				'trigger' => 'scroll',
				'url' => blocksy_cdn_url(
					BLOCKSY_URL . 'framework/premium/extensions/shortcuts/static/bundle/auto-hide.js'
				),
			];

			$has_cart = false;

			$items = blc_theme_functions()->blocksy_get_theme_mod('shortcuts_bar_items', []);

			foreach ($items as $item) {
				if (
					class_exists('WooCommerce')
					&&
					$item['id'] === 'cart'
					&&
					$item['enabled']
				) {
					$has_cart = true;
				}
			}

			if ($has_cart) {
				$chunks[] = [
					'id' => 'blocksy_shortcuts_cart',
					'selector' => '.ct-shortcuts-bar [data-shortcut="cart"]',
					'trigger' => 'click',
					'url' => blocksy_cdn_url(
						BLOCKSY_URL . 'framework/premium/extensions/shortcuts/static/bundle/cart.js'
					),
				];
			}

			return $chunks;
		});

		add_filter(
			'blocksy:translations-manager:all-translation-keys',
			function ($all_keys) {
				$shortcuts_bar_items = blc_theme_functions()->blocksy_get_theme_mod(
					'shortcuts_bar_items',
					'__EMPTY__'
				);

				if ($shortcuts_bar_items === '__EMPTY__') {
					return $all_keys;
				}

				foreach ($shortcuts_bar_items as $item) {
					$id_prefix = 'shortcuts:' . $item['id'];

					if ($item['id'] === 'custom_link' && isset($item['__id'])) {
						$id_prefix = 'shortcuts:' . $item['__id'];
					}

					if (isset($item['label']) && ! empty($item['label'])) {
						$all_keys[] = [
							'key' => $id_prefix . ':label',
							'value' => $item['label']
						];
					}

					if (isset($item['link']) && ! empty($item['link'])) {
						$all_keys[] = [
							'key' => $id_prefix . ':link',
							'value' => $item['link']
						];
					}
				}

				return $all_keys;
			}
		);

		add_action(
			'blocksy:global-dynamic-css:enqueue',
			'BlocksyExtensionShortcuts::add_global_styles',
			10, 3
		);
	}

	public function has_shortcuts_bar() {
		$items = blc_theme_functions()->blocksy_get_theme_mod('shortcuts_bar_items', [
			[
				'id' => 'home',
				'enabled' => true,
				'label' => __('Home', 'blocksy-companion'),
				'icon' => [
					'icon' => 'blc blc-home'
				]
			],

			[
				'id' => 'phone',
				'enabled' => true,
				'label' => __('Phone', 'blocksy-companion'),
				'icon' => [
					'icon' => 'blc blc-phone'
				]
			]
		]);

		$result = [];

		foreach ($items as $single_item) {
			if (isset($single_item['enabled']) && ! $single_item['enabled']) {
				continue;
			}

			$result[] = $single_item;
		}

		if (empty($result)) {
			return false;
		}

		$initial_conditions = [
			[
				'type' => 'include',
				'rule' => 'everywhere'
			]
		];

		if (class_exists('WooCommerce')) {
			$initial_conditions[] = [
				'type' => 'exclude',
				'rule' => 'page_ids',
				'payload' => [
					'post_id' => intval(get_option('woocommerce_cart_page_id'))
				]
			];

			$initial_conditions[] = [
				'type' => 'exclude',
				'rule' => 'page_ids',
				'payload' => [
					'post_id' => intval(
						get_option('woocommerce_checkout_page_id')
					)
				]
			];
		}

		$conditions = blc_theme_functions()->blocksy_get_theme_mod(
			'shortcuts_bar_conditions',
			$initial_conditions
		);

		$conditions_manager = new \Blocksy\ConditionsManager();

		if (! $conditions_manager->condition_matches($conditions)) {
			return false;
		}

		if (defined('REST_REQUEST') && REST_REQUEST) {
			return false;
		}

		return true;
	}

	static public function add_global_styles($args) {
		blocksy_theme_get_dynamic_styles(array_merge([
			'path' => dirname(__FILE__) . '/global.php',
			'chunk' => 'global',
		], $args));
	}

	static public function onDeactivation() {
		remove_action(
			'blocksy:global-dynamic-css:enqueue',
			'BlocksyExtensionShortcuts::add_global_styles',
			10, 3
		);
	}
}

