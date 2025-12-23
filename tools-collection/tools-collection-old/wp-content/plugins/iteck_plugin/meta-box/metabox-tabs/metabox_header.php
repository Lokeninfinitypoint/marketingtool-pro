<?php
/**
 * Metabox For Header.
 *
 * @package Iteck
 */
?>
<?php 
iteck_meta_box_dropdown('iteck_header_format', 
	esc_html__('Header Position/Format', 'iteck_plg'),
	array('default' => esc_html__( 'Global Settings (in Theme Options)', 'iteck_plg' ),
		  'standard_header' => esc_html__( 'Standard Header', 'iteck_plg' ),
		  'custom_header' => esc_html__( 'Custom Header', 'iteck_plg' ),
		  'no_header' => esc_html__( 'No Header', 'iteck_plg' )
	)
);

iteck_meta_box_dropdown_custom_headers('iteck_header_list',
	esc_html__('Choose Custom Header', 'iteck_plg'),
	'',
	esc_html__('Only if (Header Position/Format= "Custom Header")', 'iteck_plg') 
);

iteck_meta_box_dropdown('iteck_header_position',
	esc_html__('Header Position/Format', 'iteck_plg'),
	array(
		'default' => esc_html__( 'Global Settings (in Theme Options)', 'iteck_plg' ),
		'head_white' => esc_html__( 'Relative Position with Background, ', 'iteck_plg' ),
		'head_trans' => esc_html__( 'Absolute Position, Transperant','iteck_plg'),
	)
);


iteck_meta_box_dropdown_menu('iteck_header_menu',
	esc_html__('Select Menu', 'iteck_plg'), 
	'',
	esc_html__('You can manage menu using Appearance > Menus', 'iteck_plg')
);
iteck_meta_box_dropdown('iteck_menu_position',
	esc_html__('Menu Alignment', 'iteck_plg'),
	array('default' => esc_html__('Global Settings (in Theme Options)', 'iteck_plg'),
		  'right' => esc_html__( 'Right', 'iteck_plg' ),
		  'center' => esc_html__( 'Center', 'iteck_plg' ),
	)
);

iteck_meta_box_dropdown('iteck_header_enable_social',
	esc_html__('Show Social', 'iteck_plg'),
	array('default' => esc_html__('Global Settings (in Theme Options)', 'iteck_plg'),
		  'on' => esc_html__( 'On', 'iteck_plg' ),
		  'off' => esc_html__( 'Off', 'iteck_plg' ),
	)
);

iteck_meta_box_dropdown('iteck_header_search',
	esc_html__('Show Search icon', 'iteck_plg'),
	array('default' => esc_html__('Global Settings (in Theme Options)', 'iteck_plg'),
		  'on' => esc_html__( 'On', 'iteck_plg' ),
		  'off' => esc_html__( 'Off', 'iteck_plg' ),
	)
);
iteck_meta_box_dropdown('iteck_header_cart',
	esc_html__('Show Cart', 'iteck_plg'),
	array('default' => esc_html__('Global Settings (in Theme Options)', 'iteck_plg'),
		  'on' => esc_html__( 'On', 'iteck_plg' ),
		  'off' => esc_html__( 'Off', 'iteck_plg' ),
	)
);
iteck_meta_box_dropdown('iteck_header_btn',
	esc_html__('Show Button', 'iteck_plg'),
	array('default' => esc_html__('Global Settings (in Theme Options)', 'iteck_plg'),
		  'on' => esc_html__( 'On', 'iteck_plg' ),
		  'off' => esc_html__( 'Off', 'iteck_plg' ),
	)
);

iteck_meta_box_upload('iteck_header_logo_white', 
				esc_html__('Light Logo', 'iteck_plg'),
				esc_html__('Upload Dark logo in the header', 'iteck_plg'),
				'',
				esc_html__( 'Menu Logo', 'iteck_plg' ),
				esc_html__( 'Set Menu Logo', 'iteck_plg' ) 
			);
iteck_meta_box_upload('iteck_header_logo_dark', 
				esc_html__('Dark Logo', 'iteck_plg'),
				esc_html__('Upload lihgt logo in the header', 'iteck_plg'),
				'',
				esc_html__( 'Menu Logo', 'iteck_plg' ),
				esc_html__( 'Set Menu Logo', 'iteck_plg' ) 
			);


