<?php
/**
 * The template part for header
 *
 * @package Software Agency 
 * @subpackage software-agency
 * @since software-agency 1.0
 */
?>
<div class="main-header <?php if( get_theme_mod( 'software_agency_sticky_header', false) == 1 || get_theme_mod( 'software_agency_stickyheader_hide_show', false) == 1) { ?> header-sticky"<?php } else { ?>close-sticky <?php } ?>">
  <div id="header" class="py-2 border-bottom">
    <div class="container">
      <div class="row">
        <div class="col-lg-9 col-md-7 col-3 border-right">
          <?php ?>
            <div class="toggle-nav mobile-menu">
              <button role="tab" onclick="software_agency_menu_open_nav()" class="responsivetoggle"><i class="p-3 <?php echo esc_attr(get_theme_mod('software_agency_res_open_menu_icon','fas fa-bars')); ?>"></i><span class="screen-reader-text"><?php esc_html_e('Open Button','software-agency'); ?></span></button>
            </div>
          <?php  ?>
          <div id="mySidenav" class="nav sidenav">
            <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Top Menu', 'software-agency' ); ?>">
              <?php
                wp_nav_menu( array( 
                  'theme_location' => 'primary',
                  'container_class' => 'main-menu my-2 clearfix' ,
                  'menu_class' => 'clearfix',
                  'items_wrap' => '<ul id="%1$s" class="%2$s mobile_nav">%3$s</ul>',
                  'fallback_cb' => 'wp_page_menu',
                ) );
               ?>
              <a href="javascript:void(0)" class="closebtn mobile-menu" onclick="software_agency_menu_close_nav()"><i class="<?php echo esc_attr(get_theme_mod('software_agency_res_close_menu_icon','fas fa-times')); ?>"></i><span class="screen-reader-text"><?php esc_html_e('Close Button','software-agency'); ?></span></a>
            </nav>
          </div>
        </div>
        <div class="col-lg-1 col-md-2 col-3 pe-lg-0">
          <?php if(class_exists('woocommerce')){ ?>
            <span class="cart_no my-3 d-inline-block">
              <a href="<?php if(function_exists('wc_get_cart_url')){ echo esc_url(wc_get_cart_url()); } ?>" title="<?php esc_attr_e( 'shopping cart','software-agency' ); ?>"><i class="fas fa-cart-plus"></i><span class="screen-reader-text"><?php esc_html_e( 'shopping cart','software-agency' );?></span></a>
              <span class="cart-value"><?php esc_html_e('Cart','software-agency'); ?> (<?php echo esc_attr( wp_kses_data( WC()->cart->get_cart_contents_count()));?>)</span>
            </span>
          <?php } ?>
        </div>
        <div class="col-lg-2 col-md-3 col-6">
          <?php if( get_theme_mod('software_agency_get_started_link') != '' || get_theme_mod('software_agency_get_started_text') != ''){ ?>
            <div class="get-start-btn py-2 px-3 text-center my-2">
              <a href="<?php echo esc_url(get_theme_mod('software_agency_get_started_link',''));?>"><?php echo esc_html(get_theme_mod('software_agency_get_started_text',''));?></a>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>  