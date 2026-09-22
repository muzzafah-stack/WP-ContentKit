<?php
/**
 * Content Box Templates Registry and Inline CSS Compiler.
 *
 * @package WP_ContentKit
 */

namespace WP_ContentKit;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Content_Box_Templates
 */
class Content_Box_Templates {

	/**
	 * Get all registered templates metadata.
	 *
	 * @return array Templates configuration.
	 */
	public static function get_templates() {
		return array(
			'important' => array(
				'id'          => 'important',
				'title'       => __( 'Poin Penting', 'wp-contentkit' ),
				'description' => __( 'Kotak highlight bergaris tebal untuk menonjolkan poin-poin utama atau kesimpulan.', 'wp-contentkit' ),
				'icon'        => 'dashicons-star-filled',
				'fields'      => array(
					'title'    => array(
						'type'    => 'text',
						'label'   => __( 'Judul Box', 'wp-contentkit' ),
						'default' => __( 'Poin Penting:', 'wp-contentkit' ),
					),
					'items'    => array(
						'type'        => 'textarea',
						'label'       => __( 'Daftar Poin (Satu poin per baris)', 'wp-contentkit' ),
						'default'     => "Mulai dari latihan dasar sebelum mencoba teknik yang lebih kompleks.\nPastikan orang yang terlibat memahami dan menyetujui proses.\nGunakan secara bertanggung jawab.\nEvaluasi pengalaman latihan untuk meningkatkan kemampuan.",
						'placeholder' => __( "Poin 1\nPoin 2\nPoin 3", 'wp-contentkit' ),
					),
					'bg_color' => array(
						'type'    => 'color',
						'label'   => __( 'Warna Background', 'wp-contentkit' ),
						'default' => '#f8fafc',
					),
					'border_color' => array(
						'type'    => 'color',
						'label'   => __( 'Warna Aksen Border', 'wp-contentkit' ),
						'default' => '#2563eb',
					),
				),
			),

			'author' => array(
				'id'          => 'author',
				'title'       => __( 'Author Box', 'wp-contentkit' ),
				'description' => __( 'Kotak profil penulis lengkap dengan avatar, jabatan, biografi singkat, dan tautan.', 'wp-contentkit' ),
				'icon'        => 'dashicons-admin-users',
				'fields'      => array(
					'name'       => array(
						'type'    => 'text',
						'label'   => __( 'Nama Penulis', 'wp-contentkit' ),
						'default' => __( 'Nama Penulis', 'wp-contentkit' ),
					),
					'role'       => array(
						'type'    => 'text',
						'label'   => __( 'Jabatan / Keahlian', 'wp-contentkit' ),
						'default' => __( 'Content Writer & Specialist', 'wp-contentkit' ),
					),
					'avatar_url' => array(
						'type'        => 'text',
						'label'       => __( 'URL Avatar / Foto (Opsional)', 'wp-contentkit' ),
						'default'     => '',
						'placeholder' => 'https://example.com/avatar.jpg',
					),
					'bio'        => array(
						'type'    => 'textarea',
						'label'   => __( 'Biografi Singkat', 'wp-contentkit' ),
						'default' => __( 'Penulis dan praktisi yang berfokus pada pengembangan konten edukatif dan strategi komunikasi digital.', 'wp-contentkit' ),
					),
					'link_text'  => array(
						'type'    => 'text',
						'label'   => __( 'Teks Link Profil', 'wp-contentkit' ),
						'default' => __( 'Lihat Profil Lengkap', 'wp-contentkit' ),
					),
					'link_url'   => array(
						'type'        => 'text',
						'label'       => __( 'URL Profil', 'wp-contentkit' ),
						'default'     => 'https://example.com',
						'placeholder' => 'https://example.com/profile',
					),
					'bg_color'   => array(
						'type'    => 'color',
						'label'   => __( 'Warna Background', 'wp-contentkit' ),
						'default' => '#ffffff',
					),
					'border_color' => array(
						'type'    => 'color',
						'label'   => __( 'Warna Border', 'wp-contentkit' ),
						'default' => '#e2e8f0',
					),
				),
			),

			'reviewed_by' => array(
				'id'          => 'reviewed_by',
				'title'       => __( 'Reviewed By / Ditinjau Oleh', 'wp-contentkit' ),
				'description' => __( 'Kotak verifikasi kredibilitas ahli atau reviewer profesional (E-E-A-T friendly).', 'wp-contentkit' ),
				'icon'        => 'dashicons-yes-alt',
				'fields'      => array(
					'reviewer_name' => array(
						'type'    => 'text',
						'label'   => __( 'Nama Reviewer & Gelar', 'wp-contentkit' ),
						'default' => __( 'Dr. Nama Lengkap, M.Si', 'wp-contentkit' ),
					),
					'profession'    => array(
						'type'    => 'text',
						'label'   => __( 'Bidang / Profesi Keahlian', 'wp-contentkit' ),
						'default' => __( 'Spesialis Ahli', 'wp-contentkit' ),
					),
					'description'   => array(
						'type'    => 'textarea',
						'label'   => __( 'Deskripsi Peninjauan', 'wp-contentkit' ),
						'default' => __( 'Artikel ini telah ditinjau dan diverifikasi akurasinya oleh praktisi profesional di bidangnya.', 'wp-contentkit' ),
					),
					'link_text'     => array(
						'type'    => 'text',
						'label'   => __( 'Teks Link Verifikasi', 'wp-contentkit' ),
						'default' => __( 'Lihat Profil Peninjau', 'wp-contentkit' ),
					),
					'link_url'      => array(
						'type'        => 'text',
						'label'       => __( 'URL Peninjau', 'wp-contentkit' ),
						'default'     => 'https://example.com',
						'placeholder' => 'https://example.com',
					),
					'bg_color'      => array(
						'type'    => 'color',
						'label'   => __( 'Warna Background', 'wp-contentkit' ),
						'default' => '#fafafa',
					),
					'border_color'  => array(
						'type'    => 'color',
						'label'   => __( 'Warna Border', 'wp-contentkit' ),
						'default' => '#888888',
					),
				),
			),

			'related_content' => array(
				'id'          => 'related_content',
				'title'       => __( 'Related Content / Baca Juga', 'wp-contentkit' ),
				'description' => __( 'Kotak rekomendasi artikel terkait di tengah konten untuk meningkatkan pageviews.', 'wp-contentkit' ),
				'icon'        => 'dashicons-excerpt-view',
				'fields'      => array(
					'badge'       => array(
						'type'    => 'text',
						'label'   => __( 'Label Badge', 'wp-contentkit' ),
						'default' => __( 'Baca Juga:', 'wp-contentkit' ),
					),
					'article_title' => array(
						'type'    => 'text',
						'label'   => __( 'Judul Artikel Terkait', 'wp-contentkit' ),
						'default' => __( 'Panduan Lengkap Optimasi Konten dan SEO On-Page', 'wp-contentkit' ),
					),
					'article_url' => array(
						'type'        => 'text',
						'label'       => __( 'URL Artikel Terkait', 'wp-contentkit' ),
						'default'     => 'https://example.com/artikel-terkait',
						'placeholder' => 'https://example.com/artikel-terkait',
					),
					'bg_color'    => array(
						'type'    => 'color',
						'label'   => __( 'Warna Background', 'wp-contentkit' ),
						'default' => '#eff6ff',
					),
					'border_color' => array(
						'type'    => 'color',
						'label'   => __( 'Warna Border', 'wp-contentkit' ),
						'default' => '#bfdbfe',
					),
				),
			),

			'note' => array(
				'id'          => 'note',
				'title'       => __( 'Note / Catatan', 'wp-contentkit' ),
				'description' => __( 'Kotak catatan bersahabat dengan aksen hangat untuk tips atau klarifikasi.', 'wp-contentkit' ),
				'icon'        => 'dashicons-info',
				'fields'      => array(
					'title'    => array(
						'type'    => 'text',
						'label'   => __( 'Judul Catatan', 'wp-contentkit' ),
						'default' => __( 'Catatan:', 'wp-contentkit' ),
					),
					'content'  => array(
						'type'    => 'textarea',
						'label'   => __( 'Isi Catatan', 'wp-contentkit' ),
						'default' => __( 'Pastikan Anda mencadangkan (backup) data Anda sebelum melakukan pembaruan sistem.', 'wp-contentkit' ),
					),
					'bg_color' => array(
						'type'    => 'color',
						'label'   => __( 'Warna Background', 'wp-contentkit' ),
						'default' => '#fffbeb',
					),
					'border_color' => array(
						'type'    => 'color',
						'label'   => __( 'Warna Aksen Border', 'wp-contentkit' ),
						'default' => '#f59e0b',
					),
				),
			),

			'warning' => array(
				'id'          => 'warning',
				'title'       => __( 'Warning / Peringatan', 'wp-contentkit' ),
				'description' => __( 'Kotak peringatan tegas untuk hal-hal krusial atau disclaimer penting.', 'wp-contentkit' ),
				'icon'        => 'dashicons-warning',
				'fields'      => array(
					'title'    => array(
						'type'    => 'text',
						'label'   => __( 'Judul Peringatan', 'wp-contentkit' ),
						'default' => __( 'Peringatan Penting:', 'wp-contentkit' ),
					),
					'content'  => array(
						'type'    => 'textarea',
						'label'   => __( 'Isi Peringatan', 'wp-contentkit' ),
						'default' => __( 'Informasi ini ditujukan untuk tujuan edukasi. Jangan terapkan metode ini tanpa supervisi praktisi resmi.', 'wp-contentkit' ),
					),
					'bg_color' => array(
						'type'    => 'color',
						'label'   => __( 'Warna Background', 'wp-contentkit' ),
						'default' => '#fef2f2',
					),
					'border_color' => array(
						'type'    => 'color',
						'label'   => __( 'Warna Aksen Border', 'wp-contentkit' ),
						'default' => '#ef4444',
					),
				),
			),

			'simple_info' => array(
				'id'          => 'simple_info',
				'title'       => __( 'Simple Info Box', 'wp-contentkit' ),
				'description' => __( 'Kotak info bernuansa segar dan minimalis untuk tips tambahan.', 'wp-contentkit' ),
				'icon'        => 'dashicons-lightbulb',
				'fields'      => array(
					'title'    => array(
						'type'    => 'text',
						'label'   => __( 'Judul Info', 'wp-contentkit' ),
						'default' => __( 'Informasi Tambahan', 'wp-contentkit' ),
					),
					'content'  => array(
						'type'    => 'textarea',
						'label'   => __( 'Isi Informasi', 'wp-contentkit' ),
						'default' => __( 'Fitur ini dapat diaktifkan secara instan langsung dari menu pengaturan utama.', 'wp-contentkit' ),
					),
					'bg_color' => array(
						'type'    => 'color',
						'label'   => __( 'Warna Background', 'wp-contentkit' ),
						'default' => '#f0fdf4',
					),
					'border_color' => array(
						'type'    => 'color',
						'label'   => __( 'Warna Border', 'wp-contentkit' ),
						'default' => '#bbf7d0',
					),
				),
			),

			'custom' => array(
				'id'          => 'custom',
				'title'       => __( 'Custom Box', 'wp-contentkit' ),
				'description' => __( 'Kustomisasi penuh warna, ketebalan border, gaya garis, radius sudut, dan padding.', 'wp-contentkit' ),
				'icon'        => 'dashicons-admin-generic',
				'fields'      => array(
					'title'        => array(
						'type'    => 'text',
						'label'   => __( 'Judul Box', 'wp-contentkit' ),
						'default' => __( 'Judul Custom Box', 'wp-contentkit' ),
					),
					'content'      => array(
						'type'    => 'textarea',
						'label'   => __( 'Isi Konten', 'wp-contentkit' ),
						'default' => __( 'Tulis isi konten box kustom Anda di sini dengan leluasa.', 'wp-contentkit' ),
					),
					'link_url'     => array(
						'type'        => 'text',
						'label'       => __( 'Link URL (Opsional)', 'wp-contentkit' ),
						'default'     => '',
						'placeholder' => 'https://example.com',
					),
					'link_text'    => array(
						'type'        => 'text',
						'label'       => __( 'Teks Link (Opsional)', 'wp-contentkit' ),
						'default'     => '',
						'placeholder' => __( 'Pelajari Lebih Lanjut →', 'wp-contentkit' ),
					),
					'bg_color'     => array(
						'type'    => 'color',
						'label'   => __( 'Warna Background', 'wp-contentkit' ),
						'default' => '#f8fafc',
					),
					'text_color'   => array(
						'type'    => 'color',
						'label'   => __( 'Warna Teks', 'wp-contentkit' ),
						'default' => '#1e293b',
					),
					'border_color' => array(
						'type'    => 'color',
						'label'   => __( 'Warna Border', 'wp-contentkit' ),
						'default' => '#cbd5e1',
					),
					'border_width' => array(
						'type'    => 'number',
						'label'   => __( 'Tebal Border (px)', 'wp-contentkit' ),
						'default' => '2',
					),
					'border_style' => array(
						'type'    => 'select',
						'label'   => __( 'Gaya Border', 'wp-contentkit' ),
						'default' => 'solid',
						'options' => array(
							'solid'  => __( 'Solid (Lurus)', 'wp-contentkit' ),
							'dashed' => __( 'Dashed (Putus-putus)', 'wp-contentkit' ),
							'dotted' => __( 'Dotted (Titik-titik)', 'wp-contentkit' ),
							'double' => __( 'Double (Ganda)', 'wp-contentkit' ),
							'none'   => __( 'None (Tanpa Border)', 'wp-contentkit' ),
						),
					),
					'border_radius' => array(
						'type'    => 'number',
						'label'   => __( 'Radius Sudut (px)', 'wp-contentkit' ),
						'default' => '10',
					),
					'padding'      => array(
						'type'    => 'number',
						'label'   => __( 'Padding Dalam (px)', 'wp-contentkit' ),
						'default' => '20',
					),
				),
			),
		);
	}

	/**
	 * Render template to pure Inline CSS HTML.
	 *
	 * @param string $template_id Template identifier.
	 * @param array  $data User submitted data.
	 * @return string Pure self-contained HTML.
	 */
	public static function render_inline_html( $template_id, $data = array() ) {
		switch ( $template_id ) {
			case 'important':
				$title    = ! empty( $data['title'] ) ? esc_html( $data['title'] ) : esc_html__( 'Poin Penting:', 'wp-contentkit' );
				$raw_list = ! empty( $data['items'] ) ? (string) $data['items'] : '';
				$lines    = array_filter( array_map( 'trim', explode( "\n", $raw_list ) ) );
				$bg       = ! empty( $data['bg_color'] ) ? sanitize_hex_color( $data['bg_color'] ) : '#f8fafc';
				$border   = ! empty( $data['border_color'] ) ? sanitize_hex_color( $data['border_color'] ) : '#2563eb';

				$list_html = '';
				if ( ! empty( $lines ) ) {
					$list_html .= '<ul style="margin: 10px 0 0 0; padding-left: 20px; list-style-type: disc; color: #334155; line-height: 1.6;">';
					foreach ( $lines as $line ) {
						$list_html .= '<li style="margin-bottom: 6px;">' . esc_html( $line ) . '</li>';
					}
					$list_html .= '</ul>';
				}

				return sprintf(
					'<div style="background: %1$s; border-left: 4px solid %2$s; padding: 18px 20px; border-radius: 8px; margin: 24px 0; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen-Sans, Ubuntu, Cantarell, \'Helvetica Neue\', sans-serif; box-sizing: border-box;">' .
						'<div style="font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 8px;">%3$s</div>' .
						'%4$s' .
					'</div>',
					esc_attr( $bg ),
					esc_attr( $border ),
					$title,
					$list_html
				);

			case 'author':
				$name       = ! empty( $data['name'] ) ? esc_html( $data['name'] ) : esc_html__( 'Nama Penulis', 'wp-contentkit' );
				$role       = ! empty( $data['role'] ) ? esc_html( $data['role'] ) : '';
				$bio        = ! empty( $data['bio'] ) ? esc_html( $data['bio'] ) : '';
				$avatar_url = ! empty( $data['avatar_url'] ) ? esc_url( $data['avatar_url'] ) : '';
				$link_text  = ! empty( $data['link_text'] ) ? esc_html( $data['link_text'] ) : '';
				$link_url   = ! empty( $data['link_url'] ) ? esc_url( $data['link_url'] ) : '';
				$bg         = ! empty( $data['bg_color'] ) ? sanitize_hex_color( $data['bg_color'] ) : '#ffffff';
				$border     = ! empty( $data['border_color'] ) ? sanitize_hex_color( $data['border_color'] ) : '#e2e8f0';

				$avatar_html = '';
				if ( ! empty( $avatar_url ) ) {
					$avatar_html = sprintf(
						'<div style="margin-right: 18px; flex-shrink: 0;"><img src="%1$s" alt="%2$s" style="width: 72px; height: 72px; border-radius: 50%%; object-fit: cover; border: 2px solid %3$s; display: block;" /></div>',
						$avatar_url,
						esc_attr( $name ),
						esc_attr( $border )
					);
				}

				$link_html = '';
				if ( ! empty( $link_url ) && ! empty( $link_text ) ) {
					$link_html = sprintf(
						'<p style="margin: 10px 0 0 0; font-size: 14px;"><a href="%1$s" target="_blank" rel="noopener noreferrer" style="color: #2563eb; text-decoration: underline; font-weight: 600;">%2$s &rarr;</a></p>',
						$link_url,
						$link_text
					);
				}

				return sprintf(
					'<div style="background: %1$s; border: 1px solid %2$s; padding: 22px; border-radius: 12px; margin: 24px 0; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen-Sans, Ubuntu, Cantarell, \'Helvetica Neue\', sans-serif; box-sizing: border-box;">' .
						'<div style="display: flex; align-items: center; flex-wrap: wrap;">' .
							'%3$s' .
							'<div style="flex: 1; min-width: 200px;">' .
								'<h4 style="margin: 0 0 4px 0; font-size: 18px; font-weight: 700; color: #0f172a;">%4$s</h4>' .
								( ! empty( $role ) ? '<div style="font-size: 13px; font-weight: 600; color: #64748b; margin-bottom: 8px;">' . $role . '</div>' : '' ) .
								( ! empty( $bio ) ? '<p style="margin: 0; font-size: 14px; color: #334155; line-height: 1.55;">' . $bio . '</p>' : '' ) .
								'%5$s' .
							'</div>' .
						'</div>' .
					'</div>',
					esc_attr( $bg ),
					esc_attr( $border ),
					$avatar_html,
					$name,
					$link_html
				);

			case 'reviewed_by':
				$reviewer_name = ! empty( $data['reviewer_name'] ) ? esc_html( $data['reviewer_name'] ) : esc_html__( 'Nama Reviewer', 'wp-contentkit' );
				$profession    = ! empty( $data['profession'] ) ? esc_html( $data['profession'] ) : '';
				$description   = ! empty( $data['description'] ) ? esc_html( $data['description'] ) : '';
				$link_text     = ! empty( $data['link_text'] ) ? esc_html( $data['link_text'] ) : '';
				$link_url      = ! empty( $data['link_url'] ) ? esc_url( $data['link_url'] ) : '';
				$bg            = ! empty( $data['bg_color'] ) ? sanitize_hex_color( $data['bg_color'] ) : '#fafafa';
				$border        = ! empty( $data['border_color'] ) ? sanitize_hex_color( $data['border_color'] ) : '#888888';

				$link_html = '';
				if ( ! empty( $link_url ) ) {
					$display_text = ! empty( $link_text ) ? $link_text : $link_url;
					$link_html = sprintf(
						'<p style="margin: 12px 0 0 0; font-size: 14px; color: #475569;">' . esc_html__( 'Lihat profil lengkap:', 'wp-contentkit' ) . ' <a href="%1$s" target="_blank" rel="noopener noreferrer" style="color: #2563eb; font-weight: 600; text-decoration: underline;">%2$s</a></p>',
						$link_url,
						esc_html( $display_text )
					);
				}

				return sprintf(
					'<div style="border: 2px dashed %1$s; padding: 20px; border-radius: 12px; background: %2$s; margin: 24px 0; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen-Sans, Ubuntu, Cantarell, \'Helvetica Neue\', sans-serif; box-sizing: border-box;">' .
						'<h4 style="margin: 0 0 4px 0; font-size: 17px; font-weight: 700; color: #1e293b;">' . esc_html__( 'Ditinjau Oleh:', 'wp-contentkit' ) . ' %3$s</h4>' .
						( ! empty( $profession ) ? '<strong style="font-size: 13px; color: #475569; display: block; margin-bottom: 8px;">%4$s</strong>' : '' ) .
						( ! empty( $description ) ? '<p style="margin: 0; font-size: 14px; color: #334155; line-height: 1.6;">%5$s</p>' : '' ) .
						'%6$s' .
					'</div>',
					esc_attr( $border ),
					esc_attr( $bg ),
					$reviewer_name,
					$profession,
					$description,
					$link_html
				);

			case 'related_content':
				$badge       = ! empty( $data['badge'] ) ? esc_html( $data['badge'] ) : esc_html__( 'Baca Juga:', 'wp-contentkit' );
				$title       = ! empty( $data['article_title'] ) ? esc_html( $data['article_title'] ) : '';
				$article_url = ! empty( $data['article_url'] ) ? esc_url( $data['article_url'] ) : '#';
				$bg          = ! empty( $data['bg_color'] ) ? sanitize_hex_color( $data['bg_color'] ) : '#eff6ff';
				$border      = ! empty( $data['border_color'] ) ? sanitize_hex_color( $data['border_color'] ) : '#bfdbfe';

				return sprintf(
					'<div style="background: %1$s; border: 1px solid %2$s; border-radius: 8px; padding: 14px 18px; margin: 20px 0; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen-Sans, Ubuntu, Cantarell, \'Helvetica Neue\', sans-serif; box-sizing: border-box; display: flex; align-items: baseline; flex-wrap: wrap; gap: 8px;">' .
						'<span style="background: #2563eb; color: #ffffff; font-size: 12px; font-weight: 700; padding: 3px 8px; border-radius: 4px; text-transform: uppercase; letter-spacing: 0.5px;">%3$s</span>' .
						'<a href="%4$s" style="color: #1e3a8a; font-size: 15px; font-weight: 600; text-decoration: underline; line-height: 1.4;">%5$s</a>' .
					'</div>',
					esc_attr( $bg ),
					esc_attr( $border ),
					$badge,
					$article_url,
					$title
				);

			case 'note':
				$title   = ! empty( $data['title'] ) ? esc_html( $data['title'] ) : esc_html__( 'Catatan:', 'wp-contentkit' );
				$content = ! empty( $data['content'] ) ? esc_html( $data['content'] ) : '';
				$bg      = ! empty( $data['bg_color'] ) ? sanitize_hex_color( $data['bg_color'] ) : '#fffbeb';
				$border  = ! empty( $data['border_color'] ) ? sanitize_hex_color( $data['border_color'] ) : '#f59e0b';

				return sprintf(
					'<div style="background: %1$s; border-left: 4px solid %2$s; padding: 16px 18px; border-radius: 6px; margin: 20px 0; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen-Sans, Ubuntu, Cantarell, \'Helvetica Neue\', sans-serif; box-sizing: border-box;">' .
						'<div style="font-weight: 700; color: #92400e; font-size: 15px; margin-bottom: 4px;">%3$s</div>' .
						'<div style="color: #78350f; font-size: 14px; line-height: 1.6;">%4$s</div>' .
					'</div>',
					esc_attr( $bg ),
					esc_attr( $border ),
					$title,
					$content
				);

			case 'warning':
				$title   = ! empty( $data['title'] ) ? esc_html( $data['title'] ) : esc_html__( 'Peringatan:', 'wp-contentkit' );
				$content = ! empty( $data['content'] ) ? esc_html( $data['content'] ) : '';
				$bg      = ! empty( $data['bg_color'] ) ? sanitize_hex_color( $data['bg_color'] ) : '#fef2f2';
				$border  = ! empty( $data['border_color'] ) ? sanitize_hex_color( $data['border_color'] ) : '#ef4444';

				return sprintf(
					'<div style="background: %1$s; border-left: 4px solid %2$s; padding: 16px 18px; border-radius: 6px; margin: 20px 0; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen-Sans, Ubuntu, Cantarell, \'Helvetica Neue\', sans-serif; box-sizing: border-box;">' .
						'<div style="font-weight: 700; color: #991b1b; font-size: 15px; margin-bottom: 4px;">%3$s</div>' .
						'<div style="color: #7f1d1d; font-size: 14px; line-height: 1.6;">%4$s</div>' .
					'</div>',
					esc_attr( $bg ),
					esc_attr( $border ),
					$title,
					$content
				);

			case 'simple_info':
				$title   = ! empty( $data['title'] ) ? esc_html( $data['title'] ) : esc_html__( 'Informasi Tambahan', 'wp-contentkit' );
				$content = ! empty( $data['content'] ) ? esc_html( $data['content'] ) : '';
				$bg      = ! empty( $data['bg_color'] ) ? sanitize_hex_color( $data['bg_color'] ) : '#f0fdf4';
				$border  = ! empty( $data['border_color'] ) ? sanitize_hex_color( $data['border_color'] ) : '#bbf7d0';

				return sprintf(
					'<div style="background: %1$s; border: 1px solid %2$s; padding: 16px 18px; border-radius: 8px; margin: 20px 0; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen-Sans, Ubuntu, Cantarell, \'Helvetica Neue\', sans-serif; box-sizing: border-box;">' .
						'<div style="font-weight: 700; color: #166534; font-size: 15px; margin-bottom: 4px;">%3$s</div>' .
						'<div style="color: #14532d; font-size: 14px; line-height: 1.6;">%4$s</div>' .
					'</div>',
					esc_attr( $bg ),
					esc_attr( $border ),
					$title,
					$content
				);

			case 'custom':
			default:
				$title         = ! empty( $data['title'] ) ? esc_html( $data['title'] ) : '';
				$content       = ! empty( $data['content'] ) ? nl2br( esc_html( $data['content'] ) ) : '';
				$link_url      = ! empty( $data['link_url'] ) ? esc_url( $data['link_url'] ) : '';
				$link_text     = ! empty( $data['link_text'] ) ? esc_html( $data['link_text'] ) : $link_url;
				$bg            = ! empty( $data['bg_color'] ) ? sanitize_hex_color( $data['bg_color'] ) : '#f8fafc';
				$text_color    = ! empty( $data['text_color'] ) ? sanitize_hex_color( $data['text_color'] ) : '#1e293b';
				$border_color  = ! empty( $data['border_color'] ) ? sanitize_hex_color( $data['border_color'] ) : '#cbd5e1';
				$border_width  = isset( $data['border_width'] ) ? max( 0, (int) $data['border_width'] ) : 2;
				$border_style  = ! empty( $data['border_style'] ) ? preg_replace( '/[^a-z]/', '', $data['border_style'] ) : 'solid';
				$border_radius = isset( $data['border_radius'] ) ? max( 0, (int) $data['border_radius'] ) : 10;
				$padding       = isset( $data['padding'] ) ? max( 0, (int) $data['padding'] ) : 20;

				$style = sprintf(
					'background: %1$s; color: %2$s; border: %3$dpx %4$s %5$s; border-radius: %6$dpx; padding: %7$dpx; margin: 24px 0; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen-Sans, Ubuntu, Cantarell, \'Helvetica Neue\', sans-serif; box-sizing: border-box;',
					esc_attr( $bg ),
					esc_attr( $text_color ),
					$border_width,
					esc_attr( $border_style ),
					esc_attr( $border_color ),
					$border_radius,
					$padding
				);

				$link_html = '';
				if ( ! empty( $link_url ) && ! empty( $link_text ) ) {
					$link_html = sprintf(
						'<p style="margin: 12px 0 0 0; font-size: 14px;"><a href="%1$s" target="_blank" rel="noopener noreferrer" style="color: %2$s; font-weight: 600; text-decoration: underline;">%3$s</a></p>',
						$link_url,
						esc_attr( $text_color ),
						$link_text
					);
				}

				return sprintf(
					'<div style="%1$s">' .
						( ! empty( $title ) ? '<h4 style="margin: 0 0 8px 0; font-size: 17px; font-weight: 700; color: ' . esc_attr( $text_color ) . ';">' . $title . '</h4>' : '' ) .
						( ! empty( $content ) ? '<div style="font-size: 14px; line-height: 1.6;">' . $content . '</div>' : '' ) .
						'%2$s' .
					'</div>',
					esc_attr( $style ),
					$link_html
				);
		}
	}
}
