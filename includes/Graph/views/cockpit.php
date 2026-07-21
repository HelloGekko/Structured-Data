<?php
/**
 * Cockpit screen markup.
 *
 * @var array<int,array<string,mixed>>      $rows         Table rows.
 * @var array<string,\WP_Post_Type>         $public_types Public post types.
 * @var string                              $filter_type  Active post type filter.
 * @var string                              $filter_flag  Active flag filter.
 * @var string                              $search       Search term.
 * @var int                                 $paged        Current page.
 * @var int                                 $total_pages  Total pages.
 * @var bool                                $indexing     Whether a background index is running.
 * @var int                                 $indexed_at   Timestamp of last full index.
 * @var string                              $engine_label Active SEO adapter label.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;

$hgsd_base_url = add_query_arg( [ 'post_type' => HGSD_CPT, 'page' => 'hgsd-cockpit' ], admin_url( 'edit.php' ) );
?>
<div class="wrap hgsd-cockpit">
	<h1><?php esc_html_e( 'Cockpit', 'hg-structured-data' ); ?></h1>

	<p class="hgsd-cockpit-engine">
		<?php
		printf(
			/* translators: %s: SEO plugin name. */
			esc_html__( 'SEO settings (canonical, robots, cornerstone) are written to: %s', 'hg-structured-data' ),
			'<strong>' . esc_html( $engine_label ) . '</strong>'
		);
		?>
		&nbsp;·&nbsp;
		<?php if ( $indexing ) : ?>
			<em><?php esc_html_e( 'Link index is being built in the background — refresh in a minute.', 'hg-structured-data' ); ?></em>
		<?php elseif ( $indexed_at ) : ?>
			<?php
			printf(
				/* translators: %s: human time diff. */
				esc_html__( 'Links indexed %s ago.', 'hg-structured-data' ),
				esc_html( human_time_diff( $indexed_at ) )
			);
			?>
		<?php else : ?>
			<em><?php esc_html_e( 'Not indexed yet.', 'hg-structured-data' ); ?></em>
		<?php endif; ?>
		<button type="button" class="button button-small hgsd-reindex"><?php esc_html_e( 'Reindex', 'hg-structured-data' ); ?></button>
	</p>

	<?php /* Tips ------------------------------------------------------------ */ ?>
	<div class="hgsd-tips">
		<div class="hgsd-tips-head">
			<h2>
				<?php esc_html_e( 'Tips', 'hg-structured-data' ); ?>
				<span class="hgsd-tips-count <?php echo empty( $tips_active ) ? 'is-zero' : ''; ?>"><?php echo count( $tips_active ); ?></span>
			</h2>
			<?php if ( $show_ignored ) : ?>
				<a href="<?php echo esc_url( remove_query_arg( 'ignored' ) ); ?>"><?php esc_html_e( 'Hide ignored', 'hg-structured-data' ); ?></a>
			<?php else : ?>
				<a href="<?php echo esc_url( add_query_arg( 'ignored', '1' ) ); ?>">
					<?php
					printf(
						/* translators: %d: number of ignored tips. */
						esc_html__( 'Show ignored (%d)', 'hg-structured-data' ),
						count( $tips_dismissed )
					);
					?>
				</a>
			<?php endif; ?>
		</div>

		<?php if ( empty( $tips_active ) && ! $show_ignored ) : ?>
			<p class="hgsd-tips-empty">✓ <?php esc_html_e( 'Nothing to fix — everything the cockpit checks looks good.', 'hg-structured-data' ); ?></p>
		<?php else : ?>
			<ul class="hgsd-tips-list">
				<?php foreach ( $tips_active as $hgsd_tip ) : ?>
					<li class="hgsd-tip" data-key="<?php echo esc_attr( $hgsd_tip['key'] ); ?>">
						<span class="hgsd-badge hgsd-severity-<?php echo esc_attr( $hgsd_tip['severity'] ); ?>"><?php echo esc_html( $hgsd_tip['severity'] ); ?></span>
						<span class="hgsd-tip-message"><?php echo esc_html( $hgsd_tip['message'] ); ?></span>
						<?php if ( ! empty( $hgsd_tip['url'] ) ) : ?>
							<a class="hgsd-tip-open" href="<?php echo esc_url( $hgsd_tip['url'] ); ?>"><?php esc_html_e( 'View list', 'hg-structured-data' ); ?></a>
						<?php elseif ( ! empty( $hgsd_tip['post_id'] ) ) : ?>
							<button type="button" class="button-link hgsd-tip-open" data-post="<?php echo (int) $hgsd_tip['post_id']; ?>"><?php esc_html_e( 'Open', 'hg-structured-data' ); ?></button>
						<?php endif; ?>
						<button type="button" class="button-link hgsd-tip-dismiss"><?php esc_html_e( 'Ignore', 'hg-structured-data' ); ?></button>
					</li>
				<?php endforeach; ?>
				<?php if ( $show_ignored ) : ?>
					<?php foreach ( $tips_dismissed as $hgsd_tip ) : ?>
						<li class="hgsd-tip is-dismissed" data-key="<?php echo esc_attr( $hgsd_tip['key'] ); ?>">
							<span class="hgsd-badge hgsd-severity-<?php echo esc_attr( $hgsd_tip['severity'] ); ?>"><?php echo esc_html( $hgsd_tip['severity'] ); ?></span>
							<span class="hgsd-tip-message"><?php echo esc_html( $hgsd_tip['message'] ); ?></span>
							<button type="button" class="button-link hgsd-tip-restore"><?php esc_html_e( 'Restore', 'hg-structured-data' ); ?></button>
						</li>
					<?php endforeach; ?>
				<?php endif; ?>
			</ul>
		<?php endif; ?>

		<details class="hgsd-tips-settings">
			<summary><?php esc_html_e( 'Tips settings', 'hg-structured-data' ); ?></summary>
			<p>
				<?php esc_html_e( 'Skip the orphan/archive checks for:', 'hg-structured-data' ); ?>
				<?php foreach ( $public_types as $hgsd_pt ) : ?>
					<label class="hgsd-tips-skip-label">
						<input type="checkbox" class="hgsd-tips-skip" value="<?php echo esc_attr( $hgsd_pt->name ); ?>" <?php checked( in_array( $hgsd_pt->name, $tips_skip_types, true ) ); ?> />
						<?php echo esc_html( $hgsd_pt->labels->name ); ?>
					</label>
				<?php endforeach; ?>
				<button type="button" class="button button-small hgsd-tips-settings-save"><?php esc_html_e( 'Save', 'hg-structured-data' ); ?></button>
			</p>
		</details>
	</div>

	<?php if ( ! empty( $cluster_options ) ) : ?>
		<div class="hgsd-graph-bar">
			<label>
				<?php esc_html_e( 'Cluster graph:', 'hg-structured-data' ); ?>
				<select class="hgsd-graph-select">
					<option value=""><?php esc_html_e( '— choose a cornerstone —', 'hg-structured-data' ); ?></option>
					<?php foreach ( $cluster_options as $hgsd_cid => $hgsd_ctitle ) : ?>
						<option value="<?php echo (int) $hgsd_cid; ?>"><?php echo esc_html( $hgsd_ctitle ); ?></option>
					<?php endforeach; ?>
				</select>
			</label>
			<span class="hgsd-graph-legend">
				<span class="hgsd-legend-item"><span class="hgsd-legend-node hgsd-legend-center"></span> <?php esc_html_e( 'this page', 'hg-structured-data' ); ?></span>
				<span class="hgsd-legend-item"><span class="hgsd-legend-node hgsd-legend-cornerstone"></span> <?php esc_html_e( 'cornerstone', 'hg-structured-data' ); ?></span>
				<span class="hgsd-legend-item"><span class="hgsd-legend-node hgsd-legend-orphan"></span> <?php esc_html_e( 'orphan (no incoming links)', 'hg-structured-data' ); ?></span>
				<span class="hgsd-legend-item"><span class="hgsd-legend-line hgsd-legend-link"></span> <?php esc_html_e( 'link', 'hg-structured-data' ); ?></span>
				<span class="hgsd-legend-item"><span class="hgsd-legend-line hgsd-legend-relation"></span> <?php esc_html_e( 'relation + link', 'hg-structured-data' ); ?></span>
				<span class="hgsd-legend-item"><span class="hgsd-legend-line hgsd-legend-missing"></span> <?php esc_html_e( 'relation, link missing', 'hg-structured-data' ); ?></span>
			</span>
		</div>
		<div class="hgsd-graph-wrap" hidden>
			<svg class="hgsd-graph-svg" viewBox="0 0 900 560" preserveAspectRatio="xMidYMid meet"></svg>
		</div>
	<?php endif; ?>

	<form method="get" class="hgsd-cockpit-filters">
		<input type="hidden" name="post_type" value="<?php echo esc_attr( HGSD_CPT ); ?>" />
		<input type="hidden" name="page" value="hgsd-cockpit" />
		<select name="pt">
			<option value=""><?php esc_html_e( 'All post types', 'hg-structured-data' ); ?></option>
			<?php foreach ( $public_types as $hgsd_pt ) : ?>
				<option value="<?php echo esc_attr( $hgsd_pt->name ); ?>" <?php selected( $filter_type, $hgsd_pt->name ); ?>><?php echo esc_html( $hgsd_pt->labels->name ); ?></option>
			<?php endforeach; ?>
		</select>
		<select name="flag">
			<option value=""><?php esc_html_e( 'All pages', 'hg-structured-data' ); ?></option>
			<option value="orphans" <?php selected( $filter_flag, 'orphans' ); ?>><?php esc_html_e( 'True orphans only', 'hg-structured-data' ); ?></option>
			<option value="archiveonly" <?php selected( $filter_flag, 'archiveonly' ); ?>><?php esc_html_e( 'Archive-only (no contextual links)', 'hg-structured-data' ); ?></option>
			<option value="cornerstones" <?php selected( $filter_flag, 'cornerstones' ); ?>><?php esc_html_e( 'Cornerstones only', 'hg-structured-data' ); ?></option>
		</select>
		<input type="search" name="s" value="<?php echo esc_attr( $search ); ?>" placeholder="<?php esc_attr_e( 'Search…', 'hg-structured-data' ); ?>" />
		<button class="button"><?php esc_html_e( 'Filter', 'hg-structured-data' ); ?></button>
	</form>

	<table class="widefat striped hgsd-cockpit-table">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Page', 'hg-structured-data' ); ?></th>
				<th><?php esc_html_e( 'Schemas', 'hg-structured-data' ); ?></th>
				<th class="hgsd-num"><?php esc_html_e( 'In', 'hg-structured-data' ); ?></th>
				<th class="hgsd-num"><?php esc_html_e( 'Out', 'hg-structured-data' ); ?></th>
				<th class="hgsd-num"><?php esc_html_e( 'Depth', 'hg-structured-data' ); ?></th>
				<th><?php esc_html_e( 'Cornerstone', 'hg-structured-data' ); ?></th>
				<th><?php esc_html_e( 'Robots', 'hg-structured-data' ); ?></th>
				<th><?php esc_html_e( 'Canonical', 'hg-structured-data' ); ?></th>
				<th><?php esc_html_e( 'Flags', 'hg-structured-data' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php if ( empty( $rows ) ) : ?>
				<tr><td colspan="9"><?php esc_html_e( 'No pages found.', 'hg-structured-data' ); ?></td></tr>
			<?php endif; ?>
			<?php foreach ( $rows as $hgsd_row ) : ?>
				<?php
				$hgsd_post   = $hgsd_row['post'];
				$hgsd_robots = $hgsd_row['robots'];
				?>
				<tr class="hgsd-row-item" data-post="<?php echo (int) $hgsd_post->ID; ?>"
					data-canonical="<?php echo esc_attr( (string) $hgsd_row['canonical'] ); ?>"
					data-noindex="<?php echo $hgsd_robots['noindex'] ? '1' : '0'; ?>"
					data-nofollow="<?php echo $hgsd_robots['nofollow'] ? '1' : '0'; ?>">
					<td>
						<strong><?php echo esc_html( get_the_title( $hgsd_post ) ); ?></strong>
						<span class="hgsd-muted"><?php echo esc_html( $public_types[ $hgsd_post->post_type ]->labels->singular_name ?? $hgsd_post->post_type ); ?></span>
					</td>
					<td>
						<?php echo $hgsd_row['schemas'] ? esc_html( implode( ', ', $hgsd_row['schemas'] ) ) : '<span class="hgsd-muted">—</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</td>
					<td class="hgsd-num"><?php echo (int) $hgsd_row['inlinks']; ?></td>
					<td class="hgsd-num"><?php echo (int) $hgsd_row['outlinks']; ?></td>
					<td class="hgsd-num"><?php echo null === $hgsd_row['depth'] ? '—' : (int) $hgsd_row['depth']; ?></td>
					<td><input type="checkbox" class="hgsd-cornerstone-toggle" <?php checked( (bool) $hgsd_row['cornerstone'] ); ?> /></td>
					<td class="hgsd-robots-cell">
						<?php
						$hgsd_flags = [];
						if ( $hgsd_robots['noindex'] ) {
							$hgsd_flags[] = 'noindex';
						}
						if ( $hgsd_robots['nofollow'] ) {
							$hgsd_flags[] = 'nofollow';
						}
						echo $hgsd_flags ? esc_html( implode( ', ', $hgsd_flags ) ) : '<span class="hgsd-muted">' . esc_html__( 'default', 'hg-structured-data' ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
					</td>
					<td class="hgsd-canonical-cell">
						<?php echo '' !== $hgsd_row['canonical'] ? '<span class="hgsd-badge hgsd-badge-blue">' . esc_html__( 'override', 'hg-structured-data' ) . '</span>' : '<span class="hgsd-muted">' . esc_html__( 'default', 'hg-structured-data' ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</td>
					<td>
						<?php if ( 'orphan' === $hgsd_row['link_state'] ) : ?>
							<span class="hgsd-badge hgsd-badge-red"><?php esc_html_e( 'orphan', 'hg-structured-data' ); ?></span>
						<?php elseif ( 'archive' === $hgsd_row['link_state'] ) : ?>
							<span class="hgsd-badge hgsd-badge-blue" title="<?php esc_attr_e( 'Only reachable via archive pages — no contextual links', 'hg-structured-data' ); ?>"><?php esc_html_e( 'archive-only', 'hg-structured-data' ); ?></span>
						<?php endif; ?>
						<?php if ( $hgsd_row['missing'] > 0 ) : ?>
							<span class="hgsd-badge hgsd-badge-yellow" title="<?php esc_attr_e( 'Declared relations without an actual link', 'hg-structured-data' ); ?>">
								<?php
								printf(
									/* translators: %d: number of missing links. */
									esc_html__( '%d link missing', 'hg-structured-data' ),
									(int) $hgsd_row['missing']
								);
								?>
							</span>
						<?php endif; ?>
						<?php if ( ! empty( $hgsd_row['gsc_mismatch'] ) ) : ?>
							<span class="hgsd-badge hgsd-badge-red" title="<?php esc_attr_e( 'Google chose a different canonical than this page declares', 'hg-structured-data' ); ?>">
								<?php esc_html_e( 'GSC canonical', 'hg-structured-data' ); ?>
							</span>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php if ( $total_pages > 1 ) : ?>
		<p class="hgsd-cockpit-pagination">
			<?php if ( $paged > 1 ) : ?>
				<a class="button" href="<?php echo esc_url( add_query_arg( [ 'pt' => $filter_type, 'flag' => $filter_flag, 's' => $search, 'paged' => $paged - 1 ], $hgsd_base_url ) ); ?>">‹ <?php esc_html_e( 'Previous', 'hg-structured-data' ); ?></a>
			<?php endif; ?>
			<span><?php echo esc_html( $paged . ' / ' . $total_pages ); ?></span>
			<?php if ( $paged < $total_pages ) : ?>
				<a class="button" href="<?php echo esc_url( add_query_arg( [ 'pt' => $filter_type, 'flag' => $filter_flag, 's' => $search, 'paged' => $paged + 1 ], $hgsd_base_url ) ); ?>"><?php esc_html_e( 'Next', 'hg-structured-data' ); ?> ›</a>
			<?php endif; ?>
		</p>
	<?php endif; ?>

	<?php /* Side panel ---------------------------------------------------- */ ?>
	<div class="hgsd-panel" hidden>
		<div class="hgsd-panel-head">
			<h2 class="hgsd-panel-title"></h2>
			<button type="button" class="button-link hgsd-panel-close" aria-label="<?php esc_attr_e( 'Close', 'hg-structured-data' ); ?>">✕</button>
		</div>
		<p class="hgsd-panel-links">
			<a href="#" target="_blank" class="hgsd-panel-view"><?php esc_html_e( 'View', 'hg-structured-data' ); ?></a> ·
			<a href="#" class="hgsd-panel-edit"><?php esc_html_e( 'Edit', 'hg-structured-data' ); ?></a>
		</p>

		<label class="hgsd-panel-field">
			<input type="checkbox" class="hgsd-panel-cornerstone" />
			<?php esc_html_e( 'Cornerstone content', 'hg-structured-data' ); ?>
		</label>

		<label class="hgsd-panel-field">
			<?php esc_html_e( 'Canonical URL (leave empty for default)', 'hg-structured-data' ); ?>
			<input type="url" class="hgsd-panel-canonical widefat" placeholder="https://" />
		</label>

		<fieldset class="hgsd-panel-field">
			<label><input type="checkbox" class="hgsd-panel-noindex" /> noindex</label>
			<label><input type="checkbox" class="hgsd-panel-nofollow" /> nofollow</label>
		</fieldset>

		<p>
			<button type="button" class="button button-primary hgsd-panel-save"><?php esc_html_e( 'Save', 'hg-structured-data' ); ?></button>
			<span class="hgsd-panel-status"></span>
		</p>

		<h3><?php esc_html_e( 'Relations', 'hg-structured-data' ); ?></h3>
		<ul class="hgsd-panel-relations"></ul>
		<div class="hgsd-panel-add-relation">
			<select class="hgsd-rel-type"></select>
			<span class="hgsd-rel-search">
				<input type="text" class="hgsd-rel-search-input" autocomplete="off" placeholder="<?php esc_attr_e( 'Search page…', 'hg-structured-data' ); ?>" />
				<input type="hidden" class="hgsd-rel-target" />
				<ul class="hgsd-rel-results" hidden></ul>
			</span>
			<button type="button" class="button hgsd-rel-add"><?php esc_html_e( 'Add', 'hg-structured-data' ); ?></button>
		</div>
		<p class="description"><?php esc_html_e( 'Relations are emitted as schema.org references (isPartOf, about, …) and flag a warning until a real link exists.', 'hg-structured-data' ); ?></p>

		<h3><?php esc_html_e( 'Referenced by', 'hg-structured-data' ); ?></h3>
		<ul class="hgsd-panel-incoming"></ul>

		<h3><?php esc_html_e( 'Link suggestions', 'hg-structured-data' ); ?></h3>
		<ul class="hgsd-panel-suggestions"></ul>

		<h3><?php esc_html_e( 'Search Console', 'hg-structured-data' ); ?></h3>
		<div class="hgsd-panel-gsc">
			<ul class="hgsd-panel-gsc-facts"></ul>
			<button type="button" class="button button-small hgsd-gsc-inspect"><?php esc_html_e( 'Inspect now', 'hg-structured-data' ); ?></button>
			<span class="hgsd-gsc-status"></span>
		</div>

		<h3><?php esc_html_e( 'Instant indexing', 'hg-structured-data' ); ?></h3>
		<div class="hgsd-panel-index">
			<ul class="hgsd-panel-index-facts"></ul>
			<button type="button" class="button button-small button-primary hgsd-index-submit"><?php esc_html_e( 'Submit to Google', 'hg-structured-data' ); ?></button>
			<span class="hgsd-index-status hgsd-panel-status"></span>
		</div>

		<h3><?php esc_html_e( 'AI readability', 'hg-structured-data' ); ?></h3>
		<div class="hgsd-panel-ai">
			<ul class="hgsd-panel-ai-facts"></ul>
			<button type="button" class="button button-small hgsd-ai-recheck"><?php esc_html_e( 'Check full page', 'hg-structured-data' ); ?></button>
			<span class="hgsd-ai-status hgsd-panel-status"></span>
		</div>

		<h3><?php esc_html_e( 'Incoming links', 'hg-structured-data' ); ?></h3>
		<ul class="hgsd-panel-inlinks"></ul>

		<h3><?php esc_html_e( 'Outgoing links', 'hg-structured-data' ); ?></h3>
		<ul class="hgsd-panel-outlinks"></ul>
	</div>
</div>
