<?php
/**
 * Base class for review sources (Places API, Business Profile API, manual).
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Reviews;

defined( 'ABSPATH' ) || exit;

/**
 * A review provider fetches and normalises reviews from a single source.
 *
 * Normalised shape returned by fetch():
 *   [
 *     'aggregate' => [ 'ratingValue' => float, 'reviewCount' => int, 'bestRating' => int ],
 *     'items'     => [ [ 'author', 'rating', 'text', 'date' (ISO 8601), 'url' ], ... ],
 *   ]
 */
abstract class AbstractReviewProvider {

	/**
	 * Stable identifier, e.g. "places".
	 */
	abstract public function id(): string;

	/**
	 * Human readable label.
	 */
	abstract public function label(): string;

	/**
	 * Whether the provider has everything it needs to run.
	 *
	 * @param array<string,mixed> $settings Reviews settings.
	 */
	abstract public function is_configured( array $settings ): bool;

	/**
	 * Fetch and normalise reviews.
	 *
	 * @param array<string,mixed> $settings Reviews settings.
	 * @return array{aggregate:array<string,mixed>,items:array<int,array<string,mixed>>}|\WP_Error
	 */
	abstract public function fetch( array $settings );

	/**
	 * Empty normalised payload helper.
	 *
	 * @return array{aggregate:array<string,mixed>,items:array<int,array<string,mixed>>}
	 */
	protected function empty(): array {
		return [
			'aggregate' => [],
			'items'     => [],
		];
	}

	/**
	 * Clamp a rating to the 1–5 range as an int.
	 */
	protected function clamp_rating( $value ): int {
		$value = (int) round( (float) $value );
		return max( 1, min( 5, $value ) );
	}
}
