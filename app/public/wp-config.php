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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'Fmf6NK50MekgDK7f0L6tlJWVD0S3Wi6vxcCjpRHZ577yeTsfSmzItZflxgQfcaW8WkVXXglIUPJcy08tRsr2qg==');
define('SECURE_AUTH_KEY',  'u2rzL2oHLqiSc4xRUsYidflBsUYQIu15f1nW5gp6Q1YnM1NniXvxSTfXUUVWav6R8OKSNQUqzrxls9YO6TLSDg==');
define('LOGGED_IN_KEY',    '+nXJpaZu6tSlJBVRvP1nY8HrwF6Ii/k5n0i9DoOYoZA1Iv0VHwucMf0qdRWMtdLzBnaAn8eJXNIbrEY0Ec838g==');
define('NONCE_KEY',        'djqWKoFYYmU+AMYKjwSpPjXr8wYBFPG6VK6/zCXKlEck0X9Hqs8d4YDa5G3vVWUBXLbSZaHog56/2Vu/Ei1zoQ==');
define('AUTH_SALT',        '/12gul8csFWCSnVrfl7iDDDwpU95VSUrb1O2LQykOgA9vcEDeWXQGmPaf08BSURK0HXz2V3hGzIc6IdDiOgR7w==');
define('SECURE_AUTH_SALT', 'W1HRTxlr6HZvay3JQLrHbXY77Dee8xWoiHmr+kzd/ptedLLZyWbfTU1i8X6FINhBUzF0GVerD8obQ2VxwuvSZQ==');
define('LOGGED_IN_SALT',   '0pBcQuqgp+/tJr7wrqH4FnjVUwVeh2SjKD8I5n/p3eyTWGnrbKutSxq7GYH4QNfX416JjvJDiHKIOmPa+eW5VQ==');
define('NONCE_SALT',       '7plxCDKbiZiAed0xfcNEEcjE/qqpMXTnk8rYgfy2JPog9S1FgunbCzmiDv8IGpNlTycdQhA2nOc5PYs6kVEKPg==');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
