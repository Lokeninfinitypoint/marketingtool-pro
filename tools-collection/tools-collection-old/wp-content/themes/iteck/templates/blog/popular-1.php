<?php 
 $iteck_blog_popular_title  = iteck_option( 'iteck_blog_popular_title', 'Popular Posts' ); 
 $allow_html= array(
    'a' => array(
        'href' => array(),
        'title' => array()
    ),
    'br' => array(),
    'em' => array(),
    'strong' => array(),
    'span' => array(
        'class' => array(),
    )
);

$popularpostbyview = array(
    'meta_key'  => 'post_views_count', // set custom meta key
    'orderby'    => 'meta_value_num',
    'order'      => 'DESC',
    'posts_per_page' => 3
);
 
// Invoke the query
$prime_posts = new WP_Query( $popularpostbyview );

?>


<!-- ====== start Popular Posts ====== -->
<section class="popular-posts related Posts section-padding border-bottom brd-gray pt-50 pb-100">
                        <h5 class="fw-normal text-center text-uppercase mb-70"><?php echo wp_kses($iteck_blog_popular_title,$allow_html); ?></h5>
                        <div class="related-postes-slider position-relative">
                            <div class="container">
                                <div class="row gx-5">

                                    <?php while( $prime_posts->have_posts() ): $prime_posts->the_post();?>

                                        <div class="col-lg-4">
                                            <div class="card border-0 bg-transparent rounded-0 mb-30 mb-lg-0 d-block">
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
                                                        <span class="op-8"><?php echo esc_html(__('Posted on', 'iteck').iteck_time_ago()); ?></span>
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
                                                            <span class="mt-1">
                                                            <i class="lnr lnr-user fw-600"></i> <?php the_author_posts_link(); ?>
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
                                                            <span><?php echo esc_attr(iteck_get_post_view()); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php  endwhile; wp_reset_postdata(); ?>
                                </div>
                            </div>
                        </div>
                </section>
<!-- ====== end Popular Posts ====== -->
