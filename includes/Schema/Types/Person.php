<?php
/**
 * Person schema type.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Schema\Types;

use HelloGekko\StructuredData\Schema\AbstractSchemaType;

defined( 'ABSPATH' ) || exit;

/**
 * Schema.org Person.
 */
class Person extends AbstractSchemaType {

	public function key(): string {
		return 'Person';
	}

	public function label(): string {
		return __( 'Person', 'hg-structured-data' );
	}

	public function group(): string {
		return __( 'Entities', 'hg-structured-data' );
	}

	public function properties(): array {
		return [
			'name'         => [
				'label'       => __( 'Name', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'text',
			],
			'url'          => [
				'label' => __( 'URL', 'hg-structured-data' ),
				'type'  => 'url',
			],
			'image'        => [
				'label' => __( 'Image (URL)', 'hg-structured-data' ),
				'type'  => 'image',
			],
			'jobTitle'     => [
				'label' => __( 'Job title', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'email'        => [
				'label' => __( 'Email', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'telephone'    => [
				'label' => __( 'Telephone', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'description'  => [
				'label' => __( 'Description / bio', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'sameAs'       => [
				'label' => __( 'Same as (social profile URL)', 'hg-structured-data' ),
				'type'  => 'url',
			],
			'worksFor.name' => [
				'label' => __( 'Works for (organization)', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'address.addressLocality' => [
				'label' => __( 'City / locality', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'address.addressCountry'  => [
				'label' => __( 'Country', 'hg-structured-data' ),
				'type'  => 'text',
			],
		];
	}

	public function nested_types(): array {
		return [
			'worksFor' => 'Organization',
			'address'  => 'PostalAddress',
		];
	}

	public function default_mappings(): array {
		return [
			[ 'property' => 'name', 'source' => 'wp', 'value' => 'author_name' ],
			[ 'property' => 'url', 'source' => 'wp', 'value' => 'author_url' ],
			[ 'property' => 'description', 'source' => 'wp', 'value' => 'author_bio' ],
		];
	}
}
