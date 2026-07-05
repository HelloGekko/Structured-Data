<?php
/**
 * The Cockpit: one overview to inspect and tune site structure and SEO state.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Graph;

use HelloGekko\StructuredData\Schema\SchemaRegistry;
use HelloGekko\StructuredData\SchemaDefinition;
use HelloGekko\StructuredData\Seo\SeoManager;

defined( 'ABSPATH' ) || exit;

/**
 * Admin screen listing all public content with entity types, link metrics,
 * cornerstone/canonical/robots state — editable via a side panel that writes
 * through the active SEO plugin's adapter.
 */
final class Cockpit {

	private const PER_PAGE = 50;

	private LinkRepository $repository;
	private GraphMetrics $metrics;
	private SeoManager $seo;
	private SchemaRegistry $registry;

	public function __construct( LinkRepository $repository, GraphMetrics $metrics, SeoManager $seo, SchemaRegistry $registry ) {
		$this->repository = $repository;
		$this->metrics    = $metrics;
		$this->seo        = $seo;
		$this->registry   = $registry;
	}

	public function register_hooks(): void {
		add_action( 'admin_menu', [ $this, 'menu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue' ] );
		add_action( 'wp_ajax_hgsd_cockpit_save', [ $this, 'ajax_save' ] );
		add_action( 'wp_ajax_hgsd_cockpit_detail', [ $this, 'ajax_detail' ] );
		add_action( 'wp_ajax_hgsd_cockpit_reindex', [ $this, 'ajax_reindex' ] );
	}

	private string $hook = '';

	public function menu(): void {
		$this->hook = (string) add_submenu_page(
			'edit.php?post_type=' . HGSD_CPT,
			__( 'Cockpit', 'hg-structured-data' ),
			__( 'Cockpit', 'hg-structured-data' ),
			'manage_options',
			'hgsd-cockpit',
			[ $this, 'render' ]
		);
	}

	/**
	 * Load assets on the cockpit screen only.
	 *
	 * @param string $hook Current admin page hook.
	 */
	public function enqueue( string $hook ): void {
		if ( $hook !== $this->hook ) {
			return;
		}
		wp_enqueue_style( 'hgsd-cockpit', HGSD_URL . 'assets/css/cockpit.css', [], HGSD_VERSION );
		wp_enqueue_script( 'hgsd-cockpit', HGSD_URL . 'assets/js/cockpit.js', [ 'jquery' ], HGSD_VERSION, true );
		wp_localize_script(
			'hgsd-cockpit',
			'HGSDCockpit',
			[
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'hgsd_ajax' ),
				'i18n'    => [
					'saved'  => __( 'Saved.', 'hg-structured-data' ),
					'error'  => __( 'Could not save.', 'hg-structured-data' ),
					'none'   => __( 'None', 'hg-structured-data' ),
				],
			]
		);
	}

	/**
	 * Render the cockpit screen.
	 */
	public function render(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only filters.
		$filter_type = isset( $_GET['pt'] ) ? sanitize_key( (string) wp_unslash( $_GET['pt'] ) ) : '';
		$filter_flag = isset( $_GET['flag'] ) ? sanitize_key( (string) wp_unslash( $_GET['flag'] ) ) : '';
		$search      = isset( $_GET['s'] ) ? sanitize_text_field( (string) wp_unslash( $_GET['s'] ) ) : '';
		$paged       = isset( $_GET['paged'] ) ? max( 1, (int) $_GET['paged'] ) : 1;
		// phpcs:enable WordPress.Security.NonceVerification.Recommended

		$public_types = get_post_types( [ 'public' => true ], 'objects' );
		unset( $public_types['attachment'] );

		$query = new \WP_Query(
			[
				'post_type'      => '' !== $filter_type ? $filter_type : array_keys( $public_types ),
				'post_status'    => 'publish',
				'posts_per_page' => self::PER_PAGE,
				'paged'          => $paged,
				's'              => $search,
				'orderby'        => 'title',
				'order'          => 'ASC',
			]
		);

		$inlinks  = $this->repository->inlink_counts();
		$outlinks = $this->repository->outlink_counts();
		$depths   = $this->metrics->depths();
		$adapter  = $this->seo->adapter();

		$rows = [];
		foreach ( $query->posts as $post ) {
			$orphan = GraphMetrics::is_orphan( $post->ID, $inlinks );
			$row    = [
				'post'        => $post,
				'schemas'     => $this->schema_labels( $post ),
				'inlinks'     => $inlinks[ $post->ID ] ?? 0,
				'outlinks'    => $outlinks[ $post->ID ] ?? 0,
				'depth'       => $depths[ $post->ID ] ?? null,
				'orphan'      => $orphan,
				'cornerstone' => $adapter->get_cornerstone( $post->ID ),
				'canonical'   => $adapter->get_canonical( $post->ID ),
				'robots'      => $adapter->get_robots( $post->ID ),
			];

			if ( 'orphans' === $filter_flag && ! $row['orphan'] ) {
				continue;
			}
			if ( 'cornerstones' === $filter_flag && ! $row['cornerstone'] ) {
				continue;
			}
			$rows[] = $row;
		}

		$indexing     = false !== get_option( Installer::OPTION_POINTER, false );
		$indexed_at   = (int) get_option( Installer::OPTION_INDEXED, 0 );
		$engine_label = $adapter->label();
		$total_pages  = (int) $query->max_num_pages;

		require HGSD_PATH . 'includes/Graph/views/cockpit.php';
	}

	/**
	 * Labels of the schema types that (approximately) apply to a post.
	 *
	 * @return array<int,string>
	 */
	private function schema_labels( \WP_Post $post ): array {
		static $definitions = null;
		static $matcher     = null;

		if ( null === $definitions ) {
			$matcher = new SchemaMatcher();
			$ids     = get_posts(
				[
					'post_type'     => HGSD_CPT,
					'post_status'   => 'publish',
					'numberposts'   => -1,
					'fields'        => 'ids',
					'no_found_rows' => true,
				]
			);
			$definitions = array_map( static fn( $id ) => new SchemaDefinition( (int) $id ), $ids );
		}

		$labels = [];
		foreach ( $definitions as $definition ) {
			if ( ! $definition->enabled() || ! $matcher->matches( $definition, $post ) ) {
				continue;
			}
			$type     = $this->registry->get( $definition->type() );
			$labels[] = $type ? $type->label() : $definition->type();
		}

		return array_values( array_unique( $labels ) );
	}

	/**
	 * AJAX: save cornerstone/canonical/robots for a post via the adapter.
	 */
	public function ajax_save(): void {
		check_ajax_referer( 'hgsd_ajax', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}

		$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
		if ( ! $post_id || ! get_post( $post_id ) ) {
			wp_send_json_error();
		}

		$adapter = $this->seo->adapter();
		$adapter->set_cornerstone( $post_id, ! empty( $_POST['cornerstone'] ) && '0' !== $_POST['cornerstone'] );
		$adapter->set_robots( $post_id, ! empty( $_POST['noindex'] ) && '0' !== $_POST['noindex'], ! empty( $_POST['nofollow'] ) && '0' !== $_POST['nofollow'] );
		$adapter->set_canonical( $post_id, isset( $_POST['canonical'] ) ? esc_url_raw( wp_unslash( (string) $_POST['canonical'] ) ) : '' );

		wp_send_json_success(
			[
				'cornerstone' => $adapter->get_cornerstone( $post_id ),
				'canonical'   => $adapter->get_canonical( $post_id ),
				'robots'      => $adapter->get_robots( $post_id ),
			]
		);
	}

	/**
	 * AJAX: detail payload for the side panel (links in/out + current state).
	 */
	public function ajax_detail(): void {
		check_ajax_referer( 'hgsd_ajax', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}

		$post_id = isset( $_GET['post_id'] ) ? absint( $_GET['post_id'] ) : 0;
		$post    = $post_id ? get_post( $post_id ) : null;
		if ( ! $post ) {
			wp_send_json_error();
		}

		$adapter = $this->seo->adapter();

		wp_send_json_success(
			[
				'title'       => get_the_title( $post ),
				'url'         => get_permalink( $post ),
				'edit'        => get_edit_post_link( $post->ID, 'raw' ),
				'cornerstone' => $adapter->get_cornerstone( $post->ID ),
				'canonical'   => $adapter->get_canonical( $post->ID ),
				'robots'      => $adapter->get_robots( $post->ID ),
				'inlinks'     => $this->repository->inlinks_for( $post->ID ),
				'outlinks'    => $this->repository->outlinks_for( $post->ID ),
			]
		);
	}

	/**
	 * AJAX: restart the full background index.
	 */
	public function ajax_reindex(): void {
		check_ajax_referer( 'hgsd_ajax', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}
		Installer::start_full_index();
		GraphMetrics::flush_cache();
		wp_send_json_success();
	}
}
