<?php
/*
* Bottom Footer 
*/
global $iteck_theme_settings;

$iteck_footer_logo_white = iteck_option('iteck_footer_logo_white');
$iteck_footer_logo_dark = iteck_option('iteck_footer_logo_dark');
?>

<footer class="footer">
	<div class="container-fluid">
		
		<?php if ( class_exists( 'ReduxFrameworkPlugin' ) && iteck_option( 'iteck_footer_logo_white' ) != '' ) : ?>
			<img class="footer-img" src="<?php echo esc_url ( $iteck_footer_logo_white['url']); ?>" alt="<?php esc_attr_e ('LogoWhite','iteck'); ?>">
		<?php elseif ( class_exists('ReduxFrameworkPlugin' ) && iteck_option( 'iteck_footer_logo_dark' ) != '') : ?>
			<img class="footer-img" src="<?php echo esc_url ( $iteck_footer_logo_dark['url']); ?>"   alt="<?php esc_attr_e ('LogoDark','iteck'); ?>">
		<?php endif; ?>

		<div class="clearboth clearfix"></div>

		<ul class="footer-icon d-none d-md-block">  <!-- hidden-sm hidden-xs -->
			<?php if ( class_exists( 'ReduxFrameworkPlugin' ) ) : 
				if ( iteck_option( 'iteck_footer_enable_social' ) == true && iteck_option( 'iteck_footer_facebook' )) : ?>
					<li><a href="<?php  echo esc_url( iteck_option( 'iteck_footer_facebook' ) ); ?>">
							<i class="fa fa-facebook"></i>
						</a>
					</li>
				<?php endif; ?>
			<?php endif; ?>

			<?php if ( class_exists( 'ReduxFrameworkPlugin' ) ) :
				if ( iteck_option( 'iteck_footer_enable_social' ) == true && iteck_option( 'iteck_footer_twitter' ) ) : ?>
					<li>
						<a href="<?php  echo esc_url(iteck_option( 'iteck_footer_twitter' )); ?>">
							<i class="fa fa-twitter"></i>
						</a>
					</li>
				<?php endif; ?>
			<?php endif; ?>

			<?php if (class_exists('ReduxFrameworkPlugin')) :
					if (iteck_option('iteck_footer_enable_social') == true && iteck_option( 'iteck_footer_instagram' )) :  ?>
						<li>
							<a href="<?php  echo esc_url(iteck_option( 'iteck_footer_instagram' )); ?>">
								<i class="fa fa-instagram"></i>
							</a>
						</li>
					<?php endif; ?>
			<?php endif; ?>

			<?php if (class_exists('ReduxFrameworkPlugin')) :
					if (iteck_option('iteck_footer_enable_social') == true && iteck_option( 'iteck_footer_pinterest')) :  ?>
						<li>
							<a href="<?php  echo esc_url(iteck_option( 'iteck_footer_pinterest') ); ?>">
								<i class="fa fa-pinterest"></i>
							</a>
						</li>
					<?php endif; ?>
			<?php endif; ?>

			<?php if ( class_exists( 'ReduxFrameworkPlugin' ) ) :
					if ( iteck_option( 'iteck_footer_enable_social' ) == true && iteck_option( 'iteck_footer_xing' ) ) : ?>
						<li>
							<a href="<?php  echo esc_url(iteck_option( 'iteck_footer_xing') ); ?>">
								<i class="fa fa-xing"></i>
							</a>
						</li>
					<?php endif; ?>
			<?php endif; ?>

			<?php if ( class_exists( 'ReduxFrameworkPlugin' ) ) :
					if ( iteck_option( 'iteck_footer_enable_social' ) == true && iteck_option( 'iteck_footer_linkedin' ) ) : ?>
						<li>
							<a href="<?php  echo esc_url(iteck_option( 'iteck_footer_linkedin') ); ?>">
								<i class="fa fa-linkedin"></i>
							</a>
						</li>
					<?php endif; ?>
			<?php endif; ?>
		</ul><!-- /.footer-icon -->

		<?php 
		if ( class_exists( 'ReduxFrameworkPlugin' ) && iteck_option( 'iteck_footer_text' ) ) { 
			$iteck_footer_text = iteck_option( 'iteck_footer_text' );
			echo wp_kses_post( $iteck_footer_text ); 
		} else {
			echo '<p>'.esc_html__( 'Copyright 2022 by ThemesCamp All Rights Reserved.', 'iteck' ).'</p>';
		} 
		?>

	</div><!--/.container-fluid-->
</footer><!--/.footer--> 