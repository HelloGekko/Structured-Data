<?php
/**
 * Generates includes/Schema/data/catalog.php from the official schema.org
 * machine-readable vocabulary.
 *
 * Usage:
 *   1. Download the latest vocabulary:
 *      curl -o schemaorg.jsonld https://schema.org/version/latest/schemaorg-current-https.jsonld
 *   2. Run:
 *      php bin/generate-catalog.php /path/to/schemaorg.jsonld 30.0
 *
 * The catalog lists, per supported type, every schema.org property that is
 * valid for that type (direct + inherited) and can hold a single scalar value,
 * together with a humanised label, the derived data type and the official
 * description. Object-valued properties (Person, PostalAddress, …) are handled
 * through the curated nested mappings in the schema type classes.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

if ( PHP_SAPI !== 'cli' ) {
	exit( 'CLI only.' );
}

$source  = $argv[1] ?? '';
$version = $argv[2] ?? 'unknown';

if ( '' === $source || ! is_readable( $source ) ) {
	fwrite( STDERR, "Provide a readable path to schemaorg-current-https.jsonld\n" );
	exit( 1 );
}

/** Supported types: our key => schema.org class. */
$targets = [
	'Article'       => 'Article',
	'BlogPosting'   => 'BlogPosting',
	'Book'          => 'Book',
	'FAQPage'       => 'FAQPage',
	'NewsArticle'   => 'NewsArticle',
	'WebPage'       => 'WebPage',
	'ItemPage'      => 'ItemPage',
	'Event'         => 'Event',
	'LocalBusiness' => 'LocalBusiness',
	'Product'       => 'Product',
	'Service'       => 'Service',
	'Person'        => 'Person',
	'Organization'  => 'Organization',
];

$data  = json_decode( (string) file_get_contents( $source ), true );
$graph = $data['@graph'] ?? [];

$classes    = [];
$properties = [];
foreach ( $graph as $node ) {
	$id   = $node['@id'] ?? '';
	$type = $node['@type'] ?? '';
	if ( 'rdfs:Class' === $type ) {
		$classes[ $id ] = $node;
	} elseif ( 'rdf:Property' === $type ) {
		$properties[ $id ] = $node;
	}
}

/** Normalise a value that may be a single node or a list of nodes to an id list. */
$ids = static function ( $value ): array {
	if ( empty( $value ) ) {
		return [];
	}
	$value = isset( $value['@id'] ) ? [ $value ] : $value;
	$out   = [];
	foreach ( (array) $value as $item ) {
		if ( isset( $item['@id'] ) ) {
			$out[] = $item['@id'];
		}
	}
	return $out;
};

/** All ancestors of a class (including itself) via rdfs:subClassOf. */
$ancestors = static function ( string $class ) use ( $classes, $ids ): array {
	$seen  = [];
	$stack = [ 'schema:' . $class ];
	while ( $stack ) {
		$current = array_pop( $stack );
		if ( isset( $seen[ $current ] ) ) {
			continue;
		}
		$seen[ $current ] = true;
		foreach ( $ids( $classes[ $current ]['rdfs:subClassOf'] ?? null ) as $parent ) {
			$stack[] = $parent;
		}
	}
	return $seen;
};

$datatypes = [ 'Text', 'URL', 'Date', 'DateTime', 'Time', 'Number', 'Integer', 'Float', 'Boolean', 'CssSelectorType', 'XPathType', 'PronounceableText' ];

/** Classify a property's expected data type from its rangeIncludes. */
$classify = static function ( array $ranges ): ?string {
	$set = array_flip( $ranges );
	if ( isset( $set['Boolean'] ) ) {
		return 'boolean';
	}
	// Number beats date for mixed ranges (merchantReturnDays: Integer|Date) —
	// a plain "30" must stay a number, not be coerced into a date.
	if ( isset( $set['Number'] ) || isset( $set['Integer'] ) || isset( $set['Float'] ) ) {
		return 'number';
	}
	if ( isset( $set['Date'] ) || isset( $set['DateTime'] ) ) {
		return 'date';
	}
	if ( isset( $set['Time'] ) ) {
		return 'time';
	}
	if ( isset( $set['Text'] ) || isset( $set['PronounceableText'] ) || isset( $set['CssSelectorType'] ) || isset( $set['XPathType'] ) ) {
		return 'text';
	}
	if ( isset( $set['URL'] ) ) {
		return 'url';
	}
	return null; // Object-only property.
};

/** Humanise a camelCase property name: articleBody -> Article Body. */
$humanise = static function ( string $name ): string {
	$spaced = preg_replace( '/(?<!^)([A-Z])/', ' $1', $name );
	return ucfirst( trim( (string) $spaced ) );
};

$clean = static function ( $comment ): string {
	// rdfs:comment may be a plain string, a language-tagged object, or a list.
	if ( is_array( $comment ) ) {
		if ( isset( $comment['@value'] ) ) {
			$comment = $comment['@value'];
		} else {
			$first   = reset( $comment );
			$comment = is_array( $first ) ? ( $first['@value'] ?? '' ) : (string) $first;
		}
	}
	$comment = wp_strip_tags_fallback( (string) $comment );
	// schema.org wiki-style links: [[CreativeWork]] -> CreativeWork.
	$comment = preg_replace( '/\[\[([^\]]+)\]\]/', '$1', (string) $comment );
	$comment = preg_replace( '/\s+/', ' ', (string) $comment );
	return trim( (string) $comment );
};

function wp_strip_tags_fallback( string $s ): string {
	return trim( strip_tags( $s ) );
}

// Classes that are (transitively) schema:Enumeration — their instances are the
// fixed set of allowed values for properties ranged at them.
$enum_classes = [];
foreach ( $classes as $cid => $class_node ) {
	$short = substr( $cid, strlen( 'schema:' ) );
	if ( isset( $ancestors( $short )['schema:Enumeration'] ) ) {
		$enum_classes[ $cid ] = true;
	}
}

// Collect the members of every enumeration class.
$enum_members = [];
foreach ( $graph as $node ) {
	$raw = $node['@type'] ?? null;
	if ( ! $raw ) {
		continue;
	}
	$types = is_array( $raw ) ? $raw : [ $raw ];
	foreach ( $types as $type_id ) {
		if ( ! is_string( $type_id ) || ! isset( $enum_classes[ $type_id ] ) ) {
			continue;
		}
		$member_id = $node['@id'] ?? '';
		if ( ! is_string( $member_id ) || 0 !== strpos( $member_id, 'schema:' ) ) {
			continue;
		}
		$member_short                 = substr( $member_id, strlen( 'schema:' ) );
		$enum_members[ $type_id ][]   = [
			'value' => 'https://schema.org/' . $member_short,
			'label' => $member_short,
		];
	}
}

// Allowed values for every enumeration-valued property, across the whole
// vocabulary (so curated nested props like offers.availability find them too).
// Only pure-enum properties qualify: when the range also allows a scalar type
// (e.g. category = Text|URL|Thing|PhysicalActivityCategory) the user must be
// able to type freely, so no fixed list is attached.
$global_enums = [];
foreach ( $properties as $pid => $prop ) {
	$name      = substr( $pid, strlen( 'schema:' ) );
	$range_ids = $ids( $prop['schema:rangeIncludes'] ?? null );
	$ranges    = array_map( static fn( $r ) => substr( $r, strlen( 'schema:' ) ), $range_ids );
	if ( null !== $classify( $ranges ) ) {
		continue; // Scalar datatype allowed — keep it a free field.
	}
	foreach ( $range_ids as $rid ) {
		if ( isset( $enum_members[ $rid ] ) ) {
			$global_enums[ $name ] = array_slice( $enum_members[ $rid ], 0, 60 );
			break;
		}
	}
}

/**
 * The scalar (and pure-enum) properties valid for a class, direct + inherited.
 * Shared by the per-type entries and the object sub-property expansion.
 */
$scalar_props_for = static function ( string $class ) use ( $properties, $ancestors, $ids, $classify, $humanise, $clean, $enum_members, $global_enums ): array {
	$anc   = $ancestors( $class );
	$entry = [];

	foreach ( $properties as $pid => $prop ) {
		$domains = $ids( $prop['schema:domainIncludes'] ?? null );
		if ( ! array_intersect( $domains, array_keys( $anc ) ) ) {
			continue;
		}

		$range_ids = $ids( $prop['schema:rangeIncludes'] ?? null );
		$ranges    = array_map( static fn( $r ) => substr( $r, strlen( 'schema:' ) ), $range_ids );
		$datatype  = $classify( $ranges );

		// Enumeration-valued properties become a fixed list of allowed values.
		if ( null === $datatype ) {
			$has_members = false;
			foreach ( $range_ids as $rid ) {
				if ( isset( $enum_members[ $rid ] ) ) {
					$has_members = true;
					break;
				}
			}
			if ( ! $has_members ) {
				continue; // Object-only property.
			}
			$datatype = 'enum';
		}

		$name = substr( $pid, strlen( 'schema:' ) );

		$entry[ $name ] = [
			'label'   => $humanise( $name ),
			'type'    => $datatype,
			'comment' => $clean( $prop['rdfs:comment'] ?? '' ),
		];

		if ( 'enum' === $datatype && isset( $global_enums[ $name ] ) ) {
			$entry[ $name ]['enum'] = $global_enums[ $name ];
		}
	}

	ksort( $entry );
	return $entry;
};

$datatype_names = array_flip( $datatypes );

/**
 * Object-valued properties of a class: property => target class to expand.
 * Picks the first defined, non-enumeration class in the range.
 */
$object_props_for = static function ( string $class ) use ( $properties, $ancestors, $ids, $classify, $humanise, $clean, $enum_members, $classes, $datatype_names ): array {
	$anc = $ancestors( $class );
	$out = [];

	foreach ( $properties as $pid => $prop ) {
		$domains = $ids( $prop['schema:domainIncludes'] ?? null );
		if ( ! array_intersect( $domains, array_keys( $anc ) ) ) {
			continue;
		}

		$range_ids = $ids( $prop['schema:rangeIncludes'] ?? null );
		$ranges    = array_map( static fn( $r ) => substr( $r, strlen( 'schema:' ) ), $range_ids );
		if ( null !== $classify( $ranges ) ) {
			continue; // Scalar — already covered.
		}

		$target = '';
		foreach ( $range_ids as $rid ) {
			$short = substr( $rid, strlen( 'schema:' ) );
			if ( isset( $enum_members[ $rid ] ) || isset( $datatype_names[ $short ] ) ) {
				continue;
			}
			if ( isset( $classes[ $rid ] ) ) {
				$target = $short;
				break;
			}
		}
		if ( '' === $target ) {
			continue; // Pure enumeration (handled as enum) or unknown range.
		}

		$name         = substr( $pid, strlen( 'schema:' ) );
		$out[ $name ] = [
			'label'   => $humanise( $name ),
			'class'   => $target,
			'comment' => $clean( $prop['rdfs:comment'] ?? '' ),
		];
	}

	ksort( $out );
	return $out;
};

$catalog     = [];
$objects_map = [];
$class_props = [];

foreach ( $targets as $key => $class ) {
	$catalog[ $key ]     = $scalar_props_for( $class );
	$objects_map[ $key ] = $object_props_for( $class );

	// Collect the sub-property sets for every referenced target class.
	foreach ( $objects_map[ $key ] as $object ) {
		$target = $object['class'];
		if ( ! isset( $class_props[ $target ] ) ) {
			$class_props[ $target ] = $scalar_props_for( $target );
		}
	}
}
ksort( $class_props );

ksort( $global_enums );

$export = var_export(
	[
		'version' => $version,
		'types'   => $catalog,
		'enums'   => $global_enums,
		'objects' => $objects_map,
		'classes' => $class_props,
	],
	true
);

$header = "<?php\n"
	. "/**\n"
	. " * Auto-generated schema.org property catalog. DO NOT EDIT BY HAND.\n"
	. " * Generated from the official schema.org vocabulary by bin/generate-catalog.php.\n"
	. " *\n"
	. " * @package HelloGekko\\StructuredData\n"
	. " */\n\n"
	. "declare( strict_types=1 );\n\n"
	. "return ";

$out = $header . $export . ";\n";

$target = dirname( __DIR__ ) . '/includes/Schema/data/catalog.php';
file_put_contents( $target, $out );

$total   = array_sum( array_map( 'count', $catalog ) );
$objects = array_sum( array_map( 'count', $objects_map ) );
fwrite( STDOUT, "Wrote {$target}\nschema.org version {$version}: {$total} scalar properties, {$objects} object properties (" . count( $class_props ) . ' expandable classes) across ' . count( $catalog ) . " types.\n" );
