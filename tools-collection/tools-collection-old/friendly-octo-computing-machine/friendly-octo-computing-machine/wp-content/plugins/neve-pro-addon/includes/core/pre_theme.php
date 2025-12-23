<?php 
/**
 * Pre Theme class. 
 * 
 * @package Neve Pro Addon
 * @since 4.1.0
 */

namespace Neve_Pro\Core;

use Neve_Pro\Traits\Core;

/**
 * Class Pre_Theme
 */
final class Pre_Theme {
	use Core;

	/**
	 * Initialize the class.
	 */
	public function init() {
		add_filter( 'neve_hooks_upsell_should_load', [ $this, 'adapt_custom_layouts' ] );
		add_filter( 'neve_custom_layouts_post_type_args', [ $this, 'adapt_cpt' ], 999 );
	}

	/**
	 * Adapt custom layouts.
	 *
	 * @return bool
	 */
	public function adapt_custom_layouts( $should_load ) {
		if ( $this->get_license_status() === 'active_expired' ) {
			return true;
		}

		return false;
	}

	/**
	 * Adapt custom post type args.
	 */
	public function adapt_cpt( $args ) {
		if ( $this->get_license_status() !== 'active_expired' ) {
			return $args;
		}

		$args['show_in_menu']      = false;
		$args['show_in_admin_bar'] = false;
		$args['show_ui']           = false;

		return $args;
	}

}
