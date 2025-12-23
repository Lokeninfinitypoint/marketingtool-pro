<?php

get_header(); 

/*--Start custom header--*/
if ( class_exists('ReduxFrameworkPlugin') ) { 
	do_action('iteck-custom-header','iteck_header_start') ;        
} else { ?>

	<div class="default-header clearfix">
		<?php get_template_part( 'inc/menu','normal'); ?>
	</div><!--  End home  -->

<?php } 

//custom post

	if ( class_exists('ReduxFrameworkPlugin')) { 
		$style = iteck_option( 'iteck_single_type_layout' );
		get_template_part('templates/post/post', $style);
	} else {
		//Fall back if no reduxoptions instaliteck 
		get_template_part('templates/post/post','2'); 
	}  

//custom footer
	if ( class_exists('ReduxFrameworkPlugin') ) { 
		do_action('iteck-custom-footer','iteck_footer_start');
	} else {
		//Fall back if no reduxoptions instaliteck 
		get_template_part( 'inc/bottom','footer'); 
	}  

get_footer(); ?>