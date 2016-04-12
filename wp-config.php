<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'carhire');

/** MySQL database username */
define('DB_USER', 'noah');

/** MySQL database password */
define('DB_PASSWORD', 'sam');

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
define('AUTH_KEY',         'S&vgukBM aUnqKsSZ<y,?}8o%GA2yYeLV1$/|lvFWdr+NU^2~m:W9DP]2pJ8zjG4');
define('SECURE_AUTH_KEY',  'IJ,*+@li>9SQ{Nn@U</C.|pD^?M6QpqZ9W_atU$SB<8s6:TGIev,bJvcfJ|gw[C1');
define('LOGGED_IN_KEY',    ']X*`rM_:-i*>=-sCapoAv9r0{v<tJ}.oQ`cQ6mXx(<_S99QRxU&hz><PB:4(1DD*');
define('NONCE_KEY',        'o<3zeo)b63hr5+&up6RUptxR5<4,Axzr C<nY}3c&r!#n)2^ Gq>HLlTr!z1<grW');
define('AUTH_SALT',        '.2+9PySTqPlg[+c21(nd[#8QpH1<@S2@%Jj$* %>{,W~jBinOh(pG8KR8aAsb e,');
define('SECURE_AUTH_SALT', 'Rf~48Sf+p]# sDMiHK?dmm<7:K^JlNh*:zV{pp79#cX[-SVh`ziO1Fl,7_tXbOzs');
define('LOGGED_IN_SALT',   'EycNkZ]6pK7DR&c)qC2bu[6Bst~}<q$ja00P^wKtI,v^>KE7QU^br&6sdV]I;#H_');
define('NONCE_SALT',       'FbvH]qOX*$)sug.vADpgs:Q,x4sjIV!]R!]QXj6tz|s8tOr$cxBcjkEja#WrnNoK');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
