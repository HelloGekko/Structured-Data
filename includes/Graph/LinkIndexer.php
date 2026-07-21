<?php
/**
 * Builds and maintains the internal-link index.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Graph;

use HelloGekko\StructuredData\AI\ReadabilityAudit;
use HelloGekko\StructuredData\ContentTypes;
use HelloGekko\StructuredData\Output\ContentRenderer;

defined( 'ABSPATH' ) || exit;

/**
 * Extracts internal links from rendered post content (Elementor-aware) and
 * from nav menus, and keeps the hgsd_links table current. Menu links are
 * stored with source_id 0 — the virtual "site/home" node.
 */
final class LinkIndexer {

	private const BATCH_SIZE = 25;
	private const RUN_BUDGET = 15;

	/** Post meta holding the content-level AI-readability issue map. */
	public const META_READABILITY = '_hgsd_ai_issues';

	public function register_hooks(): void {
		add_action( 'save_post', [ $this, 'on_save_post' ], 20, 2 );
		add_action( 'deleted_post', [ $this, 'on_deleted_post' ] );
		add_action( 'wp_update_nav_menu', [ $this, 'reindex_menus' ] );
		add_action( Installer::CRON_HOOK, [ $this, 'run_batch' ] );
	}

	/**
	 * Reindex a post when it is saved.
	 *
	 * @param int      $post_id Post ID.
	 * @param \WP_Post $post    Post object.
	 */
	public function on_save_post( int $post_id, \WP_Post $post ): void {
		if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
			return;
		}
		$this->index_post( $post );
		GraphMetrics::flush_cache();
	}

	/**
	 * Clean up rows when a post is deleted.
	 */
	public function on_deleted_post( int $post_id ): void {
		global $wpdb;
		$table = Installer::table();
		$wpdb->delete( $table, [ 'source_id' => $post_id ] ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$wpdb->delete( $table, [ 'target_id' => $post_id ] ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$wpdb->delete( Installer::content_table(), [ 'post_id' => $post_id ] ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		GraphMetrics::flush_cache();
	}

	/**
	 * Index one post: parse its rendered content for internal links.
	 */
	public function index_post( \WP_Post $post ): void {
		global $wpdb;
		$table = Installer::table();

		// Always clear the old rows for this source first.
		$wpdb->delete( $table, [ 'source_id' => $post->ID ] ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

		if ( 'publish' !== $post->post_status || ! in_array( $post->post_type, ContentTypes::list(), true ) ) {
			$wpdb->delete( Installer::content_table(), [ 'post_id' => $post->ID ] ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			delete_post_meta( $post->ID, self::META_READABILITY );
			return;
		}

		$html = ContentRenderer::render( $post );

		// AI-readability signals from the content (alt text, link text, heading
		// order). H1 is a whole-page concern (often in the theme), so it is
		// checked on demand from the cockpit, not here.
		$this->store_readability( $post->ID, $html );

		// Plain text snapshot, used for mention-based link suggestions.
		$text = trim( (string) preg_replace( '/\s+/', ' ', wp_strip_all_tags( $html ) ) );
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$wpdb->replace(
			Installer::content_table(),
			[
				'post_id' => $post->ID,
				'txt'     => mb_substr( $text, 0, 60000 ),
			],
			[ '%d', '%s' ]
		);

		$seen = [];
		foreach ( self::extract_links( $html ) as $link ) {
			$target = $this->resolve( $link['href'] );
			if ( ! $target || $target === $post->ID ) {
				continue;
			}
			$key = $target . '|' . $link['anchor'];
			if ( isset( $seen[ $key ] ) ) {
				continue;
			}
			$seen[ $key ] = true;

			// phpcs:ignore WordPress.DB.DirectDatabaseQuery
			$wpdb->insert(
				$table,
				[
					'source_id' => $post->ID,
					'target_id' => $target,
					'anchor'    => $link['anchor'],
					'context'   => 'content',
				],
				[ '%d', '%d', '%s', '%s' ]
			);
		}
	}

	/**
	 * Compute and store the content-level AI-readability issues for a post.
	 */
	private function store_readability( int $post_id, string $html ): void {
		/**
		 * Allow disabling the AI-readability audit entirely.
		 *
		 * @param bool $enabled Default true.
		 */
		if ( ! apply_filters( 'hgsd_ai_readability_enabled', true ) ) {
			return;
		}

		$issues = ReadabilityAudit::analyze( $html, false );
		if ( empty( $issues ) ) {
			delete_post_meta( $post_id, self::META_READABILITY );
			return;
		}
		update_post_meta( $post_id, self::META_READABILITY, $issues );
	}

	/**
	 * Rebuild the menu edges (virtual source 0 → menu targets).
	 */
	public function reindex_menus(): void {
		global $wpdb;
		$table = Installer::table();
		$wpdb->delete( $table, [ 'context' => 'menu' ] ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

		$seen = [];
		foreach ( wp_get_nav_menus() as $menu ) {
			$items = wp_get_nav_menu_items( $menu );
			if ( ! is_array( $items ) ) {
				continue;
			}
			foreach ( $items as $item ) {
				$target = 0;
				if ( 'post_type' === $item->type ) {
					$target = (int) $item->object_id;
				} elseif ( ! empty( $item->url ) ) {
					$target = $this->resolve( (string) $item->url );
				}
				if ( ! $target || isset( $seen[ $target ] ) ) {
					continue;
				}
				$seen[ $target ] = true;

				// phpcs:ignore WordPress.DB.DirectDatabaseQuery
				$wpdb->insert(
					$table,
					[
						'source_id' => 0,
						'target_id' => $target,
						'anchor'    => sanitize_text_field( (string) $item->title ),
						'context'   => 'menu',
					],
					[ '%d', '%d', '%s', '%s' ]
				);
			}
		}
		GraphMetrics::flush_cache();
	}

	/**
	 * Background batch: index BATCH_SIZE posts per run until done.
	 */
	public function run_batch(): void {
		$pointer = (int) get_option( Installer::OPTION_POINTER, 0 );
		$ids     = $this->ids_after( $pointer );

		$started = microtime( true );
		$done    = 0;
		foreach ( $ids as $id ) {
			$post = get_post( $id );
			if ( $post ) {
				$this->index_post( $post );
			}
			$pointer = $id;
			++$done;

			// Rendering content (Elementor included) is the heaviest work we do;
			// stop after the budget so a single run can't tie up a PHP worker,
			// and resume from the pointer on the next run.
			if ( ( microtime( true ) - $started ) > self::RUN_BUDGET ) {
				break;
			}
		}

		// More to do when we filled a batch, or we stopped early mid-batch.
		if ( count( $ids ) === self::BATCH_SIZE || $done < count( $ids ) ) {
			update_option( Installer::OPTION_POINTER, $pointer, false );
			wp_schedule_single_event( time() + 15, Installer::CRON_HOOK );
			return;
		}

		// Done: refresh menu edges, purge stale rows and stamp completion.
		delete_option( Installer::OPTION_POINTER );
		$this->reindex_menus();
		$this->purge_stale();
		update_option( Installer::OPTION_INDEXED, time(), false );
		GraphMetrics::flush_cache();
	}

	/**
	 * Remove rows that reference deleted posts or non-content types (e.g.
	 * Elementor templates indexed before they were excluded).
	 */
	public function purge_stale(): void {
		global $wpdb;
		$types = ContentTypes::list();
		if ( empty( $types ) ) {
			return;
		}

		$links        = Installer::table();
		$content      = Installer::content_table();
		$placeholders = implode( ',', array_fill( 0, count( $types ), '%s' ) );
		$conditions   = "p.ID IS NULL OR p.post_status != 'publish' OR p.post_type NOT IN ({$placeholders})";

		// phpcs:disable WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$wpdb->query(
			$wpdb->prepare(
				"DELETE l FROM {$links} l LEFT JOIN {$wpdb->posts} p ON p.ID = l.source_id WHERE l.source_id > 0 AND ({$conditions})",
				$types
			)
		);
		$wpdb->query(
			$wpdb->prepare(
				"DELETE l FROM {$links} l LEFT JOIN {$wpdb->posts} p ON p.ID = l.target_id WHERE {$conditions}",
				$types
			)
		);
		$wpdb->query(
			$wpdb->prepare(
				"DELETE c FROM {$content} c LEFT JOIN {$wpdb->posts} p ON p.ID = c.post_id WHERE {$conditions}",
				$types
			)
		);
		// phpcs:enable
	}

	/**
	 * Published, public post IDs greater than the pointer.
	 *
	 * @return array<int,int>
	 */
	private function ids_after( int $pointer ): array {
		global $wpdb;
		$types = ContentTypes::list();
		if ( empty( $types ) ) {
			return [];
		}
		$placeholders = implode( ',', array_fill( 0, count( $types ), '%s' ) );

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$ids = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT ID FROM {$wpdb->posts} WHERE ID > %d AND post_status = 'publish' AND post_type IN ({$placeholders}) ORDER BY ID ASC LIMIT %d",
				array_merge( [ $pointer ], $types, [ self::BATCH_SIZE ] )
			)
		);

		return array_map( 'intval', $ids );
	}

	/**
	 * Extract anchor href/text pairs from an HTML fragment.
	 *
	 * @return array<int,array{href:string,anchor:string}>
	 */
	public static function extract_links( string $html ): array {
		if ( '' === trim( $html ) || ! class_exists( '\DOMDocument' ) ) {
			return [];
		}

		$dom = new \DOMDocument();
		libxml_use_internal_errors( true );
		$dom->loadHTML( '<?xml encoding="utf-8"?><div>' . $html . '</div>', LIBXML_NOERROR | LIBXML_NOWARNING );
		libxml_clear_errors();

		$out = [];
		foreach ( $dom->getElementsByTagName( 'a' ) as $a ) {
			$href = trim( (string) $a->getAttribute( 'href' ) );
			if ( '' === $href || '#' === $href[0] || 0 === stripos( $href, 'mailto:' ) || 0 === stripos( $href, 'tel:' ) || 0 === stripos( $href, 'javascript:' ) ) {
				continue;
			}
			$anchor = trim( preg_replace( '/\s+/', ' ', (string) $a->textContent ) );
			$out[]  = [
				'href'   => $href,
				'anchor' => mb_substr( $anchor, 0, 190 ),
			];
		}

		return $out;
	}

	/**
	 * Resolve a URL to a local post ID (0 when external or unresolvable).
	 */
	private function resolve( string $url ): int {
		static $cache = [];
		if ( isset( $cache[ $url ] ) ) {
			return $cache[ $url ];
		}

		$home = wp_parse_url( home_url( '/' ) );
		$link = wp_parse_url( $url );
		if ( false === $link ) {
			return $cache[ $url ] = 0; // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments
		}

		// Relative URLs are local; absolute ones must match the site host.
		if ( ! empty( $link['host'] ) && strtolower( $link['host'] ) !== strtolower( (string) ( $home['host'] ?? '' ) ) ) {
			return $cache[ $url ] = 0; // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments
		}

		$path  = (string) ( $link['path'] ?? '/' );
		$clean = home_url( $path );

		$post_id = (int) url_to_postid( $clean );

		// Fall back to the static front page for the root path.
		if ( ! $post_id && untrailingslashit( $clean ) === untrailingslashit( home_url( '/' ) ) ) {
			$post_id = (int) get_option( 'page_on_front' );
		}

		return $cache[ $url ] = $post_id; // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments
	}
}
