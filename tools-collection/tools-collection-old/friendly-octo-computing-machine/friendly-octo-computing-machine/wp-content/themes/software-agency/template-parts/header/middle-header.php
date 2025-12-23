<?php
/**
 * The template part for top header
 *
 * @package Software Agency
 * @subpackage software-agency
 * @since software-agency 1.0
 */
?>

<div class="middle-bar text-center text-lg-start text-md-start py-2 border-bottom">
  <div class="container">
    <div class="row">
      <div class="col-lg-4 col-md-12">
        <div class="logo text-md-center text-lg-start py-md-2 py-lg-0">
          <?php if ( has_custom_logo() ) : ?>
            <div class="site-logo"><?php the_custom_logo(); ?></div>
          <?php endif; ?>
          <?php $blog_info = get_bloginfo( 'name' ); ?>
            <?php if ( ! empty( $blog_info ) ) : ?>
              <?php if ( is_front_page() && is_home() ) : ?>
                <?php if( get_theme_mod('software_agency_logo_title_hide_show',true) == 1){ ?>
                  <p class="site-title py-1"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
                <?php } ?>
              <?php else : ?>
                <?php if( get_theme_mod('software_agency_logo_title_hide_show',true) == 1){ ?>
                  <p class="site-title py-1 mb-0"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
                <?php } ?>
              <?php endif; ?>
            <?php endif; ?>
            <?php
              $description = get_bloginfo( 'description', 'display' );
              if ( $description || is_customize_preview() ) :
            ?>
            <?php if( get_theme_mod('software_agency_tagline_hide_show',false) == 1){ ?>
              <p class="site-description mb-0">
                <?php echo esc_html($description); ?>
              </p>
            <?php } ?>
          <?php endif; ?>
        </div>
      </div>
      <div class="col-lg-3 col-md-4 my-2 align-self-lg-center">
        <?php if (is_active_sidebar('social-links')) : ?>
          <?php dynamic_sidebar('social-links'); ?>
        <?php else : ?>
          <!-- Default Social Icons Widgets -->
            <div class="widget">
                <ul class="custom-social-icons" >
                  <li><a href="https://twitter.com" target="_blank"><i class="fab fa-twitter"></i></a></li> 
                  <li><a href="https://facebook.com" target="_blank"><i class="fab fa-facebook"></i></a></li> 
                  <li><a href="https://pinterest.com" target="_blank"><i class="fab fa-pinterest"></i></a></li>
                  <li><a href="https://instagram.com" target="_blank"><i class="fab fa-instagram"></i></a></li> 
               </ul>
            </div>
        <?php endif; ?>  
      </div>
      <div class="col-lg-2 col-md-4 border-right my-2 align-self-lg-center">
        <?php if( get_theme_mod('software_agency_phone_number_text') != '' || get_theme_mod('software_agency_phone_number') != ''){ ?>
          <div class="row">
            <div class="col-lg-3 col-md-3">
              <i class="fas fa-phone my-1"></i>
            </div>
            <div class="col-lg-9 col-md-9">
              <p class="mb-0"><?php echo esc_html(get_theme_mod('software_agency_phone_number_text',''));?></p>
              <strong class=""><a href="tel:<?php echo esc_attr( get_theme_mod('software_agency_phone_number','') ); ?>"><?php echo esc_html(get_theme_mod('software_agency_phone_number',''));?></a></strong>
            </div>
          </div>
        <?php } ?>
      </div>
      <div class="col-lg-3 col-md-4 my-2 align-self-lg-center">
        <?php if( get_theme_mod('software_agency_email_address_text') != '' || get_theme_mod('software_agency_email_address') != ''){ ?>
          <div class="row">
            <div class="col-lg-3 col-md-3">
              <i class="fas fa-envelope-open my-1"></i>
            </div>
            <div class="col-lg-9 col-md-9">
              <p class="mb-0"><?php echo esc_html(get_theme_mod('software_agency_email_address_text',''));?></p>
              <strong class=""><a href="mailto:<?php echo esc_attr(get_theme_mod('software_agency_email_address',''));?>"><?php echo esc_html(get_theme_mod('software_agency_email_address',''));?></a></strong>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>