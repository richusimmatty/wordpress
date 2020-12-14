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
define('DB_NAME', 'sql2382103');

/** MySQL database username */
define('DB_USER', 'sql2382103');

/** MySQL database password */
define('DB_PASSWORD', 'tQ1!wQ7!');

/** MySQL hostname */
define('DB_HOST', 'sql2.freesqldatabase.com');

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
define('AUTH_KEY',         'u:6V|_KL?y1*}%j02-8}J<_w;t8H>5#~#63k~`?U^{xu{<Bi]iGaQ1#,/dZ)+~a4');
define('SECURE_AUTH_KEY',  '?9n=S.n*L[%R2;]L<|`BIM3RG(;2tZu([HL,J(5/=Hc[WH{^8^[2G?w^g$-W9w{W');
define('LOGGED_IN_KEY',    'cv2TNCOt)xvp&6b IO`h^Ua^b!%D@MW&K)?Ui:nM1ciGJu;skJtF=odCO;@EwAH1');
define('NONCE_KEY',        '^wTU;?ByXx(4@mF9c0};I$/@}c28GHjW%xP|.=XUc*^uWK$eR.(msfH(A61UBmgg');
define('AUTH_SALT',        '`/7ZEBs#Vk(%&JiC0bAk>2(CHKD8#3q%MhaSrc738h|r<~hCNIw)]/$ACMz3db(e');
define('SECURE_AUTH_SALT', 'q*+9]3ECkGYoT[lWpPD_n41-,tm`mh[{[ -a*wm& +?`3JgVE3Da{+n{o`3Rl_lP');
define('LOGGED_IN_SALT',   'rzE9gB qWgjG0)!57FNRMYoD7qpoRzm_kMbde$Glu7;3,<U=aWxX 8}##F BwIBm');
define('NONCE_SALT',       'p{:$*~OEodUGIe,Q(L*0*?jQNg;EBekioDr9H?O^k<$^&qXOJe/zfCF5AwzN_#r5');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_rh_';

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
define( 'FS_METHOD' , 'direct' );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

