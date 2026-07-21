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
	public const CANNIBAL_OPTION = 'hgsd_cannibalization';
	public const CANNIBAL_HOOK   = 'hgsd_gsc_cannibal';
	private const SEARCH_ANALYTICS = 'https://www.googleapis.com/webmasters/v3/sites/';
	private const TOKEN_URL   = 'https://oauth2.googleapis.com/token';
	private const INSPECT_URL = 'https://searchconsole.googleapis.com/v1/urlInspection/index:inspect';
	private const BATCH_SIZE  = 10;
	private const HTTP_TIMEOUT = 8;
	private const RUN_BUDGET   = 15;

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
		add_action( self::CANNIBAL_HOOK, [ $this, 'fetch_cannibalization' ] );
	}

	/**
	 * Keep the hourly batch and daily cannibalisation pull scheduled in line with
	 * the settings.
	 */
	public function sync_schedule(): void {
		$should = $this->configured() && $this->settings()['batch'];
		$next   = wp_next_scheduled( self::CRON_HOOK );

		if ( $should && ! $next ) {
			wp_schedule_event( time() + 60, 'hourly', self::CRON_HOOK );
		} elseif ( ! $should && $next ) {
			wp_unschedule_event( $next, self::CRON_HOOK );
		}

		// Cannibalisation only needs the connection, not the inspection batch.
		$cannibal_next = wp_next_scheduled( self::CANNIBAL_HOOK );
		if ( $this->configured() && ! $cannibal_next ) {
			wp_schedule_event( time() + 120, 'daily', self::CANNIBAL_HOOK );
		} elseif ( ! $this->configured() && $cannibal_next ) {
			wp_unschedule_event( $cannibal_next, self::CANNIBAL_HOOK );
		}
	}

	public static function unschedule(): void {
		foreach ( [ self::CRON_HOOK, self::CANNIBAL_HOOK ] as $hook ) {
			$timestamp = wp_next_scheduled( $hook );
			if ( $timestamp ) {
				wp_unschedule_event( $timestamp, $hook );
			}
		}
	}

	/**
	 * Query Search Console for queries where two or more of your own pages rank,
	 * i.e. keyword cannibalisation, and cache the result. Runs daily via cron and
	 * can be triggered on demand from the cockpit.
	 */
	public function fetch_cannibalization(): void {
		if ( ! $this->configured() ) {
			return;
		}

		$token = $this->access_token();
		if ( is_wp_error( $token ) ) {
			return;
		}

		$end   = (string) wp_date( 'Y-m-d', time() - 3 * DAY_IN_SECONDS ); // GSC data lags a few days.
		$start = (string) wp_date( 'Y-m-d', time() - 30 * DAY_IN_SECONDS );

		$response = wp_remote_post(
			self::SEARCH_ANALYTICS . rawurlencode( $this->settings()['property'] ) . '/searchAnalytics/query',
			[
				'timeout' => 15,
				'headers' => [
					'Authorization' => 'Bearer ' . $token,
					'Content-Type'  => 'application/json',
				],
				'body'    => wp_json_encode(
					[
						'startDate'  => $start,
						'endDate'    => $end,
						'dimensions' => [ 'query', 'page' ],
						'rowLimit'   => 5000,
						'type'       => 'web',
					]
				),
			]
		);
		if ( is_wp_error( $response ) || 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
			return;
		}

		$body = json_decode( (string) wp_remote_retrieve_body( $response ), true );
		$rows = is_array( $body['rows'] ?? null ) ? $body['rows'] : [];

		$by_query = [];
		foreach ( $rows as $row ) {
			$keys = $row['keys'] ?? [];
			if ( count( $keys ) < 2 ) {
				continue;
			}
			$impressions = (float) ( $row['impressions'] ?? 0 );
			$position    = (float) ( $row['position'] ?? 0 );
			// Only pages that actually surface in results for the query.
			if ( $impressions < 5 || $position > 20 || $position <= 0 ) {
				continue;
			}
			$by_query[ (string) $keys[0] ][] = [
				'url'         => (string) $keys[1],
				'impressions' => $impressions,
				'position'    => round( $position, 1 ),
			];
		}

		$items = [];
		foreach ( $by_query as $query => $pages ) {
			if ( count( $pages ) < 2 ) {
				continue; // Only one ranking page → no cannibalisation.
			}
			usort( $pages, static fn( $a, $b ) => $a['position'] <=> $b['position'] );
			$items[] = [
				'query'  => $query,
				'pages'  => array_slice( $pages, 0, 5 ),
				'weight' => array_sum( array_column( $pages, 'impressions' ) ),
			];
		}

		usort( $items, static fn( $a, $b ) => $b['weight'] <=> $a['weight'] );

		update_option(
			self::CANNIBAL_OPTION,
			[
				'time'  => time(),
				'items' => array_slice( $items, 0, 50 ),
			],
			false
		);
	}

	/**
	 * Cached cannibalisation items (query + competing pages), newest pull.
	 *
	 * @return array<int,array{query:string,pages:array<int,array<string,mixed>>,weight:float}>
	 */
	public static function cannibalization(): array {
		$stored = get_option( self::CANNIBAL_OPTION, [] );
		return is_array( $stored['items'] ?? null ) ? $stored['items'] : [];
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
				'timeout' => self::HTTP_TIMEOUT,
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

		$started = microtime( true );
		foreach ( array_unique( array_map( 'intval', $ids ) ) as $post_id ) {
			$result = $this->inspect_and_store( $post_id );
			if ( is_wp_error( $result ) ) {
				break; // Token/quota trouble — retry next hour.
			}

			// Never let one cron run hog a PHP worker: stop after the budget and
			// let the next hourly run pick up where we left off.
			if ( ( microtime( true ) - $started ) > self::RUN_BUDGET ) {
				break;
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
				'timeout' => 10,
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
