<?php
/**
 * Google Search Console client (URL Inspection API).
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Gsc;

use WP_Error;

defined( 'ABSPATH' ) || exit;

/**
 * Inspects URLs through the Search Console API using a stored OAuth refresh
 * token, and caches the result per post — so the cockpit can show how Google
 * actually indexed each page (coverage, chosen canonical, last crawl).
 */
final class GscClient {

	public const OPTION       = 'hgsd_gsc_settings';
	public const META_RESULT  = '_hgsd_gsc';
	public const META_TIME    = '_hgsd_gsc_time';
	public const CRON_HOOK    = 'hgsd_gsc_batch';
	public const TOKEN_CACHE  = 'hgsd_gsc_token';
	private const TOKEN_URL   = 'https://oauth2.googleapis.com/token';
	private const INSPECT_URL = 'https://searchconsole.googleapis.com/v1/urlInspection/index:inspect';
	private const BATCH_SIZE  = 20;

	/**
	 * Settings merged with defaults.
	 *
	 * @return array{client_id:string,client_secret:string,refresh_token:string,property:string,batch:bool}
	 */
	public function settings(): array {
		$stored = get_option( self::OPTION, [] );
		$stored = is_array( $stored ) ? $stored : [];

		return [
			'client_id'     => (string) ( $stored['client_id'] ?? '' ),
			'client_secret' => (string) ( $stored['client_secret'] ?? '' ),
			'refresh_token' => (string) ( $stored['refresh_token'] ?? '' ),
			'property'      => (string) ( $stored['property'] ?? '' ),
			'batch'         => ! empty( $stored['batch'] ),
		];
	}

	/**
	 * Whether the connection is fully configured.
	 */
	public function configured(): bool {
		$s = $this->settings();
		return '' !== $s['client_id'] && '' !== $s['client_secret'] && '' !== $s['refresh_token'] && '' !== $s['property'];
	}

	public function register_hooks(): void {
		add_action( self::CRON_HOOK, [ $this, 'run_batch' ] );
	}

	/**
	 * Keep the hourly batch scheduled in line with the settings.
	 */
	public function sync_schedule(): void {
		$should = $this->configured() && $this->settings()['batch'];
		$next   = wp_next_scheduled( self::CRON_HOOK );

		if ( $should && ! $next ) {
			wp_schedule_event( time() + 60, 'hourly', self::CRON_HOOK );
		} elseif ( ! $should && $next ) {
			wp_unschedule_event( $next, self::CRON_HOOK );
		}
	}

	public static function unschedule(): void {
		$timestamp = wp_next_scheduled( self::CRON_HOOK );
		if ( $timestamp ) {
			wp_unschedule_event( $timestamp, self::CRON_HOOK );
		}
	}

	/**
	 * Inspect a post's URL and store the normalised result in post meta.
	 *
	 * @return array<string,mixed>|WP_Error
	 */
	public function inspect_and_store( int $post_id ) {
		$url = get_permalink( $post_id );
		if ( ! $url ) {
			return new WP_Error( 'hgsd_gsc_url', __( 'Post has no permalink.', 'hg-structured-data' ) );
		}

		$result = $this->inspect( (string) $url );
		if ( is_wp_error( $result ) ) {
			return $result;
		}

		update_post_meta( $post_id, self::META_RESULT, $result );
		update_post_meta( $post_id, self::META_TIME, time() );

		return $result;
	}

	/**
	 * Call the URL Inspection API for one URL.
	 *
	 * @return array{coverage:string,verdict:string,google_canonical:string,user_canonical:string,last_crawl:string}|WP_Error
	 */
	public function inspect( string $url ) {
		if ( ! $this->configured() ) {
			return new WP_Error( 'hgsd_gsc_unconfigured', __( 'Connect Search Console first.', 'hg-structured-data' ) );
		}

		$token = $this->access_token();
		if ( is_wp_error( $token ) ) {
			return $token;
		}

		$response = wp_remote_post(
			self::INSPECT_URL,
			[
				'timeout' => 20,
				'headers' => [
					'Authorization' => 'Bearer ' . $token,
					'Content-Type'  => 'application/json',
				],
				'body'    => wp_json_encode(
					[
						'inspectionUrl' => $url,
						'siteUrl'       => $this->settings()['property'],
					]
				),
			]
		);
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code = (int) wp_remote_retrieve_response_code( $response );
		$body = json_decode( (string) wp_remote_retrieve_body( $response ), true );
		if ( 200 !== $code ) {
			$message = $body['error']['message'] ?? (string) $code;
			return new WP_Error( 'hgsd_gsc_api', sprintf( /* translators: %s: API error. */ __( 'Search Console API error: %s', 'hg-structured-data' ), $message ) );
		}

		$status = $body['inspectionResult']['indexStatusResult'] ?? [];

		return [
			'coverage'         => (string) ( $status['coverageState'] ?? '' ),
			'verdict'          => (string) ( $status['verdict'] ?? '' ),
			'google_canonical' => (string) ( $status['googleCanonical'] ?? '' ),
			'user_canonical'   => (string) ( $status['userCanonical'] ?? '' ),
			'last_crawl'       => (string) ( $status['lastCrawlTime'] ?? '' ),
		];
	}

	/**
	 * Stored inspection result for a post.
	 *
	 * @return array<string,mixed>
	 */
	public static function result_for( int $post_id ): array {
		$stored = get_post_meta( $post_id, self::META_RESULT, true );
		return is_array( $stored ) ? $stored : [];
	}

	/**
	 * Whether Google chose a different canonical than the page declares.
	 */
	public static function canonical_mismatch( int $post_id, string $declared ): bool {
		$result = self::result_for( $post_id );
		$google = (string) ( $result['google_canonical'] ?? '' );
		if ( '' === $google ) {
			return false;
		}

		$expected = '' !== $declared ? $declared : (string) get_permalink( $post_id );

		return untrailingslashit( $google ) !== untrailingslashit( $expected );
	}

	/**
	 * Hourly batch: inspect the least-recently-checked published posts.
	 */
	public function run_batch(): void {
		if ( ! $this->configured() || ! $this->settings()['batch'] ) {
			return;
		}

		// Never-checked posts first.
		$ids = get_posts(
			[
				'post_type'      => \HelloGekko\StructuredData\ContentTypes::list(),
				'post_status'    => 'publish',
				'numberposts'    => self::BATCH_SIZE,
				'fields'         => 'ids',
				'no_found_rows'  => true,
				'meta_query'     => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					[
						'key'     => self::META_TIME,
						'compare' => 'NOT EXISTS',
					],
				],
			]
		);

		// Then the stalest ones.
		if ( count( $ids ) < self::BATCH_SIZE ) {
			$ids = array_merge(
				$ids,
				get_posts(
					[
						'post_type'     => \HelloGekko\StructuredData\ContentTypes::list(),
						'post_status'   => 'publish',
						'numberposts'   => self::BATCH_SIZE - count( $ids ),
						'fields'        => 'ids',
						'no_found_rows' => true,
						'meta_key'      => self::META_TIME, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
						'orderby'       => 'meta_value_num',
						'order'         => 'ASC',
					]
				)
			);
		}

		foreach ( array_unique( array_map( 'intval', $ids ) ) as $post_id ) {
			$result = $this->inspect_and_store( $post_id );
			if ( is_wp_error( $result ) ) {
				break; // Token/quota trouble — retry next hour.
			}
		}
	}

	/**
	 * Public accessor for a valid bearer token, shared with the Indexing API
	 * client (same OAuth connection, same cached token).
	 *
	 * @return string|WP_Error
	 */
	public function bearer_token() {
		return $this->access_token();
	}

	/**
	 * Exchange the refresh token for a short-lived access token (cached).
	 *
	 * @return string|WP_Error
	 */
	private function access_token() {
		$cached = get_transient( self::TOKEN_CACHE );
		if ( is_string( $cached ) && '' !== $cached ) {
			return $cached;
		}

		$s        = $this->settings();
		$response = wp_remote_post(
			self::TOKEN_URL,
			[
				'timeout' => 15,
				'body'    => [
					'client_id'     => $s['client_id'],
					'client_secret' => $s['client_secret'],
					'refresh_token' => $s['refresh_token'],
					'grant_type'    => 'refresh_token',
				],
			]
		);
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$body = json_decode( (string) wp_remote_retrieve_body( $response ), true );
		if ( empty( $body['access_token'] ) ) {
			$message = $body['error_description'] ?? ( $body['error'] ?? __( 'Could not refresh access token.', 'hg-structured-data' ) );
			return new WP_Error( 'hgsd_gsc_token', (string) $message );
		}

		$token = (string) $body['access_token'];
		set_transient( self::TOKEN_CACHE, $token, 45 * MINUTE_IN_SECONDS );

		return $token;
	}
}
