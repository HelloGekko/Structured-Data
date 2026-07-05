<?php
/**
 * Derived link-graph metrics: click depth and orphan detection.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Graph;

defined( 'ABSPATH' ) || exit;

/**
 * Computes click depth (BFS from the home/menu node) over the link index.
 * Cached in a transient; flushed whenever the index changes.
 */
final class GraphMetrics {

	private const TRANSIENT = 'hgsd_graph_metrics';

	private LinkRepository $repository;

	public function __construct( LinkRepository $repository ) {
		$this->repository = $repository;
	}

	/**
	 * Click depth per post ID. Node 0 (home + menus) is depth 0; anything
	 * unreachable from there is absent (shown as "—" in the cockpit).
	 *
	 * @return array<int,int>
	 */
	public function depths(): array {
		$cached = get_transient( self::TRANSIENT );
		if ( is_array( $cached ) ) {
			return $cached;
		}

		$depths = self::bfs( $this->repository->edges(), (int) get_option( 'page_on_front' ) );
		set_transient( self::TRANSIENT, $depths, 15 * MINUTE_IN_SECONDS );

		return $depths;
	}

	/**
	 * Pure BFS over [source, target] edges from the virtual home node (0).
	 * The static front page, when set, is treated as depth 0 as well.
	 *
	 * @param array<int,array{0:int,1:int}> $edges Edge list.
	 * @return array<int,int> post_id => depth
	 */
	public static function bfs( array $edges, int $front_page_id ): array {
		$adjacency = [];
		foreach ( $edges as [ $source, $target ] ) {
			$adjacency[ $source ][] = $target;
		}

		$depths  = [];
		$queue   = [ [ 0, 0 ] ];
		$visited = [ 0 => true ];
		if ( $front_page_id > 0 ) {
			$depths[ $front_page_id ]  = 0;
			$visited[ $front_page_id ] = true;
			$queue[]                   = [ $front_page_id, 0 ];
		}
		while ( $queue ) {
			[ $node, $depth ] = array_shift( $queue );
			foreach ( $adjacency[ $node ] ?? [] as $next ) {
				if ( isset( $visited[ $next ] ) ) {
					continue;
				}
				$visited[ $next ] = true;
				$depths[ $next ]  = $depth + 1;
				$queue[]          = [ $next, $depth + 1 ];
			}
		}

		return $depths;
	}

	/**
	 * Whether a post is an orphan: published and reachable by no internal link
	 * or menu item (the front page is never an orphan).
	 *
	 * @param array<int,int> $inlinks Inlink counts (from LinkRepository).
	 */
	public static function is_orphan( int $post_id, array $inlinks ): bool {
		if ( $post_id === (int) get_option( 'page_on_front' ) ) {
			return false;
		}
		return empty( $inlinks[ $post_id ] );
	}

	/**
	 * Drop the cached metrics (index changed).
	 */
	public static function flush_cache(): void {
		delete_transient( self::TRANSIENT );
	}
}
