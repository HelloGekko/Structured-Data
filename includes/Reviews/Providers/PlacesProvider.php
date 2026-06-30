<?php
/**
 * Google Places API review provider.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Reviews\Providers;

use HelloGekko\StructuredData\Reviews\AbstractReviewProvider;
use WP_Error;

defined( 'ABSPATH' ) || exit;

/**
 * Fetches up to five reviews plus the overall rating via the Places Details API.
 */
final class PlacesProvider extends AbstractReviewProvider {

	private const ENDPOINT = 'https://maps.googleapis.com/maps/api/place/details/json';

	public function id(): string {
		return 'places';
	}

	public function label(): string {
		return __( 'Google Places API', 'hg-structured-data' );
	}

	public function is_configured( array $settings ): bool {
		return '' !== (string) ( $settings['places_api_key'] ?? '' )
			&& '' !== (string) ( $settings['place_id'] ?? '' );
	}

	/**
	 * @param array<string,mixed> $settings Reviews settings.
	 * @return array{aggregate:array<string,mixed>,items:array<int,array<string,mixed>>}|WP_Error
	 */
	public function fetch( array $settings ) {
		if ( ! $this->is_configured( $settings ) ) {
			return new WP_Error( 'hgsd_places_unconfigured', __( 'Add a Places API key and Place ID first.', 'hg-structured-data' ) );
		}

		$url = add_query_arg(
			[
				'place_id'              => (string) $settings['place_id'],
				'fields'                => 'rating,user_ratings_total,reviews',
				'reviews_no_translations' => 'true',
				'key'                   => (string) $settings['places_api_key'],
			],
			self::ENDPOINT
		);

		$response = wp_remote_get( $url, [ 'timeout' => 15 ] );
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$body = json_decode( (string) wp_remote_retrieve_body( $response ), true );
		$status = $body['status'] ?? 'UNKNOWN';
		if ( 'OK' !== $status ) {
			return new WP_Error( 'hgsd_places_error', sprintf( /* translators: %s: API status. */ __( 'Places API error: %s', 'hg-structured-data' ), $status ) );
		}

		$result = $body['result'] ?? [];
		$items  = [];

		foreach ( (array) ( $result['reviews'] ?? [] ) as $review ) {
			$items[] = [
				'author' => (string) ( $review['author_name'] ?? '' ),
				'rating' => $this->clamp_rating( $review['rating'] ?? 0 ),
				'text'   => (string) ( $review['text'] ?? '' ),
				'date'   => isset( $review['time'] ) ? gmdate( 'c', (int) $review['time'] ) : '',
				'url'    => (string) ( $review['author_url'] ?? '' ),
			];
		}

		return [
			'aggregate' => [
				'ratingValue' => isset( $result['rating'] ) ? (float) $result['rating'] : null,
				'reviewCount' => isset( $result['user_ratings_total'] ) ? (int) $result['user_ratings_total'] : null,
				'bestRating'  => 5,
			],
			'items'     => $items,
		];
	}
}
