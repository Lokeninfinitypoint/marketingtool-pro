<?php
/**
 * Plugin Name: Ajax Load More for ACF
 * Plugin URI: http://connekthq.com/plugins/ajax-load-more/extensions/advanced-custom-fields/
 * Description: Ajax Load More extension that adds compatibility with various field types for Advanced Custom Fields.
 * Text Domain: ajax-load-more-for-acf
 * Author: Darren Cooney
 * Author URI: https://connekthq.com
 * Version: 1.4.0
 * License: GPL
 * Copyright: Connekt Media & Darren Cooney
 * Requires Plugins: ajax-load-more
 *
 * @package ALM_ACF
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ALM_ACF_PATH', plugin_dir_path( __FILE__ ) );
define( 'ALM_ACF_URL', plugins_url( '', __FILE__ ) );


if ( ! class_exists( 'ALM_ACF' ) ) :

	/**
	 * Initiate the class.
	 */
	class ALM_ACF {

		/**
		 * Set up contructors.
		 */
		public function __construct() {
			add_action( 'alm_acf_installed', [ $this, 'alm_acf_installed' ] );
			add_filter( 'alm_acf_shortcode', [ $this, 'alm_acf_shortcode' ], 10, 7 );
			add_filter( 'alm_acf_preloaded', [ $this, 'alm_acf_preloaded_query' ], 10, 3 );
			add_action( 'wp_ajax_alm_acf', [ $this, 'alm_acf_query' ] );
			add_action( 'wp_ajax_nopriv_alm_acf', [ $this, 'alm_acf_query' ] );
			add_filter( 'alm_acf_total_rows', [ $this, 'alm_acf_total_rows' ], 10, 1 );
			$this->alm_acf_includes();
		}

		/**
		 * Load these files before the theme loads
		 *
		 * @since 1.3.0
		 */
		public function alm_acf_includes() {
			include_once ALM_ACF_PATH . 'functions.php';
		}

		/**
		 * Count total rows for Repeater field.
		 *
		 * @see /ajax-load-more/classes/class.alm-shortcode
		 *
		 * @param  array $args The query arguments.
		 * @return mixed       The total rows.
		 * @since 1.0
		 */
		public function alm_acf_total_rows( $args ) {
			$acf               = isset( $args['acf'] ) ? $args['acf'] : false; // true / false.
			$post_id           = isset( $args['acf_post_id'] ) ? $args['acf_post_id'] : ''; // Post ID.
			$field_type        = isset( $args['acf_field_type'] ) ? $args['acf_field_type'] : 'repeater'; // ACF Field Type.
			$field_name        = isset( $args['acf_field_name'] ) ? $args['acf_field_name'] : ''; // ACF Field Name.
			$parent_field_name = isset( $args['acf_parent_field_name'] ) ? $args['acf_parent_field_name'] : ''; // ACF Parent Field Name.
			$row_index         = isset( $args['acf_row_index'] ) ? (int) $args['acf_row_index'] : 0; // ACF Row Index.
			$total             = 0;

			if ( empty( $post_id ) ) {
				$post_id = isset( $args['post_id'] ) ? $args['post_id'] : ''; // Backup Post ID.
			}

			if ( $acf && $post_id && ( $field_type !== 'relationship' ) ) {
				if ( $parent_field_name ) {
					switch ( $field_type ) {
						case 'repeater':
						case 'flexible':
							$total = alm_acf_loop_repeater_rows( 'count', $parent_field_name, $field_name, $post_id, $row_index );
							break;

						case 'gallery':
							$total = alm_acf_loop_gallery_rows( 'count', $parent_field_name, $field_name, $post_id, $row_index );
							break;
					}
				} else {
					$total = count( get_field( $field_name, $post_id ) );

				}
			}

			return $total;
		}

		/**
		 * Preloaded Query for Advanced Custom Fields.
		 *
		 * @param array  $args The query arguments.
		 * @param string $repeater The current Repeater Template name.
		 * @param string $theme_repeater The current Theme Repeater name.
		 * @since 1.0
		 */
		public function alm_acf_preloaded_query( $args, $repeater, $theme_repeater ) {
			$acf_data          = '';
			$acf_post_id       = isset( $args['acf_post_id'] ) ? $args['acf_post_id'] : ''; // Post ID.
			$field_type        = isset( $args['acf_field_type'] ) ? $args['acf_field_type'] : 'repeater'; // ACF Field Type.
			$field_name        = isset( $args['acf_field_name'] ) ? $args['acf_field_name'] : ''; // ACF Field Name.
			$parent_field_name = isset( $args['acf_parent_field_name'] ) ? $args['acf_parent_field_name'] : ''; // ACF Parent Field Name.
			$row_index         = isset( $args['acf_row_index'] ) ? (int) $args['acf_row_index'] : 0; // ACF Row Index.

			$posts_per_page = isset( $args['posts_per_page'] ) ? $args['posts_per_page'] : 5;
			$offset         = isset( $args['offset'] ) ? (int) $args['offset'] : 0;
			$max_pages      = $posts_per_page + $offset;

			// Check for empty ACF post ID.
			$post_id = empty( $acf_post_id ) ? $args['post_id'] : $acf_post_id;

			switch ( $field_type ) {
				case 'repeater':
				case 'flexible':
					$options  = [
						'posts_per_page' => $posts_per_page,
						'offset'         => $offset,
						'max_pages'      => $max_pages,
						'repeater'       => $repeater,
						'theme_repeater' => $theme_repeater,
						'is_preloaded'   => true,
						'preloaded'      => 'true',
					];
					$acf_data = alm_acf_loop_repeater_rows( 'query', $parent_field_name, $field_name, $post_id, $options, $row_index );
					break;

				case 'gallery':
					$images = alm_acf_loop_gallery_rows( 'query', $parent_field_name, $field_name, $post_id, $row_index );

					if ( $images ) {
						$total     = count( $images );
						$max_pages = $posts_per_page + $offset;
						$row_count = 0;

						ob_start();
						foreach ( $images as $key => $image ) :
							// Start displaying rows after the offset.
							if ( $key >= $offset ) {
								// Exit when rows exceeds max pages.
								if ( $key >= $max_pages ) {
									break; // exit early.
								}
								++$row_count;

								// Set ALM Variables.
								$alm_found_posts = $total;
								$alm_page        = 1;
								$alm_item        = $row_count;
								$alm_current     = $alm_item;

								if ( $theme_repeater !== 'null' && has_action( 'alm_get_acf_gallery_theme_repeater' ) ) {
									// Theme Repeater.
									do_action( 'alm_get_acf_gallery_theme_repeater', $theme_repeater, $alm_found_posts, $alm_page, $alm_item, $alm_current, $image );
								} else {
									// Repeater.
									$type = alm_get_repeater_type( $repeater );
									include alm_get_current_repeater( $repeater, $type );
								}
							}
						endforeach;
						$acf_data = ob_get_clean();
					}
					break;
			}
			return $acf_data;
		}

		/**
		 * Ajax Query ACF fields and return data to ALM.
		 *
		 * @return mixed Returns the contents of the output buffer and end output buffering. If output buffering isn't active then false is returned.
		 * @since 1.0
		 */
		public function alm_acf_query() {
			$params = filter_input_array( INPUT_GET );

			$data           = isset( $params['acf'] ) ? $params['acf'] : ''; // Get $acf object array.
			$repeater       = isset( $params['repeater'] ) ? $params['repeater'] : 'default';
			$type           = alm_get_repeater_type( $repeater );
			$theme_repeater = isset( $params['theme_repeater'] ) ? $params['theme_repeater'] : 'null';
			$posts_per_page = isset( $params['posts_per_page'] ) ? $params['posts_per_page'] : 5;
			$page           = isset( $params['page'] ) ? $params['page'] : 1;
			$offset         = isset( $params['offset'] ) ? $params['offset'] : 0;
			$query_type     = isset( $params['query_type'] ) ? $params['query_type'] : 'standard'; // 'standard' or 'totalposts'. // phpcs:ignore

			// Preloaded Add-on.
			$preloaded        = isset( $params['preloaded'] ) ? $params['preloaded'] : false;
			$preloaded_amount = isset( $params['preloaded_amount'] ) ? (int) $params['preloaded_amount'] : 5;
			if ( has_action( 'alm_preload_installed' ) && $preloaded === 'true' ) {
				$offset = $offset + $preloaded_amount; // Preloaded: Offset by posts_per_page + preload_amount.
			}

			// Default variables.
			$postcount       = 0;
			$no_results_data = [
				'html' => '',
				'meta' => [
					'postcount'  => 0,
					'totalposts' => 0,
				],
			];

			if ( $data ) {
				$acf_data          = '';
				$acf               = isset( $data['acf'] ) ? $data['acf'] : false; // true / false.
				$post_id           = isset( $data['post_id'] ) ? $data['post_id'] : ''; // Post ID.
				$field_type        = isset( $data['field_type'] ) ? $data['field_type'] : 'repeater'; // ACF Field Type.
				$field_name        = isset( $data['field_name'] ) ? $data['field_name'] : ''; // ACF Field Name.
				$parent_field_name = isset( $data['parent_field_name'] ) ? $data['parent_field_name'] : ''; // ACF Parent Field Name.
				$row_index         = isset( $data['row_index'] ) ? (int) $data['row_index'] : false; // ACF Row Index.
				$max_pages         = 999;

				if ( empty( $field_name ) || empty( $post_id ) ) {
					$acf = false; // If field_name and post_id are not set, exit.
				}

				if ( $acf && $post_id ) {
					switch ( $field_type ) {
						case 'repeater':
						case 'flexible':
							$options = [
								'page'           => $page,
								'posts_per_page' => $posts_per_page,
								'offset'         => $offset,
								'max_pages'      => $max_pages,
								'repeater'       => $repeater,
								'theme_repeater' => $theme_repeater,
								'is_preloaded'   => false,
								'preloaded'      => $preloaded,
							];

							$data = alm_acf_loop_repeater_rows( 'query', $parent_field_name, $field_name, $post_id, $options, $row_index );

							// Parse return data.
							$acf_data  = $data && $data['content'] ? $data['content'] : '';
							$postcount = $data && $data['postcount'] ? $data['postcount'] : '';
							$total     = $data && $data['totalposts'] ? $data['totalposts'] : '';
							break;

						case 'gallery':
							$images = alm_acf_loop_gallery_rows( 'query', $parent_field_name, $field_name, $post_id, $row_index ); // Get Images.

							if ( $images ) {
								$total = count( $images );
								$start = ( $posts_per_page * $page ) + $offset;
								$end   = $start + $posts_per_page;
								$count = 0;

								ob_start();
								foreach ( $images as $image ) :

									// Only display rows between the values.
									if ( $postcount < $posts_per_page && $count >= $start ) {
										++$postcount;

										// Set ALM Variables.
										$alm_found_posts = $total;
										$alm_page        = $page + 1;
										$alm_item        = $count + 1;
										$alm_current     = $postcount + 1;

										if ( $theme_repeater !== 'null' && has_action( 'alm_get_acf_gallery_theme_repeater' ) ) {
											// Theme Repeater.
											do_action( 'alm_get_acf_gallery_theme_repeater', $theme_repeater, $alm_found_posts, $alm_page, $alm_item, $alm_current, $image );
										} else {
											// Repeater.
											include alm_get_current_repeater( $repeater, $type );
										}
									}

									++$count;

									if ( $count >= $end ) {
										break; // exit.
									}

									endforeach;
								$acf_data = ob_get_clean();
							}
							break;
					}

					if ( $query_type === 'totalposts' ) {
						// Combined Preloaded & Paging add-ons.
						wp_send_json(
							[
								'totalposts' => $total,
							]
						);
					}

					if ( $acf_data ) {
						$return = [
							'html' => $acf_data,
							'meta' => [
								'postcount'  => $postcount,
								'totalposts' => $total,
							],
						];

					} else {
						$return = $no_results_data;
					}

					wp_send_json( $return );
				}
			} else {
				// No results.
				if ( $query_type === 'totalposts' ) {
					wp_send_json(
						[
							'totalposts' => 0,
						]
					);
				} else {
					wp_send_json( $no_results_data );
				}
				wp_die();
			}
		}

		/**
		 * Build ACF shortcode params and send back to core ALM as data attributes.
		 *
		 * @param  string $acf               The value for ACF (true/false).
		 * @param  string $field_type        The ACF field type.
		 * @param  string $field_name        The ACF field name.
		 * @param  string $acf_post_id       The ACF post ID passed via params.
		 * @param  string $post_id           The current post ID.
		 * @param  string $parent_field_name The parent field name.
		 * @param  string $row_index         The row index.
		 * @return string                    The generated HTML data attributes.
		 * @since 1.0
		 */
		public function alm_acf_shortcode( $acf, $field_type, $field_name, $acf_post_id, $post_id, $parent_field_name, $row_index = 0 ) {
			$data  = ' data-acf="' . $acf . '"';
			$data .= ' data-acf-field-type="' . $field_type . '"';
			$data .= ' data-acf-field-name="' . $field_name . '"';
			if ( $parent_field_name ) {
				$data .= ' data-acf-parent-field-name="' . $parent_field_name . '"';
				if ( $row_index ) {
					$data .= ' data-acf-row-index="' . $row_index . '"';
				}
			}
			if ( empty( $acf_post_id ) ) {
				$acf_post_id = $post_id;
			}
			$data .= ' data-acf-post-id="' . $acf_post_id . '"';
			return $data;
		}
	}

		/**
		 * The main function responsible for returning the one true ALM_ACF Instance.
		 *
		 * @since 1.0
		 */
	function alm_acf() {
		global $alm_acf;
		if ( ! isset( $alm_acf ) ) {
			$alm_acf = new ALM_ACF();
		}
		return $alm_acf;
	}
	alm_acf();

endif;
