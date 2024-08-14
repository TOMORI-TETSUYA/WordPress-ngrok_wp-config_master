<?php
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
 * * ABSPATH
 *
 * This has been slightly modified (to read environment variables) for use in Docker.
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// IMPORTANT: this file needs to stay in-sync with https://github.com/WordPress/WordPress/blob/master/wp-config-sample.php
// (it gets parsed by the upstream wizard in https://github.com/WordPress/WordPress/blob/f27cb65e1ef25d11b535695a660e7282b98eb742/wp-admin/setup-config.php#L356-L392)

// a helper function to lookup "env_FILE", "env", then fallback
if (!function_exists('getenv_docker')) {
	// https://github.com/docker-library/wordpress/issues/588 (WP-CLI will load this file 2x)
	function getenv_docker($env, $default) {
		if ($fileEnv = getenv($env . '_FILE')) {
			return rtrim(file_get_contents($fileEnv), "\r\n");
		}
		else if (($val = getenv($env)) !== false) {
			return $val;
		}
		else {
			return $default;
		}
	}
}

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', getenv_docker('WORDPRESS_DB_NAME', 'wordpress') );

/** Database username */
define( 'DB_USER', getenv_docker('WORDPRESS_DB_USER', 'example username') );

/** Database password */
define( 'DB_PASSWORD', getenv_docker('WORDPRESS_DB_PASSWORD', 'example password') );

/**
 * Docker image fallback values above are sourced from the official WordPress installation wizard:
 * https://github.com/WordPress/WordPress/blob/f9cc35ebad82753e9c86de322ea5c76a9001c7e2/wp-admin/setup-config.php#L216-L230
 * (However, using "example username" and "example password" in your database is strongly discouraged.  Please use strong, random credentials!)
 */

/** Database hostname */
define( 'DB_HOST', getenv_docker('WORDPRESS_DB_HOST', 'mysql') );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', getenv_docker('WORDPRESS_DB_CHARSET', 'utf8') );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', getenv_docker('WORDPRESS_DB_COLLATE', '') );

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
define( 'AUTH_KEY',         getenv_docker('WORDPRESS_AUTH_KEY',         '260ab4bbb7b70740d4b78301849b5c59a4a04f7c') );
define( 'SECURE_AUTH_KEY',  getenv_docker('WORDPRESS_SECURE_AUTH_KEY',  '5353f2c14055e43d9b718e59c4ef882841f00969') );
define( 'LOGGED_IN_KEY',    getenv_docker('WORDPRESS_LOGGED_IN_KEY',    '31691171303ad86be13db9f14d6814202e918cac') );
define( 'NONCE_KEY',        getenv_docker('WORDPRESS_NONCE_KEY',        'f09042c00881bdba333d9501efd454533d47dfe3') );
define( 'AUTH_SALT',        getenv_docker('WORDPRESS_AUTH_SALT',        '44d8a4e10d1e857530fdbd293872380ea566324d') );
define( 'SECURE_AUTH_SALT', getenv_docker('WORDPRESS_SECURE_AUTH_SALT', '55f0073aac3633113f98d0dc268ae33802cc66d5') );
define( 'LOGGED_IN_SALT',   getenv_docker('WORDPRESS_LOGGED_IN_SALT',   'ce5e8ef228fa358eea53411e4699da1932a675f7') );
define( 'NONCE_SALT',       getenv_docker('WORDPRESS_NONCE_SALT',       'aaf5962b6bd41ce628a15db225921b4ca885c9e7') );
// (See also https://wordpress.stackexchange.com/a/152905/199287)

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = getenv_docker('WORDPRESS_TABLE_PREFIX', 'wp_');

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
define( 'WP_DEBUG', !!getenv_docker('WORDPRESS_DEBUG', '') );

/* Add any custom values between this line and the "stop editing" line. */

// If we're behind a proxy server and using HTTPS, we need to alert WordPress of that fact
// see also https://wordpress.org/support/article/administration-over-ssl/#using-a-reverse-proxy
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strpos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false) {
	$_SERVER['HTTPS'] = 'on';
}
// (we include this by default because reverse proxying is extremely common in container environments)

if ($configExtra = getenv_docker('WORDPRESS_CONFIG_EXTRA', '')) {
	eval($configExtra);
}

/* That's all, stop editing! Happy publishing. */

/** SSL tuika */
if (empty($_SERVER['HTTPS'])) {
    $_SERVER['HTTPS'] = 'on'; $_ENV['HTTPS'] = 'on';
}
/** kokomade */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** tuika */
define('.COOKIE_DOMAIN.', 'kurara-nodake.post-house-system.com');
define('.SITECOOKIEPATH.', '.');

if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $list = explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']);
        $_SERVER['REMOTE_ADDR'] = $list[0];
  }
define( 'WP_HOME', 'http://kurara-nodake.post-house-system.com/kurara-nodake/' );
define( 'WP_SITEURL', 'http://kurara-nodake.post-house-system.com/kurara-nodake/' );
$_SERVER['HTTP_HOST'] = 'kurara-nodake.post-house-system.com';
$_SERVER['REMOTE_ADDR'] = 'http://kurara-nodake.post-house-system.com/kurara-nodake/';
$_SERVER[ 'SERVER_ADDR' ] = 'kurara-nodake.post-house-system.com';
if($_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') $_SERVER['HTTPS'] = 'on';
/** kokomade */

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
/** tuika */
define('WP_SITEURL', 'http://kurara-nodake.post-house-system.com/kurara-nodake/');
define('WP_HOME', 'http://kurara-nodake.post-house-system.com/kurara-nodake/');
define('WP_CONTENT_URL', 'http://kurara-nodake.post-house-system.com/kurara-nodake/wp-content');
$_SERVER['HTTP_HOST'] = $_SERVER['HTTP_X_FORWARDED_HOST'];
$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
/**
$_SERVER['HTTPS'] = 1;
*/
/** kokomade */
/** tuika */
if (isset($_SERVER['HTTP_X_FORWARDED_HOST']) && $_SERVER['HTTP_X_FORWARDED_HOST'] === 'kurara-nodake.post-house-system.com') {
    $_SERVER['HTTP_HOST'] = 'kurara-nodake.post-house-system.com';
    $_SERVER['SERVER_NAME'] = 'kurara-nodake.post-house-system.com';
}