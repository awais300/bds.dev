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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'bds');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'mysql');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'N9 ut$3oGHp6fq]6=p6,um&&6V3Fvww<C:6#lV]/`JK{&p$(o!TZ;|hQDjfo%qg{');
define('SECURE_AUTH_KEY',  ']AD|90isf D>{^JL-PvS22wxmN3Ub;FL=U$T:h[.MY3yT23S)Xk,+.8?vKzqn0Cn');
define('LOGGED_IN_KEY',    'Z!hlGr_=4^Q^CWtT)h=6}-+^!#?/IlM33m/eukh-ZzX*GyA9(bk?.seN.pG<Yn>t');
define('NONCE_KEY',        '}}u}E~.|jq2YGUQ_>X^=WF/QB;3K&N#mTvE&KGedl)Gbk-F<=9iteiD|m/VgTk^B');
define('AUTH_SALT',        '.m%KA]7ZGXhhR(z1PMO!D$:,5wZ:2`S(zw,@/t:T^ww,p^V}T3*(ZLlaMoo>[`JV');
define('SECURE_AUTH_SALT', ' a0i*siQh=h..^40`gu@Ha.j$$!?[Tr-0An?&LA1Z(|4RuN6-W?lKRTo@Cj:sE#g');
define('LOGGED_IN_SALT',   'T.u0UZNLPBV4.Om;UYEsQqE1AtWZ5hMRWdfjJ4Z3O6x|cUvp4K~/!%nKs?g?*Xb ');
define('NONCE_SALT',       '?=(~<h9MkuiN,!6e !y0TV#hx-9uDJQ,d5BH;Lp0&t_h<lR~1&E<Z4yM3oKj|Y*_');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
