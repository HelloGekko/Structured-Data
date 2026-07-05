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

		// Fold in front-end links injected by Internal Link Builder, counting
		// each extra source only once per target.
		if ( LinkBuilderBridge::active() ) {
			$existing = $this->sources_by_target();
			foreach ( LinkBuilderBridge::edges() as $edge ) {
				[ $source, $target ] = $edge;
				if ( isset( $existing[ $target ][ $source ] ) ) {
					continue;
				}
				$existing[ $target ][ $source ] = true;
				$out[ $target ]                 = ( $out[ $target ] ?? 0 ) + 1;
			}
		}

		return $out;
	}

	/**
	 * Map of target_id => [source_id => true] from the native index, used to
	 * de-duplicate when merging Internal Link Builder edges.
	 *
	 * @return array<int,array<int,bool>>
	 */
	private function sources_by_target(): array {
		global $wpdb;
		$table = Installer::table();
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$rows = $wpdb->get_results( "SELECT DISTINCT source_id, target_id FROM {$table}", ARRAY_N );

		$map = [];
		foreach ( $rows as $row ) {
			$map[ (int) $row[1] ][ (int) $row[0] ] = true;
		}
		return $map;
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

		$edges = array_map(
			static fn( $row ) => [ (int) $row[0], (int) $row[1] ],
			$rows
		);

		// Include front-end links injected by Internal Link Builder so
		// click-depth, orphan detection and the cluster graph see them too.
		if ( LinkBuilderBridge::active() ) {
			$seen = [];
			foreach ( $edges as $edge ) {
				$seen[ $edge[0] . '-' . $edge[1] ] = true;
			}
			foreach ( LinkBuilderBridge::edges() as $edge ) {
				$key = $edge[0] . '-' . $edge[1];
				if ( ! isset( $seen[ $key ] ) ) {
					$seen[ $key ] = true;
					$edges[]      = $edge;
				}
			}
		}

		return $edges;
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
	 * Plain-text content snapshot of a post (from the index).
	 */
	public function text_for( int $post_id ): string {
		global $wpdb;
		$table = Installer::content_table();
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		return (string) $wpdb->get_var( $wpdb->prepare( "SELECT txt FROM {$table} WHERE post_id = %d", $post_id ) );
	}

	/**
	 * Distinct content-link edges between a set of posts.
	 *
	 * @param array<int,int> $ids Member post IDs.
	 * @return array<int,array{0:int,1:int}>
	 */
	public function edges_between( array $ids ): array {
		$ids = array_values( array_filter( array_map( 'intval', $ids ) ) );
		if ( count( $ids ) < 2 ) {
			return [];
		}

		global $wpdb;
		$table        = Installer::table();
		$placeholders = implode( ',', array_fill( 0, count( $ids ), '%d' ) );

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$rows = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT DISTINCT source_id, target_id FROM {$table} WHERE source_id IN ({$placeholders}) AND target_id IN ({$placeholders})",
				array_merge( $ids, $ids )
			),
			ARRAY_N
		);

		$edges = array_map( static fn( $row ) => [ (int) $row[0], (int) $row[1] ], $rows );

		if ( LinkBuilderBridge::active() ) {
			$set = array_flip( $ids );
			$seen = [];
			foreach ( $edges as $edge ) {
				$seen[ $edge[0] . '-' . $edge[1] ] = true;
			}
			foreach ( LinkBuilderBridge::edges() as $edge ) {
				if ( ! isset( $set[ $edge[0] ], $set[ $edge[1] ] ) ) {
					continue;
				}
				$key = $edge[0] . '-' . $edge[1];
				if ( ! isset( $seen[ $key ] ) ) {
					$seen[ $key ] = true;
					$edges[]      = $edge;
				}
			}
		}

		return $edges;
	}

	/**
	 * All posts linked to or from a post (content links only).
	 *
	 * @return array<int,int>
	 */
	public function neighbours( int $post_id, int $limit = 40 ): array {
		global $wpdb;
		$table = Installer::table();
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$rows = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT DISTINCT CASE WHEN source_id = %d THEN target_id ELSE source_id END
				 FROM {$table}
				 WHERE (source_id = %d OR target_id = %d) AND source_id > 0
				 LIMIT %d",
				$post_id,
				$post_id,
				$post_id,
				$limit
			)
		);

		return array_map( 'intval', $rows );
	}

	/**
	 * Published, public post IDs (for site-wide checks).
	 *
	 * @return array<int,int>
	 */
	public function all_published_ids(): array {
		global $wpdb;
		$types = \HelloGekko\StructuredData\ContentTypes::list();
		if ( empty( $types ) ) {
			return [];
		}
		$placeholders = implode( ',', array_fill( 0, count( $types ), '%s' ) );

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$ids = $wpdb->get_col(
			$wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_status = 'publish' AND post_type IN ({$placeholders})", $types )
		);

		return array_map( 'intval', $ids );
	}

	/**
	 * Posts whose indexed text mentions a phrase.
	 *
	 * @return array<int,int>
	 */
	public function mentioning_posts( string $phrase, int $limit = 50 ): array {
		if ( mb_strlen( $phrase ) < 4 ) {
			return [];
		}

		global $wpdb;
		$table = Installer::content_table();
		$like  = '%' . $wpdb->esc_like( $phrase ) . '%';

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$ids = $wpdb->get_col(
			$wpdb->prepare( "SELECT post_id FROM {$table} WHERE txt LIKE %s LIMIT %d", $like, $limit )
		);

		return array_map( 'intval', $ids );
	}

	/**
	 * Distinct posts that link to a target (content links).
	 *
	 * @return array<int,int>
	 */
	public function linking_sources( int $target_id ): array {
		global $wpdb;
		$table = Installer::table();
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$ids = $wpdb->get_col(
			$wpdb->prepare( "SELECT DISTINCT source_id FROM {$table} WHERE target_id = %d AND source_id > 0", $target_id )
		);

		$ids = array_map( 'intval', $ids );

		// A post that only links via Internal Link Builder's front-end
		// injection still counts as a linking source — this is what stops the
		// "mentioned but not linked" tip from firing on links that do exist.
		if ( LinkBuilderBridge::active() ) {
			$ids = array_values( array_unique( array_merge( $ids, LinkBuilderBridge::sources_for( $target_id ) ) ) );
		}

		return $ids;
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
