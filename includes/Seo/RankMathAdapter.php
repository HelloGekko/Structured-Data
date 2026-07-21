<?php
/**
 * Rank Math adapter.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Seo;

defined( 'ABSPATH' ) || exit;

/**
 * Reads/writes Rank Math's per-post meta so Rank Math keeps emitting the tags.
 */
final class RankMathAdapter extends SeoAdapter {

	public function id(): string {
		return 'rankmath';
	}

	public function label(): string {
		return 'Rank Math';
	}

	public function is_active(): bool {
		return defined( 'RANK_MATH_VERSION' ) || class_exists( 'RankMath' );
	}

	public function get_canonical( int $post_id ): string {
		return (string) get_post_meta( $post_id, 'rank_math_canonical_url', true );
	}

	public function focus_keyword( int $post_id ): string {
		// Rank Math stores a comma-separated list; the first is the primary.
		$raw   = (string) get_post_meta( $post_id, 'rank_math_focus_keyword', true );
		$first = explode( ',', $raw )[0];
		return trim( mb_strtolower( $first ) );
	}

	public function focus_keyword_meta_key(): string {
		return 'rank_math_focus_keyword';
	}

	public function set_canonical( int $post_id, string $url ): void {
		$this->put_meta( $post_id, 'rank_math_canonical_url', esc_url_raw( $url ) );
	}

	public function get_robots( int $post_id ): array {
		$robots = get_post_meta( $post_id, 'rank_math_robots', true );
		$robots = is_array( $robots ) ? $robots : [];

		return [
			'noindex'  => in_array( 'noindex', $robots, true ),
			'nofollow' => in_array( 'nofollow', $robots, true ),
		];
	}

	public function set_robots( int $post_id, bool $noindex, bool $nofollow ): void {
		if ( ! $noindex && ! $nofollow ) {
			delete_post_meta( $post_id, 'rank_math_robots' ); // Back to Rank Math defaults.
			return;
		}

		$existing = get_post_meta( $post_id, 'rank_math_robots', true );
		$existing = is_array( $existing ) ? $existing : [];
		$keep     = array_values( array_diff( $existing, [ 'index', 'noindex', 'nofollow' ] ) );

		$robots   = $keep;
		$robots[] = $noindex ? 'noindex' : 'index';
		if ( $nofollow ) {
			$robots[] = 'nofollow';
		}

		update_post_meta( $post_id, 'rank_math_robots', array_values( array_unique( $robots ) ) );
	}

	public function get_cornerstone( int $post_id ): bool {
		return 'on' === get_post_meta( $post_id, 'rank_math_pillar_content', true );
	}

	public function set_cornerstone( int $post_id, bool $cornerstone ): void {
		$this->put_meta( $post_id, 'rank_math_pillar_content', $cornerstone ? 'on' : '' );
	}

	protected function cornerstone_meta(): array {
		return [ 'rank_math_pillar_content', 'on' ];
	}
}
