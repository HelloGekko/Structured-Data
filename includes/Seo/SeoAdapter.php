<?php
/**
 * Base class for SEO-plugin adapters.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Seo;

defined( 'ABSPATH' ) || exit;

/**
 * The cockpit never emits its own SEO tags next to an active SEO plugin.
 * Instead an adapter reads and writes that plugin's own per-post settings
 * (canonical, robots, cornerstone), so the plugin keeps doing the output.
 */
abstract class SeoAdapter {

	/**
	 * Stable identifier, e.g. "rankmath".
	 */
	abstract public function id(): string;

	/**
	 * Human readable name shown in the cockpit ("settings are written to …").
	 */
	abstract public function label(): string;

	/**
	 * Whether the underlying plugin is active.
	 */
	abstract public function is_active(): bool;

	abstract public function get_canonical( int $post_id ): string;

	abstract public function set_canonical( int $post_id, string $url ): void;

	/**
	 * @return array{noindex:bool,nofollow:bool}
	 */
	abstract public function get_robots( int $post_id ): array;

	abstract public function set_robots( int $post_id, bool $noindex, bool $nofollow ): void;

	abstract public function get_cornerstone( int $post_id ): bool;

	abstract public function set_cornerstone( int $post_id, bool $cornerstone ): void;

	/**
	 * Helper: update or delete a meta value ("" deletes).
	 *
	 * @param mixed $value Value to store.
	 */
	protected function put_meta( int $post_id, string $key, $value ): void {
		if ( '' === $value || null === $value || false === $value ) {
			delete_post_meta( $post_id, $key );
			return;
		}
		update_post_meta( $post_id, $key, $value );
	}
}
