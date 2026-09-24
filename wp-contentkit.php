<?php
/**
 * Plugin Name:       WP ContentKit
 * Plugin URI:        https://github.com/muzzafah-stack
 * Description:       Smart Tools for Better Content. Lightweight Server-Side TOC for Elementor and portable Inline Content Box generator for Classic Editor.
 * Version:           1.1.0
 * Requires at least: 5.8
 * Tested up to:      7.1.2
 * Requires PHP:      7.4
 * Author:            Hipnolink Team Digital
 * Author URI:        https://www.hipnolink.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wp-contentkit
 * Domain Path:       /languages
 *
 * @package WP_ContentKit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Plugin Constants.
define( 'WP_CONTENTKIT_VERSION', '1.1.0' );
define( 'WP_CONTENTKIT_FILE', __FILE__ );
define( 'WP_CONTENTKIT_PATH', plugin_dir_path( __FILE__ ) );
define( 'WP_CONTENTKIT_URL', plugin_dir_url( __FILE__ ) );
define( 'WP_CONTENTKIT_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Autoload core classes.
 */
spl_autoload_register( function ( $class ) {
	$prefix   = 'WP_ContentKit\\';
	$base_dir = WP_CONTENTKIT_PATH . 'includes/';

	$len = strlen( $prefix );
	if ( strncmp( $prefix, $class, $len ) !== 0 ) {
		return;
	}

	$relative_class = substr( $class, $len );
	$file           = $base_dir . 'class-' . strtolower( str_replace( '_', '-', $relative_class ) ) . '.php';

	if ( file_exists( $file ) ) {
		require_once $file;
	}
} );

// Require main orchestrator class.
require_once WP_CONTENTKIT_PATH . 'includes/class-plugin.php';

/**
 * Initialize WP ContentKit Plugin.
 *
 * @return WP_ContentKit\Plugin
 */
function wp_contentkit() {
	return WP_ContentKit\Plugin::get_instance();
}

// Kickoff plugin execution.
add_action( 'plugins_loaded', 'wp_contentkit' );
