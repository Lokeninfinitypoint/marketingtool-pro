<?php
/*
* Related Post 
*/ 
?>
<?php $iteck_single_layout  = iteck_option( 'single_sidebar_layout', 'right' );  ?>
<?php $iteck_related_posts  = iteck_option( 'Single_related_posts', 2 );  ?>


<?php 

$related = iteck_related_post( get_the_ID(), $iteck_related_posts );

if( $related->have_posts() ):
?>

<div id="related_posts" class="clearfix">
	<h4 class="title-related-post">
		<?php  esc_html_e('Related Posts', 'iteck'); ?>
	</h4>
	<div class="row"> 
		<?php 
		while( $related->have_posts() ):
			$related->the_post();?>
			<div class="<?php if ($iteck_related_posts==2 ){ echo 'col-sm-6 col-xs-12';}else{echo 'col-sm-4 col-xs-6';}?>"> 
				
				<?php if ( class_exists('ReduxFrameworkPlugin') && (iteck_option( 'iteck_related_image' ) =='show')) {  ?>
				<a href="<?php  the_permalink()  ?>" rel="bookmark" title="<?php  echo esc_attr( the_title_attribute()); ?>">
					<?php 
					if ( has_post_thumbnail() ) {
						the_post_thumbnail( 'iteck-related-post' );
					} ?>
				</a>
 
				<?php } ?>
				 
				<div class="related-inner clerfix">
					<div>
						<a href="<?php the_permalink(); ?>"><h3 class="related-title"><?php the_title(); ?></h3></a>
						<ul class="post-detail">
							<li>
								<i class="lnr lnr-user fw-600"></i> <?php the_author_posts_link(); ?> 
							</li>
							<li>
								<i class="lnr lnr-history"></i> <?php echo get_the_date(); ?> 
							</li>
						</ul>
						
						<?php if( '' !== get_post()->post_content ){?>
                        <p class="excerpt">
                            <?php $excerpt = get_the_excerpt();
                            $excerpt = substr( $excerpt , 0,'95'); 
                            echo esc_html($excerpt.' ..');?> 
                        </p>
                   		 <?php } ?>
					</div> 
				</div>
			</div><!--/.col-sm-4-->  
		<?php  endwhile; ?>
	</div><!--/.row--> 
</div><!--related-post-->
<?php endif;
wp_reset_postdata(); ?>