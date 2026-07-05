<?php
/**
 * Main plugin controller.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData;

use HelloGekko\StructuredData\Admin\Admin;
use HelloGekko\StructuredData\AI\AiSettings;
use HelloGekko\StructuredData\AI\LlmsTxt;
use HelloGekko\StructuredData\AI\MarkdownEndpoint;
use HelloGekko\StructuredData\Compat\ConflictManager;
use HelloGekko\StructuredData\Compat\ConflictSettings;
use HelloGekko\StructuredData\Graph\Cockpit;
use HelloGekko\StructuredData\Graph\GraphMetrics;
use HelloGekko\StructuredData\Graph\Installer;
use HelloGekko\StructuredData\Graph\LinkIndexer;
use HelloGekko\StructuredData\Graph\LinkRepository;
use HelloGekko\StructuredData\Graph\RelationRepository;
use HelloGekko\StructuredData\Output\FrontendOutput;
use HelloGekko\StructuredData\Seo\SeoManager;
use HelloGekko\StructuredData\Reviews\ReviewsManager;
use HelloGekko\StructuredData\Reviews\ReviewsSettings;
use HelloGekko\StructuredData\Schema\SchemaCatalog;
use HelloGekko\StructuredData\Schema\SchemaRegistry;

defined( 'ABSPATH' ) || exit;

/**
 * Singleton that wires everything together.
 */
final class Plugin {

	private static ?Plugin $instance = null;

	private SchemaRegistry $registry;

	private ReviewsManager $reviews;

	private function __construct() {}

	/**
	 * Retrieve the shared instance.
	 */
	public static function instance(): Plugin {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Boot all components.
	 */
	public function boot(): void {
		load_plugin_textdomain( 'hg-structured-data', false, dirname( HGSD_BASENAME ) . '/languages' );

		if ( ! defined( 'HGSD_SCHEMA_VERSION' ) ) {
			define( 'HGSD_SCHEMA_VERSION', SchemaCatalog::instance()->version() );
		}

		$this->registry = new SchemaRegistry();
		$this->registry->bootstrap();

		// Reviews integration (providers, caching, cron sync).
		$this->reviews = new ReviewsManager();
		$this->reviews->register_hooks();
		$this->reviews->schedule();

		// Register the post type that stores schema definitions.
		( new PostType() )->register_hooks();

		// Front-end JSON-LD output (with curated relations attached as @id refs).
		$relations = new RelationRepository();
		$frontend  = new FrontendOutput( $this->registry, $this->reviews, $relations );
		$frontend->register_hooks();

		// AI-readable output: per-page Markdown (.md) and /llms.txt index.
		( new MarkdownEndpoint( $frontend ) )->register_hooks();
		( new LlmsTxt() )->register_hooks();

		// Detect and optionally overrule other structured-data plugins.
		$conflicts = new ConflictManager( $frontend );
		$conflicts->register_hooks();

		// Cockpit: link index, graph metrics and SEO orchestration layer.
		Installer::maybe_install();
		( new LinkIndexer() )->register_hooks();
		$seo = new SeoManager();
		$seo->register_hooks();

		// Admin UI (wizard, meta boxes, ajax).
		if ( is_admin() ) {
			( new Admin( $this->registry, $this->reviews ) )->register_hooks();
			( new ReviewsSettings( $this->reviews ) )->register_hooks();
			( new ConflictSettings( $conflicts ) )->register_hooks();
			( new AiSettings() )->register_hooks();

			$link_repository = new LinkRepository();
			( new Cockpit( $link_repository, new GraphMetrics( $link_repository ), $seo, $this->registry, $relations ) )->register_hooks();
		}
	}

	/**
	 * Access the reviews manager.
	 */
	public function reviews(): ReviewsManager {
		return $this->reviews;
	}

	/**
	 * Access the schema registry.
	 */
	public function registry(): SchemaRegistry {
		return $this->registry;
	}

	/**
	 * Activation: register the CPT then flush rewrite rules.
	 */
	public static function activate(): void {
		( new PostType() )->register();
		flush_rewrite_rules();
	}

	/**
	 * Deactivation: clean up rewrite rules and scheduled events.
	 */
	public static function deactivate(): void {
		( new ReviewsManager() )->unschedule();
		Installer::unschedule();
		flush_rewrite_rules();
	}

	/**
	 * Whether Advanced Custom Fields (free or Pro) is active.
	 */
	public static function has_acf(): bool {
		return function_exists( 'acf' ) && function_exists( 'get_field' );
	}

	/**
	 * Whether ACF Pro (repeater support) is available.
	 */
	public static function has_acf_pro(): bool {
		return self::has_acf() && function_exists( 'have_rows' );
	}
}
