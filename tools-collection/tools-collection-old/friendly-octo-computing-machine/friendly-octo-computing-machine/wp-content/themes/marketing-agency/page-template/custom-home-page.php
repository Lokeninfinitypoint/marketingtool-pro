<?php
/**
 * Template Name: Custom Home Page
 */

get_header(); ?>

<main id="maincontent" role="main">  
  <?php do_action( 'marketing_agency_before_slider' ); ?>

  <?php if( get_theme_mod( 'marketing_agency_slider_arrows', true) == 1 || get_theme_mod( 'marketing_agency_resp_slider_hide_show', true) == 1) { ?>
    <?php if(get_theme_mod('marketing_agency_slider_type', 'Default slider') == 'Default slider' ){ ?>
    <section id="slider">
      
        <div class="slider-box"></div>
        <div id="carouselExampleCaptions" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="<?php echo esc_attr(get_theme_mod( 'marketing_agency_slider_speed',3000)) ?>">  
          <?php $marketing_agency_pages = array();
            for ( $count = 1; $count <= 3; $count++ ) {
              $mod = intval( get_theme_mod( 'marketing_agency_slider_page' . $count ));
              if ( 'page-none-selected' != $mod ) {
                $marketing_agency_pages[] = $mod;
              }
            }
            if( !empty($marketing_agency_pages) ) :
              $args = array(
                'post_type' => 'page',
                'post__in' => $marketing_agency_pages,
                'orderby' => 'post__in'
              );
              $query = new WP_Query( $args );
              if ( $query->have_posts() ) :
                $i = 1;
          ?>
          <div class="carousel-inner" role="listbox">
            <?php while ( $query->have_posts() ) : $query->the_post(); ?>
              <div <?php if($i == 1){echo 'class="carousel-item active"';} else{ echo 'class="carousel-item"';}?>>
                <div class="container">
                  <div class="row">
                    <div class="col-lg-7 col-md-7 position-relative align-self-center mt-md-4 mt-0">
                      <div class="carousel-caption">
                        <h1 class="mb-0 pt-0 wow bounceInUp" data-wow-duration="2s"><?php the_title(); ?></h1>
                        <p class="wow bounceInUp" data-wow-duration="2s"><?php $marketing_agency_excerpt = get_the_excerpt(); echo esc_html( marketing_agency_string_limit_words( $marketing_agency_excerpt, esc_attr(get_theme_mod('marketing_agency_slider_excerpt_number','25')))); ?></p>
                        <div class="slider-btn wow slideInLeft delay-1000" data-wow-duration="2s">
                          <?php if ( get_theme_mod('marketing_agency_banner_button_label','GET STARTED') != '' ) {?>
                            <div class ="more-btn mt-md-4 mt-0">
                              <a class="px-4 px-lg-4 px-md-4 py-3 py-lg-3 py-md-3" href="<?php echo esc_url(get_theme_mod('marketing_agency_top_button_url',false));?>"><?php echo esc_html(get_theme_mod('marketing_agency_banner_button_label','GET STARTED'));?><span class="screen-reader-text"><?php esc_html_e( 'GET STARTED','marketing-agency');?></span></a>
                            </div>
                          <?php }?>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-5 col-md-5 align-self-center mt-md-5 mt-0">
                      <div class="slider-img"></div>
                      <?php if(has_post_thumbnail()){
                        the_post_thumbnail();
                      } else{?>
                        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/block-patterns/images/circle.png" alt="" />
                      <?php } ?>                    
                    </div>
                  </div>
                </div>              
              </div>
            <?php $i++; endwhile; 
            wp_reset_postdata();?>
          </div>
          <?php else : ?>
            <div class="no-postfound"></div>
          <?php endif;
          endif;?>
          <a class="carousel-control-prev" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev" role="button">
            <span class="carousel-control-prev-icon" aria-hidden="true"><i class="fas fa-caret-left"></i></span>
            <span class="screen-reader-text"><?php esc_html_e( 'Previous','marketing-agency' );?></span>
          </a>
          <a class="carousel-control-next" data-bs-target="#carouselExampleCaptions" data-bs-slide="next" role="button">
            <span class="carousel-control-next-icon" aria-hidden="true"><i class="fas fa-caret-right"></i></span>
            <span class="screen-reader-text"><?php esc_html_e( 'Next','marketing-agency' );?></span>
          </a>
        </div>
        <div class="clearfix"></div>
    </section>
      <?php } else if(get_theme_mod('marketing_agency_slider_type', 'Advance slider') == 'Advance slider'){?>
        <?php echo do_shortcode(get_theme_mod('marketing_agency_advance_slider_shortcode')); ?>
      <?php } ?>
  <?php }?>

  <?php do_action( 'marketing_agency_after_slider' ); ?>

  <section id="services-sec" class="text-center py-5 wow zoomInUp delay-1000" data-wow-duration="2s">
    <div class="container">
      <div class="heading-text px-3 px-lg-5 px-md- mb-4">
        <?php if( get_theme_mod( 'marketing_agency_section_title') != '') { ?>
          <h2><?php echo esc_html(get_theme_mod('marketing_agency_section_title',''));?></h2>
        <?php } ?>
      </div>
      <div class="row">
        <?php
        $marketing_agency_catData=  get_theme_mod('marketing_agency_services_category');
        if($marketing_agency_catData){
          $page_query = new WP_Query(array( 'category_name' => esc_html( $marketing_agency_catData ,'marketing-agency')));?>
          <?php while( $page_query->have_posts() ) : $page_query->the_post(); ?>
            <div class="col-lg-4 col-md-4">
              <div class="inner-box border p-3 mb-3">
                <h3><a href="<?php the_permalink();?>"><?php the_title();?><span class="screen-reader-text"><?php the_title(); ?></span></a></h3>
                <div class="serv-image">
                  <?php the_post_thumbnail(); ?>
                </div>
                <p class="mt-3"><?php $marketing_agency_excerpt = get_the_excerpt(); echo esc_html( marketing_agency_string_limit_words( $marketing_agency_excerpt, esc_attr(get_theme_mod('marketing_agency_services_excerpt_number','20')))); ?></p>
              </div>
            </div>
          <?php endwhile;
          wp_reset_postdata();
        } ?>
      </div>
    </div>
  </section>

  <?php do_action( 'marketing_agency_after_service' ); ?>

  <div id="content-vw">
    <div class="container">
      <?php while ( have_posts() ) : the_post(); ?>
        <?php the_content(); ?>
      <?php endwhile; // end of the loop. ?>
    </div>
  </div>
</main>

<?php get_footer(); ?>