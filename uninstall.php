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
foreach ( [ 'hgsd_reviews_settings', 'hgsd_reviews_cache', 'hgsd_conflict_settings', 'hgsd_ai_settings', 'hgsd_db_version', 'hgsd_index_pointer', 'hgsd_indexed_at' ] as $hgsd_option ) {
	delete_option( $hgsd_option );
}
delete_transient( 'hgsd_llms_txt' );
delete_transient( 'hgsd_graph_metrics' );

// Link-index table.
global $wpdb;
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}hgsd_links" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
