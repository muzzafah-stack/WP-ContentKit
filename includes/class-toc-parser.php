<?php
/**
 * Server-side Table of Contents Parser and Heading Injector.
 *
 * @package WP_ContentKit
 */

namespace WP_ContentKit;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class TOC_Parser
 */
class TOC_Parser {

	/**
	 * Unique ID registry for collision avoidance per request.
	 *
	 * @var array
	 */
	private $used_ids = array();

	/**
	 * Configuration options.
	 *
	 * @var array
	 */
	private $config = array();

	/**
	 * Constructor.
	 *
	 * @param array $config Configuration parameters.
	 */
	public function __construct( $config = array() ) {
		$defaults = array(
			'allowed_levels' => array( 'h2', 'h3', 'h4', 'h5', 'h6' ),
			'min_headings'   => 2,
			'id_prefix'      => '',
		);

		$this->config = wp_parse_args( $config, $defaults );
	}

	/**
	 * Reset ID registry.
	 */
	public function reset() {
		$this->used_ids = array();
	}

	/**
	 * Parse headings from content.
	 *
	 * @param string $content HTML content.
	 * @return array Parsed headings list.
	 */
	public function extract_headings( $content ) {
		$this->reset();

		if ( empty( $content ) ) {
			return array();
		}

		$allowed = array_map( 'strtolower', (array) $this->config['allowed_levels'] );
		$levels_pattern = implode( '', array_map( function( $h ) {
			return substr( $h, 1 ); // '23456'
		}, $allowed ) );

		if ( empty( $levels_pattern ) ) {
			return array();
		}

		// Regex to match <h2 ...>Content</h2> with support for attributes and multi-line.
		$pattern = '/<h([' . $levels_pattern . '])([^>]*)>(.*?)<\/h\1>/isu';

		if ( ! preg_match_all( $pattern, $content, $matches, PREG_SET_ORDER ) ) {
			return array();
		}

		$headings = array();
		$index    = 0;

		foreach ( $matches as $match ) {
			$index++;
			$level      = (int) $match[1];
			$attributes = $match[2];
			$raw_text   = $match[3];
			$clean_text = trim( html_entity_decode( wp_strip_all_tags( $raw_text ), ENT_QUOTES, 'UTF-8' ) );

			if ( '' === $clean_text ) {
				continue;
			}

			// Check for existing ID in attributes.
			$existing_id = '';
			if ( preg_match( '/\bid=[\'"]([^\'"]+)[\'"]/i', $attributes, $id_match ) ) {
				$existing_id = sanitize_title( $id_match[1] );
			}

			$id = $this->generate_unique_id( $existing_id ?: $clean_text );

			$headings[] = array(
				'index'        => $index,
				'level'        => $level,
				'tag'          => 'h' . $level,
				'title'        => $clean_text,
				'id'           => $id,
				'has_orig_id'  => ! empty( $existing_id ),
				'raw_heading'  => $match[0],
			);
		}

		return $headings;
	}

	/**
	 * Inject anchor IDs into content headings.
	 *
	 * @param string $content HTML content.
	 * @param array  $headings Extracted headings array from extract_headings().
	 * @return string Modified HTML content with IDs injected into headings.
	 */
	public function inject_anchors( $content, &$headings = array() ) {
		$this->reset();

		if ( empty( $content ) ) {
			return $content;
		}

		$allowed = array_map( 'strtolower', (array) $this->config['allowed_levels'] );
		$levels_pattern = implode( '', array_map( function( $h ) {
			return substr( $h, 1 );
		}, $allowed ) );

		if ( empty( $levels_pattern ) ) {
			return $content;
		}

		$pattern = '/<h([' . $levels_pattern . '])([^>]*)>(.*?)<\/h\1>/isu';
		$index   = 0;
		$headings = array();

		$modified_content = preg_replace_callback( $pattern, function( $match ) use ( &$index, &$headings ) {
			$index++;
			$level      = (int) $match[1];
			$attributes = $match[2];
			$raw_text   = $match[3];
			$clean_text = trim( html_entity_decode( wp_strip_all_tags( $raw_text ), ENT_QUOTES, 'UTF-8' ) );

			if ( '' === $clean_text ) {
				return $match[0];
			}

			$existing_id = '';
			$has_id      = false;

			if ( preg_match( '/\bid=[\'"]([^\'"]+)[\'"]/i', $attributes, $id_match ) ) {
				$existing_id = sanitize_title( $id_match[1] );
				$has_id      = true;
				$id          = $this->generate_unique_id( $existing_id );

				// Replace old ID if duplicate resolution altered it.
				if ( $id !== $existing_id ) {
					$attributes = preg_replace( '/\bid=[\'"][^\'"]+[\'"]/i', 'id="' . esc_attr( $id ) . '"', $attributes );
				}
			} else {
				$id         = $this->generate_unique_id( $clean_text );
				$attributes = rtrim( $attributes ) . ' id="' . esc_attr( $id ) . '"';
			}

			$headings[] = array(
				'index'       => $index,
				'level'       => $level,
				'tag'         => 'h' . $level,
				'title'       => $clean_text,
				'id'          => $id,
				'has_orig_id' => $has_id,
			);

			return sprintf( '<h%1$d%2$s>%3$s</h%1$d>', $level, $attributes, $raw_text );
		}, $content );

		return $modified_content;
	}

	/**
	 * Generate a unique and slugified ID for heading.
	 *
	 * @param string $source String to convert to slug ID.
	 * @return string Unique ID.
	 */
	public function generate_unique_id( $source ) {
		$prefix = ! empty( $this->config['id_prefix'] ) ? sanitize_title( $this->config['id_prefix'] ) . '-' : '';
		$slug   = sanitize_title( $source );

		if ( empty( $slug ) ) {
			$slug = 'section';
		}

		$base_id = $prefix . $slug;
		$id      = $base_id;
		$counter = 2;

		while ( in_array( $id, $this->used_ids, true ) ) {
			$id = $base_id . '-' . $counter;
			$counter++;
		}

		$this->used_ids[] = $id;

		return $id;
	}

	/**
	 * Build hierarchical tree from flat heading array.
	 *
	 * @param array $headings Flat headings list.
	 * @return array Hierarchical nested array.
	 */
	public static function build_hierarchy_tree( $headings ) {
		if ( empty( $headings ) ) {
			return array();
		}

		$tree = array();
		$stack = array();

		foreach ( $headings as $heading ) {
			$item = array(
				'index'    => $heading['index'],
				'level'    => $heading['level'],
				'tag'      => $heading['tag'],
				'title'    => $heading['title'],
				'id'       => $heading['id'],
				'children' => array(),
			);

			while ( ! empty( $stack ) && end( $stack )['level'] >= $heading['level'] ) {
				array_pop( $stack );
			}

			if ( empty( $stack ) ) {
				$tree[] = &$item;
				$stack[] = &$item;
			} else {
				$parent = &$stack[ count( $stack ) - 1 ];
				$parent['children'][] = &$item;
				$stack[] = &$item;
			}
			unset( $item );
		}

		return $tree;
	}
}
