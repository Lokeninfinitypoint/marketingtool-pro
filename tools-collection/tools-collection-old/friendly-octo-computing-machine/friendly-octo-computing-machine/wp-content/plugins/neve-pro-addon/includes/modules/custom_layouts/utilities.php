<?php
/**
 * Helper functions for Custom Layouts
 *
 * @package Neve Pro Addon
 */

namespace Neve_Pro\Modules\Custom_Layouts;

use Neve_Pro\Admin\Conditional_Display;
use Neve_Pro\Core\Loader;
use Neve_Pro\Modules\Custom_Layouts\Admin\Inside_Layout;
use Neve_Pro\Modules\Custom_Layouts\Admin\Layouts_Metabox;

trait Utilities {
	/**
	 * Get priority value of the given custom layout post.
	 *
	 * @param  int     $post_id Custom layout post ID.
	 * @param  boolean $is_new Is that a new custom layout that haven't saved yet?.
	 * @return int
	 */
	private static function get_priority( $post_id, $is_new = false ) {
		$priority               = get_post_meta( $post_id, Layouts_Metabox::META_PRIORITY, true );
		$backward_default_value = 1; // backward compatibility for old users.

		if ( empty( $priority ) && $priority !== 0 ) {
			if ( $is_new ) {
				return 10;
			}

			return $backward_default_value;
		}

		return $priority;
	}

	/**
	 * Get the layouts Options.
	 *
	 * @return array
	 */
	public static function get_layouts() {
		$layouts = array(
			'individual'  => __( 'Individual', 'neve-pro-addon' ),
			'header'      => __( 'Header', 'neve-pro-addon' ),
			'inside'      => __( 'Inside content', 'neve-pro-addon' ),
			'footer'      => __( 'Footer', 'neve-pro-addon' ),
			'global'      => __( 'Global', 'neve-pro-addon' ),
			'hooks'       => __( 'Hooks', 'neve-pro-addon' ),
			'not_found'   => __( '404 Page', 'neve-pro-addon' ),
			'single_post' => __( 'Single Post', 'neve-pro-addon' ),
			'single_page' => __( 'Single Page', 'neve-pro-addon' ),
			'search'      => __( 'Search', 'neve-pro-addon' ),
			'archives'    => __( 'Archives', 'neve-pro-addon' ),
		);

		if ( Loader::has_compatibility( 'custom_post_types_sidebar' ) ) {
			$layouts['sidebar'] = __( 'Sidebar', 'neve-pro-addon' );
		}

		if ( defined( 'PWA_VERSION' ) ) {
			$layouts['offline']      = __( 'Offline Page', 'neve-pro-addon' );
			$layouts['server_error'] = __( 'Internal Server Error Page', 'neve-pro-addon' );
		}
		return $layouts;
	}

	/**
	 * Get the display settings for all custom layouts.
	 *
	 * @access public
	 * @static
	 *
	 * @return array Array of layout display settings
	 */
	public static function get_layouts_display() {
		$layouts_display = array(
			'page_structure' => array(
				'label'   => __( 'Page Structure', 'neve-pro-addon' ),
				'layouts' => array(
					'header' => array(
						'label'       => __( 'Header', 'neve-pro-addon' ),
						'description' => __( 'Add content to header area.', 'neve-pro-addon' ),
						'icon'        => 'dashicons-heading',
					),
					'inside' => array(
						'label'       => __( 'Inside content', 'neve-pro-addon' ),
						'description' => __( 'Insert content withing the main content area.', 'neve-pro-addon' ),
						'icon'        => 'dashicons-align-wide',
					),
					'footer' => array(
						'label'       => __( 'Footer', 'neve-pro-addon' ),
						'description' => __( 'Add content to the footer area.', 'neve-pro-addon' ),
						'icon'        => 'dashicons-align-center',
					),
				),
			),
			'content_types'  => array(
				'label'   => __( 'Content Types', 'neve-pro-addon' ),
				'layouts' => array(
					'single_post' => array(
						'label'       => __( 'Single Post', 'neve-pro-addon' ),
						'description' => __( 'Custom layout for individual posts.', 'neve-pro-addon' ),
						'icon'        => 'dashicons-admin-post',
					),
					'single_page' => array(
						'label'       => __( 'Single Page', 'neve-pro-addon' ),
						'description' => __( 'Custom layout for individual pages.', 'neve-pro-addon' ),
						'icon'        => 'dashicons-admin-page',
					),
					'search'      => array(
						'label'       => __( 'Search', 'neve-pro-addon' ),
						'description' => __( 'Custom layout for search results.', 'neve-pro-addon' ),
						'icon'        => 'dashicons-search',
					),
					'archives'    => array(
						'label'       => __( 'Archives', 'neve-pro-addon' ),
						'description' => __( 'Custom layout for archive pages.', 'neve-pro-addon' ),
						'icon'        => 'dashicons-archive',
					),
				),
			),
			'special_cases'  => array(
				'label'   => __( 'Special Cases', 'neve-pro-addon' ),
				'layouts' => array(
					'not_found'  => array(
						'label'       => __( '404 Page', 'neve-pro-addon' ),
						'description' => __( 'Custom not found page layout.', 'neve-pro-addon' ),
						'icon'        => 'dashicons-warning',
					),
					'individual' => array(
						'label'       => __( 'Individual', 'neve-pro-addon' ),
						'description' => __( 'Custom layout for specific content.', 'neve-pro-addon' ),
						'icon'        => 'dashicons-admin-generic',
					),
					'hooks'      => array(
						'label'       => __( 'Hooks', 'neve-pro-addon' ),
						'description' => __( 'Insert content at a specific theme hooks.', 'neve-pro-addon' ),
						'icon'        => 'dashicons-admin-plugins',
					),
				),
			),
		);

		if ( Loader::has_compatibility( 'custom_post_types_sidebar' ) ) {
			$layouts_display['special_cases']['layouts']['sidebar'] = array(
				'label'       => __( 'Sidebar', 'neve-pro-addon' ),
				'description' => __( 'Add content to the sidebar area.', 'neve-pro-addon' ),
				'icon'        => 'dashicons-align-pull-left',
			);
		}

		if ( defined( 'PWA_VERSION' ) ) {
			$layouts_display['special_cases']['layouts']['offline']      = array(
				'label'       => __( 'Offline Page', 'neve-pro-addon' ),
				'description' => __( 'Custom layout for the offline PWA page.', 'neve-pro-addon' ),
				'icon'        => 'dashicons-dismiss',
			);
			$layouts_display['special_cases']['layouts']['server_error'] = array(
				'label'       => __( 'Internal Server Error Page', 'neve-pro-addon' ),
				'description' => __( 'Custom layout for the server error page.', 'neve-pro-addon' ),
				'icon'        => 'dashicons-warning',
			);
		}

		return $layouts_display;
	}

	/**
	 * Sidebar positions Options.
	 *
	 * @return array
	 */
	private static function get_sidebar_positions() {
		$sidebar_positions = [
			'blog' => __( 'Blog', 'neve-pro-addon' ),
		];
		if ( class_exists( 'LifterLMS', false ) ) {
			$sidebar_positions['lifter_lms'] = 'Lifter LMS';
		}
		if ( class_exists( 'WooCommerce', false ) ) {
			$sidebar_positions['woocommerce'] = 'WooCommerce';
		}
		return $sidebar_positions;
	}

	/**
	 * Sidebar actions Options.
	 *
	 * @return array
	 */
	private static function get_sidebar_actions() {
		return [
			'replace' => __( 'By selecting this option, the whole sidebar will be replaced with the content of this post.', 'neve-pro-addon' ),
			'append'  => __( 'By selecting this option, the content of this post will be added just after the sidebar.', 'neve-pro-addon' ),
			'prepend' => __( 'By selecting this option, the content of this post will be added just before the sidebar.', 'neve-pro-addon' ),
		];
	}

	/**
	 * Inside content Options.
	 *
	 * @return array[]
	 */
	private static function get_inside_positions() {
		return [
			'after' => [
				''                            => __( 'Select', 'neve-pro-addon' ),
				Inside_Layout::AFTER_HEADINGS => __( 'After certain number of headings', 'neve-pro-addon' ),
				Inside_Layout::AFTER_BLOCKS   => __( 'After certain number of blocks', 'neve-pro-addon' ),
			],
		];
	}

	/**
	 * Return all select options for the select controls.
	 * Used by the modal inside Custom Layouts Page.
	 *
	 * @return array
	 */
	public static function get_modal_select_options() {
		$layout           = self::get_layouts();
		$layout_templates = [ 'not_found', 'single_post', 'single_page', 'search', 'archives' ];
		$excluded         = [ 'hooks', 'global' ];

		$templates_filtered = array_filter(
			$layout,
			function ( $key ) use ( $layout_templates ) {
				return in_array( $key, $layout_templates, true );
			},
			ARRAY_FILTER_USE_KEY 
		);

		$components_filtered = array_filter(
			$layout,
			function ( $key ) use ( $layout_templates, $excluded ) {
				return ! in_array( $key, array_merge( $layout_templates, $excluded ), true );
			},
			ARRAY_FILTER_USE_KEY 
		);

		$templates  = array_merge( [ 'none' => __( 'Select', 'neve-pro-addon' ) ], $templates_filtered );
		$components = array_merge( [ 'none' => __( 'Select', 'neve-pro-addon' ) ], $components_filtered );
		$hooks      = array_merge( [ 'none' => __( 'Select a hook', 'neve-pro-addon' ) ], neve_hooks() );

		return [
			'templates'  => $templates,
			'components' => $components,
			'hooks'      => $hooks,
		];
	}

	/**
	 * Return all select options for the select controls.
	 * Used by the sidebar inside Gutenberg.
	 *
	 * @return array
	 */
	public static function get_sidebar_select_options() {
		$layout            = array_merge( [ 'none' => __( 'Select', 'neve-pro-addon' ) ], self::get_layouts() );
		$hooks             = array_merge( [ 'none' => __( 'Select a hook', 'neve-pro-addon' ) ], neve_hooks() );
		$sidebar_positions = self::get_sidebar_positions();
		$sidebar_actions   = array_merge( [ 'none' => __( 'Select an action', 'neve-pro-addon' ) ], self::get_sidebar_actions() );
		$inside_positions  = self::get_inside_positions();

		$conditional_display = new Conditional_Display();

		$layout = array_filter(
			$layout,
			function ( $key ) {
				return $key !== 'global';
			},
			ARRAY_FILTER_USE_KEY
		);

		return [
			'layouts'          => $layout,
			'hooks'            => $hooks,
			'sidebarPositions' => $sidebar_positions,
			'sidebarActions'   => $sidebar_actions,
			'insidePositions'  => $inside_positions,
			'conditions'       => [
				'root' => $conditional_display->get_root_ruleset(),
				'end'  => $conditional_display->get_end_ruleset(),
				'map'  => $conditional_display->get_ruleset_map(),
			],
		];
	}
}
