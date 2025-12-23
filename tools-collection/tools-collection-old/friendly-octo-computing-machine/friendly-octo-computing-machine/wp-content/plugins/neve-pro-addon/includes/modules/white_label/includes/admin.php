<?php
/**
 * White Label Admin Class
 *
 * @package Neve_Pro\Modules\White_Label\Includes
 */

namespace Neve_Pro\Modules\White_Label\Includes;

/**
 * Class Admin
 */
class Admin {
	/**
	 * Settings schema.
	 *
	 * @var array
	 */
	private static $settings_schema = null;

	/**
	 * Setting groups
	 *
	 * @var array
	 */
	private $setting_groups = [];

	/**
	 * Option Key.
	 *
	 * @var string
	 */
	private static $option_key = 'ti_white_label_inputs';

	/**
	 * Details of the product where the module is called.
	 *
	 * @var array $product_settings Product details.
	 */
	private $product_details;


	/**
	 * Ti_Withe_Label_Admin constructor.
	 *
	 * @param array $settings Product.
	 */
	public function __construct( $settings ) {
		$this->product_details = $settings;

		add_action( 'init', [ $this, 'define_settings_schema' ], 0 );
	}

	/**
	 * Define the settings schema.
	 */
	public function define_settings_schema() {
		$my_library_setting = [
			'label'         => __( 'Hide My Library', 'neve-pro-addon' ),
			'default_value' => false,
			'type'          => 'toggle',
			'dependent'     => 'starter_sites',
		];
		// We use this check to make the toggle not be dependent on the starter-sites if the changes from TPC are released.
		if ( defined( 'TIOB_VERSION' ) && version_compare( TIOB_VERSION, '1.1.38', '>' ) ) {
			unset( $my_library_setting['dependent'] );
		}

		$this->setting_groups = [
			'agency'  => [
				'title'  => __( 'Agency Branding', 'neve-pro-addon' ),
				'icon'   => 'compass.svg',
				'fields' => [
					'author_name'   => [
						'label'         => __( 'Agency Author', 'neve-pro-addon' ),
						'default_value' => '',
						'type'          => 'text',
					],
					'author_url'    => [
						'label'         => __( 'Agency Author URL', 'neve-pro-addon' ),
						'default_value' => '',
						'type'          => 'url',
					],
					'starter_sites' => [
						'label'         => __( 'Hide Sites Library', 'neve-pro-addon' ),
						'default_value' => false,
						'type'          => 'toggle',
					],
					'my_library'    => $my_library_setting,
				],
			],
			'theme'   => [
				'title'  => __( 'Theme Branding', 'neve-pro-addon' ),
				'icon'   => 'logo.svg',
				'fields' => [
					'theme_name'        => [
						'label'         => __( 'Theme Name', 'neve-pro-addon' ),
						'default_value' => '',
						'type'          => 'text',
					],
					'theme_description' => [
						'label'         => __( 'Theme Description', 'neve-pro-addon' ),
						'default_value' => '',
						'type'          => 'textarea',
					],
					'screenshot_url'    => [
						'label'         => __( 'Screenshot URL', 'neve-pro-addon' ),
						'default_value' => '',
						'type'          => 'url',
					],
				],
			],
			'plugin'  => [
				'title'  => __( 'Plugin Branding', 'neve-pro-addon' ),
				'icon'   => 'tachometer.svg',
				'fields' => [
					'plugin_name'        => [
						'label'         => __( 'Plugin Name', 'neve-pro-addon' ),
						'default_value' => '',
						'type'          => 'text',
					],
					'plugin_description' => [
						'label'         => __( 'Plugin Description', 'neve-pro-addon' ),
						'default_value' => '',
						'type'          => 'textarea',
					],
				],
			],
			'sidebar' => [
				'title'  => __( 'Enable White Label', 'neve-pro-addon' ),
				'fields' => [
					'white_label' => [
						'label'         => __( 'Hide Options from Dashboard', 'neve-pro-addon' ),
						'default_value' => false,
						'type'          => 'toggle',
						/* translators: Neve Pro */
						'description'   => sprintf( __( 'This will remove the white label module from the dashboard. If you want to access white label settings in future, simply deactivate the %s plugin and activate it again.', 'neve-pro-addon' ), $this->product_details['product_name'] ),
					],
					'license'     => [
						'label'         => __( 'Enable License Hiding', 'neve-pro-addon' ),
						'default_value' => false,
						'type'          => 'toggle',
						'description'   => __( 'This will remove the license field from the Dashboard page and all the admin notices related to it.', 'neve-pro-addon' ),
					],
				],
			],
		];

		foreach ( $this->setting_groups as $group_slug => $args ) {
			if ( ! isset( $args['fields'] ) ) { // @phpstan-ignore-line - we are sure that the fields are set. It's just a guard for future.
				continue;
			}
			foreach ( $args['fields'] as $key => $field_args ) {
				self::$settings_schema[ $key ] = $field_args['default_value'];
			}
		}
	}

	/**
	 * Hooks and filters.
	 * 
	 * @deprecated 4.0.0 - We're using init_v4 instead of this method as we merged WhiteLabel settings into the dashboard and they have to be handled differently.
	 * 
	 * @return void
	 */
	public function init() {
		add_action( 'admin_menu', [ $this, 'register_sub_menu' ] );
		add_action( 'init', [ $this, 'register_settings' ] );
	}

	/**
	 * Initialize dashboard data and register the settings.
	 *
	 * @return void
	 */
	public function init_v4() {
		add_action( 'init', [ $this, 'register_settings' ] );
		add_filter(
			'neve_dashboard_page_data',
			function( $data ) {
				if ( ! is_array( $data ) ) {
					return $data;
				}

				$data['whiteLabelData'] = [
					'options'   => $this->get_options(),
					'fields'    => $this->setting_groups,
					'optionKey' => self::$option_key,
				];

				return $data;
			} 
		);
	}

	/**
	 * Register settings.
	 */
	public function register_settings() {
		register_setting(
			'neve_white_label_settings',
			'ti_white_label_inputs',
			[
				'type'              => 'string',
				'show_in_rest'      => true,
				'default'           => wp_json_encode( self::$settings_schema ),
				'sanitize_callback' => function ( $value ) {
					$value = json_decode( $value, true );
					foreach ( $value as $key => $val ) {
						if ( ! array_key_exists( $key, self::$settings_schema ) ) {
							unset( $value[ $key ] );
						}
						$value[ $key ] = wp_kses_post( $val );
					}
					return wp_json_encode( $value );
				},
			]
		);
	}

	/**
	 * Create the page for White Label module.
	 */
	public function register_sub_menu() {
		add_submenu_page(
			'',
			__( 'White Label', 'neve-pro-addon' ),
			__( 'White Label', 'neve-pro-addon' ),
			'manage_options',
			'ti-white-label',
			[ $this, 'render' ]
		);
	}

	/**
	 * Render White Label module.
	 */
	public function render() {
		$this->enqueue();
		?>
		<div class="white-label-wrap">
			<div id="white-label-root"></div>
		</div>
		<?php
	}

	/**
	 * Enqueue script and styles.
	 */
	public function enqueue() {
		$dependencies = include trailingslashit( dirname( dirname( __FILE__ ) ) ) . 'build/index.asset.php';

		wp_register_script( 'neve-white-label', NEVE_PRO_URL . 'includes/modules/white_label/build/index.js', $dependencies['dependencies'], $dependencies['version'], true );
		wp_localize_script( 'neve-white-label', 'neveWhiteLabel', $this->get_localization() );
		wp_enqueue_script( 'neve-white-label' );

		wp_register_style( 'neve-white-label', NEVE_PRO_URL . 'includes/modules/white_label/build/style-index.css', [ 'wp-components' ], $dependencies['version'] );
		wp_style_add_data( 'neve-white-label', 'rtl', 'replace' );
		wp_enqueue_style( 'neve-white-label' );
		
		wp_set_script_translations( 'neve-white-label', 'neve-pro-addon' );
	}

	/**
	 * Localize the sites library.
	 *
	 * @return array
	 */
	private function get_localization() {
		return [
			'assetsURL' => esc_url( NEVE_PRO_URL . 'includes/modules/white_label/assets/' ),
			'nonce'     => wp_create_nonce( 'wp_rest' ),
			'options'   => $this->get_options(),
			'fields'    => $this->setting_groups,
			'optionKey' => self::$option_key,
		];
	}

	/**
	 * Get options.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	static function get_options() {
		$value = get_option( self::$option_key );
		$value = json_decode( $value, true );

		if ( ! is_array( $value ) || empty( $value ) ) {
			return self::$settings_schema;
		}
		return wp_parse_args( $value, self::$settings_schema );
	}
}
