<?php
/**
 * The Cockpit: one overview to inspect and tune site structure and SEO state.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Graph;

use HelloGekko\StructuredData\AI\ReadabilityAudit;
use HelloGekko\StructuredData\Gsc\GscClient;
use HelloGekko\StructuredData\Gsc\IndexingClient;
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
	private RelationRepository $relations;
	private ?GscClient $gsc;
	private ?IndexingClient $indexing;
	private Advisor $advisor;

	public function __construct( LinkRepository $repository, GraphMetrics $metrics, SeoManager $seo, SchemaRegistry $registry, RelationRepository $relations, ?GscClient $gsc = null, ?IndexingClient $indexing = null ) {
		$this->repository = $repository;
		$this->metrics    = $metrics;
		$this->seo        = $seo;
		$this->registry   = $registry;
		$this->relations  = $relations;
		$this->gsc        = $gsc;
		$this->indexing   = $indexing;
		$this->advisor    = new Advisor( $repository, $metrics, $relations, $seo );
	}

	public function register_hooks(): void {
		add_action( 'admin_menu', [ $this, 'menu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue' ] );
		add_action( 'wp_ajax_hgsd_cockpit_save', [ $this, 'ajax_save' ] );
		add_action( 'wp_ajax_hgsd_cockpit_detail', [ $this, 'ajax_detail' ] );
		add_action( 'wp_ajax_hgsd_cockpit_reindex', [ $this, 'ajax_reindex' ] );
		add_action( 'wp_ajax_hgsd_cockpit_relation_add', [ $this, 'ajax_relation_add' ] );
		add_action( 'wp_ajax_hgsd_cockpit_relation_delete', [ $this, 'ajax_relation_delete' ] );
		add_action( 'wp_ajax_hgsd_cockpit_graph', [ $this, 'ajax_graph' ] );
		add_action( 'wp_ajax_hgsd_cockpit_gsc', [ $this, 'ajax_gsc' ] );
		add_action( 'wp_ajax_hgsd_cockpit_index', [ $this, 'ajax_index' ] );
		add_action( 'wp_ajax_hgsd_cockpit_airecheck', [ $this, 'ajax_airecheck' ] );
		add_action( 'wp_ajax_hgsd_cockpit_rescan', [ $this, 'ajax_rescan' ] );
		add_action( 'wp_ajax_hgsd_cockpit_tip', [ $this, 'ajax_tip' ] );
		add_action( 'wp_ajax_hgsd_cockpit_tip_settings', [ $this, 'ajax_tip_settings' ] );
	}

	/**
	 * AJAX: save which post types the orphan/archive checks skip.
	 */
	public function ajax_tip_settings(): void {
		check_ajax_referer( 'hgsd_ajax', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- verified above.
		$types = isset( $_POST['types'] ) ? (array) wp_unslash( $_POST['types'] ) : [];
		Advisor::save_settings( array_map( 'sanitize_key', $types ) );

		wp_send_json_success();
	}

	/**
	 * AJAX: re-check the currently-flagged pages so resolved issues drop off the
	 * tip list immediately, instead of waiting for the next background index or
	 * Search Console refresh. Only the flagged pages are re-scanned, so it stays
	 * fast.
	 */
	public function ajax_rescan(): void {
		check_ajax_referer( 'hgsd_ajax', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}

		[ $active ] = Advisor::split_dismissed( $this->advisor->issues() );

		$post_ids = [];
		$gsc_ids  = [];
		foreach ( $active as $tip ) {
			$pid = (int) ( $tip['post_id'] ?? 0 );
			if ( $pid < 1 ) {
				continue;
			}
			$post_ids[ $pid ] = true;
			if ( 0 === strpos( (string) ( $tip['key'] ?? '' ), 'gsc' ) ) {
				$gsc_ids[ $pid ] = true;
			}
		}

		// Refresh the link/readability index for the flagged pages (local, fast),
		// bounded by count and wall-clock so the request never times out.
		$indexer = new LinkIndexer();
		$started = microtime( true );
		$done    = 0;
		foreach ( array_keys( $post_ids ) as $pid ) {
			if ( $done >= 40 || ( microtime( true ) - $started ) > 12 ) {
				break;
			}
			$post = get_post( $pid );
			if ( $post instanceof \WP_Post ) {
				$indexer->index_post( $post );
				++$done;
			}
		}
		GraphMetrics::flush_cache();

		// Search Console re-inspection is slow (one API call per page), so hand
		// the flagged pages to a background job instead of blocking the request.
		if ( ! empty( $gsc_ids ) && null !== $this->gsc && $this->gsc->configured() ) {
			$this->gsc->schedule_recheck( array_keys( $gsc_ids ) );
		}

		wp_send_json_success( [ 'rescanned' => $done ] );
	}

	/**
	 * AJAX: dismiss or restore a tip.
	 */
	public function ajax_tip(): void {
		check_ajax_referer( 'hgsd_ajax', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}

		$key = isset( $_POST['key'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['key'] ) ) : '';
		$op  = isset( $_POST['op'] ) && 'restore' === $_POST['op'] ? 'restore' : 'dismiss';

		if ( '' === $key || ! preg_match( '/^[a-z_]+:[0-9:a-zA-Z_-]+$/', $key ) ) {
			wp_send_json_error();
		}

		Advisor::set_dismissed( $key, 'dismiss' === $op );
		wp_send_json_success();
	}

	/**
	 * AJAX: inspect a URL in Search Console on demand.
	 */
	public function ajax_gsc(): void {
		check_ajax_referer( 'hgsd_ajax', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}

		$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
		if ( ! $post_id || null === $this->gsc || ! $this->gsc->configured() ) {
			wp_send_json_error( [ 'message' => __( 'Search Console is not connected.', 'hg-structured-data' ) ] );
		}

		$result = $this->gsc->inspect_and_store( $post_id );
		if ( is_wp_error( $result ) ) {
			wp_send_json_error( [ 'message' => $result->get_error_message() ] );
		}

		wp_send_json_success( $result );
	}

	/**
	 * AJAX: submit a URL to Google's Indexing API on demand.
	 */
	public function ajax_index(): void {
		check_ajax_referer( 'hgsd_ajax', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}

		$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
		if ( ! $post_id || null === $this->indexing || ! $this->indexing->ready() ) {
			wp_send_json_error( [ 'message' => __( 'Instant indexing is not enabled.', 'hg-structured-data' ) ] );
		}

		$result = $this->indexing->submit_post( $post_id );
		if ( is_wp_error( $result ) ) {
			wp_send_json_error( [ 'message' => $result->get_error_message() ] );
		}

		wp_send_json_success(
			[
				'time'      => (int) get_post_meta( $post_id, IndexingClient::META_TIME, true ),
				'remaining' => $this->indexing->remaining_quota(),
				'message'   => __( 'Submitted to Google.', 'hg-structured-data' ),
			]
		);
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
					'noLink'    => __( 'no link yet', 'hg-structured-data' ),
					'linkTo'    => __( 'Consider linking to', 'hg-structured-data' ),
					'mentioned' => __( 'mentioned in text', 'hg-structured-data' ),
					'gscOff'    => __( 'Not connected — see Structured Data → Search Console.', 'hg-structured-data' ),
					'gscNone'   => __( 'Not inspected yet.', 'hg-structured-data' ),
					'indexBtn'    => __( 'Submit to Google', 'hg-structured-data' ),
					'indexBusy'   => __( 'Submitting…', 'hg-structured-data' ),
					'indexOff'    => __( 'Enable Structured Data → Instant Indexing to submit URLs.', 'hg-structured-data' ),
					'indexNever'  => __( 'Not submitted yet.', 'hg-structured-data' ),
					'indexOn'     => __( 'Last submitted', 'hg-structured-data' ),
					'aiClean'     => __( 'Content looks machine-readable. Use “Check full page” for headings and H1.', 'hg-structured-data' ),
					'aiCleanFull' => __( 'The full page is machine-readable — no issues found.', 'hg-structured-data' ),
					'rescanning'  => __( 'Re-scanning the flagged pages…', 'hg-structured-data' ),
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

		$public_types = \HelloGekko\StructuredData\ContentTypes::objects();

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
		$missing  = $this->relations->missing_link_counts();
		$adapter  = $this->seo->adapter();

		$skip_types = Advisor::settings()['skip_orphan_types'];

		$rows = [];
		foreach ( $query->posts as $post ) {
			$row = [
				'post'        => $post,
				'schemas'     => $this->schema_labels( $post ),
				'inlinks'     => $inlinks[ $post->ID ] ?? 0,
				'outlinks'    => $outlinks[ $post->ID ] ?? 0,
				'depth'       => $depths[ $post->ID ] ?? null,
				'missing'     => $missing[ $post->ID ] ?? 0,
				'cornerstone' => $adapter->get_cornerstone( $post->ID ),
				'canonical'   => $adapter->get_canonical( $post->ID ),
				'robots'      => $adapter->get_robots( $post->ID ),
			];

			// Link state: fine / archive-only (weak) / true orphan.
			$state = 'linked';
			if (
				GraphMetrics::is_orphan( $post->ID, $inlinks )
				&& ! in_array( $post->post_type, $skip_types, true )
				&& ! $row['robots']['noindex']
			) {
				$state = Advisor::is_archive_reachable( $post ) ? 'archive' : 'orphan';
			}
			$row['link_state'] = $state;

			$row['gsc_mismatch'] = GscClient::canonical_mismatch( $post->ID, (string) $row['canonical'] );

			if ( 'orphans' === $filter_flag && 'orphan' !== $state ) {
				continue;
			}
			if ( 'archiveonly' === $filter_flag && 'archive' !== $state ) {
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

		$cluster_options = [];
		foreach ( $adapter->cornerstone_ids() as $cornerstone_id ) {
			$cluster_options[ $cornerstone_id ] = (string) get_the_title( $cornerstone_id );
		}
		asort( $cluster_options );

		// Tips: everything the advisor flags, split into active and dismissed.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$show_ignored                    = isset( $_GET['ignored'] ) && '1' === $_GET['ignored'];
		[ $tips_active, $tips_dismissed ] = Advisor::split_dismissed( $this->advisor->issues() );
		$tips_skip_types                  = $skip_types;

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
				'title'         => get_the_title( $post ),
				'url'           => get_permalink( $post ),
				'edit'          => get_edit_post_link( $post->ID, 'raw' ),
				'cornerstone'   => $adapter->get_cornerstone( $post->ID ),
				'canonical'     => $adapter->get_canonical( $post->ID ),
				'robots'        => $adapter->get_robots( $post->ID ),
				'inlinks'       => $this->repository->inlinks_for( $post->ID ),
				'outlinks'      => $this->repository->outlinks_for( $post->ID ),
				'relations'     => $this->relations_payload( $post->ID ),
				'incoming'      => $this->relations->for_target( $post->ID ),
				'relationTypes' => RelationRepository::types(),
				'suggestions'   => $this->suggestions( $post ),
				'gsc'           => GscClient::result_for( $post->ID ),
				'gscReady'      => null !== $this->gsc && $this->gsc->configured(),
				'indexReady'    => null !== $this->indexing && $this->indexing->ready(),
				'indexStatus'   => IndexingClient::status_for( $post->ID ),
				'readability'   => array_values( ReadabilityAudit::messages( $this->stored_readability( $post->ID ) ) ),
			]
		);
	}

	/**
	 * Stored content-level readability issue map for a post.
	 *
	 * @return array<string,mixed>
	 */
	private function stored_readability( int $post_id ): array {
		$stored = get_post_meta( $post_id, LinkIndexer::META_READABILITY, true );
		return is_array( $stored ) ? $stored : [];
	}

	/**
	 * AJAX: re-audit the full rendered page (not just the content) for AI
	 * readability — this catches whole-page signals like a missing or duplicate
	 * H1 that the content-level index cannot see.
	 */
	public function ajax_airecheck(): void {
		check_ajax_referer( 'hgsd_ajax', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}

		$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
		$url     = $post_id ? get_permalink( $post_id ) : '';
		if ( ! $url ) {
			wp_send_json_error( [ 'message' => __( 'This page has no URL to check.', 'hg-structured-data' ) ] );
		}

		$response = wp_remote_get(
			$url,
			[
				'timeout'    => 12,
				'user-agent' => 'StructuredData-Readability/1.0',
				'headers'    => [ 'Accept' => 'text/html' ],
			]
		);
		if ( is_wp_error( $response ) || 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
			wp_send_json_error( [ 'message' => __( 'Could not fetch the page to audit it.', 'hg-structured-data' ) ] );
		}

		$issues   = ReadabilityAudit::analyze( (string) wp_remote_retrieve_body( $response ), true );
		$messages = array_values( ReadabilityAudit::messages( $issues ) );

		wp_send_json_success( [ 'messages' => $messages ] );
	}

	/**
	 * Relations for a post, each flagged with whether the actual link exists.
	 *
	 * @return array<int,array<string,mixed>>
	 */
	private function relations_payload( int $post_id ): array {
		$rows = $this->relations->for_source( $post_id );
		foreach ( $rows as &$row ) {
			$row['linked'] = $this->relations->link_exists( $post_id, $row['target_id'] );
			$row['label']  = RelationRepository::types()[ $row['relation'] ] ?? $row['relation'];
		}
		unset( $row );
		return $rows;
	}

	/**
	 * Link suggestions: cornerstones this page does not link to yet. A
	 * cornerstone whose title is literally mentioned in this page's text ranks
	 * first ("mention"); otherwise topical overlap via shared terms ("topic").
	 *
	 * @return array<int,array{post_id:int,title:string,url:string,reason:string}>
	 */
	private function suggestions( \WP_Post $post ): array {
		$cornerstones = $this->seo->adapter()->cornerstone_ids();
		if ( empty( $cornerstones ) ) {
			return [];
		}

		$linked = [];
		foreach ( $this->repository->outlinks_for( $post->ID, 200 ) as $link ) {
			$linked[ $link['post_id'] ] = true;
		}

		$text      = $this->repository->text_for( $post->ID );
		$own_terms = array_map(
			'intval',
			wp_get_object_terms( $post->ID, [ 'category', 'post_tag' ], [ 'fields' => 'ids' ] ) ?: []
		);

		$mentions = [];
		$topical  = [];
		foreach ( $cornerstones as $candidate ) {
			if ( $candidate === $post->ID || isset( $linked[ $candidate ] ) ) {
				continue;
			}

			$title = (string) get_the_title( $candidate );
			$item  = [
				'post_id' => $candidate,
				'title'   => $title,
				'url'     => (string) get_permalink( $candidate ),
			];

			// Strongest signal: the cornerstone is mentioned but not linked.
			if ( '' !== $text && mb_strlen( $title ) >= 4 && false !== mb_stripos( $text, $title ) ) {
				$item['reason'] = 'mention';
				$mentions[]     = $item;
				continue;
			}

			if ( ! empty( $own_terms ) ) {
				$candidate_terms = array_map(
					'intval',
					wp_get_object_terms( $candidate, [ 'category', 'post_tag' ], [ 'fields' => 'ids' ] ) ?: []
				);
				if ( ! empty( $candidate_terms ) && empty( array_intersect( $own_terms, $candidate_terms ) ) ) {
					continue;
				}
			}

			$item['reason'] = 'topic';
			$topical[]      = $item;
		}

		return array_slice( array_merge( $mentions, $topical ), 0, 5 );
	}

	/**
	 * AJAX: cluster graph data around a center post — nodes plus typed edges
	 * (link / relation / relation-without-link).
	 */
	public function ajax_graph(): void {
		check_ajax_referer( 'hgsd_ajax', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}

		$center = isset( $_GET['post_id'] ) ? absint( $_GET['post_id'] ) : 0;
		if ( ! $center || ! get_post( $center ) ) {
			wp_send_json_error();
		}

		// Cluster members: everything related to or linked with the center.
		$members = [ $center ];
		foreach ( $this->relations->touching( $center ) as $relation ) {
			$members[] = $relation['source_id'];
			$members[] = $relation['target_id'];
		}
		$members = array_merge( $members, $this->repository->neighbours( $center, 40 ) );
		$members = array_slice( array_values( array_unique( array_filter( $members ) ) ), 0, 41 );

		$link_edges = $this->repository->edges_between( $members );
		$link_index = [];
		foreach ( $link_edges as [ $source, $target ] ) {
			$link_index[ $source . '|' . $target ] = true;
		}

		$edges = [];
		foreach ( $link_edges as [ $source, $target ] ) {
			$edges[ $source . '|' . $target ] = [
				'source' => $source,
				'target' => $target,
				'type'   => 'link',
			];
		}
		foreach ( $this->relations->between( $members ) as $relation ) {
			$key    = $relation['source_id'] . '|' . $relation['target_id'];
			$linked = isset( $link_index[ $key ] );
			$edges[ $key . '|' . $relation['relation'] ] = [
				'source'   => $relation['source_id'],
				'target'   => $relation['target_id'],
				'type'     => $linked ? 'relation' : 'relation-missing',
				'relation' => $relation['relation'],
			];
		}

		$adapter = $this->seo->adapter();
		$inlinks = $this->repository->inlink_counts();
		$nodes   = [];
		foreach ( $members as $member ) {
			$nodes[] = [
				'id'          => $member,
				'title'       => (string) get_the_title( $member ),
				'url'         => (string) get_permalink( $member ),
				'center'      => $member === $center,
				'cornerstone' => $adapter->get_cornerstone( $member ),
				'orphan'      => GraphMetrics::is_orphan( $member, $inlinks ),
			];
		}

		wp_send_json_success(
			[
				'nodes' => $nodes,
				'edges' => array_values( $edges ),
			]
		);
	}

	/**
	 * AJAX: add a relation from the side panel.
	 */
	public function ajax_relation_add(): void {
		check_ajax_referer( 'hgsd_ajax', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}

		$source   = isset( $_POST['source'] ) ? absint( $_POST['source'] ) : 0;
		$target   = isset( $_POST['target'] ) ? absint( $_POST['target'] ) : 0;
		$relation = isset( $_POST['relation'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['relation'] ) ) : '';

		if ( ! $source || ! $target || ! get_post( $source ) || ! get_post( $target ) ) {
			wp_send_json_error();
		}

		$this->relations->add( $source, $target, $relation );

		wp_send_json_success( [ 'relations' => $this->relations_payload( $source ) ] );
	}

	/**
	 * AJAX: delete a relation from the side panel.
	 */
	public function ajax_relation_delete(): void {
		check_ajax_referer( 'hgsd_ajax', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}

		$relation_id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;
		$source      = isset( $_POST['source'] ) ? absint( $_POST['source'] ) : 0;
		if ( $relation_id ) {
			$this->relations->delete( $relation_id );
		}

		wp_send_json_success( [ 'relations' => $source ? $this->relations_payload( $source ) : [] ] );
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
