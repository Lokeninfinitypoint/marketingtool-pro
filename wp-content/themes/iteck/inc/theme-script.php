<?php
//load all theme jquery script
function iteck_theme_scripts() {
	wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/assets/js/modernizr.js',array('jquery'), false, '', false);   
	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js',array(),'', 'in_footer');
	wp_enqueue_script( 'jquery-effects-core'); 
	wp_enqueue_script( 'superfish', get_template_directory_uri() . '/assets/js/superfish.js',array(),'', 'in_footer');
	wp_enqueue_script( 'pace', get_template_directory_uri() . '/assets/js/pace.min.js',array(),'', 'in_footer');
	wp_enqueue_script( 'jquery-fitvids', get_template_directory_uri() . '/assets/js/jquery.fitvids.js',array(),'', 'in_footer');
	wp_enqueue_script( 'jquery-magnific-popup', get_template_directory_uri() . '/assets/js/jquery.magnific-popup.min.js',array(),'', 'in_footer');
	wp_enqueue_script( 'jquery-sticky', get_template_directory_uri() . '/assets/js/jquery.sticky.js',array(),'', 'in_footer');
	wp_enqueue_script( 'imagesloaded');     
	wp_enqueue_script( 'slick', get_template_directory_uri() . '/assets/js/slick.min.js',array(),'', 'in_footer');
	wp_enqueue_script( 'slick-animation', get_template_directory_uri() . '/assets/js/slick-animation.js',array(),'', 'in_footer');
	wp_enqueue_script( 'resizesensor', get_template_directory_uri() . '/assets/js/ResizeSensor.min.js',array(),'', 'in_footer');
	wp_enqueue_script( 'theia-sticky-sidebar', get_template_directory_uri() . '/assets/js/theia-sticky-sidebar.min.js',array(),'', 'in_footer');
	wp_enqueue_script( 'svgembedder', get_template_directory_uri() . '/assets/js/svgembedder.min.js',array(),'', 'in_footer');
	wp_enqueue_script( 'iteck-totop', get_template_directory_uri() . '/assets/js/totop.js',array(),'', 'in_footer');
	wp_enqueue_script( 'animated-headline', get_template_directory_uri() . '/assets/js/animated.headline.js',array(),'', 'in_footer');
	if( !is_rtl() ) {
	wp_enqueue_script( 'splitting', get_template_directory_uri() . '/assets/js/splitting.min.js',array(),'', 'in_footer');
	}
	wp_enqueue_script( 'isotope.pkgd', get_template_directory_uri() . '/assets/js/isotope.pkgd.min.js',array(),'', 'in_footer');
	wp_enqueue_script( 'swiper', get_template_directory_uri() . '/assets/js/swiper.min.js',array('jquery'),'', 'in_footer');
	wp_enqueue_script( 'simpleParallax', get_template_directory_uri() . '/assets/js/simpleParallax.min.js',array('jquery'),'', 'in_footer');

	wp_enqueue_script( 'waypoints', get_template_directory_uri() . '/assets/js/waypoints.min.js',array(),'', 'in_footer');
	wp_enqueue_script( 'jquery-counterup', get_template_directory_uri() . '/assets/js/jquery.counterup.min.js',array(),'', 'in_footer');

	wp_enqueue_script( 'jquery-knob', get_template_directory_uri() . '/assets/js/jquery.knob.js',array(),'', 'in_footer');
	wp_enqueue_script( 'jquery-wow', get_template_directory_uri() . '/assets/js/wow.min.js',array(),'', 'in_footer');
	wp_enqueue_script( 'jquery-appear', get_template_directory_uri() . '/assets/js/jquery.appear.js',array(),'', 'in_footer');
	wp_enqueue_script( 'iteck-scripts', get_template_directory_uri() . '/assets/js/scripts.js',array( 'jquery' ),'', true);
}     