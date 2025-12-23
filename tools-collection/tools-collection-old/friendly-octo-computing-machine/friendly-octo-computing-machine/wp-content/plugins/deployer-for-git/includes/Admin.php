<?php
namespace DeployerForGit;

use DeployerForGit\Subpages\DashboardPage\Dashboard;
use DeployerForGit\Subpages\InstallThemePage\InstallTheme;
use DeployerForGit\Subpages\InstallPluginPage\InstallPlugin;
use DeployerForGit\Subpages\LogsPage\Logs;
use DeployerForGit\Subpages\MiscellaneousPage\Miscellaneous;

/**
 * Class Admin
 *
 * @package DeployerForGit
 */
class Admin {
	/**
	 * Admin constructor.
	 */
	public function __construct() {
		$this->init_dashboard_page();
		$this->init_install_theme_page();
		$this->init_install_plugin_page();
		$this->init_logs_page();
		$this->init_miscellaneous_page();

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( is_multisite() ? 'network_admin_notices' : 'admin_notices', array( $this, 'display_alert_notification_box' ) );
		add_filter( 'admin_footer_text', array( $this, 'admin_footer' ), 1, 2 );
	}

	/**
	 * Initialize dashboard page
	 */
	private function init_dashboard_page() {
		new Dashboard();
	}

	/**
	 * Initialize install theme page
	 */
	public function init_install_theme_page() {
		new InstallTheme();
	}

	/**
	 * Initialize install plugin page
	 */
	private function init_install_plugin_page() {
		new InstallPlugin();
	}

	/**
	 * Initialize miscellaneous page
	 */
	private function init_miscellaneous_page() {
		new Miscellaneous();
	}

	/**
	 * Initialize logs page
	 */
	private function init_logs_page() {
		new Logs();
	}

	/**
	 * Display alert notification box
	 */
	public function display_alert_notification_box() {
		$data_manager               = new DataManager();
		$alert_notification_setting = $data_manager->get_alert_notification_setting();

		if ( $alert_notification_setting ) {
			$menu_slug     = Helper::menu_slug();
			$dashboard_url = is_multisite() ? network_admin_url( "admin.php?page={$menu_slug}" ) : menu_page_url( $menu_slug, false );

			// translators: %s = dashboard url.
			$html = wp_kses_post( __( '<strong>Warning:</strong> Please do not make any changes to the theme or plugin code directly because some themes or plugins are connected with Git.<br>Click <a href="%s">here</a> to learn more.', 'deployer-for-git' ) );
			$html = sprintf( $html, esc_url( $dashboard_url ) );

			$html = apply_filters( 'dfg_alert_notification_message', $html );

			$html = "<div class='dfg_alert_box' >{$html}</div>";

			echo wp_kses(
				$html,
				array(
					'a'      => array(
						'href'  => array(),
						'title' => array(),
					),
					'br'     => array(),
					'strong' => array(),
					'div'    => array(
						'class' => array(),
					),
				)
			);
		}
	}

	/**
	 * Enqueue scripts
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'dfg-styles', DFG_URL . 'assets/css/deployer-for-git-main.css', array(), DFG_VERSION );

		wp_enqueue_script( 'dfg-script', DFG_URL . 'assets/js/deployer-for-git-main.js', array(), DFG_VERSION, true );

		wp_localize_script(
			'dfg-script',
			'dfg',
			array(
				'copy_url_label'         => __( 'Copy URL', 'deployer-for-git' ),
				'copied_url_label'       => __( 'Copied!', 'deployer-for-git' ),
				'updating_now_label'     => __( 'Updating now...', 'deployer-for-git' ),
				'error_label'            => __( 'Something went wrong', 'deployer-for-git' ),
				'update_completed_label' => __( 'Updated!', 'deployer-for-git' ),
				'update_theme_label'     => __( 'Update Theme', 'deployer-for-git' ),
				'update_plugin_label'    => __( 'Update Plugin', 'deployer-for-git' ),
			)
		);
	}

	/**
	 * When user is on a plugin related admin page, display footer text.
	 *
	 * @param string $text Footer text.
	 *
	 * @return string
	 */
	public function admin_footer( $text ) {

		global $current_screen;

		if ( ! empty( $current_screen->id ) && strpos( $current_screen->id, 'deployer-for-git' ) !== false ) {
			$url  = 'https://wordpress.org/support/plugin/deployer-for-git/reviews/?filter=5#new-post';
			$text = sprintf(
				wp_kses( /* translators: $1$s - Deployer for Git plugin name; $2$s - WP.org review link; $3$s - WP.org review link. */
					__( 'Please rate %1$s <a href="%2$s" target="_blank" rel="noopener noreferrer">&#9733;&#9733;&#9733;&#9733;&#9733;</a> on <a href="%3$s" target="_blank" rel="noopener">WordPress.org</a> to support and promote our solution. Thank you very much!', 'deployer-for-git' ),
					array(
						'a' => array(
							'href'   => array(),
							'target' => array(),
							'rel'    => array(),
						),
					)
				),
				'<strong>Deployer for Git</strong>',
				$url,
				$url
			);
		}

		return $text;
	}
}
