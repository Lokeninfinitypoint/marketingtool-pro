<?php

get_header(); 
		
	//custom header
	if ( class_exists('ReduxFrameworkPlugin') ) { 
		do_action('iteck-custom-header','iteck_header_start') ;        
	} else { ?>
	
		<!--Fall back if no reduxoptions instaliteck-->
		<div class="default-header clearfix">
			<?php get_template_part( 'inc/menu','normal'); ?>
		</div><!--/home end-->
		
	<?php } ?>

<?php 
	//custom side panel


	if ( class_exists('ReduxFrameworkPlugin') && iteck_option( 'iteck_sidepanel_set')) { 
		echo'<div class="side-panel">';
		echo'<div class="close-black-block-offcanvas"><a href="#"><i class="fa fa-times"></i></a></div>';
		do_action('iteck-custom-sidepanel','iteck_sidepanel_start');
		echo'</div>';
	}
	
?>
	<div class="content blog-wrapper">  
		<div class="container clearfix">
			 <div class="row clearfix">
				<div class="<?php if (is_active_sidebar( 'main-sidebar' )){ echo 'col-md-8';} 
					else { echo 'col-md-12';}?> blog-content">
				
					<!--BLOG POST START-->
					<?php while (have_posts()) : the_post(); ?>
					
						<article id="post-<?php  the_ID(); ?>" <?php  post_class('clearfix blog-post'); ?>>
							<?php  the_post_thumbnail(); ?> 
							<div class="spc-10 clearfix"></div>
							<h3 class="entry-title"><?php the_title(); ?></h3>

							<div class="spc-30 clearfix"></div>
								<?php the_content(); ?>
							<div class="clearfix"></div>
								 
							<div class="post-pager clearfix">
								<?php wp_link_pages(array(
									'before'=> '<p class"pagins">' . esc_html__( 'Pages:', 'iteck' ),
									'after' => '</p>')); 
								?>
							</div>
							<div class="border-post clearfix"></div>
							<div class="clearboth spc-40"></div> 
						</article><!--/.blog-post-->

					<?php endwhile; ?>

					<!--BLOG POST END-->
					<?php if ( comments_open() ) { ?>
					   <div id="comments" class="comments clearfix"><?php comments_template(); ?></div>
					<?php } ?>
					
					<div class="spc-40 clearfix"></div>
				</div><!--/.col-md-8-->
				
				<?php get_sidebar(); ?>
			 </div><!--/.row-->
		</div><!--/.container-->
	</div><!--/.blog-wrapper-->
	
	<?php //custom footer
	if ( class_exists('ReduxFrameworkPlugin') ) { 
		do_action('iteck-custom-footer','iteck_footer_start');
	} else {
		//Fall back if no reduxoptions instaliteck
		get_template_part( 'inc/bottom','footer'); 
	}
		
get_footer(); ?>