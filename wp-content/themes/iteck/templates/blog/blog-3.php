<?php $iteck_blog_layout  = iteck_option( 'blog_sidebar_layout', 'right' );  ?>


<div class="content blog-wrapper blog-style-3">  
    <div class="container clearfix">
        <div class="row clearfix">
        <?php if ($iteck_blog_layout =='left') { get_sidebar(); }?>

        <div class="<?php if ($iteck_blog_layout== 'none' || !is_active_sidebar( 'main-sidebar' ) ){ 
            echo 'col-md-12';
        }else{echo 'col-md-8';} ?> blog-content">

                <?php while (have_posts()) : the_post(); 
                    get_template_part( 'templates/blog/loop/style', '3' );  
                    
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