<?php
/**
 * JobPosting schema type.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Schema\Types;

use HelloGekko\StructuredData\Schema\AbstractSchemaType;

defined( 'ABSPATH' ) || exit;

/**
 * Schema.org JobPosting — powers Google's job listings (and is one of the two
 * types Google's Indexing API officially supports, so it pairs well with the
 * plugin's instant-indexing feature).
 */
class JobPosting extends AbstractSchemaType {

	public function key(): string {
		return 'JobPosting';
	}

	public function label(): string {
		return __( 'Job posting (vacancy)', 'hg-structured-data' );
	}

	public function group(): string {
		return __( 'Local & Commerce', 'hg-structured-data' );
	}

	public function recommended(): array {
		return [
			'title'                                => [
				'label'       => __( 'Job title', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'text',
			],
			'description'                          => [
				'label'       => __( 'Description (full)', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'text',
			],
			'datePosted'                           => [
				'label'       => __( 'Date posted', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'date',
			],
			'validThrough'                         => [
				'label' => __( 'Valid through (expiry)', 'hg-structured-data' ),
				'type'  => 'date',
			],
			'employmentType'                       => [
				'label' => __( 'Employment type (FULL_TIME, PART_TIME, CONTRACTOR, TEMPORARY, INTERN, VOLUNTEER, PER_DIEM, OTHER)', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'hiringOrganization.name'              => [
				'label'       => __( 'Hiring organization — name', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'text',
			],
			'hiringOrganization.sameAs'            => [
				'label' => __( 'Hiring organization — website', 'hg-structured-data' ),
				'type'  => 'url',
			],
			'hiringOrganization.logo'              => [
				'label' => __( 'Hiring organization — logo (URL)', 'hg-structured-data' ),
				'type'  => 'image',
			],
			'jobLocation.address.streetAddress'    => [
				'label' => __( 'Location — street address', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'jobLocation.address.addressLocality'  => [
				'label'       => __( 'Location — city', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'text',
			],
			'jobLocation.address.addressRegion'    => [
				'label' => __( 'Location — region/province', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'jobLocation.address.postalCode'       => [
				'label' => __( 'Location — postal code', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'jobLocation.address.addressCountry'   => [
				'label'       => __( 'Location — country (e.g. NL)', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'text',
			],
			'jobLocationType'                      => [
				'label' => __( 'Remote? enter TELECOMMUTE for a fully remote job', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'applicantLocationRequirements.name'   => [
				'label' => __( 'Remote — allowed applicant country (e.g. Netherlands)', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'baseSalary.currency'                  => [
				'label' => __( 'Salary — currency (e.g. EUR)', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'baseSalary.value.value'               => [
				'label' => __( 'Salary — amount (fixed)', 'hg-structured-data' ),
				'type'  => 'number',
			],
			'baseSalary.value.minValue'            => [
				'label' => __( 'Salary — minimum (range)', 'hg-structured-data' ),
				'type'  => 'number',
			],
			'baseSalary.value.maxValue'            => [
				'label' => __( 'Salary — maximum (range)', 'hg-structured-data' ),
				'type'  => 'number',
			],
			'baseSalary.value.unitText'            => [
				'label' => __( 'Salary — per (HOUR, DAY, WEEK, MONTH, YEAR)', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'identifier.name'                      => [
				'label' => __( 'Job identifier — name/source', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'identifier.value'                     => [
				'label' => __( 'Job identifier — value/reference', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'directApply'                          => [
				'label' => __( 'Direct apply available on this page', 'hg-structured-data' ),
				'type'  => 'boolean',
			],
		];
	}

	public function nested_types(): array {
		return [
			'hiringOrganization'            => 'Organization',
			'jobLocation'                   => 'Place',
			'jobLocation.address'           => 'PostalAddress',
			'applicantLocationRequirements' => 'AdministrativeArea',
			'baseSalary'                    => 'MonetaryAmount',
			'baseSalary.value'              => 'QuantitativeValue',
			'identifier'                    => 'PropertyValue',
		];
	}

	public function default_mappings(): array {
		return [
			[ 'property' => 'title', 'source' => 'wp', 'value' => 'post_title' ],
			[ 'property' => 'description', 'source' => 'wp', 'value' => 'post_content' ],
			[ 'property' => 'datePosted', 'source' => 'wp', 'value' => 'post_date' ],
			[ 'property' => 'hiringOrganization.name', 'source' => 'wp', 'value' => 'site_name' ],
			[ 'property' => 'hiringOrganization.logo', 'source' => 'wp', 'value' => 'site_logo' ],
		];
	}
}
