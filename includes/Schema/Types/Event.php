<?php
/**
 * Event schema type.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Schema\Types;

use HelloGekko\StructuredData\Schema\AbstractSchemaType;

defined( 'ABSPATH' ) || exit;

/**
 * Schema.org Event.
 */
class Event extends AbstractSchemaType {

	public function key(): string {
		return 'Event';
	}

	public function label(): string {
		return __( 'Event', 'hg-structured-data' );
	}

	public function group(): string {
		return __( 'Local & Commerce', 'hg-structured-data' );
	}

	public function supports_reviews(): bool {
		return true;
	}

	public function recommended(): array {
		return [
			'name'                  => [
				'label'       => __( 'Name', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'text',
			],
			'description'           => [
				'label' => __( 'Description', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'startDate'             => [
				'label'       => __( 'Start date', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'date',
			],
			'endDate'               => [
				'label' => __( 'End date', 'hg-structured-data' ),
				'type'  => 'date',
			],
			'eventStatus'           => [
				'label' => __( 'Event status', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'eventAttendanceMode'   => [
				'label' => __( 'Attendance mode', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'image'                 => [
				'label'       => __( 'Image (URL)', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'image',
			],
			'url'                   => [
				'label' => __( 'URL', 'hg-structured-data' ),
				'type'  => 'url',
			],
			'location.name'         => [
				'label'       => __( 'Location name', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'text',
			],
			'location.address'      => [
				'label' => __( 'Location address', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'location.url'          => [
				'label' => __( 'Location URL (online events)', 'hg-structured-data' ),
				'type'  => 'url',
			],
			'performer.name'        => [
				'label' => __( 'Performer name', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'organizer.name'        => [
				'label' => __( 'Organizer name', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'organizer.url'         => [
				'label' => __( 'Organizer URL', 'hg-structured-data' ),
				'type'  => 'url',
			],
			'offers.price'          => [
				'label' => __( 'Ticket price', 'hg-structured-data' ),
				'type'  => 'number',
			],
			'offers.priceCurrency'  => [
				'label' => __( 'Price currency', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'offers.availability'   => [
				'label' => __( 'Availability', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'offers.url'            => [
				'label' => __( 'Tickets URL', 'hg-structured-data' ),
				'type'  => 'url',
			],
		];
	}

	public function nested_types(): array {
		return [
			'location'  => 'Place',
			'performer' => 'Person',
			'organizer' => 'Organization',
			'offers'    => 'Offer',
		];
	}

	public function default_mappings(): array {
		return [
			[ 'property' => 'name', 'source' => 'wp', 'value' => 'post_title' ],
			[ 'property' => 'description', 'source' => 'wp', 'value' => 'post_excerpt' ],
			[ 'property' => 'image', 'source' => 'wp', 'value' => 'featured_image' ],
			[ 'property' => 'url', 'source' => 'wp', 'value' => 'permalink' ],
		];
	}
}
