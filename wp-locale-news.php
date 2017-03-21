<?php

/**
 * The plugin bootstrap file
 *
 * @link              http://chandrapatel.in
 * @since             1.0
 * @package           Wp_Locale_News
 *
 * @wordpress-plugin
 * Plugin Name:       WP Locale News
 * Plugin URI:
 * Description:       Display news from locale's Rosetta site on admin dashboard and in theme.
 * Version:           1.0
 * Author:            Chandra Patel
 * Author URI:        http://chandrapatel.in
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-locale-news
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'WPLN_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Load the plugin text domain for translation.
 *
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since    1.0
 */
function wpln_load_plugin_textdomain() {

	global $wp_version;

	$locale = get_locale();

	if ( $wp_version >= 4.7 ) {

		$locale = get_user_locale();

	}

	$mofile = sprintf( '%1$s-%2$s.mo', 'wp-locale-news', $locale );

	// Look in wp-content/languages/plugins/wp-locale-news.
	$global_mofile = WP_LANG_DIR . '/plugins/wp-locale-news/' . $mofile;

	if ( file_exists( $global_mofile ) ) {

		load_textdomain( 'wp-locale-news', $global_mofile );

	} else {

		load_plugin_textdomain( 'wp-locale-news', false, WPLN_PLUGIN_DIR . '/languages/' );

	}

}
add_action( 'plugins_loaded', 'wpln_load_plugin_textdomain' );

// Include required files.
require_once 'includes/class-wp-locale-news.php';
require_once 'includes/class-wpln-dashboard-widget.php';
require_once 'includes/class-wpln-admin.php';
require_once 'includes/class-wpln-widget.php';
