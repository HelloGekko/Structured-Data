<?php
/**
 * Admin-side approximation of which schemas apply to a post.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Graph;

use HelloGekko\StructuredData\Display\AcfCondition;
use HelloGekko\StructuredData\SchemaDefinition;

defined( 'ABSPATH' ) || exit;

/**
 * The front-end DisplayConditions rely on query conditionals (is_singular()
 * etc.) that don't exist in wp-admin. This matcher evaluates the same rules
 * against a concrete post instead, for the cockpit's "Schemas" column.
 * Request-bound rules (URL parameter, date archive) can't be resolved for a
 * post and count as no match.
 */
final class SchemaMatcher {

	/**
	 * Whether a definition's conditions match the given post.
	 */
	public function matches( SchemaDefinition $definition, \WP_Post $post ): bool {
		$conditions = $definition->conditions();
		$include    = $conditions['include'];
		if ( empty( $include ) ) {
			return false;
		}

		$results = array_map(
			fn( $rule ) => $this->rule_matches( $rule, $post ),
			$include
		);

		$included = 'all' === ( $conditions['logic'] ?? 'any' )
			? ! in_array( false, $results, true )
			: in_array( true, $results, true );

		if ( ! $included ) {
			return false;
		}

		foreach ( $conditions['exclude'] as $rule ) {
			if ( $this->rule_matches( $rule, $post ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Evaluate one rule against a post.
	 *
	 * @param array<string,mixed> $rule Rule definition.
	 */
	private function rule_matches( array $rule, \WP_Post $post ): bool {
		$type     = (string) ( $rule['type'] ?? '' );
		$operator = (string) ( $rule['operator'] ?? 'is' );
		$value    = (string) ( $rule['value'] ?? '' );
		$value2   = (string) ( $rule['value2'] ?? '' );

		$result = $this->evaluate( $type, $value, $value2, $post );

		return 'is_not' === $operator ? ! $result : $result;
	}

	/**
	 * Positive evaluation per rule type, post-bound.
	 */
	private function evaluate( string $type, string $value, string $value2, \WP_Post $post ): bool {
		switch ( $type ) {
			case 'global':
				return true;
			case 'homepage':
				return $post->ID === (int) get_option( 'page_on_front' );
			case 'post_type':
				return $post->post_type === ( '' !== $value ? $value : 'post' );
			case 'post':
			case 'page':
				return (int) $value === $post->ID;
			case 'post_category':
				return has_category( ctype_digit( $value ) ? (int) $value : $value, $post );
			case 'taxonomy':
				return has_term( ctype_digit( $value ) ? (int) $value : $value, '' !== $value2 ? $value2 : 'post_tag', $post );
			case 'post_format':
				$format = get_post_format( $post );
				return ( false === $format ? 'standard' : $format ) === ( '' !== $value ? $value : 'standard' );
			case 'page_template':
				$template = get_page_template_slug( $post );
				return ( '' === $value ? 'default' : $value ) === ( '' === $template ? 'default' : $template );
			case 'author':
				return (int) $post->post_author === (int) $value;
			case 'author_name':
				$name = get_the_author_meta( 'display_name', (int) $post->post_author );
				$nice = get_the_author_meta( 'user_nicename', (int) $post->post_author );
				return in_array( $value, [ $name, $nice ], true );
			case 'acf_field':
				return AcfCondition::matches( $value, $value2, $post->ID );
			default:
				return false; // Request-bound rules can't be evaluated per post.
		}
	}
}
