<?php
/**
 * Service schema type.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Schema\Types;

use HelloGekko\StructuredData\Schema\AbstractSchemaType;

defined( 'ABSPATH' ) || exit;

/**
 * Schema.org Service.
 */
class Service extends AbstractSchemaType {

	public function key(): string {
		return 'Service';
	}

	public function label(): string {
		return __( 'Service', 'hg-structured-data' );
	}

	public function group(): string {
		return __( 'Local & Commerce', 'hg-structured-data' );
	}

	public function supports_reviews(): bool {
		return true;
	}

	public function recommended(): array {
		return [
			'name'                 => [
				'label'       => __( 'Name', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'text',
			],
			'description'          => [
				'label'       => __( 'Description', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'text',
			],
			'serviceType'          => [
				'label'       => __( 'Service type', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'text',
			],
			'image'                => [
				'label' => __( 'Image (URL)', 'hg-structured-data' ),
				'type'  => 'image',
			],
			'url'                  => [
				'label' => __( 'URL', 'hg-structured-data' ),
				'type'  => 'url',
			],
			'provider.name'        => [
				'label'       => __( 'Provider name', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'text',
			],
			'provider.url'         => [
				'label' => __( 'Provider URL', 'hg-structured-data' ),
				'type'  => 'url',
			],
			'areaServed'           => [
				'label' => __( 'Area served', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'category'             => [
				'label' => __( 'Category', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'brand.name'           => [
				'label' => __( 'Brand name', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'offers.price'         => [
				'label' => __( 'Price', 'hg-structured-data' ),
				'type'  => 'number',
			],
			'offers.priceCurrency' => [
				'label' => __( 'Price currency', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'offers.availability'  => [
				'label' => __( 'Availability', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'offers.url'           => [
				'label' => __( 'Offer URL', 'hg-structured-data' ),
				'type'  => 'url',
			],
			'termsOfService'       => [
				'label' => __( 'Terms of service (URL)', 'hg-structured-data' ),
				'type'  => 'url',
			],
			'slogan'               => [
				'label' => __( 'Slogan', 'hg-structured-data' ),
				'type'  => 'text',
			],
		];
	}

	public function nested_types(): array {
		return [
			'provider' => 'Organization',
			'offers'   => 'Offer',
			'brand'    => 'Brand',
		];
	}

	public function default_mappings(): array {
		return [
			[ 'property' => 'name', 'source' => 'wp', 'value' => 'post_title' ],
			[ 'property' => 'description', 'source' => 'wp', 'value' => 'post_excerpt' ],
			[ 'property' => 'image', 'source' => 'wp', 'value' => 'featured_image' ],
			[ 'property' => 'url', 'source' => 'wp', 'value' => 'permalink' ],
			[ 'property' => 'provider.name', 'source' => 'wp', 'value' => 'site_name' ],
		];
	}
}
