<?php
/**
 * Search Console settings screen.
 *
 * @var array<string,mixed> $s          Current settings.
 * @var bool                $configured Whether fully configured.
 * @var string              $redirect   OAuth redirect URI.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;

// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only notices.
$notice_error = isset( $_GET['error'] ) ? sanitize_text_field( rawurldecode( wp_unslash( (string) $_GET['error'] ) ) ) : '';
$show_updated = isset( $_GET['updated'] );
$show_conn    = isset( $_GET['connected'] );
// phpcs:enable WordPress.Security.NonceVerification.Recommended
?>
<div class="wrap">
	<h1><?php esc_html_e( 'Search Console', 'hg-structured-data' ); ?></h1>

	<?php if ( $show_updated ) : ?>
		<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Settings saved.', 'hg-structured-data' ); ?></p></div>
	<?php endif; ?>
	<?php if ( $show_conn ) : ?>
		<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Search Console connected.', 'hg-structured-data' ); ?></p></div>
	<?php endif; ?>
	<?php if ( '' !== $notice_error ) : ?>
		<div class="notice notice-error is-dismissible"><p><?php echo esc_html( $notice_error ); ?></p></div>
	<?php endif; ?>

	<p class="description">
		<?php esc_html_e( 'Connect Google Search Console to see per page how Google indexed it — coverage state, the canonical Google chose, and the last crawl — right inside the Cockpit.', 'hg-structured-data' ); ?>
	</p>

	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<input type="hidden" name="action" value="hgsd_save_gsc" />
		<?php wp_nonce_field( 'hgsd_save_gsc' ); ?>

		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><?php esc_html_e( 'OAuth Client ID', 'hg-structured-data' ); ?></th>
				<td><input type="text" class="regular-text" name="hgsd_gsc[client_id]" value="<?php echo esc_attr( $s['client_id'] ); ?>" autocomplete="off" /></td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'OAuth Client Secret', 'hg-structured-data' ); ?></th>
				<td><input type="text" class="regular-text" name="hgsd_gsc[client_secret]" value="<?php echo esc_attr( $s['client_secret'] ); ?>" autocomplete="off" /></td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Authorized redirect URI', 'hg-structured-data' ); ?></th>
				<td><code><?php echo esc_html( $redirect ); ?></code>
					<p class="description"><?php esc_html_e( 'Add this exact URI to your OAuth client in Google Cloud Console (API: "Google Search Console API").', 'hg-structured-data' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Property', 'hg-structured-data' ); ?></th>
				<td>
					<input type="text" class="regular-text" name="hgsd_gsc[property]" value="<?php echo esc_attr( $s['property'] ); ?>" placeholder="sc-domain:example.com" />
					<p class="description"><?php esc_html_e( 'The Search Console property exactly as registered: "sc-domain:example.com" for a domain property, or the full URL (with trailing slash) for a URL-prefix property.', 'hg-structured-data' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Refresh token', 'hg-structured-data' ); ?></th>
				<td>
					<input type="text" class="large-text" name="hgsd_gsc[refresh_token]" value="<?php echo esc_attr( $s['refresh_token'] ); ?>" autocomplete="off" placeholder="<?php esc_attr_e( 'Connect below, or paste a refresh token manually', 'hg-structured-data' ); ?>" />
					<p class="description">
						<?php echo $configured ? esc_html__( 'Connected ✓', 'hg-structured-data' ) : esc_html__( 'Not connected.', 'hg-structured-data' ); ?>
					</p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Background sync', 'hg-structured-data' ); ?></th>
				<td>
					<label>
						<input type="checkbox" name="hgsd_gsc[batch]" value="1" <?php checked( ! empty( $s['batch'] ) ); ?> />
						<?php esc_html_e( 'Inspect pages automatically (20 per hour, least-recently-checked first)', 'hg-structured-data' ); ?>
					</label>
				</td>
			</tr>
		</table>

		<?php submit_button( __( 'Save settings', 'hg-structured-data' ) ); ?>
	</form>

	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<input type="hidden" name="action" value="hgsd_gsc_connect" />
		<?php wp_nonce_field( 'hgsd_gsc_connect' ); ?>
		<?php submit_button( __( 'Connect Search Console', 'hg-structured-data' ), 'secondary', 'submit', false ); ?>
	</form>
</div>
