<?php
/**
 * Emits JSON-LD structured data on the front-end.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Output;

use HelloGekko\StructuredData\Display\DisplayConditions;
use HelloGekko\StructuredData\Graph\RelationEmitter;
use HelloGekko\StructuredData\Graph\RelationRepository;
use HelloGekko\StructuredData\Graph\SchemaMatcher;
use HelloGekko\StructuredData\Reviews\ReviewsManager;
use HelloGekko\StructuredData\Schema\SchemaRegistry;
use HelloGekko\StructuredData\SchemaDefinition;

defined( 'ABSPATH' ) || exit;

/**
 * Collects all matching schema definitions and prints their JSON-LD in the head.
 */
final class FrontendOutput {

	private SchemaRegistry $registry;
	private DisplayConditions $conditions;
	private PropertyResolver $resolver;
	private ReviewsManager $reviews;
	private ?RelationRepository $relations;

	/**
	 * @var array<int,string>
	 */
	private array $emitted_types = [];

	public function __construct( SchemaRegistry $registry, ReviewsManager $reviews, ?RelationRepository $relations = null ) {
		$this->registry   = $registry;
		$this->conditions = new DisplayConditions();
		$this->resolver   = new PropertyResolver();
		$this->reviews    = $reviews;
		$this->relations  = $relations;
	}

	public function register_hooks(): void {
		add_action( 'wp_head', [ $this, 'render' ], 20 );
	}

	/**
	 * Build and print the JSON-LD for the current request.
	 */
	public function render(): void {
		if ( is_admin() || is_feed() || is_embed() ) {
			return;
		}

		$context = $this->context();
		$nodes   = [];

		foreach ( $this->definitions() as $def ) {
			if ( ! $def->enabled() ) {
				continue;
			}

			$type = $this->registry->get( $def->type() );
			if ( ! $type ) {
				continue;
			}

			if ( ! $this->conditions->should_display( $def, $context ) ) {
				continue;
			}

			$config = [
				'properties' => $def->properties(),
				'faq'        => $def->faq(),
				'reviews'    => $def->reviews(),
			];

			$node = $type->build( $config, $this->resolver, $this->source_context( $context, $def ) );
			if ( null !== $node ) {
				$nodes[] = $node;
			}
		}

		/**
		 * Filter the full set of JSON-LD nodes before output.
		 *
		 * @param array<int,array<string,mixed>> $nodes   The schema nodes.
		 * @param array<string,mixed>             $context Runtime context.
		 */
		$nodes = $this->attach_relations( $nodes, (int) $context['post_id'] );

		// Automatic BreadcrumbList, output alongside our other JSON-LD so it is
		// marked as ours and counts towards emitted_types (dedupe-aware).
		$breadcrumb = Breadcrumbs::node( (int) $context['post_id'] );
		if ( null !== $breadcrumb ) {
			$nodes[] = $breadcrumb;
		}

		$nodes = apply_filters( 'hgsd_output_nodes', $nodes, $context );

		foreach ( $nodes as $node ) {
			if ( isset( $node['@type'] ) ) {
				foreach ( (array) $node['@type'] as $type ) {
					$this->emitted_types[] = (string) $type;
				}
			}
			$this->print_node( $node );
		}
	}

	/**
	 * The schema.org @type values this plugin has emitted on the current request.
	 *
	 * @return array<int,string>
	 */
	public function emitted_types(): array {
		return array_values( array_unique( $this->emitted_types ) );
	}

	/**
	 * Build (but do not print) the schema nodes for a specific post, ignoring
	 * display conditions. Used by the AI/Markdown output.
	 *
	 * @return array<int,array<string,mixed>>
	 */
	public function nodes_for_post( int $post_id ): array {
		$context = [
			'post_id'        => $post_id,
			'author_id'      => $post_id ? (int) get_post_field( 'post_author', $post_id ) : 0,
			'queried_object' => $post_id ? get_post( $post_id ) : null,
			'reviews'        => $this->reviews->data(),
		];

		$nodes = [];
		foreach ( $this->definitions() as $def ) {
			if ( ! $def->enabled() ) {
				continue;
			}
			$type = $this->registry->get( $def->type() );
			if ( ! $type ) {
				continue;
			}
			$config = [
				'properties' => $def->properties(),
				'faq'        => $def->faq(),
				'reviews'    => $def->reviews(),
			];
			$node = $type->build( $config, $this->resolver, $this->source_context( $context, $def ) );
			if ( null !== $node ) {
				$nodes[] = $node;
			}
		}

		return $this->attach_relations( $nodes, $post_id );
	}

	/**
	 * Attach curated relations to the page's primary node as @id references.
	 *
	 * @param array<int,array<string,mixed>> $nodes Built nodes.
	 * @return array<int,array<string,mixed>>
	 */
	private function attach_relations( array $nodes, int $post_id ): array {
		if ( null === $this->relations || ! $post_id || empty( $nodes ) ) {
			return $nodes;
		}

		$relations = $this->relations->for_source( $post_id );
		if ( empty( $relations ) ) {
			return $nodes;
		}

		return RelationEmitter::attach(
			$nodes,
			$relations,
			(string) get_permalink( $post_id ),
			fn( int $target ): string => $this->target_ref( $target )
		);
	}

	/**
	 * The @id a relation should reference for a target post: the target's own
	 * primary schema node when one applies there, otherwise its permalink.
	 */
	private function target_ref( int $target_id ): string {
		$permalink = (string) get_permalink( $target_id );
		if ( '' === $permalink ) {
			return '';
		}

		static $matcher = null;
		if ( null === $matcher ) {
			$matcher = new SchemaMatcher();
		}

		$post = get_post( $target_id );
		if ( ! $post ) {
			return $permalink;
		}

		foreach ( $this->definitions() as $def ) {
			if ( ! $def->enabled() || ! $matcher->matches( $def, $post ) ) {
				continue;
			}
			$type = $this->registry->get( $def->type() );
			if ( $type ) {
				return $permalink . '#' . strtolower( $type->type_value() );
			}
		}

		return $permalink;
	}

	/**
	 * Print a single JSON-LD script tag.
	 *
	 * @param array<string,mixed> $node Schema node.
	 */
	private function print_node( array $node ): void {
		$json = wp_json_encode( $node, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
		if ( false === $json ) {
			return;
		}

		// JSON-LD must be safe inside a <script> element; escape closing tags.
		$json = str_replace( '</', '<\/', $json );

		// data-hgsd marks our output so the conflict "strip foreign JSON-LD" mode keeps it.
		echo "\n<script type=\"application/ld+json\" data-hgsd=\"1\">" . $json . "</script>\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Apply a definition's field-source setting on top of the base context.
	 *
	 * @param array<string,mixed> $context Base runtime context.
	 * @return array<string,mixed>
	 */
	private function source_context( array $context, SchemaDefinition $def ): array {
		$source = $def->source();

		if ( 'post' === $source['mode'] && $source['post_id'] ) {
			$context['post_id']   = $source['post_id'];
			$context['author_id'] = (int) get_post_field( 'post_author', $source['post_id'] );
		} elseif ( 'option' === $source['mode'] ) {
			$context['acf_source'] = 'option';
		}

		return $context;
	}

	/**
	 * Build the runtime context for condition evaluation and resolution.
	 *
	 * @return array<string,mixed>
	 */
	private function context(): array {
		$post_id = 0;
		if ( is_singular() ) {
			$post_id = (int) get_queried_object_id();
		}

		return [
			'post_id'        => $post_id,
			'author_id'      => $post_id ? (int) get_post_field( 'post_author', $post_id ) : 0,
			'queried_object' => get_queried_object(),
			'reviews'        => $this->reviews->data(),
		];
	}

	/**
	 * Load all published schema definitions.
	 *
	 * @return array<int,SchemaDefinition>
	 */
	private function definitions(): array {
		static $cache = null;
		if ( null !== $cache ) {
			return $cache;
		}

		$ids = get_posts(
			[
				'post_type'              => HGSD_CPT,
				'post_status'            => 'publish',
				'numberposts'            => -1,
				'fields'                 => 'ids',
				'no_found_rows'          => true,
				'update_post_term_cache' => false,
			]
		);

		$cache = array_map( static fn( $id ) => new SchemaDefinition( (int) $id ), $ids );
		return $cache;
	}
}
