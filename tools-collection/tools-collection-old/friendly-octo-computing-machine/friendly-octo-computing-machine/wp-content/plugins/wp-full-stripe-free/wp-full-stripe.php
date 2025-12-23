<?php
// leave plugin name as is; otherwise it will break the pot files
/*
Plugin Name: WP Full Pay
Plugin URI: https://paymentsplugin.com
Description: Use WP Full Pay to accept Stripe payments on your WordPress. Prebuilt forms to accept payments, donations and subscriptions. 
Author: Themeisle
Version: 8.3.2
Author URI: https://themeisle.com
Text Domain: wp-full-stripe-free
Domain Path: /languages
Requires License: yes
WordPress Available: yes
*/

//defines

//define( 'WP_FULL_STRIPE_DEMO_MODE', true );

define( 'WP_FULL_STRIPE_MIN_PHP_VERSION', '6.4.0' );
define( 'WP_FULL_STRIPE_MIN_WP_VERSION', '5.0.0' );
define( 'WP_FULL_STRIPE_STRIPE_API_VERSION', '7.24.0' );

define( 'WP_FULL_STRIPE_CRON_SCHEDULES_KEY_15_MIN', '15min' );

if ( ! defined( 'WP_FULL_STRIPE_NAME' ) ) {
	define( 'WP_FULL_STRIPE_NAME', trim( dirname( plugin_basename( __FILE__ ) ), '/' ) );
    define( 'WP_FULL_STRIPE_PRODUCT_SLUG', dirname( plugin_basename( __FILE__ ) ) );
}

if ( ! defined( 'WP_FULL_STRIPE_BASENAME' ) ) {
	define( 'WP_FULL_STRIPE_BASENAME', __FILE__ );
}

if ( ! defined( 'WP_FULL_STRIPE_DIR' ) ) {
	define( 'WP_FULL_STRIPE_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'WP_FULL_STRIPE_PATH' ) ) {
	define( 'WP_FULL_STRIPE_PATH', __DIR__ );
}


function wp_full_stripe_prepare_cron_schedules( $schedules ) {
    if ( ! isset( $schedules[ WP_FULL_STRIPE_CRON_SCHEDULES_KEY_15_MIN ] ) ) {
        $schedules[ WP_FULL_STRIPE_CRON_SCHEDULES_KEY_15_MIN ] = [
            'interval' => 15 * 60,
            'display'  =>
            /* translators: Textual description of how often a periodic task of the plugin runs */
                __( 'Every 15 minutes', 'wp-full-stripe-free' )
        ];
    }

    return $schedules;
}

function wpfsShowAdminNotice( $message ) {
    echo "<div class='notice notice-error'><p><b>WP Full Pay error</b>: {$message}</p></div>";
}

function wpfsIsPhpCompatible() {
    return version_compare( PHP_VERSION, WP_FULL_STRIPE_MIN_PHP_VERSION ) >= 0;
}

function wpfsIsWordpressCompatible() {
    return version_compare( get_bloginfo( 'version' ), WP_FULL_STRIPE_MIN_WP_VERSION ) >= 0;
}

function wpfsIsCurlAvailable() {
    return extension_loaded( 'curl' );
}

function wpfsIsMbStringAvailable() {
    return extension_loaded( 'mbstring' );
}

function wpfsShowAdminNotices() {
    if ( ! wpfsIsPhpCompatible() ) {
        wpfsShowAdminNotice( sprintf( __( 'PHP version required is %1$s but %2$s found.', 'wp-full-stripe-free' ), WP_FULL_STRIPE_MIN_PHP_VERSION, PHP_VERSION ));
    }
    if ( ! wpfsIsWordpressCompatible() ) {
        wpfsShowAdminNotice( sprintf( __( 'WordPress version required is %1$s but %2$s found.', 'wp-full-stripe-free' ), WP_FULL_STRIPE_MIN_WP_VERSION, get_bloginfo( 'version' )));
    }
    if ( ! wpfsIsCurlAvailable() ) {
        wpfsShowAdminNotice( sprintf( __( 'Required PHP extension called "%1$s" is missing.', 'wp-full-stripe-free' ), 'cURL' ));
    }
    if ( ! wpfsIsMbStringAvailable() ) {
        wpfsShowAdminNotice( sprintf( __( 'Required PHP extension called "%1$s" is missing.', 'wp-full-stripe-free' ), 'MBString' ));
    }
}

$wpfsDiagCheck = true;
$wpfsDiagCheck = $wpfsDiagCheck && wpfsIsPhpCompatible();
$wpfsDiagCheck = $wpfsDiagCheck && wpfsIsWordpressCompatible();
$wpfsDiagCheck = $wpfsDiagCheck && wpfsIsCurlAvailable();
$wpfsDiagCheck = $wpfsDiagCheck && wpfsIsMbStringAvailable();

if ( $wpfsDiagCheck ) {
    require_once( dirname( __FILE__ ) . '/vendor/autoload.php' );
    require_once( dirname( __FILE__ ) . '/includes/stripe/autoload.php' );

    add_filter(
        'themeisle_sdk_products',
        function ( $products ) {
            $products[] = __FILE__;
    
            return $products;
        }
    );

    add_filter(
        'themesle_sdk_namespace_' . md5( __FILE__ ),
        function () {
            return 'wpfs';
        }
    );


    if ( ! class_exists( 'WPFS_License' ) ) {
        include( dirname( __FILE__ ) . '/includes/wpfs-license.php' );
    }

    $namespace = WPFS_License::get_namespace();

    // We hide the license notice as it is not required for this plugin.
    add_filter( $namespace . '_hide_license_notices', '__return_true', 10, 1 );
    add_filter( $namespace . '_hide_license_field', '__return_true' );

    add_filter( $namespace . '_about_us_metadata', function ( $config ) {
        return [
            'location'         => 'wpfs-transactions',
            'logo'             => MM_WPFS_Assets::images( 'wpfs-logo.svg' ),
            'has_upgrade_menu' => ! WPFS_License::is_active(),
            'upgrade_link'     => tsdk_utmify( 'https://paymentsplugin.com/pricing/' ,'admin-menu'),
            'upgrade_text'     => __( 'Get Pro Version', 'wp-full-stripe-free' ),
        ];
    } );

    require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'wpfs-main.php';
    require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'includes'. DIRECTORY_SEPARATOR
        . 'payment-methods' . DIRECTORY_SEPARATOR . 'functions.php';

    register_activation_hook( __FILE__, [ 'MM_WPFS', 'setup_db' ] );
    register_activation_hook( __FILE__, ['MM_WPFS_CustomerPortalService', 'onActivation' ] );
    register_deactivation_hook( __FILE__, ['MM_WPFS_CustomerPortalService', 'onDeactivation' ] );
    register_activation_hook( __FILE__, [ 'MM_WPFS_CheckoutSubmissionService', 'onActivation' ] );
    register_deactivation_hook( __FILE__, [ 'MM_WPFS_CheckoutSubmissionService', 'onDeactivation' ] );

    \StripeWPFS\Stripe\Stripe::setAppInfo( 'WP Full Pay', MM_WPFS::VERSION, 'https://paymentsplugin.com', 'pp_partner_FnULHViL0IqHp6' );

    add_filter( 'cron_schedules', 'wp_full_stripe_prepare_cron_schedules' );
} else {
    add_action( 'admin_notices', 'wpfsShowAdminNotices' );
}
