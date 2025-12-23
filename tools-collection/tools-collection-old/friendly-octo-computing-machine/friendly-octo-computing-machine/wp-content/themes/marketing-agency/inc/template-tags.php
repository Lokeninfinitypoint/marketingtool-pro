<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Marketing Agency
 */

if ( ! function_exists( 'marketing_agency_the_attached_image' ) ) :
/**
 * Prints the attached image with a link to the next attached image.
 */
function marketing_agency_the_attached_image() {
	$post                = get_post();
	$attachment_size     = apply_filters( 'marketing_agency_attachment_size', array( 1200, 1200 ) );
	$next_attachment_url = wp_get_attachment_url();
	$attachment_ids 	 = get_posts( array(
		'post_parent'    => $post->post_parent,
		'fields'         => 'ids',
		'numberposts'    =>  1,
		'post_status'    => 'inherit',
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'order'          => 'ASC',
		'orderby'        => 'menu_order ID'
	) );

	wp_reset_postdata();

	// If there is more than 1 attachment in a gallery...
	if ( count( $attachment_ids ) > 1 ) {
		foreach ( $attachment_ids as $attachment_id ) {
			if ( $attachment_id == $post->ID ) {
				$next_id = current( $attachment_ids );
				break;
			}
		}

		// get the URL of the next image attachment...
		if ( $next_id )
			$next_attachment_url = get_attachment_link( $next_id );

		// or get the URL of the first image attachment.
		else
			$next_attachment_url = get_attachment_link( array_shift( $attachment_ids ) );
	}

	printf( '<a href="%1$s" rel="attachment">%2$s</a>',
		esc_url( $next_attachment_url ),
		wp_get_attachment_image( $post->ID, $attachment_size )
	);
	wp_reset_postdata();
}
endif;

/**
 * Returns true if a blog has more than 1 category
 */
function marketing_agency_categorized_blog() {
	if ( false === ( $marketing_agency_all_the_cool_cats = get_transient( 'marketing_agency_all_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts
		$marketing_agency_all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts
		$marketing_agency_all_the_cool_cats = count( $marketing_agency_all_the_cool_cats );

		set_transient( 'marketing_agency_all_the_cool_cats', $marketing_agency_all_the_cool_cats );
	}

	if ( '1' != $marketing_agency_all_the_cool_cats ) {
		// This blog has more than 1 category so marketing_agency_categorized_blog should return true
		return true;
	} else {
		// This blog has only 1 category so marketing_agency_categorized_blog should return false
		return false;
	}
}

if ( ! function_exists( 'marketing_agency_the_custom_logo' ) ) :
/**
 * Displays the optional custom logo.
 *
 * Does nothing if the custom logo is not available.
 *
 * @since marketing-agency
 */
function marketing_agency_the_custom_logo() {	
	the_custom_logo();
}
endif;

/**
 * Flush out the transients used in marketing_agency_categorized_blog
 */
function marketing_agency_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'marketing_agency_all_the_cool_cats' );
}
add_action( 'edit_category', 'marketing_agency_category_transient_flusher' );
add_action( 'save_post',     'marketing_agency_category_transient_flusher' );

add_theme_support( 'admin-bar', array( 'callback' => 'marketing_agency_my_admin_bar_css') );
function marketing_agency_my_admin_bar_css(){
?>
<style type="text/css" media="screen">	
	html body { margin-top: 0px !important; }
</style>
<?php
}

/**
 * Posts pagination.
 */
if ( ! function_exists( 'marketing_agency_blog_posts_pagination' ) ) {
	function marketing_agency_blog_posts_pagination() {
		$pagination_type = get_theme_mod( 'marketing_agency_blog_pagination_type', 'blog-page-numbers' );
		if ( $pagination_type == 'blog-page-numbers' ) {
			the_posts_pagination();
		} else {
			the_posts_navigation();
		}
	}
}