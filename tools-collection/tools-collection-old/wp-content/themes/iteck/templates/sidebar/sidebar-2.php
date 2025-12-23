<?php
	if ( ! is_active_sidebar( 'main-sidebar' ) ) {
		return;
	}

	if ( class_exists('ReduxFrameworkPlugin') && (iteck_option( 'single_sidebar_def_width' ) =='9')) {
				$side_bar_width='col-md-3';
			}else{$side_bar_width='col-md-4';}
?>

<div class="<?php echo esc_attr($side_bar_width); ?> sidebar fixed-sidebar sidebar-2">
	<div class="theiaStickySidebar" <?php echo Iteck_Theme_Helper::render_sidebars(); ?>>
		<?php  if ( function_exists( 'dynamic_sidebar' ) ){ 
			if ( is_active_sidebar( 'main-sidebar' ) ) { dynamic_sidebar( 'main-sidebar' );}
		} ?>
	</div><!--  End StickySidebar  -->
</div><!--  End Sidebar  -->  
