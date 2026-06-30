<?php
/**
 * Organization schema type.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Schema\Types;

use HelloGekko\StructuredData\Schema\AbstractSchemaType;

defined( 'ABSPATH' ) || exit;

/**
 * Schema.org Organization.
 */
class Organization extends AbstractSchemaType {

	public function key(): string {
		return 'Organization';
	}

	public function label(): string {
		return __( 'Organization', 'hg-structured-data' );
	}

	public function group(): string {
		return __( 'Entities', 'hg-structured-data' );
	}

	public function supports_reviews(): bool {
		return true;
	}

	public function recommended(): array {
		return [
			'name'                      => [
				'label'       => __( 'Name', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'text',
			],
			'url'                       => [
				'label'       => __( 'URL', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'url',
			],
			'logo'                      => [
				'label'       => __( 'Logo (URL)', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'image',
			],
			'image'                     => [
				'label' => __( 'Image (URL)', 'hg-structured-data' ),
				'type'  => 'image',
			],
			'description'               => [
				'label' => __( 'Description', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'email'                     => [
				'label' => __( 'Email', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'telephone'                 => [
				'label' => __( 'Telephone', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'sameAs'                    => [
				'label' => __( 'Same as (social profile URL)', 'hg-structured-data' ),
				'type'  => 'url',
			],
			'address.streetAddress'     => [
				'label' => __( 'Street address', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'address.addressLocality'   => [
				'label' => __( 'City / locality', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'address.postalCode'        => [
				'label' => __( 'Postal code', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'address.addressCountry'    => [
				'label' => __( 'Country', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'contactPoint.telephone'    => [
				'label' => __( 'Contact telephone', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'contactPoint.contactType'  => [
				'label' => __( 'Contact type', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'contactPoint.email'        => [
				'label' => __( 'Contact email', 'hg-structured-data' ),
				'type'  => 'text',
			],
		];
	}

	public function nested_types(): array {
		return [
			'address'      => 'PostalAddress',
			'contactPoint' => 'ContactPoint',
		];
	}

	public function default_mappings(): array {
		return [
			[ 'property' => 'name', 'source' => 'wp', 'value' => 'site_name' ],
			[ 'property' => 'url', 'source' => 'wp', 'value' => 'home_url' ],
			[ 'property' => 'description', 'source' => 'wp', 'value' => 'site_description' ],
		];
	}
}
