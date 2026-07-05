<?php
/**
 * Detects other structured-data plugins and (optionally) suppresses their output.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Compat;

use HelloGekko\StructuredData\Output\FrontendOutput;

defined( 'ABSPATH' ) || exit;

/**
 * Finds competing schema/SEO plugins so the user can be warned about duplicate
 * structured data, and can let this plugin "own" the output by overruling them.
 */
final class ConflictManager {

	public const OPTION = 'hgsd_conflict_settings';

	/**
	 * Types that should be treated as the same entity when de-duplicating.
	 * Maps a schema.org @type to a canonical family key.
	 */
	private const EQUIVALENCE = [
		// Organization family.
		'Corporation'           => 'Organization',
		'LocalBusiness'         => 'Organization',
		'OnlineBusiness'        => 'Organization',
		'NGO'                   => 'Organization',
		'GovernmentOrganization' => 'Organization',
		'EducationalOrganization' => 'Organization',
		'NewsMediaOrganization' => 'Organization',
		'Store'                 => 'Organization',
		// Article family.
		'NewsArticle'           => 'Article',
		'BlogPosting'           => 'Article',
		'Report'                => 'Article',
		// Page family.
		'ItemPage'              => 'WebPage',
		'CollectionPage'        => 'WebPage',
		'AboutPage'             => 'WebPage',
		'ContactPage'           => 'WebPage',
	];

	private ?FrontendOutput $frontend;

	public function __construct( ?FrontendOutput $frontend = null ) {
		$this->frontend = $frontend;
	}

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
	 * mode: 'off' (do nothing) | 'dedupe' (remove only foreign types we also
	 * output) | 'all' (remove every foreign JSON-LD block).
	 *
	 * @return array{suppress:array<string,bool>,mode:string}
	 */
	public function settings(): array {
		$stored = get_option( self::OPTION, [] );
		$stored = is_array( $stored ) ? $stored : [];

		$mode = $stored['mode'] ?? '';
		if ( ! in_array( $mode, [ 'off', 'dedupe', 'all' ], true ) ) {
			// Back-compat with the original boolean toggle.
			$mode = ! empty( $stored['strip_foreign'] ) ? 'all' : 'off';
		}

		return [
			'suppress' => is_array( $stored['suppress'] ?? null ) ? $stored['suppress'] : [],
			'mode'     => $mode,
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

		// Output filtering: either remove duplicates only, or all foreign JSON-LD.
		if ( 'all' === $settings['mode'] ) {
			ob_start( [ $this, 'strip_foreign_jsonld' ] );
		} elseif ( 'dedupe' === $settings['mode'] ) {
			ob_start( [ $this, 'dedupe_foreign_jsonld' ] );
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
	 * Remove only the foreign schema nodes whose @type this plugin also outputs
	 * on this page, leaving all other structured data (breadcrumbs, sitelinks,
	 * website, …) untouched.
	 */
	public function dedupe_foreign_jsonld( string $html ): string {
		$ours = [];
		foreach ( $this->frontend ? $this->frontend->emitted_types() : [] as $type ) {
			$ours[] = $this->canonical( $type );
		}
		$ours = array_values( array_unique( $ours ) );

		if ( empty( $ours ) ) {
			return $html;
		}

		return (string) preg_replace_callback(
			'#<script\b[^>]*type=([\'"])application/ld\+json\1[^>]*>(.*?)</script>#is',
			function ( array $m ) use ( $ours ): string {
				// Never touch our own output.
				if ( false !== strpos( $m[0], 'data-hgsd' ) ) {
					return $m[0];
				}

				$data = json_decode( trim( $m[2] ), true );
				if ( ! is_array( $data ) ) {
					return $m[0]; // Not parseable — leave it alone.
				}

				$is_list = array_keys( $data ) === range( 0, count( $data ) - 1 );
				$nodes   = $is_list ? $data : [ $data ];
				$kept    = $this->filter_nodes( $nodes, $ours );

				if ( empty( $kept ) ) {
					return ''; // Everything in this block duplicated ours.
				}

				$payload = ( ! $is_list && 1 === count( $kept ) ) ? $kept[0] : $kept;
				$json    = wp_json_encode( $payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
				if ( false === $json ) {
					return $m[0];
				}

				return '<script type="application/ld+json">' . str_replace( '</', '<\/', $json ) . '</script>';
			},
			$html
		);
	}

	/**
	 * Keep only the nodes that do not conflict with our emitted types, recursing
	 * into @graph containers.
	 *
	 * @param array<int,mixed>   $nodes Decoded top-level nodes.
	 * @param array<int,string>  $ours  Canonical types we output.
	 * @return array<int,mixed>
	 */
	private function filter_nodes( array $nodes, array $ours ): array {
		$kept = [];
		foreach ( $nodes as $node ) {
			if ( ! is_array( $node ) ) {
				$kept[] = $node;
				continue;
			}

			if ( isset( $node['@graph'] ) && is_array( $node['@graph'] ) ) {
				$graph = $this->filter_nodes( $node['@graph'], $ours );
				if ( ! empty( $graph ) ) {
					$node['@graph'] = $graph;
					$kept[]         = $node;
				}
				continue;
			}

			if ( $this->conflicts( $node, $ours ) ) {
				continue;
			}
			$kept[] = $node;
		}
		return $kept;
	}

	/**
	 * Whether a decoded node carries a type we also output.
	 *
	 * @param array<string,mixed> $node Decoded node.
	 * @param array<int,string>   $ours Canonical types we output.
	 */
	private function conflicts( array $node, array $ours ): bool {
		$types = isset( $node['@type'] ) ? (array) $node['@type'] : [];
		foreach ( $types as $type ) {
			if ( in_array( $this->canonical( (string) $type ), $ours, true ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Canonical family key for a schema.org type, so e.g. Corporation and
	 * Organization are treated as duplicates of one another.
	 */
	private function canonical( string $type ): string {
		return self::EQUIVALENCE[ $type ] ?? $type;
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

		// Already handled: the dedupe/strip output mode covers everything, and a
		// per-plugin suppression covers that plugin. Only warn about the rest.
		$settings = $this->settings();
		if ( 'off' !== $settings['mode'] ) {
			return;
		}

		$labels = [];
		foreach ( $this->integrations() as $key => $integration ) {
			if ( ! in_array( $key, $detected, true ) ) {
				continue;
			}
			$suppressed = ! empty( $settings['suppress'][ $key ] ) && is_callable( $integration['disable'] ?? null );
			if ( $suppressed ) {
				continue;
			}
			$labels[] = $integration['label'];
		}

		if ( empty( $labels ) ) {
			return;
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
