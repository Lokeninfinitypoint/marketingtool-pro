<?php
/**
 * Metabox For General.
 *
 * @package Iteck 
 */
?>
<?php 
global $mode;
If ($mode==true):
	iteck_meta_box_dropdown('iteck_theme_mode', 
		esc_html__('Webpage Dark/Light Mode', 'iteck_plg'),
		array('default' => esc_html__( 'Use Global Settings (in Theme Options)', 'iteck_plg' ),
			  'light_mode' => esc_html__( 'Light', 'iteck_plg' ),
			  'auto_mode' => esc_html__( 'Auto','iteck_plg'),
			  'dark_mode' => esc_html__( 'Dark', 'iteck_plg' )
		)
	);
	iteck_meta_box_dropdown('iteck_mode_switcher', 
		esc_html__('Color mode switcher', 'iteck_plg'),
		array('default' => esc_html__( 'Use Global Settings (in Theme Options)', 'iteck_plg' ),
			  'on' => esc_html__( 'On', 'iteck_plg' ),
			  'off' => esc_html__( 'Off','iteck_plg'),
			  
		)
	);
endif;

iteck_meta_box_dropdown('iteck_sidebar_format', 
	esc_html__('Sidebar Format', 'iteck_plg'),
	array('default' => esc_html__( 'Use Global Settings (in Theme Options)', 'iteck_plg' ),
		  'right_sidebar' => esc_html__( 'Right Sidebar', 'iteck_plg' ),
		  'left_sidebar' => esc_html__( 'Left Sidebar','iteck_plg'),
		  'no_sidebar' => esc_html__( 'No Sidebar', 'iteck_plg' )
	)
);


iteck_meta_box_colorpicker( 'iteck_color_general',
	esc_html__( 'General scheme color ', 'iteck_plg' )
); 

iteck_meta_box_colorpicker( 'iteck_custom_hovers',
	esc_html__( 'Custom hovers', 'iteck_plg' )
);

iteck_meta_box_colorpicker( 'iteck_color_scheme',
	esc_html__( 'Color scheme', 'iteck_plg' )
);
iteck_meta_box_colorpicker( 'general_color',
	esc_html__( 'General color', 'iteck_plg' )
);

