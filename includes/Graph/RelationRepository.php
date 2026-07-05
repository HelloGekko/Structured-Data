<?php
/**
 * Storage for curated page-to-page relations.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Graph;

defined( 'ABSPATH' ) || exit;

/**
 * Relations are the *intended* structure of the site ("this article supports
 * that cornerstone"), stored separately from the crawled link index (the
 * *actual* structure). The delta between the two is what the cockpit flags.
 * Relation names are schema.org properties so they can be emitted verbatim.
 */
final class RelationRepository {

	/**
	 * Supported relation types: schema.org property => human label.
	 *
	 * @return array<string,string>
	 */
	public static function types(): array {
		return [
			'isPartOf' => __( 'Is part of (supports cornerstone)', 'hg-structured-data' ),
			'about'    => __( 'Is about', 'hg-structured-data' ),
			'mentions' => __( 'Mentions', 'hg-structured-data' ),
			'citation' => __( 'Cites', 'hg-structured-data' ),
		];
	}

	/**
	 * Relations declared on a source post.
	 *
	 * @return array<int,array{id:int,target_id:int,relation:string,title:string,url:string}>
	 */
	public function for_source( int $post_id ): array {
		global $wpdb;
		$table = Installer::relations_table();
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$rows = $wpdb->get_results(
			$wpdb->prepare( "SELECT id, target_id, relation FROM {$table} WHERE source_id = %d ORDER BY relation, id", $post_id )
		);

		$out = [];
		foreach ( $rows as $row ) {
			$out[] = [
				'id'        => (int) $row->id,
				'target_id' => (int) $row->target_id,
				'relation'  => (string) $row->relation,
				'title'     => (string) get_the_title( (int) $row->target_id ),
				'url'       => (string) get_permalink( (int) $row->target_id ),
			];
		}
		return $out;
	}

	/**
	 * Relations pointing *to* a post (reverse view).
	 *
	 * @return array<int,array{id:int,source_id:int,relation:string,title:string}>
	 */
	public function for_target( int $post_id ): array {
		global $wpdb;
		$table = Installer::relations_table();
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$rows = $wpdb->get_results(
			$wpdb->prepare( "SELECT id, source_id, relation FROM {$table} WHERE target_id = %d ORDER BY relation, id", $post_id )
		);

		$out = [];
		foreach ( $rows as $row ) {
			$out[] = [
				'id'        => (int) $row->id,
				'source_id' => (int) $row->source_id,
				'relation'  => (string) $row->relation,
				'title'     => (string) get_the_title( (int) $row->source_id ),
			];
		}
		return $out;
	}

	/**
	 * Add a relation (idempotent thanks to the unique key).
	 */
	public function add( int $source_id, int $target_id, string $relation ): bool {
		if ( ! isset( self::types()[ $relation ] ) || $source_id === $target_id || ! $source_id || ! $target_id ) {
			return false;
		}

		global $wpdb;
		$table = Installer::relations_table();

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$exists = $wpdb->get_var(
			$wpdb->prepare( "SELECT id FROM {$table} WHERE source_id = %d AND target_id = %d AND relation = %s", $source_id, $target_id, $relation )
		);
		if ( $exists ) {
			return true;
		}

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery
		return false !== $wpdb->insert(
			$table,
			[
				'source_id' => $source_id,
				'target_id' => $target_id,
				'relation'  => $relation,
			],
			[ '%d', '%d', '%s' ]
		);
	}

	/**
	 * Delete a relation by ID.
	 */
	public function delete( int $relation_id ): void {
		global $wpdb;
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$wpdb->delete( Installer::relations_table(), [ 'id' => $relation_id ] );
	}

	/**
	 * Whether an actual content link exists from source to target.
	 */
	public function link_exists( int $source_id, int $target_id ): bool {
		global $wpdb;
		$table = Installer::table();
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		return (bool) $wpdb->get_var(
			$wpdb->prepare( "SELECT id FROM {$table} WHERE source_id = %d AND target_id = %d LIMIT 1", $source_id, $target_id )
		);
	}

	/**
	 * All relations touching a post (both directions).
	 *
	 * @return array<int,array{source_id:int,target_id:int,relation:string}>
	 */
	public function touching( int $post_id ): array {
		global $wpdb;
		$table = Installer::relations_table();
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$rows = $wpdb->get_results(
			$wpdb->prepare( "SELECT source_id, target_id, relation FROM {$table} WHERE source_id = %d OR target_id = %d", $post_id, $post_id )
		);

		return array_map(
			static fn( $row ) => [
				'source_id' => (int) $row->source_id,
				'target_id' => (int) $row->target_id,
				'relation'  => (string) $row->relation,
			],
			$rows
		);
	}

	/**
	 * Relations between a set of posts.
	 *
	 * @param array<int,int> $ids Member post IDs.
	 * @return array<int,array{source_id:int,target_id:int,relation:string}>
	 */
	public function between( array $ids ): array {
		$ids = array_values( array_filter( array_map( 'intval', $ids ) ) );
		if ( count( $ids ) < 2 ) {
			return [];
		}

		global $wpdb;
		$table        = Installer::relations_table();
		$placeholders = implode( ',', array_fill( 0, count( $ids ), '%d' ) );

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$rows = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT source_id, target_id, relation FROM {$table} WHERE source_id IN ({$placeholders}) AND target_id IN ({$placeholders})",
				array_merge( $ids, $ids )
			)
		);

		return array_map(
			static fn( $row ) => [
				'source_id' => (int) $row->source_id,
				'target_id' => (int) $row->target_id,
				'relation'  => (string) $row->relation,
			],
			$rows
		);
	}

	/**
	 * All relations that have NO matching content link, with both IDs.
	 *
	 * @return array<int,array{source_id:int,target_id:int,relation:string}>
	 */
	public function missing_pairs(): array {
		global $wpdb;
		$relations = Installer::relations_table();
		$links     = Installer::table();

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$rows = $wpdb->get_results(
			"SELECT r.source_id, r.target_id, r.relation
			 FROM {$relations} r
			 LEFT JOIN {$links} l ON l.source_id = r.source_id AND l.target_id = r.target_id
			 WHERE l.id IS NULL"
		);

		return array_map(
			static fn( $row ) => [
				'source_id' => (int) $row->source_id,
				'target_id' => (int) $row->target_id,
				'relation'  => (string) $row->relation,
			],
			$rows
		);
	}

	/**
	 * Per-source count of relations that have NO matching content link — the
	 * "intended but not built" delta shown as a flag in the cockpit.
	 *
	 * @return array<int,int> source_id => missing count
	 */
	public function missing_link_counts(): array {
		global $wpdb;
		$relations = Installer::relations_table();
		$links     = Installer::table();

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$rows = $wpdb->get_results(
			"SELECT r.source_id, COUNT(*) AS n
			 FROM {$relations} r
			 LEFT JOIN {$links} l ON l.source_id = r.source_id AND l.target_id = r.target_id
			 WHERE l.id IS NULL
			 GROUP BY r.source_id"
		);

		$out = [];
		foreach ( $rows as $row ) {
			$out[ (int) $row->source_id ] = (int) $row->n;
		}
		return $out;
	}
}
