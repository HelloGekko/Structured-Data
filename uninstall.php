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
