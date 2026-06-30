<?php
/**
 * WebPage schema type.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Schema\Types;

use HelloGekko\StructuredData\Schema\AbstractSchemaType;

defined( 'ABSPATH' ) || exit;

/**
 * Schema.org WebPage.
 */
class WebPage extends AbstractSchemaType {

	public function key(): string {
		return 'WebPage';
	}

	public function label(): string {
		return __( 'Web Page', 'hg-structured-data' );
	}

	public function group(): string {
		return __( 'Content', 'hg-structured-data' );
	}

	public function recommended(): array {
		return [
			'name'          => [
				'label'       => __( 'Name', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'text',
			],
			'description'   => [
				'label' => __( 'Description', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'url'           => [
				'label'       => __( 'URL', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'url',
			],
			'image'         => [
				'label' => __( 'Image (URL)', 'hg-structured-data' ),
				'type'  => 'image',
			],
			'datePublished' => [
				'label' => __( 'Date published', 'hg-structured-data' ),
				'type'  => 'date',
			],
			'dateModified'  => [
				'label' => __( 'Date modified', 'hg-structured-data' ),
				'type'  => 'date',
			],
			'inLanguage'    => [
				'label' => __( 'Language', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'primaryImageOfPage' => [
				'label' => __( 'Primary image of page (URL)', 'hg-structured-data' ),
				'type'  => 'image',
			],
		];
	}

	public function default_mappings(): array {
		return [
			[ 'property' => 'name', 'source' => 'wp', 'value' => 'post_title' ],
			[ 'property' => 'description', 'source' => 'wp', 'value' => 'post_excerpt' ],
			[ 'property' => 'url', 'source' => 'wp', 'value' => 'permalink' ],
			[ 'property' => 'image', 'source' => 'wp', 'value' => 'featured_image' ],
			[ 'property' => 'datePublished', 'source' => 'wp', 'value' => 'post_date' ],
			[ 'property' => 'dateModified', 'source' => 'wp', 'value' => 'post_modified' ],
			[ 'property' => 'inLanguage', 'source' => 'wp', 'value' => 'site_language' ],
		];
	}
}
