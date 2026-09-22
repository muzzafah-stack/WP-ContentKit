<?php
/**
 * Classic Editor Integration for WP ContentKit.
 *
 * @package WP_ContentKit
 */

namespace WP_ContentKit;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Classic_Editor
 */
class Classic_Editor {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'register_tinymce_button' ) );
		add_action( 'admin_print_footer_scripts', array( $this, 'register_quicktags_button' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_editor_assets' ) );
		add_action( 'admin_footer', array( $this, 'render_modal_container' ) );

		// AJAX preview/render endpoint.
		add_action( 'wp_ajax_wpck_render_box_preview', array( $this, 'ajax_render_box_preview' ) );
	}

	/**
	 * Register TinyMCE plugin and button.
	 */
	public function register_tinymce_button() {
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			return;
		}

		if ( 'true' === get_user_option( 'rich_editing' ) ) {
			add_filter( 'mce_external_plugins', array( $this, 'add_tinymce_plugin' ) );
			add_filter( 'mce_buttons', array( $this, 'add_tinymce_button' ) );
		}
	}

	/**
	 * Add TinyMCE external plugin script.
	 *
	 * @param array $plugins Plugins array.
	 * @return array
	 */
	public function add_tinymce_plugin( $plugins ) {
		$plugins['wpck_box'] = WP_CONTENTKIT_URL . 'assets/js/classic-editor-modal.js';
		return $plugins;
	}

	/**
	 * Add button to TinyMCE toolbar.
	 *
	 * @param array $buttons Buttons array.
	 * @return array
	 */
	public function add_tinymce_button( $buttons ) {
		$buttons[] = 'wpck_box';
		return $buttons;
	}

	/**
	 * Register Quicktags button for Text Editor.
	 */
	public function register_quicktags_button() {
		if ( ! wp_script_is( 'quicktags' ) ) {
			return;
		}
		?>
		<script type="text/javascript">
		if (typeof QTags !== 'undefined') {
			QTags.addButton(
				'wpck_insert_box_qt',
				'<?php echo esc_js( __( 'Content Box', 'wp-contentkit' ) ); ?>',
				function() {
					if (window.WPCK_Modal) {
						window.WPCK_Modal.open();
					}
				},
				'',
				'q',
				'<?php echo esc_js( __( 'Insert Inline Content Box (WP ContentKit)', 'wp-contentkit' ) ); ?>',
				119
			);
		}
		</script>
		<?php
	}

	/**
	 * Enqueue assets on post edit screens.
	 *
	 * @param string $hook_suffix Current admin screen hook.
	 */
	public function enqueue_editor_assets( $hook_suffix ) {
		if ( ! in_array( $hook_suffix, array( 'post.php', 'post-new.php' ), true ) ) {
			return;
		}

		wp_enqueue_style(
			'wpck-classic-editor-modal',
			WP_CONTENTKIT_URL . 'assets/css/classic-editor-modal.css',
			array( 'dashicons' ),
			WP_CONTENTKIT_VERSION
		);

		wp_enqueue_script(
			'wpck-classic-editor-modal',
			WP_CONTENTKIT_URL . 'assets/js/classic-editor-modal.js',
			array( 'jquery', 'wp-color-picker' ),
			WP_CONTENTKIT_VERSION,
			true
		);

		wp_enqueue_style( 'wp-color-picker' );

		wp_localize_script(
			'wpck-classic-editor-modal',
			'wpckModalData',
			array(
				'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
				'nonce'     => wp_create_nonce( 'wpck_box_nonce' ),
				'templates' => Content_Box_Templates::get_templates(),
				'i18n'      => array(
					'modalTitle'   => __( 'Insert Content Box — WP ContentKit', 'wp-contentkit' ),
					'insertButton' => __( 'Insert Box ke Editor', 'wp-contentkit' ),
					'close'        => __( 'Tutup', 'wp-contentkit' ),
					'preview'      => __( 'Live Preview', 'wp-contentkit' ),
					'copied'       => __( 'HTML berhasil disalin!', 'wp-contentkit' ),
					'generating'   => __( 'Membuat tampilan box...', 'wp-contentkit' ),
				),
			)
		);
	}

	/**
	 * Render HTML modal container in admin footer.
	 */
	public function render_modal_container() {
		$screen = get_current_screen();
		if ( ! $screen || ! in_array( $screen->base, array( 'post' ), true ) ) {
			return;
		}

		$templates = Content_Box_Templates::get_templates();
		include WP_CONTENTKIT_PATH . 'templates/classic-editor-modal.php';
	}

	/**
	 * AJAX endpoint to compile HTML from submitted form data.
	 */
	public function ajax_render_box_preview() {
		check_ajax_referer( 'wpck_box_nonce', 'nonce' );

		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( array( 'message' => __( 'Unauthorized user.', 'wp-contentkit' ) ) );
		}

		$template_id = isset( $_POST['template'] ) ? sanitize_key( $_POST['template'] ) : 'important';
		$fields_raw  = isset( $_POST['fields'] ) && is_array( $_POST['fields'] ) ? wp_unslash( $_POST['fields'] ) : array();

		$data = array();
		foreach ( $fields_raw as $k => $v ) {
			$data[ sanitize_key( $k ) ] = sanitize_textarea_field( $v );
		}

		$html = Content_Box_Templates::render_inline_html( $template_id, $data );

		wp_send_json_success( array(
			'html' => $html,
		) );
	}
}
