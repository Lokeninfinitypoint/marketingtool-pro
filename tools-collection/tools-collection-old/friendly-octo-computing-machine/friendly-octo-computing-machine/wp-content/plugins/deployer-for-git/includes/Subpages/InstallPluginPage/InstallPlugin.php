<?php

namespace DeployerForGit\Subpages\InstallPluginPage;

use DeployerForGit\Subpages\InstallPackage;

/**
 * Class InstallPlugin
 *
 * @package DeployerForGit\Subpages\InstallPluginPage
 */
class InstallPlugin extends InstallPackage {

	/**
	 * Initializes the menu.
	 */
	public function init_menu() {
		$menu_slug  = \DeployerForGit\Helper::menu_slug();
		$page_title = esc_attr__( 'Install Plugin', 'deployer-for-git' );
		$capability = is_multisite() ? 'manage_network_options' : 'manage_options';

		add_submenu_page(
			$menu_slug,
			$page_title,
			$page_title,
			$capability,
			"{$menu_slug}-install-plugin",
			array( $this, 'init_page' )
		);
	}

	/**
	 * Installs a plugin from a zip file.
	 *
	 * @param string $package_zip_url The URL of the zip file.
	 * @param string $package_slug The slug of the package.
	 * @param string $package_provider The provider of the package (github, bitbucket, gitlab, gitea).
	 * @param array  $options The options.
	 * @return WP_Error|bool Returns WP_Error object for failure or true for success.
	 */
	public static function install_plugin_from_zip_url( $package_zip_url, $package_slug, $package_provider, $options = array() ) {
		return parent::install_package_from_zip_url( $package_zip_url, $package_slug, 'plugin', $package_provider, $options );
	}

	/**
	 * Initializes the page.
	 */
	public function init_page() {
		// Handle package installation form.
		$install_result = parent::handle_package_install_form();

		include_once __DIR__ . '/template.php';
	}
}
