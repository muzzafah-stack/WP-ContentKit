<?php
/**
 * Admin Settings Page and Management.
 *
 * @package WP_ContentKit
 */

namespace WP_ContentKit;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Admin_Settings
 */
class Admin_Settings {

	/**
	 * Option name.
	 */
	const OPTION_KEY = 'wp_contentkit_settings';

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'register_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
	}

	/**
	 * Register top-level admin menu.
	 */
	public function register_admin_menu() {
		add_menu_page(
			__( 'WP ContentKit Settings', 'wp-contentkit' ),
			__( 'WP ContentKit', 'wp-contentkit' ),
			'manage_options',
			'wp-contentkit',
			array( $this, 'render_settings_page' ),
			'dashicons-editor-kitchensink',
			85
		);
	}

	/**
	 * Get default plugin options.
	 *
	 * @return array Default settings.
	 */
	public static function get_default_options() {
		return array(
			'enable_toc'              => '1',
			'enable_content_box'      => '1',
			'default_heading_levels'  => array( 'h2', 'h3', 'h4' ),
			'min_headings_count'      => 2,
			'toc_default_title'       => 'Daftar Isi',
			'toc_numbering'           => 'decimal', // none, decimal, nested
			'toc_collapsible'         => '1',
			'toc_default_state'       => 'expanded', // expanded, collapsed
			'toc_smooth_scroll'       => '1',
			'toc_scroll_offset'       => 80,
			'toc_id_prefix'           => '',
			'load_assets_conditional' => '1',
		);
	}

	/**
	 * Get saved settings merged with defaults.
	 *
	 * @return array Active settings.
	 */
	public static function get_options() {
		$saved    = get_option( self::OPTION_KEY, array() );
		$defaults = self::get_default_options();

		return wp_parse_args( (array) $saved, $defaults );
	}

	/**
	 * Register settings in WordPress.
	 */
	public function register_settings() {
		register_setting(
			'wp_contentkit_settings_group',
			self::OPTION_KEY,
			array( $this, 'sanitize_settings' )
		);
	}

	/**
	 * Sanitize submitted settings.
	 *
	 * @param array $input Raw input.
	 * @return array Sanitized array.
	 */
	public function sanitize_settings( $input ) {
		$output = self::get_default_options();

		$output['enable_toc']         = ! empty( $input['enable_toc'] ) ? '1' : '0';
		$output['enable_content_box'] = ! empty( $input['enable_content_box'] ) ? '1' : '0';

		if ( isset( $input['default_heading_levels'] ) && is_array( $input['default_heading_levels'] ) ) {
			$allowed = array( 'h2', 'h3', 'h4', 'h5', 'h6' );
			$output['default_heading_levels'] = array_values( array_intersect( $allowed, $input['default_heading_levels'] ) );
		} else {
			$output['default_heading_levels'] = array( 'h2', 'h3', 'h4' );
		}

		$output['min_headings_count'] = isset( $input['min_headings_count'] ) ? max( 1, (int) $input['min_headings_count'] ) : 2;
		$output['toc_default_title']  = ! empty( $input['toc_default_title'] ) ? sanitize_text_field( $input['toc_default_title'] ) : 'Daftar Isi';
		
		$allowed_numbering            = array( 'none', 'decimal', 'nested' );
		$output['toc_numbering']      = ( isset( $input['toc_numbering'] ) && in_array( $input['toc_numbering'], $allowed_numbering, true ) ) ? $input['toc_numbering'] : 'decimal';

		$output['toc_collapsible']    = ! empty( $input['toc_collapsible'] ) ? '1' : '0';
		
		$allowed_states               = array( 'expanded', 'collapsed' );
		$output['toc_default_state']  = ( isset( $input['toc_default_state'] ) && in_array( $input['toc_default_state'], $allowed_states, true ) ) ? $input['toc_default_state'] : 'expanded';

		$output['toc_smooth_scroll']  = ! empty( $input['toc_smooth_scroll'] ) ? '1' : '0';
		$output['toc_scroll_offset']  = isset( $input['toc_scroll_offset'] ) ? max( 0, (int) $input['toc_scroll_offset'] ) : 80;
		$output['toc_id_prefix']      = isset( $input['toc_id_prefix'] ) ? sanitize_title( $input['toc_id_prefix'] ) : '';

		$output['load_assets_conditional'] = ! empty( $input['load_assets_conditional'] ) ? '1' : '0';

		add_settings_error(
			'wp_contentkit_messages',
			'wp_contentkit_message',
			__( 'Pengaturan WP ContentKit berhasil disimpan.', 'wp-contentkit' ),
			'updated'
		);

		return $output;
	}

	/**
	 * Enqueue stylesheet and generator script on plugin settings page.
	 *
	 * @param string $hook Screen hook.
	 */
	public function enqueue_admin_assets( $hook ) {
		if ( 'toplevel_page_wp-contentkit' !== $hook ) {
			return;
		}

		wp_enqueue_style( 'wp-color-picker' );

		wp_enqueue_style(
			'wpck-admin-settings',
			WP_CONTENTKIT_URL . 'assets/css/admin-settings.css',
			array( 'wp-color-picker', 'dashicons' ),
			WP_CONTENTKIT_VERSION
		);

		wp_enqueue_script(
			'wpck-admin-settings-js',
			WP_CONTENTKIT_URL . 'assets/js/classic-editor-modal.js',
			array( 'jquery', 'wp-color-picker' ),
			WP_CONTENTKIT_VERSION,
			true
		);

		wp_localize_script(
			'wpck-admin-settings-js',
			'wpckModalData',
			array(
				'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
				'nonce'     => wp_create_nonce( 'wpck_box_nonce' ),
				'templates' => Content_Box_Templates::get_templates(),
				'i18n'      => array(
					'modalTitle'   => __( 'Content Box Generator — WP ContentKit', 'wp-contentkit' ),
					'insertButton' => __( 'Insert Box ke Editor', 'wp-contentkit' ),
					'close'        => __( 'Tutup', 'wp-contentkit' ),
					'preview'      => __( 'Live Preview', 'wp-contentkit' ),
					'copied'       => __( 'HTML Berhasil Disalin!', 'wp-contentkit' ),
					'generating'   => __( 'Membuat tampilan box...', 'wp-contentkit' ),
				),
			)
		);
	}

	/**
	 * Render settings page template.
	 */
	public function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$options   = self::get_options();
		$templates = Content_Box_Templates::get_templates();
		include WP_CONTENTKIT_PATH . 'templates/admin-settings-page.php';
	}
}
