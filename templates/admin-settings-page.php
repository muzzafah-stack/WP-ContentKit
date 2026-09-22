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
				<?php esc_html_e( 'Smart Tools for Better Content. Lightweight SSR TOC & Inline Content Box Utility.', 'wp-contentkit' ); ?>
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
			<span class="dashicons dashicons-editor-kitchensink"></span> <?php esc_html_e( 'Content Box', 'wp-contentkit' ); ?>
		</button>
		<button type="button" class="wpck-tab-btn" data-tab="tab-performance">
			<span class="dashicons dashicons-performance"></span> <?php esc_html_e( 'Performance & Status', 'wp-contentkit' ); ?>
		</button>
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
							<?php esc_html_e( 'Menambahkan tombol "Content Box" pada TinyMCE & Text Editor dengan 7 template bawaan + Custom Box.', 'wp-contentkit' ); ?>
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

		<!-- ================= TAB: CONTENT BOX ================= -->
		<div class="wpck-tab-panel" id="tab-content-box">
			<div class="wpck-card">
				<h3 class="wpck-card-title"><?php esc_html_e( 'Daftar Template Content Box Siap Pakai', 'wp-contentkit' ); ?></h3>
				<p style="color: #475569; font-size: 13.5px; line-height: 1.5; margin-bottom: 20px;">
					<?php esc_html_e( 'Setiap template dirancang khusus menghasilkan tag HTML dengan atribut style (Inline CSS) murni, sehingga 100% mandiri, tidak memerlukan stylesheet eksternal, dan aman dipindahkan antar dokumen.', 'wp-contentkit' ); ?>
				</p>

				<div class="wpck-status-grid">
					<div class="wpck-status-box">
						<h4>1. Poin Penting</h4>
						<div class="val" style="font-size: 14px; font-weight: 600; color: #2563eb;">Border Kiri Biru Tebal</div>
						<p style="font-size: 12px; color: #64748b; margin: 4px 0 0 0;">Bullet points terstruktur untuk kesimpulan cepat.</p>
					</div>
					<div class="wpck-status-box">
						<h4>2. Author Box</h4>
						<div class="val" style="font-size: 14px; font-weight: 600; color: #0f172a;">Profil Penulis Lengkap</div>
						<p style="font-size: 12px; color: #64748b; margin: 4px 0 0 0;">Avatar, nama, role, bio, dan link portofolio.</p>
					</div>
					<div class="wpck-status-box">
						<h4>3. Reviewed By</h4>
						<div class="val" style="font-size: 14px; font-weight: 600; color: #475569;">E-E-A-T Verifikasi</div>
						<p style="font-size: 12px; color: #64748b; margin: 4px 0 0 0;">Kotak peninjauan ahli bersertifikasi.</p>
					</div>
					<div class="wpck-status-box">
						<h4>4. Related Content</h4>
						<div class="val" style="font-size: 14px; font-weight: 600; color: #1e3a8a;">Baca Juga</div>
						<p style="font-size: 12px; color: #64748b; margin: 4px 0 0 0;">Badge rekomendasi artikel terkait di tengah konten.</p>
					</div>
					<div class="wpck-status-box">
						<h4>5. Note / Catatan</h4>
						<div class="val" style="font-size: 14px; font-weight: 600; color: #d97706;">Aksen Hangat</div>
						<p style="font-size: 12px; color: #64748b; margin: 4px 0 0 0;">Informasi tips atau catatan tambahan.</p>
					</div>
					<div class="wpck-status-box">
						<h4>6. Warning</h4>
						<div class="val" style="font-size: 14px; font-weight: 600; color: #dc2626;">Aksen Waspada</div>
						<p style="font-size: 12px; color: #64748b; margin: 4px 0 0 0;">Disclaimer atau peringatan penting.</p>
					</div>
					<div class="wpck-status-box">
						<h4>7. Simple Info</h4>
						<div class="val" style="font-size: 14px; font-weight: 600; color: #16a34a;">Nuansa Segar</div>
						<p style="font-size: 12px; color: #64748b; margin: 4px 0 0 0;">Kotak info minimalis bergaris hijau halus.</p>
					</div>
					<div class="wpck-status-box">
						<h4>8. Custom Box</h4>
						<div class="val" style="font-size: 14px; font-weight: 600; color: #7c3aed;">Kustom Bebas</div>
						<p style="font-size: 12px; color: #64748b; margin: 4px 0 0 0;">Atur warna, border style, radius, & padding sesuka hati.</p>
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

				<h4 style="margin: 24px 0 10px 0; font-size: 15px; color: #0f172a;"><?php esc_html_e( 'Status Arsitektur Plugin', 'wp-contentkit' ); ?></h4>

				<div class="wpck-status-grid">
					<div class="wpck-status-box">
						<h4>Rendering Method</h4>
						<div class="val" style="color: #16a34a;">100% Server-Side (SSR)</div>
						<p style="font-size: 11.5px; color: #64748b; margin: 4px 0 0 0;">TOC langsung tersedia di HTML pertama tanpa loading spinner.</p>
					</div>
					<div class="wpck-status-box">
						<h4>Cache Plugin Safety</h4>
						<div class="val" style="color: #2563eb;">Delay/Defer JS Safe</div>
						<p style="font-size: 11.5px; color: #64748b; margin: 4px 0 0 0;">Perfmatters, FlyingPress, WP Rocket, LiteSpeed, Cloudflare.</p>
					</div>
					<div class="wpck-status-box">
						<h4>Frontend JS Dependency</h4>
						<div class="val" style="color: #0f172a;">Zero (Vanilla JS &lt; 2KB)</div>
						<p style="font-size: 11.5px; color: #64748b; margin: 4px 0 0 0;">Tanpa jQuery di frontend. Navigasi tetap bekerja jika JS dimatikan.</p>
					</div>
					<div class="wpck-status-box">
						<h4>Elementor Status</h4>
						<div class="val" style="color: <?php echo did_action( 'elementor/loaded' ) ? '#16a34a' : '#94a3b8'; ?>;">
							<?php echo did_action( 'elementor/loaded' ) ? esc_html__( 'Elementor Aktif', 'wp-contentkit' ) : esc_html__( 'Elementor Tidak Terdeteksi', 'wp-contentkit' ); ?>
						</div>
						<p style="font-size: 11.5px; color: #64748b; margin: 4px 0 0 0;">Widget Smart TOC terintegrasi.</p>
					</div>
				</div>
			</div>
		</div>

		<!-- Submit Button -->
		<div class="wpck-submit-wrap">
			<?php submit_button( __( 'Simpan Perubahan', 'wp-contentkit' ), 'primary wpck-btn-primary', 'submit', false ); ?>
		</div>
	</form>

</div>

<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
	var tabs = document.querySelectorAll('.wpck-tab-btn');
	var panels = document.querySelectorAll('.wpck-tab-panel');

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
		});
	});
});
</script>
