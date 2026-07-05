<?php
/**
 * Injects curated relations into a page's JSON-LD nodes.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Graph;

defined( 'ABSPATH' ) || exit;

/**
 * Turns stored relations into schema.org @id references on the page's primary
 * (page-bound) node — e.g. isPartOf: {"@id": "https://…/cornerstone/#service"}.
 */
final class RelationEmitter {

	/**
	 * Attach relations to the first page-bound node.
	 *
	 * @param array<int,array<string,mixed>>                    $nodes     The page's JSON-LD nodes.
	 * @param array<int,array{target_id:int,relation:string}>   $relations Stored relations.
	 * @param string                                            $page_url  Permalink of the current page.
	 * @param callable(int):string                              $ref       Resolves a target post ID to its @id.
	 * @return array<int,array<string,mixed>>
	 */
	public static function attach( array $nodes, array $relations, string $page_url, callable $ref ): array {
		if ( empty( $relations ) || '' === $page_url ) {
			return $nodes;
		}

		// The page's primary node: the first whose @id anchors to this page.
		$primary = null;
		foreach ( $nodes as $index => $node ) {
			if ( isset( $node['@id'] ) && is_string( $node['@id'] ) && 0 === strpos( $node['@id'], $page_url ) ) {
				$primary = $index;
				break;
			}
		}
		if ( null === $primary ) {
			return $nodes; // Only site-wide nodes on this page — nothing to anchor to.
		}

		foreach ( $relations as $relation ) {
			$property = (string) ( $relation['relation'] ?? '' );
			$target   = (int) ( $relation['target_id'] ?? 0 );
			if ( '' === $property || ! $target ) {
				continue;
			}

			$target_ref = (string) $ref( $target );
			if ( '' === $target_ref ) {
				continue;
			}

			$value    = [ '@id' => $target_ref ];
			$existing = $nodes[ $primary ][ $property ] ?? null;

			if ( null === $existing || is_scalar( $existing ) ) {
				// Unset, or a scalar mapping — the typed reference wins.
				$nodes[ $primary ][ $property ] = $value;
			} elseif ( is_array( $existing ) && array_keys( $existing ) === range( 0, count( $existing ) - 1 ) ) {
				$nodes[ $primary ][ $property ][] = $value;
			} else {
				$nodes[ $primary ][ $property ] = [ $existing, $value ];
			}
		}

		return $nodes;
	}
}
