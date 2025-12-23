<?php
define( 'WP_CACHE', true );

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u520004865_ASztr' );

/** Database username */
define( 'DB_USER', 'u520004865_tF68o' );

/** Database password */
define( 'DB_PASSWORD', 'r8fdIcyd8Y' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'V05XV>Bh1GAXIS!X <:.b@ve4<MeMVpucu;xo#?o_j([F1a^^l-9rwjp)?gXQuz<' );
define( 'SECURE_AUTH_KEY',   '1o_Qr1.7#{Tm,;5c_6p,]#tcCv6 `~;jk,aDp%P}yIifIWsFio!Q(D3+#K %~VEC' );
define( 'LOGGED_IN_KEY',     'ax|P04KM}er7Re<;{t8SgBQ9doh2=fX](NDqQ je(H}e,07H)DYE]9Oc}=ES6TH`' );
define( 'NONCE_KEY',         'RSR[Pq:1weCK_;n2(2#ZFZI-mE5|2`dX2|iXU}RWC=3[X#-*~jpPXOm1|zjbfEL|' );
define( 'AUTH_SALT',         'fn_mDc;<y.FUd:)f~YYyMjz|)N*/4*k+KQQ3W{iwPHv0&~i%]6A[y>(,SNrvK{8h' );
define( 'SECURE_AUTH_SALT',  ']bb^rVwmx8!`8r%]o`zEzo<ih!&se5pN?YPn)kr5Rc&!})>sbDY)j8p|RZVbmjg,' );
define( 'LOGGED_IN_SALT',    'X`r.*^oRzRh|1)C^XG{^ilT{$y0]N1~ orR7EXx3%O]U^nxBd8=:8Tgd4gm 3*bv' );
define( 'NONCE_SALT',        ',BqjEuI,?_.C:ZDC=xwCaL2xjTe?tPXZ]8;m/s<izsrV^:*6q]{Qz$%{1ekDdq2(' );
define( 'WP_CACHE_KEY_SALT', 'jU2ogpWp9/=i0]~e6hpfWL@%~4`*U,7h(rP1M-Z>Xs(M &3#D#/@C15uZwD,1HJm' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'FS_METHOD', 'direct' );
define( 'COOKIEHASH', '73da9092936fd56a16a7c9004d1d4d16' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
