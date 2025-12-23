<?php
/*
* Header Menu Loop
*/
global $iteck_theme_settings;
global $post;  
if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
	$logo_height= iteck_option('iteck_logo_dim');
	$logo_height = $logo_height['height'];
	$logo_height_css = 'height:'.$logo_height;
	$logo_height_style = !empty($logo_height_css) ? ' style='.$logo_height_css : ''; 

} else{$logo_height_style ="";}
?> 
<nav class="header apply-header not-custom-menu clearfix 
	<?php if ( is_singular() && class_exists('ReduxFrameworkPlugin') && (iteck_option( 'iteck_header_position' ) =='head_white')){ 
		echo 'white-header shadow-header';
	} else { 
		echo 'custom-absolute-menu';
	} ?>
	 ">

	<div class="nav-box">
		<div class="stuck-nav">
			<div class="header-top d-none d-md-block" > <!-- hidden-xs hidden-sm -->
				<div class="container-fluid">
					<div class="col"> 
						<?php if ( class_exists( 'ReduxFrameworkPlugin' ) ) :
									if ( iteck_option( 'iteck_header_enable_topmenu' ) == 'on' && iteck_option( 'iteck_header_phone') ) :  ?>
						<h6><i class="fa fa fa-mobile-phone"> </i><?php echo esc_attr( iteck_option( 'iteck_header_phone')); ?></h6>
						<?php endif; 
									endif; ?>

						<?php if ( class_exists( 'ReduxFrameworkPlugin' ) ) :
									if ( iteck_option( 'iteck_header_enable_topmenu' ) == 'on' && iteck_option( 'iteck_header_mail') ) :  ?>
						<h6><i class="fa fa fa-envelope-o"> </i><?php echo esc_attr( iteck_option( 'iteck_header_mail')); ?></h6>
						<?php endif; 
									endif; ?>

						<?php if ( class_exists( 'ReduxFrameworkPlugin' ) ) :
									if ( iteck_option( 'iteck_header_enable_topmenu' ) == 'on' && iteck_option( 'iteck_header_address') ) :  ?>

						<h6><i class="fa fa fa-map-marker"> </i><?php echo esc_attr( iteck_option( 'iteck_header_address')); ?></h6>
						<?php endif; 
									endif; ?>
						<?php if ( class_exists( 'ReduxFrameworkPlugin' ) ) :
									if ( iteck_option( 'iteck_header_enable_topmenu' ) == 'on' && iteck_option( 'iteck_header_join') ) :  ?>
						<h6 class="pull-right"><a href="<?php  echo esc_url( iteck_option( 'iteck_header_joinlink') ); ?>"> <i class="fa fa-user"> </i><?php echo esc_attr( iteck_option( 'iteck_header_join')); ?></a></h6>
						<?php endif; 
									endif; ?>
					</div>
				</div>
			</div>
			 <div class="container-fluid">
				<div class="top-logo">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"> 
						<?php if ( is_singular() && iteck_option( 'iteck_header_position' ) =='head_white'){ ?> 
							<img alt="<?php esc_attr_e ('Logo','iteck'); ?>" class="logo1 logo-l-mode" <?php echo esc_html($logo_height_style); ?> src ="<?php 
								if ( iteck_option('iteck_header_logo_white') ) {
									$iteck_header_logo_white = iteck_option('iteck_header_logo_white');
								   if(is_array($iteck_header_logo_white))
    									$iteck_header_logo_white =  $iteck_header_logo_white['url']; 
											echo esc_url ($iteck_header_logo_white); 
									} else { echo get_template_directory_uri(); ?>/assets/images/logo-white.png <?php } ?>">

									<img alt="<?php esc_attr_e ('Logo','iteck'); ?>" class="logo1 logo-d-mode" <?php echo esc_html($logo_height_style); ?> src ="<?php 
										if ( iteck_option('iteck_header_logo_dark') ) {
											$iteck_header_logo_dark = iteck_option('iteck_header_logo_dark');
										   if(is_array($iteck_header_logo_dark))
		    									$iteck_header_logo_dark =  $iteck_header_logo_dark['url']; 
													echo esc_url ($iteck_header_logo_dark); 
											} else { echo get_template_directory_uri(); ?>/assets/images/logo-dark.png <?php } ?>">

								<?php } else { ?>

							<img alt="<?php esc_attr_e ('Logo','iteck'); ?>" class="logo1 logo-dark" <?php echo esc_html($logo_height_style); ?> src="<?php 
								if ( is_singular() && class_exists('ReduxFrameworkPlugin')&& iteck_option('iteck_header_logo_dark') ) {
									$iteck_header_logo_dark = iteck_option('iteck_header_logo_dark');
									   if(is_array($iteck_header_logo_dark))
        								$iteck_header_logo_dark =  $iteck_header_logo_dark['url'];
									
									echo esc_url ( $iteck_header_logo_dark); 
								} else { 
									echo get_template_directory_uri(); ?>/assets/images/logo-dark.png <?php 
								} ?>"> 

							<img alt="<?php esc_attr_e ('Logo','iteck'); ?>" class="logo1 logo-white" <?php echo esc_html($logo_height_style); ?> src="<?php 
								if ( is_singular() && class_exists('ReduxFrameworkPlugin')&& iteck_option('iteck_header_logo_white') ) {
									$iteck_header_logo_white = iteck_option('iteck_header_logo_white');
									   if(is_array($iteck_header_logo_white))
        								$iteck_header_logo_white =  $iteck_header_logo_white['url']; 
									echo esc_url ( $iteck_header_logo_white); 
								} else { 
									echo get_template_directory_uri(); ?>/assets/images/logo-white.png <?php 
								} ?>">

						<?php } ?>    
					</a>
				</div><!--End Logo--> 
				
				<div class="header-wrapper <?php  if ( class_exists('ReduxFrameworkPlugin') && (iteck_option( 'iteck_menu_position' ) =='center')) { echo 'dflex';}?> d-none d-md-block" > <!-- hidden-xs hidden-sm -->
					<div class="main-menu menu-wrapper"> 
						<?php iteck_custom_menu_page ('iteck_header_menu');  ?>
					</div><!-- End menu-wrapper -->
				
					<ul class="header-icon d-none d-md-block ">  <!-- hidden-sm hidden-xs -->
						
						<?php 
						$iteck_open_social_new_tab  = iteck_option( 'iteck_header_social_new_tab', 'on' );
						if($iteck_open_social_new_tab == 'on'):
							$open_new_tab = ' target="_blank"'; 
						else:
							$open_new_tab = ''; 
						endif; ?>
						<?php if ( class_exists( 'ReduxFrameworkPlugin' ) ) :
							if ( iteck_option( 'iteck_header_enable_social' ) == 'on' && iteck_option( 'iteck_header_facebook') ) :  ?>
								<li>
									<a href="<?php  echo esc_url( iteck_option( 'iteck_header_facebook' ) ); ?>" <?php echo $open_new_tab; ?>>
										<i class="fa fa-facebook"></i>
									</a>
								</li>
							<?php endif; 
						endif; ?>

						<?php if ( class_exists( 'ReduxFrameworkPlugin' ) ) :
							if ( iteck_option( 'iteck_header_enable_social' ) == 'on' && iteck_option( 'iteck_header_twitter' ) ) :  ?>
								<li>
									<a href="<?php  echo esc_url( iteck_option( 'iteck_header_twitter' ) ); ?>" <?php echo $open_new_tab; ?>>
										<i class="fa fa-twitter"></i>
									</a>
								</li>
							<?php endif; 
						endif; ?>

						<?php if ( class_exists( 'ReduxFrameworkPlugin' ) ) :
							if ( (iteck_option( 'iteck_header_enable_social' ) == 'on') && iteck_option( 'iteck_header_instagram' ) ) :  ?>
								<li>
									<a href="<?php  echo esc_url( iteck_option( 'iteck_header_instagram' ) ); ?>" <?php echo $open_new_tab; ?>>
										<i class="fa fa-instagram"></i>
									</a>
								</li>
							<?php endif; 
						endif; ?>

						<?php if ( class_exists( 'ReduxFrameworkPlugin' ) ) :
							if ( iteck_option( 'iteck_header_enable_social' ) == 'on' && iteck_option( 'iteck_header_pinterest') ) :  ?>
								<li>
									<a href="<?php  echo esc_url(iteck_option( 'iteck_header_pinterest') ); ?>" <?php echo $open_new_tab; ?>>
										<i class="fa fa-pinterest"></i>
									</a>
								</li>
							<?php endif; 
						endif; ?>

						<?php if ( class_exists( 'ReduxFrameworkPlugin' ) ) :
							if ( iteck_option('iteck_header_enable_social') == 'on' && iteck_option( 'iteck_header_xing')) :  ?>
								<li>
									<a href="<?php  echo esc_url( iteck_option( 'iteck_header_xing') ); ?>" <?php echo $open_new_tab; ?>>
										<i class="fa fa-xing"></i>
									</a>
								</li>
							<?php endif; 
						endif; ?>
						<?php if ( class_exists( 'ReduxFrameworkPlugin' ) ) :
							if ( iteck_option('iteck_header_enable_social') == 'on' && iteck_option( 'iteck_header_linkedin')) :  ?>
								<li>
									<a href="<?php  echo esc_url( iteck_option( 'iteck_header_linkedin') ); ?>" <?php echo $open_new_tab; ?>>
										<i class="fa fa-linkedin"></i>
									</a>
								</li>
							<?php endif; 
						endif; ?>
						<?php if ( class_exists( 'ReduxFrameworkPlugin' ) ) :
							if ( iteck_option('iteck_header_enable_social') == 'on' && iteck_option( 'iteck_header_youtube')) :  ?>
								<li>
									<a href="<?php  echo esc_url( iteck_option( 'iteck_header_youtube') ); ?>" <?php echo $open_new_tab; ?>>
										<i class="fa fa-youtube"></i>
									</a>
								</li>
							<?php endif; 
						endif; ?>

					</ul><!-- top Socials -->


					<div class="search-icon-header d-none d-md-block " > <!-- hidden-xs hidden-sm -->
						<?php  if ( class_exists('ReduxFrameworkPlugin') && (iteck_option( 'iteck_header_search' ) =='on')) { ?>
						<a class="search"  href="#">
							<i class="fa fa-search"></i>
						</a>
						<div class="black-search-block">
							<div class="black-search-table">
								<div class="black-search-table-cell">
									<div>
										<?php $iteck_unique_id = iteck_unique_id( 'search-form-' ); ?>
										<form role="search" method="get" id="<?php echo esc_attr( $iteck_unique_id ); ?>" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
											<input type="search" class="focus-input" placeholder="<?php echo esc_attr__('Type search keyword...','iteck'); ?>" value="<?php get_search_query()?>" name="s">
											<input type="submit" class="searchsubmit" value="">
										</form>
									</div>
								</div>
							</div>
							<div class="close-black-block"><a href="#"><i class="fa fa-times"></i></a></div>
						</div>
						<?php } ?>

						<?php  if ( class_exists('ReduxFrameworkPlugin') && (iteck_option( 'iteck_header_cart' ) =='on')) { ?>

							<?php if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

								$count = WC()->cart->cart_contents_count;
								?>
								<a class="cart-contents 1" href="<?php echo wc_get_cart_url(); ?>" title="<?php esc_attr__( 'View your shopping cart','iteck' ); ?>">
									<?php 
									?>
									<span class="cart-contents-count"><?php echo esc_html( $count ); ?></span>
									<?php
									?></a>

							<?php } ?>
							<?php } ?>							

						<?php  if ( class_exists('ReduxFrameworkPlugin') && (iteck_option( 'iteck_header_btn' ) =='on')) { ?>

						<?php if ( class_exists('ReduxFrameworkPlugin') && iteck_option( 'iteck_menu_btn') && iteck_option( 'iteck_menu_btn_url') ) { ?>

							<div class="btn-nav-top 2">
				                <a  href="<?php  echo esc_url( iteck_option( 'iteck_menu_btn_url') ); ?>" target="_blank">
			                    <?php echo esc_attr( iteck_option( 'iteck_menu_btn')); ?>
			               		 </a>
							</div>


						<?php } ?>
						<?php }?>
					</div>
					

				</div><!-- header-wrapper -->  

				<div class="mobile-wrapper d-block d-md-none "> <!-- hidden-lg hidden-md -->
					<a href="#" class="hamburger"><div class="hamburger__icon"></div></a>
					<div class="fat-nav">
						<div class="fat-nav__wrapper">
							<div class="fat-list"> <?php iteck_custom_flat_menu_page ('iteck_header_menu'); ?></div>
						</div>
					</div>
				</div><!-- End mobile-wrapper -->  
				
			</div><!-- container-fluid -->  
		</div><!-- stuck-nav -->
	</div><!-- nav-box -->
</nav><!-- header -->
