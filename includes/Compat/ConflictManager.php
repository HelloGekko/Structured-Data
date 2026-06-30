<?php
/**
 * Detects other structured-data plugins and (optionally) suppresses their output.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Compat;

defined( 'ABSPATH' ) || exit;

/**
 * Finds competing schema/SEO plugins so the user can be warned about duplicate
 * structured data, and can let this plugin "own" the output by overruling them.
 */
final class ConflictManager {

	public const OPTION = 'hgsd_conflict_settings';

	public function register_hooks(): void {
		add_action( 'admin_notices', [ $this, 'notice' ] );
		// Apply suppression on the front-end once everything is loaded.
		add_action( 'template_redirect', [ $this, 'apply' ], 0 );
	}

	/**
	 * Known structured-data sources.
	 *
	 * Each entry: label, detect (callable=>bool), and an optional clean
	 * "disable" callable that uses the plugin's own filter. Plugins without a
	 * reliable disable filter rely on the "strip foreign JSON-LD" output mode.
	 *
	 * @return array<string,array<string,mixed>>
	 */
	public function integrations(): array {
		return [
			'yoast'        => [
				'label'   => 'Yoast SEO',
				'detect'  => static fn(): bool => defined( 'WPSEO_VERSION' ),
				'disable' => static function (): void {
					add_filter( 'wpseo_json_ld_output', '__return_false', 99 );
				},
			],
			'rankmath'     => [
				'label'   => 'Rank Math',
				'detect'  => static fn(): bool => defined( 'RANK_MATH_VERSION' ) || class_exists( 'RankMath' ),
				'disable' => static function (): void {
					add_filter( 'rank_math/json_ld', static fn( $data ) => [], 99 );
				},
			],
			'aioseo'       => [
				'label'   => 'All in One SEO',
				'detect'  => static fn(): bool => defined( 'AIOSEO_VERSION' ) || function_exists( 'aioseo' ),
				'disable' => null,
			],
			'seoframework' => [
				'label'   => 'The SEO Framework',
				'detect'  => static fn(): bool => defined( 'THE_SEO_FRAMEWORK_VERSION' ) || function_exists( 'the_seo_framework' ),
				'disable' => null,
			],
			'saswp'        => [
				'label'   => 'Schema & Structured Data for WP',
				'detect'  => static fn(): bool => defined( 'SASWP_VERSION' ) || class_exists( 'saswp_output_service' ) || function_exists( 'saswp_schema_init' ),
				'disable' => null,
			],
			'schemapro'    => [
				'label'   => 'Schema Pro',
				'detect'  => static fn(): bool => defined( 'BSF_AIOSRS_PRO_VER' ) || class_exists( 'BSF_AIOSRS_Pro' ),
				'disable' => null,
			],
		];
	}

	/**
	 * Keys of integrations currently active on the site.
	 *
	 * @return array<int,string>
	 */
	public function detected(): array {
		$found = [];
		foreach ( $this->integrations() as $key => $integration ) {
			if ( ( $integration['detect'] )() ) {
				$found[] = $key;
			}
		}
		return $found;
	}

	/**
	 * Current settings.
	 *
	 * @return array{suppress:array<string,bool>,strip_foreign:bool}
	 */
	public function settings(): array {
		$stored = get_option( self::OPTION, [] );
		$stored = is_array( $stored ) ? $stored : [];

		return [
			'suppress'      => is_array( $stored['suppress'] ?? null ) ? $stored['suppress'] : [],
			'strip_foreign' => ! empty( $stored['strip_foreign'] ),
		];
	}

	/**
	 * Apply the configured overrules on the front-end.
	 */
	public function apply(): void {
		if ( is_admin() ) {
			return;
		}

		$settings     = $this->settings();
		$integrations = $this->integrations();

		// Clean, per-plugin disabling via their own filters.
		foreach ( $this->detected() as $key ) {
			if ( ! empty( $settings['suppress'][ $key ] ) && is_callable( $integrations[ $key ]['disable'] ?? null ) ) {
				( $integrations[ $key ]['disable'] )();
			}
		}

		// Aggressive fallback: strip every foreign JSON-LD block from the output.
		if ( $settings['strip_foreign'] ) {
			ob_start( [ $this, 'strip_foreign_jsonld' ] );
		}
	}

	/**
	 * Remove all application/ld+json scripts except the ones this plugin emits
	 * (marked with data-hgsd).
	 */
	public function strip_foreign_jsonld( string $html ): string {
		return (string) preg_replace_callback(
			'#<script\b[^>]*type=([\'"])application/ld\+json\1[^>]*>.*?</script>#is',
			static function ( array $m ): string {
				return false !== strpos( $m[0], 'data-hgsd' ) ? $m[0] : '';
			},
			$html
		);
	}

	/**
	 * Show an admin notice on the plugin screens when competitors are detected.
	 */
	public function notice(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$screen = get_current_screen();
		$on_plugin_screen = $screen && ( HGSD_CPT === $screen->post_type || false !== strpos( (string) $screen->id, 'hgsd' ) || 'plugins' === $screen->base );
		if ( ! $on_plugin_screen ) {
			return;
		}

		$detected = $this->detected();
		if ( empty( $detected ) ) {
			return;
		}

		$labels = [];
		foreach ( $this->integrations() as $key => $integration ) {
			if ( in_array( $key, $detected, true ) ) {
				$labels[] = $integration['label'];
			}
		}

		$url = add_query_arg(
			[ 'post_type' => HGSD_CPT, 'page' => 'hgsd-conflicts' ],
			admin_url( 'edit.php' )
		);

		printf(
			'<div class="notice notice-warning"><p><strong>%s</strong> %s</p><p><a class="button button-secondary" href="%s">%s</a></p></div>',
			esc_html__( 'Structured Data:', 'hg-structured-data' ),
			sprintf(
				/* translators: %s: comma-separated plugin names. */
				esc_html__( 'Another plugin that outputs structured data is active (%s). This can create duplicate or conflicting schema. You can let this plugin overrule it.', 'hg-structured-data' ),
				esc_html( implode( ', ', $labels ) )
			),
			esc_url( $url ),
			esc_html__( 'Manage conflicts', 'hg-structured-data' )
		);
	}
}
