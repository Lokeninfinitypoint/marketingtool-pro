<?php
namespace DeployerForGit\Subpages\MiscellaneousPage;

use DeployerForGit\DataManager;
use DeployerForGit\Helper;

/**
 * Miscellaneous page
 */
class Miscellaneous {

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
		$menu_slug  = Helper::menu_slug();
		$capability = is_multisite() ? 'manage_network_options' : 'manage_options';

		add_submenu_page(
			$menu_slug,
			esc_attr__( 'Miscellaneous', 'deployer-for-git' ),
			esc_attr__( 'Miscellaneous', 'deployer-for-git' ),
			$capability,
			"{$menu_slug}-miscellaneous",
			array( $this, 'init_page' )
		);
	}

	/**
	 * Initialize page
	 *
	 * @return void
	 */
	public function init_page() {
		$regenerate_secret_key_result = $this->handle_regenerate_secret_key_form();
		$flush_cache_result           = $this->handle_flush_cache_form();
		$alert_notification_result    = $this->handle_alert_notification_form();
		$data_manager                 = new DataManager();

		include_once __DIR__ . '/template.php';
	}

	/**
	 * Handle regenerate secret key form
	 *
	 * @return boolean|null
	 */
	public function handle_regenerate_secret_key_form() {
		$result = null;

		if ( isset( $_POST['action'] ) && $_POST['action'] === 'regenerate_secret_key' ) {
			$result = false;

			if ( isset( $_POST[ DFG_SLUG . '_nonce' ] ) || wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ DFG_SLUG . '_nonce' ] ) ), DFG_SLUG . '_regenerate_secret_key' ) ) {
				$result = true;
				Helper::generate_api_secret();

				do_action( 'dfg_after_secret_key_regenerate' );
			}
		}

		return $result;
	}

	/**
	 * Handle flush cache form
	 *
	 * @return boolean|null
	 */
	public function handle_flush_cache_form() {
		$result = null;

		if ( isset( $_POST['action'] ) && $_POST['action'] === 'flush_cache' ) {
			$result = false;

			if ( isset( $_POST[ DFG_SLUG . '_nonce' ] ) || wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ DFG_SLUG . '_nonce' ] ) ), DFG_SLUG . '_flush_cache' ) ) {
				$data_manager = new DataManager();

				$result = $data_manager->update_flush_cache_setting( isset( $_POST['flush_cache_setting'] ) );

				do_action( 'dfg_after_flush_cache_setting_update' );
			}
		}

		return $result;
	}

	/**
	 * Handle alert notification form
	 *
	 * @return boolean|null
	 */
	public function handle_alert_notification_form() {
		$result = null;

		if ( isset( $_POST['action'] ) && $_POST['action'] === 'alert_notification' ) {
			$result = false;

			if ( isset( $_POST[ DFG_SLUG . '_nonce' ] ) || wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ DFG_SLUG . '_nonce' ] ) ), DFG_SLUG . '_alert_notification' ) ) {
				$data_manager = new DataManager();

				$result = $data_manager->update_alert_notification_setting( isset( $_POST['alert_notification_setting'] ) );

				do_action( 'dfg_after_alert_notification_setting_update' );
			}
		}

		return $result;
	}
}
