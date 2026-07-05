<?php
/**
 * Google Indexing API client.
 *
 * Submits URLs to Google for (re)crawling through the Indexing API, reusing the
 * same OAuth connection as the Search Console integration. Google officially
 * scopes this API to JobPosting/BroadcastEvent, but in practice accepts any URL
 * — this mirrors the "instant indexing" feature shipped by the major SEO
 * plugins. A daily quota (default 200) is enforced locally and every call is
 * logged so the cockpit can show what happened.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Gsc;

use HelloGekko\StructuredData\ContentTypes;
use WP_Error;
use WP_Post;

defined( 'ABSPATH' ) || exit;

/**
 * Publishes URL notifications to the Google Indexing API.
 */
final class IndexingClient {

	public const OPTION       = 'hgsd_indexing_settings';
	public const STATE_OPTION = 'hgsd_indexing_state';
	public const QUEUE_OPTION = 'hgsd_indexing_queue';
	public const DRAIN_HOOK   = 'hgsd_indexing_drain';
	public const META_TIME    = '_hgsd_indexed_at';
	public const META_STATUS  = '_hgsd_indexed_status';

	private const PUBLISH_URL   = 'https://indexing.googleapis.com/v3/urlNotifications:publish';
	private const DEFAULT_QUOTA = 200;
	private const DRAIN_LIMIT   = 25;
	private const LOG_MAX       = 40;

	private GscClient $gsc;

	public function __construct( GscClient $gsc ) {
		$this->gsc = $gsc;
	}

	/**
	 * Settings merged with defaults.
	 *
	 * @return array{enabled:bool,auto:bool,quota:int,post_types:array<int,string>}
	 */
	public function settings(): array {
		$stored = get_option( self::OPTION, [] );
		$stored = is_array( $stored ) ? $stored : [];

		return [
			'enabled'    => ! empty( $stored['enabled'] ),
			'auto'       => ! isset( $stored['auto'] ) || ! empty( $stored['auto'] ),
			'quota'      => max( 1, (int) ( $stored['quota'] ?? self::DEFAULT_QUOTA ) ),
			'post_types' => is_array( $stored['post_types'] ?? null ) ? array_map( 'sanitize_key', $stored['post_types'] ) : ContentTypes::list(),
		];
	}

	/**
	 * Whether submission is switched on and the shared connection is usable.
	 */
	public function ready(): bool {
		return $this->settings()['enabled'] && $this->gsc->configured();
	}

	/**
	 * Clear any pending queue-drain event (called on deactivation).
	 */
	public static function unschedule(): void {
		$timestamp = wp_next_scheduled( self::DRAIN_HOOK );
		if ( $timestamp ) {
			wp_unschedule_event( $timestamp, self::DRAIN_HOOK );
		}
	}

	public function register_hooks(): void {
		add_action( self::DRAIN_HOOK, [ $this, 'drain_queue' ] );

		// Auto-submit on publish/update and on trashing (URL_DELETED).
		add_action( 'transition_post_status', [ $this, 'on_transition' ], 10, 3 );
	}

	/**
	 * Queue a URL when a supported post is published/updated or trashed.
	 *
	 * @param string  $new_status New post status.
	 * @param string  $old_status Previous post status.
	 * @param WP_Post $post       Post being transitioned.
	 */
	public function on_transition( string $new_status, string $old_status, WP_Post $post ): void {
		$settings = $this->settings();
		if ( ! $settings['enabled'] || ! $settings['auto'] ) {
			return;
		}
		if ( ! in_array( $post->post_type, $settings['post_types'], true ) ) {
			return;
		}
		if ( wp_is_post_revision( $post ) || wp_is_post_autosave( $post ) ) {
			return;
		}

		if ( 'publish' === $new_status ) {
			$this->enqueue( (int) $post->ID, 'URL_UPDATED' );
		} elseif ( 'publish' === $old_status && in_array( $new_status, [ 'trash', 'draft', 'pending', 'private' ], true ) ) {
			$this->enqueue( (int) $post->ID, 'URL_DELETED' );
		}
	}

	/**
	 * Add a post to the submission queue and schedule a near-immediate drain.
	 */
	public function enqueue( int $post_id, string $type = 'URL_UPDATED' ): void {
		$type  = 'URL_DELETED' === $type ? 'URL_DELETED' : 'URL_UPDATED';
		$queue = $this->queue();

		// De-duplicate: newest intent for a post wins.
		$queue[ $post_id ] = $type;
		update_option( self::QUEUE_OPTION, $queue, false );

		if ( ! wp_next_scheduled( self::DRAIN_HOOK ) ) {
			wp_schedule_single_event( time() + 30, self::DRAIN_HOOK );
		}
	}

	/**
	 * Process queued submissions, respecting the remaining daily quota.
	 */
	public function drain_queue(): void {
		if ( ! $this->ready() ) {
			return;
		}

		$queue = $this->queue();
		if ( empty( $queue ) ) {
			return;
		}

		$processed = 0;
		foreach ( $queue as $post_id => $type ) {
			if ( $processed >= self::DRAIN_LIMIT || $this->remaining_quota() < 1 ) {
				break;
			}

			$post_id = (int) $post_id;
			unset( $queue[ $post_id ] );
			update_option( self::QUEUE_OPTION, $queue, false );

			$this->submit_post( $post_id, (string) $type );
			++$processed;
		}

		// Anything still queued (quota/limit) gets picked up on the next run.
		if ( ! empty( $queue ) && ! wp_next_scheduled( self::DRAIN_HOOK ) ) {
			wp_schedule_single_event( time() + HOUR_IN_SECONDS, self::DRAIN_HOOK );
		}
	}

	/**
	 * Submit a single post's permalink. Records status in post meta and the log.
	 *
	 * @return true|WP_Error
	 */
	public function submit_post( int $post_id, string $type = 'URL_UPDATED' ) {
		$url = get_permalink( $post_id );
		if ( ! $url ) {
			return new WP_Error( 'hgsd_index_url', __( 'Post has no permalink.', 'hg-structured-data' ) );
		}

		$result = $this->submit( (string) $url, $type );

		update_post_meta( $post_id, self::META_TIME, time() );
		update_post_meta( $post_id, self::META_STATUS, is_wp_error( $result ) ? 'error' : 'ok' );

		return $result;
	}

	/**
	 * Publish one URL notification to the Indexing API.
	 *
	 * @param string $url  Absolute URL.
	 * @param string $type URL_UPDATED or URL_DELETED.
	 * @return true|WP_Error
	 */
	public function submit( string $url, string $type = 'URL_UPDATED' ) {
		if ( ! $this->settings()['enabled'] ) {
			return new WP_Error( 'hgsd_index_off', __( 'Instant indexing is switched off.', 'hg-structured-data' ) );
		}
		if ( ! $this->gsc->configured() ) {
			return new WP_Error( 'hgsd_index_unconfigured', __( 'Connect Search Console first.', 'hg-structured-data' ) );
		}
		if ( $this->remaining_quota() < 1 ) {
			return new WP_Error( 'hgsd_index_quota', __( 'Daily Google indexing quota reached — try again tomorrow.', 'hg-structured-data' ) );
		}

		$token = $this->gsc->bearer_token();
		if ( is_wp_error( $token ) ) {
			return $token;
		}

		$type     = 'URL_DELETED' === $type ? 'URL_DELETED' : 'URL_UPDATED';
		$response = wp_remote_post(
			self::PUBLISH_URL,
			[
				'timeout' => 20,
				'headers' => [
					'Authorization' => 'Bearer ' . $token,
					'Content-Type'  => 'application/json',
				],
				'body'    => wp_json_encode(
					[
						'url'  => $url,
						'type' => $type,
					]
				),
			]
		);
		if ( is_wp_error( $response ) ) {
			$this->log( $url, $type, 'error', $response->get_error_message() );
			return $response;
		}

		$code = (int) wp_remote_retrieve_response_code( $response );
		$body = json_decode( (string) wp_remote_retrieve_body( $response ), true );

		if ( 200 !== $code ) {
			$message = $body['error']['message'] ?? (string) $code;
			$this->log( $url, $type, 'error', (string) $message );
			return new WP_Error( 'hgsd_index_api', sprintf( /* translators: %s: API error. */ __( 'Indexing API error: %s', 'hg-structured-data' ), $message ) );
		}

		$this->count_submission();
		$this->log( $url, $type, 'ok', '' );

		return true;
	}

	/**
	 * Submissions still allowed today.
	 */
	public function remaining_quota(): int {
		return max( 0, $this->settings()['quota'] - $this->used_today() );
	}

	/**
	 * Submissions already made today.
	 */
	public function used_today(): int {
		$state = $this->state();
		return $state['date'] === $this->today() ? (int) $state['count'] : 0;
	}

	/**
	 * Recent submission log, newest first.
	 *
	 * @return array<int,array{url:string,type:string,status:string,message:string,time:int}>
	 */
	public function recent_log(): array {
		$state = $this->state();
		return is_array( $state['log'] ?? null ) ? $state['log'] : [];
	}

	/**
	 * Per-post submission summary for the cockpit panel.
	 *
	 * @return array{time:int,status:string}
	 */
	public static function status_for( int $post_id ): array {
		return [
			'time'   => (int) get_post_meta( $post_id, self::META_TIME, true ),
			'status' => (string) get_post_meta( $post_id, self::META_STATUS, true ),
		];
	}

	/**
	 * Current queue as post_id => type.
	 *
	 * @return array<int,string>
	 */
	private function queue(): array {
		$queue = get_option( self::QUEUE_OPTION, [] );
		if ( ! is_array( $queue ) ) {
			return [];
		}

		$out = [];
		foreach ( $queue as $id => $type ) {
			$out[ (int) $id ] = 'URL_DELETED' === $type ? 'URL_DELETED' : 'URL_UPDATED';
		}
		return $out;
	}

	/**
	 * Daily state (quota counter + rolling log), reset when the day rolls over.
	 *
	 * @return array{date:string,count:int,log:array<int,array<string,mixed>>}
	 */
	private function state(): array {
		$stored = get_option( self::STATE_OPTION, [] );
		$stored = is_array( $stored ) ? $stored : [];

		$date = (string) ( $stored['date'] ?? '' );
		return [
			'date'  => '' !== $date ? $date : $this->today(),
			'count' => $date === $this->today() ? (int) ( $stored['count'] ?? 0 ) : 0,
			'log'   => is_array( $stored['log'] ?? null ) ? $stored['log'] : [],
		];
	}

	/**
	 * Increment today's quota counter.
	 */
	private function count_submission(): void {
		$state = $this->state();
		update_option(
			self::STATE_OPTION,
			[
				'date'  => $this->today(),
				'count' => $state['count'] + 1,
				'log'   => $state['log'],
			],
			false
		);
	}

	/**
	 * Prepend an entry to the rolling submission log.
	 */
	private function log( string $url, string $type, string $status, string $message ): void {
		$state = $this->state();

		array_unshift(
			$state['log'],
			[
				'url'     => $url,
				'type'    => $type,
				'status'  => $status,
				'message' => $message,
				'time'    => time(),
			]
		);
		$state['log'] = array_slice( $state['log'], 0, self::LOG_MAX );

		update_option(
			self::STATE_OPTION,
			[
				'date'  => $state['date'],
				'count' => $state['count'],
				'log'   => $state['log'],
			],
			false
		);
	}

	/**
	 * Today's date key in the site timezone.
	 */
	private function today(): string {
		return (string) wp_date( 'Y-m-d' );
	}
}
