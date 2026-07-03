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
			'enums'   => is_array( $data['enums'] ?? null ) ? $data['enums'] : [],
			'objects' => is_array( $data['objects'] ?? null ) ? $data['objects'] : [],
			'classes' => is_array( $data['classes'] ?? null ) ? $data['classes'] : [],
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

	/**
	 * Allowed values for an enumeration-valued property (by property name).
	 *
	 * @return array<int,array{value:string,label:string}>
	 */
	public function enum( string $property ): array {
		return $this->data['enums'][ $property ] ?? [];
	}

	/**
	 * Object-valued properties for a schema.org class: property => target class.
	 *
	 * @return array<string,array{label:string,class:string,comment:string}>
	 */
	public function objects( string $class ): array {
		return $this->data['objects'][ $class ] ?? [];
	}

	/**
	 * Scalar sub-properties of an expandable target class (e.g. MerchantReturnPolicy).
	 *
	 * @return array<string,array<string,mixed>>
	 */
	public function class_properties( string $class ): array {
		return $this->data['classes'][ $class ] ?? [];
	}
}
