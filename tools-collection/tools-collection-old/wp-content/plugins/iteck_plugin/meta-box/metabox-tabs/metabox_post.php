<?php
/**
 * Metabox For Single Post.
 *
 * @package Iteck  
 */
?>
<?php 
iteck_meta_box_dropdown('iteck_single_type_layout',
	esc_html__('Post Image', 'iteck_plg'),
	array('default' => esc_html__('Global Settings (in Theme Options)', 'iteck_plg'),
		'1' => esc_html__( 'Elegant', 'iteck_plg' ),
		'2' => esc_html__( 'Classic', 'iteck_plg' ),
		'3' => esc_html__( 'Overlay Image', 'iteck_plg' ),
	)
);

iteck_meta_box_dropdown('iteck_sidebar_layout',
	esc_html__('Post Sidebar Layout', 'iteck_plg'),
	array('default' => esc_html__('Global Settings (in Theme Options)', 'iteck_plg'),
		'1' => esc_html__( 'Clean Style', 'iteck_plg' ),
		'2' => esc_html__( 'Boundary Style', 'iteck_plg' ),
		'3' => esc_html__( 'Elegant Style', 'iteck_plg' ),
	)
);

iteck_meta_box_dropdown('iteck_single_sidebar_layout',
	esc_html__('Post Sidebar Layout', 'iteck_plg'),
	array('default' => esc_html__('Global Settings (in Theme Options)', 'iteck_plg'),
		'left' => esc_html__( 'Left', 'iteck_plg' ),
		'none' => esc_html__( 'None', 'iteck_plg' ),
		'right' => esc_html__( 'Right', 'iteck_plg' ),
	)
);





