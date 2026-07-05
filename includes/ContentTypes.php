<?php
/**
 * Which post types count as real site content.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData;

defined( 'ABSPATH' ) || exit;

/**
 * Page builders register their template libraries as "public" post types
 * (Elementor's elementor_library being the classic case), which would flood
 * the link index and the cockpit with Footer/404/Loop-Item pseudo-pages.
 * This helper defines the content set everything else works on: public,
 * searchable post types minus known template/utility types.
 */
final class ContentTypes {

	private const DENYLIST = [
		'attachment',
		'elementor_library',
		'e-landing-page',
		'e-floating-buttons',
		'elementor-hf',
		'astra-advanced-hook',
		'brizy-template',
		'ct_template',
		'fl-builder-template',
		'oxy_user_library',
	];

	/**
	 * Content post type names.
	 *
	 * @return array<int,string>
	 */
	public static function list(): array {
		return array_keys( self::objects() );
	}

	/**
	 * Content post type objects, keyed by name.
	 *
	 * @return array<string,\WP_Post_Type>
	 */
	public static function objects(): array {
		$out = [];
		foreach ( get_post_types( [ 'public' => true ], 'objects' ) as $type ) {
			if ( in_array( $type->name, self::DENYLIST, true ) ) {
				continue;
			}
			// Template libraries hide themselves from search — real content doesn't.
			if ( ! empty( $type->exclude_from_search ) ) {
				continue;
			}
			$out[ $type->name ] = $type;
		}

		/**
		 * Filter the post types treated as site content by the cockpit and index.
		 *
		 * @param array<string,\WP_Post_Type> $out Content post types by name.
		 */
		return (array) apply_filters( 'hgsd_content_post_types', $out );
	}
}
