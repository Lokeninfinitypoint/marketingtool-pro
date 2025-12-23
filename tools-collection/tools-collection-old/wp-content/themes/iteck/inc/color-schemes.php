<?php 
//color schemes 
function iteck_color_scheme() {
	if ( class_exists( 'ReduxFrameworkPlugin' ) ) {   
		global $post ;

		//Theme color options
		$main_col = iteck_option( 'iteck_main_color' );
		$primary_col = iteck_option( 'iteck_primary_color' );
		$secondary_col = iteck_option( 'iteck_secondary_color' );
		$ternary_col = iteck_option( 'iteck_ternary_color' ); 
		$color_general = "
			:root{
				--color-main:$main_col;
				--color-primary:$primary_col;
				--color-secondary:$secondary_col;
				--color-ternary: $ternary_col;
			}
		";   

		if ( class_exists( 'ReduxFrameworkPlugin' ) && iteck_option( 'iteck_main_color' ) ) {           
			wp_add_inline_style( 'iteck-style', $color_general );
		}

		//hovers color
		$hovers = iteck_option( 'iteck_custom_hovers' );
		if ( class_exists( 'ReduxFrameworkPlugin' ) && iteck_option( 'iteck_custom_hovers' ) ) {  
			$custom_hovers = "a:hover{color:$hovers;}";         
			wp_add_inline_style( 'iteck-style', $custom_hovers );
		}

		//scheme color
		$color = iteck_option( 'iteck_color_scheme' );
		if ( class_exists( 'ReduxFrameworkPlugin' ) && iteck_option( 'iteck_color_scheme' ) ) {  
			$custom_css = "a{color:$color;}";   
			wp_add_inline_style( 'iteck-style', $custom_css );
		}
		
		//heading color 
		$heading = iteck_option( 'iteck_heading_color' );
		if ( class_exists( 'ReduxFrameworkPlugin' ) && iteck_option( 'iteck_heading_color' ) ) { 
			$heading_color = "h1, h2, h3, h4, h5, h6{color:$heading;} ";   
			wp_add_inline_style( 'iteck-style', $heading_color );
		}

		//body color
		$general = iteck_option( 'iteck_general_color' );   
		if ( class_exists( 'ReduxFrameworkPlugin' ) && iteck_option( 'iteck_general_color' ) ) { 
			$general_color = "body{color:$general;}";          
			wp_add_inline_style( 'iteck-style', $general_color );
		}
		
		//footer color
		$footer = iteck_option( 'iteck_footer_color' );
		if ( class_exists( 'ReduxFrameworkPlugin' ) && iteck_option( 'iteck_footer_color' ) ) {   
			$footer_color = ".footer{background-color:$footer;}";   
			wp_add_inline_style( 'iteck-style', $footer_color );
		}

		//Main menu background
		$main_menu = iteck_option( 'iteck_main_menu' );
		if ( class_exists( 'ReduxFrameworkPlugin' ) && iteck_option( 'iteck_main_menu' ) ) {  
			$color_menu = ".white-header{background: $main_menu;}";   
			wp_add_inline_style( 'iteck-style', $color_menu );
		}
		//Main menu color
		$main_menu = iteck_option( 'iteck_main_menu_hover' );
		if ( class_exists( 'ReduxFrameworkPlugin' ) && iteck_option( 'iteck_main_menu_hover' ) ) {  
			$color_menu = ".white-header .navigation li a{color: $main_menu;}";   
			wp_add_inline_style( 'iteck-style', $color_menu );
		}
		
		//Sticky menu background
		$menu = iteck_option( 'iteck_stick_menu_bg' );
		if ( class_exists( 'ReduxFrameworkPlugin' ) && iteck_option( 'iteck_stick_menu_bg' ) ) {  
			$color_menu = ".white-header .is-sticky .stuck-nav{background: $menu;}";   
			wp_add_inline_style( 'iteck-style', $color_menu );
		}

		//menu2 color
		$menu2 = iteck_option( 'iteck_stick_menu_color' );
		if ( class_exists( 'ReduxFrameworkPlugin' ) && iteck_option( 'iteck_stick_menu_color' ) ) { 
			$color_menu2 = ".white-header .is-sticky .navigation li a{color: $menu2;}";   
			wp_add_inline_style( 'iteck-style', $color_menu2 );
		}

		//menu border color
		$menu_border = iteck_option( 'iteck_menu_border' );
		if ( class_exists( 'ReduxFrameworkPlugin' ) && iteck_option( 'iteck_menu_border' ) ) { 
			$color_border = ".custom-absolute-menu{border-color: $menu_border;}";   
			wp_add_inline_style( 'iteck-style', $color_border );
		}
		
	}         
}







		