<?php
namespace DeployerForGit;

/**
 * Data Manager class
 */
class DataManager {

	/**
	 * Update flush cache setting
	 *
	 * @param  boolean $enabled true if need to enable, false otherwise.
	 * @return boolean
	 */
	public function update_flush_cache_setting( $enabled = false ) {
		$option_name = $this->get_flush_cache_option_name();

		$string_value = $enabled ? '1' : '0';

		return update_option( $option_name, $string_value );
	}

	/**
	 * Update alert notification setting
	 *
	 * @param  boolean $enabled true if need to enable, false otherwise.
	 * @return boolean
	 */
	public function update_alert_notification_setting( $enabled = false ) {
		$option_name = $this->get_alert_notification_option_name();

		$string_value = $enabled ? '1' : '0';

		return update_option( $option_name, $string_value );
	}

	/**
	 * Get flush cache option name
	 *
	 * @return boolean, false if not enabled, true if enabled
	 */
	public function get_flush_cache_setting() {
		$option_name  = $this->get_flush_cache_option_name();
		$option_value = get_option( $option_name, '0' );

		$bool_result = ( $option_value === '0' ) ? false : true;

		return $bool_result;
	}

	/**
	 * Get alert notification option name
	 *
	 * @return boolean
	 */
	public function get_alert_notification_setting() {
		$option_name  = $this->get_alert_notification_option_name();
		$option_value = get_option( $option_name, '0' );

		$bool_result = ( $option_value === '0' ) ? false : true;

		return $bool_result;
	}

	/**
	 * Method which will reverify the theme saved in database and will remove if it's not exist.
	 */
	private function reverify_theme_lists() {
		return $this->reverify_package_lists( 'theme' );
	}

	/**
	 * Method which will reverify the plugin saved in database and will remove if it's not exist.
	 */
	private function reverify_plugin_lists() {
		return $this->reverify_package_lists( 'plugin' );
	}

	/**
	 * Method which will reverify the theme details.
	 *
	 * @param string $type Type of package (theme or plugin).
	 * @return void
	 */
	private function reverify_package_lists( $type = 'theme' ) {
		$type = ( $type === 'theme' ) ? 'theme' : 'plugin';

		if ( $type === 'theme' ) {
			$option_name = $this->get_themes_option_name();
		} else {
			$option_name = $this->get_plugins_option_name();
		}

		$package_list = get_option( $option_name, array() );

		foreach ( $package_list as $package_data ) {
			if ( $type === 'theme' ) {
				$theme = wp_get_theme( $package_data['slug'] );

				if ( ! $theme->exists() ) {
					$this->remove_package_details( $package_data['slug'], 'theme' );
				}
			} else {
				$plugin_path = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $package_data['slug'];

				if ( ! file_exists( $plugin_path ) ) {
					$this->remove_package_details( $package_data['slug'], 'plugin' );
				}
			}
		}
	}

	/**
	 * Method which will return the theme details.
	 *
	 * @param string $slug Theme slug.
	 * @param string $type Type of package (theme or plugin).
	 * @return array|bool Array of theme details if found, false otherwise.
	 */
	private function get_package( $slug = '', $type = 'theme' ) {
		$type = ( $type === 'theme' ) ? 'theme' : 'plugin';

		if ( $type === 'theme' ) {
			$package_list = $this->get_theme_list();
		} else {
			$package_list = $this->get_plugin_list();
		}

		foreach ( $package_list as $package_details ) {
			// If the theme_stylesheet matches the one we're looking to update.
			if ( $package_details['slug'] === $slug ) {

				return $package_details;
			}
		}

		return false;
	}

	/**
	 * Method which will return the theme details.
	 *
	 * @param string $theme_slug Theme slug.
	 * @return array|bool Array of theme details if found, false otherwise.
	 */
	public function get_theme( $theme_slug = '' ) {
		return $this->get_package( $theme_slug, 'theme' );
	}

	/**
	 * Method which will return the plugin details.
	 *
	 * @param string $plugin_slug Plugin slug.
	 * @return array|bool Array of plugin details if found, false otherwise.
	 */
	public function get_plugin( $plugin_slug = '' ) {
		return $this->get_package( $plugin_slug, 'plugin' );
	}

	/**
	 * Method which will return the list of all stored theme details.
	 *
	 * @return array Array of all stored theme details.
	 */
	public function get_theme_list() {
		// synchronize existing themes with plugin options.
		$this->reverify_theme_lists();

		// Get all stored theme details.
		$option_name = $this->get_themes_option_name();
		return get_option( $option_name, array() );
	}

	/**
	 * Method which will return the list of all stored plugin details.
	 *
	 * @return array Array of all stored plugin details.
	 */
	public function get_plugin_list() {
		// synchronize existing themes with plugin options.
		$this->reverify_plugin_lists();

		// Get all stored theme details.
		$option_name = $this->get_plugins_option_name();
		return get_option( $option_name, array() );
	}

	/**
	 * Method which will return the option name for storing alert notification setting.
	 *
	 * @return string Option name for storing alert notification setting.
	 */
	private function get_alert_notification_option_name() {
		return DFG_SLUG . '_alert_notification';
	}

	/**
	 * Method which will return the option name for storing flush cache setting.
	 *
	 * @return string Option name for storing flush cache setting.
	 */
	private function get_flush_cache_option_name() {
		return DFG_SLUG . '_flush_cache';
	}

	/**
	 * Method which will return the option name for storing theme details.
	 *
	 * @return string Option name for storing theme details.
	 */
	private function get_themes_option_name() {
		return DFG_SLUG . '_themes_list';
	}

	/**
	 * Method which will return the option name for storing plugin details.
	 *
	 * @return string Option name for storing plugin details.
	 */
	private function get_plugins_option_name() {
		return DFG_SLUG . '_plugins_list';
	}

	/**
	 * Method which will check if the data is correct.
	 *
	 * @param array $data Array of package data to save.
	 * @return bool true if saved successfully, false otherwise.
	 */
	private function option_value_is_correct( $data ) {
		if ( ! is_array( $data ) ) {
			return false; }

		foreach ( array( 'slug', 'repo_url', 'branch', 'provider', 'is_private_repository', 'options' ) as $name ) {
			if ( ! array_key_exists( $name, $data ) ) {
				return false;
			}
		}

		if ( ! is_bool( $data['is_private_repository'] ) ) {
			return false; }

		if ( ! is_array( $data['options'] ) ) {
			return false; }

		if ( $data['is_private_repository'] === true ) {

			$access_token_exist = array_key_exists( 'access_token', $data['options'] );
			$user_pwd_exist     = array_key_exists( 'username', $data['options'] ) && array_key_exists( 'password', $data['options'] );

			if ( ! $access_token_exist && ! $user_pwd_exist ) {
				return false;
			}

			if ( $data['provider'] === 'github' && ! $access_token_exist ) {
				return false;
			}

			if ( $data['provider'] === 'gitea' && ! $access_token_exist ) {
				return false;
			}

			if ( $data['provider'] === 'bitbucket' && ! $user_pwd_exist ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Method which will save theme data to the DB.
	 *
	 * @param array  $new_data Array of package data to save.
	 * @param string $type Type of package (theme or plugin).
	 * @return bool true if saved successfully, false otherwise.
	 */
	public function store_package_details( $new_data, $type = 'theme' ) {
		$type = ( $type === 'theme' ) ? 'theme' : 'plugin';

		if ( ! $this->option_value_is_correct( $new_data ) ) {
			return false; }

		$data_updated = false;

		if ( $type === 'theme' ) {
			// Get all stored theme details.
			$packages_list = $this->get_theme_list();
		} else {
			// Get all stored plugin details.
			$packages_list = $this->get_plugin_list();
		}

		// Iterate over each packages details.
		foreach ( $packages_list as &$details ) {
			// If the theme_stylesheet matches the one we're looking to update.
			if ( $details['slug'] === $new_data['slug'] ) {
				// Update theme data URL for this theme.
				$details      = $new_data;
				$data_updated = true;

				// Since we've found and updated the theme we're interested in, we can break the loop.
				break;
			}
		}

		// check if nothing was updated.
		if ( ! $data_updated ) {
			// append to existing data.
			$packages_list[] = $new_data;
		}

		if ( $type === 'theme' ) {
			$option_name = $this->get_themes_option_name();
		} else {
			$option_name = $this->get_plugins_option_name();
		}
		// Save the updated package details back to the options.
		return update_option( $option_name, $packages_list );
	}

	/**
	 * Method which will remove theme data from the DB.
	 *
	 * @param string $slug Theme slug.
	 * @param string $type Type of package (theme or plugin).
	 * @return bool true if saved successfully, false otherwise.
	 */
	private function remove_package_details( $slug = '', $type = 'theme' ) {
		$type = ( $type === 'theme' ) ? 'theme' : 'plugin';

		if ( empty( $slug ) ) {
			return false; }

		if ( $type === 'theme' ) {
			// Get all stored theme details.
			$option_name = $this->get_themes_option_name();
		} else {
			// Get all stored plugin details.
			$option_name = $this->get_plugins_option_name();
		}

		$packages_details = get_option( $option_name, array() );

		// Iterate over each theme's details.
		foreach ( $packages_details as $key => $package_details ) {
			// If the theme_stylesheet matches the one we're looking to update.
			if ( $package_details['slug'] === $slug ) {
				// Update theme data URL for this theme.
				unset( $packages_details[ $key ] );

				// Since we've found and removed the theme we're interested in, we can break the loop.
				break;
			}
		}

		// Save the updated theme details back to the options.
		return update_option( $option_name, $packages_details );
	}
}
