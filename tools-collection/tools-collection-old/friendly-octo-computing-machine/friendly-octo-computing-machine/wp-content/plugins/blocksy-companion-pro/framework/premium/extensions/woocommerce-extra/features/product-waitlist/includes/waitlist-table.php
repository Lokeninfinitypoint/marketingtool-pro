<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class Waitlist_Table extends \WP_List_Table {
	protected function extra_tablenav($which) {
		?>
		<div class="alignleft actions has-searchbox">
			<?php
				if ('top' === $which) {
					$this->search_box(esc_html__('Search Products', 'blocksy-companion'), 'blocksy-companion');
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
			return apply_filters(
				'blocksy:ext:woocommerce-extra:waitlist:products:column_default',
				esc_html($item[$column_name]), $item, $column_name
			);
		}
	}

	public function column_cb($item) {
		return blocksy_safe_sprintf(
			'<input type="checkbox" name="products_ids[]" value="%1$s" />',
			! empty($item['variation_id']) ? $item['variation_id'] : $item['product_id']
		);
	}

	public function column_thumb($item) {
		$product_id = $item['variation_id'] ? $item['variation_id'] : $item['product_id'];
		$product = wc_get_product($product_id);

		if (! $product) {
			return '';
		}
		?>
		<a href="<?php echo esc_url(get_edit_post_link($product->get_id())); ?>">
			<?php echo $product->get_image('thumbnail'); // phpcs:ignore. ?>
		</a>
		<?php
	}

	public function column_action($item) {
		?>
			<a href="<?php echo esc_url(
				add_query_arg(
					[
						'page' => 'blocksy-waitlist-page',
						'tab' => 'users',
						'product_id' => $item['product_id'],
						'variation_id' => $item['variation_id'],
					],
					admin_url('edit.php?post_type=product')
				)
			); ?>" class="button">
				<?php esc_html_e('View Subscribers', 'blocksy-companion'); ?>
			</a>
		<?php
	}

	public function column_name($item) {
		$product_id = $item['variation_id'] ? $item['variation_id'] : $item['product_id'];
		$product = wc_get_product($product_id);

		if (! $product) {
			return '';
		}

		$product_edit_url = get_edit_post_link($item['product_id']);

		$actions = [
			'product_id' => blocksy_safe_sprintf('ID: %s', esc_html($item['product_id'])),
			'edit' => blocksy_safe_sprintf(
				'<a href="%s" title="%s">%s</a>',
				$product_edit_url,
				esc_html__('Edit this item', 'blocksy-companion'),
				esc_html__('Edit', 'blocksy-companion')
			),
			'view_product' => blocksy_safe_sprintf(
				'<a href="%s" title="%s" rel="permalink">%s</a>',
				$product->get_permalink(),
				esc_html__('View Product', 'blocksy-companion'),
				esc_html__('View Product', 'blocksy-companion')
			),
		];

		if ('variation' === $product->get_type()) {
			$attributes = [];

			foreach ($product->get_attributes() as $taxonomy => $value) {
				$attribute_values = wc_get_product_terms($product->get_parent_id(), $taxonomy, ['fields' => 'all']);

				$value_name = $value;

				foreach ($attribute_values as $attribute_value) {
					if ($attribute_value->slug === $value) {
						$value_name = esc_html($attribute_value->name);

						break;
					}
				}

				$attributes[wc_attribute_label($taxonomy)] = $value_name;
			}
		}

		?>
		<div class="product-details">
			<strong>
				<a class="row-title" href="<?php echo esc_url($product_edit_url); ?>">
					<?php echo esc_html($product->get_title()); ?>
				</a>
			</strong>

			<?php if (isset($attributes) && ! empty($attributes)) : ?>
				<table cellspacing="0" class="product-variations">
					<?php foreach ($attributes as $label => $value) : ?>
						<tr>
							<th><?php echo wp_kses_post(ucfirst($label)); ?>:</th>
							<td><?php echo wp_kses_post(ucfirst($value)); ?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php endif; ?>

			<?php echo $this->row_actions($actions); // phpcs:ignore. ?>
		</div>
		<?php
	}

	public function column_is_in_stock($item) {
		$product_id = $item['variation_id'] ? $item['variation_id'] : $item['product_id'];
		$product = wc_get_product($product_id);

		if (! $product) {
			return '';
		}

		if ($product->is_in_stock()) {
			$status_class = 'instock';
			$status_label = esc_html__('In stock', 'blocksy-companion');
		} else {
			$status_class = 'outofstock';
			$status_label = esc_html__('Out of stock', 'blocksy-companion');
		}

		ob_start();
		?>
		<mark class="<?php echo esc_attr($status_class); ?>">
			<?php echo esc_html($status_label); ?>
		</mark>
		<?php

		return ob_get_clean();
	}

	public function column_price($item) {
		$product_id = $item['variation_id'] ? $item['variation_id'] : $item['product_id'];
		$product = wc_get_product($product_id);

		if (! $product) {
			return '';
		}

		return $product->get_price_html();
	}

	public function column_users($item) {
		$user_count = $item['user_count'];

		return blocksy_safe_sprintf(
			'<a href="%s">%d</a>',
			esc_url(
				add_query_arg(
					[
						'page' => 'blocksy-waitlist-page',
						'tab' => 'users',
						'product_id' => $item['product_id'],
						'variation_id' => $item['variation_id'],
					],
					admin_url('edit.php?post_type=product')
				)
			),
			$user_count
		);
	}

	public function column_statuses($item) {
		$user_count = $item['user_count'];
		$failed = $item['failed_count'];
		$pending = $item['pending_count'];

		$content = blocksy_safe_sprintf(
			'<a href="%s">%s: %d</a>',
			esc_url(
				add_query_arg(
					[
						'page' => 'blocksy-waitlist-page',
						'tab' => 'users',
						'product_id' => $item['product_id'],
						'variation_id' => $item['variation_id'],
					],
					admin_url('edit.php?post_type=product')
				)
			),
			esc_html__('Subscribers', 'blocksy-companion'),
			$user_count
		);

		if ($pending > 0) {
			$content .= blocksy_safe_sprintf(
				'<div class="pending">%s: %d</div>',
				esc_html__('Pending entries', 'blocksy-companion'),
				$pending
			);
		}

		if ($failed > 0) {
			$content .= blocksy_safe_sprintf(
				'<div class="failed">%s: %d</div>',
				esc_html__('Failed entries', 'blocksy-companion'),
				$failed
			);
		}

		return $content;
	}

	public function get_columns() {
		return [
			'cb' => '<input type="checkbox" />',
			'thumb' => '<span class="wc-image tips" data-tip="' . esc_attr__('Image', 'blocksy-companion') . '">' . esc_html__('Image', 'blocksy-companion') . '</span>',
			'name' => esc_html__('Name', 'blocksy-companion'),
			'is_in_stock' => esc_html__('Stock', 'blocksy-companion'),
			'price' => esc_html__('Price', 'blocksy-companion'),
			'statuses' => esc_html__('Status', 'blocksy-companion'),
			'action' => '',
		];
	}

	public function get_sortable_columns() {
		return [];
	}

	public function get_bulk_actions() {
		return [
			'delete' => esc_html__('Delete', 'blocksy-companion'),
		];
	}

	public function process_bulk_action() {
		if (
			! isset($_REQUEST['_wpnonce'])
			||
			! wp_verify_nonce(sanitize_key($_REQUEST['_wpnonce']), 'bulk-' . $this->_args['plural'])
		) {
			return;
		}

		$products_ids = isset($_REQUEST['products_ids']) ? array_map('intval', (array) $_REQUEST['products_ids']) : false;

		if (
			'delete' === $this->current_action()
			&&
			! empty($products_ids)
		) {
			foreach ($products_ids as $product_id) {
				try {
					ProductWaitlistDb::unsubscribe_by_product($product_id);
				} catch (Exception $e) {
					continue;
				}
			}

			wp_safe_redirect(admin_url('/edit.php?post_type=product&page=blocksy-waitlist-page'));
			die();
		}

		$users_emails = isset($_REQUEST['users_emails']) ? array_map('sanitize_email', (array) wp_unslash($_REQUEST['users_emails'])) : false;
		$product_id = isset($_REQUEST['product_id']) ? absint(wp_unslash($_REQUEST['product_id'])) : false;
		$variation_id = isset($_REQUEST['variation_id']) ? absint(wp_unslash($_REQUEST['variation_id'])) : false;

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
						'product_id' => $product_id,
						'variation_id' => $variation_id,
					],
					admin_url('edit.php?post_type=product')
				)
			);
			die();
		}

		if (
			'retry' === $this->current_action()
			&&
			! empty($users_emails)
			&&
			! empty($product_id)
		) {
			$backInStockScheduler = new BackInStockEmailScheduler();
			$product = wc_get_product($product_id);

			if ($variation_id) {
				$product = wc_get_product($variation_id);
			}

			if (
				! $product
				||
				$product->get_stock_status() === 'outofstock'
			) {
				return;
			}

			$waitlists_to_process = [];

			foreach ($users_emails as $user_email) {
				$waitlist = ProductWaitlistDb::get_waitlists_from_db($product, $user_email, '', true);

				if (! empty($waitlist)) {
					$waitlists_to_process[] = $waitlist[0];
				}
			}

			if (empty($waitlists_to_process)) {
				return;
			}

			$waitlists_to_process = array_filter($waitlists_to_process, function ($waitlist) {
				return $waitlist->state === 'failed' || $waitlist->state === 'new';
			});

			$backInStockScheduler->initiate($waitlists_to_process);
		}
	}

	public function prepare_items() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if (isset($_REQUEST['wp_screen_options'])) {
			$user_id = get_current_user_id();

			if (
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				isset($_REQUEST['wp_screen_options']['option'])
				&&
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				isset($_REQUEST['wp_screen_options']['value'])
				&&
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				sanitize_text_field(wp_unslash($_REQUEST['wp_screen_options']['option'])) === 'blocksy_waitlist_per_page'
			) {
				update_user_meta(
					$user_id,
					'blocksy_waitlist_per_page',
					// phpcs:ignore WordPress.Security.NonceVerification.Recommended
					absint(wp_unslash($_REQUEST['wp_screen_options']['value']))
				);
			}
		}

		$columns = $this->get_columns();
		$hidden = $this->get_hidden_columns();
		$sortable = $this->get_sortable_columns();
		$user_id = get_current_user_id();

		$data = $this->table_data();
		usort($data, [$this, 'sort_data']);

		$per_page = 20;

		$blocksy_waitlist_per_page = get_user_meta(
			$user_id,
			'blocksy_waitlist_per_page',
			true
		);

		if (! empty($blocksy_waitlist_per_page)) {
			$per_page = $blocksy_waitlist_per_page;
		}

		$current_page = $this->get_pagenum();

		global $wpdb;

		list($where_query_text, $join_clause) = $this->build_query();

		// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$total_items = $wpdb->get_var(
			"SELECT COUNT(*) as total_count
			FROM (
				SELECT
					$wpdb->blocksy_waitlists.`subscription_id`
				FROM $wpdb->blocksy_waitlists
				$join_clause
				$where_query_text
				GROUP BY
					$wpdb->blocksy_waitlists.`product_id`,
					$wpdb->blocksy_waitlists.`variation_id`
			) as subquery;"
		);
		// phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		$this->set_pagination_args([
			'total_items' => $total_items,
			'per_page' => $per_page,
		]);

		$this->_column_headers = [$columns, $hidden, $sortable];
		$this->items = $data;

		$this->process_bulk_action();
	}

	private function table_data() {
		global $wpdb;

		list($where_query_text, $join_clause) = $this->build_query();

        $user_id = get_current_user_id();

		$blocksy_waitlist_per_page = get_user_meta(
			$user_id,
			'blocksy_waitlist_per_page',
			true
		);

		$per_page = 20;

		if (! empty($blocksy_waitlist_per_page)) {
			$per_page = $blocksy_waitlist_per_page;
		}

        $offset = ($this->get_pagenum() - 1) * $per_page;

		// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$waitlists = $wpdb->get_results(
			"SELECT
				$wpdb->blocksy_waitlists.`subscription_id`,
				$wpdb->blocksy_waitlists.`product_id`,
				$wpdb->blocksy_waitlists.`variation_id`,
				COUNT($wpdb->blocksy_waitlists.`user_id`) as `user_count`,
				SUM(CASE WHEN $wpdb->blocksy_waitlists.`state` = 'new' THEN 1 ELSE 0 END) AS new_count,
				SUM(CASE WHEN $wpdb->blocksy_waitlists.`state` = 'pending' THEN 1 ELSE 0 END) AS pending_count,
				SUM(CASE WHEN $wpdb->blocksy_waitlists.`state` = 'failed' THEN 1 ELSE 0 END) AS failed_count,
				$wpdb->blocksy_waitlists.`created_date_gmt` as `created_date`
			FROM $wpdb->blocksy_waitlists
			$join_clause
			$where_query_text
			GROUP BY
				$wpdb->blocksy_waitlists.`product_id`,
				$wpdb->blocksy_waitlists.`variation_id`
			LIMIT $per_page OFFSET $offset;",
			ARRAY_A
		);
		// phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		$waitlists = array_filter($waitlists, function ($waitlist) {
			if ($waitlist['variation_id']) {
				$product = wc_get_product($waitlist['variation_id']);
			} else {
				$product = wc_get_product($waitlist['product_id']);
			}

			return !!$product;
		});

		return $waitlists;
	}

	private function build_query() {
		global $wpdb;

		$where_query = [];

		$search = false;

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if (isset($_REQUEST['s'])) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$search = sanitize_text_field(wp_unslash($_REQUEST['s']));
		}

		if ($search) {
			$where_query[] = $wpdb->prepare(
				"$wpdb->posts.`post_title` LIKE %s",
				'%' . $wpdb->esc_like($search) . '%'
			);
		}

		$where_query[] = $wpdb->prepare(
			"$wpdb->blocksy_waitlists.`confirmed` = %d",
			1
		);

		$where_query_text = '';

		if (! empty($where_query)) {
			$where_query_text = ' WHERE ' . implode(' AND ', $where_query);
		}

		// Join clause (common between queries)
		$join_clause = "INNER JOIN $wpdb->posts
						ON $wpdb->posts.`ID` = $wpdb->blocksy_waitlists.`product_id`";

		return [$where_query_text, $join_clause];
	}

	private function sort_data($a, $b) {
		// Set defaults.
		$order_by = 'created_date';
		$order = 'desc';

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
