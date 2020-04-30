<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'codeinjsDB' );

/** MySQL database username */
define( 'DB_USER', 'codeinjs' );

/** MySQL database password */
define( 'DB_PASSWORD', 'wpie1hc3' );

/** MySQL hostname */
define( 'DB_HOST', 'wordpress-codeinjs.ce8s3tjbnxib.us-east-1.rds.amazonaws.com' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

define( 'WP_REDIS_HOST','wordpress.ndj3gk.0001.use1.cache.amazonaws.com');
define( 'WP_CACHE',true);
/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'C.=xkOE*/#(/Cw(8|Z5>xL PQE@%TFu36<WvegO=;(I+u`>loZ-oX&qZl3whzgf~');
define('SECURE_AUTH_KEY',  'BDY?G+gK5:u<e`D#k%.f:fP+<2fcgW-39c*/wsUr.2/nQtnFYub~8t+fX=2|+-,[');
define('LOGGED_IN_KEY',    'w::,.I1Tw0j.CnHd5R 9*t+`MS9fCld/ZI3?]d}5lbrL4{z!cIWw!hEJKEv(>kHF');
define('NONCE_KEY',        'S~?rWXMjYe>( H5m3c/F?ETEEf8S<x{<:9J:vNG}Il|Tif?/$pIz+0mj5prV9)3V');
define('AUTH_SALT',        '[>:>7HT0A?KM|W #>|v0t^tJXp^9/EY6)QG<^{lxqhcF/#+6s>C/yzO:pe_9$y(>');
define('SECURE_AUTH_SALT', '+]E7h@!WXR:(M%_zXajS507 lw%)o;k6js@;f P&kCJD+A,.w+u11enYj INuPU!');
define('LOGGED_IN_SALT',   '&F;:7|PkdyZpxtBQ)+|[<S7-Au(e/a]p1NOkB3<pi)3x)!KR^5!|e*Q?T1J|,HI]');
define('NONCE_SALT',       'sGlsx;.%3!eb3lf!n!PZlhSr%P+ym~9@rzZ`f _I)!S,~kHkai0%QhGW(y}NJD8|');

define('WP_HOME','https://api.codeinjavascript.com');
define('WP_SITEURL','https://api.codeinjavascript.com');
/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
define( 'WP_DEBUG', true );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
