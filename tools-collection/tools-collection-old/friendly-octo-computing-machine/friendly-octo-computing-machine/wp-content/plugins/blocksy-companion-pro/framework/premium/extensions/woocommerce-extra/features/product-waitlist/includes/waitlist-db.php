<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class ProductWaitlistDb {
    public static $db_name = 'blocksy_waitlists';
	public static $cookie_name = 'blc_products_waitlist';

    public function __construct() {
        self::define_tables();

        add_action('admin_init', [$this, 'install_db'], 100);
		add_action('admin_init', [$this, 'upgrade_db'], 100);
    }

	public static function remove_not_confirmed_emails() {
		global $wpdb;

		$wpdb->query( // phpcs:ignore.
			"DELETE FROM $wpdb->blocksy_waitlists
			WHERE created_date_gmt < (NOW() - INTERVAL 2 DAY) AND confirmed = 0"
		);
	}

	public static function get_total_number_of_rows() {
		global $wpdb;

		$query = "SELECT COUNT(*) FROM $wpdb->blocksy_waitlists";

		return $wpdb->get_var($query); // phpcs:ignore.
	}

	public static function confirm_subscription($token) {
		global $wpdb;

		$db_row = $wpdb->get_row( // phpcs:ignore.
			$wpdb->prepare("SELECT user_email, product_id, variation_id FROM $wpdb->blocksy_waitlists WHERE confirm_token = %s", $token)
		);

		if (! $db_row) {
			return false;
		}

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$wpdb->update(
			$wpdb->blocksy_waitlists,
			['confirmed' => 1],
			['confirm_token' => $token]
		);

		return true;
	}

    public static function get_subscription_by_token($token) {
		global $wpdb;

		$db_row = $wpdb->get_row( // phpcs:ignore.
			$wpdb->prepare("SELECT user_email, product_id, variation_id FROM $wpdb->blocksy_waitlists WHERE confirm_token = %s", $token)
		);

		return $db_row;
	}

	public static function unsubscribe_by_token($token) {
		global $wpdb;

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		return $wpdb->delete(
			$wpdb->blocksy_waitlists,
			[
				'unsubscribe_token' => $token
			]
		);
	}

	public static function get_waitlist($product = '', $email = '', $confirmed = true, $page = false) {
		if (! is_user_logged_in()) {
			return self::get_waitlists_from_cookies($product);
		}

		return self::get_waitlists_from_db($product, $email, get_current_user_id(), $confirmed, [], $page);
	}

	public static function get_waitlists_from_cookies($product = '') {
		if (!isset($_COOKIE[self::$cookie_name])) {
			return [];
		}

		$waitlist = json_decode(
			sanitize_text_field(wp_unslash($_COOKIE[self::$cookie_name])),
			true
		);

		if (!$waitlist) {
			return [];
		}

		$waitlist = self::get_waitlists_from_db($product, '', '', false, $waitlist);

		return $waitlist;
	}

    public static function get_waitlists_from_db($product = '', $email = '', $user_id = '', $confirmed = true, $lists = [], $page = false) {
		global $wpdb;

		$where = [];

		if (
			! empty($product)
			// &&
			// $product instanceof WC_Product
		) {
			$where_product_key = 'variation' === $product->get_type() ? 'variation_id' : 'product_id';
			$where[] = $where_product_key . ' = ' . $product->get_id();
		}

		if (is_email($email)) {
			$where[] = $wpdb->prepare('user_email = %s', $email);
		}

		if (! empty($user_id)) {
			$where[] = $wpdb->prepare('user_id = %d', $user_id);
		}

		if ($confirmed) {
			$where[] = $wpdb->prepare('confirmed = %d', 1);
		}

		if (! empty($lists)) {
			$where[] = 'subscription_id IN (' . implode(',', $lists) . ')';
		}

		$query = "SELECT * FROM $wpdb->blocksy_waitlists";

		if (! empty($where)) {
			$query .= ' WHERE ' . implode(' AND ', $where);
		}

		if ($page) {
			$items_per_page = abs(20);
			$offset  = ($page - 1) * $items_per_page;
			$query .= $wpdb->prepare(
				' LIMIT %d OFFSET %d',
				$items_per_page,
				$offset
			);
		}

		$waitlists = $wpdb->get_results($query); // phpcs:ignore.

		$waitlists = array_filter($waitlists, function ($waitlist) {
			if ($waitlist->variation_id) {
				$product = wc_get_product($waitlist->variation_id);
			} else {
				$product = wc_get_product($waitlist->product_id);
			}

			return !!$product;
		});

		return $waitlists;
	}

    public static function create_subscription($email, $product, $confirmed) {
		global $wpdb;

		$data = array_merge(
			self::get_product_ids_by_type($product),
			[
				'user_id' => get_current_user_id(),
				'user_email' => $email,
				'confirm_token' => wp_generate_password(24, false),
				'unsubscribe_token' => wp_generate_password(24, false),
				'created_date_gmt' => current_time('mysql', 1),
				'confirmed' => $confirmed
			]
		);

		$wpdb->insert($wpdb->blocksy_waitlists, $data); // phpcs:ignore.

		return [
			'subscription_id' => $wpdb->insert_id,
			'unsubscribe_token' => $data['unsubscribe_token'],
		];
	}

	public static function update_waitlist_data($product, $email, $data) {
		global $wpdb;

		$where = [
			'user_email' => $email,
		];

		$where_product_key = 'variation' === $product->get_type() ? 'variation_id' : 'product_id';
		$where[ $where_product_key ] = $product->get_id();

		$db_row = $wpdb->update( // phpcs:ignore.
			$wpdb->blocksy_waitlists,
			$data,
			$where
		);

		return $db_row ? $db_row : false;
	}

	public static function bulk_update_waitlist_data(
		$waitlist_ids,
		$data
	) {
		global $wpdb;

		$q = "UPDATE $wpdb->blocksy_waitlists SET ";
		$set = [];

		foreach ($data as $key => $value) {
			$set[] = "$key = '$value'";
		}

		$q .= implode(', ', $set);

		$q .= ' WHERE subscription_id IN (' . implode(',', $waitlist_ids) . ')';
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
		$wpdb->query($q);

		return $wpdb->last_error ? false : true;
	}

	public static function update_for_user($user_id) {
		$waitlists = ProductWaitlistDb::get_waitlists_from_cookies();

		if (empty($waitlists)) {
			return;
		}

		global $wpdb;

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$wpdb->update(
			$wpdb->blocksy_waitlists,
			['user_id' => $user_id],
			['user_email' => get_userdata($user_id)->user_email]
		);

		setcookie(ProductWaitlistDb::$cookie_name, false);
	}

    private static function get_product_ids_by_type($product) {
        $product = wc_get_product($product);

		$product_id = $product->get_id();

		if ($product->is_type('variation')) {
			$variation_id = $product_id;
			$product_id = $product->get_parent_id();
		} else {
			$variation_id = null;
		}

		return [
            'product_id' => $product_id,
			'variation_id' => $variation_id,
        ];
	}

    public static function define_tables() {
		global $wpdb;

		$wpdb->blocksy_waitlists = $wpdb->prefix . self::$db_name;
		$wpdb->tables[] = self::$db_name;
	}

    public static function install_db() {
		global $wpdb;

		if (! function_exists('dbDelta')) {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		}

		$charset_collate = $wpdb->get_charset_collate();
		$table_name      = $wpdb->prefix . self::$db_name;
		$sql             = "CREATE TABLE $table_name (
					subscription_id bigint(20) NOT NULL AUTO_INCREMENT,
					user_id bigint(20),
					user_email VARCHAR(100) NOT NULL,
					product_id bigint(20) NOT NULL,
					variation_id bigint(20),
					confirmed tinyint(1) NOT NULL DEFAULT 0,
					confirm_token VARCHAR(100),
					unsubscribe_token VARCHAR(100),
					created_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
					created_date_gmt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
					state VARCHAR(100) DEFAULT 'new',
					state_updated DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY (subscription_id),
					UNIQUE (confirm_token),
					UNIQUE (unsubscribe_token)
					) $charset_collate;";

        maybe_create_table($table_name, $sql);
	}

	public function upgrade_db() {
		global $wpdb;

		$column = 'state';
		$column_updated = 'state_updated';

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$wpdb->query("SHOW COLUMNS FROM $wpdb->blocksy_waitlists LIKE '$column'");

		if (! $wpdb->num_rows) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.SchemaChange
			$wpdb->query("ALTER TABLE $wpdb->blocksy_waitlists ADD COLUMN $column VARCHAR(100) DEFAULT 'new'");
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.SchemaChange
			$wpdb->query("ALTER TABLE $wpdb->blocksy_waitlists ADD COLUMN $column_updated DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP");
		}
	}

	public static function export_users_by_product($product_id) {
		global $wpdb;

		$waitlists = self::get_waitlists_from_db(wc_get_product($product_id));

		// Open a temporary file in memory to write CSV data
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
		$csv = fopen('php://temp', 'r+');

		if (!$csv) {
			return new WP_Error('csv_open_failed', 'Failed to open CSV file.');
		}

		$columns = [
			'Email',
			'First Name',
			'Last Name',
			'Confirmed',
			'Subscription Date',
		];

		// Write column headers to CSV
		fputcsv($csv, $columns);

		foreach ($waitlists as $waitlist) {
			$first_name = '';
			$last_name = '';

			if ($waitlist->user_id) {
				$user = get_userdata($waitlist->user_id);
				$first_name = $user->first_name;
				$last_name = $user->last_name;
			}

			fputcsv($csv, [
				$waitlist->user_email,
				$first_name,
				$last_name,
				$waitlist->confirmed ? 'Yes' : 'No',
				$waitlist->created_date,
			]);
		}

		$upload_dir = wp_upload_dir();
		$dir_path = $upload_dir['path'] . '/blc-waitlist-export/';
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$filename = 'waitlist-' . sanitize_title(get_the_title(isset($_POST['product_id']) ? absint($_POST['product_id']) : 0)) . '.csv';
		$full_file_path = $dir_path . $filename;

		// Check if the directory exists, and create it if it doesn't
		if (!file_exists($dir_path)) {
			if (!wp_mkdir_p($dir_path)) {
				return new WP_Error('directory_creation_failed', 'Failed to create directory: ' . $dir_path);
			}
		}

		// Open the file for writing
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
		$fp = fopen($full_file_path, 'w');
		if (!$fp) {
			return new WP_Error('file_open_failed', 'Failed to open file: ' . $full_file_path);
		}

		// Rewind the in-memory CSV data and write it to the file
		rewind($csv);
		while (($line = fgets($csv)) !== false) {
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fwrite
			fwrite($fp, $line);
		}

		// Close the file handles
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
		fclose($csv);
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
		fclose($fp);

		// Return the file URL
		return $upload_dir['url'] . '/blc-waitlist-export/' . $filename;
	}

	public static function unsubscribe_by_product($product_id, $user_email = '') {
		global $wpdb;

		$product = wc_get_product($product_id);
		$where_product_key = 'variation' === $product->get_type() ? 'variation_id' : 'product_id';

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$wpdb->delete(
			$wpdb->blocksy_waitlists,
			array_merge(
				[
					$where_product_key => $product_id
				],
				! empty($user_email) ? ['user_email' => $user_email] : []
			)
		);
	}
}
