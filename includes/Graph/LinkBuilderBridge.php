<?php
/**
 * Bridge to the sibling "Internal Link Builder" plugin.
 *
 * Internal Link Builder injects contextual internal links at render time by
 * buffering the finished front-end page (on `template_redirect`), so those
 * links never exist in the stored/rendered content our own indexer reads in a
 * background context — and would otherwise be invisible to the cockpit.
 *
 * It does, however, precompute the entire link graph into its own
 * `{prefix}ilb_links` table via cron, keyed by source/target object IDs. That
 * table is authoritative and readable from any context, so we fold its
 * post→post edges into our link view. This keeps incoming-link counts, orphan
 * detection, click-depth and the "mentioned but not linked" advisor honest
 * about links that only appear on the front end.
 *
 * The bridge is entirely optional: when the plugin (or its table) is absent it
 * returns empty sets and the cockpit behaves exactly as before.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Graph;

defined( 'ABSPATH' ) || exit;

/**
 * Read-only view over Internal Link Builder's precomputed link graph.
 */
final class LinkBuilderBridge {

	/**
	 * Memoised post→post edges for the current request, or null when not loaded.
	 *
	 * @var array<int,array{0:int,1:int}>|null
	 */
	private static $edges = null;

	/**
	 * Memoised table availability check (null = unknown).
	 *
	 * @var bool|null
	 */
	private static $available = null;

	/**
	 * Whether the integration is active for this request.
	 */
	public static function active(): bool {
		/**
		 * Allow disabling the Internal Link Builder integration entirely.
		 *
		 * @param bool $enabled Default true.
		 */
		if ( ! apply_filters( 'hgsd_use_link_builder', true ) ) {
			return false;
		}

		if ( null !== self::$available ) {
			return self::$available;
		}

		global $wpdb;
		$table = self::table();
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$found = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table ) );

		self::$available = ( $found === $table );
		return self::$available;
	}

	/**
	 * Post→post edges Internal Link Builder would inject on the front end.
	 *
	 * @return array<int,array{0:int,1:int}> [source_id, target_id] pairs.
	 */
	public static function edges(): array {
		if ( ! self::active() ) {
			return [];
		}
		if ( null !== self::$edges ) {
			return self::$edges;
		}

		global $wpdb;
		$table = self::table();
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$rows = $wpdb->get_results(
			"SELECT DISTINCT source_id, target_id FROM {$table}
			 WHERE source_type = 'post' AND target_type = 'post' AND source_id > 0 AND target_id > 0",
			ARRAY_N
		);

		self::$edges = array_map(
			static fn( $row ) => [ (int) $row[0], (int) $row[1] ],
			is_array( $rows ) ? $rows : []
		);
		return self::$edges;
	}

	/**
	 * Distinct post IDs that link to a target through Internal Link Builder.
	 *
	 * @return array<int,int>
	 */
	public static function sources_for( int $target_id ): array {
		$out = [];
		foreach ( self::edges() as $edge ) {
			if ( $edge[1] === $target_id ) {
				$out[ $edge[0] ] = $edge[0];
			}
		}
		return array_values( $out );
	}

	/**
	 * Incoming-link counts per target (distinct sources).
	 *
	 * @return array<int,int> target_id => count
	 */
	public static function inlink_counts(): array {
		$seen = [];
		foreach ( self::edges() as $edge ) {
			$seen[ $edge[1] ][ $edge[0] ] = true;
		}

		$out = [];
		foreach ( $seen as $target => $sources ) {
			$out[ $target ] = count( $sources );
		}
		return $out;
	}

	/**
	 * Clears the per-request memo (used by tests and after regeneration).
	 */
	public static function flush(): void {
		self::$edges     = null;
		self::$available = null;
	}

	/**
	 * Fully-qualified Internal Link Builder links table name.
	 */
	private static function table(): string {
		global $wpdb;
		return $wpdb->prefix . 'ilb_links';
	}
}
