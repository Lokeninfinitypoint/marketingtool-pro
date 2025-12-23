<?php
/**
 * Author:          Andrei Baicus <andrei@themeisle.com>
 * Created on:      2019-01-28
 *
 * @package Neve Pro Addon
 */

add_action( 'admin_notices', 'neve_pro_not_theme_notice' );

if ( ! function_exists( 'neve_pro_not_theme_notice' ) ) {
	/**
	 * Notice displayed if the theme is not neve.
	 *
	 * @since 0.0.1
	 */
	function neve_pro_not_theme_notice() {
		printf(
			'<div class="error"><p>%s</p></div>',
			esc_html(
				sprintf(
				// translators: %s: the name of the plugin (Neve Pro Addon).
					__( '%s is not a WordPress theme. Please install it as a plugin to work properly.', 'neve-pro-addon' ),
					'<b>' . __( 'Neve Pro Addon', 'neve-pro-addon' ) . '</b>'
				) 
			)
		);
	}
}
