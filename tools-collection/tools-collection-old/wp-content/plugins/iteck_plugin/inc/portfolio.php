<?php
// Registers the new post type 

function iteck_portfolio_post_type() {
	register_post_type( 'portfolio',
		array(
			'labels' => array(
				'name' => __( 'Portfolio', 'iteck_plg' ),
				'singular_name' => __( 'Portfolio' , 'iteck_plg'),
				'add_new' => __( 'Add New Portfolio', 'iteck_plg' ),
				'add_new_item' => __( 'Add New Portfolio', 'iteck_plg' ),
				'edit_item' => __( 'Edit Portfolio', 'iteck_plg' ),
				'new_item' => __( 'Add New Portfolio', 'iteck_plg' ),
				'view_item' => __( 'View Portfolio', 'iteck_plg' ),
				'search_items' => __( 'Search Portfolio', 'iteck_plg' ),
				'not_found' => __( 'No Portfolio found', 'iteck_plg' ),
				'not_found_in_trash' => __( 'No Portfolio found in trash', 'iteck_plg' )
			),
			'public' => true,
			'has_archive' => true,
			'supports' => array( 'title','editor', 'thumbnail', 'comments' , 'excerpt'),
			'capability_type' => 'post',
			'rewrite' => array("slug" => "portfolio"), // Permalinks format
			'menu_position' => 5,
			'menu_icon'           => 'dashicons-index-card',
			'exclude_from_search' => true,
			'taxonomies'  => array( 'portfolio', 'portfolio_category' ), 
		)
	);
}

add_action( 'init', 'iteck_portfolio_post_type' );

//add taxonomies(portfolio category)
function iteck_taxonomies_portfolio() {
	$labels = array(
		'name'              => _x( 'Portfolio Categories', 'taxonomy general name' ),
		'singular_name'     => _x( 'Portfolio Category', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Portfolio Categories' ),
		'all_items'         => __( 'All Portfolio Categories' ),
		'parent_item'       => __( 'Parent Portfolio Category' ),
		'parent_item_colon' => __( 'Parent Portfolio Category:' ),
		'edit_item'         => __( 'Edit Portfolio Category' ), 
		'update_item'       => __( 'Update Portfolio Category' ),
		'add_new_item'      => __( 'Add New Portfolio Category' ),
		'new_item_name'     => __( 'New Portfolio Category' ),
		'menu_name'         => __( 'Portfolio Categories' ),
	);
	$args = array(
		'labels' => $labels,
		'hierarchical' => true,
		'has_archive' => true, 
	);
	register_taxonomy( 'portfolio_category', 'portfolio', $args );
}
add_action( 'init', 'iteck_taxonomies_portfolio', 0 );

//add taxonomies(portfolio tag)
function iteck_taxonomies_portfolio_tag() {
	$labels = array(
		'name'              => _x( 'Portfolio Tags', 'taxonomy general name' ),
		'singular_name'     => _x( 'Portfolio Tag', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Portfolio Tags' ),
		'all_items'         => __( 'All Portfolio Tags' ),
		'edit_item'         => __( 'Edit Portfolio Tag' ), 
		'update_item'       => __( 'Update Portfolio Tag' ),
		'add_new_item'      => __( 'Add New Portfolio Tag' ),
		'new_item_name'     => __( 'New Portfolio Tag' ),
		'menu_name'         => __( 'Portfolio Tags' ),
		'parent_item' => null,
		'parent_item_colon' => null,
		'separate_items_with_commas' => __( 'Separate tags with commas' ),
		'add_or_remove_items' => __( 'Add or remove tags' ),
		'choose_from_most_used' => __( 'Choose from the most used tags' ),
	);
	$args = array(
		'labels' => $labels,
		'hierarchical' => false,
		'rewrite' => true,
		'query_var' => true,
		'has_archive' => true,
	);
	register_taxonomy( 'porto_tag', 'portfolio', $args );
}
add_action( 'init', 'iteck_taxonomies_portfolio_tag', 0 );

