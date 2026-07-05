<?php
/**
 * Read access to the link index.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Graph;

defined( 'ABSPATH' ) || exit;

/**
 * Query helpers over the hgsd_links table.
 */
final class LinkRepository {

	/**
	 * Incoming link counts per target post (includes menu edges).
	 *
	 * @return array<int,int> target_id => count
	 */
	public function inlink_counts(): array {
		global $wpdb;
		$table = Installer::table();
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$rows = $wpdb->get_results( "SELECT target_id, COUNT(DISTINCT source_id) AS n FROM {$table} GROUP BY target_id" );

		$out = [];
		foreach ( $rows as $row ) {
			$out[ (int) $row->target_id ] = (int) $row->n;
		}
		return $out;
	}

	/**
	 * Outgoing content-link counts per source post (menu edges excluded).
	 *
	 * @return array<int,int> source_id => count
	 */
	public function outlink_counts(): array {
		global $wpdb;
		$table = Installer::table();
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$rows = $wpdb->get_results( "SELECT source_id, COUNT(DISTINCT target_id) AS n FROM {$table} WHERE source_id > 0 GROUP BY source_id" );

		$out = [];
		foreach ( $rows as $row ) {
			$out[ (int) $row->source_id ] = (int) $row->n;
		}
		return $out;
	}

	/**
	 * All distinct edges, for graph traversal.
	 *
	 * @return array<int,array{0:int,1:int}> [source, target] pairs.
	 */
	public function edges(): array {
		global $wpdb;
		$table = Installer::table();
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$rows = $wpdb->get_results( "SELECT DISTINCT source_id, target_id FROM {$table}", ARRAY_N );

		return array_map(
			static fn( $row ) => [ (int) $row[0], (int) $row[1] ],
			$rows
		);
	}

	/**
	 * Incoming links for one post, with source titles and anchors.
	 *
	 * @return array<int,array{post_id:int,title:string,anchor:string,context:string}>
	 */
	public function inlinks_for( int $post_id, int $limit = 20 ): array {
		global $wpdb;
		$table = Installer::table();
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$rows = $wpdb->get_results(
			$wpdb->prepare( "SELECT source_id, anchor, context FROM {$table} WHERE target_id = %d ORDER BY source_id LIMIT %d", $post_id, $limit )
		);

		return $this->hydrate( $rows, 'source_id' );
	}

	/**
	 * Outgoing links for one post, with target titles and anchors.
	 *
	 * @return array<int,array{post_id:int,title:string,anchor:string,context:string}>
	 */
	public function outlinks_for( int $post_id, int $limit = 20 ): array {
		global $wpdb;
		$table = Installer::table();
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$rows = $wpdb->get_results(
			$wpdb->prepare( "SELECT target_id, anchor, context FROM {$table} WHERE source_id = %d ORDER BY target_id LIMIT %d", $post_id, $limit )
		);

		return $this->hydrate( $rows, 'target_id' );
	}

	/**
	 * Attach post titles to raw link rows.
	 *
	 * @param array<int,object> $rows  Raw rows.
	 * @param string            $field Which column holds the related post ID.
	 * @return array<int,array{post_id:int,title:string,anchor:string,context:string}>
	 */
	private function hydrate( array $rows, string $field ): array {
		$out = [];
		foreach ( $rows as $row ) {
			$related = (int) $row->{$field};
			$out[]   = [
				'post_id' => $related,
				'title'   => 0 === $related ? __( 'Site / menu', 'hg-structured-data' ) : (string) get_the_title( $related ),
				'anchor'  => (string) $row->anchor,
				'context' => (string) $row->context,
			];
		}
		return $out;
	}
}
