<?php
/**
 * Rest endpoints.
 *
 * @package  ALMRESTAPI
 * @since    1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Bail early if accessed directly.
}

/**
 * Custom WP REST API Routes
 *
 *  @since 1.0
 */
add_action(
	'rest_api_init',
	function () {
		// Get posts ajaxloadmore[namespace] /posts[endpoint].
		$my_namespace = 'ajaxloadmore';
		$my_endpoint  = '/posts';
		register_rest_route(
			$my_namespace,
			$my_endpoint,
			[
				'methods'             => 'GET',
				'callback'            => 'alm_get_posts',
				'permission_callback' => '__return_true',
			]
		);
	}
);

/**
 * Custom /posts endpoint for ajaxloadmore.
 *
 * @see http://v2.wp-api.org/extending/adding/
 *
 * @param array $data Options for the function.
 * @return void
 * @since 1.0
 */
function alm_get_posts( $data ) {
	$response = [];

	// Set Defaults.
	$args           = [];
	$page           = isset( $data['page'] ) ? $data['page'] : 0;
	$posts_per_page = isset( $data['posts_per_page'] ) ? $data['posts_per_page'] : 5;

	if ( method_exists( 'ALM_QUERY_ARGS', 'alm_build_queryargs' ) ) {
		/**
		 * Pluck query args from core ALM class.
		 *
		 * @see ajax-load-more/core/classes/class-queryargs.php
		 */
		$args           = ALM_QUERY_ARGS::alm_build_queryargs( $data );
		$args['offset'] = $args['offset'] + $page * $args['posts_per_page'];
	}

	// Run Query.
	$posts = new WP_Query( $args );

	/**
	 * ALM Template vars.
	 *
	 * @see https://connekthq.com/plugins/ajax-load-more/docs/variables/
	 */
	$alm_item        = $page * $posts_per_page;
	$alm_found_posts = $posts->found_posts;
	$alm_post_count  = $posts->post_count;
	$alm_current     = 0;

	$data = [];
	while ( $posts->have_posts() ) :
		$posts->the_post();

		$alm_current++;

		// Get post thumbnail.
		$thumbnail_id = get_post_thumbnail_id();
		$thumbnail    = '';
		$alt          = '';
		if ( $thumbnail_id ) {
			$thumbnail_arr = wp_get_attachment_image_src( $thumbnail_id, 'alm-thumbnail', true );
			$thumbnail     = $thumbnail_arr[0];
			$alt           = get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true );
		}

		// Build $data JSON object.
		$data[] = [
			'alm_page'        => $page + 1,
			'alm_item'        => ( $alm_item++ ) + 1,
			'alm_current'     => $alm_current,
			'alm_found_posts' => $alm_found_posts,
			'date'            => get_the_time( 'F d, Y' ),
			'link'            => get_permalink(),
			'post_title'      => get_the_title(),
			'post_excerpt'    => get_the_excerpt(),
			'thumbnail'       => $thumbnail,
			'thumbnail_alt'   => $alt,
		];

		/**
		 * Content [Apply shortcode filter for loaded shortcodes].
		 * $content = get_the_content();.
		 * $data['post_content'] = apply_filters('the_content', $content);.
		 */

	endwhile;
	wp_reset_query(); // phpcs:ignore

	if ( empty( $data ) ) {
		// Empty results.
		$data            = null;
		$alm_post_count  = null;
		$alm_found_posts = null;
	}

	$return = [
		'html' => $data,
		'meta' => [
			'postcount'  => $alm_post_count,
			'totalposts' => $alm_found_posts,
		],
	];

	wp_send_json( $return );
}
