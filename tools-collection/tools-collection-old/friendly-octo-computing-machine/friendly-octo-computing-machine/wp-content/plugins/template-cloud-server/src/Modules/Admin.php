<?php

namespace TI\Template_Cloud\Modules;

use TI\Template_Cloud\Common\Constants;
use TI\Template_Cloud\Common\License;
use TI\Template_Cloud\Models\Access_Keys_Model;

class Admin implements Module_Interface {
	private const API                = 'https://api.themeisle.com/';
	public const ADMIN_PAGE_SLUG     = 'ti-template-cloud';
	public const COLLECTION_TAXONOMY = 'tc-collection';
	public const CATEGORY_TAXONOMY   = 'wp_pattern_category';
	/**
	 * Strings array.
	 *
	 * @var string[] $i18n The internationalization strings.
	 */
	private array $i18n;

	/**
	 * Admin constructor.
	 */
	public function __construct() {
		$this->i18n = [];
	}

	/**
	 * Initialize the module.
	 *
	 * @return void
	 */
	public function init() {
		License::get_instance();
		add_action(
			'init',
			function () {
				$this->i18n = Constants::get_strings();
			}
		);
		add_action( 'init', [ $this, 'register_pattern_collection_taxonomy' ] );
		add_action( 'admin_menu', [ $this, 'add_admin_page' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_dashboard' ] );
	}

	/**
	 * Register collections taxonomy for block patterns
	 */
	public function register_pattern_collection_taxonomy() {
		$labels = array(
			'name'                       => _x( 'Collections', 'taxonomy general name', 'template-cloud-server' ),
			'singular_name'              => _x( 'Collection', 'taxonomy singular name', 'template-cloud-server' ),
			'search_items'               => __( 'Search Collections', 'template-cloud-server' ),
			'popular_items'              => __( 'Popular Collections', 'template-cloud-server' ),
			'all_items'                  => __( 'All Collections', 'template-cloud-server' ),
			'edit_item'                  => __( 'Edit Collection', 'template-cloud-server' ),
			'update_item'                => __( 'Update Collection', 'template-cloud-server' ),
			'add_new_item'               => __( 'Add New Collection', 'template-cloud-server' ),
			'new_item_name'              => __( 'New Collection Name', 'template-cloud-server' ),
			'separate_items_with_commas' => __( 'Separate collections with commas', 'template-cloud-server' ),
			'add_or_remove_items'        => __( 'Add or remove collections', 'template-cloud-server' ),
			'choose_from_most_used'      => __( 'Choose from the most used collections', 'template-cloud-server' ),
			'menu_name'                  => __( 'Collections', 'template-cloud-server' ),
		);

		$args = array(
			'hierarchical'          => true,
			'labels'                => $labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'show_in_rest'          => true,
			'rest_base'             => 'tc-collection',
			'show_in_quick_edit'    => true,
			'query_var'             => true,
			'rewrite'               => array( 'slug' => self::COLLECTION_TAXONOMY ),
			'show_in_menu'          => true,
			'public'                => true,
			'update_count_callback' => '_update_post_term_count',
			'show_in_nav_menus'     => false,
			'meta_box_cb'           => null,
		);

		register_taxonomy( self::COLLECTION_TAXONOMY, array( 'wp_block' ), $args );
	}

	/**
	 * Adds the admin page.
	 *
	 * @return void
	 */
	public function add_admin_page() {
		add_submenu_page(
			'themes.php',
			__( 'Template Cloud', 'template-cloud-server' ),
			__( 'Template Cloud', 'template-cloud-server' ),
			'manage_options',
			self::ADMIN_PAGE_SLUG,
			[ $this, 'render_admin_page' ]
		);
	}

	/**
	 * Render the admin page.
	 *
	 * @return void
	 */
	public function render_admin_page() {
		echo '<div id="ti-tc-dash"><div class="w-full flex items-center justify-center min-h-[90vh] text-primary"><div class="animate-spin size-6"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"></path></svg></div></div></div>';
	}

	/**
	 * Enqueue the dashboard styles and scripts.
	 *
	 * @param string $hook The page hook.
	 *
	 * @return void
	 */
	public function enqueue_dashboard( $hook ) {
		if ( 'appearance_page_' . self::ADMIN_PAGE_SLUG !== $hook ) {
			return;
		}

		$script_handle = 'ti-tc-dashboard';
		$dependencies  = require TI_TC_SERVER_PATH . 'assets/build/dashboard.asset.php';
		$keys_model    = new Access_Keys_Model();

		wp_register_script( $script_handle, TI_TC_SERVER_URL . 'assets/build/dashboard.js', $dependencies['dependencies'], $dependencies['version'], true );
		wp_register_style( $script_handle, TI_TC_SERVER_URL . 'assets/build/style-dashboard.css', array( 'wp-components' ), $dependencies['version'] );

		wp_localize_script(
			$script_handle,
			'TCDash',
			array(
				'i18n'         => $this->i18n,
				'version'      => TI_TC_SERVER_VERSION,
				'licenseTIOB'  => License::get_license_data(),
				'api'          => self::API,
				'restUrl'      => rest_url(),
				'collections'  => get_terms(
					array(
						'taxonomy'   => self::COLLECTION_TAXONOMY,
						'hide_empty' => false,
					)
				),
				'categories'   => get_terms(
					array(
						'taxonomy'   => self::CATEGORY_TAXONOMY,
						'hide_empty' => false,
					)
				),
				'keysEndpoint' => rest_url( Server::API_NAMESPACE . '/keys' ),
				'accessKeys'   => $keys_model->get_saved_data(),
				'homeUrl'      => home_url(),
				'links'        => array(
					'support'       => tsdk_translate_link( tsdk_utmify( 'https://themeisle.com/contact/', 'settings_page' ), 'query' ),
					'documentation' => tsdk_utmify( 'https://docs.themeisle.com/article/1354-neve-template-cloud-library', 'settings_page' ),
				),
			),
		);

		wp_enqueue_script( $script_handle );
		wp_enqueue_style( $script_handle );
	}

	/**
	 * Get the admin page URL.
	 *
	 * @return string
	 */
	public static function get_admin_page_url() {
		return admin_url( 'themes.php?page=' . self::ADMIN_PAGE_SLUG );
	}
}
