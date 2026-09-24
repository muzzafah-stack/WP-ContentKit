<?php
/**
 * Admin Settings View Template.
 *
 * @package WP_ContentKit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wrap wpck-settings-wrap">

	<!-- Header Banner -->
	<div class="wpck-header-banner">
		<div>
			<h1 class="wpck-header-title">
				WP ContentKit <span class="badge">v<?php echo esc_html( WP_CONTENTKIT_VERSION ); ?></span>
			</h1>
			<p class="wpck-header-tagline">
				<?php esc_html_e( 'Smart Tools for Better Content. Lightweight SSR TOC & Inline Content Box Generator.', 'wp-contentkit' ); ?>
			</p>
		</div>
		<div class="wpck-header-author">
			<span><?php esc_html_e( 'By', 'wp-contentkit' ); ?> </span>
			<a href="https://www.hipnolink.com" target="_blank" rel="noopener noreferrer">Hipnolink Team Digital</a>
			&bull;
			<a href="https://github.com/muzzafah-stack" target="_blank" rel="noopener noreferrer">GitHub</a>
		</div>
	</div>

	<?php settings_errors(); ?>

	<!-- Tab Navigation -->
	<div class="wpck-tabs-nav">
		<button type="button" class="wpck-tab-btn active" data-tab="tab-general">
			<span class="dashicons dashicons-admin-generic"></span> <?php esc_html_e( 'General', 'wp-contentkit' ); ?>
		</button>
		<button type="button" class="wpck-tab-btn" data-tab="tab-toc">
			<span class="dashicons dashicons-list-view"></span> <?php esc_html_e( 'Smart TOC', 'wp-contentkit' ); ?>
		</button>
		<button type="button" class="wpck-tab-btn" data-tab="tab-content-box">
			<span class="dashicons dashicons-editor-kitchensink"></span> <?php esc_html_e( 'Content Box Generator', 'wp-contentkit' ); ?>
		</button>
		<button type="button" class="wpck-tab-btn" data-tab="tab-performance">
			<span class="dashicons dashicons-performance"></span> <?php esc_html_e( 'Performance & Status', 'wp-contentkit' ); ?>
		</button>
	</div>

	<!-- ================= TAB: CONTENT BOX GENERATOR (INTERACTIVE STUDIO) ================= -->
	<div class="wpck-tab-panel" id="tab-content-box">
		<div class="wpck-card">
			<div class="wpck-generator-intro">
				<h3 class="wpck-card-title" style="margin-bottom: 6px;">
					<span class="dashicons dashicons-editor-kitchensink" style="color: #2563eb;"></span>
					<?php esc_html_e( 'Interactive Content Box HTML Generator', 'wp-contentkit' ); ?>
				</h3>
				<p style="color: #475569; font-size: 13.5px; line-height: 1.5; margin: 0 0 16px 0;">
					<?php esc_html_e( 'Pilih template siap pakai di bawah ini, sesuaikan isi & warnanya secara live, lalu salin kode HTML-nya langsung untuk ditempel ke Classic Editor, Elementor (HTML Widget), Gutenberg (Custom HTML), maupun platform lainnya.', 'wp-contentkit' ); ?>
				</p>
			</div>

			<!-- Template Selector Chips -->
			<div class="wpck-template-selector" style="border-radius: 8px; margin-bottom: 20px;">
				<?php foreach ( $templates as $tpl_id => $tpl ) : ?>
					<button type="button" class="wpck-template-chip <?php echo 'important' === $tpl_id ? 'active' : ''; ?>" data-template="<?php echo esc_attr( $tpl_id ); ?>">
						<span class="dashicons <?php echo esc_attr( $tpl['icon'] ); ?>"></span>
						<?php echo esc_html( $tpl['title'] ); ?>
					</button>
				<?php endforeach; ?>
			</div>

			<!-- 2-Column Generator Workspace -->
			<div class="wpck-generator-grid">
				<!-- Left: Dynamic Form Fields -->
				<div class="wpck-generator-col wpck-generator-fields">
					<h4 class="wpck-col-title">
						<span class="dashicons dashicons-admin-settings"></span>
						<?php esc_html_e( 'Sesuaikan Parameter Template', 'wp-contentkit' ); ?>
					</h4>
					<div id="wpck-modal-fields" class="wpck-fields-container">
						<!-- Dynamically populated by classic-editor-modal.js -->
					</div>
				</div>

				<!-- Right: Live Preview & HTML Output -->
				<div class="wpck-generator-col wpck-generator-output">
					<div class="wpck-preview-header" style="margin-bottom: 12px;">
						<div class="wpck-preview-view-toggles">
							<button type="button" class="wpck-preview-toggle active" data-view="visual">
								<span class="dashicons dashicons-visibility"></span> <?php esc_html_e( 'Visual Preview', 'wp-contentkit' ); ?>
							</button>
							<button type="button" class="wpck-preview-toggle" data-view="code">
								<span class="dashicons dashicons-editor-code"></span> <?php esc_html_e( 'Kode HTML', 'wp-contentkit' ); ?>
							</button>
						</div>
						<span class="wpck-preview-badge"><?php esc_html_e( '100% Inline CSS', 'wp-contentkit' ); ?></span>
					</div>

					<!-- Visual Viewport -->
					<div id="wpck-preview-viewport" class="wpck-preview-viewport" style="min-height: 280px;">
						<!-- Live Preview Rendered Here -->
					</div>

					<!-- Code Viewport -->
					<div id="wpck-code-viewport" class="wpck-code-viewport" style="display: none; min-height: 280px;">
						<textarea id="wpck-generated-html" class="wpck-code-textarea" readonly spellcheck="false"></textarea>
					</div>

					<!-- Action Buttons -->
					<div class="wpck-generator-actions" style="margin-top: 14px; display: flex; align-items: center; justify-content: space-between;">
						<div style="display: flex; align-items: center; gap: 10px;">
							<button type="button" id="wpck-btn-copy" class="button button-primary wpck-btn-primary-action">
								<span class="dashicons dashicons-clipboard"></span> <?php esc_html_e( 'Salin Kode HTML', 'wp-contentkit' ); ?>
							</button>
							<span id="wpck-copy-feedback" class="wpck-copy-feedback" style="display: none;">
								<span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Berhasil Disalin ke Clipboard!', 'wp-contentkit' ); ?>
							</span>
						</div>
						<div style="font-size: 12px; color: #64748b;">
							<?php esc_html_e( 'Mandiri & Bebas Dependency', 'wp-contentkit' ); ?>
						</div>
					</div>
				</div>
			</div>

			<!-- Usage Guide Box -->
			<div class="wpck-usage-guide" style="margin-top: 28px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px;">
				<h4 style="margin: 0 0 12px 0; font-size: 14px; color: #0f172a; display: flex; align-items: center; gap: 6px;">
					<span class="dashicons dashicons-book-alt" style="color: #2563eb;"></span>
					<?php esc_html_e( 'Panduan Cara Menggunakan Content Box di Berbagai Editor', 'wp-contentkit' ); ?>
				</h4>
				<div class="wpck-guide-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
					<div class="wpck-guide-item">
						<strong style="color: #1e293b; font-size: 13px; display: block; margin-bottom: 4px;">1. Classic Editor (WordPress Classic Editor)</strong>
						<p style="margin: 0; font-size: 12px; color: #475569; line-height: 1.5;">
							Klik tombol <strong>"Content Box"</strong> di atas editor (di sebelah tombol <em>Tambah Media / Add Media</em>) atau klik ikon kotak pada toolbar TinyMCE. Pilih template lalu klik <strong>"Insert Box ke Editor"</strong>.
						</p>
					</div>
					<div class="wpck-guide-item">
						<strong style="color: #1e293b; font-size: 13px; display: block; margin-bottom: 4px;">2. Elementor Editor</strong>
						<p style="margin: 0; font-size: 12px; color: #475569; line-height: 1.5;">
							Klik <strong>"Salin Kode HTML"</strong> dari generator di atas, lalu tambahkan widget <strong>HTML</strong> atau <strong>Text Editor</strong> di Elementor dan tempelkan kodenya.
						</p>
					</div>
					<div class="wpck-guide-item">
						<strong style="color: #1e293b; font-size: 13px; display: block; margin-bottom: 4px;">3. Gutenberg / Block Editor</strong>
						<p style="margin: 0; font-size: 12px; color: #475569; line-height: 1.5;">
							Tambahkan blok <strong>Custom HTML</strong> di Gutenberg dan tempelkan kode yang telah disalin.
						</p>
					</div>
					<div class="wpck-guide-item">
						<strong style="color: #1e293b; font-size: 13px; display: block; margin-bottom: 4px;">4. Email Newsletter & External CMS</strong>
						<p style="margin: 0; font-size: 12px; color: #475569; line-height: 1.5;">
							Karena menggunakan 100% Inline CSS murni, kotak template ini kompatibel dengan Gmail, Mailchimp, email newsletter, dan website apapun tanpa CSS eksternal.
						</p>
					</div>
				</div>
			</div>

		</div>
	</div>

	<form method="post" action="options.php">
		<?php settings_fields( 'wp_contentkit_settings_group' ); ?>

		<!-- ================= TAB: GENERAL ================= -->
		<div class="wpck-tab-panel active" id="tab-general">
			<div class="wpck-card">
				<h3 class="wpck-card-title"><?php esc_html_e( 'Pengaturan Utama', 'wp-contentkit' ); ?></h3>

				<div class="wpck-field-row">
					<div class="wpck-field-label">
						<?php esc_html_e( 'Aktifkan Smart TOC', 'wp-contentkit' ); ?>
					</div>
					<div class="wpck-field-input">
						<label class="wpck-checkbox-label">
							<input type="checkbox" name="wp_contentkit_settings[enable_toc]" value="1" <?php checked( '1', $options['enable_toc'] ); ?>>
							<?php esc_html_e( 'Aktifkan modul Table of Contents & Heading Anchor Injector', 'wp-contentkit' ); ?>
						</label>
						<p class="wpck-field-desc">
							<?php esc_html_e( 'Menyediakan server-side heading parser dan widget Smart TOC di Elementor.', 'wp-contentkit' ); ?>
						</p>
					</div>
				</div>

				<div class="wpck-field-row">
					<div class="wpck-field-label">
						<?php esc_html_e( 'Aktifkan Content Box', 'wp-contentkit' ); ?>
					</div>
					<div class="wpck-field-input">
						<label class="wpck-checkbox-label">
							<input type="checkbox" name="wp_contentkit_settings[enable_content_box]" value="1" <?php checked( '1', $options['enable_content_box'] ); ?>>
							<?php esc_html_e( 'Aktifkan tombol generator Content Box di Classic Editor', 'wp-contentkit' ); ?>
						</label>
						<p class="wpck-field-desc">
							<?php esc_html_e( 'Menambahkan tombol "Content Box" di samping Add Media, TinyMCE, dan Text Editor.', 'wp-contentkit' ); ?>
						</p>
					</div>
				</div>

				<div class="wpck-field-row">
					<div class="wpck-field-label">
						<?php esc_html_e( 'Heading Default', 'wp-contentkit' ); ?>
					</div>
					<div class="wpck-field-input">
						<div class="wpck-checkbox-group">
							<?php
							$levels = array( 'h2' => 'H2', 'h3' => 'H3', 'h4' => 'H4', 'h5' => 'H5', 'h6' => 'H6' );
							$saved_levels = (array) $options['default_heading_levels'];
							foreach ( $levels as $key => $label ) :
								?>
								<label class="wpck-checkbox-label">
									<input type="checkbox" name="wp_contentkit_settings[default_heading_levels][]" value="<?php echo esc_attr( $key ); ?>" <?php checked( in_array( $key, $saved_levels, true ) ); ?>>
									<?php echo esc_html( $label ); ?>
								</label>
							<?php endforeach; ?>
						</div>
						<p class="wpck-field-desc">
							<?php esc_html_e( 'Pilih heading level standar yang akan dimasukkan ke dalam TOC secara otomatis.', 'wp-contentkit' ); ?>
						</p>
					</div>
				</div>

				<div class="wpck-field-row">
					<div class="wpck-field-label">
						<?php esc_html_e( 'Batas Minimal Heading', 'wp-contentkit' ); ?>
					</div>
					<div class="wpck-field-input">
						<input type="number" name="wp_contentkit_settings[min_headings_count]" value="<?php echo esc_attr( $options['min_headings_count'] ); ?>" min="1" max="20" style="max-width: 120px;">
						<p class="wpck-field-desc">
							<?php esc_html_e( 'TOC otomatis disembunyikan jika jumlah heading pada artikel kurang dari nilai ini.', 'wp-contentkit' ); ?>
						</p>
					</div>
				</div>
			</div>
		</div>

		<!-- ================= TAB: SMART TOC ================= -->
		<div class="wpck-tab-panel" id="tab-toc">
			<div class="wpck-card">
				<h3 class="wpck-card-title"><?php esc_html_e( 'Pengaturan Standar Table of Contents', 'wp-contentkit' ); ?></h3>

				<div class="wpck-field-row">
					<div class="wpck-field-label">
						<?php esc_html_e( 'Judul Default TOC', 'wp-contentkit' ); ?>
					</div>
					<div class="wpck-field-input">
						<input type="text" name="wp_contentkit_settings[toc_default_title]" value="<?php echo esc_attr( $options['toc_default_title'] ); ?>" placeholder="Daftar Isi">
					</div>
				</div>

				<div class="wpck-field-row">
					<div class="wpck-field-label">
						<?php esc_html_e( 'Gaya Penomoran', 'wp-contentkit' ); ?>
					</div>
					<div class="wpck-field-input">
						<select name="wp_contentkit_settings[toc_numbering]">
							<option value="decimal" <?php selected( 'decimal', $options['toc_numbering'] ); ?>><?php esc_html_e( 'Desimal Normal (1, 2, 3...)', 'wp-contentkit' ); ?></option>
							<option value="nested" <?php selected( 'nested', $options['toc_numbering'] ); ?>><?php esc_html_e( 'Desimal Bertingkat (1.1, 1.2...)', 'wp-contentkit' ); ?></option>
							<option value="none" <?php selected( 'none', $options['toc_numbering'] ); ?>><?php esc_html_e( 'Tanpa Nomor', 'wp-contentkit' ); ?></option>
						</select>
					</div>
				</div>

				<div class="wpck-field-row">
					<div class="wpck-field-label">
						<?php esc_html_e( 'Fitur Buka/Tutup (Collapsible)', 'wp-contentkit' ); ?>
					</div>
					<div class="wpck-field-input">
						<label class="wpck-checkbox-label">
							<input type="checkbox" name="wp_contentkit_settings[toc_collapsible]" value="1" <?php checked( '1', $options['toc_collapsible'] ); ?>>
							<?php esc_html_e( 'Izinkan pembaca memperkecil / menutup daftar isi', 'wp-contentkit' ); ?>
						</label>
					</div>
				</div>

				<div class="wpck-field-row">
					<div class="wpck-field-label">
						<?php esc_html_e( 'Status Awal TOC', 'wp-contentkit' ); ?>
					</div>
					<div class="wpck-field-input">
						<select name="wp_contentkit_settings[toc_default_state]">
							<option value="expanded" <?php selected( 'expanded', $options['toc_default_state'] ); ?>><?php esc_html_e( 'Terbuka Penuh (Expanded)', 'wp-contentkit' ); ?></option>
							<option value="collapsed" <?php selected( 'collapsed', $options['toc_default_state'] ); ?>><?php esc_html_e( 'Tertutup (Collapsed)', 'wp-contentkit' ); ?></option>
						</select>
					</div>
				</div>

				<div class="wpck-field-row">
					<div class="wpck-field-label">
						<?php esc_html_e( 'Smooth Scrolling', 'wp-contentkit' ); ?>
					</div>
					<div class="wpck-field-input">
						<label class="wpck-checkbox-label">
							<input type="checkbox" name="wp_contentkit_settings[toc_smooth_scroll]" value="1" <?php checked( '1', $options['toc_smooth_scroll'] ); ?>>
							<?php esc_html_e( 'Aktifkan efek gulir halus (Smooth Scroll) saat link TOC diklik', 'wp-contentkit' ); ?>
						</label>
					</div>
				</div>

				<div class="wpck-field-row">
					<div class="wpck-field-label">
						<?php esc_html_e( 'Sticky Header Offset (px)', 'wp-contentkit' ); ?>
					</div>
					<div class="wpck-field-input">
						<input type="number" name="wp_contentkit_settings[toc_scroll_offset]" value="<?php echo esc_attr( $options['toc_scroll_offset'] ); ?>" min="0" max="300" style="max-width: 120px;">
						<p class="wpck-field-desc">
							<?php esc_html_e( 'Kompensasi jarak dalam piksel agar judul heading tidak tertutup oleh header tema yang sticky/fixed saat scroll berhenti.', 'wp-contentkit' ); ?>
						</p>
					</div>
				</div>

				<div class="wpck-field-row">
					<div class="wpck-field-label">
						<?php esc_html_e( 'ID Prefix Kustom (Opsional)', 'wp-contentkit' ); ?>
					</div>
					<div class="wpck-field-input">
						<input type="text" name="wp_contentkit_settings[toc_id_prefix]" value="<?php echo esc_attr( $options['toc_id_prefix'] ); ?>" placeholder="misal: sec">
						<p class="wpck-field-desc">
							<?php esc_html_e( 'Menambahkan prefix di depan slug ID heading (contoh: #sec-judul-heading). Biarkan kosong untuk slug standar.', 'wp-contentkit' ); ?>
						</p>
					</div>
				</div>
			</div>
		</div>

		<!-- ================= TAB: PERFORMANCE & STATUS ================= -->
		<div class="wpck-tab-panel" id="tab-performance">
			<div class="wpck-card">
				<h3 class="wpck-card-title"><?php esc_html_e( 'Optimasi Performa & Kompatibilitas Cache', 'wp-contentkit' ); ?></h3>

				<div class="wpck-field-row">
					<div class="wpck-field-label">
						<?php esc_html_e( 'Pemuatan Aset Kondisional', 'wp-contentkit' ); ?>
					</div>
					<div class="wpck-field-input">
						<label class="wpck-checkbox-label">
							<input type="checkbox" name="wp_contentkit_settings[load_assets_conditional]" value="1" <?php checked( '1', $options['load_assets_conditional'] ); ?>>
							<?php esc_html_e( 'Hanya muat file CSS & JS frontend pada halaman postingan artikel', 'wp-contentkit' ); ?>
						</label>
						<p class="wpck-field-desc">
							<?php esc_html_e( 'Mencegah pemuatan file CSS/JS di halaman beranda atau arsip yang tidak memiliki widget TOC.', 'wp-contentkit' ); ?>
						</p>
					</div>
				</div>

				<h4 style="margin: 24px 0 10px 0; font-size: 15px; color: #0f172a;"><?php esc_html_e( 'Status Arsitektur Plugin & Kompatibilitas', 'wp-contentkit' ); ?></h4>

				<div class="wpck-status-grid">
					<div class="wpck-status-box">
						<h4>WordPress Target</h4>
						<div class="val" style="color: #16a34a;">WP 7.1.2 & 6.x Ready</div>
						<p style="font-size: 11.5px; color: #64748b; margin: 4px 0 0 0;">PHP 7.4 - 8.3+ Clean Architecture.</p>
					</div>
					<div class="wpck-status-box">
						<h4>Elementor Status</h4>
						<div class="val" style="color: <?php echo did_action( 'elementor/loaded' ) ? '#16a34a' : '#94a3b8'; ?>;">
							<?php echo did_action( 'elementor/loaded' ) ? ( defined( 'ELEMENTOR_VERSION' ) ? 'Elementor v' . esc_html( ELEMENTOR_VERSION ) : 'Elementor Aktif' ) : esc_html__( 'Elementor Tidak Terdeteksi', 'wp-contentkit' ); ?>
						</div>
						<p style="font-size: 11.5px; color: #64748b; margin: 4px 0 0 0;">Kompatibel Elementor 4.3.1 & 3.5+.</p>
					</div>
					<div class="wpck-status-box">
						<h4>Rendering Method</h4>
						<div class="val" style="color: #16a34a;">100% Server-Side (SSR)</div>
						<p style="font-size: 11.5px; color: #64748b; margin: 4px 0 0 0;">TOC langsung di HTML tanpa delay/spinner.</p>
					</div>
					<div class="wpck-status-box">
						<h4>Cache Plugin Safety</h4>
						<div class="val" style="color: #2563eb;">Delay/Defer JS Safe</div>
						<p style="font-size: 11.5px; color: #64748b; margin: 4px 0 0 0;">WP Rocket, LiteSpeed, Cloudflare, FlyingPress.</p>
					</div>
				</div>
			</div>
		</div>

		<!-- Submit Button (Hidden on Generator Tab, shown on Settings tabs) -->
		<div class="wpck-submit-wrap" id="wpck-settings-submit-wrap">
			<?php submit_button( __( 'Simpan Perubahan', 'wp-contentkit' ), 'primary wpck-btn-primary', 'submit', false ); ?>
		</div>
	</form>

</div>

<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
	var tabs = document.querySelectorAll('.wpck-tab-btn');
	var panels = document.querySelectorAll('.wpck-tab-panel');
	var submitWrap = document.getElementById('wpck-settings-submit-wrap');

	tabs.forEach(function(tab) {
		tab.addEventListener('click', function() {
			var target = tab.getAttribute('data-tab');

			tabs.forEach(function(t) { t.classList.remove('active'); });
			panels.forEach(function(p) { p.classList.remove('active'); });

			tab.classList.add('active');
			var activePanel = document.getElementById(target);
			if (activePanel) {
				activePanel.classList.add('active');
			}

			if (target === 'tab-content-box') {
				if (submitWrap) submitWrap.style.display = 'none';
				if (window.WPCK_Modal) {
					window.WPCK_Modal.updatePreview();
				}
			} else {
				if (submitWrap) submitWrap.style.display = 'block';
			}
		});
	});
});
</script>
