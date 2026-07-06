<?php
/**
 * Evaluates an "ACF field" display condition against a post.
 *
 * The comparison adapts to whatever the field holds: a text field compares as
 * text, a true/false field as a boolean, a select/radio as its chosen option,
 * and a checkbox/multi-select matches when the expected value is one of the
 * selected options. With no expected value the rule simply asks "does this
 * field have a value?" — handy for a boolean toggle.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Display;

defined( 'ABSPATH' ) || exit;

/**
 * Shared ACF-condition matcher for the front-end and the cockpit.
 */
final class AcfCondition {

	/**
	 * Whether the ACF field on the given post satisfies the condition.
	 *
	 * @param string $field    ACF field name or key.
	 * @param string $expected Expected value ('' means "field has any value").
	 * @param int    $post_id  Post to read the field from.
	 */
	public static function matches( string $field, string $expected, int $post_id ): bool {
		if ( '' === $field || ! $post_id || ! function_exists( 'get_field' ) ) {
			return false;
		}

		$actual = get_field( $field, $post_id );

		// No expected value: treat the rule as "the field has a value".
		if ( '' === $expected ) {
			return self::has_value( $actual );
		}

		// Choice fields returning the label/value pair.
		if ( is_array( $actual ) && isset( $actual['value'] ) ) {
			$actual = $actual['value'];
		}

		// Checkbox / multi-select: match when the expected value is selected.
		if ( is_array( $actual ) ) {
			foreach ( $actual as $item ) {
				if ( is_array( $item ) && isset( $item['value'] ) ) {
					$item = $item['value'];
				}
				if ( self::scalar_equals( $item, $expected ) ) {
					return true;
				}
			}
			return false;
		}

		return self::scalar_equals( $actual, $expected );
	}

	/**
	 * Whether a returned ACF value counts as "has a value".
	 *
	 * @param mixed $actual Field value.
	 */
	private static function has_value( $actual ): bool {
		if ( is_array( $actual ) ) {
			return ! empty( $actual );
		}
		if ( is_bool( $actual ) ) {
			return $actual;
		}
		return null !== $actual && '' !== $actual && false !== $actual;
	}

	/**
	 * Compare a single field value against the expected string, normalising
	 * booleans and the common truthy/falsy spellings.
	 *
	 * @param mixed  $actual   Field value.
	 * @param string $expected Expected value.
	 */
	private static function scalar_equals( $actual, string $expected ): bool {
		$truthy = [ '1', 'true', 'yes', 'on' ];
		$falsy  = [ '0', 'false', 'no', 'off', '' ];

		if ( is_bool( $actual ) ) {
			$want = strtolower( $expected );
			return $actual
				? in_array( $want, $truthy, true )
				: in_array( $want, $falsy, true );
		}

		// ACF true_false returns 1/0; let "true"/"yes"/"on" match it too.
		if ( ( '1' === (string) $actual || '0' === (string) $actual ) ) {
			$want = strtolower( $expected );
			if ( '1' === (string) $actual && in_array( $want, $truthy, true ) ) {
				return true;
			}
			if ( '0' === (string) $actual && in_array( $want, $falsy, true ) && '' !== $want ) {
				return true;
			}
		}

		return (string) $actual === $expected
			|| strtolower( (string) $actual ) === strtolower( $expected );
	}
}
