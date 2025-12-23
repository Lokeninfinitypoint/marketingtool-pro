<?php

defined( 'ABSPATH' ) || exit;

/**
* Iteck Theme Helper
*
*
* @class        Iteck_Theme_Helper
* @version      1.0
* @category     Class
* @author       ThemesCamp
*/

if (! class_exists('Iteck_Theme_Helper')) {
	class Iteck_Theme_Helper
	{
		public static function render_sidebars(){

			$sidebar_style = '';
			$sidebar_gap = iteck_option( 'single_sidebar_gap','0' );
			$layout = iteck_option( 'single_sidebar_layout' );

			if (isset($sidebar_gap) && $sidebar_gap != 'def' && $layout != 'default') {
				$layout_pos = $layout == 'left' ? 'right' : 'left';
				$sidebar_style = 'style="padding-'.$layout_pos.': '.$sidebar_gap.'px;"';
				return $sidebar_style;
			}
		}

	}
}