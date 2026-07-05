<?php
/**
 * Self-hosted update channel via GitHub Releases.
 *
 * WordPress only offers update notifications for plugins hosted on
 * wordpress.org. For a plugin distributed from GitHub we teach WordPress where
 * to look: we compare the installed version against the latest GitHub release
 * and, when newer, inject an update into the normal update transient so the
 * site shows the standard "Update available" notice and one-click update — and
 * lets the user enable auto-updates too.
 *
 * Dependency-free: it talks to the GitHub REST API directly and caches the
 * result so it never rate-limits or slows the dashboard.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Update;

defined( 'ABSPATH' ) || exit;

/**
 * Bridges GitHub Releases into WordPress's plugin-update machinery.
 */
final class Updater {

	private const CACHE_KEY = 'hgsd_update_release';
	private const CACHE_TTL = 6 * HOUR_IN_SECONDS;
	private const API_BASE  = 'https://api.github.com/repos/';

	private string $owner;
	private string $repo;
	private string $basename;
	private string $slug;
	private string $version;

	/**
	 * @param string $owner    GitHub owner/org, e.g. "HelloGekko".
	 * @param string $repo     Repository name, e.g. "Structured-Data".
	 * @param string $basename Plugin basename, e.g. "structured-data/structured-data.php".
	 * @param string $version  Installed version (HGSD_VERSION).
	 */
	public function __construct( string $owner, string $repo, string $basename, string $version ) {
		$this->owner    = $owner;
		$this->repo     = $repo;
		$this->basename = $basename;
		$this->slug     = dirname( $basename );
		$this->version  = $version;
	}

	public function register_hooks(): void {
		add_filter( 'pre_set_site_transient_update_plugins', [ $this, 'inject_update' ] );
		add_filter( 'plugins_api', [ $this, 'details' ], 10, 3 );
		add_filter( 'upgrader_source_selection', [ $this, 'rename_source' ], 10, 4 );
		add_action( 'upgrader_process_complete', [ $this, 'flush_after_update' ], 10, 2 );
	}

	/**
	 * Add our plugin to the list of available updates when GitHub has a newer
	 * release than the installed version.
	 *
	 * @param mixed $transient The update_plugins transient (object or false).
	 * @return mixed
	 */
	public function inject_update( $transient ) {
		if ( ! is_object( $transient ) ) {
			return $transient;
		}

		$release = $this->latest_release();
		if ( ! $release ) {
			return $transient;
		}

		$remote = $release['version'];
		$item   = (object) [
			'id'            => 'github.com/' . $this->owner . '/' . $this->repo,
			'slug'          => $this->slug,
			'plugin'        => $this->basename,
			'new_version'   => $remote,
			'url'           => $release['url'],
			'package'       => $release['package'],
			'icons'         => [],
			'banners'       => [],
			'tested'        => $release['tested'],
			'requires_php'  => $release['requires_php'],
			'requires'      => $release['requires'],
		];

		if ( version_compare( $remote, $this->version, '>' ) ) {
			if ( ! isset( $transient->response ) || ! is_array( $transient->response ) ) {
				$transient->response = [];
			}
			$transient->response[ $this->basename ] = $item;
		} else {
			// Advertise that we are up to date (enables the auto-update toggle).
			if ( ! isset( $transient->no_update ) || ! is_array( $transient->no_update ) ) {
				$transient->no_update = [];
			}
			$transient->no_update[ $this->basename ] = $item;
		}

		return $transient;
	}

	/**
	 * Provide the "View version details" popup content.
	 *
	 * @param mixed  $result Passed-through result (false by default).
	 * @param string $action The plugins_api action.
	 * @param object $args   Request arguments (has ->slug).
	 * @return mixed
	 */
	public function details( $result, $action, $args ) {
		if ( 'plugin_information' !== $action || empty( $args->slug ) || $args->slug !== $this->slug ) {
			return $result;
		}

		$release = $this->latest_release();
		if ( ! $release ) {
			return $result;
		}

		return (object) [
			'name'          => 'Structured data for WordPress',
			'slug'          => $this->slug,
			'version'       => $release['version'],
			'author'        => '<a href="https://hellogekko.nl">HelloGekko</a>',
			'homepage'      => 'https://hellogekko.nl/structured-data',
			'download_link' => $release['package'],
			'requires'      => $release['requires'],
			'requires_php'  => $release['requires_php'],
			'tested'        => $release['tested'],
			'last_updated'  => $release['date'],
			'sections'      => [
				'description' => esc_html__( 'Build perfect, schema.org-compliant structured data for WordPress, with an internal-link cockpit, Search Console and instant indexing.', 'hg-structured-data' ),
				'changelog'   => $release['changelog'],
			],
		];
	}

	/**
	 * GitHub's source zip unpacks to a hash-named folder (owner-repo-sha). Rename
	 * it to the plugin slug so WordPress installs it over the existing folder.
	 * A properly-named release asset needs no rename and passes straight through.
	 *
	 * @param string $source        Unpacked folder path.
	 * @param string $remote_source Temp root.
	 * @param object $upgrader      WP_Upgrader instance.
	 * @param array  $hook_extra    Contextual info.
	 * @return string|\WP_Error
	 */
	public function rename_source( $source, $remote_source, $upgrader, $hook_extra = [] ) {
		if ( empty( $hook_extra['plugin'] ) || $hook_extra['plugin'] !== $this->basename ) {
			return $source;
		}

		$desired = trailingslashit( $remote_source ) . $this->slug;
		if ( untrailingslashit( $source ) === untrailingslashit( $desired ) ) {
			return $source;
		}

		global $wp_filesystem;
		if ( $wp_filesystem && $wp_filesystem->move( untrailingslashit( $source ), untrailingslashit( $desired ), true ) ) {
			return trailingslashit( $desired );
		}

		return $source;
	}

	/**
	 * Clear the cached release right after an update so a follow-up check is
	 * accurate.
	 *
	 * @param object $upgrader WP_Upgrader instance.
	 * @param array  $data     Update context.
	 */
	public function flush_after_update( $upgrader, $data ): void {
		if ( ( $data['type'] ?? '' ) === 'plugin' ) {
			delete_transient( self::CACHE_KEY );
		}
	}

	/**
	 * Fetch (and cache) the latest GitHub release, normalised for our use.
	 *
	 * @return array{version:string,url:string,package:string,changelog:string,date:string,requires:string,requires_php:string,tested:string}|null
	 */
	private function latest_release(): ?array {
		$cached = get_transient( self::CACHE_KEY );
		if ( is_array( $cached ) ) {
			return $cached;
		}
		// Cache a negative result briefly so a hiccup doesn't hammer the API.
		if ( 'none' === $cached ) {
			return null;
		}

		$url      = self::API_BASE . rawurlencode( $this->owner ) . '/' . rawurlencode( $this->repo ) . '/releases/latest';
		$response = wp_remote_get(
			$url,
			[
				'timeout' => 8,
				'headers' => [
					'Accept'     => 'application/vnd.github+json',
					'User-Agent' => 'StructuredData-Updater',
				],
			]
		);

		if ( is_wp_error( $response ) || 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
			set_transient( self::CACHE_KEY, 'none', HOUR_IN_SECONDS );
			return null;
		}

		$body = json_decode( (string) wp_remote_retrieve_body( $response ), true );
		if ( ! is_array( $body ) || empty( $body['tag_name'] ) || ! empty( $body['draft'] ) ) {
			set_transient( self::CACHE_KEY, 'none', HOUR_IN_SECONDS );
			return null;
		}

		$release = [
			'version'      => ltrim( (string) $body['tag_name'], 'vV' ),
			'url'          => (string) ( $body['html_url'] ?? '' ),
			'package'      => $this->package_url( $body ),
			'changelog'    => $this->changelog( (string) ( $body['body'] ?? '' ) ),
			'date'         => (string) ( $body['published_at'] ?? '' ),
			'requires'     => '6.0',
			'requires_php' => '8.0',
			'tested'       => '6.5',
		];

		set_transient( self::CACHE_KEY, $release, self::CACHE_TTL );
		return $release;
	}

	/**
	 * Prefer an uploaded .zip release asset; fall back to GitHub's source zip.
	 *
	 * @param array<string,mixed> $body Decoded release payload.
	 */
	private function package_url( array $body ): string {
		foreach ( (array) ( $body['assets'] ?? [] ) as $asset ) {
			$name = (string) ( $asset['name'] ?? '' );
			if ( ! empty( $asset['browser_download_url'] ) && str_ends_with( strtolower( $name ), '.zip' ) ) {
				return (string) $asset['browser_download_url'];
			}
		}

		return (string) ( $body['zipball_url'] ?? '' );
	}

	/**
	 * Turn the release notes (Markdown) into a minimal HTML changelog.
	 */
	private function changelog( string $markdown ): string {
		$markdown = wp_strip_all_tags( $markdown );
		if ( '' === trim( $markdown ) ) {
			return esc_html__( 'See the GitHub release notes for details.', 'hg-structured-data' );
		}

		$lines = preg_split( '/\r\n|\r|\n/', $markdown ) ?: [];
		$out   = '';
		foreach ( $lines as $line ) {
			$line = trim( $line );
			if ( '' === $line ) {
				continue;
			}
			if ( preg_match( '/^[-*]\s+(.*)$/', $line, $m ) ) {
				$out .= '<li>' . esc_html( $m[1] ) . '</li>';
			} elseif ( preg_match( '/^#{1,6}\s+(.*)$/', $line, $m ) ) {
				$out .= '<h4>' . esc_html( $m[1] ) . '</h4>';
			} else {
				$out .= '<p>' . esc_html( $line ) . '</p>';
			}
		}

		return $out;
	}
}
