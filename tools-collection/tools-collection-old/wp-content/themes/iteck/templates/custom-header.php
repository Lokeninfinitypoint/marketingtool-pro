<?php

get_header(); 

//custom header
if ( class_exists('ReduxFrameworkPlugin') ) { 
	do_action('iteck-custom-header','iteck_header_start') ;        
} else { 
	?>
	<!--Fall back if no reduxoptions instaliteck-->
	<div class="default-header clearfix">
		<?php get_template_part( 'inc/menu','normal'); ?>
	</div><!--/home end-->
	<?php 
} ?>
