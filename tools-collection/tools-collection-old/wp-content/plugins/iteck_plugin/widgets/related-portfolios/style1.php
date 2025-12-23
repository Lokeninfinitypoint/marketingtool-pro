
	<?php // get the custom post type's destudio_taxonomy terms
	global $post ;
	$custom_taxterms = wp_get_object_terms( $post->ID, 'portfolio_category', array('fields' => 'ids') );
	// arguments
	$args = array(
		'post_type' => 'portfolio',
		'post_status' => 'publish',
		'posts_per_page' => 4, 
		'orderby' => 'rand',
		'tax_query' => array(
		array(
			'taxonomy' => 'portfolio_category',
			'field' => 'id',
			'terms' => $custom_taxterms
			)
		),
		'post__not_in' => array ($post->ID),
	);
	$related_items = new WP_Query( $args );


	$destudio_work = new \WP_Query(array(
		'paged' => $iteck_paged,
		'posts_per_page'   => $settings['portfolio_item'],
		'post_type' =>  'portfolio', 'iteck_plg',
		$order       =>  $ord_val
	)); 


	?>

<div class="iteck-related-port">
	<div class="slider-3items slider-style-6">
		<div class="swiper-container pb-0">
			<div class="swiper-wrapper">
				<?php while ( $related_items->have_posts() ) : $related_items->the_post(); ?>
				<div class="swiper-slide">
					<div  class="project-card style-6">
						<div class="img"> 
							<img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="img">
						</div><!-- .img --> 
						<div class="info">
							<a href="<?php the_permalink(); ?>" class="title"> <?php the_title();?> </a>
							<small class="color-blue6">
								<?php
									$destudio_taxonomy = 'portfolio_category';
									$destudio_taxs = wp_get_post_terms($post->ID, $destudio_taxonomy);
									$destudio_cats = array();
									$count = 1;
									foreach ($destudio_taxs as $destudio_tax) { 
										if($count != 1) echo ', '; ?>
										<a class="cat" href="<?php echo esc_url( get_term_link( $destudio_tax->slug, $destudio_taxonomy ) ); ?>"><?php echo $destudio_tax->name; ?></a>
										<?php $count++;
									}; 
								?>
							</small>
							<div class="text">
								<?php the_excerpt(); ?>
							</div>
							<div class="tags">


										<?php if  (has_tag()) { ?>
											the_tags(' : ');?>
										<?php } ?>


										<?php
											$iteck_taxonomy_tag = 'porto_tag';
											$iteck_taxs_tag = wp_get_post_terms($post->ID, $iteck_taxonomy_tag);
											$iteck_tags = array();
											$count = 1;
											foreach ($iteck_taxs_tag as $iteck_tax_tag) { 
												if($count != 1) ?>
												<span>
													<a class="cat" href="<?php echo esc_url( get_term_link( $iteck_tax_tag->slug, $iteck_taxonomy_tag ) ); ?>"><?php echo $iteck_tax_tag->name; ?></a>
												</span>
												<?php $count++;
											}; ?>  
							</div>
								
						</div><!-- .info -->
					</div><!-- .project-card -->
				</div>
				<?php endwhile; wp_reset_postdata();?>

			</div>
		</div>
		<!-- --------- arrows --------- -->
		<div class="swiper-button-next"></div>
		<div class="swiper-button-prev"></div>
	</div>
</div>
