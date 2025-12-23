<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class ProductWaitlistLayer {
	public function __construct() {
		add_filter('blocksy_woo_single_options_layers:defaults', [
			$this,
			'register_layer_defaults',
		]);

		add_filter('blocksy_woo_single_options_layers:extra', [
			$this,
			'register_layer_options',
		]);

		add_action('blocksy:woocommerce:product:custom:layer', [
			$this,
			'render_layer',
		]);

		add_action(
			'blocksy:woocommerce:quick-view:add-to-cart:after',
			[$this, 'render_quick_view_layer'],
			10
		);

		add_shortcode('blocksy_waitlist', [
			$this,
			'shortcode_render',
		]);

		add_filter('do_shortcode_tag', function($output, $tag, $attr) {
			if ('blocksy_waitlist' === $tag) {
				wp_enqueue_style('blocksy-ext-woocommerce-extra-product-waitlist-styles');
			}

			return $output;
		}, 10, 3);
	}

	public static function get_users_count($product_id) {
		$waitlist_users = count(ProductWaitlistDb::get_waitlists_from_db(wc_get_product($product_id), '', '', true));

		$message = blocksy_safe_sprintf(
			// translators: %1$s is the number of users, %2$s is the plural form (user/users)
			__('%1$s %2$s joined the waitlist for this item.', 'blocksy-companion'),
			blocksy_html_tag(
				'span',
				[],
				$waitlist_users,
			),
			$string = _n(
				'user',
				'users',
				$waitlist_users,
				'blocksy-companion'
			)
		);

		return [
			'waitlist_users' => $waitlist_users,
			'message' => $message,
		];
	}

	private static function need_to_show() {
		global $product;

		if (! $product) {
			return [
				'need_to_show' => false,
				'visible' => false,
				'product_id' => null
			];
		}

		$need_to_show = false;
		$visible = false;

		$product_id = $product->get_id();

		if (
			$product->get_stock_status() === 'outofstock'
			||
			(
				$product->get_stock_status() === 'onbackorder'
				&&
				blc_theme_functions()->blocksy_get_theme_mod('waitlist_allow_backorders', 'no') === 'yes'
			)
		) {
			$need_to_show = true;

			if (! $product->is_type('variable')) {
				$visible = true;
			}
		}

		if ($product->is_type('variable')) {
			$maybe_current_variation = null;

			if (blc_theme_functions()->blocksy_manager()) {
				$maybe_current_variation = blc_theme_functions()->blocksy_manager()
					->woocommerce
					->retrieve_product_default_variation($product);
			}

			if ($maybe_current_variation) {
				$product_id = $maybe_current_variation->get_id();

				if (
					$maybe_current_variation->get_stock_status() === 'outofstock'
					||
					(
						$maybe_current_variation->get_stock_status() === 'onbackorder'
						&&
						blc_theme_functions()->blocksy_get_theme_mod('waitlist_allow_backorders', 'no') === 'yes'
					)
				) {
					$need_to_show = true;
					$visible = true;
				} else {
					$need_to_show = false;
				}
			}

			// TODO: this is a potential performance issue
			// If large setups will need this, we will need to find a
			// faster way to query only relevant variations.
			$all_variations = $product->get_available_variations();

			foreach ($all_variations as $variation) {
				if (
					(
						isset($variation['backorders_allowed'])
						&&
						$variation['backorders_allowed']
						&&
						blc_theme_functions()->blocksy_get_theme_mod('waitlist_allow_backorders', 'no') === 'no'
					)
					||
					(
						isset($variation['is_in_stock'])
						&&
						$variation['is_in_stock']
					)
				) {
					continue;
				}

				$need_to_show = true;

				if ($need_to_show) {
					break;
				}
			}
		}

		return [
			'need_to_show' => $need_to_show,
			'visible' => $visible,
			'product_id' => $product_id,
		];
	}

	public static function get_content() {
		$waitlist_user_visibility = blc_theme_functions()->blocksy_get_theme_mod('waitlist_user_visibility', 'no');

		if (
			$waitlist_user_visibility === 'yes'
			&&
			! is_user_logged_in()
		) {
			return;
		}

		$waitlist_conditions = blc_theme_functions()->blocksy_get_theme_mod('waitlist_conditions', [
			[
				'type' => 'include',
				'rule' => 'everywhere',
				'payload' => []
			]
		]);

		$conditions_manager = new \Blocksy\ConditionsManager();

		$is_matching_conditions = $conditions_manager->condition_matches(
			$waitlist_conditions,
			['relation' => 'OR']
		);

		if (! $is_matching_conditions) {
			return;
		}

		$visibility = self::need_to_show();

		if (! $visibility['need_to_show']) {
			return;
		}

		$state = 'hidden';
		$unsubscribe_token = '';
		$subscription_id = '';

		if ($visibility['visible']) {
			$state = 'visible';

			$waitlist = ProductWaitlistDb::get_waitlist(wc_get_product($visibility['product_id']), '', false);

			if (! empty($waitlist)) {
				$state = 'subscribed';

				$unsubscribe_token = $waitlist[0]->unsubscribe_token;
				$subscription_id = $waitlist[0]->subscription_id;

				if ($waitlist[0]->confirmed === '1') {
					$state = 'subscribed-confirmed';
				}
			}
		}

		return blocksy_render_view(
			dirname(__FILE__) . '/views/form.php',
			[
				'state' => $state,
				'unsubscribe_token' => $unsubscribe_token,
				'subscription_id' => $subscription_id,
				'product_id' => $visibility['product_id'],
			]
		);
	}

	public function shortcode_render() {
		$content = self::get_content();

		if (! $content) {
			return;
		}

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $content;
	}

	public function render_quick_view_layer() {
		$content = self::get_content();

		if (! $content) {
			return;
		}

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $content;
	}

	public function render_layer($layer) {
		if ($layer['id'] !== 'product_waitlist') {
			return;
		}

		$content = self::get_content();

		if (! $content) {
			return;
		}

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $content;
	}

	public function register_layer_defaults($opt) {
		return array_merge($opt, [
			[
				'id' => 'product_waitlist',
				'enabled' => false,
			],
		]);
	}

	public function register_layer_options($opt) {
		return array_merge($opt, [
			'product_waitlist' => [
				'label' => __('Waitlist Form', 'blocksy-companion'),
				'options' => [
					'spacing' => [
						'label' => __('Bottom Spacing', 'blocksy-companion'),
						'type' => 'ct-slider',
						'min' => 0,
						'max' => 100,
						'value' => 35,
						'responsive' => true,
						'sync' => [
							'id' => 'woo_single_layout_skip',
						],
					],
				],
			],
		]);

		return $opt;
	}
}
