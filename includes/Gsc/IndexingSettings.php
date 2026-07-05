<?php
/**
 * Settings screen for the Google Indexing API ("instant indexing").
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Gsc;

use HelloGekko\StructuredData\ContentTypes;

defined( 'ABSPATH' ) || exit;

/**
 * Renders and persists the instant-indexing settings. Submission itself and the
 * shared OAuth connection live in IndexingClient / GscClient.
 */
final class IndexingSettings {

	private IndexingClient $client;
	private GscClient $gsc;

	public function __construct( IndexingClient $client, GscClient $gsc ) {
		$this->client = $client;
		$this->gsc    = $gsc;
	}

	public function register_hooks(): void {
		add_action( 'admin_menu', [ $this, 'menu' ] );
		add_action( 'admin_post_hgsd_save_indexing', [ $this, 'save' ] );
	}

	public function menu(): void {
		add_submenu_page(
			'edit.php?post_type=' . HGSD_CPT,
			__( 'Instant Indexing', 'hg-structured-data' ),
			__( 'Instant Indexing', 'hg-structured-data' ),
			'manage_options',
			'hgsd-indexing',
			[ $this, 'render' ]
		);
	}

	private function settings_url( array $args = [] ): string {
		return add_query_arg(
			array_merge( [ 'post_type' => HGSD_CPT, 'page' => 'hgsd-indexing' ], $args ),
			admin_url( 'edit.php' )
		);
	}

	public function save(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Not allowed.', 'hg-structured-data' ) );
		}
		check_admin_referer( 'hgsd_save_indexing' );

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- verified above.
		$in = isset( $_POST['hgsd_indexing'] ) ? wp_unslash( $_POST['hgsd_indexing'] ) : [];
		$in = is_array( $in ) ? $in : [];

		$types = isset( $in['post_types'] ) && is_array( $in['post_types'] )
			? array_values( array_map( 'sanitize_key', $in['post_types'] ) )
			: [];

		update_option(
			IndexingClient::OPTION,
			[
				'enabled'    => ! empty( $in['enabled'] ),
				'auto'       => ! empty( $in['auto'] ),
				'quota'      => max( 1, (int) ( $in['quota'] ?? 200 ) ),
				'post_types' => $types,
			]
		);

		wp_safe_redirect( $this->settings_url( [ 'updated' => '1' ] ) );
		exit;
	}

	public function render(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$s          = $this->client->settings();
		$connected  = $this->gsc->configured();
		$used       = $this->client->used_today();
		$quota      = $s['quota'];
		$log        = $this->client->recent_log();
		$post_types = get_post_types( [ 'public' => true ], 'objects' );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Instant Indexing', 'hg-structured-data' ); ?></h1>
			<p class="description">
				<?php esc_html_e( 'Submit new and updated pages straight to Google for (re)crawling through the Indexing API, using your existing Search Console connection.', 'hg-structured-data' ); ?>
			</p>

			<?php if ( isset( $_GET['updated'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
				<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Settings saved.', 'hg-structured-data' ); ?></p></div>
			<?php endif; ?>

			<?php if ( ! $connected ) : ?>
				<div class="notice notice-warning inline">
					<p>
						<?php
						printf(
							/* translators: %s: settings page link. */
							esc_html__( 'Connect %s first — instant indexing reuses that connection.', 'hg-structured-data' ),
							'<a href="' . esc_url( add_query_arg( [ 'post_type' => HGSD_CPT, 'page' => 'hgsd-gsc' ], admin_url( 'edit.php' ) ) ) . '">' . esc_html__( 'Search Console', 'hg-structured-data' ) . '</a>'
						);
						?>
					</p>
				</div>
			<?php else : ?>
				<div class="notice notice-info inline">
					<p>
						<?php esc_html_e( 'Submissions used today:', 'hg-structured-data' ); ?>
						<strong><?php echo esc_html( $used . ' / ' . $quota ); ?></strong>
						&nbsp;—&nbsp;
						<?php esc_html_e( 'If submissions fail with a permission error, reconnect Search Console once to grant the new indexing permission.', 'hg-structured-data' ); ?>
					</p>
				</div>
			<?php endif; ?>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="hgsd_save_indexing" />
				<?php wp_nonce_field( 'hgsd_save_indexing' ); ?>

				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><?php esc_html_e( 'Enable instant indexing', 'hg-structured-data' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="hgsd_indexing[enabled]" value="1" <?php checked( $s['enabled'] ); ?> />
								<?php esc_html_e( 'Allow this site to submit URLs to the Google Indexing API.', 'hg-structured-data' ); ?>
							</label>
							<p class="description">
								<?php esc_html_e( 'Google documents this API for job postings and livestreams; in practice it accepts any URL, like the instant-indexing feature in other SEO plugins.', 'hg-structured-data' ); ?>
							</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Submit automatically', 'hg-structured-data' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="hgsd_indexing[auto]" value="1" <?php checked( $s['auto'] ); ?> />
								<?php esc_html_e( 'Send a URL whenever a page is published or updated (and remove it on trash).', 'hg-structured-data' ); ?>
							</label>
							<p class="description"><?php esc_html_e( 'You can always submit a single URL by hand from the Cockpit detail panel.', 'hg-structured-data' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="hgsd-indexing-quota"><?php esc_html_e( 'Daily quota', 'hg-structured-data' ); ?></label></th>
						<td>
							<input type="number" min="1" max="2000" id="hgsd-indexing-quota" name="hgsd_indexing[quota]" value="<?php echo esc_attr( (string) $quota ); ?>" class="small-text" />
							<p class="description"><?php esc_html_e( 'Google allows 200 requests per day by default. Submissions pause once this is reached and resume the next day.', 'hg-structured-data' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Post types', 'hg-structured-data' ); ?></th>
						<td>
							<fieldset>
								<?php
								foreach ( $post_types as $type ) :
									if ( ! ContentTypes::is_content( $type->name ) ) {
										continue;
									}
									?>
									<label style="margin-right:14px;">
										<input type="checkbox" name="hgsd_indexing[post_types][]" value="<?php echo esc_attr( $type->name ); ?>" <?php checked( in_array( $type->name, $s['post_types'], true ) ); ?> />
										<?php echo esc_html( $type->labels->name ); ?>
									</label>
								<?php endforeach; ?>
							</fieldset>
							<p class="description"><?php esc_html_e( 'Only these post types are submitted automatically.', 'hg-structured-data' ); ?></p>
						</td>
					</tr>
				</table>

				<?php submit_button( __( 'Save settings', 'hg-structured-data' ) ); ?>
			</form>

			<?php if ( ! empty( $log ) ) : ?>
				<h2><?php esc_html_e( 'Recent submissions', 'hg-structured-data' ); ?></h2>
				<table class="widefat striped">
					<thead>
						<tr>
							<th><?php esc_html_e( 'When', 'hg-structured-data' ); ?></th>
							<th><?php esc_html_e( 'URL', 'hg-structured-data' ); ?></th>
							<th><?php esc_html_e( 'Type', 'hg-structured-data' ); ?></th>
							<th><?php esc_html_e( 'Result', 'hg-structured-data' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $log as $entry ) : ?>
							<tr>
								<td><?php echo esc_html( wp_date( 'j M H:i', (int) $entry['time'] ) ); ?></td>
								<td><?php echo esc_html( $entry['url'] ); ?></td>
								<td><?php echo 'URL_DELETED' === $entry['type'] ? esc_html__( 'Remove', 'hg-structured-data' ) : esc_html__( 'Update', 'hg-structured-data' ); ?></td>
								<td>
									<?php if ( 'ok' === $entry['status'] ) : ?>
										<span style="color:#16794a;">✓ <?php esc_html_e( 'Sent', 'hg-structured-data' ); ?></span>
									<?php else : ?>
										<span style="color:#b32d2e;" title="<?php echo esc_attr( (string) $entry['message'] ); ?>">✕ <?php esc_html_e( 'Failed', 'hg-structured-data' ); ?></span>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>
		</div>
		<?php
	}
}
