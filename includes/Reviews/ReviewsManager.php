<?php
/**
 * Orchestrates review providers, caching and scheduled syncing.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Reviews;

use HelloGekko\StructuredData\Reviews\Providers\BusinessProfileProvider;
use HelloGekko\StructuredData\Reviews\Providers\ManualProvider;
use HelloGekko\StructuredData\Reviews\Providers\PlacesProvider;
use WP_Error;

defined( 'ABSPATH' ) || exit;

/**
 * Single entry point for everything review-related: settings, the active
 * provider, the cached payload and the WP-Cron sync.
 */
final class ReviewsManager {

	public const OPTION_SETTINGS = 'hgsd_reviews_settings';
	public const OPTION_CACHE    = 'hgsd_reviews_cache';
	public const CRON_HOOK       = 'hgsd_sync_reviews';

	/**
	 * @var array<string,AbstractReviewProvider>
	 */
	private array $providers;

	public function __construct() {
		$this->providers = [];
		foreach ( [ new PlacesProvider(), new BusinessProfileProvider(), new ManualProvider() ] as $provider ) {
			$this->providers[ $provider->id() ] = $provider;
		}
	}

	public function register_hooks(): void {
		add_action( self::CRON_HOOK, [ $this, 'sync' ] );
		add_action( 'update_option_' . self::OPTION_SETTINGS, [ $this, 'reschedule' ], 10, 0 );
	}

	/**
	 * @return array<string,AbstractReviewProvider>
	 */
	public function providers(): array {
		return $this->providers;
	}

	public function provider( string $id ): ?AbstractReviewProvider {
		return $this->providers[ $id ] ?? null;
	}

	/**
	 * Current settings merged with defaults.
	 *
	 * @return array<string,mixed>
	 */
	public function settings(): array {
		$stored = get_option( self::OPTION_SETTINGS, [] );
		$stored = is_array( $stored ) ? $stored : [];

		return wp_parse_args(
			$stored,
			[
				'provider'         => 'manual',
				'places_api_key'   => '',
				'place_id'         => '',
				'bp_client_id'     => '',
				'bp_client_secret' => '',
				'bp_refresh_token' => '',
				'bp_location'      => '',
				'sync_interval'    => 'hourly',
				'manual_aggregate' => [ 'ratingValue' => '', 'reviewCount' => '' ],
				'manual_items'     => [],
			]
		);
	}

	/**
	 * The active provider based on the saved settings.
	 */
	public function active_provider(): AbstractReviewProvider {
		$id = (string) $this->settings()['provider'];
		return $this->providers[ $id ] ?? $this->providers['manual'];
	}

	/**
	 * Cached, normalised review payload used for output.
	 *
	 * @return array{aggregate:array<string,mixed>,items:array<int,array<string,mixed>>,fetched_at:int}
	 */
	public function data(): array {
		$cache = get_option( self::OPTION_CACHE, [] );
		$cache = is_array( $cache ) ? $cache : [];

		return [
			'aggregate'  => is_array( $cache['aggregate'] ?? null ) ? $cache['aggregate'] : [],
			'items'      => is_array( $cache['items'] ?? null ) ? $cache['items'] : [],
			'fetched_at' => (int) ( $cache['fetched_at'] ?? 0 ),
		];
	}

	/**
	 * Run a sync now: fetch from the active provider and cache the result.
	 *
	 * @return true|WP_Error
	 */
	public function sync() {
		$settings = $this->settings();
		$provider = $this->active_provider();

		$result = $provider->fetch( $settings );
		if ( is_wp_error( $result ) ) {
			return $result;
		}

		update_option(
			self::OPTION_CACHE,
			[
				'aggregate'  => $result['aggregate'],
				'items'      => $result['items'],
				'fetched_at' => time(),
				'provider'   => $provider->id(),
			],
			false
		);

		return true;
	}

	/**
	 * Ensure the cron event is scheduled at the configured interval.
	 */
	public function schedule(): void {
		$interval = (string) $this->settings()['sync_interval'];
		$schedules = wp_get_schedules();
		if ( ! isset( $schedules[ $interval ] ) ) {
			$interval = 'hourly';
		}

		if ( ! wp_next_scheduled( self::CRON_HOOK ) ) {
			wp_schedule_event( time() + 60, $interval, self::CRON_HOOK );
		}
	}

	/**
	 * Re-schedule when the interval changes.
	 */
	public function reschedule(): void {
		$this->unschedule();
		$this->schedule();
	}

	public function unschedule(): void {
		$timestamp = wp_next_scheduled( self::CRON_HOOK );
		if ( $timestamp ) {
			wp_unschedule_event( $timestamp, self::CRON_HOOK );
		}
	}
}
