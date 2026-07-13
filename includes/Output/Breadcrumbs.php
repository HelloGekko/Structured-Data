<?php
/**
 * Automatic BreadcrumbList generation.
 *
 * Builds a schema.org BreadcrumbList for the current page from its place in the
 * site: Home → parent pages (for hierarchical content) or the primary category
 * (for posts) → the page itself. This restores breadcrumb structured data when
 * the plugin is the single source of JSON-LD, without leaning on the SEO
 * plugin's own output.
 *
 * The whole trail is filterable through `hgsd_breadcrumb_items` for bespoke
 * hierarchies.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Output;

use HelloGekko\StructuredData\ContentTypes;
use WP_Post;
use WP_Term;

defined( 'ABSPATH' ) || exit;

/**
 * Produces a BreadcrumbList JSON-LD node for the current singular request.
 */
final class Breadcrumbs {

	public const OPTION = 'hgsd_breadcrumbs';

	/**
	 * Settings merged with defaults.
	 *
	 * @return array{enabled:bool,home_label:string,post_types:array<int,string>}
	 */
	public static function settings(): array {
		$stored = get_option( self::OPTION, [] );
		$stored = is_array( $stored ) ? $stored : [];

		return [
			'enabled'    => ! isset( $stored['enabled'] ) || ! empty( $stored['enabled'] ),
			'home_label' => '' !== (string) ( $stored['home_label'] ?? '' ) ? (string) $stored['home_label'] : __( 'Home', 'hg-structured-data' ),
			'post_types' => is_array( $stored['post_types'] ?? null ) ? array_map( 'sanitize_key', $stored['post_types'] ) : ContentTypes::list(),
		];
	}

	/**
	 * BreadcrumbList node for a post, or null when it should not be output.
	 *
	 * @return array<string,mixed>|null
	 */
	public static function node( int $post_id ): ?array {
		// No trail on the homepage itself (it would just be "Home").
		if ( ! $post_id || ! is_singular() || is_front_page() ) {
			return null;
		}

		$settings = self::settings();
		if ( ! $settings['enabled'] ) {
			return null;
		}

		$post = get_post( $post_id );
		if ( ! $post instanceof WP_Post || ! in_array( $post->post_type, $settings['post_types'], true ) ) {
			return null;
		}

		$items = self::items( $post, $settings['home_label'] );

		// A single crumb (just Home, or just the page) is not a useful trail.
		if ( count( $items ) < 2 ) {
			return null;
		}

		$elements = [];
		$position = 1;
		foreach ( $items as $item ) {
			$element = [
				'@type'    => 'ListItem',
				'position' => $position,
				'name'     => (string) $item['name'],
			];
			if ( ! empty( $item['url'] ) ) {
				$element['item'] = (string) $item['url'];
			}
			$elements[] = $element;
			++$position;
		}

		return [
			'@context'        => 'https://schema.org',
			'@type'           => 'BreadcrumbList',
			'@id'             => get_permalink( $post ) . '#breadcrumb',
			'itemListElement' => $elements,
		];
	}

	/**
	 * The ordered crumbs (name + url) for a post.
	 *
	 * @return array<int,array{name:string,url:string}>
	 */
	private static function items( WP_Post $post, string $home_label ): array {
		$items = [ self::crumb( $home_label, home_url( '/' ) ) ];

		if ( is_post_type_hierarchical( $post->post_type ) && $post->post_parent ) {
			foreach ( array_reverse( get_post_ancestors( $post ) ) as $ancestor_id ) {
				$items[] = self::crumb( (string) get_the_title( $ancestor_id ), (string) get_permalink( $ancestor_id ) );
			}
		} else {
			$term = self::primary_term( $post );
			if ( $term ) {
				$link    = get_term_link( $term );
				$items[] = self::crumb( $term->name, is_string( $link ) ? $link : '' );
			}
		}

		$items[] = self::crumb( (string) get_the_title( $post ), (string) get_permalink( $post ) );

		/**
		 * Filter the breadcrumb trail before it becomes JSON-LD.
		 *
		 * @param array<int,array{name:string,url:string}> $items Ordered crumbs.
		 * @param WP_Post                                   $post  Current post.
		 */
		$items = apply_filters( 'hgsd_breadcrumb_items', $items, $post );

		return is_array( $items ) ? array_values( array_filter( $items, static fn( $i ) => is_array( $i ) && '' !== (string) ( $i['name'] ?? '' ) ) ) : [];
	}

	/**
	 * The post's primary term, honouring Rank Math / Yoast primary-category meta.
	 */
	private static function primary_term( WP_Post $post ): ?WP_Term {
		$taxonomy = 'category';
		if ( ! is_object_in_taxonomy( $post->post_type, $taxonomy ) ) {
			return null;
		}

		$primary = (int) ( get_post_meta( $post->ID, 'rank_math_primary_' . $taxonomy, true )
			?: get_post_meta( $post->ID, '_yoast_wpseo_primary_' . $taxonomy, true ) );

		if ( $primary ) {
			$term = get_term( $primary, $taxonomy );
			if ( $term instanceof WP_Term ) {
				return $term;
			}
		}

		$terms = get_the_terms( $post, $taxonomy );
		if ( is_array( $terms ) && isset( $terms[0] ) && $terms[0] instanceof WP_Term ) {
			return $terms[0];
		}

		return null;
	}

	/**
	 * @return array{name:string,url:string}
	 */
	private static function crumb( string $name, string $url ): array {
		return [
			'name' => $name,
			'url'  => $url,
		];
	}
}
