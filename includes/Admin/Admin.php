<?php
/**
 * Admin controller: wizard meta boxes, saving and AJAX endpoints.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Admin;

use HelloGekko\StructuredData\Display\DisplayConditions;
use HelloGekko\StructuredData\Output\PropertyResolver;
use HelloGekko\StructuredData\Plugin;
use HelloGekko\StructuredData\Reviews\ReviewsManager;
use HelloGekko\StructuredData\Schema\SchemaRegistry;
use HelloGekko\StructuredData\SchemaDefinition;

defined( 'ABSPATH' ) || exit;

/**
 * Wires up everything needed to configure schemas in wp-admin.
 */
final class Admin {

	private const NONCE = 'hgsd_save_schema';

	private SchemaRegistry $registry;
	private ReviewsManager $reviews;

	public function __construct( SchemaRegistry $registry, ReviewsManager $reviews ) {
		$this->registry = $registry;
		$this->reviews  = $reviews;
	}

	public function register_hooks(): void {
		add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
		add_action( 'save_post_' . HGSD_CPT, [ $this, 'save' ], 10, 2 );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue' ] );

		// AJAX endpoints.
		add_action( 'wp_ajax_hgsd_get_acf_fields', [ $this, 'ajax_acf_fields' ] );
		add_action( 'wp_ajax_hgsd_search_content', [ $this, 'ajax_search_content' ] );
		add_action( 'wp_ajax_hgsd_preview', [ $this, 'ajax_preview' ] );

		// List table columns.
		add_filter( 'manage_' . HGSD_CPT . '_posts_columns', [ $this, 'columns' ] );
		add_action( 'manage_' . HGSD_CPT . '_posts_custom_column', [ $this, 'column_content' ], 10, 2 );
	}

	/**
	 * Register the wizard meta box and the sidebar status box.
	 */
	public function add_meta_boxes(): void {
		add_meta_box(
			'hgsd_wizard',
			__( 'Structured Data', 'hg-structured-data' ),
			[ $this, 'render_wizard' ],
			HGSD_CPT,
			'normal',
			'high'
		);

		add_meta_box(
			'hgsd_status',
			__( 'Status', 'hg-structured-data' ),
			[ $this, 'render_status' ],
			HGSD_CPT,
			'side',
			'high'
		);
	}

	/**
	 * Render the main wizard meta box.
	 */
	public function render_wizard( \WP_Post $post ): void {
		wp_nonce_field( self::NONCE, 'hgsd_nonce' );
		$def = new SchemaDefinition( $post->ID );
		require HGSD_PATH . 'includes/Admin/views/wizard.php';
	}

	/**
	 * Render the sidebar status / enable box.
	 */
	public function render_status( \WP_Post $post ): void {
		$def = new SchemaDefinition( $post->ID );
		require HGSD_PATH . 'includes/Admin/views/status.php';
	}

	/**
	 * Persist the submitted wizard data.
	 */
	public function save( int $post_id, \WP_Post $post ): void {
		if ( ! isset( $_POST['hgsd_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['hgsd_nonce'] ) ), self::NONCE ) ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce verified above.
		$raw = isset( $_POST['hgsd'] ) ? wp_unslash( $_POST['hgsd'] ) : [];
		$raw = is_array( $raw ) ? $raw : [];

		$def = new SchemaDefinition( $post_id );

		$def->set_type( isset( $raw['type'] ) ? (string) $raw['type'] : '' );
		$def->set_enabled( ! empty( $raw['enabled'] ) );
		$def->set_conditions( $this->sanitize_conditions( $raw['conditions'] ?? [] ) );
		$def->set_properties( $this->sanitize_properties( $raw['properties'] ?? [] ) );
		$def->set_faq( $this->sanitize_faq( $raw['faq'] ?? [] ) );
		$def->set_reviews( is_array( $raw['reviews'] ?? null ) ? $raw['reviews'] : [] );
	}

	/**
	 * Sanitize the display conditions structure.
	 *
	 * @param mixed $raw Raw submitted conditions.
	 * @return array<string,mixed>
	 */
	private function sanitize_conditions( $raw ): array {
		$raw   = is_array( $raw ) ? $raw : [];
		$logic = isset( $raw['logic'] ) && 'all' === $raw['logic'] ? 'all' : 'any';

		$clean = static function ( $rules ): array {
			$rules  = is_array( $rules ) ? $rules : [];
			$result = [];
			foreach ( $rules as $rule ) {
				if ( ! is_array( $rule ) || empty( $rule['type'] ) ) {
					continue;
				}
				$result[] = [
					'type'     => sanitize_text_field( (string) $rule['type'] ),
					'operator' => isset( $rule['operator'] ) && 'is_not' === $rule['operator'] ? 'is_not' : 'is',
					'value'    => isset( $rule['value'] ) ? sanitize_text_field( (string) $rule['value'] ) : '',
					'value2'   => isset( $rule['value2'] ) ? sanitize_text_field( (string) $rule['value2'] ) : '',
				];
			}
			return $result;
		};

		return [
			'logic'   => $logic,
			'include' => $clean( $raw['include'] ?? [] ),
			'exclude' => $clean( $raw['exclude'] ?? [] ),
		];
	}

	/**
	 * Sanitize property mappings.
	 *
	 * @param mixed $raw Raw submitted properties.
	 * @return array<int,array<string,string>>
	 */
	private function sanitize_properties( $raw ): array {
		$raw    = is_array( $raw ) ? $raw : [];
		$result = [];
		foreach ( $raw as $row ) {
			if ( ! is_array( $row ) || empty( $row['property'] ) || '__all__' === $row['property'] ) {
				continue;
			}
			$source = in_array( $row['source'] ?? '', [ 'wp', 'acf', 'custom' ], true ) ? $row['source'] : 'wp';
			$value  = (string) ( $row['value'] ?? '' );

			$result[] = [
				'property' => sanitize_text_field( (string) $row['property'] ),
				'source'   => $source,
				// Custom text may legitimately contain punctuation; keep it but strip tags.
				'value'    => 'custom' === $source ? sanitize_textarea_field( $value ) : sanitize_text_field( $value ),
			];
		}
		return $result;
	}

	/**
	 * Sanitize the FAQ configuration.
	 *
	 * @param mixed $raw Raw submitted FAQ config.
	 * @return array<string,mixed>
	 */
	private function sanitize_faq( $raw ): array {
		$raw    = is_array( $raw ) ? $raw : [];
		$method = isset( $raw['method'] ) && 'automatic' === $raw['method'] ? 'automatic' : 'manual';

		$items = [];
		if ( isset( $raw['items'] ) && is_array( $raw['items'] ) ) {
			foreach ( $raw['items'] as $item ) {
				if ( ! is_array( $item ) ) {
					continue;
				}
				$question = sanitize_text_field( (string) ( $item['question'] ?? '' ) );
				$answer   = wp_kses_post( (string) ( $item['answer'] ?? '' ) );
				if ( '' === $question && '' === $answer ) {
					continue;
				}
				$items[] = [
					'question' => $question,
					'answer'   => $answer,
				];
			}
		}

		return [
			'method'            => $method,
			'acf_repeater'      => sanitize_text_field( (string) ( $raw['acf_repeater'] ?? '' ) ),
			'question_subfield' => sanitize_text_field( (string) ( $raw['question_subfield'] ?? '' ) ),
			'answer_subfield'   => sanitize_text_field( (string) ( $raw['answer_subfield'] ?? '' ) ),
			'items'             => $items,
		];
	}

	/**
	 * Enqueue admin assets on the schema edit screens.
	 *
	 * @param string $hook Current admin page hook.
	 */
	public function enqueue( string $hook ): void {
		if ( ! in_array( $hook, [ 'post.php', 'post-new.php' ], true ) ) {
			return;
		}
		$screen = get_current_screen();
		if ( ! $screen || HGSD_CPT !== $screen->post_type ) {
			return;
		}

		wp_enqueue_style( 'hgsd-admin', HGSD_URL . 'assets/css/admin.css', [], HGSD_VERSION );
		wp_enqueue_script( 'hgsd-admin', HGSD_URL . 'assets/js/admin.js', [ 'jquery' ], HGSD_VERSION, true );

		wp_localize_script( 'hgsd-admin', 'HGSD', $this->localized_data() );
	}

	/**
	 * Data passed to the admin JavaScript.
	 *
	 * @return array<string,mixed>
	 */
	private function localized_data(): array {
		$types = [];
		foreach ( $this->registry->all() as $key => $type ) {
			$types[ $key ] = [
				'label'           => $type->label(),
				'group'           => $type->group(),
				'properties'      => $type->properties(),
				'isFaq'           => 'FAQPage' === $type->type_value(),
				'supportsReviews' => $type->supports_reviews(),
			];
		}

		return [
			'ajaxUrl'        => admin_url( 'admin-ajax.php' ),
			'nonce'          => wp_create_nonce( 'hgsd_ajax' ),
			'hasAcf'         => Plugin::has_acf(),
			'hasAcfPro'      => Plugin::has_acf_pro(),
			'schemaVersion'  => \HelloGekko\StructuredData\Schema\SchemaCatalog::instance()->version(),
			'reviewsUrl'     => add_query_arg( [ 'post_type' => HGSD_CPT, 'page' => 'hgsd-reviews' ], admin_url( 'edit.php' ) ),
			'types'          => $types,
			'conditionTypes' => DisplayConditions::types(),
			'wpFields'       => $this->wp_field_choices(),
			'postTypes'      => $this->post_type_choices(),
			'taxonomies'     => $this->taxonomy_choices(),
			'postFormats'    => $this->post_format_choices(),
			'pageTemplates'  => $this->page_template_choices(),
			'i18n'           => [
				'addProperty'   => __( 'Add Property', 'hg-structured-data' ),
				'addRule'       => __( 'Add Condition', 'hg-structured-data' ),
				'addFaq'        => __( 'Add Question', 'hg-structured-data' ),
				'remove'        => __( 'Remove', 'hg-structured-data' ),
				'selectField'   => __( '— Select field —', 'hg-structured-data' ),
				'selectValue'   => __( '— Select —', 'hg-structured-data' ),
				'customText'    => __( 'Custom text', 'hg-structured-data' ),
				'wordpress'     => __( 'WordPress', 'hg-structured-data' ),
				'acf'           => __( 'ACF', 'hg-structured-data' ),
				'noAcf'         => __( 'ACF is not active. Install Advanced Custom Fields to map ACF fields.', 'hg-structured-data' ),
				'searchPosts'   => __( 'Type to search…', 'hg-structured-data' ),
				'question'      => __( 'Question', 'hg-structured-data' ),
				'answer'        => __( 'Answer', 'hg-structured-data' ),
				'recommended'   => __( 'Recommended', 'hg-structured-data' ),
				'allProperties' => __( 'All schema.org properties', 'hg-structured-data' ),
				'showAll'       => __( 'Show all schema.org properties…', 'hg-structured-data' ),
				'previewTitle'  => __( 'Live preview', 'hg-structured-data' ),
				'previewRefresh' => __( 'Refresh', 'hg-structured-data' ),
				'previewLoading' => __( 'Generating preview…', 'hg-structured-data' ),
				'previewEmpty'  => __( 'No output yet — map at least one property with a value.', 'hg-structured-data' ),
				'reviewsTitle'  => __( 'Reviews', 'hg-structured-data' ),
				'reviewsEnable' => __( 'Add reviews to this schema', 'hg-structured-data' ),
				'reviewsAgg'    => __( 'Include aggregate rating', 'hg-structured-data' ),
				'reviewsInd'    => __( 'Include individual reviews', 'hg-structured-data' ),
				'reviewsNote'   => __( 'Reviews are pulled from your configured source on a schedule.', 'hg-structured-data' ),
				'reviewsManage' => __( 'Manage review source →', 'hg-structured-data' ),
			],
		];
	}

	/**
	 * Available WordPress field sources for property mapping.
	 *
	 * @return array<string,string>
	 */
	private function wp_field_choices(): array {
		return [
			'post_title'      => __( 'Post title', 'hg-structured-data' ),
			'post_content'    => __( 'Post content', 'hg-structured-data' ),
			'post_excerpt'    => __( 'Post excerpt', 'hg-structured-data' ),
			'post_date'       => __( 'Published date', 'hg-structured-data' ),
			'post_modified'   => __( 'Modified date', 'hg-structured-data' ),
			'permalink'       => __( 'Permalink (URL)', 'hg-structured-data' ),
			'featured_image'  => __( 'Featured image URL', 'hg-structured-data' ),
			'word_count'      => __( 'Word count', 'hg-structured-data' ),
			'author_name'     => __( 'Author name', 'hg-structured-data' ),
			'author_url'      => __( 'Author URL', 'hg-structured-data' ),
			'author_bio'      => __( 'Author bio', 'hg-structured-data' ),
			'author_email'    => __( 'Author email', 'hg-structured-data' ),
			'site_name'       => __( 'Site name', 'hg-structured-data' ),
			'site_description' => __( 'Site description', 'hg-structured-data' ),
			'site_language'   => __( 'Site language', 'hg-structured-data' ),
			'home_url'        => __( 'Home URL', 'hg-structured-data' ),
		];
	}

	/**
	 * @return array<string,string>
	 */
	private function post_type_choices(): array {
		$choices = [];
		foreach ( get_post_types( [ 'public' => true ], 'objects' ) as $pt ) {
			$choices[ $pt->name ] = $pt->labels->singular_name;
		}
		return $choices;
	}

	/**
	 * @return array<string,string>
	 */
	private function taxonomy_choices(): array {
		$choices = [];
		foreach ( get_taxonomies( [ 'public' => true ], 'objects' ) as $tax ) {
			$choices[ $tax->name ] = $tax->labels->singular_name;
		}
		return $choices;
	}

	/**
	 * @return array<string,string>
	 */
	private function post_format_choices(): array {
		return [
			'standard' => __( 'Standard', 'hg-structured-data' ),
			'aside'    => __( 'Aside', 'hg-structured-data' ),
			'gallery'  => __( 'Gallery', 'hg-structured-data' ),
			'link'     => __( 'Link', 'hg-structured-data' ),
			'image'    => __( 'Image', 'hg-structured-data' ),
			'quote'    => __( 'Quote', 'hg-structured-data' ),
			'video'    => __( 'Video', 'hg-structured-data' ),
			'audio'    => __( 'Audio', 'hg-structured-data' ),
		];
	}

	/**
	 * @return array<string,string>
	 */
	private function page_template_choices(): array {
		$choices = [ 'default' => __( 'Default template', 'hg-structured-data' ) ];
		$theme   = wp_get_theme();
		foreach ( $theme->get_page_templates() as $file => $name ) {
			$choices[ (string) $file ] = (string) $name;
		}
		return $choices;
	}

	/**
	 * AJAX: list ACF fields, optionally repeaters or a repeater's subfields.
	 */
	public function ajax_acf_fields(): void {
		check_ajax_referer( 'hgsd_ajax', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}

		$mode     = isset( $_GET['mode'] ) ? sanitize_text_field( wp_unslash( (string) $_GET['mode'] ) ) : 'all';
		$repeater = isset( $_GET['repeater'] ) ? sanitize_text_field( wp_unslash( (string) $_GET['repeater'] ) ) : '';

		wp_send_json_success( ( new AcfFields() )->get( $mode, $repeater ) );
	}

	/**
	 * AJAX: search posts/pages or terms for condition values.
	 */
	public function ajax_search_content(): void {
		check_ajax_referer( 'hgsd_ajax', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}

		$object = isset( $_GET['object'] ) ? sanitize_text_field( wp_unslash( (string) $_GET['object'] ) ) : 'post';
		$search = isset( $_GET['search'] ) ? sanitize_text_field( wp_unslash( (string) $_GET['search'] ) ) : '';
		$arg    = isset( $_GET['arg'] ) ? sanitize_text_field( wp_unslash( (string) $_GET['arg'] ) ) : '';

		$results = [];

		if ( 'term' === $object ) {
			$terms = get_terms(
				[
					'taxonomy'   => $arg ?: 'category',
					'search'     => $search,
					'number'     => 20,
					'hide_empty' => false,
				]
			);
			if ( ! is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					$results[] = [
						'id'   => $term->term_id,
						'text' => $term->name,
					];
				}
			}
		} else {
			$posts = get_posts(
				[
					'post_type'   => $arg ?: 'post',
					's'           => $search,
					'numberposts' => 20,
					'post_status' => 'publish',
				]
			);
			foreach ( $posts as $p ) {
				$results[] = [
					'id'   => $p->ID,
					'text' => get_the_title( $p ) . ' (#' . $p->ID . ')',
				];
			}
		}

		wp_send_json_success( $results );
	}

	/**
	 * AJAX: build a live JSON-LD preview from the (unsaved) wizard values.
	 */
	public function ajax_preview(): void {
		check_ajax_referer( 'hgsd_ajax', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce verified above.
		$raw = isset( $_POST['hgsd'] ) ? wp_unslash( $_POST['hgsd'] ) : [];
		$raw = is_array( $raw ) ? $raw : [];

		$type = $this->registry->get( isset( $raw['type'] ) ? sanitize_text_field( (string) $raw['type'] ) : '' );
		if ( ! $type ) {
			wp_send_json_success(
				[
					'empty' => true,
					'note'  => __( 'Select a schema type to preview its output.', 'hg-structured-data' ),
				]
			);
		}

		$config = [
			'properties' => $this->sanitize_properties( $raw['properties'] ?? [] ),
			'faq'        => $this->sanitize_faq( $raw['faq'] ?? [] ),
			'reviews'    => is_array( $raw['reviews'] ?? null ) ? $raw['reviews'] : [],
		];

		[ $post_id, $note ] = $this->preview_context( $this->sanitize_conditions( $raw['conditions'] ?? [] ) );

		$context = [
			'post_id'        => $post_id,
			'author_id'      => $post_id ? (int) get_post_field( 'post_author', $post_id ) : 0,
			'queried_object' => $post_id ? get_post( $post_id ) : null,
			'reviews'        => $this->reviews->data(),
		];

		$node = $type->build( $config, new PropertyResolver(), $context );

		if ( null === $node ) {
			wp_send_json_success(
				[
					'empty' => true,
					'note'  => $note,
				]
			);
		}

		$json = wp_json_encode( $node, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

		wp_send_json_success(
			[
				'json' => $json,
				'note' => $note,
			]
		);
	}

	/**
	 * Pick a representative post to render the preview against.
	 *
	 * @param array<string,mixed> $conditions Sanitized conditions.
	 * @return array{0:int,1:string} [post_id, human note]
	 */
	private function preview_context( array $conditions ): array {
		$include   = $conditions['include'] ?? [];
		$post_type = 'post';

		// A specific post/page beats everything else.
		foreach ( $include as $rule ) {
			$type  = $rule['type'] ?? '';
			$value = (string) ( $rule['value'] ?? '' );
			if ( in_array( $type, [ 'post', 'page' ], true ) && ctype_digit( $value ) ) {
				$post = get_post( (int) $value );
				if ( $post ) {
					/* translators: %s: post title. */
					return [ (int) $value, sprintf( __( 'Preview based on: %s', 'hg-structured-data' ), get_the_title( $post ) ) ];
				}
			}
			if ( 'post_type' === $type && '' !== $value ) {
				$post_type = $value;
			}
			if ( 'page' === $type ) {
				$post_type = 'page';
			}
		}

		// A taxonomy/category condition narrows to a post in that term.
		foreach ( $include as $rule ) {
			$type = $rule['type'] ?? '';
			if ( in_array( $type, [ 'post_category', 'taxonomy' ], true ) && ! empty( $rule['value'] ) ) {
				$taxonomy = 'post_category' === $type ? 'category' : ( $rule['value2'] ?: 'post_tag' );
				$posts    = get_posts(
					[
						'numberposts' => 1,
						'post_status' => 'publish',
						'tax_query'   => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
							[
								'taxonomy' => $taxonomy,
								'field'    => ctype_digit( (string) $rule['value'] ) ? 'term_id' : 'slug',
								'terms'    => $rule['value'],
							],
						],
					]
				);
				if ( $posts ) {
					/* translators: %s: post title. */
					return [ $posts[0]->ID, sprintf( __( 'Preview based on: %s', 'hg-structured-data' ), get_the_title( $posts[0] ) ) ];
				}
			}
		}

		$latest = get_posts(
			[
				'post_type'   => $post_type,
				'numberposts' => 1,
				'post_status' => 'publish',
			]
		);

		if ( $latest ) {
			/* translators: 1: post type, 2: post title. */
			return [ $latest[0]->ID, sprintf( __( 'Preview based on latest %1$s: %2$s', 'hg-structured-data' ), $post_type, get_the_title( $latest[0] ) ) ];
		}

		return [ 0, __( 'Preview without a specific post (site-wide).', 'hg-structured-data' ) ];
	}

	/**
	 * Add a "Type" and "Status" column to the list table.
	 *
	 * @param array<string,string> $columns Existing columns.
	 * @return array<string,string>
	 */
	public function columns( array $columns ): array {
		$new = [];
		foreach ( $columns as $key => $label ) {
			$new[ $key ] = $label;
			if ( 'title' === $key ) {
				$new['hgsd_type']   = __( 'Schema type', 'hg-structured-data' );
				$new['hgsd_status'] = __( 'Status', 'hg-structured-data' );
			}
		}
		return $new;
	}

	/**
	 * Render custom column content.
	 */
	public function column_content( string $column, int $post_id ): void {
		$def = new SchemaDefinition( $post_id );
		if ( 'hgsd_type' === $column ) {
			$type = $this->registry->get( $def->type() );
			echo esc_html( $type ? $type->label() : $def->type() );
		} elseif ( 'hgsd_status' === $column ) {
			echo $def->enabled()
				? '<span style="color:#16794a;">' . esc_html__( 'Enabled', 'hg-structured-data' ) . '</span>'
				: '<span style="color:#b32d2e;">' . esc_html__( 'Disabled', 'hg-structured-data' ) . '</span>';
		}
	}
}
