<?php
// single portfolio script
function iteck_single_portfolio_script() {
	global $post;
	if ( is_singular( 'portfolio' ) ) {
		wp_enqueue_script('jquery-isotope',ITECK_URL .'assets/js/isotope.pkgd.js', array('jquery'), null, true  );
        wp_enqueue_script('imgbg-script',ITECK_URL . 'assets/js/imgbg.js' , array('jquery'), null, true );
		wp_enqueue_script('single-portfolio',ITECK_URL . 'assets/js/single-portfolio.js' , array('jquery'), null, true );
		wp_enqueue_script('slider-script',ITECK_URL . 'assets/js/slider.js' , array('jquery'), null, true );
		if (get_post_meta($post->ID, 'port_format', true) == 'port_two' && get_post_meta($post->ID, 'top_type', true) == 'top_content_slider' ){
			wp_enqueue_script('sliderbg-script',ITECK_URL . 'assets/js/sliderbg.js' , array('jquery'), null, true );
		}
		if (get_post_meta($post->ID, 'port_format', true) == 'port_two' && get_post_meta($post->ID, 'top_type', true) == 'top_content_youtube' ){
			wp_enqueue_script( 'iteck_ytPlayer', ITECK_URL . 'assets/js/jquery.mb.YTPlayer.js' ,array(),'', 'in_footer');
			wp_enqueue_script( 'iteck_homeyt', ITECK_URL . 'assets/js/homeyt.js' ,array(),'', 'in_footer');
		}
		if (get_post_meta($post->ID, 'port_format', true) == 'port_two' && get_post_meta($post->ID, 'top_type', true) == 'top_content_video' ){
			wp_enqueue_script('jquery-videojs',ITECK_URL . 'assets/js/video.js' , array('jquery'), null, true );
			wp_enqueue_script('jquery-big-video',ITECK_URL . 'assets/js/bigvideo.js' , array('jquery'), null, true );
			wp_enqueue_script('iteck-single-portfolio-video',ITECK_URL . 'assets/js/singleport-video.js' , array('jquery'), null, true );
		}
		
    }

}

add_action( 'wp_enqueue_scripts', 'iteck_single_portfolio_script',100 );



