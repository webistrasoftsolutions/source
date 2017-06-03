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
define('DB_NAME', 'sastawebistrabazar');

/** MySQL database username */
define('DB_USER', 'sastawebistrabaz');

/** MySQL database password */
define('DB_PASSWORD', 'Sumit1@Hacker');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         '!G__]*nKI6|R/BcinM:i[.Ox!QJqG|>G*5mF3e2qz<z1$$jZf2&Y}Wd[<.7crSdR');
define('SECURE_AUTH_KEY',  'j@}3%z1wOX(l^1%b7DWUr?(n%Me0R91D:_W;MM8*7^{?/7=0;8]JlP9$U8+e*5et');
define('LOGGED_IN_KEY',    'hCGkQXkGQG2dB7_wvty(@,Y;jl:x,j2#u7v$}b5a&#2V!^B3[Jy}0twLhGC}*DCs');
define('NONCE_KEY',        'y@vFl(!3DLU3NFz.}}$2Fm1Ru1RG]gv1SJ?h`yR^%]-R;`{RyB#M=y~>n$1`w2Pn');
define('AUTH_SALT',        '-u-ex=GoZZDDNnP/lC;.g^G/*ore#]J<OQhZt(r1Kp~Bx%F@T;hDVE:xE-g{nhCD');
define('SECURE_AUTH_SALT', '?zw}<fDkKP3Gya]7:TWA_rn7{qQLeb&8$eH07yy(()$^(Hu}*%_}ETNsIqVf<JR_');
define('LOGGED_IN_SALT',   'CwXLX| !5p*lA6e+dR%[D(`S$B/y$D^]}/SO25L&EkLP|-F@!wn=}gpOjXh*9zca');
define('NONCE_SALT',       'U1s!|qR$47Dq`M2,p$>+7W;3URI.OPUT4ZKnv)a,cPr[ZIi@0dhgOsuHP{Zf1]^D');

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
