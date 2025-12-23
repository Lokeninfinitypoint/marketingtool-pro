<?php
/**
 * Author:          ThemeIsle <friends@themeisle.com>
 *
 * @package Neve Pro
 */

namespace Neve_Pro\Modules\White_Label;

use Neve_Pro\Core\Abstract_Module;
use Neve_Pro\Modules\White_Label\Includes\Admin;
use Neve_Pro\Modules\White_Label\Includes\Markup;

/**
 * Class Module  - main class for the module
 * Enqueue scripts, style
 * Render functions
 *
 * @package Neve_Pro\Modules\Blog_Pro
 */
class Module extends Abstract_Module {
	/**
	 * Instance of Ti_Withe_Label_Admin
	 *
	 * @var Admin
	 */
	protected $admin = null;

	/**
	 * Product base file, with the proper metadata.
	 *
	 * @var string $base_file The file with headers.
	 */
	protected $base_file;

	/**
	 * File path to enqueue files.
	 *
	 * @var string $file The file name.
	 */
	private $file_path;

	/**
	 * Product name, fetched from the file headers.
	 *
	 * @var string $name The product name.
	 */
	private $product_name;

	/**
	 * Product store url.
	 *
	 * @var string $store_url The store url.
	 */
	private $store_url; // @phpstan-ignore-line Note: This is a private property, so it's not used anywhere else.

	/**
	 * Product store/author name.
	 *
	 * @var string $store_name The store name.
	 */
	private $store_name; // @phpstan-ignore-line Note: This is a private property, so it's not used anywhere else.

	/**
	 * Product author url.
	 *
	 * @var string $author_url Author url,
	 */
	private $author_url; // @phpstan-ignore-line Note: This is a private property, so it's not used anywhere else.

	/**
	 * Define module properties.
	 *
	 * @access  public
	 * @return void
	 *
	 * @version 1.0.0
	 */
	public function define_module_properties() {
		$this->slug            = 'white_label';
		$this->min_req_license = 3;
		$this->order           = 6;
		$this->base_file       = NEVE_PRO_BASEFILE;
	}

	/**
	 * Setup module labels.
	 */
	public function setup_labels() {
		$this->name = __( 'White Label', 'neve-pro-addon' );
		/* translators: %s: Neve brand name */
		$this->description   = sprintf( __( 'Rebrand Neve as your own. Change theme name, author, and links to match your agency identity.', 'neve-pro-addon' ), 'Neve' );
		$this->links         = array(
			array(
				'url'   => admin_url( '?page=ti-white-label' ),
				'label' => __( 'Settings', 'neve-pro-addon' ),
			),
		);
		$this->documentation = array(
			'url'   => 'https://docs.themeisle.com/article/1061-white-label-module-documentation',
			'label' => __( 'Learn more', 'neve-pro-addon' ),
		);
	}

	/**
	 * Check if module should load.
	 *
	 * @return bool
	 */
	public function should_load() {
		$settings = $this->init_props_get_config();

		$this->admin = new Admin( $settings );
		$this->admin->init_v4();

		return $this->is_active();
	}

	/**
	 * Run Blog Pro Module
	 */
	public function run_module() {
		$settings = $this->init_props_get_config();

		$this->admin = new Admin( $settings );
		$this->admin->init();

		new Markup( $settings );
	}

	/**
	 * Initialize properties and get the for other module classes.
	 * 
	 * @return array
	 */
	private function init_props_get_config() {
		$this->file_path = plugin_dir_url( $this->base_file );

		$file_headers = get_file_data(
			$this->base_file,
			[
				'Name'       => 'Plugin Name',
				'AuthorName' => 'Author',
				'AuthorURI'  => 'Author URI',
			]
		);

		$this->product_name = $file_headers['Name'];
		$this->store_name   = $file_headers['AuthorName'];
		$this->author_url   = $file_headers['AuthorURI'];
		$this->store_url    = $file_headers['AuthorURI'];

		$settings = [
			'product_name'     => $this->product_name,
			'file_path'        => $this->file_path,
			'plugin_base_name' => plugin_basename( $this->base_file ),
		];

		return $settings;
	}
}
