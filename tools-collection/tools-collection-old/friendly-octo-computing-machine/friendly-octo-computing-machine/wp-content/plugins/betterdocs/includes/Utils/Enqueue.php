<?php
namespace WPDeveloper\BetterDocs\Utils;

class Enqueue extends Base {
	private $plugin_url;
	private $plugin_path;
	private $version;

	public function __construct( $plugin_url, $plugin_path, $version ) {
		$this->plugin_url  = $plugin_url;
		$this->plugin_path = $plugin_path;
		$this->version     = $version;
	}

	public function enqueue( $handle, $filename, $dependencies = [], $args = null ) {
		$config = $this->asset_config( $filename, $dependencies, $args );
		$this->call_wp_func( 'wp_enqueue', $handle, $config );
	}

	public function register( $handle, $filename, $dependencies = [], $args = null ) {
		$config = $this->asset_config( $filename, $dependencies, $args );
		$this->call_wp_func( 'wp_register', $handle, $config );
	}

	public function localize( $handle, $name, $args ) {
		wp_localize_script( $handle, $name, $args );
	}

	private function call_wp_func( $action, $handle, $config ) {
		call_user_func(
			$action . '_' . $config['type'],
			$handle,
			$config['url'],
			$config['dependencies'],
			$config['version'],
			$config['args']
		);

		if ( 'script' === $config['type'] && in_array( 'wp-i18n', $config['dependencies'], true ) ) {
			$url = isset( $config['url'] ) ? $config['url'] : '';
			preg_match( '~/plugins/([^/]+)~', $url, $matches );
			$url_portion = isset( $matches[1] ) ? $matches[1] : '';
			if ( $url_portion == 'betterdocs-pro' ) {
				wp_set_script_translations( $handle, 'betterdocs-pro' );
			} elseif ( $url_portion == 'betterdocs-ai-chatbot' ) {
				wp_set_script_translations( $handle, 'betterdocs-ai-chatbot' );
			} elseif ( $url_portion == 'betterdocs' ) {
				wp_set_script_translations( $handle, 'betterdocs' );
			}
		}
	}

	public function asset_config( $filename, $dependencies = [], $args = null ) {
		$is_js             = preg_match( '/\.js$/', $filename );
		$basename          = preg_replace( '/\.\w+$/', '', $filename );
		$url               = $this->asset_url( $filename );
		$version           = $this->version;
		$asset_config_path = $this->dist_path( $basename . '.asset.php' );

		if ( file_exists( $asset_config_path ) ) {
			$asset_config = require $asset_config_path;

			if ( $is_js ) {
				$dependencies = array_unique( array_merge( $asset_config['dependencies'], $dependencies ) );
			}
			$version = $asset_config['version'];
		}

		return [
			'url'          => $url,
			'dependencies' => $dependencies,
			'version'      => $version,
			'type'         => $is_js ? 'script' : 'style',
			'args'         => null !== $args ? $args : ( $is_js ? true : 'all' )
		];
	}

	public function asset_url( $filename ) {
		if ( filter_var( $filename, FILTER_VALIDATE_URL ) ) {
			return $filename;
		}

		return esc_url( $this->plugin_url . 'assets/' . $filename );
	}

	public function dist_path( $file ) {
		return path_join( $this->plugin_path, 'assets/' . $file );
	}

	public function icon( $name, $is_admin = false ) {
		$_path = $is_admin ? 'assets/admin/images/' : 'assets/images/';
		return esc_url( $this->plugin_url . $_path . $name . '?v=' . $this->version );
	}
}
