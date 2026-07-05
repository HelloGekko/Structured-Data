<?php
/**
 * Uninstall routine: remove all schema definitions created by the plugin.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

$hgsd_posts = get_posts(
	[
		'post_type'   => 'hgsd_schema',
		'post_status' => 'any',
		'numberposts' => -1,
		'fields'      => 'ids',
	]
);

foreach ( $hgsd_posts as $hgsd_post_id ) {
	wp_delete_post( (int) $hgsd_post_id, true );
}

// Options and transients.
foreach ( [ 'hgsd_reviews_settings', 'hgsd_reviews_cache', 'hgsd_conflict_settings', 'hgsd_ai_settings', 'hgsd_db_version', 'hgsd_index_pointer', 'hgsd_indexed_at', 'hgsd_gsc_settings', 'hgsd_tips_dismissed', 'hgsd_tips_settings', 'hgsd_indexing_settings', 'hgsd_indexing_state', 'hgsd_indexing_queue' ] as $hgsd_option ) {
	delete_option( $hgsd_option );
}
delete_transient( 'hgsd_llms_txt' );
delete_transient( 'hgsd_graph_metrics' );
delete_transient( 'hgsd_gsc_token' );
delete_transient( 'hgsd_update_release' );

// Link-index, relations and content tables.
global $wpdb;
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}hgsd_links" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}hgsd_relations" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}hgsd_content" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared

// Per-post Search Console snapshots.
delete_post_meta_by_key( '_hgsd_gsc' );
delete_post_meta_by_key( '_hgsd_gsc_time' );

// Per-post instant-indexing submission markers.
delete_post_meta_by_key( '_hgsd_indexed_at' );
delete_post_meta_by_key( '_hgsd_indexed_status' );
