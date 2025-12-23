<?php
/**
 * Template Name: Custom Home Page
 */

get_header(); ?>

<main id="maincontent" role="main">
  <?php do_action( 'software_agency_before_slider' ); ?>

  <?php if( get_theme_mod( 'software_agency_slider_arrows', true) == 1 || get_theme_mod( 'software_agency_resp_slider_hide_show', true) == 1) { ?>
  <?php if(get_theme_mod('software_agency_slider_type', 'Default slider') == 'Default slider' ){ ?>
    <section id="slider">
       <div id="carouselExampleCaptions" class="carousel carousel-fade" data-bs-ride="carousel">
        <?php $software_agency_pages = array();
          for ( $count = 1; $count <= 3; $count++ ) {
            $mod = intval( get_theme_mod( 'software_agency_slider_page' . $count ));
            if ( 'page-none-selected' != $mod ) {
              $software_agency_pages[] = $mod;
            }
          }
          if( !empty($software_agency_pages) ) :
            $args = array(
              'post_type' => 'page',
              'post__in' => $software_agency_pages,
              'orderby' => 'post__in'
            );
            $query = new WP_Query( $args );
            if ( $query->have_posts() ) :
              $i = 1;
        ?>
        <div class="carousel-inner" role="listbox">
          <?php while ( $query->have_posts() ) : $query->the_post(); ?>
            <div <?php if($i == 1){echo 'class="carousel-item active"';} else{ echo 'class="carousel-item"';}?>>
              <div class="row">
                <div class="col-lg-7 col-md-7 order-box1">
                  <div class="carousel-caption">
                    <?php if( get_theme_mod('software_agency_slider_title_hide_show',true) == 1){ ?>
                      <h1 class="mb-0 pt-0 wow slideInLeft delay-1000" data-wow-duration="2s"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
                    <?php } ?>
                    <div class="slide-search">
                      <?php get_search_form(); ?>
                    </div>
                  </div>
                </div>
                <div class="col-lg-5 col-md-5 order-box2">            
                  <div class="slider-box">
                    <?php if(has_post_thumbnail()){ ?>
                    <div class="slider-img"></div>
                  <?php the_post_thumbnail();
                } else{?>
                  <img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/block-patterns/images/banner.png" alt="" />
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
            <span class="carousel-control-prev-icon py-2 px-3 w-auto h-auto" aria-hidden="true"><i class="fas fa-chevron-left"></i></span>
            <span class="screen-reader-text"><?php esc_html_e( 'Previous','software-agency' );?></span>
          </a>
          <a class="carousel-control-next" data-bs-target="#carouselExampleCaptions" data-bs-slide="next" role="button">
            <span class="carousel-control-next-icon py-2 px-3 w-auto h-auto" aria-hidden="true"><i class="fas fa-chevron-right"></i></span>
            <span class="screen-reader-text"><?php esc_html_e( 'Next','software-agency' );?></span>
          </a>
      </div>
      <div class="clearfix"></div>
    </section>
  <?php } else if(get_theme_mod('software_agency_slider_type', 'Advance slider') == 'Advance slider'){?>
    <?php echo do_shortcode(get_theme_mod('software_agency_advance_slider_shortcode')); ?>
  <?php } ?>
  <?php }?>

  <?php do_action( 'software_agency_after_slider' ); ?>

  <section id="product-sec" class="pt-5 pb-5 wow zoomInDown delay-1000" data-wow-duration="3s"> 
    <div class="container">
      <?php $software_agency_product_page = array();
        $mod = absint( get_theme_mod( 'software_agency_product_settings','software-agency'));
        if ( 'page-none-selected' != $mod ) {
          $software_agency_product_page[] = $mod;
        }
        if( !empty($software_agency_product_page) ) :
          $args = array(
            'post_type' => 'page',
            'post__in' => $software_agency_product_page,
            'orderby' => 'post__in'
          );
          $query = new WP_Query( $args );
          if ( $query->have_posts() ) :
            $count = 0;
            while ( $query->have_posts() ) : $query->the_post(); ?>
              <div class="heading-txt mb-4">
                <h3><?php the_title(); ?></h3>
              </div>
              <?php the_content(); ?>
            <?php $count++; endwhile; ?>
          <?php else : ?>
            <div class="no-postfound"></div>
          <?php endif;
        endif;
        wp_reset_postdata();
      ?>
    </div>
  </section>

  <?php do_action( 'software_agency_after_service' ); ?>

  <div id="content-vw">
    <div class="container">
      <?php while ( have_posts() ) : the_post(); ?>
        <?php the_content(); ?>
      <?php endwhile; // end of the loop. ?>
    </div>
  </div>
</main>

<?php get_footer(); ?>