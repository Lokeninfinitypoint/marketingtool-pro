
<?php 
//custom footer
if ( class_exists('ReduxFrameworkPlugin') ) { 
	do_action('iteck-custom-footer','iteck_footer_start');
} else {
	//Fall back if no reduxoptions instaliteck
	get_template_part( 'inc/bottom','footer'); 
}

get_footer(); ?>