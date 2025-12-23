<?php
/**
 * Metabox For sidepanel.
 *
 * @package Iteck 
 */
?>
<?php 
iteck_meta_box_dropdown('iteck_sidepanel_format',
	esc_html__('sidepanel Format.', 'iteck_plg'),
	array('default' => esc_html__('Default', 'iteck_plg'),
		  'global' => esc_html__( 'Use Global Settings (in Theme Options)', 'iteck_plg' ),
		  'custom_sidepanel' => esc_html__( 'Use Custom sidepanel', 'iteck_plg' ),
		  'no_sidepanel' => esc_html__( 'No sidepanel', 'iteck_plg' )
	)
);

iteck_meta_box_dropdown_custom_sidepanels('iteck_sidepanel_list',
	esc_html__('Choose Custom sidepanel.', 'iteck_plg'),
	'',
	esc_html__('Only if used "Custom sidepanel" format', 'iteck_plg')
);


