<?php
/**
 * Article schema type.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Schema\Types;

use HelloGekko\StructuredData\Schema\AbstractSchemaType;

defined( 'ABSPATH' ) || exit;

/**
 * Schema.org Article.
 */
class Article extends AbstractSchemaType {

	public function key(): string {
		return 'Article';
	}

	public function label(): string {
		return __( 'Article', 'hg-structured-data' );
	}

	public function group(): string {
		return __( 'Content', 'hg-structured-data' );
	}

	public function recommended(): array {
		return array_merge(
			$this->content_properties(),
			[
				'articleBody'    => [
					'label' => __( 'Article body', 'hg-structured-data' ),
					'type'  => 'text',
				],
				'articleSection' => [
					'label' => __( 'Article section', 'hg-structured-data' ),
					'type'  => 'text',
				],
				'keywords'       => [
					'label' => __( 'Keywords', 'hg-structured-data' ),
					'type'  => 'text',
				],
				'wordCount'      => [
					'label' => __( 'Word count', 'hg-structured-data' ),
					'type'  => 'number',
				],
			]
		);
	}

	public function nested_types(): array {
		return $this->content_nested_types();
	}

	public function default_mappings(): array {
		return $this->content_defaults();
	}
}
