<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class ProductWaitlistMailer {
	private $backInStockScheduler = null;

    public function __construct() {
		$this->backInStockScheduler = new BackInStockEmailScheduler();

		add_action('init', [$this, 'confirm_subscription']);
		add_action('init', [$this, 'cancel_subscription']);

		add_filter('woocommerce_email_classes', [$this, 'add_expedited_order_woocommerce_email']);

		add_action('woocommerce_product_set_stock_status', [$this, 'send_instock_email_emails'], 10, 3);
		add_action('woocommerce_variation_set_stock_status', [$this, 'send_instock_email_emails'], 10, 3);

		add_filter('woocommerce_email_styles', [$this, 'blocksy_emails_styles'], 10, 2);

		add_filter('wc_get_template', function ($template, $template_name, $args, $template_path, $default_path) {
			$base_path = path_join(
				BLOCKSY_PATH,
				'framework/premium/extensions/woocommerce-extra/features/product-waitlist/templates'
			);

			$local_template_path = path_join($base_path, $template_name);

			$is_our_template = file_exists($local_template_path);

			if (file_exists($template) && $is_our_template) {
				return $template;
			}

			if ($is_our_template) {
				return $local_template_path;
			}

			return $template;
		}, 10, 5);
    }

	public function blocksy_emails_styles($css, $email) {
		$emails_list = [
			'Blocksy\Extensions\WoocommerceExtra\BackInStockEmail',
			'Blocksy\Extensions\WoocommerceExtra\ConfirmSubscriptionEmail',
			'Blocksy\Extensions\WoocommerceExtra\SubscriptionConfirmedEmail',
		];

		if (in_array(get_class($email), $emails_list, true)) {
			ob_start();
			blocksy_render_view_e(
				dirname(__FILE__) . '/views/email-styles.php',
				[]
			);
			$css .= ob_get_clean();
		}

		return $css;
	}

	public function cancel_subscription() {
		if (
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			! isset($_GET['action'])
			||
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			'blocksy_cancel_subscription' !== $_GET['action']
			||
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			! isset($_GET['token'])
		) {
			return;
		}

		$redirect = remove_query_arg(['action', 'token']);
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$token = sanitize_text_field(wp_unslash($_GET['token']));

		if (ProductWaitlistDb::unsubscribe_by_token($token)) {
			do_action('woocommerce_set_cart_cookies',  true);

			if (function_exists('wc_add_notice')) {
				wc_add_notice(
					esc_html__('Your waitlist subscription has been successfully canceled.', 'blocksy-companion'),
					'success'
				);
			}
		}

		wp_safe_redirect($redirect);
		exit();
	}

	public function confirm_subscription() {
		if (
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			! isset($_GET['action'])
			||
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			'blocksy_confirm_subscription' !== $_GET['action']
			||
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			! isset($_GET['token'])
		) {
			return;
		}

		$redirect = remove_query_arg(['action', 'token']);
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$token = sanitize_text_field(wp_unslash($_GET['token']));

		if (ProductWaitlistDb::confirm_subscription($token)) {
			$data = ProductWaitlistDb::get_subscription_by_token($token);
			$product_id = ! empty($data->variation_id) ? $data->variation_id : $data->product_id;

			$mailer = WC()->mailer();
			$subscribtion_confirmder_email = $mailer->emails['SubscriptionConfirmedEmail'];

			if ($subscribtion_confirmder_email->is_enabled()) {
				$subscribtion_confirmder_email->trigger($data->user_email, wc_get_product($product_id));
			}

			do_action('woocommerce_set_cart_cookies',  true);

			if (function_exists('wc_add_notice')) {
				wc_add_notice(
					esc_html__('Your waitlist subscription has been successfully confirmed.', 'blocksy-companion'),
					'success'
				);
			}
		}

		wp_safe_redirect($redirect);
		exit();
	}

	public function add_expedited_order_woocommerce_email($email_classes) {
		$email_classes['BackInStockEmail'] = new \Blocksy\Extensions\WoocommerceExtra\BackInStockEmail();
		$email_classes['ConfirmSubscriptionEmail'] = new \Blocksy\Extensions\WoocommerceExtra\ConfirmSubscriptionEmail();
		$email_classes['SubscriptionConfirmedEmail'] = new \Blocksy\Extensions\WoocommerceExtra\SubscriptionConfirmedEmail();

		return $email_classes;

	}

	public function send_instock_email_emails($product_id, $stock_status, $product) {
		if (
			'instock' !== $stock_status
			||
			(
				'variable' === $product->get_type()
				&&
				! $product->get_manage_stock()
			)
		) {
			return;
		}

		wc_get_logger()->debug(
			'BackInStockEmailScheduler:send_instock_email_emails',
			[
				'source' => 'blocksy_waitlist_emails',
				'managed_stock' => $product->get_manage_stock(),
				'product' => $product->get_id(),
			]
		);

		$waitlists_to_process = ProductWaitlistDb::get_waitlists_from_db($product, '', '', true);

		if ('variable' === $product->get_type()) {
			$waitlists_to_process = [];
			$variations = $product->get_children();

			foreach ($variations as $variation_id) {
				$variation = wc_get_product($variation_id);

				wc_get_logger()->debug(
					'BackInStockEmailScheduler:send_instock_email_emails:variation_from_parent',
					[
						'source' => 'blocksy_waitlist_emails',
						'managed_stock' => $variation->get_manage_stock(),
						'product' => $variation->get_id(),
					]
				);

				if ('parent' !== $variation->get_manage_stock()) {
					continue;
				}

				$waitlists_to_process = array_merge(
					$waitlists_to_process,
					ProductWaitlistDb::get_waitlists_from_db($variation, '', '', true)
				);
			}
		}

		$this->backInStockScheduler->initiate($waitlists_to_process);
	}
}
