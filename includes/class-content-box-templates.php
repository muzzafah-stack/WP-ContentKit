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
}
