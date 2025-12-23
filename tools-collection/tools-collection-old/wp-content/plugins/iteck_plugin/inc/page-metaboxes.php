<?php
/**
 * Initialize the Post Post Meta Boxes. 
 */
add_action( 'admin_init', 'iteck_page_mb' ); 
function iteck_page_mb() {
  
  /**
   * Create a custom meta boxes array that we pass to 
   * the reduxoptions Meta Box API Class.
   */
  $iteck_page_mb = array(
    'id'          => 'page_meta_box',
    'title'       => esc_html__( 'Page Settings', 'iteck_plg' ),
    'desc'        => '',
    'pages'       => array( 'page','portfolio','post'),
    'context'     => 'normal',
    'priority'    => 'high',
    'fields'      => array(
	
	  array(
        'id'          => 'custom_footer_header_note',
        'label'       => esc_html__('Please Note', 'iteck_plg' ),
        'desc'        => esc_html__('The Custom Header & Custom Footer only appear on the actual page, not in elementor editor.', 'iteck_plg' ),
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
	  
	  
	   
	  
	  array(
        'label'       => esc_html__( 'Header Settings', 'iteck_plg' ),
        'id'          => 'header_setting_section',
        'type'        => 'tab',
      ),
	  
	  array(
        'label'       => esc_html__( 'Header Options', 'iteck_plg' ),
		'desc'          =>  '',
        'id'          => 'custom_header_choice',
        'type'        => 'select',
		'std'		 => 'global',
		'choices'     => array( 
			 array(
                'value'       => 'global',
                'label'       => esc_html__( 'Use Global Settings (in Theme Options)', 'iteck_plg' )
              ),
			  array(
                'value'       => 'standard',
                'label'       => esc_html__( 'Use Default Header', 'iteck_plg' )
              ),
			  array(
                'value'       => 'custom_header',
                'label'       => esc_html__( 'Use Custom Header', 'iteck_plg' )
              ),
			  array(
                'value'       => 'no_header',
                'label'       => esc_html__( 'No Header', 'iteck_plg' )
              ),
			  
		)
      ),
	  
      array(
        'id'          => 'header_list',
        'label'       => esc_html__( 'Choose Custom Header', 'iteck_plg' ),
        'desc'        => '',
        'std'         => '',
		'condition'   => 'custom_header_choice:is(custom_header)',
        'type' => 'custom-post-type-select',
        'rows'        => '',
        'post_type'   => 'header',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => ''
      ),
	  
	  array(
        'label'       => esc_html__( 'Header Format', 'iteck_plg' ),
		'desc'          => '',
        'id'          => 'menu_format',
        'type'        => 'select',
		'condition'   => 'custom_header_choice:is(standard)',
		'std'		 => 'head_clean',
		'choices'     => array( 
			  array(
                'value'       => 'head_clean',
                'label'       => esc_html__( 'Black Text with White Background Header in Relative Position', 'iteck_plg' )
              ),
			  array(
                'value'       => 'head_standard',
                'label'       => esc_html__( 'White Text with Transparent Background Header in Absolute Position', 'iteck_plg' )
              ),
			  
		)
      ),


	  
	  array(
        'label'       => esc_html__( 'Footer Settings', 'iteck_plg' ),
        'id'          => 'footer_setting_section',
        'type'        => 'tab',
      ),
 
	  array(
        'label'       => esc_html__( 'Use Custom Footer', 'iteck_plg' ),
		    'desc'          =>  '',
        'id'          => 'custom_footer_choice',
        'type'        => 'select',
    		'std'		 => 'global',
    		'choices'     => array( 
    			  array(
                    'value'       => 'global',
                    'label'       => esc_html__( 'Use Global Settings (in Theme Options) Footer', 'iteck_plg' )
                  ),
    			  array(
                    'value'       => 'standard',
                    'label'       => esc_html__( 'Use Default Footer', 'iteck_plg' )
                  ),
    			  array(
                    'value'       => 'custom_footer',
                    'label'       => esc_html__( 'Use Custom Footer', 'iteck_plg' )
                  ),
    			  array(
                    'value'       => 'no_footer',
                    'label'       => esc_html__( 'No Footer', 'iteck_plg' )
                  ),
    			  
    		)
      ),
	  
	
	  
	  array(
        'id'          => 'footer_list',
        'label'       => esc_html__( 'Choose Custom Footer', 'iteck_plg' ),
        'desc'        => '',
        'std'         => '',
    		'condition'   => 'custom_footer_choice:is(custom_footer)',
            'type' => 'custom-post-type-select',
            'rows'        => '',
            'post_type'   => 'footer',
            'taxonomy'    => '',
            'min_max_step'=> '',
            'class'       => ''
          ),
	  
     ),

    array(
        'label'       => esc_html__( 'side panel Settings', 'iteck_plg' ),
        'id'          => 'sidepanel_setting_section',
        'type'        => 'tab',
      ),
    array(
        'label'       => esc_html__( 'Use Custom sidepanel', 'iteck_plg' ),
        'desc'          =>  '',
        'id'          => 'custom_sidepanel_choice',
        'type'        => 'select',
        'std'    => 'global',
        'choices'     => array( 
            array(
                    'value'       => 'global',
                    'label'       => esc_html__( 'Use Global Settings (in Theme Options) sidepanel', 'iteck_plg' )
                  ),
            array(
                    'value'       => 'standard',
                    'label'       => esc_html__( 'Use Default Footer', 'iteck_plg' )
                  ),
            array(
                    'value'       => 'custom_sidepanel',
                    'label'       => esc_html__( 'Use Custom Footer', 'iteck_plg' )
                  ),
            array(
                    'value'       => 'no_sidepanel',
                    'label'       => esc_html__( 'No sidepanel', 'iteck_plg' )
                  ),
            
        )
      ),
    array(
        'id'          => 'sidepanel_list',
        'label'       => esc_html__( 'Choose Custom sidepanel', 'iteck_plg' ),
        'desc'        => '',
        'std'         => '',
        'condition'   => 'custom_sidepanel_choice:is(custom_sidepanel)',
            'type' => 'custom-post-type-select',
            'rows'        => '',
            'post_type'   => 'sidepanel',
            'taxonomy'    => '',
            'min_max_step'=> '',
            'class'       => ''
          ),

  );

}

