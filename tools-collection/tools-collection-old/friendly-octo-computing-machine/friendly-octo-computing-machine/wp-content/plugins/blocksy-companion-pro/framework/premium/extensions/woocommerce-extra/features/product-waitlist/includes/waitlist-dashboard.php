<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class ProductWaitlistDashboard {
    public function __construct() {
		add_action(
			'wp_ajax_blc_subcribe_to_waitlist',
			[$this, 'subcribe_to_waitlist']
		);

		add_action(
			'wp_ajax_nopriv_blc_subcribe_to_waitlist',
			[$this, 'subcribe_to_waitlist']
		);

		add_action(
			'wp_ajax_blc_waitlist_unsubscribe',
			[$this, 'unsubscribe_from_waitlist']
		);

		add_action(
			'wp_ajax_nopriv_blc_waitlist_unsubscribe',
			[$this, 'unsubscribe_from_waitlist']
		);

		add_action(
			'wp_ajax_blc_waitlist_sync',
			[$this, 'sync']
		);

		add_action(
			'wp_ajax_nopriv_blc_waitlist_sync',
			[$this, 'sync']
		);

		add_action(
			'wp_ajax_blocksy_ext_waitlist_export_users',
			[$this, 'export_users']
		);

		add_action('admin_menu', [$this, 'register_waitlist_page']);

		add_action(
			'admin_enqueue_scripts',
			function () {
				if (!function_exists('get_plugin_data')) {
					require_once ABSPATH . 'wp-admin/includes/plugin.php';
				}

				$data = get_plugin_data(BLOCKSY__FILE__);

				if (
					! is_admin()
					||
					// phpcs:ignore WordPress.Security.NonceVerification.Recommended
					! isset($_GET['page'])
					||
					// phpcs:ignore WordPress.Security.NonceVerification.Recommended
					$_GET['page'] !== 'blocksy-waitlist-page'
					||
					// phpcs:ignore WordPress.Security.NonceVerification.Recommended
					! isset($_GET['tab'])
					||
					// phpcs:ignore WordPress.Security.NonceVerification.Recommended
					$_GET['tab'] !== 'users'
				) {
					return;
				}

				wp_enqueue_script(
					'blocksy-waitlist-page',
					BLOCKSY_URL . 'framework/premium/extensions/woocommerce-extra/features/admin-static/bundle/admin-waitlist.js',
					[],
					$data['Version'],
					true
				);
			}
		);

		add_action('blocksy_waitlist_remove_not_confirmed', [$this, 'remove_not_confirmed_emails']);

		if (! wp_next_scheduled('blocksy_waitlist_remove_not_confirmed')) {
			wp_schedule_event(
				time(),
				apply_filters('blocksy_waitlist_remove_not_confirmed_time', 'daily'),
				'blocksy_waitlist_remove_not_confirmed'
			);
		}
    }

	public function remove_not_confirmed_emails() {
		ProductWaitlistDb::remove_not_confirmed_emails();
	}

	public function sync() {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if (! isset($_POST['product_id'])) {
			wp_send_json_error([
				'message' => __('Invalid request', 'blocksy-companion'),
			]);
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$product_id = intval($_POST['product_id']);

		$waitlist = ProductWaitlistDb::get_waitlist(wc_get_product($product_id));

		if (empty($waitlist)) {
			wp_send_json_success([
				'message' => __('No waitlist found', 'blocksy-companion'),
			]);
		}

		wp_send_json_success([
			'subscription_id' => $waitlist[0]->subscription_id,
			'unsubscribe_token' => $waitlist[0]->unsubscribe_token,
		]);
	}

	public function export_users() {
		wp_send_json_success([
			// phpcs:ignore WordPress.Security.NonceVerification.Missing
			'url' => ProductWaitlistDb::export_users_by_product(isset($_POST['product_id']) ? absint($_POST['product_id']) : 0)
		]);
	}

	public function register_waitlist_page() {
		global $blocksy_waitlists_page;

		$blocksy_waitlists_page = add_submenu_page(
			'edit.php?post_type=product',
			esc_html__('Waitlists', 'blocksy-companion'),
			esc_html__('Waitlists', 'blocksy-companion'),
			'edit_products',
			'blocksy-waitlist-page',
			[$this, 'render_waitlist_page']
		);

		add_action('load-' . $blocksy_waitlists_page, [$this, 'waitlist_screen_options']);
	}

	public function waitlist_screen_options() {
		global $blocksy_waitlists_page;

		$screen = get_current_screen();

		if (
			! is_object($screen)
			||
			$screen->id !== $blocksy_waitlists_page
		) {
			return;
		}

		add_screen_option(
			'per_page',
			[
				'label' => esc_html__('Number of items per page', 'blocksy-companion'),
				'default' => 20,
				'option' => 'blocksy_waitlist_per_page',
			]
		);
	}

	public function render_waitlist_page() {
		$list_table = new Waitlist_Table();
		$table_title = esc_html__('Waitlists', 'blocksy-companion');

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$is_user_table = ! empty($_GET['tab']) && 'users' === $_GET['tab'];

		if ($is_user_table) {
			$list_table = new Waitlist_Users_Table();
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$product_id = isset($_GET['product_id']) ? sanitize_text_field(wp_unslash($_GET['product_id'])) : '';

			$table_title = sprintf(
				// translators: %s is the product title
				esc_html__('Waitlist for %s', 'blocksy-companion'),
				get_the_title($product_id)
			);
		}

		wp_enqueue_style('woocommerce_admin_styles');

		$list_table->prepare_items();
		?>
			<div class="wrap">
				<h1 class="wp-heading-inline"><?php echo esc_html($table_title); ?></h1>

				<form id="posts-filter" class="blocksy-waitlist-form" method="get" action="">
					<input type="hidden" name="page" value="blocksy-waitlist-page" />
					<input type="hidden" name="post_type" value="product" />
					<?php $list_table->display(); ?>
				</form>
			</div>
		<?php
	}

	public function subcribe_to_waitlist() {
		if (
			! isset($_POST['product_id'])
			||
			! isset($_POST['email'])
		) {
			wp_send_json_error([
				'message' => __('Invalid request', 'blocksy-companion'),
			]);
		}

		if (
			! isset($_POST['_wpnonce'])
			||
			! wp_verify_nonce(sanitize_key($_POST['_wpnonce']), 'blocksy_waitlist_subscribe')
		) {
			wp_send_json_error([
				'message' => __('Invalid request', 'blocksy-companion'),
			]);
		}

		$product_id = intval($_POST['product_id']);
		$email = sanitize_email(wp_unslash($_POST['email']));

		$custom_validation = apply_filters(
			'blocksy:ext:woocommerce-extra:waitlist:subscribe:validate',
			null,
			$product_id,
			$email
		);

		if (is_wp_error($custom_validation)) {
			wp_send_json_error([
				'message' => $custom_validation->get_error_message()
			]);
		}

		// Allow custom email sanitization from the filter
		if (
			is_array($custom_validation)
			&&
			isset($custom_validation['email'])
		) {
			$email = $custom_validation['email'];
		} else {
			// No custom sanitizer provided for email field, use default.
			// Also double check email field.
			$email = sanitize_email($email);

			if (
				empty($email)
				||
				! is_email($email)
			) {
				wp_send_json_error([
					'message' => __('Invalid email', 'blocksy-companion'),
				]);
			}
		}

		$maybe_waitlist = ProductWaitlistDb::get_waitlists_from_db(wc_get_product($product_id), $email, '', false);

		if (! empty($maybe_waitlist)) {
			wp_send_json_error([
				'message' => __('You are already on the waitlist', 'blocksy-companion'),
			]);
		}

		$need_confirmation = blc_theme_functions()->blocksy_get_theme_mod('waitlist_user_confirmation', [
			'logged_in' => true,
			'logged_out' => true,
		]);

		$need_confirmation = is_user_logged_in()
			? $need_confirmation['logged_in']
			: $need_confirmation['logged_out'];

		$subscribtion = ProductWaitlistDb::create_subscription($email, $product_id, !$need_confirmation);

		$mailer = WC()->mailer();
		$confirm_subscription_email = $mailer->emails['ConfirmSubscriptionEmail'];
		$subscribtion_confirmder_email = $mailer->emails['SubscriptionConfirmedEmail'];

		if (
			$confirm_subscription_email->is_enabled()
			&&
			$need_confirmation
		) {
			$confirm_subscription_email->trigger($email, wc_get_product($product_id));
		}

		if (
			$subscribtion_confirmder_email->is_enabled()
			&&
			! $need_confirmation
		) {
			$subscribtion_confirmder_email->trigger($email, wc_get_product($product_id));
		}

		$count_data = ProductWaitlistLayer::get_users_count($product_id);

		wp_send_json_success([
			'message' => __('You have been added to the waitlist', 'blocksy-companion'),
			'subscription_id' => $subscribtion['subscription_id'],
			'unsubscribe_token' => $subscribtion['unsubscribe_token'],
			'waitlist_users' => $count_data['waitlist_users'],
			'waitlist_users_message' => $count_data['message'],
		]);
	}

	public function unsubscribe_from_waitlist() {
		if (
			// phpcs:ignore WordPress.Security.NonceVerification.Missing
			! isset($_POST['token'])
			||
			// phpcs:ignore WordPress.Security.NonceVerification.Missing
			! isset($_POST['product_id'])
		) {
			wp_send_json_error([
				'message' => __('Invalid request', 'blocksy-companion'),
			]);
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$product_id = intval($_POST['product_id']);

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		ProductWaitlistDb::unsubscribe_by_token(sanitize_text_field(wp_unslash($_POST['token'])));

		$count_data = ProductWaitlistLayer::get_users_count($product_id);

		wp_send_json_success([
			'message' => __('You have been removed from the waitlist', 'blocksy-companion'),
			'waitlist_users' => $count_data['waitlist_users'],
			'waitlist_users_message' => $count_data['message'],
		]);
	}
}

