<?php
/**
 * BlogPosting schema type.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Schema\Types;

defined( 'ABSPATH' ) || exit;

/**
 * Schema.org BlogPosting (a subtype of Article).
 */
class BlogPosting extends Article {

	public function key(): string {
		return 'BlogPosting';
	}

	public function label(): string {
		return __( 'Blog Posting', 'hg-structured-data' );
	}

	public function type_value(): string {
		return 'BlogPosting';
	}
}
