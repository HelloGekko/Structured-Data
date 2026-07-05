<?php
/**
 * Collects actionable tips/issues for the cockpit.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Graph;

use HelloGekko\StructuredData\Gsc\GscClient;
use HelloGekko\StructuredData\Seo\SeoManager;

defined( 'ABSPATH' ) || exit;

/**
 * Scans the link graph, relations, SEO state and Search Console snapshots and
 * turns everything that looks wrong into a flat, dismissible issue list.
 * Every issue has a stable key so a dismissal survives re-scans.
 */
final class Advisor {

	public const OPTION_DISMISSED = 'hgsd_tips_dismissed';

	private const MAX_PER_TYPE = 15;

	private LinkRepository $repository;
	private GraphMetrics $metrics;
	private RelationRepository $relations;
	private SeoManager $seo;

	public function __construct( LinkRepository $repository, GraphMetrics $metrics, RelationRepository $relations, SeoManager $seo ) {
		$this->repository = $repository;
		$this->metrics    = $metrics;
		$this->relations  = $relations;
		$this->seo        = $seo;
	}

	/**
	 * All detected issues, dismissed ones included (callers split them).
	 *
	 * @return array<int,array{key:string,severity:string,post_id:int,message:string}>
	 */
	public function issues(): array {
		$issues = array_merge(
			$this->orphans(),
			$this->missing_relation_links(),
			$this->gsc_issues(),
			$this->cornerstone_issues(),
			$this->deep_pages(),
			$this->unlinked_mentions()
		);

		return self::sort_issues( $issues );
	}

	/**
	 * Split issues into active and dismissed, per the stored dismissals.
	 *
	 * @param array<int,array<string,mixed>> $issues All issues.
	 * @return array{0:array<int,array<string,mixed>>,1:array<int,array<string,mixed>>} [active, dismissed]
	 */
	public static function split_dismissed( array $issues ): array {
		$dismissed_keys = get_option( self::OPTION_DISMISSED, [] );
		$dismissed_keys = is_array( $dismissed_keys ) ? $dismissed_keys : [];

		$active    = [];
		$dismissed = [];
		foreach ( $issues as $issue ) {
			if ( isset( $dismissed_keys[ $issue['key'] ] ) ) {
				$dismissed[] = $issue;
			} else {
				$active[] = $issue;
			}
		}

		return [ $active, $dismissed ];
	}

	/**
	 * Mark an issue key as dismissed / restore it.
	 */
	public static function set_dismissed( string $key, bool $dismissed ): void {
		$stored = get_option( self::OPTION_DISMISSED, [] );
		$stored = is_array( $stored ) ? $stored : [];

		if ( $dismissed ) {
			$stored[ $key ] = time();
		} else {
			unset( $stored[ $key ] );
		}

		update_option( self::OPTION_DISMISSED, $stored, false );
	}

	/**
	 * Stable ordering: errors first, then warnings, then tips; title inside.
	 *
	 * @param array<int,array<string,mixed>> $issues Issues.
	 * @return array<int,array<string,mixed>>
	 */
	public static function sort_issues( array $issues ): array {
		$rank = [ 'error' => 0, 'warning' => 1, 'tip' => 2 ];
		usort(
			$issues,
			static function ( $a, $b ) use ( $rank ) {
				$diff = ( $rank[ $a['severity'] ] ?? 9 ) <=> ( $rank[ $b['severity'] ] ?? 9 );
				return 0 !== $diff ? $diff : strcasecmp( (string) $a['message'], (string) $b['message'] );
			}
		);
		return $issues;
	}

	/**
	 * Pages without any incoming link or menu entry.
	 *
	 * @return array<int,array<string,mixed>>
	 */
	private function orphans(): array {
		$inlinks = $this->repository->inlink_counts();
		$adapter = $this->seo->adapter();
		$issues  = [];

		foreach ( $this->repository->all_published_ids() as $post_id ) {
			if ( ! GraphMetrics::is_orphan( $post_id, $inlinks ) ) {
				continue;
			}
			// Deliberately unlinked pages (thank-you, confirmation) are noindexed —
			// then being an orphan is by design, not a problem.
			if ( $adapter->get_robots( $post_id )['noindex'] ) {
				continue;
			}
			$issues[] = [
				'key'      => 'orphan:' . $post_id,
				'severity' => 'warning',
				'post_id'  => $post_id,
				'message'  => sprintf(
					/* translators: %s: page title. */
					__( '“%s” is an orphan: no internal link or menu item points to it, so search engines may never find it. Link to it from a related page.', 'hg-structured-data' ),
					get_the_title( $post_id )
				),
			];
		}

		return self::cap( $issues );
	}

	/**
	 * Declared relations whose actual link is missing.
	 *
	 * @return array<int,array<string,mixed>>
	 */
	private function missing_relation_links(): array {
		$issues = [];
		foreach ( $this->relations->missing_pairs() as $pair ) {
			$issues[] = [
				'key'      => 'relmiss:' . $pair['source_id'] . ':' . $pair['target_id'] . ':' . $pair['relation'],
				'severity' => 'warning',
				'post_id'  => $pair['source_id'],
				'message'  => sprintf(
					/* translators: 1: source title, 2: relation, 3: target title. */
					__( '“%1$s” declares “%2$s” → “%3$s”, but the actual link is missing. Add a link in the content.', 'hg-structured-data' ),
					get_the_title( $pair['source_id'] ),
					$pair['relation'],
					get_the_title( $pair['target_id'] )
				),
			];
		}

		return self::cap( $issues );
	}

	/**
	 * Search Console problems on inspected pages.
	 *
	 * @return array<int,array<string,mixed>>
	 */
	private function gsc_issues(): array {
		$inspected = get_posts(
			[
				'post_type'     => \HelloGekko\StructuredData\ContentTypes::list(),
				'post_status'   => 'publish',
				'numberposts'   => 300,
				'fields'        => 'ids',
				'no_found_rows' => true,
				'meta_key'      => GscClient::META_RESULT, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			]
		);

		$adapter = $this->seo->adapter();
		$issues  = [];

		foreach ( $inspected as $post_id ) {
			$post_id = (int) $post_id;
			$result  = GscClient::result_for( $post_id );

			if ( GscClient::canonical_mismatch( $post_id, $adapter->get_canonical( $post_id ) ) ) {
				$issues[] = [
					'key'      => 'gsccanon:' . $post_id,
					'severity' => 'error',
					'post_id'  => $post_id,
					'message'  => sprintf(
						/* translators: 1: page title, 2: canonical URL Google chose. */
						__( 'Google chose a different canonical for “%1$s”: %2$s. Merge or differentiate the pages, or 301 one of them.', 'hg-structured-data' ),
						get_the_title( $post_id ),
						(string) ( $result['google_canonical'] ?? '' )
					),
				];
				continue; // The coverage state is a consequence — one issue is enough.
			}

			$verdict  = (string) ( $result['verdict'] ?? '' );
			$coverage = (string) ( $result['coverage'] ?? '' );
			if ( '' !== $coverage && 'PASS' !== $verdict ) {
				$issues[] = [
					'key'      => 'gscidx:' . $post_id,
					'severity' => 'warning',
					'post_id'  => $post_id,
					'message'  => sprintf(
						/* translators: 1: page title, 2: coverage state. */
						__( '“%1$s” is not indexed: %2$s.', 'hg-structured-data' ),
						get_the_title( $post_id ),
						$coverage
					),
				];
			}
		}

		return self::cap( $issues );
	}

	/**
	 * Cornerstones that are noindexed or weakly linked.
	 *
	 * @return array<int,array<string,mixed>>
	 */
	private function cornerstone_issues(): array {
		$adapter = $this->seo->adapter();
		$inlinks = $this->repository->inlink_counts();
		$issues  = [];

		foreach ( $adapter->cornerstone_ids() as $post_id ) {
			$robots = $adapter->get_robots( $post_id );
			if ( $robots['noindex'] ) {
				$issues[] = [
					'key'      => 'cs_noindex:' . $post_id,
					'severity' => 'error',
					'post_id'  => $post_id,
					'message'  => sprintf(
						/* translators: %s: page title. */
						__( 'Cornerstone “%s” is set to noindex — your most important page is invisible to search engines.', 'hg-structured-data' ),
						get_the_title( $post_id )
					),
				];
			}

			$count = $inlinks[ $post_id ] ?? 0;
			if ( $count < 3 ) {
				$issues[] = [
					'key'      => 'cs_weak:' . $post_id,
					'severity' => 'tip',
					'post_id'  => $post_id,
					'message'  => sprintf(
						/* translators: 1: page title, 2: number of inlinks. */
						__( 'Cornerstone “%1$s” has only %2$d internal links pointing to it. Cornerstones should be the most-linked pages of their topic.', 'hg-structured-data' ),
						get_the_title( $post_id ),
						$count
					),
				];
			}
		}

		return self::cap( $issues );
	}

	/**
	 * Pages buried deeper than three clicks from home.
	 *
	 * @return array<int,array<string,mixed>>
	 */
	private function deep_pages(): array {
		$issues = [];
		foreach ( $this->metrics->depths() as $post_id => $depth ) {
			if ( $depth < 4 || ! get_post( $post_id ) ) {
				continue;
			}
			$issues[] = [
				'key'      => 'deep:' . $post_id,
				'severity' => 'tip',
				'post_id'  => $post_id,
				'message'  => sprintf(
					/* translators: 1: page title, 2: click depth. */
					__( '“%1$s” is %2$d clicks from the homepage. Important pages should be reachable in 3 clicks or fewer.', 'hg-structured-data' ),
					get_the_title( $post_id ),
					$depth
				),
			];
		}

		return self::cap( $issues );
	}

	/**
	 * Pages that mention a cornerstone by title but do not link to it.
	 *
	 * @return array<int,array<string,mixed>>
	 */
	private function unlinked_mentions(): array {
		$issues = [];

		foreach ( $this->seo->adapter()->cornerstone_ids() as $cornerstone ) {
			$title = (string) get_the_title( $cornerstone );
			if ( mb_strlen( $title ) < 4 ) {
				continue;
			}

			$linking = array_flip( $this->repository->linking_sources( $cornerstone ) );

			foreach ( $this->repository->mentioning_posts( $title ) as $post_id ) {
				if ( $post_id === $cornerstone || isset( $linking[ $post_id ] ) ) {
					continue;
				}
				$issues[] = [
					'key'      => 'mention:' . $post_id . ':' . $cornerstone,
					'severity' => 'tip',
					'post_id'  => $post_id,
					'message'  => sprintf(
						/* translators: 1: page title, 2: cornerstone title. */
						__( '“%1$s” mentions “%2$s” in its text but does not link to it — an easy internal-link win.', 'hg-structured-data' ),
						get_the_title( $post_id ),
						$title
					),
				];
			}
		}

		return self::cap( $issues );
	}

	/**
	 * Cap a detector's output so one noisy check can't flood the list.
	 *
	 * @param array<int,array<string,mixed>> $issues Issues.
	 * @return array<int,array<string,mixed>>
	 */
	private static function cap( array $issues ): array {
		return array_slice( $issues, 0, self::MAX_PER_TYPE );
	}
}
