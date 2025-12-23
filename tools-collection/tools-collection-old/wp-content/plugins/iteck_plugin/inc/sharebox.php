<?php
function iteck_sharebox() {
  ?>
	<div class="hidden share-remove">
      <div class="share-box">
         <span class="share-text"><?php esc_html_e("Share On:", "iteck_plg"); ?> </span>

         <a class="tw-share tw-bg" href="http://twitter.com/home/?status=<?php echo rawurlencode(get_the_title());  ?>%20-%20<?php the_permalink(); ?>" 
         title="<?php esc_html_e("Tweet this", "iteck_plg"); ?>">
            <i class="fa fa-twitter"></i>
         </a>

         <a class="fb-share f-bg" href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?>&amp;t=<?php echo rawurlencode(get_the_title());  ?>" 
         title="<?php esc_html_e("Share on Facebook", "iteck_plg"); ?>">
            <i class="fa fa-facebook"></i>
         </a>

         <a class="pin-bg" href="http://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&media=<?php 
         global $post;
         $url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); echo $url; ?>" 
         title="<?php esc_html_e("Pin This", "iteck_plg"); ?>">
            <i class="fa fa-pinterest"></i>
         </a>

     </div>
 </div>

<script type="text/javascript">
	(function ($) {
	'use strict';
		$( ".sharebox" ).append( $( ".share-box" ) );
		
		$( ".share-remove" ).remove();
		
		$(window).on("load", function() {
			$('.sharebox a').on('click', function() {
				window.open(this.href,"","menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600");return false;
				});
		});
	})(jQuery);
</script>
    <?php 

}


function iteck_share_box_single() {
	if (is_singular( 'post' ) && iteck_option( 'iteck_post_share_box' ) ==true ) {
		add_action( 'wp_footer', 'iteck_sharebox',1 );
	}
} 

add_action( 'wp_enqueue_scripts', 'iteck_share_box_single' );		