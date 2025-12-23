<?php 
/**
 * Iteck theme functions and definitions
 */

add_action( 'after_setup_theme', 'iteck_theme_setup' );
function iteck_theme_setup() {

	/* 
	* Add filters, actions, and theme-supported features.
	*/
	// Custom Post Type Supports
	add_theme_support( 'portfolio' );

	//add thumbnail
	add_theme_support( 'post-thumbnails' );

	//custom background
	add_theme_support( 'custom-background' );

	//Support Html5  
	add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script' ) );

	//Let WordPress manage the document title
	add_theme_support( 'title-tag' );

	//automatic feed
	add_theme_support( 'automatic-feed-links' );

	// Add support for editor styles.
	add_theme_support( 'editor-styles' );

	// Wide Alignment.
	add_theme_support( 'align-wide' );

	// Enqueue editor styles.
	add_editor_style( 'css/style-editor.css' ); 

	// Enqueue editor font sizes.
	add_theme_support('disable-custom-font-sizes');

	// Responsive embeds.
	add_theme_support('responsive-embeds');

	//add menu homepage,portfolio and blog
	add_action( 'init', 'iteck_register_menu' );
  
	// Set the theme's text domain using the unique identifier from above
	load_theme_textdomain('iteck', get_template_directory() . '/lang');
  
	//width content
	if ( ! isset( $content_width ) )$content_width = 1170;
  
	//theme default script.
	add_action('wp_enqueue_scripts', 'iteck_theme_scripts');

	//theme default styles.
	add_action('wp_enqueue_scripts', 'iteck_theme_styles');
  
	//register sidebar
	add_action( 'widgets_init', 'iteck_sidebar' );
  
	/*
	* custom filters
	*/
	//custom search setting
	add_filter( 'get_search_form', 'iteck_search_form' );

	//custom excerpt
	add_filter( 'excerpt_length', 'iteck_excerpt_length', 10 );

	//remove [..] in excerpt
	add_filter('get_the_excerpt', 'iteck_trim_excerpt');

	//custom comment styles
	add_filter('comment_form_default_fields','iteck_modify_comment_form_fields');

	//tag cloud filter
	add_filter('wp_generate_tag_cloud', 'iteck_tag_cloud',10,1);

	//preloader styles.
	add_action( 'wp_enqueue_scripts', 'iteck_preloader_set' );

	//preloader script.
	add_action( 'wp_enqueue_scripts', 'iteck_preloader' );

	//next post link.
	add_filter('next_post_link', function($link) {
		$next_post = get_next_post();
		$title = esc_attr( $next_post->post_title);
		$link = str_replace('href=', 'title="'.esc_attr($title).'" href=', $link);
		return $link;
	});
  
	//previous post link.
	add_filter('previous_post_link', function($link) {
		$previous_post = get_previous_post();
		$title = esc_attr($previous_post->post_title);
		$link = str_replace('href=', 'title="'.esc_attr($title).'" href=', $link);
		return $link;
	});
  
	//color_schecmes script
	add_action( 'wp_enqueue_scripts', 'iteck_color_scheme',99 );

	//iteck custom css
	add_action( 'wp_enqueue_scripts', 'iteck_custom_css',99 );

	//custom header
	add_action('iteck-custom-header','iteck_header_start') ;

	//create custom header
	add_action('iteck-header-page','iteck_custom_header_page') ;

	//custom header option
	add_action('iteck-header-global','iteck_custom_header_global') ;
  
	//custom footer
	add_action('iteck-custom-footer','iteck_footer_start') ;

	//custom side panel
	add_action('iteck-custom-sidepanel','iteck_sidepanel_start') ;
  
	//add image size
	add_image_size( 'iteck-related-post', 500, 300, array( 'center', 'center' ) );
	//add image gallery size
	add_image_size( 'iteck-gallery', 63, 63, array( 'center', 'center' ) );
  
	//comment reply
	add_action(  'wp_enqueue_scripts', 'iteck_enqueue_comments_reply' );
  
	//color_schecmes script
	add_action( 'wp_enqueue_scripts', 'iteck_color_scheme' );

		//Woocommerce
		add_theme_support( 'woocommerce', array(
			'thumbnail_image_width' => 300,
			'gallery_thumbnail_image_width' => 250,
			'single_image_width'    => 800,
	
			'product_grid'          => array(
				'default_rows'    => 3,
				'min_rows'        => 2,
				'max_rows'        => 8,
				'default_columns' => 4,
				'min_columns'     => 2,
				'max_columns'     => 5,
			),
		) );
	
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );

}

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function iteck_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'iteck_pingback_header' );

/**
 * Ensure cart contents update when products are added to the cart via AJAX
 */
function iteck_add_to_cart_fragment( $fragments ) {
 
    ob_start();
    $count = WC()->cart->cart_contents_count;
    ?><a class="cart-contents 3" href="<?php echo WC()->cart->get_cart_url(); ?>" title="<?php esc_attr__( 'View your shopping cart','iteck' ); ?>"><?php

        ?>
        <span class="cart-contents-count"><?php echo esc_html( $count ); ?></span>
        <?php            

        ?></a><?php
 
    $fragments['a.cart-contents'] = ob_get_clean();
     
    return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'iteck_add_to_cart_fragment' );


//tag cloud filter
function iteck_tag_cloud($input){
 	return preg_replace('/ style=("|\')(.*?)("|\')/','',$input);
}

//add menu for all page 

function iteck_register_menu() {
	register_nav_menus( [
				'primary_menu' => esc_html__('All pages menu', 'iteck')
			] ); 
}

//add meta options
if ( ! function_exists( 'iteck_option' ) ) :
	function iteck_option( $iteck_option, $def_value='' ) {
		global $iteck_theme_settings, $post;
		$defval = '' != $def_value ? $def_value : false;
		$iteck_single = false;
		if(is_singular()){
			$iteck_value = get_post_meta( $post->ID, $iteck_option, true); 
			$iteck_single = true;
		}

		if($iteck_single == true){
			if (is_string($iteck_value) && (strlen($iteck_value) > 0 || is_array($iteck_value)) && $iteck_value != 'default') {
				return $iteck_value;
			}
		}

		if(class_exists('Redux') && isset($iteck_theme_settings[$iteck_option]) && $iteck_theme_settings[$iteck_option] != ''){
			$iteck_option_value = $iteck_theme_settings[$iteck_option];
			return $iteck_option_value;
		}else{
			return $defval;
		}

		return false;
	}
endif;

// Add specific CSS class to body by filter. 
 
add_filter( 'body_class', function( $classes ) {
	$iteck_mode='';
	if (iteck_option( 'iteck_theme_mode')=='dark_mode'){$iteck_mode='iteck-dark-mode';
	}elseif(iteck_option( 'iteck_theme_mode')=='auto_mode'){$iteck_mode='iteck-auto-mode';}
    return array_merge( $classes, array( $iteck_mode ) );
} );

//custom excerpt function
function iteck_excerpt_length( $length ) {
	return 60;
}

// Remove [...]
function iteck_trim_excerpt($text) {
	$text = str_replace('[', '', $text);
	$text = str_replace(']', '', $text);
	return $text;
}

//adding sidebar widget
function iteck_sidebar() {
	register_sidebar(
		array(
			'name' => esc_html__('Main Sidebar', 'iteck' ),
			'id' => 'main-sidebar',
			'description' => esc_html__('Appears as the sidebar on blog and pages', 'iteck' ),
			'before_widget' => '<div  id="%1$s" class="widget %2$s clearfix">','after_widget' => '</div>',
			'before_title' => '<h3 class="widgettitle">',
			'after_title' => '</h3> <div class="widget-border"></div>',
		)
	);
}

//add span to category
add_filter('wp_list_categories', 'iteck_cat_count');
function iteck_cat_count($links) {
	$links = str_replace('</a> (', '</a> <span>', $links);
	$links = str_replace(')', '</span>', $links);
	return $links;
}

add_filter('get_archives_link', 'iteck_arch_count');
function iteck_arch_count($links) {
	$links = str_replace('</a>&nbsp;(', '</a> <span>', $links);
	$links = str_replace(')</li>', '</span></li>', $links);
	return $links;
}

/* Replacing the default WordPress search form with an HTML5 version */   

function iteck_search_form( $form ) {
	$iteck_unique_id = iteck_unique_id( 'search-form-' );
	$form = '<form role="search" method="get" id="'.esc_attr( $iteck_unique_id ).'" class="searchform" action="' . esc_url( home_url( '/' ) ) . '" > 
	<input type="search" placeholder="'.esc_attr__('Type keyword here','iteck').'" value="' . get_search_query() . '" name="s" />
	<input type="submit" class="searchsubmit" />
	</form>';
	return $form;
}

//related post
function iteck_related_post( $post_id, $related_count, $args = array() ) {
	$args = wp_parse_args( (array) $args, array(
		'orderby' => 'rand',
		'return'  => 'query',
	) );

	$related_args = array(
	'post_type'      => get_post_type( $post_id ),
	'posts_per_page' => $related_count,
	'post_status'    => 'publish',
	'post__not_in'   => array( $post_id ),
	'orderby'        => $args['orderby'],
	'tax_query'      => array()
	);

	$post = get_post( $post_id );
	$taxonomies = get_object_taxonomies( $post, 'names' );

	foreach( $taxonomies as $taxonomy ) {
		$terms = get_the_terms( $post_id, $taxonomy );
		if ( empty( $terms ) ) continue;
		$term_list = wp_list_pluck( $terms, 'slug' );
		$related_args['tax_query'][] = array(
			'taxonomy' => $taxonomy,
			'field'    => 'slug',
			'terms'    => $term_list
		);
	}

	if( count( $related_args['tax_query'] ) > 1 ) {
		$related_args['tax_query']['relation'] = 'OR';
	}

	if( $args['return'] == 'query' ) {
		return new WP_Query( $related_args );
	} else {
		return $related_args;
	}
}

//custom comment form
function iteck_modify_comment_form_fields($fields){
	$req = get_option('require_name_email');
	$commenter = wp_get_current_commenter();
	$aria_req = ( $req ? " aria-required='true'" : '' ); 
	$fields['author'] = '<p class="comment-form-author">' . ( $req ? '' : '' ) . '<input id="author" name="author" type="text" placeholder="'. esc_attr__('Your Name ...','iteck').'" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>';
	$fields['email'] = '<p class="comment-form-email">' . ( $req ? '' : '' ) . '<input id="email" name="email" type="text" placeholder="'. esc_attr__('Your Email ...','iteck') .'"  value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>';
	$fields['url'] = '<p class="comment-form-url">'. '<input id="url" name="url" type="text" placeholder="'. esc_attr__('Your Website ...','iteck').'" value="' . esc_url( $commenter['comment_author_url'] ) . '" size="30" /></p>';
	return $fields;
}

//comment reply script
function iteck_enqueue_comments_reply() {
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

//Get unique ID. 
function iteck_unique_id( $prefix = '' ) {
	static $id_counter = 0;
	if ( function_exists( 'wp_unique_id' ) ) {
		return wp_unique_id( $prefix );
	}
	return $prefix . (string) ++$id_counter;
}


/* Function which displays your post date in time ago format */
function iteck_time_ago() {
	return human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ).' '.__( 'ago', 'iteck');
}

/* Function to display menu description */
function iteck_nav_description( $item_output, $item, $depth, $args ) {
    if ( !empty( $item->description ) ) {
        $item_output = str_replace( $args->link_after . '</a>', '<p class="menu-item-desc">' . $item->description . '</p>' . $args->link_after . '</a>', $item_output );
    }
    return $item_output;
}


add_filter( 'walker_nav_menu_start_el', 'iteck_nav_description', 10, 4 );

// Itack single product page body class
function iteck_single_product_woocommerce_body_class( $classes ) {

	if(in_array('woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) )  ) && class_exists( 'WooCommerce' )):
		// NOT single product page, so return
		if ( ! is_product() ) return $classes;
		
		// Add new class
		$classes[] = 'iteck-single-product';

	endif;

	return $classes;
}
add_filter( 'body_class', 'iteck_single_product_woocommerce_body_class', 10, 2 );

/*
* Theme scripts & Styles
*/
//include theme style
include( get_template_directory().'/inc/theme-style.php' );

//include theme script
include( get_template_directory().'/inc/theme-script.php');

//include color schemes 
include( get_template_directory().'/inc/class/theme-helper.php');

//included preloader setting
include( get_template_directory().'/inc/preloader.php');

//include color schemes 
include( get_template_directory().'/inc/color-schemes.php');

//include custom style 
include( get_template_directory().'/inc/custom-style.php');

//include comment template
include( get_template_directory().'/inc/comment-template.php');

//include custom header
include( get_template_directory().'/inc/custom-header.php');

//include custom footer
include( get_template_directory().'/inc/custom-footer.php');

//include custom side panel
include( get_template_directory().'/inc/custom-sidepanel.php');

//Count view
include( get_template_directory().'/inc/count-view.php');

//pagination
include( get_template_directory().'/inc/pagination.php');

//include TGM activation
include( get_template_directory().'/inc/plugin-install.php');

//include options admin
include_once( get_template_directory().'/inc/option-init.php');



