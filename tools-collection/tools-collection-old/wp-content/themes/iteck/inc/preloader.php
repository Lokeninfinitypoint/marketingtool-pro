<?php 
//preloader custom setting
function iteck_preloader_set() {
	if  ( class_exists('ReduxFrameworkPlugin')){
		$color =  iteck_option( 'iteck_loader_color' );
		$loader_bg = "
		.load-circle{border-top-color: $color;}
		";   
		if ( class_exists('ReduxFrameworkPlugin') && iteck_option( 'iteck_loader_color' )) {           
			wp_add_inline_style( 'iteck-style', $loader_bg );
		}
	}
} 

function iteck_preloader() {
	if  ( class_exists('ReduxFrameworkPlugin')){
		$preload = iteck_option( 'iteck_preloader_set' );
		if ( class_exists('ReduxFrameworkPlugin') ) : if ($preload == 'show_home') :
			wp_enqueue_script( 'preloader', get_template_directory_uri() . '/assets/js/loader.js',array(),'', 'in_footer');
		endif ;  endif;
		
		if ( class_exists('ReduxFrameworkPlugin') ) : if ($preload == 'show_all') :
			wp_enqueue_script( 'preloader', get_template_directory_uri() . '/assets/js/loader.js',array(),'', 'in_footer');
		endif ;  endif;
	}
}    