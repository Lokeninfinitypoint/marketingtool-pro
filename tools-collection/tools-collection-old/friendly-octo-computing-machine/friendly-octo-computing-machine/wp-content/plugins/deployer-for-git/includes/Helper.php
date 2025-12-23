<?php
namespace DeployerForGit;

/**
 * Helper class
 */
class Helper {

	/**
	 * Get the plugin name
	 *
	 * @return string
	 */
	public static function menu_slug() {
		return 'deployer-for-git';
	}

	/**
	 * Get the theme install page url
	 *
	 * @return string
	 */
	public static function install_theme_url() {
		$menu_slug             = self::menu_slug();
		$path                  = "admin.php?page={$menu_slug}-install-theme";
		$theme_istall_page_url = is_multisite() ? network_admin_url( $path ) : admin_url( $path );

		return $theme_istall_page_url;
	}

	/**
	 * Get the plugin install page url
	 *
	 * @return string
	 */
	public static function install_plugin_url() {
		$menu_slug              = self::menu_slug();
		$path                   = "admin.php?page={$menu_slug}-install-plugin";
		$plugin_istall_page_url = is_multisite() ? network_admin_url( $path ) : admin_url( $path );

		return $plugin_istall_page_url;
	}

	/**
	 * Get the available providers
	 *
	 * @return array
	 */
	public static function available_providers() {
		return array(
			'bitbucket' => 'Bitbucket',
			'github'    => 'GitHub',
			'gitea'     => 'Gitea',
			'gitlab'    => 'GitLab',
		);
	}

	/**
	 * Get the provider class
	 *
	 * @param  string $provider Provider name.
	 * @return string
	 */
	public static function get_provider_class( $provider ) {
		// phpcs:disable Squiz.PHP.NonExecutableCode.Unreachable
		switch ( $provider ) {
			case 'bitbucket':
				return '\DeployerForGit\Providers\BitbucketProvider';
			break;
			case 'github':
				return '\DeployerForGit\Providers\GithubProvider';
			break;
			case 'gitea':
				return '\DeployerForGit\Providers\GiteaProvider';
			break;
			case 'gitlab':
				return '\DeployerForGit\Providers\GitlabProvider';
			break;

			default:
				return '\DeployerForGit\Providers\BitbucketProvider';
			break;
		}
		// phpcs:enable Squiz.PHP.NonExecutableCode.Unreachable
	}

	/**
	 * Generate the api secret
	 *
	 * @return string
	 */
	public static function generate_api_secret() {
		$key = bin2hex( random_bytes( 32 ) );
		return update_option( 'deployer_for_git_api_secret', $key );
	}

	/**
	 * Get the api secret
	 *
	 * @return string|boolean false
	 */
	public static function get_api_secret() {
		return get_option( 'deployer_for_git_api_secret', false );
	}

	/**
	 * Check if WpRocket plugin is activated.
	 *
	 * @return boolean
	 */
	public static function wp_rocket_activated() {
		return function_exists( 'rocket_clean_domain' );
	}

	/**
	 * Check if WP Optimize plugin is activated.
	 *
	 * @return boolean
	 */
	public static function wp_optimize_activated() {
		return class_exists( 'WP_Optimize' );
	}

	/**
	 * Check if W3 Total Cache plugin is activated.
	 *
	 * @return boolean
	 */
	public static function w3tc_activated() {
		return function_exists( 'w3tc_pgcache_flush' );
	}

	/**
	 * Check if LiteSpeed Cache plugin is activated.
	 *
	 * @return boolean
	 */
	public static function litespeed_cache_activated() {
		return has_action( 'litespeed_purge_all' );
	}

	/**
	 * Check if WP Super Cache plugin is activated.
	 *
	 * @return boolean
	 */
	public static function wp_super_cache_activated() {
		return function_exists( 'wp_cache_clear_cache' );
	}

	/**
	 * Check if WP Fastest Cache plugin is activated.
	 *
	 * @return boolean
	 */
	public static function wp_fastest_cache_activated() {
		return ( isset( $GLOBALS['wp_fastest_cache'] ) && method_exists( $GLOBALS['wp_fastest_cache'], 'deleteCache' ) );
	}

	/**
	 * Check if Autoptimize plugin is activated.
	 *
	 * @return boolean
	 */
	public static function autoptimize_activated() {
		return method_exists( 'autoptimizeCache', 'clearall' );
	}

	/**
	 * Trigger cache flush
	 *
	 * @return void
	 */
	public static function trigger_cache_flush() {
		if ( self::wp_rocket_activated() ) {
			\rocket_clean_domain();
		}

		if ( self::wp_optimize_activated() ) {
			if ( method_exists( 'WP_Optimize', 'get_page_cache' ) && method_exists( \WP_Optimize()->get_page_cache(), 'purge' ) ) {
				\WP_Optimize()->get_page_cache()->purge();
			}
		}

		if ( self::w3tc_activated() ) {
			\w3tc_pgcache_flush();
		}

		if ( self::litespeed_cache_activated() ) {
			do_action( 'litespeed_purge_all' );
		}

		if ( self::wp_super_cache_activated() ) {
			\wp_cache_clear_cache();
		}

		if ( self::wp_fastest_cache_activated() ) {
			$GLOBALS['wp_fastest_cache']->deleteCache( true );
		}

		if ( self::autoptimize_activated() ) {
			\autoptimizeCache::clearall();
		}
	}

	/**
	 * Check if GitHub token is a fine-grained personal access token
	 *
	 * @param string $token GitHub token.
	 * @return boolean
	 */
	public static function is_github_pat_token( $token ) {
		return str_contains( $token, 'github_pat_' );
	}
}
