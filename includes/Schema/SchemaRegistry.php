<?php
/**
 * Registry of all supported schema types.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Schema;

use HelloGekko\StructuredData\Schema\Types;

defined( 'ABSPATH' ) || exit;

/**
 * Holds every registered AbstractSchemaType, keyed by its key().
 */
final class SchemaRegistry {

	/**
	 * @var array<string,AbstractSchemaType>
	 */
	private array $types = [];

	/**
	 * Register the built-in types and allow third parties to add more.
	 */
	public function bootstrap(): void {
		$defaults = [
			new Types\Article(),
			new Types\BlogPosting(),
			new Types\NewsArticle(),
			new Types\WebPage(),
			new Types\ItemPage(),
			new Types\Book(),
			new Types\FAQ(),
			new Types\Event(),
			new Types\LocalBusiness(),
			new Types\Product(),
			new Types\Service(),
			new Types\Person(),
			new Types\Organization(),
			new Types\JobPosting(),
		];

		foreach ( $defaults as $type ) {
			$this->register( $type );
		}

		/**
		 * Allow add-ons to register custom schema types.
		 *
		 * @param SchemaRegistry $registry The registry instance.
		 */
		do_action( 'hgsd_register_schema_types', $this );
	}

	public function register( AbstractSchemaType $type ): void {
		$this->types[ $type->key() ] = $type;
	}

	public function get( string $key ): ?AbstractSchemaType {
		return $this->types[ $key ] ?? null;
	}

	/**
	 * @return array<string,AbstractSchemaType>
	 */
	public function all(): array {
		return $this->types;
	}

	/**
	 * Types grouped by their group() label, for the picker.
	 *
	 * @return array<string,array<string,AbstractSchemaType>>
	 */
	public function grouped(): array {
		$grouped = [];
		foreach ( $this->types as $key => $type ) {
			$grouped[ $type->group() ][ $key ] = $type;
		}
		return $grouped;
	}
}
