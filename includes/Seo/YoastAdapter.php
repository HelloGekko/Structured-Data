<?php
/**
 * Yoast SEO adapter.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Seo;

defined( 'ABSPATH' ) || exit;

/**
 * Reads/writes Yoast's per-post meta so Yoast keeps emitting the tags.
 */
final class YoastAdapter extends SeoAdapter {

	public function id(): string {
		return 'yoast';
	}

	public function label(): string {
		return 'Yoast SEO';
	}

	public function is_active(): bool {
		return defined( 'WPSEO_VERSION' );
	}

	public function get_canonical( int $post_id ): string {
		return (string) get_post_meta( $post_id, '_yoast_wpseo_canonical', true );
	}

	public function set_canonical( int $post_id, string $url ): void {
		$this->put_meta( $post_id, '_yoast_wpseo_canonical', esc_url_raw( $url ) );
	}

	public function get_robots( int $post_id ): array {
		return [
			'noindex'  => '1' === get_post_meta( $post_id, '_yoast_wpseo_meta-robots-noindex', true ),
			'nofollow' => '1' === get_post_meta( $post_id, '_yoast_wpseo_meta-robots-nofollow', true ),
		];
	}

	public function set_robots( int $post_id, bool $noindex, bool $nofollow ): void {
		$this->put_meta( $post_id, '_yoast_wpseo_meta-robots-noindex', $noindex ? '1' : '' );
		$this->put_meta( $post_id, '_yoast_wpseo_meta-robots-nofollow', $nofollow ? '1' : '' );
	}

	public function get_cornerstone( int $post_id ): bool {
		return '1' === get_post_meta( $post_id, '_yoast_wpseo_is_cornerstone', true );
	}

	public function set_cornerstone( int $post_id, bool $cornerstone ): void {
		$this->put_meta( $post_id, '_yoast_wpseo_is_cornerstone', $cornerstone ? '1' : '' );
	}
}
