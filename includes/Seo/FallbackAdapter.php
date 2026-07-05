<?php
/**
 * Fallback adapter: used only when no supported SEO plugin is active.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Seo;

defined( 'ABSPATH' ) || exit;

/**
 * Stores settings in own post meta and emits minimal output (canonical via
 * WordPress core's canonical filter, robots via wp_robots). Never active
 * alongside Rank Math or Yoast, so it cannot cause duplicate tags.
 */
final class FallbackAdapter extends SeoAdapter {

	public const META_CANONICAL   = '_hgsd_canonical';
	public const META_NOINDEX     = '_hgsd_noindex';
	public const META_NOFOLLOW    = '_hgsd_nofollow';
	public const META_CORNERSTONE = '_hgsd_cornerstone';

	public function id(): string {
		return 'fallback';
	}

	public function label(): string {
		return __( 'this plugin (no SEO plugin active)', 'hg-structured-data' );
	}

	public function is_active(): bool {
		return true;
	}

	/**
	 * Front-end output hooks, registered only when this adapter is selected.
	 */
	public function register_output_hooks(): void {
		add_filter( 'get_canonical_url', [ $this, 'filter_canonical' ], 10, 2 );
		add_filter( 'wp_robots', [ $this, 'filter_robots' ] );
	}

	/**
	 * Let a per-post override win over the default canonical.
	 *
	 * @param string   $canonical_url Default canonical.
	 * @param \WP_Post $post          Post object.
	 */
	public function filter_canonical( $canonical_url, $post ) {
		$override = $post instanceof \WP_Post ? $this->get_canonical( $post->ID ) : '';
		return '' !== $override ? $override : $canonical_url;
	}

	/**
	 * Apply per-post noindex/nofollow.
	 *
	 * @param array<string,bool> $robots Robots directives.
	 * @return array<string,bool>
	 */
	public function filter_robots( array $robots ): array {
		if ( ! is_singular() ) {
			return $robots;
		}
		$flags = $this->get_robots( (int) get_queried_object_id() );
		if ( $flags['noindex'] ) {
			$robots['noindex'] = true;
			unset( $robots['max-image-preview'] );
		}
		if ( $flags['nofollow'] ) {
			$robots['nofollow'] = true;
		}
		return $robots;
	}

	public function get_canonical( int $post_id ): string {
		return (string) get_post_meta( $post_id, self::META_CANONICAL, true );
	}

	public function set_canonical( int $post_id, string $url ): void {
		$this->put_meta( $post_id, self::META_CANONICAL, esc_url_raw( $url ) );
	}

	public function get_robots( int $post_id ): array {
		return [
			'noindex'  => '1' === get_post_meta( $post_id, self::META_NOINDEX, true ),
			'nofollow' => '1' === get_post_meta( $post_id, self::META_NOFOLLOW, true ),
		];
	}

	public function set_robots( int $post_id, bool $noindex, bool $nofollow ): void {
		$this->put_meta( $post_id, self::META_NOINDEX, $noindex ? '1' : '' );
		$this->put_meta( $post_id, self::META_NOFOLLOW, $nofollow ? '1' : '' );
	}

	public function get_cornerstone( int $post_id ): bool {
		return '1' === get_post_meta( $post_id, self::META_CORNERSTONE, true );
	}

	public function set_cornerstone( int $post_id, bool $cornerstone ): void {
		$this->put_meta( $post_id, self::META_CORNERSTONE, $cornerstone ? '1' : '' );
	}

	protected function cornerstone_meta(): array {
		return [ self::META_CORNERSTONE, '1' ];
	}
}
