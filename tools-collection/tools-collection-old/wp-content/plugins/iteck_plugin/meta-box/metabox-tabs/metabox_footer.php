<?php
/**
 * Metabox For Footer.
 *
 * @package Iteck
 */
?>
<?php 
iteck_meta_box_dropdown('iteck_footer_format',
	esc_html__('Footer Format', 'iteck_plg'),
	array('global' => esc_html__( 'Use Global Settings (in Theme Options)', 'iteck_plg' ),
		  'custom_footer' => esc_html__( 'Use Custom Footer', 'iteck_plg' ),
		  'no_footer' => esc_html__( 'No Footer', 'iteck_plg' )
	)
);

iteck_meta_box_dropdown_custom_footers('iteck_footer_list',
	esc_html__('Choose Custom Footer', 'iteck_plg'),
	'',
	esc_html__('Only if used "Custom Footer" format', 'iteck_plg')
);

iteck_meta_box_colorpicker( 'iteck_footer_color',
	esc_html__( 'Footer color', 'iteck_plg' )
);


