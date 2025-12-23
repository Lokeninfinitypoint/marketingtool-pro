<?php

namespace DeployerForGit;

/**
 * Logger class
 */
class Logger {

	/**
	 * Log directory
	 *
	 * @var string
	 */
	private $log_directory;

	/**
	 * Constructor
	 */
	public function __construct() {
		$upload_dir          = wp_upload_dir();
		$logs_directory_name = DFG_SLUG . '-logs';
		$this->log_directory = $upload_dir['basedir'] . '/' . $logs_directory_name;

		$this->create_log_directory();
		$this->create_htaccess();
	}

	/**
	 * Log message to log file
	 *
	 * @param  string $message Message to log.
	 */
	public function log( $message ) {
		// Load the WordPress filesystem API.
		global $wp_filesystem;

		// Make sure the filesystem is loaded and ready for use.
		if ( ! is_object( $wp_filesystem ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
			WP_Filesystem();
		}

		// Check if the filesystem is ready for use.
		if ( ! is_object( $wp_filesystem ) ) {
			// Filesystem couldn't be loaded, handle the error or fallback to alternative methods.
			return;
		}

		$log_file_path      = $this->log_directory . DIRECTORY_SEPARATOR . 'plugin.log';
		$log_message_string = '[' . gmdate( 'Y-m-d H:i:s' ) . '] ' . $message . PHP_EOL;

		// Read the existing content of the file.
		$existing_content = $wp_filesystem->get_contents( $log_file_path );

		// Concatenate the new log message to the existing content.
		$updated_content = $existing_content . $log_message_string;

		// Use WP_Filesystem method to write the updated content back to the file.
		$wp_filesystem->put_contents( $log_file_path, $updated_content );

		do_action( 'dfg_after_log', $log_message_string );
	}

	/**
	 * Display log content
	 *
	 * @return string Log content
	 */
	public function display_log_content() {
		$log_file_path = $this->log_directory . DIRECTORY_SEPARATOR . 'plugin.log';
		if ( file_exists( $log_file_path ) ) {

			if ( ! class_exists( 'WP_Filesystem_Direct' ) ) {
				require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
				require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
			}

			$filesystem  = new \WP_Filesystem_Direct( true );
			$log_content = $filesystem->get_contents( $log_file_path );

			return $log_content;
		} else {
			return esc_attr__( 'Log file not found.', 'deployer-for-git' );
		}
	}

	/**
	 * Create log file
	 *
	 * @return void
	 */
	private function create_htaccess() {
		$htaccess_file_path = $this->log_directory . '/.htaccess';

		if ( ! file_exists( $htaccess_file_path ) ) {
			$content = 'Deny from all';

			if ( ! class_exists( 'WP_Filesystem_Direct' ) ) {
				require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
				require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
			}

			$filesystem = new \WP_Filesystem_Direct( true );
			$filesystem->put_contents( $htaccess_file_path, $content );
		}
	}

	/**
	 * Create log directory
	 *
	 * @return void
	 */
	private function create_log_directory() {
		if ( ! is_dir( $this->log_directory ) ) {
			mkdir( $this->log_directory, 0755, true );
		}
	}

	/**
	 * Clear log file
	 *
	 * @return boolean
	 */
	public function clear_log_file() {
		$log_file_path = $this->log_directory . '/plugin.log';
		$result        = false;
		if ( file_exists( $log_file_path ) ) {
			if ( ! class_exists( 'WP_Filesystem_Direct' ) ) {
				require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
				require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
			}

			$filesystem = new \WP_Filesystem_Direct( true );
			$result     = $filesystem->put_contents( $log_file_path, '' );
		}

		return $result;
	}
}
