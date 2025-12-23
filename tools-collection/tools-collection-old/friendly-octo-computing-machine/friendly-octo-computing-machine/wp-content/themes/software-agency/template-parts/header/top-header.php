<?php
/**
 * The template part for top header
 *
 * @package Software Agency
 * @subpackage software-agency
 * @since software-agency 1.0
 */
?>

<div class="top-bar">
  	<div class="container">
	  	<div class="row">
	  		<div class="col-lg-8 col-md-7">
			    <?php if( get_theme_mod('software_agency_top_bar_text') != ''){ ?>
		      		<p class="py-2 py-lg-3 py-md-3 mb-0 text-center text-md-start text-lg-start"><?php echo esc_html(get_theme_mod('software_agency_top_bar_text',''));?></p>
			    <?php } ?>
			</div>
			<div class="col-lg-4 col-md-5">
				<div class="text-center text-md-end text-lg-end">
					<?php if( get_theme_mod('software_agency_support_link') != '' || get_theme_mod('software_agency_support_text') != ''){ ?>
			      		<a href="<?php echo esc_url(get_theme_mod('software_agency_support_link',''));?>" class="py-2 py-lg-3 py-md-3 me-2 d-inline-block"><?php echo esc_html(get_theme_mod('software_agency_support_text',''));?></a>/
				    <?php } ?>
				    <?php if( get_theme_mod('software_agency_wishlist_link') != '' || get_theme_mod('software_agency_wishlist_text') != ''){ ?>
			      		<a href="<?php echo esc_url(get_theme_mod('software_agency_wishlist_link',''));?>" class="py-2 py-lg-3 py-md-3 me-2 d-inline-block"><?php echo esc_html(get_theme_mod('software_agency_wishlist_text',''));?></a>/
				    <?php } ?>
				    <?php if(class_exists('woocommerce')){ ?>
			            <?php if ( is_user_logged_in() ) { ?>
		          			<a href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>" title="<?php esc_attr_e('My Account','software-agency'); ?>" class="py-2 py-lg-3 py-md-3 d-inline-block"><?php esc_html_e('My Account','software-agency'); ?><span class="screen-reader-text"><?php esc_html_e( 'My Account','software-agency' );?></span></a>
			            <?php }
			            else { ?>
		              		<a href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>" title="<?php esc_attr_e('Login - Register','software-agency'); ?>" class="py-2 py-lg-3 py-md-3 d-inline-block"><?php esc_html_e( 'Login - Register','software-agency' );?><span class="screen-reader-text"><?php esc_html_e( 'Login - Register','software-agency' );?></span></a>
			            <?php } ?>
	          		<?php }?>
	          	</div>
			</div>
		</div>
  	</div>
</div>