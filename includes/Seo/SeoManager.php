<?php
/**
 * Picks the active SEO adapter.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Seo;

defined( 'ABSPATH' ) || exit;

/**
 * Resolves which SEO plugin owns the output on this site and hands out the
 * matching adapter. The fallback only engages when none is active.
 */
final class SeoManager {

	private ?SeoAdapter $adapter = null;

	/**
	 * The adapter for the active SEO plugin (Rank Math > Yoast > fallback).
	 */
	public function adapter(): SeoAdapter {
		if ( null !== $this->adapter ) {
			return $this->adapter;
		}

		foreach ( [ new RankMathAdapter(), new YoastAdapter() ] as $candidate ) {
			if ( $candidate->is_active() ) {
				return $this->adapter = $candidate; // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments
			}
		}

		$fallback = new FallbackAdapter();
		return $this->adapter = $fallback; // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments
	}

	/**
	 * Register front-end output for the fallback (no-op otherwise).
	 */
	public function register_hooks(): void {
		$adapter = $this->adapter();
		if ( $adapter instanceof FallbackAdapter ) {
			$adapter->register_output_hooks();
		}
	}
}
