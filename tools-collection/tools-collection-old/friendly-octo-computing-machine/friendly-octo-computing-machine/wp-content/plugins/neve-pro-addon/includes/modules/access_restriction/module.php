<?php
/**
 * Access Restriction Module Main Class
 *
 * @package Neve_Pro\Modules\Access_Restriction
 */
namespace Neve_Pro\Modules\Access_Restriction;

use Neve_Pro\Core\Abstract_Module;
use Neve_Pro\Core\Loader;
use Neve_Pro\Modules\Access_Restriction\Admin\Dashboard\Manager as Admin_Dashboard;
use Neve_Pro\Modules\Access_Restriction\Admin\Content_Restriction\Edit_Post as Admin_Post_Edit;
use Neve_Pro\Modules\Access_Restriction\Admin\Content_Restriction\Edit_Term as Admin_Term_Edit;
use Neve_Pro\Modules\Access_Restriction\Content_Restriction\Facade as Content_Restriction_Facade;
use Neve_Pro\Modules\Access_Restriction\General_Settings\Storage_Adapter;
use Neve_Pro\Modules\Access_Restriction\Router\Restriction_Behavior\Redirect_Custom_Login;

/**
 * Main class which initializes the module
 */
class Module extends Abstract_Module {
	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		Redirect_Custom_Login::register_hook_for_custom_login_redirect(); // That's here because should be run earlier.
		( new Admin_Dashboard() )->init(); // We enqueue scripts here to allow the toggle to work properly, if it is already disabled.
	}

	/**
	 * Run Module
	 *
	 * @return void
	 */
	public function run_module() {
		add_action( 'init', [ $this, 'register_hooks' ], 1 );
	}

	/**
	 * Init Module
	 *
	 * Run on 'init' hook because Custom Post Types are not registered before init.
	 * (Custom Post Types are needed to prepare module options)
	 *
	 * @return void
	 */
	public function register_hooks() {
		( new Content_Restriction_Facade() )->run(); // restricts content on front side
		( new Admin_Post_Edit() )->init();
		( new Admin_Term_Edit() )->init();
	}

	/**
	 * Define Module Properties
	 *
	 * @return void
	 */
	public function define_module_properties() {
		$this->slug              = 'access_restriction';
		$this->order             = 11;
		$this->has_dynamic_style = false;
		$this->force_enabled     = true;
	}
	
	/**
	 * Setup module labels.
	 */
	public function setup_labels() {
		$this->name          = __( 'Content restriction', 'neve-pro-addon' );
		$this->description   = __( 'Create members-only content areas. Control access by user roles, logged-in status, or custom rules.', 'neve-pro-addon' );
		$this->documentation = array(
			'url'   => 'https://docs.themeisle.com/article/1863-content-restriction-module-documentation',
			'label' => __( 'Learn more', 'neve-pro-addon' ),
		);

		$options[ Storage_Adapter::WP_OPTION_NAME ] = array(
			'type'              => 'react',
			'default'           => 0,
			'show_in_rest'      => true,
			'sanitize_callback' => 'absint',
		);

		$this->options = array(
			array(
				'label'   => __( 'Enable / disable content restriction for content types', 'neve-pro-addon' ),
				'options' => $options,
			),
		);
	}
}
