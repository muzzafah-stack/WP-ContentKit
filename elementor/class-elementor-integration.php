<?php
/**
 * Elementor Integration Loader.
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
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
		add_action( 'elementor/editor/after_enqueue_scripts', array( $this, 'enqueue_editor_assets' ) );
	}

	/**
	 * Register WP ContentKit category in Elementor.
	 *
	 * @param \Elementor\Elements_Manager $elements_manager Elements manager.
	 */
	public function register_widget_category( $elements_manager ) {
		$elements_manager->add_category(
			'wp-contentkit',
			array(
				'title' => __( 'WP ContentKit', 'wp-contentkit' ),
				'icon'  => 'fa fa-plug',
			)
		);
	}

	/**
	 * Register Elementor widgets.
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Widgets manager.
	 */
	public function register_widgets( $widgets_manager ) {
		require_once WP_CONTENTKIT_PATH . 'elementor/widgets/class-toc-widget.php';
		$widgets_manager->register( new Widgets\TOC_Widget() );
	}

	/**
	 * Enqueue editor-specific assets for live validation feedback.
	 */
	public function enqueue_editor_assets() {
		wp_enqueue_script(
			'wpck-elementor-validator',
			WP_CONTENTKIT_URL . 'assets/js/elementor-editor-validator.js',
			array( 'jquery' ),
			WP_CONTENTKIT_VERSION,
			true
		);
	}
}
