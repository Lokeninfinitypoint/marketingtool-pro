<?php
/*
* Related Post  
*/ 
?>
<?php $iteck_single_layout  = iteck_option( 'single_sidebar_layout', 'right' );  ?>
<?php $iteck_related_posts  = iteck_option( 'Single_related_posts', 3 );  ?>

<?php
 
wp_reset_postdata(); 
$related = iteck_related_post( get_the_ID(), $iteck_related_posts );

if( $related->have_posts() ):
?>
	<div class="related-bottom">
		<div class="container clearfix">
			<div class="row">
				<div class="col">
					<!--RELATED POST-->
					<div class="iteck-related-slider">
						<section class="popular-posts related Posts section-padding bg-gray5">
								<h5 class="fw-bold text-uppercase mb-30"><?php echo esc_html__('Related Posts','iteck'); ?></h5>
								<div class="related-postes-slider position-relative">
									<div class="swiper-container">
										<div class="swiper-wrapper">

											<?php while( $related->have_posts() ): $related->the_post();?>

												<div class="swiper-slide">
													<div class="card border-0 bg-transparent rounded-0 p-0  d-block">
														<div class="img radius-7 overflow-hidden">
														<?php 
															if ( has_post_thumbnail() ) {
																the_post_thumbnail( 'iteck-related-post' );
															} ?>
														</div>
														<div class="card-body px-0">
															<small class="d-block date mt-10 fs-10px">
																<span class="text-uppercase border-end brd-gray pe-3 me-3 cat"><?php the_category(', '); ?></span>
																<i class="bi bi-clock me-1"></i>
																<span class="op-8"><?php echo esc_html(__('Posted on ', 'iteck').iteck_time_ago()); ?></span>
															</small>
															<h5 class="fw-bold mt-10 title">
																<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
															</h5>
															<?php if( '' !== get_post()->post_content ){?>
																<p class="mt-2 op-8 fs-10px">
																	<?php $excerpt = get_the_excerpt();
																	$excerpt = substr( $excerpt , 0,'84'); 
																	echo esc_html($excerpt.' ..');?>
																</p>
															<?php } ?>
															<div class="d-flex small mt-20 align-items-center justify-content-between op-9">
																<div class="l_side d-flex align-items-center">
																	<span class="icon-20 rounded-circle d-inline-flex justify-content-center align-items-center text-uppercase bg-main p-1 me-2 text-white post-auth">
																	<i class="lnr lnr-user fw-600"></i>
																	</span>
																	<span class="mt-1">
																	<?php the_author_posts_link(); ?>
																	</span>
																</div>
																<div class="r-side mt-1">
																	<i class="bi bi-chat-left-text me-1"></i> 
																	<span>
																		<?php 
																			if(get_comments_number()==1){
																			echo esc_attr($post->comment_count); 
																			}else{
																				echo esc_attr($post->comment_count); 
																			}

																		?>
																	</span>
																	<i class="bi bi-eye ms-4 me-1"></i>
																	<span><?php echo esc_html(iteck_get_post_view()); ?></span>
																</div>
															</div>
														</div>
													</div>
												</div>

											<?php  endwhile; ?>
										</div>
									</div>
									<div class="swiper-button-next"></div>
									<div class="swiper-button-prev"></div>
								</div>
						</section>
						<?php endif; wp_reset_postdata(); ?>
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>