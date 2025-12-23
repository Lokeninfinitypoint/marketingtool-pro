<?php
namespace DeployerForGit\Subpages\LogsPage;

use DeployerForGit\DataManager;
use DeployerForGit\Logger;

/**
 * Logs page
 */
class Logs {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( is_multisite() ? 'network_admin_menu' : 'admin_menu', array( $this, 'init_menu' ) );
	}

	/**
	 * Initialize menu
	 *
	 * @return void
	 */
	public function init_menu() {
		$menu_slug  = \DeployerForGit\Helper::menu_slug();
		$capability = is_multisite() ? 'manage_network_options' : 'manage_options';

		add_submenu_page(
			$menu_slug,
			esc_attr__( 'Logs', 'deployer-for-git' ),
			esc_attr__( 'Logs', 'deployer-for-git' ),
			$capability,
			"{$menu_slug}-logs",
			array( $this, 'init_page' )
		);
	}

	/**
	 * Initialize page
	 *
	 * @return void
	 */
	public function init_page() {
		$data_manager = new DataManager();
		$this->handle_clear_log_form();

		include_once __DIR__ . '/template.php';
	}

	/**
	 * Handle clear log form
	 *
	 * @return void
	 */
	public function handle_clear_log_form() {
		$form_submitted = false;

		if ( isset( $_POST[ DFG_SLUG . '_nonce' ] ) && wp_verify_nonce( ( sanitize_text_field( wp_unslash( $_POST[ DFG_SLUG . '_nonce' ] ) ) ), DFG_SLUG . '_clear_log_file' ) ) {
			$logger           = new Logger();
			$clear_log_result = $logger->clear_log_file();
			$form_submitted   = true;
		}
	}
}
