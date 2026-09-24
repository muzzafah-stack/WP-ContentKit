<?php
/**
 * Elementor Integration Loader.
 * Compatible with Elementor 4.3.1, 3.5.0+, and legacy Elementor.
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
		add_action( 'elementor/elements/categories_registered', array( $this, 'register_widget_category' ) );

		if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '3.5.0', '>=' ) ) {
			add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
		} else {
			add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_widgets_legacy' ) );
		}
	}

	/**
	 * Register custom Elementor category for WP ContentKit.
	 *
	 * @param \Elementor\Elements_Manager $elements_manager Elements manager.
	 */
	public function register_widget_category( $elements_manager ) {
		$elements_manager->add_category(
			'wpck-category',
			array(
				'title' => esc_html__( 'WP ContentKit', 'wp-contentkit' ),
				'icon'  => 'fa fa-plug',
			)
		);
	}

	/**
	 * Register Elementor widget (Elementor >= 3.5.0 and Elementor 4.x).
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
