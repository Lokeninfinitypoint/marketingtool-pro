<?php
/*
Template Name: Blog Archive
Template Post Type: page, portfolio 
Description:One Page Builder with container.
*/

get_header();

 $iteck_blog_slider  = iteck_option( 'iteck_blog_slider', 'hide' );
 $iteck_blog_popular  = iteck_option( 'iteck_blog_popular', 'hide' );


    //custom header
    if ( class_exists('ReduxFrameworkPlugin') ) { 
        do_action('iteck-custom-header','iteck_header_start') ;        
    } else { ?>
        <!--Fall back if no reduxoptions instaliteck-->
        <div class="default-header clearfix">
            <?php get_template_part( 'inc/menu','normal'); ?>
        </div><!--/home end-->        
    <?php } 

    if ($iteck_blog_slider =='show') {
        get_template_part('templates/blog/slider', '1'); 
    }

    if ($iteck_blog_popular =='show') {
        get_template_part('templates/blog/popular', '1');
    }  
		//custom Blog
		$iteck_blog_layout  = iteck_option( 'blog_sidebar_layout', 'right' );  
        $style = iteck_option( 'iteck_blog_article_layout' );
		?>

		<div class="content blog-wrapper blog-style-<?php echo $style; ?>">  
			<div class="container clearfix">
				<div class="row clearfix">
					<?php if ($iteck_blog_layout =='left') { get_sidebar(); }?>
		
					<div class="<?php if ($iteck_blog_layout== 'none' || !is_active_sidebar( 'main-sidebar' ) ){ 
						echo 'col-md-12';
					}else{echo 'col-md-8';} ?> blog-content">
		
        			<?php 

                    // Set up the main WordPress query for posts
                    $args = array(
                        'post_type' => 'post',
                        'post_status' => 'publish',
                        'posts_per_page' => get_option('posts_per_page'),
                        'paged' => get_query_var('paged') ? get_query_var('paged') : 1
                    );
                    $blog_query = new WP_Query($args);


                    while ($blog_query->have_posts()) : $blog_query->the_post();  
							get_template_part( 'inc/loop', 'post' ); 
							endwhile ?>
						<ul class="pagination clearfix">
							<li><?php  previous_posts_link( esc_html__( 'Previous Page', 'iteck' ) ); ?></li>
							<li><?php next_posts_link( esc_html__( 'Next Page', 'iteck' ) ); ?> </li>
						</ul>
						<div class="spc-40 clearfix"></div>
					</div><!--/.blog-content-->
					
					<!--SIDEBAR (RIGHT)-->
					<?php if ( $iteck_blog_layout =='right') {get_sidebar();} ?>
					
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