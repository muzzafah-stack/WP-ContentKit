<?php
/**
 * Heading Hierarchy Validator for WordPress & Elementor.
 *
 * @package WP_ContentKit
 */

namespace WP_ContentKit;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Heading_Validator
 */
class Heading_Validator {

	/**
	 * Status Constants.
	 */
	const STATUS_VALID   = 'valid';
	const STATUS_WARNING = 'warning';
	const STATUS_PROBLEM = 'problem';

	/**
	 * Validate heading list.
	 *
	 * @param array $headings Array of parsed headings.
	 * @return array Validation report.
	 */
	public static function validate( $headings = array() ) {
		if ( empty( $headings ) ) {
			return array(
				'status'       => self::STATUS_VALID,
				'status_label' => __( 'Belum ada heading untuk divalidasi.', 'wp-contentkit' ),
				'status_icon'  => 'info',
				'issues_count' => 0,
				'warnings'     => 0,
				'problems'     => 0,
				'issues'       => array(),
				'summary'      => __( 'Tambahkan heading H2-H6 di konten artikel Anda.', 'wp-contentkit' ),
			);
		}

		$issues      = array();
		$warnings    = 0;
		$problems    = 0;
		$prev_level  = 0;
		$first_found = false;

		foreach ( $headings as $index => $item ) {
			$level = (int) $item['level'];
			$tag   = 'H' . $level;
			$title = isset( $item['title'] ) ? $item['title'] : '';
			$pos   = isset( $item['index'] ) ? $item['index'] : ( $index + 1 );

			// Check 1: Empty heading text.
			if ( '' === trim( $title ) ) {
				$problems++;
				$issues[] = array(
					'type'        => self::STATUS_PROBLEM,
					'heading_idx' => $pos,
					'tag'         => $tag,
					'title'       => __( '(Heading Kosong)', 'wp-contentkit' ),
					'reason'      => sprintf( __( 'Heading urutan ke-%d tidak memiliki teks judul.', 'wp-contentkit' ), $pos ),
					'suggestion'  => __( 'Isi teks judul pada heading tersebut atau hapus tag heading yang kosong.', 'wp-contentkit' ),
				);
				continue;
			}

			// Check 2: First heading level.
			if ( ! $first_found ) {
				$first_found = true;
				if ( $level > 2 ) {
					$warnings++;
					$issues[] = array(
						'type'        => self::STATUS_WARNING,
						'heading_idx' => $pos,
						'tag'         => $tag,
						'title'       => $title,
						'reason'      => sprintf( __( 'Heading pertama artikel adalah %s, bukan H2.', 'wp-contentkit' ), $tag ),
						'suggestion'  => __( 'Awali bab pertama artikel Anda dengan H2 agar struktur hierarki SEO lebih ideal.', 'wp-contentkit' ),
					);
				}
				$prev_level = $level;
				continue;
			}

			// Check 3: Skipped heading levels (e.g. H2 -> H4).
			if ( $level > ( $prev_level + 1 ) ) {
				$jump = $level - $prev_level;
				$warnings++;
				$issues[] = array(
					'type'        => self::STATUS_WARNING,
					'heading_idx' => $pos,
					'tag'         => $tag,
					'title'       => $title,
					'reason'      => sprintf( __( 'Tingkatan heading melompat dari H%1$d langsung ke %2$s tanpa perantara (loncat %3$d level).', 'wp-contentkit' ), $prev_level, $tag, $jump ),
					'suggestion'  => sprintf( __( 'Gunakan H%d terlebih dahulu sebelum masuk ke sub-bab yang lebih spesifik.', 'wp-contentkit' ), $prev_level + 1 ),
				);
			}

			$prev_level = $level;
		}

		// Determine overall status.
		$overall_status = self::STATUS_VALID;
		$status_label   = __( '✓ Struktur heading baik', 'wp-contentkit' );

		if ( $problems > 0 ) {
			$overall_status = self::STATUS_PROBLEM;
			$status_label   = __( '! Ditemukan masalah struktur heading', 'wp-contentkit' );
		} elseif ( $warnings > 0 ) {
			$overall_status = self::STATUS_WARNING;
			$status_label   = __( '⚠ Struktur heading perlu diperiksa', 'wp-contentkit' );
		}

		return array(
			'status'       => $overall_status,
			'status_label' => $status_label,
			'total'        => count( $headings ),
			'issues_count' => count( $issues ),
			'warnings'     => $warnings,
			'problems'     => $problems,
			'issues'       => $issues,
			'summary'      => self::get_status_summary( $overall_status, count( $issues ) ),
		);
	}

	/**
	 * Helper for summary message.
	 *
	 * @param string $status Status key.
	 * @param int    $count Issue count.
	 * @return string Summary explanation.
	 */
	private static function get_status_summary( $status, $count ) {
		switch ( $status ) {
			case self::STATUS_VALID:
				return __( 'Semua heading tersusun rapi secara hierarkis (H2 → H3 → H4). Struktur ini sangat ramah untuk pembaca dan SEO.', 'wp-contentkit' );
			case self::STATUS_WARNING:
				return sprintf( _n( 'Ditemukan %d catatan hierarki yang bisa dioptimasi agar alur pembacaan lebih konsisten.', 'Ditemukan %d catatan hierarki yang bisa dioptimasi agar alur pembacaan lebih konsisten.', $count, 'wp-contentkit' ), $count );
			case self::STATUS_PROBLEM:
			default:
				return sprintf( _n( 'Ditemukan %d masalah struktur yang disarankan untuk diperbaiki.', 'Ditemukan %d masalah struktur yang disarankan untuk diperbaiki.', $count, 'wp-contentkit' ), $count );
		}
	}
}
