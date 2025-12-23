        

<?php 
 $iteck_blog_slider_title  = iteck_option( 'iteck_blog_slider_title', 'Our Journal' ); 
 $iteck_blog_slider_subtitle  = iteck_option( 'iteck_blog_slider_subtitle', 'Get the latest articles' ); 
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

$latestpost = array(
    'orderby'    => 'date',
    'order'      => 'DESC',
    'posts_per_page' => 3
);
 
// Invoke the query
$latest_posts = new WP_Query( $latestpost );



if( $latest_posts->have_posts() ):
?>
        <!-- ====== start blog-slider ====== -->

        <section class="blog-top-slider pt-50 pb-50 style-1">
            <div class="container d-block">
                <div class="section-head text-center mb-60 style-5">
                    <h2 class="mb-20 m-title"> <?php echo wp_kses($iteck_blog_slider_title,$allow_html); ?></h2>
                    <div class="text color-666"><?php echo wp_kses($iteck_blog_slider_subtitle,$allow_html); ?></div>
                </div>
                <div class="blog-details-slider">
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            <?php while( $latest_posts->have_posts() ): $latest_posts->the_post();?>
                                <div class="swiper-slide">
                                    <div class="content-card">
                                        <div class="img overlay">
                                            <?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'iteck-latest-post' ); } ?>
                                        </div>
                                        <div class="info">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="cont">
                                                        <small class="date small mb-20"> <span class="text-uppercase border-end brd-gray pe-3 me-3"> <?php the_category(', '); ?> </span> <i class="far fa-clock me-1"></i> <?php echo esc_html(__('Posted on', 'iteck').iteck_time_ago()); ?> </small>
                                                        <h2 class="title">
                                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                                        </h2>
                                                        <p class="fs-12px mt-10 text-light text-info">
                                                            <?php $excerpt = get_the_excerpt();
                                                            $excerpt = substr( $excerpt , 0,'84'); 
                                                            echo esc_html($excerpt.' ..');?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php  endwhile; ?>

                        </div>
                    </div>

                    <!-- -- pagination -- -->
                    <div class="swiper-pagination"></div>
                    <!-- -- arrows -- -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>
        </section>
<?php endif; wp_reset_postdata(); ?>
        <!-- ====== end blog-slider ====== -->