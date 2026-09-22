<?php
/**
 * Main Plugin Orchestrator Singleton.
 *
 * @package WP_ContentKit
 */

namespace WP_ContentKit;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Plugin
 */
class Plugin {

	/**
	 * Singleton instance.
	 *
	 * @var Plugin|null
	 */
	private static $instance = null;

	/**
	 * Settings instance.
	 *
	 * @var Admin_Settings
	 */
	public $admin_settings;

	/**
	 * Classic Editor instance.
	 *
	 * @var Classic_Editor
	 */
	public $classic_editor;

	/**
	 * Cached headings for current request.
	 *
	 * @var array
	 */
	private $current_post_headings = array();

	/**
	 * Get singleton instance.
	 *
	 * @return Plugin
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
		$this->init_hooks();
	}

	/**
	 * Initialize plugin hooks and subsystems.
	 */
	private function init_hooks() {
		// Internationalization.
		add_action( 'init', array( 'WP_ContentKit\\I18n', 'load_textdomain' ) );

		// Subsystems.
		if ( is_admin() ) {
			$this->admin_settings = new Admin_Settings();
			$this->classic_editor = new Classic_Editor();

			// GitHub Auto-Updater.
			new GitHub_Updater( WP_CONTENTKIT_FILE, WP_CONTENTKIT_VERSION );
		}

		// Content Filter for Anchor Injection.
		add_filter( 'the_content', array( $this, 'filter_content_anchors' ), 100 );

		// Frontend scripts and styles.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );

		// Elementor Integration check.
		add_action( 'plugins_loaded', array( $this, 'init_elementor_integration' ), 20 );
	}

	/**
	 * Initialize Elementor Add-on integration if Elementor is active.
	 */
	public function init_elementor_integration() {
		if ( did_action( 'elementor/loaded' ) || defined( 'ELEMENTOR_VERSION' ) ) {
			require_once WP_CONTENTKIT_PATH . 'elementor/class-elementor-integration.php';
			Elementor_Integration::get_instance();
		}
	}

	/**
	 * Injects unique IDs into content headings so TOC links always land accurately.
	 *
	 * @param string $content Post HTML content.
	 * @return string Modified content with anchor IDs.
	 */
	public function filter_content_anchors( $content ) {
		// Only run on singular post/pages or in main query loop to prevent overhead in widgets/excerpts.
		if ( is_feed() || empty( $content ) ) {
			return $content;
		}

		$options = Admin_Settings::get_options();
		if ( empty( $options['enable_toc'] ) || '1' !== $options['enable_toc'] ) {
			return $content;
		}

		$parser = new TOC_Parser( array(
			'allowed_levels' => $options['default_heading_levels'],
			'min_headings'   => $options['min_headings_count'],
			'id_prefix'      => $options['toc_id_prefix'],
		) );

		$headings = array();
		$modified_content = $parser->inject_anchors( $content, $headings );

		$this->current_post_headings = $headings;

		return $modified_content;
	}

	/**
	 * Get parsed headings for post or content string.
	 *
	 * @param string $content Optional content string. If omitted, uses current post.
	 * @param array  $custom_config Custom parser configuration.
	 * @return array Parsed headings list.
	 */
	public function get_post_headings( $content = '', $custom_config = array() ) {
		if ( empty( $content ) ) {
			global $post;
			if ( $post && ! empty( $post->post_content ) ) {
				$content = $post->post_content;
			}
		}

		$options = Admin_Settings::get_options();
		$config  = wp_parse_args( $custom_config, array(
			'allowed_levels' => $options['default_heading_levels'],
			'min_headings'   => $options['min_headings_count'],
			'id_prefix'      => $options['toc_id_prefix'],
		) );

		$parser = new TOC_Parser( $config );
		return $parser->extract_headings( $content );
	}

	/**
	 * Enqueue frontend CSS and Vanilla JS.
	 */
	public function enqueue_frontend_assets() {
		$options = Admin_Settings::get_options();

		// Always register styles.
		wp_register_style(
			'wpck-toc-frontend',
			WP_CONTENTKIT_URL . 'assets/css/toc-frontend.css',
			array(),
			WP_CONTENTKIT_VERSION
		);

		wp_register_script(
			'wpck-toc-frontend',
			WP_CONTENTKIT_URL . 'assets/js/toc-frontend.js',
			array(),
			WP_CONTENTKIT_VERSION,
			true
		);

		// Conditional loading check.
		$is_conditional = ! empty( $options['load_assets_conditional'] ) && '1' === $options['load_assets_conditional'];

		if ( ! $is_conditional || is_singular() ) {
			wp_enqueue_style( 'wpck-toc-frontend' );
			wp_enqueue_script( 'wpck-toc-frontend' );

			wp_localize_script(
				'wpck-toc-frontend',
				'wpckTocConfig',
				array(
					'smoothScroll' => ! empty( $options['toc_smooth_scroll'] ) && '1' === $options['toc_smooth_scroll'],
					'scrollOffset' => (int) $options['toc_scroll_offset'],
					'collapsible'  => ! empty( $options['toc_collapsible'] ) && '1' === $options['toc_collapsible'],
				)
			);
		}
	}
}
