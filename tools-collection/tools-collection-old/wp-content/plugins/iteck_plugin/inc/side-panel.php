<?php
// Registers the new post type 

function iteck_sidepanel_post_type() {
	register_post_type( 'sidepanel',
		array(
			'labels' => array(
				'name' => __( 'Custom sidepanel', 'iteck_plg' ),
				'singular_name' => __( 'Custom sidepanel' , 'iteck_plg'),
				'add_new' => __( 'Add New Custom sidepanel', 'iteck_plg' ),
				'add_new_item' => __( 'Add New Custom sidepanel', 'iteck_plg' ),
				'edit_item' => __( 'Edit Custom sidepanel', 'iteck_plg' ),
				'new_item' => __( 'Add New Custom sidepanel', 'iteck_plg' ),
				'view_item' => __( 'View Custom sidepanel', 'iteck_plg' ),
				'search_items' => __( 'Search Custom sidepanel', 'iteck_plg' ),
				'not_found' => __( 'No Custom sidepanel found', 'iteck_plg' ),
				'not_found_in_trash' => __( 'No Custom sidepanel found in trash', 'iteck_plg' )
			),
			'public' => true,
			'supports' => array( 'title'),
			'capability_type' => 'post',
			'rewrite' => array("slug" => "sidepanel"), // Permalinks format
			'menu_position' => 5,
			'menu_icon'           => 'dashicons-menu',
			'exclude_from_search' => true 
		)
	);

}

add_action( 'init', 'iteck_sidepanel_post_type' );


add_action( 'admin_init', 'iteck_sidepanel_mb' );
function iteck_sidepanel_mb() {
  
  /**
   * Create a custom meta boxes array that we pass to 
   * the reduxoptions Meta Box API Class.
   */
  $iteck_sidepanel_mb = array(
    'id'          => 'sidepanel_meta_box',
    'title'       => esc_html__( 'Notes:', 'iteck_plg' ),
    'desc'        => '',
    'pages'       => array( 'sidepanel' ),
    'context'     => 'normal',
    'priority'    => 'high',
	'fields'      => array(
	  array(
        'id'          => 'sidepanel_setting_block',
        'label'       => '',
        'desc'        => esc_html__('You can build your custom sidepanel with elementor and use it in any page using the page settings. <br/>
		Make sure you have checklist the Custom sidepanel in Elementor Settings -> Post Type', 'iteck_plg' ),
        'std'         => '',
        'type'        => 'textblock-tititeck',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
 
	  
      ),
	  
    )
  );

}







