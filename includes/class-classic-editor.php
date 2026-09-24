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
		$options = Admin_Settings::get_options();
		if ( empty( $options['enable_content_box'] ) || '1' !== $options['enable_content_box'] ) {
			return;
		}

		// Editor toolbar & media buttons hooks.
		add_action( 'media_buttons', array( $this, 'render_media_button' ), 20 );
		add_action( 'admin_init', array( $this, 'register_tinymce_button' ) );
		add_action( 'admin_print_footer_scripts', array( $this, 'register_quicktags_button' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_editor_assets' ) );
		add_action( 'admin_footer', array( $this, 'render_modal_container' ) );
	}

	/**
	 * Register button next to "Add Media" (Media Buttons bar).
	 * Most visible and reliable button position for Classic Editor.
	 *
	 * @param string $editor_id ID of the editor instance (usually 'content').
	 */
	public function render_media_button( $editor_id = 'content' ) {
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			return;
		}

		// Skip on Elementor editor.
		if ( isset( $_GET['action'] ) && 'elementor' === $_GET['action'] ) {
			return;
		}

		?>
		<button type="button" class="button wpck-media-button wpck-open-modal-btn" data-editor="<?php echo esc_attr( $editor_id ); ?>" title="<?php esc_attr_e( 'Insert Inline Content Box (WP ContentKit)', 'wp-contentkit' ); ?>">
			<span class="dashicons dashicons-editor-kitchensink wpck-media-icon"></span>
			<?php esc_html_e( 'Content Box', 'wp-contentkit' ); ?>
		</button>
		<?php
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
		$plugins['wpck_box'] = WP_CONTENTKIT_URL . 'assets/js/tinymce-plugin.js';
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

		// Do not load on Elementor editor screen to prevent script collisions.
		if ( isset( $_GET['action'] ) && 'elementor' === $_GET['action'] ) {
			return;
		}
		if ( isset( $_GET['page'] ) && strpos( $_GET['page'], 'elementor' ) !== false ) {
			return;
		}

		wp_enqueue_style( 'wp-color-picker' );

		wp_enqueue_style(
			'wpck-classic-editor-modal',
			WP_CONTENTKIT_URL . 'assets/css/classic-editor-modal.css',
			array( 'dashicons', 'wp-color-picker' ),
			WP_CONTENTKIT_VERSION
		);

		wp_enqueue_script(
			'wpck-classic-editor-modal',
			WP_CONTENTKIT_URL . 'assets/js/classic-editor-modal.js',
			array( 'jquery', 'wp-color-picker' ),
			WP_CONTENTKIT_VERSION,
			true
		);

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
					'copied'       => __( 'HTML Berhasil Disalin!', 'wp-contentkit' ),
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
		if ( ! $screen || ! in_array( $screen->base, array( 'post', 'edit' ), true ) ) {
			return;
		}

		// Skip on Elementor editor.
		if ( isset( $_GET['action'] ) && 'elementor' === $_GET['action'] ) {
			return;
		}

		$templates = Content_Box_Templates::get_templates();
		include WP_CONTENTKIT_PATH . 'templates/classic-editor-modal.php';
	}
}
