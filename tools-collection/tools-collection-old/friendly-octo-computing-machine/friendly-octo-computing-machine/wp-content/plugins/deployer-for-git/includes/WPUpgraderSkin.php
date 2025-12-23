<?php
namespace DeployerForGit;

require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader-skin.php';

/**
 * Class WPUpgraderSkin
 *
 * @package DeployerForGit
 */
class WPUpgraderSkin extends \WP_Upgrader_Skin {

	/**
	 * Override feedback method with empty function.
	 *
	 * @param string $feedback Message data.
	 * @param mixed  ...$args  Optional text replacements.
	 */
	public function feedback( $feedback, ...$args ) {}
}
