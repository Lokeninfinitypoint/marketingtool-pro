<?php

namespace DeployerForGit\Subpages;

use DeployerForGit\DataManager;
use DeployerForGit\Logger;
use DeployerForGit\Helper;
/**
 * Class InstallPackage
 *
 * @package Wp_Git_Deployer
 */
class InstallPackage {
    /**
     * Init package menu hook.
     */
    public function __construct() {
        add_action( ( is_multisite() ? 'network_admin_menu' : 'admin_menu' ), array($this, 'init_menu') );
    }

    /**
     * Initializes the menu.
     */
    public function init_menu() {
    }

    /**
     * Validates the package installation form.
     */
    private function validate_install_package_form() {
        if ( !isset( $_POST[DFG_SLUG . '_install_package_submitted'] ) ) {
            return;
        }
        // Vefiry form nonce.
        $nonce = ( isset( $_POST[DFG_SLUG . '_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST[DFG_SLUG . '_nonce'] ) ) : '' );
        if ( !wp_verify_nonce( $nonce, DFG_SLUG . '_install_package_form' ) ) {
            return new \WP_Error('invalid', __( 'Invalid nonce', 'deployer-for-git' ));
        }
        $provider_type = ( isset( $_POST['provider_type'] ) ? sanitize_text_field( wp_unslash( $_POST['provider_type'] ) ) : '' );
        // Check if provider type is valid.
        if ( !in_array( $provider_type, array_keys( Helper::available_providers() ), true ) ) {
            return new \WP_Error('invalid', __( 'Invalid provider type', 'deployer-for-git' ));
        }
        return true;
    }

    /**
     * Handles the package installation form.
     *
     * @return WP_Error|bool Returns WP_Error object for failure or true for success.
     */
    public function handle_package_install_form() {
        // Validate form.
        $validation_result = $this->validate_install_package_form();
        if ( $validation_result !== true ) {
            return $validation_result;
        }
        // phpcs:disable WordPress.Security.NonceVerification.Missing
        // Sanitize form data.
        $package_type = ( isset( $_POST['package_type'] ) ? sanitize_text_field( wp_unslash( $_POST['package_type'] ) ) : '' );
        $package_type = ( $package_type === 'plugin' ? 'plugin' : 'theme' );
        $provider_type = ( isset( $_POST['provider_type'] ) ? sanitize_text_field( wp_unslash( $_POST['provider_type'] ) ) : '' );
        $branch = ( isset( $_POST['repository_branch'] ) && !empty( $_POST['repository_branch'] ) ? sanitize_text_field( wp_unslash( $_POST['repository_branch'] ) ) : 'master' );
        $repository_url = ( isset( $_POST['repository_url'] ) ? esc_url_raw( wp_unslash( $_POST['repository_url'] ) ) : '' );
        $is_private_repository = isset( $_POST['is_private_repository'] );
        $username = ( isset( $_POST['username'] ) ? sanitize_text_field( wp_unslash( $_POST['username'] ) ) : '' );
        $password = ( isset( $_POST['password'] ) ? sanitize_text_field( wp_unslash( $_POST['password'] ) ) : '' );
        $access_token = ( isset( $_POST['access_token'] ) ? sanitize_text_field( wp_unslash( $_POST['access_token'] ) ) : '' );
        // phpcs:enable WordPress.Security.NonceVerification.Missing
        $package_install_options = array();
        if ( $is_private_repository ) {
            $package_install_options = array(
                'is_private_repository' => true,
                'username'              => $username,
                'password'              => $password,
                'access_token'          => $access_token,
            );
        }
        $provider_class_name = Helper::get_provider_class( $provider_type );
        $logger = new Logger();
        try {
            $provider = new $provider_class_name($repository_url);
            $package_slug = $provider->get_package_slug();
            $package_zip_url = $provider->get_zip_repo_url( $branch );
            // If the provider is GitHub, we need to do some additional checks.
            if ( $provider instanceof \DeployerForGit\Providers\GithubProvider ) {
                $github_token = ( isset( $package_install_options['access_token'] ) ? $package_install_options['access_token'] : '' );
                $package_zip_url = $provider->get_zip_repo_url( $branch, $github_token );
            }
            $install_result = self::install_package_from_zip_url(
                $package_zip_url,
                $package_slug,
                $package_type,
                $provider_type,
                $package_install_options
            );
            // TODO: maybe move this piece to a method?
            if ( $install_result === true ) {
                $data_manager = new DataManager();
                $package_data = array(
                    'slug'                  => $package_slug,
                    'repo_url'              => $repository_url,
                    'branch'                => $branch,
                    'provider'              => $provider_type,
                    'is_private_repository' => $is_private_repository,
                    'options'               => array(
                        'username'     => $username,
                        'password'     => $password,
                        'access_token' => $access_token,
                    ),
                );
                $data_manager->store_package_details( $package_data, $package_type );
                $logger->log( "Package ({$package_type}) \"{$package_slug}\" successfully updated or installed via wp-admin" );
            } else {
                $logger->log( "Error occured while installing a {$package_type} \"{$package_slug}\" via wp-admin \"{$install_result->get_error_message()}\"" );
            }
        } catch ( \Exception $e ) {
            // Invalid repository URL.
            $install_result = new \WP_Error('invalid', $e->getMessage());
            $logger->log( "Error occured while installing a package via wp-admin \"{$e->getMessage()}\"" );
        }
        $success = $install_result === true;
        do_action( 'dfg_after_package_install', $success );
        return $install_result;
    }

    /**
     * Initializes the page.
     */
    public function init_page() {
    }

    /**
     * Checks if the request is a WP JSON request.
     *
     * @param array $options Additional options for the installation.
     */
    private static function is_wp_json_request( $options = array() ) {
        return array_key_exists( 'wp_json_request', $options ) && $options['wp_json_request'] === true;
    }

    /**
     * Checks if the current user can proceed with the installation.
     *
     * @param string $package_type Type of the package (theme|plugin).
     */
    private static function current_user_can_proceed( $package_type ) {
        $type_in_plural = "{$package_type}s";
        return current_user_can( "install_{$type_in_plural}" );
    }

    /**
     * Installs a package from a zip file URL.
     *
     * @param string $package_zip_url The URL of the package zip file.
     * @param string $package_slug The slug of the package.
     * @param string $type Type of the package (theme|plugin).
     * @param string $provider_type Type of the provider (bitbucket|github|gitlab|gitea).
     * @param array  $options Additional options for the installation.
     *
     * @return WP_Error|bool Returns WP_Error object for failure or true for success.
     */
    public static function install_package_from_zip_url(
        $package_zip_url,
        $package_slug,
        $type,
        $provider_type,
        $options = array()
    ) {
        if ( empty( $provider_type ) ) {
            return new \WP_Error('invalid', __( 'Provider does not exist.', 'deployer-for-git' ));
        }
        // Check if provider type is valid.
        if ( !in_array( $provider_type, array_keys( Helper::available_providers() ), true ) ) {
            return new \WP_Error('invalid', __( 'Invalid provider type', 'deployer-for-git' ));
        }
        $type = ( $type === 'theme' ? 'theme' : 'plugin' );
        // Check for empty URL and slug.
        if ( empty( $package_zip_url ) || empty( $package_slug ) ) {
            return new \WP_Error('invalid', __( 'Package zip URL and package slug must exist', 'deployer-for-git' ));
        }
        $is_wp_json_request = self::is_wp_json_request( $options );
        // Check user capabilities.
        if ( !self::current_user_can_proceed( $type ) && !$is_wp_json_request ) {
            return new \WP_Error('invalid', __( 'User should have enough permissions', 'deployer-for-git' ));
        }
        // Initialize WordPress filesystem.
        if ( !function_exists( 'WP_Filesystem' ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        WP_Filesystem();
        // load file which loads most of the classes needed for package installation.
        require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        // Set up package installation parameters.
        if ( $type === 'theme' ) {
            $package_destination_dir = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . $package_slug;
            $skin = new \DeployerForGit\ThemeInstallerSkin();
            // Create a new instance of the Theme_Upgrader class.
            $package_upgrader = new \Theme_Upgrader($skin);
        } else {
            $package_destination_dir = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $package_slug;
            $skin = new \DeployerForGit\PluginInstallerSkin();
            // Create a new instance of the Plugin_Upgrader class.
            $package_upgrader = new \Plugin_Upgrader($skin);
        }
        // Set upgrader arguments.
        $package_upgrader->generic_strings();
        // Run the package installation.
        $result = $package_upgrader->run( array(
            'package'           => $package_zip_url,
            'destination'       => $package_destination_dir,
            'clear_destination' => true,
            'clear_working'     => true,
            'hook_extra'        => array(
                'type'   => $type,
                'action' => 'install',
            ),
        ) );
        $package_upgrader->maintenance_mode( false );
        if ( $result === false ) {
            return new \WP_Error('invalid', __( 'Some error occurred', 'deployer-for-git' ));
        } elseif ( is_wp_error( $result ) ) {
            return $result;
        }
        return true;
    }

}
