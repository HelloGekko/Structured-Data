<?php
/**
 * NewsArticle schema type.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Schema\Types;

defined( 'ABSPATH' ) || exit;

/**
 * Schema.org NewsArticle (a subtype of Article).
 */
class NewsArticle extends Article {

	public function key(): string {
		return 'NewsArticle';
	}

	public function label(): string {
		return __( 'News Article', 'hg-structured-data' );
	}

	public function type_value(): string {
		return 'NewsArticle';
	}

	public function properties(): array {
		return array_merge(
			parent::properties(),
			[
				'dateline'     => [
					'label' => __( 'Dateline', 'hg-structured-data' ),
					'type'  => 'text',
				],
				'printSection' => [
					'label' => __( 'Print section', 'hg-structured-data' ),
					'type'  => 'text',
				],
			]
		);
	}
}
