<?php
/**
 * Loads and queries the generated schema.org property catalog.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Schema;

defined( 'ABSPATH' ) || exit;

/**
 * Thin accessor around includes/Schema/data/catalog.php, the machine-generated
 * list of valid schema.org properties per type (schema.org v{version}).
 */
final class SchemaCatalog {

	private static ?SchemaCatalog $instance = null;

	/**
	 * @var array{version:string,types:array<string,array<string,array<string,string>>>}
	 */
	private array $data;

	private function __construct() {
		$file = HGSD_PATH . 'includes/Schema/data/catalog.php';
		$data = is_readable( $file ) ? require $file : [];
		$this->data = [
			'version' => (string) ( $data['version'] ?? 'unknown' ),
			'types'   => is_array( $data['types'] ?? null ) ? $data['types'] : [],
		];
	}

	public static function instance(): SchemaCatalog {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * The schema.org vocabulary version this catalog was generated from.
	 */
	public function version(): string {
		return $this->data['version'];
	}

	/**
	 * All scalar-mappable properties for a schema.org class.
	 *
	 * @return array<string,array<string,string>> name => [label, type, comment]
	 */
	public function properties( string $class ): array {
		return $this->data['types'][ $class ] ?? [];
	}
}
