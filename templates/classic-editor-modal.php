<?php
/**
 * Classic Editor Modal View Template.
 *
 * @package WP_ContentKit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div id="wpck-modal-backdrop" class="wpck-modal-backdrop" style="display:none;">
	<div class="wpck-modal-window" role="dialog" aria-modal="true" aria-labelledby="wpck-modal-title">

		<!-- Modal Header -->
		<div class="wpck-modal-header">
			<h3 id="wpck-modal-title" class="wpck-modal-title">
				<span class="dashicons dashicons-editor-kitchensink" style="font-size: 20px;"></span>
				<?php esc_html_e( 'Insert Inline Content Box — WP ContentKit', 'wp-contentkit' ); ?>
			</h3>
			<button type="button" class="wpck-modal-close-btn" aria-label="<?php esc_attr_e( 'Tutup Modal', 'wp-contentkit' ); ?>">&times;</button>
		</div>

		<!-- Template Selector Chips -->
		<div class="wpck-template-selector">
			<?php foreach ( $templates as $tpl_id => $tpl ) : ?>
				<button type="button" class="wpck-template-chip <?php echo 'important' === $tpl_id ? 'active' : ''; ?>" data-template="<?php echo esc_attr( $tpl_id ); ?>">
					<span class="dashicons <?php echo esc_attr( $tpl['icon'] ); ?>"></span>
					<?php echo esc_html( $tpl['title'] ); ?>
				</button>
			<?php endforeach; ?>
		</div>

		<!-- Modal Body (2 Columns) -->
		<div class="wpck-modal-body">
			<!-- Left: Form Fields -->
			<div class="wpck-modal-fields-column">
				<div id="wpck-modal-fields">
					<!-- Dynamically injected fields via classic-editor-modal.js -->
				</div>
			</div>

			<!-- Right: Live Preview -->
			<div class="wpck-modal-preview-column">
				<div class="wpck-preview-header">
					<span><?php esc_html_e( 'Live Preview', 'wp-contentkit' ); ?></span>
					<span class="wpck-preview-badge"><?php esc_html_e( 'Pure Inline CSS', 'wp-contentkit' ); ?></span>
				</div>
				<div id="wpck-preview-viewport" class="wpck-preview-viewport">
					<!-- Live preview rendered here -->
				</div>
			</div>
		</div>

		<!-- Modal Footer -->
		<div class="wpck-modal-footer">
			<div>
				<button type="button" id="wpck-btn-copy" class="wpck-btn wpck-btn-copy">
					<span class="dashicons dashicons-clipboard"></span> <?php esc_html_e( 'Salin Kode HTML', 'wp-contentkit' ); ?>
				</button>
			</div>
			<div style="display: flex; gap: 8px;">
				<button type="button" id="wpck-btn-cancel" class="wpck-btn wpck-btn-cancel">
					<?php esc_html_e( 'Batal', 'wp-contentkit' ); ?>
				</button>
				<button type="button" id="wpck-btn-insert" class="wpck-btn wpck-btn-insert">
					<span class="dashicons dashicons-plus-alt"></span> <?php esc_html_e( 'Insert Box ke Editor', 'wp-contentkit' ); ?>
				</button>
			</div>
		</div>

	</div>
</div>
