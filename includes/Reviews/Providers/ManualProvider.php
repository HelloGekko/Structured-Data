<?php
/**
 * Manual review provider — reviews typed in by hand in the settings.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Reviews\Providers;

use HelloGekko\StructuredData\Reviews\AbstractReviewProvider;

defined( 'ABSPATH' ) || exit;

/**
 * Builds the normalised payload straight from manually entered reviews.
 */
final class ManualProvider extends AbstractReviewProvider {

	public function id(): string {
		return 'manual';
	}

	public function label(): string {
		return __( 'Manual entry', 'hg-structured-data' );
	}

	public function is_configured( array $settings ): bool {
		return ! empty( $settings['manual_items'] );
	}

	/**
	 * @param array<string,mixed> $settings Reviews settings.
	 * @return array{aggregate:array<string,mixed>,items:array<int,array<string,mixed>>}
	 */
	public function fetch( array $settings ) {
		$items = [];
		foreach ( (array) ( $settings['manual_items'] ?? [] ) as $item ) {
			if ( ! is_array( $item ) ) {
				continue;
			}
			$items[] = [
				'author' => (string) ( $item['author'] ?? '' ),
				'rating' => $this->clamp_rating( $item['rating'] ?? 5 ),
				'text'   => (string) ( $item['text'] ?? '' ),
				'date'   => (string) ( $item['date'] ?? '' ),
				'url'    => (string) ( $item['url'] ?? '' ),
			];
		}

		$count = count( $items );
		$avg   = null;
		if ( $count > 0 ) {
			$sum = array_sum( array_map( static fn( $i ) => (int) $i['rating'], $items ) );
			$avg = round( $sum / $count, 1 );
		}

		// An explicit manual aggregate overrides the computed average.
		$manual_aggregate = is_array( $settings['manual_aggregate'] ?? null ) ? $settings['manual_aggregate'] : [];
		$rating_value     = isset( $manual_aggregate['ratingValue'] ) && '' !== $manual_aggregate['ratingValue']
			? (float) $manual_aggregate['ratingValue']
			: $avg;
		$review_count     = isset( $manual_aggregate['reviewCount'] ) && '' !== $manual_aggregate['reviewCount']
			? (int) $manual_aggregate['reviewCount']
			: $count;

		return [
			'aggregate' => [
				'ratingValue' => $rating_value,
				'reviewCount' => $review_count,
				'bestRating'  => 5,
			],
			'items'     => $items,
		];
	}
}
