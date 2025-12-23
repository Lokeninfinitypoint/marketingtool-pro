<?php

namespace TI\Template_Cloud;

use TI\Template_Cloud\Modules\Admin;
use TI\Template_Cloud\Modules\Cache_Manager;
use TI\Template_Cloud\Modules\Module_Interface;
use TI\Template_Cloud\Modules\Server;

class Main {

	public const PRODUCT_KEY = 'template_cloud_server';

	/**
	 * Modules.
	 *
	 * @var array $modules List of modules.
	 */
	private $modules;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->modules = array(
			'admin'         => new Admin(),
			'server'        => new Server(),
			'cache_manager' => new Cache_Manager(),
		);
	}

	/**
	 * Initialize.
	 *
	 * @return void
	 */
	public function init() {
		add_filter( 'themeisle_sdk_products', [ $this, 'load_sdk' ] );

		$this->init_modules();
		$this->adapt_sdk();
	}

	/**
	 * Adapt SDK.
	 *
	 * @return void
	 */
	private function adapt_sdk() {
		add_filter( self::PRODUCT_KEY . '_hide_license_field', '__return_true' );
		add_filter(
			self::PRODUCT_KEY . '_lc_no_valid_string',
			function ( $message ) {
				return str_replace( '<a href="%s">', '<a href="' . Admin::get_admin_page_url() . '">', $message );
			}
		);

		add_filter(
			self::PRODUCT_KEY . '_hide_license_notices',
			function () {
				$current_screen = get_current_screen();

				if ( ! isset( $current_screen ) || $current_screen->id !== 'appearance_page_' . Admin::ADMIN_PAGE_SLUG ) {
					return false;
				}

				return true;
			}
		);
		add_filter( 'template-cloud-server_sdk_enable_private_translations', '__return_true' );
	}

	/**
	 * Initialize modules.
	 *
	 * @return void
	 */
	public function init_modules() {
		foreach ( $this->modules as $module ) {
			if ( $module instanceof Module_Interface ) {
				$module->init();
			}
		}
	}

	/**
	 * Load SDK.
	 *
	 * @param array $products List of products.
	 *
	 * @return array
	 */
	public function load_sdk( $products ) {
		$products[] = TI_TC_BASE_FILE;

		return $products;
	}
}
