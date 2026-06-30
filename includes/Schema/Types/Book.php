<?php
/**
 * Book schema type.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Schema\Types;

use HelloGekko\StructuredData\Schema\AbstractSchemaType;

defined( 'ABSPATH' ) || exit;

/**
 * Schema.org Book.
 */
class Book extends AbstractSchemaType {

	public function key(): string {
		return 'Book';
	}

	public function label(): string {
		return __( 'Book', 'hg-structured-data' );
	}

	public function group(): string {
		return __( 'Content', 'hg-structured-data' );
	}

	public function recommended(): array {
		return [
			'name'           => [
				'label'       => __( 'Name / title', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'text',
			],
			'author.name'    => [
				'label'       => __( 'Author name', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'text',
			],
			'author.url'     => [
				'label' => __( 'Author URL', 'hg-structured-data' ),
				'type'  => 'url',
			],
			'isbn'           => [
				'label' => __( 'ISBN', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'bookFormat'     => [
				'label' => __( 'Book format', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'bookEdition'    => [
				'label' => __( 'Book edition', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'numberOfPages'  => [
				'label' => __( 'Number of pages', 'hg-structured-data' ),
				'type'  => 'number',
			],
			'datePublished'  => [
				'label' => __( 'Date published', 'hg-structured-data' ),
				'type'  => 'date',
			],
			'publisher.name' => [
				'label' => __( 'Publisher name', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'inLanguage'     => [
				'label' => __( 'Language', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'image'          => [
				'label' => __( 'Cover image (URL)', 'hg-structured-data' ),
				'type'  => 'image',
			],
			'url'            => [
				'label' => __( 'URL', 'hg-structured-data' ),
				'type'  => 'url',
			],
			'description'    => [
				'label' => __( 'Description', 'hg-structured-data' ),
				'type'  => 'text',
			],
		];
	}

	public function nested_types(): array {
		return [
			'author'    => 'Person',
			'publisher' => 'Organization',
		];
	}

	public function default_mappings(): array {
		return [
			[ 'property' => 'name', 'source' => 'wp', 'value' => 'post_title' ],
			[ 'property' => 'description', 'source' => 'wp', 'value' => 'post_excerpt' ],
			[ 'property' => 'image', 'source' => 'wp', 'value' => 'featured_image' ],
			[ 'property' => 'url', 'source' => 'wp', 'value' => 'permalink' ],
			[ 'property' => 'datePublished', 'source' => 'wp', 'value' => 'post_date' ],
			[ 'property' => 'inLanguage', 'source' => 'wp', 'value' => 'site_language' ],
		];
	}
}
