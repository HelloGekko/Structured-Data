<?php
/**
 * LocalBusiness schema type.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Schema\Types;

use HelloGekko\StructuredData\Schema\AbstractSchemaType;

defined( 'ABSPATH' ) || exit;

/**
 * Schema.org LocalBusiness.
 */
class LocalBusiness extends AbstractSchemaType {

	public function key(): string {
		return 'LocalBusiness';
	}

	public function label(): string {
		return __( 'Local Business', 'hg-structured-data' );
	}

	public function group(): string {
		return __( 'Local & Commerce', 'hg-structured-data' );
	}

	public function supports_reviews(): bool {
		return true;
	}

	public function recommended(): array {
		return [
			'name'                       => [
				'label'       => __( 'Name', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'text',
			],
			'description'                => [
				'label' => __( 'Description', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'image'                      => [
				'label'       => __( 'Image (URL)', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'image',
			],
			'url'                        => [
				'label' => __( 'URL', 'hg-structured-data' ),
				'type'  => 'url',
			],
			'telephone'                  => [
				'label' => __( 'Telephone', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'email'                      => [
				'label' => __( 'Email', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'priceRange'                 => [
				'label' => __( 'Price range', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'address.streetAddress'      => [
				'label'       => __( 'Street address', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'text',
			],
			'address.addressLocality'    => [
				'label'       => __( 'City / locality', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'text',
			],
			'address.addressRegion'      => [
				'label' => __( 'Region', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'address.postalCode'         => [
				'label'       => __( 'Postal code', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'text',
			],
			'address.addressCountry'     => [
				'label'       => __( 'Country', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'text',
			],
			'geo.latitude'               => [
				'label' => __( 'Latitude', 'hg-structured-data' ),
				'type'  => 'number',
			],
			'geo.longitude'              => [
				'label' => __( 'Longitude', 'hg-structured-data' ),
				'type'  => 'number',
			],
			'openingHours'               => [
				'label' => __( 'Opening hours', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'currenciesAccepted'         => [
				'label' => __( 'Currencies accepted', 'hg-structured-data' ),
				'type'  => 'text',
			],
		];
	}

	public function nested_types(): array {
		return [
			'address' => 'PostalAddress',
			'geo'     => 'GeoCoordinates',
		];
	}

	public function default_mappings(): array {
		return [
			[ 'property' => 'name', 'source' => 'wp', 'value' => 'site_name' ],
			[ 'property' => 'url', 'source' => 'wp', 'value' => 'home_url' ],
		];
	}
}
