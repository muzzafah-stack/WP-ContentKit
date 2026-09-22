<?php
/**
 * Internationalization functionality.
 *
 * @package WP_ContentKit
 */

namespace WP_ContentKit;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class I18n
 */
class I18n {

	/**
	 * Load plugin textdomain.
	 */
	public static function load_textdomain() {
		load_plugin_textdomain(
			'wp-contentkit',
			false,
			dirname( WP_CONTENTKIT_BASENAME ) . '/languages/'
		);
	}
}
