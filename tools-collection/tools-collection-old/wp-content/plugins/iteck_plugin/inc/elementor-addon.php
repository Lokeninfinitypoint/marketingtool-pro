<?php
//Elementor Editor view

//display menu list
function iteck_navmenu_navbar_menu_choices() {
	$menus = wp_get_nav_menus();
	$items = array();
	$i     = 0;
	foreach ( $menus as $menu ) {
		if ( $i == 0 ) {
			$default = $menu->slug;
			$i ++;
		}
		$items[ $menu->slug ] = $menu->name;
	}

	return $items;
}

//display Side panel list
function iteck_side_panel_choices() {
   $iteck_custom_sidepanels = new WP_Query( array( 'post_type' => 'sidepanel' ) );
   $posts = $iteck_custom_sidepanels->posts; 
   $items = array();
   $i     = 0;
   foreach ( $posts as $sidepanel ) {
      if ( $i == 0 ) {
         $default = $sidepanel->slug;
         $i ++;
      }
      $items[ $sidepanel->slug ] = $sidepanel->post_name;
   }

   return $items;

}

//display category blog list
function iteck_category_choice() {
    $categories = get_categories( );
	$blogs = array();
	$i     = 0;
	foreach ( $categories as $category ) {
		if ( $i == 0 ) {
			$default = $category->name ;
			$i ++;
		}
		$blogs[ $category->term_id ] = $category->name;
	}
	return $blogs;
}

//display portfolio categories
function iteck_tax_choice() {
    $categories = get_terms('portfolio_category' );
	$blogs = array();
	$i     = 0;
	foreach ( $categories as $category ) {
		if ( $i == 0 ) {
			$default = $category->name ;
			$i ++;
		}
		$blogs[ $category->term_id ] = $category->name;
	}
	return $blogs;
}

//display products categories
function iteck_products_choice() {
    $categories = get_terms('product_cat' );
	$blogs = array();
	$i     = 0;
	foreach ( $categories as $category ) {
		if ( $i == 0 ) {
			$default = $category->name ;
			$i ++;
		}
		$blogs[ $category->term_id ] = $category->name;
	}
	return $blogs;
}

//for imagesloaded 
add_action( 'elementor/editor/after_enqueue_scripts', function() {
   wp_enqueue_script( 'imagesloaded'); 
} );

//add new category elementor
add_action( 'elementor/init', function () {
	$elementsManager = Elementor\Plugin::instance()->elements_manager;
	$elementsManager->add_category(
		'iteck-elements',
		array(
			'title' => 'Iteck General Elements',
			'icon'  => 'font',
		),
		1
	);
} );

//add new category elementor
add_action( 'elementor/init', function () {
	$elementsManager = Elementor\Plugin::instance()->elements_manager;
	$elementsManager->add_category(
		'iteck-menu-elements',
		array(
			'title' => 'Iteck Custom Menu Elements',
			'icon'  => 'font',
		),
		2
	);
} );

//add new category elementor
add_action( 'elementor/init', function () {
	$elementsManager = Elementor\Plugin::instance()->elements_manager;
	$elementsManager->add_category(
		'iteck-portfolio-elements',
		array(
			'title' => 'Iteck Single Portfolio Elements',
			'icon'  => 'font',
		),
		3
	);
} );

//add new category elementor
add_action( 'elementor/init', function () {
	$elementsManager = Elementor\Plugin::instance()->elements_manager;
	$elementsManager->add_category(
		'iteck-blog-elements',
		array(
			'title' => 'Iteck Blog Post Elements',
			'icon'  => 'font',
		),
		4
	);
} );




add_action('elementor/element/before_section_end', function( $section, $section_id, $args ) {
	if( $section->get_name() == 'google_maps' && $section_id == 'section_map' ){
		// we are at the end of the "section_image" area of the "image-box"
		$section->add_control(
			'map_style' ,
			[
				'label'        => 'Map Style',
				'type'         => Elementor\Controls_Manager::SELECT,
				'default'      => 'default',
				'options'      => array( 'default' => 'Default', 'gray' => 'Grayscale Map' ),
				'prefix_class' => 'map-',
				'label_block'  => true,
			]
		);
	}
}, 10, 3 );


add_action( 'elementor/editor/after_enqueue_styles', function() {  wp_enqueue_style('fa-style-addons',ITECK_URL .'assets/fonts/fa/css/fontawesome.min.css', array(), null, 'all'  );} );
add_action( 'elementor/editor/after_enqueue_styles', function() {  wp_enqueue_style('flaticons-style-addons',ITECK_URL .'assets/fonts/flaticon/flaticon.css', array(), null, 'all'  );} );
add_action( 'elementor/editor/after_enqueue_styles', function() {  wp_enqueue_style('peicon-style-addons',ITECK_URL .'assets/fonts/peicon/pe-icon-7-stroke.css', array(), null, 'all'  );} );
add_action( 'elementor/editor/after_enqueue_styles', function() {  wp_enqueue_style('bootstrap-icons',ITECK_URL .'assets/fonts/bootstrap-icons/bootstrap-icons.css', array(), null, 'all'  );} );


