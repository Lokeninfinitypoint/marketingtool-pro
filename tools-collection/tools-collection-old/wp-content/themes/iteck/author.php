<?php

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
    <?php }  ?>
    <div class="iteck-author">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h1>   <?php echo esc_html__( 'Author: ', 'iteck' ).'<span>'.esc_html(get_the_author_meta('display_name')).'</span>'; ?> </h1> 
                </div>
            </div>
        </div>
    </div>
            
 <?php
    //custom Blog
    if ( class_exists('ReduxFrameworkPlugin')) { 
        $style = iteck_option( 'iteck_blog_article_layout' );
        get_template_part('templates/blog/blog', $style);
    } else {
        //Fall back if no reduxoptions instaliteck 
        get_template_part('templates/blog/blog','1'); 
    } 
    
    //custom footer
    if ( class_exists('ReduxFrameworkPlugin') ) { 
        do_action('iteck-custom-footer','iteck_footer_start');
    } else {
        //Fall back if no reduxoptions instaliteck 
        get_template_part( 'inc/bottom','footer'); 
    }
        
get_footer(); ?>