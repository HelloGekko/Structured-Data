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
			<option value="orphans" <?php selected( $filter_flag, 'orphans' ); ?>><?php esc_html_e( 'Orphans only', 'hg-structured-data' ); ?></option>
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
						<?php if ( $hgsd_row['orphan'] ) : ?>
							<span class="hgsd-badge hgsd-badge-red"><?php esc_html_e( 'orphan', 'hg-structured-data' ); ?></span>
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

		<h3><?php esc_html_e( 'Incoming links', 'hg-structured-data' ); ?></h3>
		<ul class="hgsd-panel-inlinks"></ul>

		<h3><?php esc_html_e( 'Outgoing links', 'hg-structured-data' ); ?></h3>
		<ul class="hgsd-panel-outlinks"></ul>
	</div>
</div>
