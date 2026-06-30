<?php
/**
 * ItemPage schema type.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Schema\Types;

defined( 'ABSPATH' ) || exit;

/**
 * Schema.org ItemPage (a subtype of WebPage).
 */
class ItemPage extends WebPage {

	public function key(): string {
		return 'ItemPage';
	}

	public function label(): string {
		return __( 'Item Page', 'hg-structured-data' );
	}

	public function type_value(): string {
		return 'ItemPage';
	}
}
