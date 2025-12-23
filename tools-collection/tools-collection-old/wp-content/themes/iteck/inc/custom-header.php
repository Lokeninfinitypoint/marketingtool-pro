<?php 

//function custom header by global settings 
function iteck_custom_header_global () {

	global $post ;  
	global $iteck_theme_settings;  
	$header_id =  iteck_option( 'iteck_header_set_list' );  

	$iteck_header = new WP_Query(array(
		'posts_per_page' => -1,
		'post_type' =>  'header',
		'p' => $header_id, 
	)); 
	
	if ($iteck_header->have_posts()) : 
		while  ($iteck_header->have_posts()) : $iteck_header->the_post();$header_id; ?>
			<nav class="iteck-custom-header clearfix 1 <?php if (class_exists('ReduxFrameworkPlugin') && (iteck_option( 'iteck_header_position' ) =='head_white')){ 
					echo 'white-header';
				} else { 
					echo 'custom-absolute-menu';
				} ?> ">
				<?php the_content(); ?>
			</nav>
	
		<?php endwhile; 
	endif; 
	wp_reset_postdata();
}

//function custom header by page settings  
function iteck_custom_header_page () {
	global $post ;
	$header_id =  get_post_meta( $post->ID, 'iteck_header_list', true ); 
	$iteck_header = new WP_Query( array(
		'posts_per_page' => 1,
		'post_type' =>  'header',
		'p' => $header_id,
	) ); 
	
	if ($iteck_header->have_posts()) : 
		while  ($iteck_header->have_posts()) : $iteck_header->the_post();?>
			<nav class="iteck-custom-header clearfix 2  <?php if (class_exists('ReduxFrameworkPlugin') && (iteck_option( 'iteck_header_position' ) =='head_white')){ 
				echo 'white-header';
			} else { 
				echo 'custom-absolute-menu';
			} ?>">
				<?php the_content(); ?>
			</nav>
		<?php endwhile; 
	endif; 
	wp_reset_postdata();
}

//function for output custom header
function iteck_header_start () {
	if ( is_singular()) { //if single page/post
		global $post;
		global $iteck_theme_settings; 
		if (get_post_meta($post->ID, 'iteck_header_format', true) =='custom_header' && get_post_meta($post->ID, 'iteck_header_list', true)) {
			//if page setting choose header custom
			do_action('iteck-header-page','iteck_custom_header_page') ;  
		} 
			 
		//if page setting choose header global
		else if (get_post_meta($post->ID, 'iteck_header_format', true) =='default'){ 
			//if custom header & list are selected in theme options
			if (iteck_option( 'iteck_header_set' ) =='custom' && iteck_option( 'iteck_header_set_list' ) !='' ) {
				do_action('iteck-header-global','iteck_custom_header_global') ; 
			} else {
				get_template_part( 'inc/menu','normal');
			} 
		}
			 
	//if page setting choose no header 
	else if (get_post_meta($post->ID, 'iteck_header_format', true) =='no_header'){
		//display nothing       
	}
			 
	//if page setting choose header standard 
	else { ?>
		<?php get_template_part( 'inc/menu');
	}
			
	} else { //if not single page/post 

		//if custom header & list are selected in theme options
		if (iteck_option( 'iteck_header_set' ) =='custom' && iteck_option( 'iteck_header_set_list' ) !='' ) {
			do_action('iteck-header-global','iteck_custom_header_global') ;  

		} else { 
			//if not use normal menu
			get_template_part( 'inc/menu','normal');
		}
	}
}

//function custom header by page settings  
function iteck_custom_menu_page ($menu) {
	global $post ;
	$iteck_header_menu =  iteck_option( $menu );
	if (!empty($iteck_header_menu)):
		wp_nav_menu( array(
			'menu'            => $iteck_header_menu,
			'items_wrap' => '<ul id="%1$s" class="home-nav nn navigation %2$s">%3$s</ul>',
			'menu_id'         => '',
			'echo'            => true,
		) );
	elseif(has_nav_menu('primary_menu')):
		$menu = '';
		wp_nav_menu( array(
			'menu_id'         => '',
			'items_wrap' => '<ul id="%1$s" class="home-nav mm navigation %2$s">%3$s</ul>',
			'theme_location' => 'primary_menu',
			  
		) );
	endif;
}

//function custom header by page settings
function iteck_custom_flat_menu_page ($flatmenu) {
	global $post ;
	$iteck_header_flat_menu = iteck_option( $flatmenu );
	if ( !empty($iteck_header_flat_menu) ):
		$menuParameters_flat = array(
			'menu' => $iteck_header_flat_menu,
			'container'       => true,
			'items_wrap'      => '<ul id="%1$s" class="mob-nav  %2$s">%3$s</ul>',
			'depth'           => 0,
		);
	else:
		$menuParameters_flat = array(
			'theme_location' => 'primary_menu',
			'container'       => false,
			'items_wrap'      => '<ul id="%1$s" class="mob-nav  %2$s">%3$s</ul>',
			'depth'           => 0,
		);
	endif;
	echo strip_tags(wp_nav_menu( $menuParameters_flat ), '<a>' );
}



