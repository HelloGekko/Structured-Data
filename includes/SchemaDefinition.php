<?php
/**
 * Value object + repository for a stored schema definition.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData;

defined( 'ABSPATH' ) || exit;

/**
 * Wraps the post meta that makes up a single schema definition.
 */
final class SchemaDefinition {

	public const META_TYPE       = '_hgsd_type';
	public const META_CONDITIONS = '_hgsd_conditions';
	public const META_PROPERTIES = '_hgsd_properties';
	public const META_FAQ        = '_hgsd_faq';
	public const META_ENABLED    = '_hgsd_enabled';

	private int $post_id;

	public function __construct( int $post_id ) {
		$this->post_id = $post_id;
	}

	public function id(): int {
		return $this->post_id;
	}

	/**
	 * Schema type key, e.g. "Article".
	 */
	public function type(): string {
		return (string) get_post_meta( $this->post_id, self::META_TYPE, true );
	}

	public function set_type( string $type ): void {
		update_post_meta( $this->post_id, self::META_TYPE, sanitize_text_field( $type ) );
	}

	/**
	 * Whether the schema is enabled for output.
	 */
	public function enabled(): bool {
		$value = get_post_meta( $this->post_id, self::META_ENABLED, true );
		return '' === $value ? true : (bool) $value;
	}

	public function set_enabled( bool $enabled ): void {
		update_post_meta( $this->post_id, self::META_ENABLED, $enabled ? '1' : '0' );
	}

	/**
	 * Display condition configuration.
	 *
	 * @return array{logic:string,include:array<int,array<string,mixed>>,exclude:array<int,array<string,mixed>>}
	 */
	public function conditions(): array {
		$stored = get_post_meta( $this->post_id, self::META_CONDITIONS, true );
		$stored = is_array( $stored ) ? $stored : [];

		return [
			'logic'   => $stored['logic'] ?? 'any',
			'include' => isset( $stored['include'] ) && is_array( $stored['include'] ) ? $stored['include'] : [],
			'exclude' => isset( $stored['exclude'] ) && is_array( $stored['exclude'] ) ? $stored['exclude'] : [],
		];
	}

	/**
	 * @param array<string,mixed> $conditions Raw conditions structure.
	 */
	public function set_conditions( array $conditions ): void {
		update_post_meta( $this->post_id, self::META_CONDITIONS, $conditions );
	}

	/**
	 * Property mappings.
	 *
	 * @return array<int,array{property:string,source:string,value:string}>
	 */
	public function properties(): array {
		$stored = get_post_meta( $this->post_id, self::META_PROPERTIES, true );
		return is_array( $stored ) ? $stored : [];
	}

	/**
	 * @param array<int,array<string,mixed>> $properties Property mappings.
	 */
	public function set_properties( array $properties ): void {
		update_post_meta( $this->post_id, self::META_PROPERTIES, $properties );
	}

	/**
	 * FAQ configuration.
	 *
	 * @return array<string,mixed>
	 */
	public function faq(): array {
		$stored = get_post_meta( $this->post_id, self::META_FAQ, true );
		$stored = is_array( $stored ) ? $stored : [];

		return wp_parse_args(
			$stored,
			[
				'method'           => 'manual',
				'acf_repeater'     => '',
				'question_subfield' => '',
				'answer_subfield'  => '',
				'items'            => [],
			]
		);
	}

	/**
	 * @param array<string,mixed> $faq FAQ config.
	 */
	public function set_faq( array $faq ): void {
		update_post_meta( $this->post_id, self::META_FAQ, $faq );
	}
}
