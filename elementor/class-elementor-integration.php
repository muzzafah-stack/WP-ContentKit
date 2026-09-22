<?php
/**
 * Elementor Integration Loader.
 * Lightweight & native element registration (ProElements style).
 *
 * @package WP_ContentKit
 */

namespace WP_ContentKit;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Elementor_Integration
 */
class Elementor_Integration {

	/**
	 * Singleton instance.
	 *
	 * @var Elementor_Integration|null
	 */
	private static $instance = null;

	/**
	 * Get singleton instance.
	 *
	 * @return Elementor_Integration
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '3.5.0', '>=' ) ) {
			add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
		} else {
			add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_widgets_legacy' ) );
		}
	}

	/**
	 * Register Elementor widget (Elementor >= 3.5.0).
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Widgets manager.
	 */
	public function register_widgets( $widgets_manager ) {
		require_once WP_CONTENTKIT_PATH . 'elementor/widgets/class-toc-widget.php';
		$widgets_manager->register( new Widgets\TOC_Widget() );
	}

	/**
	 * Register Elementor widget legacy (Elementor < 3.5.0).
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Widgets manager.
	 */
	public function register_widgets_legacy( $widgets_manager ) {
		require_once WP_CONTENTKIT_PATH . 'elementor/widgets/class-toc-widget.php';
		$widgets_manager->register_widget_type( new Widgets\TOC_Widget() );
	}
}
