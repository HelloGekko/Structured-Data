<?php
/**
 * Google Business Profile API review provider (OAuth).
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Reviews\Providers;

use HelloGekko\StructuredData\Reviews\AbstractReviewProvider;
use WP_Error;

defined( 'ABSPATH' ) || exit;

/**
 * Fetches all reviews for a location through the Business Profile API, using a
 * stored OAuth refresh token to mint short-lived access tokens.
 */
final class BusinessProfileProvider extends AbstractReviewProvider {

	private const TOKEN_URL = 'https://oauth2.googleapis.com/token';

	/** Star rating enum -> integer. */
	private const STARS = [
		'ONE'   => 1,
		'TWO'   => 2,
		'THREE' => 3,
		'FOUR'  => 4,
		'FIVE'  => 5,
	];

	public function id(): string {
		return 'business';
	}

	public function label(): string {
		return __( 'Google Business Profile API', 'hg-structured-data' );
	}

	public function is_configured( array $settings ): bool {
		return '' !== (string) ( $settings['bp_client_id'] ?? '' )
			&& '' !== (string) ( $settings['bp_client_secret'] ?? '' )
			&& '' !== (string) ( $settings['bp_refresh_token'] ?? '' )
			&& '' !== (string) ( $settings['bp_location'] ?? '' );
	}

	/**
	 * @param array<string,mixed> $settings Reviews settings.
	 * @return array{aggregate:array<string,mixed>,items:array<int,array<string,mixed>>}|WP_Error
	 */
	public function fetch( array $settings ) {
		if ( ! $this->is_configured( $settings ) ) {
			return new WP_Error( 'hgsd_bp_unconfigured', __( 'Connect a Google Business Profile account first.', 'hg-structured-data' ) );
		}

		$token = $this->access_token( $settings );
		if ( is_wp_error( $token ) ) {
			return $token;
		}

		$location = trim( (string) $settings['bp_location'], '/' );
		$url      = 'https://mybusiness.googleapis.com/v4/' . $location . '/reviews';

		$response = wp_remote_get(
			$url,
			[
				'timeout' => 20,
				'headers' => [ 'Authorization' => 'Bearer ' . $token ],
			]
		);
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code = (int) wp_remote_retrieve_response_code( $response );
		$body = json_decode( (string) wp_remote_retrieve_body( $response ), true );
		if ( 200 !== $code ) {
			$message = $body['error']['message'] ?? (string) $code;
			return new WP_Error( 'hgsd_bp_error', sprintf( /* translators: %s: API error. */ __( 'Business Profile API error: %s', 'hg-structured-data' ), $message ) );
		}

		$items = [];
		foreach ( (array) ( $body['reviews'] ?? [] ) as $review ) {
			$stars   = self::STARS[ $review['starRating'] ?? '' ] ?? 0;
			$items[] = [
				'author' => (string) ( $review['reviewer']['displayName'] ?? '' ),
				'rating' => $this->clamp_rating( $stars ),
				'text'   => (string) ( $review['comment'] ?? '' ),
				'date'   => (string) ( $review['createTime'] ?? '' ),
				'url'    => '',
			];
		}

		return [
			'aggregate' => [
				'ratingValue' => isset( $body['averageRating'] ) ? (float) $body['averageRating'] : null,
				'reviewCount' => isset( $body['totalReviewCount'] ) ? (int) $body['totalReviewCount'] : count( $items ),
				'bestRating'  => 5,
			],
			'items'     => $items,
		];
	}

	/**
	 * Exchange the stored refresh token for a fresh access token.
	 *
	 * @param array<string,mixed> $settings Reviews settings.
	 * @return string|WP_Error
	 */
	private function access_token( array $settings ) {
		$response = wp_remote_post(
			self::TOKEN_URL,
			[
				'timeout' => 15,
				'body'    => [
					'client_id'     => (string) $settings['bp_client_id'],
					'client_secret' => (string) $settings['bp_client_secret'],
					'refresh_token' => (string) $settings['bp_refresh_token'],
					'grant_type'    => 'refresh_token',
				],
			]
		);
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$body = json_decode( (string) wp_remote_retrieve_body( $response ), true );
		if ( empty( $body['access_token'] ) ) {
			$message = $body['error_description'] ?? ( $body['error'] ?? __( 'Could not refresh access token.', 'hg-structured-data' ) );
			return new WP_Error( 'hgsd_bp_token', (string) $message );
		}

		return (string) $body['access_token'];
	}
}
