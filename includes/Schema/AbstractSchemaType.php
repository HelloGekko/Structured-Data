<?php
/**
 * Base class for every supported schema.org type.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Schema;

use HelloGekko\StructuredData\SchemaDefinition;
use HelloGekko\StructuredData\Output\PropertyResolver;

defined( 'ABSPATH' ) || exit;

/**
 * Defines the available properties of a schema type and knows how to turn a
 * stored definition into a JSON-LD node.
 */
abstract class AbstractSchemaType {

	/**
	 * Unique key, also used as the default schema.org @type.
	 */
	abstract public function key(): string;

	/**
	 * Human readable label shown in the wizard.
	 */
	abstract public function label(): string;

	/**
	 * The schema.org @type value emitted in the JSON-LD.
	 */
	public function type_value(): string {
		return $this->key();
	}

	/**
	 * Optional grouping shown in the type picker (e.g. "Content", "Local").
	 */
	public function group(): string {
		return __( 'General', 'hg-structured-data' );
	}

	/**
	 * Available properties for this type.
	 *
	 * Dotted keys (e.g. "author.name") describe nested objects. The first
	 * segment is wrapped using {@see nested_types()}.
	 *
	 * @return array<string,array{label:string,recommended?:bool,type?:string,description?:string}>
	 */
	abstract public function properties(): array;

	/**
	 * Nested object @type per top-level property segment.
	 *
	 * @return array<string,string>
	 */
	public function nested_types(): array {
		return [];
	}

	/**
	 * Sensible default mappings so a freshly created schema already outputs
	 * useful data without manual configuration.
	 *
	 * @return array<int,array{property:string,source:string,value:string}>
	 */
	public function default_mappings(): array {
		return [];
	}

	/**
	 * Build the JSON-LD node from a stored definition.
	 *
	 * @param array<string,mixed> $context Runtime context (post_id, queried_object...).
	 * @return array<string,mixed>|null
	 */
	public function build( SchemaDefinition $def, PropertyResolver $resolver, array $context ): ?array {
		$node = [
			'@context' => 'https://schema.org',
			'@type'    => $this->type_value(),
		];

		foreach ( $def->properties() as $mapping ) {
			$property = (string) ( $mapping['property'] ?? '' );
			if ( '' === $property ) {
				continue;
			}

			$value = $resolver->resolve( $mapping, $context );
			if ( null === $value || '' === $value || [] === $value ) {
				continue;
			}

			$this->assign( $node, $property, $value );
		}

		// A node with only @context/@type is not worth emitting.
		if ( count( $node ) <= 2 ) {
			return null;
		}

		return $node;
	}

	/**
	 * Assign a (possibly dotted) property path into the node, wrapping nested
	 * objects with their @type.
	 *
	 * @param array<string,mixed> $node  Node being built (by reference).
	 * @param mixed               $value Resolved value.
	 */
	protected function assign( array &$node, string $path, $value ): void {
		if ( ! str_contains( $path, '.' ) ) {
			$node[ $path ] = $value;
			return;
		}

		[ $parent, $child ] = explode( '.', $path, 2 );

		if ( ! isset( $node[ $parent ] ) || ! is_array( $node[ $parent ] ) ) {
			$node[ $parent ] = [];
			$nested_type     = $this->nested_types()[ $parent ] ?? null;
			if ( $nested_type ) {
				$node[ $parent ]['@type'] = $nested_type;
			}
		}

		// Support a single further level of nesting (e.g. address.geo.latitude).
		if ( str_contains( $child, '.' ) ) {
			[ $sub_parent, $sub_child ] = explode( '.', $child, 2 );
			if ( ! isset( $node[ $parent ][ $sub_parent ] ) || ! is_array( $node[ $parent ][ $sub_parent ] ) ) {
				$node[ $parent ][ $sub_parent ] = [];
				$nested_type                    = $this->nested_types()[ $parent . '.' . $sub_parent ] ?? null;
				if ( $nested_type ) {
					$node[ $parent ][ $sub_parent ]['@type'] = $nested_type;
				}
			}
			$node[ $parent ][ $sub_parent ][ $sub_child ] = $value;
			return;
		}

		$node[ $parent ][ $child ] = $value;
	}

	/**
	 * Shared property groups reused across content types (Article, BlogPosting...).
	 *
	 * @return array<string,array<string,mixed>>
	 */
	protected function content_properties(): array {
		return [
			'headline'         => [
				'label'       => __( 'Headline', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'text',
			],
			'description'      => [
				'label' => __( 'Description', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'image'            => [
				'label'       => __( 'Image (URL)', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'image',
			],
			'datePublished'    => [
				'label'       => __( 'Date published', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'date',
			],
			'dateModified'     => [
				'label' => __( 'Date modified', 'hg-structured-data' ),
				'type'  => 'date',
			],
			'author.name'      => [
				'label'       => __( 'Author name', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'text',
			],
			'author.url'       => [
				'label' => __( 'Author URL', 'hg-structured-data' ),
				'type'  => 'url',
			],
			'publisher.name'   => [
				'label'       => __( 'Publisher name', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'text',
			],
			'publisher.logo.url' => [
				'label' => __( 'Publisher logo (URL)', 'hg-structured-data' ),
				'type'  => 'image',
			],
			'mainEntityOfPage' => [
				'label' => __( 'Main entity of page (URL)', 'hg-structured-data' ),
				'type'  => 'url',
			],
			'url'              => [
				'label' => __( 'URL', 'hg-structured-data' ),
				'type'  => 'url',
			],
			'inLanguage'       => [
				'label' => __( 'Language', 'hg-structured-data' ),
				'type'  => 'text',
			],
		];
	}

	/**
	 * Nested @type map shared by content types.
	 *
	 * @return array<string,string>
	 */
	protected function content_nested_types(): array {
		return [
			'author'           => 'Person',
			'publisher'        => 'Organization',
			'publisher.logo'   => 'ImageObject',
		];
	}

	/**
	 * Default mappings shared by content types.
	 *
	 * @return array<int,array<string,string>>
	 */
	protected function content_defaults(): array {
		return [
			[ 'property' => 'headline', 'source' => 'wp', 'value' => 'post_title' ],
			[ 'property' => 'description', 'source' => 'wp', 'value' => 'post_excerpt' ],
			[ 'property' => 'image', 'source' => 'wp', 'value' => 'featured_image' ],
			[ 'property' => 'datePublished', 'source' => 'wp', 'value' => 'post_date' ],
			[ 'property' => 'dateModified', 'source' => 'wp', 'value' => 'post_modified' ],
			[ 'property' => 'author.name', 'source' => 'wp', 'value' => 'author_name' ],
			[ 'property' => 'author.url', 'source' => 'wp', 'value' => 'author_url' ],
			[ 'property' => 'publisher.name', 'source' => 'wp', 'value' => 'site_name' ],
			[ 'property' => 'mainEntityOfPage', 'source' => 'wp', 'value' => 'permalink' ],
			[ 'property' => 'url', 'source' => 'wp', 'value' => 'permalink' ],
			[ 'property' => 'inLanguage', 'source' => 'wp', 'value' => 'site_language' ],
		];
	}
}
