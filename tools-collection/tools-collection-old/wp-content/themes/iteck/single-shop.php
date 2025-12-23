<?php
/*
Template Name: Shop Page template
Template Post Type: page
Description:One Page template for with container for cart, checkout, account and order tracking.
*/
get_header(); 
		
	//custom header
	if ( class_exists('ReduxFrameworkPlugin') ) { 
		do_action('iteck-custom-header','iteck_header_start') ;        
	} else { ?>
		<!--Fall back if no reduxoptions instaliteck-->
		<!--HOME START-->
		<div class="default-header clearfix">
			<!--HEADER START-->
			<?php get_template_part( 'inc/menu','normal'); ?>
			<!--HEADER END-->
		</div><!--/home end-->
		<!--HOME END-->
	<?php }?>

	<div class="content shop-wrapper">  
		<div class="container clearfix">
			<div class="row clearfix">
				<div class="col-md-12 shop-content">

				<?php
				//page content
				echo'<div class="blank-shop">';
				while (have_posts()) : the_post();
					the_content();
				endwhile;
				echo'</div>';
				?>
				</div><!--/.col-md-12-->
				
			</div><!--/.row-->
		</div><!--/.container-->
	</div><!--/.blog-wrapper-->

	<?php
	//custom footer
	if ( class_exists('ReduxFrameworkPlugin') ) { 
		do_action('iteck-custom-footer','iteck_footer_start');
	} else {
		//Fall back if no reduxoptions instaliteck
		get_template_part( 'inc/bottom','footer'); 
	}
		
get_footer(); ?>