<?php
/**
 * Evaluates whether a schema definition should be output on the current request.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Display;

use HelloGekko\StructuredData\SchemaDefinition;

defined( 'ABSPATH' ) || exit;

/**
 * Implements the include/exclude display condition logic configured in the wizard.
 */
final class DisplayConditions {

	/**
	 * All supported condition types, used to build the wizard UI.
	 *
	 * @return array<string,string>
	 */
	public static function types(): array {
		$types = [
			'global'        => __( 'Show Globally', 'hg-structured-data' ),
			'homepage'      => __( 'Homepage', 'hg-structured-data' ),
			'post_type'     => __( 'Post type', 'hg-structured-data' ),
			'post'          => __( 'Post', 'hg-structured-data' ),
			'page'          => __( 'Page', 'hg-structured-data' ),
			'post_category' => __( 'Post category', 'hg-structured-data' ),
			'taxonomy'      => __( 'Taxonomy (tag)', 'hg-structured-data' ),
			'post_format'   => __( 'Post format', 'hg-structured-data' ),
			'page_template' => __( 'Page template', 'hg-structured-data' ),
			'author'        => __( 'Author', 'hg-structured-data' ),
			'author_name'   => __( 'Author name', 'hg-structured-data' ),
			'url_parameter' => __( 'URL parameter', 'hg-structured-data' ),
			'date'          => __( 'Date', 'hg-structured-data' ),
		];

		// Only offer the ACF-field condition when ACF is available.
		if ( \HelloGekko\StructuredData\Plugin::has_acf() ) {
			$types['acf_field'] = __( 'ACF field', 'hg-structured-data' );
		}

		return $types;
	}

	/**
	 * Decide whether the given definition should be displayed now.
	 *
	 * @param array<string,mixed> $context Runtime context (post_id...).
	 */
	public function should_display( SchemaDefinition $def, array $context ): bool {
		$conditions = $def->conditions();
		$include    = $conditions['include'];
		$exclude    = $conditions['exclude'];
		$logic      = 'all' === ( $conditions['logic'] ?? 'any' ) ? 'all' : 'any';

		if ( empty( $include ) ) {
			return false;
		}

		$matches = array_map(
			fn( $rule ) => $this->match( $rule, $context ),
			$include
		);

		$included = 'all' === $logic
			? ! in_array( false, $matches, true )
			: in_array( true, $matches, true );

		if ( ! $included ) {
			return false;
		}

		// Any matching exclude rule hides the schema.
		foreach ( $exclude as $rule ) {
			if ( $this->match( $rule, $context ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Match a single rule against the current request.
	 *
	 * @param array<string,mixed> $rule    Rule definition.
	 * @param array<string,mixed> $context Runtime context.
	 */
	private function match( array $rule, array $context ): bool {
		$type     = (string) ( $rule['type'] ?? '' );
		$operator = (string) ( $rule['operator'] ?? 'is' );
		$value    = (string) ( $rule['value'] ?? '' );
		$value2   = (string) ( $rule['value2'] ?? '' );
		$post_id  = (int) ( $context['post_id'] ?? 0 );

		$result = $this->evaluate( $type, $value, $value2, $post_id );

		return 'is_not' === $operator ? ! $result : $result;
	}

	/**
	 * The positive evaluation for each condition type.
	 */
	private function evaluate( string $type, string $value, string $value2, int $post_id ): bool {
		switch ( $type ) {
			case 'global':
				return true;

			case 'homepage':
				return is_front_page() || is_home();

			case 'post_type':
				if ( ! is_singular() || ! $post_id ) {
					return false;
				}
				return get_post_type( $post_id ) === ( '' !== $value ? $value : 'post' );

			case 'post':
				return is_singular() && $post_id && (int) $value === $post_id;

			case 'page':
				return is_page() && $post_id && (int) $value === $post_id;

			case 'post_category':
				return is_singular() && $post_id && has_category( $this->term_value( $value ), $post_id );

			case 'taxonomy':
				$taxonomy = '' !== $value2 ? $value2 : 'post_tag';
				return is_singular() && $post_id && has_term( $this->term_value( $value ), $taxonomy, $post_id );

			case 'post_format':
				if ( ! is_singular() || ! $post_id ) {
					return false;
				}
				$format = get_post_format( $post_id );
				$format = false === $format ? 'standard' : $format;
				return $format === ( '' !== $value ? $value : 'standard' );

			case 'page_template':
				if ( ! $post_id ) {
					return false;
				}
				$template = get_page_template_slug( $post_id );
				return ( '' === $value ? 'default' : $value ) === ( '' === $template ? 'default' : $template );

			case 'author':
				if ( is_author() ) {
					return '' === $value || (int) get_queried_object_id() === (int) $value;
				}
				if ( is_singular() && $post_id ) {
					return (int) get_post_field( 'post_author', $post_id ) === (int) $value;
				}
				return false;

			case 'author_name':
				$author_id = is_author()
					? (int) get_queried_object_id()
					: ( $post_id ? (int) get_post_field( 'post_author', $post_id ) : 0 );
				if ( ! $author_id ) {
					return false;
				}
				$name = get_the_author_meta( 'display_name', $author_id );
				$nice = get_the_author_meta( 'user_nicename', $author_id );
				return in_array( $value, [ $name, $nice ], true );

			case 'url_parameter':
				if ( '' === $value ) {
					return false;
				}
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				if ( ! isset( $_GET[ $value ] ) ) {
					return false;
				}
				if ( '' === $value2 ) {
					return true;
				}
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return sanitize_text_field( wp_unslash( (string) $_GET[ $value ] ) ) === $value2;

			case 'acf_field':
				return AcfCondition::matches( $value, $value2, $post_id );

			case 'date':
				if ( '' === $value ) {
					return false;
				}
				if ( is_date() ) {
					return get_query_var( 'year' ) . '-' . str_pad( (string) ( get_query_var( 'monthnum' ) ?: 1 ), 2, '0', STR_PAD_LEFT ) . '-' . str_pad( (string) ( get_query_var( 'day' ) ?: 1 ), 2, '0', STR_PAD_LEFT ) === $value;
				}
				if ( is_singular() && $post_id ) {
					return get_post_time( 'Y-m-d', false, $post_id ) === $value;
				}
				return false;

			default:
				return false;
		}
	}

	/**
	 * Normalise a term condition value to an id or slug.
	 *
	 * @return int|string
	 */
	private function term_value( string $value ) {
		return ctype_digit( $value ) ? (int) $value : $value;
	}
}
