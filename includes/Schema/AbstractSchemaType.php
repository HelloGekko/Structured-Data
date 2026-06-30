<?php
/**
 * Base class for every supported schema.org type.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Schema;

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
	 * Whether this type can carry review / aggregateRating markup.
	 */
	public function supports_reviews(): bool {
		return false;
	}

	/**
	 * Curated, recommended properties for this type — the ones shown first in
	 * the wizard. Dotted keys (e.g. "author.name") describe nested objects;
	 * the first segment is wrapped using {@see nested_types()}.
	 *
	 * @return array<string,array{label:string,type?:string,description?:string}>
	 */
	abstract public function recommended(): array;

	/**
	 * The schema.org class used to look up the full property catalog.
	 */
	public function catalog_key(): string {
		return $this->type_value();
	}

	/**
	 * Complete property set: curated recommended properties first, followed by
	 * every other scalar-mappable schema.org property valid for this type.
	 *
	 * @return array<string,array<string,mixed>>
	 */
	public function properties(): array {
		$catalog = SchemaCatalog::instance()->properties( $this->catalog_key() );
		$out     = [];

		foreach ( $this->recommended() as $key => $def ) {
			$def['recommended'] = true;
			if ( empty( $def['comment'] ) && isset( $catalog[ $key ]['comment'] ) ) {
				$def['comment'] = $catalog[ $key ]['comment'];
			}
			$out[ $key ] = $def;
		}

		foreach ( $catalog as $key => $def ) {
			if ( isset( $out[ $key ] ) ) {
				continue;
			}
			$def['recommended'] = false;
			$out[ $key ]        = $def;
		}

		// Attach enumeration values by the property's leaf name so both flat and
		// nested keys (e.g. offers.availability) offer a fixed value list.
		$catalog_obj = SchemaCatalog::instance();
		foreach ( $out as $key => &$def ) {
			if ( ! empty( $def['enum'] ) ) {
				continue;
			}
			$leaf = false !== strpos( $key, '.' ) ? substr( $key, strrpos( $key, '.' ) + 1 ) : $key;
			$enum = $catalog_obj->enum( $leaf );
			if ( $enum ) {
				$def['enum'] = $enum;
				$def['type'] = 'enum';
			}
		}
		unset( $def );

		return $out;
	}

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
	 * Build the JSON-LD node from a configuration array.
	 *
	 * @param array{properties?:array<int,array<string,mixed>>,faq?:array<string,mixed>} $config  Schema config.
	 * @param array<string,mixed>                                                        $context Runtime context (post_id...).
	 * @return array<string,mixed>|null
	 */
	public function build( array $config, PropertyResolver $resolver, array $context ): ?array {
		$node = [
			'@context' => 'https://schema.org',
			'@type'    => $this->type_value(),
		];

		$definitions = $this->properties();
		$mappings    = isset( $config['properties'] ) && is_array( $config['properties'] ) ? $config['properties'] : [];

		foreach ( $mappings as $mapping ) {
			$property = (string) ( $mapping['property'] ?? '' );
			if ( '' === $property ) {
				continue;
			}

			$value = $resolver->resolve( $mapping, $context );
			if ( null === $value || '' === $value || [] === $value ) {
				continue;
			}

			// Cast to the schema.org-expected data type for valid JSON-LD.
			$type  = (string) ( $definitions[ $property ]['type'] ?? 'text' );
			$value = $this->cast( $value, $type );

			$this->assign( $node, $property, $value );
		}

		if ( $this->supports_reviews() ) {
			$this->apply_reviews(
				$node,
				isset( $config['reviews'] ) && is_array( $config['reviews'] ) ? $config['reviews'] : [],
				isset( $context['reviews'] ) && is_array( $context['reviews'] ) ? $context['reviews'] : []
			);
		}

		// A node with only @context/@type is not worth emitting.
		if ( count( $node ) <= 2 ) {
			return null;
		}

		return $node;
	}

	/**
	 * Append aggregateRating and/or individual review nodes from cached review data.
	 *
	 * @param array<string,mixed> $node Node being built (by reference).
	 * @param array<string,mixed> $cfg  Per-schema reviews config (enabled/aggregate/individual).
	 * @param array<string,mixed> $data Cached normalised review payload (aggregate/items).
	 */
	protected function apply_reviews( array &$node, array $cfg, array $data ): void {
		if ( empty( $cfg['enabled'] ) ) {
			return;
		}

		$aggregate = is_array( $data['aggregate'] ?? null ) ? $data['aggregate'] : [];
		$items     = is_array( $data['items'] ?? null ) ? $data['items'] : [];

		if ( ! empty( $cfg['aggregate'] ) && ! empty( $aggregate['reviewCount'] ) && ! empty( $aggregate['ratingValue'] ) ) {
			$node['aggregateRating'] = [
				'@type'       => 'AggregateRating',
				'ratingValue' => (float) $aggregate['ratingValue'],
				'reviewCount' => (int) $aggregate['reviewCount'],
				'bestRating'  => (int) ( $aggregate['bestRating'] ?? 5 ),
			];
		}

		if ( ! empty( $cfg['individual'] ) && $items ) {
			$reviews = [];
			foreach ( $items as $item ) {
				$review = [ '@type' => 'Review' ];
				if ( ! empty( $item['author'] ) ) {
					$review['author'] = [
						'@type' => 'Person',
						'name'  => (string) $item['author'],
					];
				}
				if ( ! empty( $item['rating'] ) ) {
					$review['reviewRating'] = [
						'@type'       => 'Rating',
						'ratingValue' => (int) $item['rating'],
						'bestRating'  => 5,
					];
				}
				if ( ! empty( $item['text'] ) ) {
					$review['reviewBody'] = (string) $item['text'];
				}
				if ( ! empty( $item['date'] ) ) {
					$review['datePublished'] = $this->iso_date( (string) $item['date'] );
				}
				$reviews[] = $review;
			}
			if ( $reviews ) {
				$node['review'] = $reviews;
			}
		}
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
			$this->set_or_append( $node, $path, $value );
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
			$this->set_or_append( $node[ $parent ][ $sub_parent ], $sub_child, $value );
			return;
		}

		$this->set_or_append( $node[ $parent ], $child, $value );
	}

	/**
	 * Set a property, or collect it into a list when the same property is
	 * mapped more than once (e.g. several sameAs / social profile URLs).
	 *
	 * @param array<string,mixed> $container Target array (by reference).
	 * @param mixed               $value     Value to set or append.
	 */
	private function set_or_append( array &$container, string $key, $value ): void {
		if ( ! array_key_exists( $key, $container ) ) {
			$container[ $key ] = $value;
			return;
		}

		$existing = $container[ $key ];

		// Already a plain (list) array of values — append.
		if ( is_array( $existing ) && array_keys( $existing ) === range( 0, count( $existing ) - 1 ) ) {
			$existing[]        = $value;
			$container[ $key ] = $existing;
			return;
		}

		// Scalar or typed object — turn into a list of both.
		$container[ $key ] = [ $existing, $value ];
	}

	/**
	 * Cast a resolved (string) value to the schema.org-expected data type so the
	 * emitted JSON-LD uses real numbers, booleans and ISO 8601 dates.
	 *
	 * @param mixed $value Resolved value.
	 * @return mixed
	 */
	protected function cast( $value, string $type ) {
		if ( ! is_string( $value ) ) {
			return $value;
		}

		switch ( $type ) {
			case 'number':
				if ( ! is_numeric( $value ) ) {
					return $value;
				}
				return ( false !== strpos( $value, '.' ) ) ? (float) $value : (int) $value;

			case 'boolean':
				$normalized = strtolower( trim( $value ) );
				if ( in_array( $normalized, [ '1', 'true', 'yes', 'on' ], true ) ) {
					return true;
				}
				if ( in_array( $normalized, [ '0', 'false', 'no', 'off' ], true ) ) {
					return false;
				}
				return (bool) $value;

			case 'date':
				return $this->iso_date( $value );

			default:
				return $value;
		}
	}

	/**
	 * Normalise a date string to ISO 8601, leaving already-valid values intact.
	 */
	protected function iso_date( string $value ): string {
		// Already ISO 8601 (date or datetime).
		if ( preg_match( '/^\d{4}-\d{2}-\d{2}([T ]\d{2}:\d{2})?/', $value ) ) {
			return $value;
		}

		$timestamp = strtotime( $value );
		return false === $timestamp ? $value : gmdate( 'c', $timestamp );
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
