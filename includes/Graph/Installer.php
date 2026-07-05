<?php
/**
 * Creates and upgrades the link-index table.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Graph;

defined( 'ABSPATH' ) || exit;

/**
 * Owns the hgsd_links table and kicks off the initial background index. Runs
 * on plugins_loaded so plugin-folder updates (without reactivation) install too.
 */
final class Installer {

	public const DB_VERSION     = '4';
	public const OPTION_VERSION = 'hgsd_db_version';
	public const OPTION_POINTER = 'hgsd_index_pointer';
	public const OPTION_INDEXED = 'hgsd_indexed_at';
	public const CRON_HOOK      = 'hgsd_index_batch';

	/**
	 * Link-index table name including the WP prefix.
	 */
	public static function table(): string {
		global $wpdb;
		return $wpdb->prefix . 'hgsd_links';
	}

	/**
	 * Relations table name including the WP prefix.
	 */
	public static function relations_table(): string {
		global $wpdb;
		return $wpdb->prefix . 'hgsd_relations';
	}

	/**
	 * Plain-text content table name (for mention-based suggestions).
	 */
	public static function content_table(): string {
		global $wpdb;
		return $wpdb->prefix . 'hgsd_content';
	}

	/**
	 * Install or upgrade when the stored version is stale.
	 */
	public static function maybe_install(): void {
		if ( get_option( self::OPTION_VERSION ) === self::DB_VERSION ) {
			return;
		}

		global $wpdb;
		$table           = self::table();
		$charset_collate = $wpdb->get_charset_collate();

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		dbDelta(
			"CREATE TABLE {$table} (
				id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				source_id bigint(20) unsigned NOT NULL DEFAULT 0,
				target_id bigint(20) unsigned NOT NULL,
				anchor varchar(191) NOT NULL DEFAULT '',
				context varchar(20) NOT NULL DEFAULT 'content',
				PRIMARY KEY  (id),
				KEY source_id (source_id),
				KEY target_id (target_id)
			) {$charset_collate};"
		);

		$relations = self::relations_table();
		dbDelta(
			"CREATE TABLE {$relations} (
				id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				source_id bigint(20) unsigned NOT NULL,
				target_id bigint(20) unsigned NOT NULL,
				relation varchar(32) NOT NULL DEFAULT 'isPartOf',
				PRIMARY KEY  (id),
				UNIQUE KEY rel (source_id,target_id,relation),
				KEY target_id (target_id)
			) {$charset_collate};"
		);

		$content = self::content_table();
		dbDelta(
			"CREATE TABLE {$content} (
				post_id bigint(20) unsigned NOT NULL,
				txt mediumtext,
				PRIMARY KEY  (post_id)
			) {$charset_collate};"
		);

		update_option( self::OPTION_VERSION, self::DB_VERSION );

		self::start_full_index();
	}

	/**
	 * (Re)start the background full-site index.
	 */
	public static function start_full_index(): void {
		update_option( self::OPTION_POINTER, 0, false );
		if ( ! wp_next_scheduled( self::CRON_HOOK ) ) {
			wp_schedule_single_event( time() + 10, self::CRON_HOOK );
		}
	}

	/**
	 * Remove the scheduled batch event.
	 */
	public static function unschedule(): void {
		$timestamp = wp_next_scheduled( self::CRON_HOOK );
		if ( $timestamp ) {
			wp_unschedule_event( $timestamp, self::CRON_HOOK );
		}
	}
}
