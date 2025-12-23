<?php 
/*
*single portfolio page
*/

get_header(); 
		
	//custom header
	if ( class_exists('ReduxFrameworkPlugin') ) { 
		do_action('iteck-custom-header','iteck_header_start') ;        
	} else { ?>
		
		<!--Fall back if no reduxoptions instaliteck-->
		<div class="default-header clearfix">
			<?php get_template_part( 'inc/menu','normal'); ?>
		</div><!--/home end-->
			
	<?php } 
		  
	if ( have_posts() ) : while ( have_posts() ) : the_post();
			
	//gallery at top
	if ( get_post_meta($post->ID, 'iteck_port_format', true) == 'port_standard' ){ ?>
		<div class="content page-content-wrapper iteck-portfolio-single">
			<div class="container-fluid clearfix">
				
				<div class="port-gallery-body port-top-gallery">
					<?php /* get the gallery list array */
					$lists = get_post_meta($post->ID, 'gallery_list',  true);
					  
					if ( ! empty( $lists ) ) {
						foreach( $lists as $list ) { ?>
							<div class="port-item <?php if ($list['gallery_size'] == 'gallery_size_big'){ echo "col-md-8"; } else {echo 'col-md-4';} ?>">

								<div class="port-inner">
									<a href="<?php echo esc_url ( $list['gallery_port_img']);  ?>"  class="popup-portfolio port-link slider-outer-box" 
									title="<?php echo esc_attr ($list['title']);  ?>">
									</a>
									<div class="port-box"></div>
									<div class="port-img width-img img-bg" data-background="<?php echo esc_url($list['gallery_port_img']);  ?>"></div>
								</div><!--/.port-inner-->
								
							</div><!--.port-item-->
						<?php } 
					} ?>
			   
					<div class="spc-40"></div>
				</div><!--/.port-gallery-body-->
					
				<h3 class="single-work-title"><?php the_title();?></h3>
						
				<div class="swork-line"></div>
				<?php the_content (); ?>
				<?php if ( get_post_meta($post->ID, 'port_item_btn_text', true) !='' && get_post_meta($post->ID, 'port_item_btn_link', true) != ''){?>
					<div class="spc-40 clearfix"></div>
					<a class="content-btn" href="<?php echo  esc_url(get_post_meta($post->ID, 'port_item_btn_link', true)); ?>">
					   <?php echo  esc_html( get_post_meta($post->ID, 'port_item_btn_text', true)); ?>
						<span class="content-btn-align-icon-right content-btn-button-icon">
							<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
						</span>
					</a>
				<?php } ?>
						
			</div><!--/.container-fluid-->
		</div><!--/.content--> 
			   
	<?php }  else { 
		if ( get_post_meta($post->ID, 'iteck_port_format', true) == 'port_two' && get_post_meta($post->ID, 'iteck_top_type', true) == 'top_content_youtube' ){ ?>
			  <!--YOUTUBE BACKGROUND-->
			  <div class="bg-youtube" data-property="{videoURL:'https://www.youtube.com/watch?v=<?php echo esc_attr(get_post_meta($post->ID, 'iteck_port_youtube_link', true)); ?>', opacity:1, 
			  autoPlay:true, containment:'.home-video-box', startAt:0, stopAt:0, mute:true, optimizeDisplay:true, showControls:false, printUrl:true, loop:true, addRaster:false, 
			  quality:'<?php if ( get_post_meta($post->ID, 'iteck_port_youtube_quality', true) != '') {echo esc_attr(get_post_meta($post->ID, 'iteck_port_youtube_quality', true)); } else {echo 'large';} ?>',
			   realfullscreen:'true', ratio:'auto'}"></div>
			  <!--YOUTUBE BACKGROUND END-->
		<?php } else if ( get_post_meta($post->ID, 'iteck_port_format', true) == 'port_two' && get_post_meta($post->ID, 'iteck_top_type', true) == 'top_content_video' ){ ?>
			
			<div class="bg-vid"  data-link="<?php echo esc_url(get_post_meta($post->ID, 'iteck_port_video_link', true)); ?>"  data-ambient="true"></div>
		<?php } ?>
			<div class="page-content-wrapper iteck-portfolio-single">
			<div class="home-video-box">
				<!--WORK SLIDER START-->
				<div class="home-slider ani-slider slider" data-slick='{"autoplaySpeed": 8000}'>
					<div class="slide">
						<div class="slider-mask" data-animation="slideUpReturn" data-delay="0.1s"></div>
						<div class="<?php if ( get_post_meta($post->ID, 'iteck_port_format', true) == 'port_two' && get_post_meta($post->ID, 'iteck_top_type', true) == 'top_content_slider' ){ 
							echo 'work-img-bg'; } ?> slider-img-bg" data-animation="puffIn" data-delay="0.2s" data-animation-duration="0.7s" data-background="<?php echo esc_url(get_post_meta($post->ID, 'iteck_port_slider_setting', true)); ?>"></div>
						<div class="slider-box container-fluid">
							<div class="slider-content left-box-slider center-box-slider">
							  	<?php $destudio_taxonomy = 'portfolio_category'; 
			  					$destudio_taxs = wp_get_post_terms($post->ID,$destudio_taxonomy);  ?> 
			 					 <p class="top-slider" data-animation="swashIn" data-delay="0.6s"><?php $destudio_cats = array();  
			  					foreach ( $destudio_taxs as $destudio_tax ) { $destudio_cats[] =   $destudio_tax->name ;   } 
		  						echo implode(', ', $destudio_cats);?></p>
						  		<div class="slider-hidden">
							 		<h3 class="slider-title" data-animation="fadeInUp" data-delay="0.8s"><?php the_title(); ?></h3>
						 		</div><!--/.slider-hidden-->
							  	<div class="slider-line"  data-animation="swashIn" data-delay="0.5s"></div>
						  	</div><!--/.slider-content-->
					  	</div><!--/.slider-box-->
				  	</div><!--/.slide-->
				</div><!--/.home-slider-->
				  <!--WORK SLIDER END-->
			</div> 
			  
			<div class="content">
				<div class="container clearfix">
					<div class="row">
						<div class="<?php if ( get_post_meta($post->ID, 'iteck_gallery_port_img', true) == ''){echo 'col-lg-12';}else{echo 'col-lg-12';} ?> ">
							<?php the_content ();?>
							<?php if ( get_post_meta($post->ID, 'port_item_btn_text', true) !='' && get_post_meta($post->ID, 'port_item_btn_link', true) != ''){?>
							<div class="spc-40 clearfix"></div>
							<a class="content-btn" href="<?php echo  esc_url(get_post_meta($post->ID, 'port_item_btn_link', true)); ?>">
								<?php echo  esc_html( get_post_meta($post->ID, 'port_item_btn_text', true)); ?>
								<span class="content-btn-align-icon-right content-btn-button-icon">
									<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
								</span>
							</a>
								<?php } ?>
						</div><!--/.col-lg-8-->

						
					</div><!--/.row-->
						
				</div><!--/.container-fluid-->
			</div><!--/.content-->
		</div>
	<?php }
		
	endwhile; endif; ?>
			  
	<?php // get the custom post type's destudio_taxonomy terms
	$custom_taxterms = wp_get_object_terms( $post->ID, 'portfolio_category', array('fields' => 'ids') );
	// arguments
	$args = array(
		'post_type' => 'portfolio',
		'post_status' => 'publish',
		'posts_per_page' => 4, 
		'orderby' => 'rand',
		'tax_query' => array(
		array(
			'taxonomy' => 'portfolio_category',
			'field' => 'id',
			'terms' => $custom_taxterms
			)
		),
		'post__not_in' => array ($post->ID),
	);
	$related_items = new WP_Query( $args );
	// loop over query
	if ($related_items->have_posts()) : $i =1; ?>
		<div class="content gray-bg">   
			<div class="container-fluid">
				<div class="other-portfolio clearfix pt-25">
					<p class="op-sub"><?php if ( class_exists('ReduxFrameworkPlugin')&& iteck_option( 'iteck_other_port_sub' ) !=''){ echo  esc_html ( iteck_option( 'iteck_other_port_sub' )); } else {esc_html_e('See our other portfolio','iteck');} ?>
					</p>
					<h3 class="op-title"><?php if ( class_exists('ReduxFrameworkPlugin')&& iteck_option( 'iteck_other_port_title' ) !=''){ echo esc_html ( iteck_option( 'iteck_other_port_title' )); } else {esc_html_e('Other portfolio','iteck');} ?>
					</h3>
					<?php while ( $related_items->have_posts() ) : $related_items->the_post(); ?>
			  
						<div class="col-md-3 port-item pb-30">
				  
							<div class="port-inner">
								<a href="<?php the_permalink(); ?>" class="port-link"></a>
								<div class="port-box"></div>
								<div class="port-img width-img img-bg" data-background="<?php echo get_the_post_thumbnail_url(); ?>"></div>
								<div class="port-dbox">
									<div class="dbox-relative">
										<h3><?php the_title(); ?></h3>
										<?php $destudio_taxonomy = 'portfolio_category'; $args = array('number' => '1',); 
										$destudio_taxs = wp_get_post_terms($post->ID,$destudio_taxonomy,$args);  ?> 
										<p><?php $destudio_cats = array();  foreach ( $destudio_taxs as $destudio_tax ) { $destudio_cats[] =   $destudio_tax->name ;   } 
										echo implode(', ', $destudio_cats);?></p>
									</div><!--/.dbox-relative-->
								</div><!--/.port-dbox-->
							</div><!--/.port-inner-->
						</div><!--.port-item-->
					<?php endwhile; ?>
				</div><!--/.other-portfolio-->  
			</div><!--/container-fluid-->
		</div>
	<?php endif; wp_reset_postdata(); ?> 
	
	<?php //custom footer
	if ( class_exists('ReduxFrameworkPlugin') ) { 
			do_action('iteck-custom-footer','iteck_footer_start');
	} else {
			//Fall back if no reduxoptions instaliteck
			get_template_part( 'inc/bottom','footer'); 
	}
		
get_footer(); ?>