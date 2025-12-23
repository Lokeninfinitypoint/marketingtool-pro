<?php
/**
 * Custom Layouts Main Class
 *
 * @package Neve Pro Addon
 */

namespace Neve_Pro\Modules\Custom_Layouts;

use Neve_Pro\Admin\Custom_Layouts_Cpt;
use Neve_Pro\Core\Abstract_Module;
use Neve_Pro\Core\Loader as CoreLoader;
use Neve_Pro\Modules\Custom_Layouts\Admin\Builders\Loader;
use Neve_Pro\Modules\Custom_Layouts\Admin\Layouts_Metabox;
use Neve_Pro\Modules\Custom_Layouts\Elementor\Elementor_Widgets_Manager;
use Neve_Pro\Admin\Conditional_Display;
use Neve_Pro\Modules\Custom_Layouts\Utilities;

/**
 * Class Module
 *
 * @package Neve_Pro\Modules\Custom_Layouts
 */
class Module extends Abstract_Module {
	use Utilities;

	/**
	 * Holds the base module namespace
	 * Used to load submodules.
	 *
	 * @var string $module_namespace
	 */
	private $module_namespace = 'Neve_Pro\Modules\Custom_Layouts';


	/**
	 * Layout types used for navigation views.
	 * 
	 * @var array $layout_types_navigation_view Array containing all available layout types for navigation views.
	 * 
	 * @access private
	 */
	private $layout_types_navigation_view = array(
		'header',
		'footer',
		'hooks',
		'inside',
		'not_found',
		'single_post',
		'single_page',
		'archives',
	);

	/**
	 * Define module properties.
	 *
	 * @access  public
	 * @return void
	 *
	 * @version 1.0.0
	 */
	public function define_module_properties() {
		$this->slug  = 'custom_layouts';
		$this->order = 6;
	}

	/**
	 * Setup module labels.
	 */
	public function setup_labels() {
		$this->name          = __( 'Custom Layouts', 'neve-pro-addon' );
		$this->description   = __( 'Create conditional headers, footers, and content blocks. Perfect for custom landing pages and marketing campaigns.', 'neve-pro-addon' );
		$this->documentation = array(
			'url'   => 'https://docs.themeisle.com/article/1062-custom-layouts-module',
			'label' => __( 'Learn more', 'neve-pro-addon' ),
		);
	}

	/**
	 * Check if module should load.
	 *
	 * @return bool
	 */
	function should_load() {
		return $this->is_active();
	}

	/**
	 * Run Custom Layouts module.
	 * This function runs at init hook which is too early for public actions in Beaver Builder, so we need to stall it a bit.
	 */
	function run_module() {
		$this->do_admin_actions();
		add_action( 'init', array( $this, 'run_public' ) );
		add_filter( 'neve_custom_layouts_post_type_args', [ $this, 'change_custom_layouts_cpt' ], 11 );
		add_action( 'registered_post_type_neve_custom_layouts', array( $this, 'register_custom_meta' ) );
	}

	/**
	 * Register meta for custom post type.
	 * To be updated from the React Sidebar.
	 *
	 * @return void
	 */
	final public function register_custom_meta() {

		$meta_to_register = [
			Layouts_Metabox::META_LAYOUTS        => [
				'type'    => 'string',
				'default' => 'none',
			],
			Layouts_Metabox::META_HOOKS          => [
				'type'    => 'string',
				'default' => 'none',
			],
			Layouts_Metabox::META_SIDEBAR        => [
				'type'    => 'string',
				'default' => 'blog',
			],
			Layouts_Metabox::META_SIDEBAR_ACTION => [
				'type'    => 'string',
				'default' => 'none',
			],
			Layouts_Metabox::META_HAS_EXPIRATION => [
				'type'    => 'boolean',
				'default' => false,
			],
			Layouts_Metabox::META_EXPIRATION     => [
				'type'    => 'string',
				'default' => '',
			],
			Layouts_Metabox::META_INSIDE         => [
				'type'    => 'string',
				'default' => 'none',
			],
			Layouts_Metabox::META_EVENTS_NO      => [
				'type'    => 'integer',
				'default' => 1,
			],
			Layouts_Metabox::META_PRIORITY       => [
				'type'    => 'integer',
				'default' => 10,
			],
			Layouts_Metabox::META_CONDITIONAL    => [
				'type'    => 'string',
				'default' => '',
			],
		];

		foreach ( $meta_to_register as $meta => $props ) {
			register_post_meta(
				'neve_custom_layouts',
				$meta,
				[
					'type'              => $props['type'],
					'default'           => $props['default'],
					'single'            => true,
					'show_in_rest'      => true,
					'sanitize_callback' => 'sanitize_text_field',
					'auth_callback'     => function () {
						return current_user_can( 'edit_posts' );
					},
				]
			);
		}

	}

	/**
	 * Run public actions
	 */
	public function run_public() {
		if ( $this->should_do_public_actions() !== true ) {
			return false;
		}
		$this->do_public_actions();
		return true;
	}

	/**
	 * Get show in menu parameter.
	 *
	 * @deprecated This is only valid for the old version of the plugin where there was no theme menu item.
	 * 
	 * @return bool|string
	 */
	private function get_show_in_menu() {
		if ( current_user_can( 'administrator' ) ) {
			return ! CoreLoader::has_compatibility( 'theme_dedicated_menu' ) ? 'themes.php' : false;
		}

		return false;
	}

	/**
	 * Make the Custom Layouts CPT public.
	 *
	 * @param array $config the CPT configuration array.
	 *
	 * @return array
	 * @hooked \Neve_Pro\Admin\Custom_Layouts_Cpt
	 */
	public function change_custom_layouts_cpt( $config ) {
		$show_ui_cap = isset( $config['capabilities'], $config['capabilities']['edit_posts'] ) ? $config['capabilities']['edit_posts'] : 'administrator';
		
		$show = current_user_can( $show_ui_cap );

		return array_merge(
			$config,
			[
				'public'            => $show,
				'show_in_menu'      => $this->get_show_in_menu(),
				'show_ui'           => $show,
				'show_in_admin_bar' => $show,
				'supports'          => array_unique( array_merge( $config['supports'], array( 'custom-fields', 'thumbnail', 'comments', 'author', 'excerpt', 'page-attributes' ) ) ),
			]
		);
	}

	/**
	 * Do admin related actions.
	 */
	private function do_admin_actions() {
		$this->load_submodules();
		$this->run_hooks();

		return true;
	}

	/**
	 * Load admin files.
	 */
	private function load_submodules() {
		$submodules = array(
			$this->module_namespace . '\Rest\Server',
			$this->module_namespace . '\Admin\Layouts_Metabox',
			$this->module_namespace . '\Admin\PHP_Editor_Admin',
			$this->module_namespace . '\Admin\View_Hooks',
		);

		$mods = [];
		foreach ( $submodules as $index => $mod ) {
			if ( class_exists( $mod ) ) {
				$mods[ $index ] = new $mod();
				$mods[ $index ]->init();
			}
		}
	}

	/**
	 * Add hooks and filters.
	 */
	private function run_hooks() {
		/**
		 * Allow custom layouts cpt to be edited with Beaver Builder.
		 */
		if ( class_exists( 'FLBuilderModel', false ) ) {
			add_filter( 'fl_builder_post_types', array( $this, 'beaver_compatibility' ), 10, 1 );
			add_filter( 'fl_render_content_by_id_can_view', '__return_true' );
		}

		/**
		 * Add a custom template for Custom Layouts cpt preview.
		 */
		add_filter( 'single_template', array( $this, 'custom_layouts_single_template' ) );

		/**
		 * Enqueue admin scripts and styles.
		 */
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		/**
		 * Enqueue sidebar scripts
		 */
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_editor_assets' ) );

		/**
		 * Add support for Brizy.
		 */
		add_filter( 'brizy_supported_post_types', array( $this, 'register_brizy_support' ) );

		/** Drop page templates for custom layouts post type */
		add_filter( 'theme_neve_custom_layouts_templates', '__return_empty_array', PHP_INT_MAX );

		/**
		 * Register Elementor widget.
		 */
		$elementor_widget_manager = new Elementor_Widgets_Manager();
		$elementor_widget_manager->run();

		/**
		 * Register shortcode widget.
		 */
		add_shortcode( 'nv-custom-layout', array( $this, 'custom_layout_shortcode' ) );

		/**
		 * Register filter for displaying Custom Layouts in dashboard.
		 */
		add_action( 'pre_get_posts', array( $this, 'admin_filter_posts_display' ) );

		/**
		 * Register AJAX action for custom layout toggle
		 */
		add_action( 'wp_ajax_neve_toggle_custom_layout', array( $this, 'handle_toggle_custom_layout' ) );
	}

	/**
	 * Add support for brizy editor in custom layouts.
	 *
	 * @param array $post_types Brizy post types support.
	 *
	 * @return array
	 */
	public function register_brizy_support( $post_types ) {
		$post_types[] = 'neve_custom_layouts';

		return $post_types;
	}

	/**
	 * Check if public actions should occur.
	 *
	 * @return bool
	 */
	private function should_do_public_actions() {
		if ( $this->is_builder_preview() ) {
			return true;
		}

		$posts_array = Custom_Layouts_Cpt::get_custom_layouts();
		if ( empty( $posts_array ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Check if is builder preview.
	 *
	 * @return bool
	 */
	private function is_builder_preview() {
		if ( array_key_exists( 'preview', $_GET ) && ! empty( $_GET['preview'] ) ) {
			return true;
		}

		if ( array_key_exists( 'elementor-preview', $_GET ) && ! empty( $_GET['elementor-preview'] ) ) {
			return true;
		}

		if ( array_key_exists( 'brizy-edit', $_GET ) && ! empty( $_GET['brizy-edit'] ) ) {
			return true;
		}

		if ( class_exists( 'FLBuilderModel', false ) ) {
			if ( \FLBuilderModel::is_builder_active() === true ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Load public files.
	 */
	private function do_public_actions() {
		if ( is_admin() ) {
			return false;
		}

		$loader = new Loader( $this->module_namespace . '\Admin\Builders\\' );

		return true;
	}

	/**
	 * Add Beaver Builder Compatibility
	 *
	 * @param array $value Post types.
	 *
	 * @return array
	 */
	public function beaver_compatibility( $value ) {
		$value[] = 'neve_custom_layouts';

		return $value;
	}

	/**
	 * Set path to neve_custom_layouts template.
	 *
	 * @param string $single Path to single.php .
	 *
	 * @return string
	 */
	public function custom_layouts_single_template( $single ) {
		global $post;
		if ( $post->post_type === 'neve_custom_layouts' && file_exists( plugin_dir_path( __FILE__ ) . 'admin/template.php' ) ) {
			return plugin_dir_path( __FILE__ ) . 'admin/template.php';
		}

		return $single;
	}

	/**
	 * Enqueue Gutenberg editor sidebar script
	 */
	public function enqueue_editor_assets() {
		$screen = get_current_screen();
		if ( 'neve_custom_layouts' !== $screen->post_type ) {
			return; // disabled for other custom post types
		}

		$relative_path = 'includes/modules/custom_layouts/assets/app/';
		$dependencies  = include NEVE_PRO_PATH . $relative_path . '/build/app.asset.php';
		wp_enqueue_script(
			'neve-pro-addon-custom-layout-sidebar',
			NEVE_PRO_URL . $relative_path . 'build/app.js',
			array_merge( $dependencies['dependencies'], [ 'wp-edit-post', 'wp-element', 'wp-components', 'wp-plugins', 'wp-data' ] ),
			$dependencies['version'],
			true
		);

		wp_register_style(
			'neve-pro-addon-custom-layout-sidebar',
			NEVE_PRO_URL . $relative_path . 'build/style-app.css',
			[
				'neve-components',
				'dashicons',
			],
			$dependencies['version']
		);
		wp_style_add_data( 'neve-pro-addon-custom-layout-sidebar', 'rtl', 'replace' );
		wp_style_add_data( 'neve-pro-addon-custom-layout-sidebar', 'suffix', '.min' );
		wp_enqueue_style( 'neve-pro-addon-custom-layout-sidebar' );

		wp_set_script_translations( 'neve-pro-addon-custom-layout-sidebar', 'neve-pro-addon' );
	}

	/**
	 * Enqueue scripts.
	 */
	public function admin_enqueue_scripts() {
		global $pagenow;
		if ( ! in_array( $pagenow, array( 'post.php', 'post-new.php', 'edit.php' ), true ) ) {
			return;
		}

		global $post;
		$post_type = $post !== null ? $post_type = $post->post_type : '';

		if ( empty( $post_type ) && isset( $_GET['post_type'] ) ) {
			$post_type = sanitize_text_field( $_GET['post_type'] );
		}

		if ( $post_type !== 'neve_custom_layouts' ) {
			return;
		}

		if ( ! function_exists( 'wp_enqueue_code_editor' ) ) {
			return;
		}

		wp_enqueue_code_editor(
			array(
				'type'       => 'application/x-httpd-php',
				'codemirror' => array(
					'indentUnit' => 2,
					'tabSize'    => 2,
				),
			)
		);

		if ( in_array( $pagenow, array( 'edit.php' ), true ) ) {
			$relative_path = 'includes/modules/custom_layouts/assets/js/';
			$dependencies  = include NEVE_PRO_PATH . $relative_path . '/build/modal.asset.php';
			$script_handle = 'neve-pro-addon-custom-layout-modal';

			wp_enqueue_script( $script_handle, NEVE_PRO_INCLUDES_URL . 'modules/custom_layouts/assets/js/build/modal.js', array_merge( $dependencies['dependencies'], [ 'wp-edit-post', 'wp-element', 'wp-components', 'wp-plugins', 'wp-data' ] ), $dependencies['version'], true );

			wp_localize_script(
				$script_handle,
				'neveCustomLayouts',
				array(
					'newLayoutUrl'        => esc_url( admin_url( 'post-new.php?post_type=neve_custom_layouts' ) ),
					'customLayoutOptions' => Layouts_Metabox::get_modal_select_options(),
					'customLayoutDocsURL' => esc_url( 'https://docs.themeisle.com/article/1062-custom-layouts-module' ),
					'layoutsDisplay'      => Layouts_Metabox::get_layouts_display(),
					'nonce'               => wp_create_nonce( 'neve_custom_layouts_nonce' ),
				)
			);

			$this->rtl_enqueue_style( 'neve-pro-addon-custom-layout-modal', NEVE_PRO_INCLUDES_URL . 'modules/custom_layouts/assets/modal.min.css', array(), $dependencies['version'] );

			wp_set_script_translations( $script_handle, 'neve-pro-addon' );

			add_filter( 'manage_neve_custom_layouts_posts_columns', array( $this, 'admin_table_columns' ) );
			add_action( 'manage_neve_custom_layouts_posts_custom_column', array( $this, 'admin_render_table_columns' ), 10, 2 );
			add_filter( 'views_edit-neve_custom_layouts', array( $this, 'admin_render_views_edit' ) );
			add_filter( 'post_row_actions', array( $this, 'admin_hide_quick_edit' ), 10, 2 );
			add_filter( 'manage_edit-neve_custom_layouts_sortable_columns', array( $this, 'admin_sortable_columns' ) );

			return;
		}

		$is_gutenberg  = get_current_screen()->is_block_editor();
		$script_handle = 'neve-pro-addon-custom-layout-sidebar';
		if ( ! $is_gutenberg ) {
			wp_enqueue_script( 'neve-pro-addon-custom-layout', NEVE_PRO_INCLUDES_URL . 'modules/custom_layouts/assets/js/build/script.js', array(), NEVE_PRO_VERSION, true );
			$script_handle = 'neve-pro-addon-custom-layout';
		}


		wp_localize_script(
			$script_handle,
			'neveCustomLayouts',
			array(
				'customEditorEndpoint' => rest_url( '/wp/v2/neve_custom_layouts/' . $post->ID ),
				'ajaxOptions'          => rest_url( NEVE_PRO_REST_NAMESPACE . '/custom-layouts/options' ),
				'nonce'                => wp_create_nonce( 'wp_rest' ),
				'phpError'             => esc_html__( 'There are some errors in your PHP code. Please fix them before saving the code.', 'neve-pro-addon' ),
				'magicTags'            => Layouts_Metabox::$magic_tags,
				'strings'              => array(
					'magicTagsDescription' => esc_html__( 'You can add the following tags in your template:', 'neve-pro-addon' ),
					'individualLayoutShd'  => Layouts_Metabox::get_shortcode_info(),
					'copiedToClipboard'    => esc_html__( 'Copied to clipboard', 'neve-pro-addon' ),
					'replace'              => esc_html__( 'By selecting this option, the whole sidebar will be replaced with the content of this post.', 'neve-pro-addon' ),
					'append'               => esc_html__( 'By selecting this option, the content of this post will be added just after the sidebar.', 'neve-pro-addon' ),
					'prepend'              => esc_html__( 'By selecting this option, the content of this post will be added just before the sidebar.', 'neve-pro-addon' ),
				),
				'conditionMap'         => Conditional_Display::create_custom_layouts_condition_text_map(),
				'sidebarOptions'       => Layouts_Metabox::get_sidebar_select_options(),
				'renderDebug'          => ( defined( 'REACT_RENDER_DEBUG' ) && REACT_RENDER_DEBUG ) ? 'true' : 'false',
			)
		);

		$this->rtl_enqueue_style( 'neve-pro-addon-custom-layouts', NEVE_PRO_INCLUDES_URL . 'modules/custom_layouts/assets/admin_style.min.css', array(), NEVE_PRO_VERSION );
	}

	/**
	 * Shortcode for custom layouts.
	 *
	 * @param array $attrs Shortcode attributes.
	 *
	 * @return false|string
	 */
	public function custom_layout_shortcode( $attrs ) {

		$attributes = shortcode_atts(
			array(
				'id' => 'none',
			),
			$attrs
		);

		$user_can_edit = current_user_can( 'editor' ) || current_user_can( 'administrator' );
		if ( (int) $attributes['id'] === get_the_ID() ) {
			if ( $user_can_edit ) {
				return esc_html__( 'You cannot have the shortcode of a custom layout in the same custom layout.', 'neve-pro-addon' );
			}
			return false;
		}

		if ( 'none' === $attributes['id'] ) {
			if ( $user_can_edit ) {
				return esc_html__( 'You need to add the id attribute of the custom layout you want to display in shortcode parameters. E.g, [nv-custom-layout id="123"]', 'neve-pro-addon' );
			}
			return false;
		}

		if ( 'neve_custom_layouts' !== get_post_type( (int) $attributes['id'] ) ) {
			if ( $user_can_edit ) {
				/* translators: %s is post id */
				return sprintf( esc_html__( 'The custom layout with id %s does not exist.', 'neve-pro-addon' ), $attributes['id'] );
			}
			return false;
		}

		$layout = get_post_meta( (int) $attributes['id'], 'custom-layout-options-layout', true );
		if ( 'individual' !== $layout ) {
			if ( $user_can_edit ) {
				return esc_html__( 'The layout that you\'ve selected is not of "individual" type.', 'neve-pro-addon' );
			}
			return false;
		}

		ob_start();
		do_action( 'neve_do_individual', $attributes['id'] );
		return ob_get_clean();
	}

	/**
	 * Filter WordPress admin columns for Custom Layout post type.
	 *
	 * Removes default 'comments' and 'author' columns and adds custom columns for
	 * layout type, location, and shortcode.
	 *
	 * @access public
	 * @param array $columns Array of column name => label.
	 * @return array Modified array of column name => label.
	 */
	public function admin_table_columns( $columns ) {
		unset( $columns['comments'] );
		unset( $columns['author'] );

		$date = $columns['date'];
		unset( $columns['date'] );
	
		$columns['type']      = __( 'Type', 'neve-pro-addon' );
		$columns['location']  = __( 'Location', 'neve-pro-addon' );
		$columns['shortcode'] = __( 'Shortcode', 'neve-pro-addon' );
		$columns['enabled']   = __( 'Status', 'neve-pro-addon' );
		$columns['date']      = $date;
		
		return $columns;
	}

	/**
	 * Renders the custom columns for the Custom Layout post type in the admin table.
	 *
	 * @param string $column  The name of the column to render.
	 * @param int    $post_id The ID of the post being displayed.
	 *
	 * @access public
	 * @return void
	 */
	public function admin_render_table_columns( $column, $post_id ) {
	
		$layout_type_label     = '-';
		$layout_location_label = '-';
		
		if ( 'type' === $column ) {
			$layout            = get_post_meta( $post_id, Layouts_Metabox::META_LAYOUTS, true );
			$available_layouts = self::get_layouts();
			$layout_type_label = isset( $available_layouts[ $layout ] ) ? $available_layouts[ $layout ] : '-';
		}
	
		if ( 'location' === $column ) {

			$layout       = get_post_meta( $post_id, Layouts_Metabox::META_LAYOUTS, true );
			$hook_handler = get_post_meta( $post_id, Layouts_Metabox::META_HOOKS, true );

			if ( 'hooks' === $layout && ! empty( $hook_handler ) ) {
				$available_hooks = neve_hooks();
	
				$hooks_location_labels = array(
					'header'           => __( 'Page Header', 'neve-pro-addon' ),
					'footer'           => __( 'Page Footer', 'neve-pro-addon' ),
					'post'             => __( 'Post', 'neve-pro-addon' ),
					'page'             => __( 'Page', 'neve-pro-addon' ),
					'single'           => __( 'Single', 'neve-pro-addon' ),
					'sidebar'          => __( 'Sidebar', 'neve-pro-addon' ),
					'blog'             => __( 'Blog', 'neve-pro-addon' ),
					'pagination'       => __( 'Pagination', 'neve-pro-addon' ),
					'shop'             => __( 'Shop', 'neve-pro-addon' ),
					'product'          => __( 'Product', 'neve-pro-addon' ),
					'cart'             => __( 'Cart', 'neve-pro-addon' ),
					'checkout'         => __( 'Checkout', 'neve-pro-addon' ),
					'login'            => __( 'Login', 'neve-pro-addon' ),
					'register'         => __( 'Register', 'neve-pro-addon' ),
					'account'          => __( 'Account', 'neve-pro-addon' ),
					'download-archive' => __( 'Download Archive', 'neve-pro-addon' ),
					'single-download'  => __( 'Single Download', 'neve-pro-addon' ),
				);
	
				foreach ( $available_hooks as $root => $hooks ) {
					if ( in_array( $hook_handler, $hooks ) ) {
						$layout_location_label = isset( $hooks_location_labels[ $root ] ) ? $hooks_location_labels[ $root ] : ucfirst( $root );
						break;
					}
				}
			} else {
				if ( 'header' === $layout || 'inside' === $layout || 'footer' === $layout ) {
					$layout_location_label = __( 'All Pages', 'neve-pro-addon' );
				}
			}
		}

		switch ( $column ) {
			case 'type':
				echo esc_html( $layout_type_label );
				break;
			case 'shortcode':
				$allowed_html = array(
					'span'   => array(
						'class' => array(),
					),
					'button' => array(
						'class' => array(),
						'type'  => array(),
					),
					'i'      => array(
						'class' => array(), 
					),
				);
				echo wp_kses(
					'<span class="cl-column-shortcode">[nv-custom-layout id="' . $post_id . '"]</span>' .
					'<button type="button" class="cl-copy-button button-secondary"><i class="dashicons dashicons-clipboard cl-copy-icon"></i></button>',
					$allowed_html
				);
				break;
			case 'location':
				echo esc_html( $layout_location_label );
				break;
			case 'enabled':
				$status     = get_post_status( $post_id );
				$is_enabled = $status === 'publish';

				echo wp_kses(
					sprintf(
						'<div class="cl-toggle-wrapper"><label class="cl-toggle"><input type="checkbox" class="cl-toggle-input" data-post-id="%d" %s><span class="cl-slider"></span></label><span class="cl-toggle-label"></span><div class="cl-toggle-error"></div></div>',
						$post_id,
						$is_enabled ? 'checked' : ''
					),
					array(
						'div'   => array(
							'class' => array(),
						),
						'label' => array(
							'class' => array(),
						),
						'input' => array(
							'type'         => array(),
							'class'        => array(),
							'data-post-id' => array(),
							'checked'      => array(),
						),
						'span'  => array(
							'class' => array(),
						),
					)
				);
				break;
		}
	}

	/**
	 * Get an array of shortcut links for header/footer layouts.
	 *
	 * @return array Array of shortcut links indexed by layout type.
	 */
	public function get_shortcuts_links_by_type() {
		$layout_types_labels = self::get_layouts();
		
		$counts = array();
		foreach ( $this->layout_types_navigation_view as $layout_type ) {
			$counts[ $layout_type ] = array(
				'label' => $layout_types_labels[ $layout_type ],
				'link'  => add_query_arg(
					array(
						'template'  => $layout_type,
						'post_type' => 'neve_custom_layouts',
					),
					admin_url( 'edit.php' ) 
				),
				'count' => 0,
			);
		}

		$query = new \WP_Query(
			array(
				'post_type'      => 'neve_custom_layouts',
				'posts_per_page' => 100,
				'fields'         => 'ids',
				'meta_key'       => Layouts_Metabox::META_LAYOUTS,
				'no_found_rows'  => true,
			) 
		);

		$results = array();
		foreach ( $query->posts as $post_id ) {
			$layout_type = get_post_meta( $post_id, Layouts_Metabox::META_LAYOUTS, true );
			if ( ! isset( $results[ $layout_type ] ) ) {
				$results[ $layout_type ] = 0; 
			}
			$results[ $layout_type ]++;
		}
		
		$custom_count = 0;
		foreach ( $results as $type => $count ) {
			if ( in_array( $type, $this->layout_types_navigation_view, true ) ) {
				$counts[ $type ]['count'] = $count;
			} else {
				$custom_count += $count;
			}
		}

		$counts['custom'] = array(
			'label' => __( 'Custom Templates', 'neve-pro-addon' ),
			'link'  => add_query_arg(
				array(
					'template'  => 'custom-templates',
					'post_type' => 'neve_custom_layouts',
				),
				admin_url( 'edit.php' ) 
			),
			'count' => $custom_count,
		);

		return $counts;
	}

	/**
	 * Filters the posts display query.
	 *
	 * @param \WP_Query $query The WordPress query object.
	 * @return void
	 */
	public function admin_filter_posts_display( $query ) {
		if ( ! is_admin() || 'neve_custom_layouts' !== $query->get( 'post_type' ) || ! $query->is_main_query() ) {
			return;
		}

		if ( isset( $_GET['template'] ) ) {
			$meta_value = sanitize_text_field( $_GET['template'] );
	
			if ( 'custom-templates' === $meta_value ) {
				$query->set(
					'meta_query',
					array(
						array(
							'key'     => Layouts_Metabox::META_LAYOUTS,
							'value'   => $this->layout_types_navigation_view,
							'compare' => 'NOT IN',
						),
					)
				);
				return;
			}
		
			$query->set(
				'meta_query',
				array(
					array(
						'key'     => Layouts_Metabox::META_LAYOUTS,
						'value'   => sanitize_text_field( $_GET['template'] ),
						'compare' => '=',
					),
				)
			);
		}

		if ( 'type' === $query->get( 'orderby' ) ) {
			$query->set( 'meta_key', Layouts_Metabox::META_LAYOUTS );
			$query->set( 'orderby', 'meta_value' );
		}

	}

	/**
	 * Handle the AJAX toggle for custom layouts
	 */
	public function handle_toggle_custom_layout() {
		
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
		if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, 'neve_custom_layouts_nonce' ) ) {
			wp_send_json_error( __( 'Invalid nonce. Try again after refreshing the page.', 'neve-pro-addon' ), 403 );
		}
		
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( __( 'Insufficient permissions. You can not edit the post.', 'neve-pro-addon' ), 401 );
		}
		
		$post_id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;
		$status  = isset( $_POST['status'] ) ? sanitize_text_field( $_POST['status'] ) : '';
		
		$post = get_post( $post_id );
		if ( ! $post || $post->post_type !== 'neve_custom_layouts' ) {
			wp_send_json_error( __( 'Invalid post.', 'neve-pro-addon' ), 404 );
		}
	
		if ( ! in_array( $status, array( 'publish', 'draft' ), true ) ) {
			wp_send_json_error( __( 'Invalid status.', 'neve-pro-addon' ), 400 );
		}
		
		$updated = wp_update_post(
			array(
				'ID'          => $post_id,
				'post_status' => $status,
			)
		);

		if ( 0 === $updated ) {
			wp_send_json_error( __( 'Failed to update status.', 'neve-pro-addon' ), 500 );
		}

		wp_send_json_success();
	}

	/**
	 * Modifies the views displayed in the layout edit screen.
	 * 
	 * @param array $views Array of available view links.
	 * @return array Modified array of view links.
	 */
	public function admin_render_views_edit( $views ) {
		
		$filter_shortcuts_list = $this->get_shortcuts_links_by_type();
	
		foreach ( $filter_shortcuts_list as $slug => $shortcut ) {
			$views[ $slug ] = sprintf( '<a href="%1$s">%2$s <span class="count">(%3$s)</span></a>', esc_url_raw( $shortcut['link'] ), esc_html( $shortcut['label'] ), esc_html( $shortcut['count'] ) );
		}
	
		return $views;
	}

	/**
	 * Hides the quick edit option for Custom Layouts in `edit.php` table.
	 *
	 * @param array    $actions Array of post row actions.
	 * @param \WP_Post $post    The post object.
	 * @return array Modified array of post row actions.
	 */
	public function admin_hide_quick_edit( $actions, $post ) {
		if ( 'neve_custom_layouts' === $post->post_type ) {
			unset( $actions['inline hide-if-no-js'] );
		}
		return $actions;
	}

	/**
	 * Adds sortable columns to the admin Custom Layouts list table.
	 *
	 * @param array $sortable_columns The sortable columns array.
	 * @return array The modified sortable columns array.
	 */
	public function admin_sortable_columns( $sortable_columns ) {
		$sortable_columns['type'] = 'type';
		return $sortable_columns;
	}
}
