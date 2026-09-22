<?php
/**
 * GitHub Auto-Updater for WP ContentKit.
 *
 * @package WP_ContentKit
 */

namespace WP_ContentKit;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class GitHub_Updater
 */
class GitHub_Updater {

	/**
	 * Repository slug (owner/repo).
	 *
	 * @var string
	 */
	private $repo = 'muzzafah-stack/WP-ContentKit';

	/**
	 * Plugin basename.
	 *
	 * @var string
	 */
	private $basename;

	/**
	 * Current version.
	 *
	 * @var string
	 */
	private $version;

	/**
	 * Constructor.
	 *
	 * @param string $file Main plugin file path.
	 * @param string $version Current plugin version.
	 */
	public function __construct( $file, $version ) {
		$this->basename = plugin_basename( $file );
		$this->version  = $version;

		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_update' ) );
		add_filter( 'plugins_api', array( $this, 'plugin_info' ), 20, 3 );
		add_filter( 'upgrader_post_install', array( $this, 'post_install' ), 10, 3 );
	}

	/**
	 * Fetch latest release from GitHub API.
	 *
	 * @return object|false
	 */
	private function get_latest_release() {
		$transient_key = 'wpck_github_release';
		$cached        = get_transient( $transient_key );

		if ( false !== $cached ) {
			return $cached;
		}

		$url      = 'https://api.github.com/repos/' . $this->repo . '/releases/latest';
		$response = wp_remote_get( $url, array(
			'timeout'    => 10,
			'headers'    => array(
				'Accept'     => 'application/vnd.github.v3+json',
				'User-Agent' => 'WordPress/' . get_bloginfo( 'version' ) . '; WP-ContentKit',
			),
		) );

		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return false;
		}

		$body = json_decode( wp_remote_retrieve_body( $response ) );
		if ( empty( $body ) || ! isset( $body->tag_name ) ) {
			return false;
		}

		set_transient( $transient_key, $body, 12 * HOUR_IN_SECONDS );

		return $body;
	}

	/**
	 * Check update hook for WordPress update transient.
	 *
	 * @param object $transient Update plugins transient.
	 * @return object
	 */
	public function check_update( $transient ) {
		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		$release = $this->get_latest_release();
		if ( ! $release ) {
			return $transient;
		}

		$remote_version = ltrim( $release->tag_name, 'v' );

		if ( version_compare( $this->version, $remote_version, '<' ) ) {
			$download_url = isset( $release->zipball_url ) ? $release->zipball_url : '';
			if ( ! empty( $release->assets ) && is_array( $release->assets ) ) {
				foreach ( $release->assets as $asset ) {
					if ( 'application/zip' === $asset->content_type || substr( $asset->name, -4 ) === '.zip' ) {
						$download_url = $asset->browser_download_url;
						break;
					}
				}
			}

			$item = (object) array(
				'slug'        => 'wp-contentkit',
				'plugin'      => $this->basename,
				'new_version' => $remote_version,
				'url'         => 'https://github.com/' . $this->repo,
				'package'     => $download_url,
				'icons'       => array(),
				'banners'     => array(),
				'tested'      => '6.7',
				'requires'    => '5.8',
				'requires_php'=> '7.4',
			);

			$transient->response[ $this->basename ] = $item;
		}

		return $transient;
	}

	/**
	 * View Plugin Details modal data.
	 *
	 * @param false|object|array $result Default result.
	 * @param string             $action Action name.
	 * @param object             $args Arguments.
	 * @return object|false
	 */
	public function plugin_info( $result, $action, $args ) {
		if ( 'plugin_information' !== $action || ! isset( $args->slug ) || 'wp-contentkit' !== $args->slug ) {
			return $result;
		}

		$release = $this->get_latest_release();
		if ( ! $release ) {
			return $result;
		}

		$remote_version = ltrim( $release->tag_name, 'v' );

		return (object) array(
			'name'          => 'WP ContentKit',
			'slug'          => 'wp-contentkit',
			'version'       => $remote_version,
			'author'        => '<a href="https://www.hipnolink.com">Hipnolink Team Digital</a>',
			'homepage'      => 'https://github.com/' . $this->repo,
			'download_link' => $release->zipball_url,
			'sections'      => array(
				'description' => 'Smart Tools for Better Content. Lightweight Server-Side TOC for Elementor and portable Inline Content Box generator for Classic Editor.',
				'changelog'   => nl2br( esc_html( $release->body ) ),
			),
		);
	}

	/**
	 * Ensure the plugin unzips into correct folder name `wp-contentkit`.
	 *
	 * @param bool  $response Install response.
	 * @param array $hook_extra Extra hook arguments.
	 * @param array $result Install result data.
	 * @return array
	 */
	public function post_install( $response, $hook_extra, $result ) {
		global $wp_filesystem;

		if ( ! isset( $hook_extra['plugin'] ) || $hook_extra['plugin'] !== $this->basename ) {
			return $result;
		}

		$proper_destination = WP_PLUGIN_DIR . '/wp-contentkit/';
		$wp_filesystem->move( $result['destination'], $proper_destination );
		$result['destination'] = $proper_destination;

		return $result;
	}
}
