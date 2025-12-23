<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class Waitlist_Users_Table extends \WP_List_Table {
	private $has_failed = false;
	private $has_pending = false;

	protected function extra_tablenav($which) {
		?>
		<div class="alignleft actions">
			<?php
				if ('top' === $which) {
					blocksy_safe_sprintf_e(
						'<a href="#" class="button action ct-waitlist-export">%s</a>',
						esc_html__('Export Subscribers', 'blocksy-companion')
					);
				}
			?>
		</div>
		<?php

		do_action('manage_posts_extra_tablenav', $which);
	}

	public function get_hidden_columns() {
		return [];
	}

	public function column_default($item, $column_name) {
		if (isset($item[$column_name])) {
			return apply_filters('blocksy:ext:woocommerce-extra:waitlist:users:column_default', esc_html($item[$column_name]), $item, $column_name);
		}
	}

	public function column_cb($item) {
		return blocksy_safe_sprintf(
			'<input type="checkbox" name="users_emails[]" value="%1$s" />',
			$item['user_email']
		) .
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		(isset($_GET['product_id']) ? blocksy_safe_sprintf(
			'<input type="hidden" name="product_id" value="%1$s" />',
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			sanitize_text_field(wp_unslash($_GET['product_id']))
		) : '') .
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		(isset($_GET['variation_id']) ? blocksy_safe_sprintf(
			'<input type="hidden" name="variation_id" value="%1$s" />',
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			sanitize_text_field(wp_unslash($_GET['variation_id']))
		) : '');
	}

	public function column_thumb($item) {
		$avatar = get_avatar($item['user_id'], 40);
		$edit_url = get_edit_user_link($item['user_id']);

		return blocksy_safe_sprintf('<a href="%s">%s</a>',
			$edit_url,
			$avatar
		);
	}

	public function column_name($item) {
		if (! $item['user_id']) {
			return esc_html__('Guest', 'blocksy-companion');
		}

		$user = get_user_by('id', $item['user_id']);
		$user_edit_url = get_edit_user_link($item['user_id']);
		$user_name = $user->user_login;

		$actions = [
			'ID' => blocksy_safe_sprintf('ID: %s', esc_html($item['user_id'])),
			'edit' => blocksy_safe_sprintf(
				'<a href="%s" title="%s">%s</a>',
				$user_edit_url,
				esc_html__('Edit this customer', 'blocksy-companion'),
				esc_html__('Edit', 'blocksy-companion')),
		];
		$row_actions = $this->row_actions($actions);

		return blocksy_safe_sprintf(
			'<strong><a class="row-title" href="%s">%s</a></strong>%s',
			$user_edit_url,
			$user_name,
			$row_actions
		);
	}

	public function column_email($item) {
		$view_waitlist_url = '';
		$delete_waitlist_url = add_query_arg(
			[
				'action' => 'blocksy_cancel_subscription',
				'token' => $item['unsubscribe_token'],
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				'product_id' => isset($_GET['product_id']) ? sanitize_text_field(wp_unslash($_GET['product_id'])) : '',
			],
			admin_url('edit.php?post_type=product&page=blocksy-waitlist-page&tab=users')
		);

		if ('0' !== $item['user_id']) {
			$view_waitlist_url = esc_url(
				add_query_arg(
					[
						'page' => 'blocksy-waitlist-page',
						'_user_id' => $item['user_id'],
					],
					admin_url('edit.php?post_type=product')
				)
			);
		}

		$actions = [
			'delete' => blocksy_safe_sprintf(
				'<a href="%s">%s</a>',
				esc_url($delete_waitlist_url),
				esc_html__('Delete', 'blocksy-companion')
			)
		];

		if (! empty($view_waitlist_url)) {
			$actions['view'] = blocksy_safe_sprintf(
				'<a href="%s">%s</a>',
				esc_url($view_waitlist_url),
				esc_html__('View', 'blocksy-companion')
			);
		}

		?>
		<?php if (! empty($view_waitlist_url)) : ?>
			<a href='<?php echo esc_url($view_waitlist_url); ?>'>
		<?php endif; ?>

		<?php echo esc_html($item['user_email']); ?>

		<?php if (! empty($view_waitlist_url)) : ?>
			</a>
		<?php endif; ?>

		<?php echo $this->row_actions($actions); // phpcs:ignore. ?>
		<?php
	}

	public function column_is_registered($item) {
		$class = $item['is_registered'] ? 'dashicons-saved' : 'dashicons-minus';
		return '<span class="dashicons ' . $class . '"></span>';
	}

	public function column_is_confirmed($item) {
		$class = $item['confirmed'] ? 'dashicons-saved' : 'dashicons-minus';
		return '<span class="dashicons ' . $class . '"></span>';
	}

	public function column_date_created($item) {
		$date_created = strtotime($item['created_date']);
		$time_diff  = time() - $date_created;

		if ($time_diff < DAY_IN_SECONDS) {
			$row = blocksy_safe_sprintf(
				// translators: %s is the human readable time difference
				esc_html__('%s ago', 'blocksy-companion'),
				human_time_diff($date_created)
			);
		} else {
			$row = date_i18n(wc_date_format(), $date_created);
		}

		return $row;
	}

	public function column_state($item) {
		if($item['state'] === 'new') {
			return '';
		}

		if($item['state'] === 'pending') {
			return blocksy_safe_sprintf(
				'<div class="pending">%s</div>',
				esc_html__('Pending', 'blocksy-companion'),
			);
		}

		return blocksy_safe_sprintf(
			'<div class="failed">%s</div>',
			esc_html__('Failed to send', 'blocksy-companion'),
		);
	}

	public function get_columns() {
		return array_merge(
			[
				'cb' => '<input type="checkbox" />',
				'thumb' => '<span class="wc-image tips" data-tip="' . esc_attr__('Image', 'blocksy-companion') . '">' . esc_html__('Image', 'blocksy-companion') . '</span>',
				'name' => esc_html__('Name', 'blocksy-companion'),
				'email' => esc_html__('Email', 'blocksy-companion'),
			],

			(
				$this->has_failed || $this->has_pending ? [
					'state' => esc_html__('Status', 'blocksy-companion'),
				] : []
			),

			[
				'is_registered' => esc_html__('Is registered', 'blocksy-companion'),
				// 'is_confirmed' => esc_html__('Is confirmed', 'blocksy-companion'),
				'date_created'  => esc_html__('Date created', 'blocksy-companion'),
			]
		);
	}

	public function get_sortable_columns() {
		return [
			'date_created' => ['created_date', false],
		];
	}

	public function get_bulk_actions() {

		return array_merge(
			[
				'delete' => esc_html__('Delete', 'blocksy-companion'),
			],
			$this->has_failed ? [
				'retry' => esc_html__('Retry Send', 'blocksy-companion'),
			] : []
		);
	}

	public function process_bulk_action() {
		if (
			! isset($_REQUEST['_wpnonce'])
			||
			! wp_verify_nonce(sanitize_key($_REQUEST['_wpnonce']), 'bulk-' . $this->_args['plural'])
		) {
			return;
		}

		$users_emails = isset($_REQUEST['users_emails']) ? array_map('sanitize_email', (array) wp_unslash($_REQUEST['users_emails'])) : false;
		$product_id = isset($_REQUEST['product_id']) ? intval(sanitize_text_field(wp_unslash($_REQUEST['product_id']))) : false;
		$variation_id = isset($_REQUEST['variation_id']) ? intval(sanitize_text_field(wp_unslash($_REQUEST['variation_id']))) : false;

		if (
			'delete' === $this->current_action()
			&&
			! empty($users_emails)
			&&
			! empty($product_id)
		) {
			foreach ($users_emails as $users_email) {
				try {
					$product_id = $variation_id ? $variation_id : $product_id;

					ProductWaitlistDb::unsubscribe_by_product($product_id, $users_email);
				} catch (Exception $e) {
					continue;
				}
			}

			wp_safe_redirect(
				add_query_arg(
					[
						'page' => 'blocksy-waitlist-page',
						'tab' => 'users',
						'product_id' => absint($_REQUEST['product_id']),
						'variation_id' => absint($_REQUEST['variation_id']),
					],
					admin_url('edit.php?post_type=product')
				)
			);
			die();
		}
	}

	public function prepare_items() {
		$user_id = get_current_user_id();

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if (isset($_REQUEST['wp_screen_options'])) {
			if (
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				isset($_REQUEST['wp_screen_options']['option'])
				&&
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				isset($_REQUEST['wp_screen_options']['value'])
				&&
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$_REQUEST['wp_screen_options']['option'] === 'blocksy_waitlist_per_page'
			) {
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				update_user_meta($user_id, 'blocksy_waitlist_per_page', intval(sanitize_text_field(wp_unslash($_REQUEST['wp_screen_options']['value']))));
			}
		}

		$items = $this->table_data();

		$columns = $this->get_columns();
		$hidden = $this->get_hidden_columns();
		$sortable = $this->get_sortable_columns();
		$data = [];

		foreach ($items as $item) {
			if (! get_user_by('id', $item['user_id'])) {
				$item['is_registered'] = false;
			} else {
				$item['is_registered'] = true;
			}

			$data[] = $item;
		}

		usort($data, [$this, 'sort_data']);

		$per_page = ! empty(get_user_meta($user_id, 'blocksy_waitlist_per_page', true)) ? get_user_meta($user_id, 'blocksy_waitlist_per_page', true) : 20;
		$current_page = $this->get_pagenum();

		global $wpdb;

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$product_id = isset($_REQUEST['product_id']) ? sanitize_text_field(wp_unslash($_REQUEST['product_id'])) : false;

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$total_items = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM $wpdb->blocksy_waitlists WHERE $wpdb->blocksy_waitlists.`confirmed` = 1 AND $wpdb->blocksy_waitlists.`product_id` = %d",
				$product_id
			)
		);

		$this->set_pagination_args([
			'total_items' => $total_items,
			'per_page'  => $per_page,
		]);

		$data = array_slice($data, (($current_page - 1) * $per_page), $per_page);

		$this->_column_headers = [$columns, $hidden, $sortable];
		$this->items = $data;

		$this->process_bulk_action();
	}

	private function table_data() {
		global $wpdb;

		$where_query  = [];
		$product_id = isset($_REQUEST['product_id']) ? sanitize_text_field(wp_unslash($_REQUEST['product_id'])) : false; // phpcs:ignore.
		$variation_id = isset($_REQUEST['variation_id']) ? sanitize_text_field(wp_unslash($_REQUEST['variation_id'])) : false; // phpcs:ignore.

		if (empty($product_id) && empty($variation_id)) {
			return [];
		}

		if ($product_id) {
			$where_query[] = $wpdb->prepare("$wpdb->blocksy_waitlists.`product_id` = %d", intval($product_id));
		}

		if ($variation_id) {
			$where_query[] = $wpdb->prepare("$wpdb->blocksy_waitlists.`variation_id` = %d", intval($variation_id));
		}

		$where_query[] = $wpdb->prepare("$wpdb->blocksy_waitlists.`confirmed` = %d", 1);

		$where_query_text = ! empty($where_query) ? ' WHERE ' . implode(' AND ', $where_query) : '';

		// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
		$data = $wpdb->get_results(
			"SELECT
				$wpdb->blocksy_waitlists.`user_id`,
				$wpdb->blocksy_waitlists.`user_email`,
				$wpdb->blocksy_waitlists.`unsubscribe_token`,
				$wpdb->blocksy_waitlists.`confirmed`,
				$wpdb->blocksy_waitlists.`created_date_gmt` as `created_date`,
				$wpdb->blocksy_waitlists.`state`
			FROM $wpdb->blocksy_waitlists"
			. $where_query_text .
			' LIMIT 50;',
			ARRAY_A
		);
		// phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared

		foreach ($data as $value) {
			if ($value['state'] === 'failed') {
				$this->has_failed = true;
			}

			if ($value['state'] === 'pending') {
				$this->has_pending = true;
			}
		}

		return $data;
	}

	private function sort_data($a, $b) {
		// Set defaults.
		$order_by = 'created_date';
		$order  = 'desc';

		// If orderby is set, use this as the sort column.
		if (! empty($_GET['orderby'])) { // phpcs:ignore.
			$order_by = $_GET['orderby']; // phpcs:ignore.
		}

		// If order is set use this as the order.
		if (! empty($_GET['order'])) { // phpcs:ignore.
			$order = $_GET['order']; // phpcs:ignore.
		}

		$result = strcmp($a[$order_by], $b[$order_by]);

		if (is_numeric($a[$order_by]) && is_numeric($a[$order_by])) {
			$result = $a[$order_by] - $b[$order_by];
		}

		if ('asc' === $order) {
			return $result;
		}

		return -$result;
	}
}
