<?php
/**
 * Reviews settings screen.
 *
 * @var array<string,mixed> $s    Current settings.
 * @var array<string,mixed> $data Cached review payload.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;

$save_url    = admin_url( 'admin-post.php' );
$redirect_ui = admin_url( 'admin-post.php?action=hgsd_gbp_callback' );

// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only notices.
$notice_error = isset( $_GET['error'] ) ? sanitize_text_field( rawurldecode( wp_unslash( (string) $_GET['error'] ) ) ) : '';
$show_updated = isset( $_GET['updated'] );
$show_synced  = isset( $_GET['synced'] ) && '1' === $_GET['synced'];
$show_conn    = isset( $_GET['connected'] );
// phpcs:enable WordPress.Security.NonceVerification.Recommended
?>
<div class="wrap hgsd-reviews-settings">
	<h1><?php esc_html_e( 'Reviews', 'hg-structured-data' ); ?></h1>

	<?php if ( $show_updated ) : ?>
		<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Settings saved.', 'hg-structured-data' ); ?></p></div>
	<?php endif; ?>
	<?php if ( $show_synced ) : ?>
		<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Reviews synced.', 'hg-structured-data' ); ?></p></div>
	<?php endif; ?>
	<?php if ( $show_conn ) : ?>
		<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Google Business Profile connected.', 'hg-structured-data' ); ?></p></div>
	<?php endif; ?>
	<?php if ( '' !== $notice_error ) : ?>
		<div class="notice notice-error is-dismissible"><p><?php echo esc_html( $notice_error ); ?></p></div>
	<?php endif; ?>

	<div class="notice notice-warning">
		<p>
			<strong><?php esc_html_e( 'Heads up — Google policy.', 'hg-structured-data' ); ?></strong>
			<?php esc_html_e( 'Google\'s rich result guidelines state that reviews shown as structured data must be collected directly from your users. Outputting third-party reviews (including Google\'s own) as Review/AggregateRating markup can trigger a manual spam action. Use this feature deliberately.', 'hg-structured-data' ); ?>
		</p>
	</div>

	<p class="hgsd-sync-status">
		<?php if ( $data['fetched_at'] ) : ?>
			<?php
			printf(
				/* translators: 1: human time diff, 2: number of cached reviews. */
				esc_html__( 'Last synced %1$s ago — %2$d reviews cached.', 'hg-structured-data' ),
				esc_html( human_time_diff( $data['fetched_at'] ) ),
				count( $data['items'] )
			);
			?>
		<?php else : ?>
			<?php esc_html_e( 'Not synced yet.', 'hg-structured-data' ); ?>
		<?php endif; ?>
	</p>

	<form method="post" action="<?php echo esc_url( $save_url ); ?>" id="hgsd-reviews-form">
		<input type="hidden" name="action" value="hgsd_save_reviews" />
		<?php wp_nonce_field( 'hgsd_save_reviews' ); ?>

		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><?php esc_html_e( 'Source', 'hg-structured-data' ); ?></th>
				<td>
					<?php foreach ( [ 'places' => __( 'Google Places API', 'hg-structured-data' ), 'business' => __( 'Google Business Profile API (OAuth)', 'hg-structured-data' ), 'manual' => __( 'Manual entry', 'hg-structured-data' ) ] as $value => $label ) : ?>
						<label style="margin-right:16px;">
							<input type="radio" name="hgsd_reviews[provider]" value="<?php echo esc_attr( $value ); ?>" <?php checked( $s['provider'], $value ); ?> />
							<?php echo esc_html( $label ); ?>
						</label>
					<?php endforeach; ?>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Sync interval', 'hg-structured-data' ); ?></th>
				<td>
					<select name="hgsd_reviews[sync_interval]">
						<option value="hourly" <?php selected( $s['sync_interval'], 'hourly' ); ?>><?php esc_html_e( 'Hourly', 'hg-structured-data' ); ?></option>
						<option value="twicedaily" <?php selected( $s['sync_interval'], 'twicedaily' ); ?>><?php esc_html_e( 'Twice daily', 'hg-structured-data' ); ?></option>
						<option value="daily" <?php selected( $s['sync_interval'], 'daily' ); ?>><?php esc_html_e( 'Daily', 'hg-structured-data' ); ?></option>
					</select>
					<p class="description"><?php esc_html_e( 'Reviews are fetched in the background and cached, so pages stay fast.', 'hg-structured-data' ); ?></p>
				</td>
			</tr>
		</table>

		<h2><?php esc_html_e( 'Google Places API', 'hg-structured-data' ); ?></h2>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><?php esc_html_e( 'API key', 'hg-structured-data' ); ?></th>
				<td><input type="text" class="regular-text" name="hgsd_reviews[places_api_key]" value="<?php echo esc_attr( $s['places_api_key'] ); ?>" autocomplete="off" /></td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Place ID', 'hg-structured-data' ); ?></th>
				<td>
					<input type="text" class="regular-text" name="hgsd_reviews[place_id]" value="<?php echo esc_attr( $s['place_id'] ); ?>" />
					<p class="description"><?php esc_html_e( 'Returns up to 5 reviews plus the overall rating.', 'hg-structured-data' ); ?></p>
				</td>
			</tr>
		</table>

		<h2><?php esc_html_e( 'Google Business Profile API', 'hg-structured-data' ); ?></h2>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><?php esc_html_e( 'OAuth Client ID', 'hg-structured-data' ); ?></th>
				<td><input type="text" class="regular-text" name="hgsd_reviews[bp_client_id]" value="<?php echo esc_attr( $s['bp_client_id'] ); ?>" autocomplete="off" /></td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'OAuth Client Secret', 'hg-structured-data' ); ?></th>
				<td><input type="text" class="regular-text" name="hgsd_reviews[bp_client_secret]" value="<?php echo esc_attr( $s['bp_client_secret'] ); ?>" autocomplete="off" /></td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Authorized redirect URI', 'hg-structured-data' ); ?></th>
				<td><code><?php echo esc_html( $redirect_ui ); ?></code>
					<p class="description"><?php esc_html_e( 'Add this exact URI to your OAuth client in Google Cloud Console.', 'hg-structured-data' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Location resource', 'hg-structured-data' ); ?></th>
				<td>
					<input type="text" class="regular-text" name="hgsd_reviews[bp_location]" value="<?php echo esc_attr( $s['bp_location'] ); ?>" placeholder="accounts/123456/locations/789012" />
					<p class="description"><?php esc_html_e( 'The account/location path whose reviews should be fetched.', 'hg-structured-data' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Refresh token', 'hg-structured-data' ); ?></th>
				<td>
					<input type="text" class="large-text" name="hgsd_reviews[bp_refresh_token]" value="<?php echo esc_attr( $s['bp_refresh_token'] ); ?>" autocomplete="off" placeholder="<?php esc_attr_e( 'Connect below, or paste a refresh token manually', 'hg-structured-data' ); ?>" />
					<p class="description">
						<?php echo '' !== (string) $s['bp_refresh_token'] ? esc_html__( 'Connected ✓ (leave blank to keep the current token).', 'hg-structured-data' ) : esc_html__( 'Not connected.', 'hg-structured-data' ); ?>
					</p>
				</td>
			</tr>
		</table>

		<h2><?php esc_html_e( 'Manual reviews', 'hg-structured-data' ); ?></h2>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><?php esc_html_e( 'Aggregate override', 'hg-structured-data' ); ?></th>
				<td>
					<label><?php esc_html_e( 'Rating value', 'hg-structured-data' ); ?>
						<input type="number" step="0.1" min="1" max="5" name="hgsd_reviews[manual_aggregate][ratingValue]" value="<?php echo esc_attr( (string) $s['manual_aggregate']['ratingValue'] ); ?>" />
					</label>
					&nbsp;
					<label><?php esc_html_e( 'Review count', 'hg-structured-data' ); ?>
						<input type="number" min="0" name="hgsd_reviews[manual_aggregate][reviewCount]" value="<?php echo esc_attr( (string) $s['manual_aggregate']['reviewCount'] ); ?>" />
					</label>
					<p class="description"><?php esc_html_e( 'Optional. Leave blank to compute the average from the reviews below.', 'hg-structured-data' ); ?></p>
				</td>
			</tr>
		</table>

		<div id="hgsd-manual-items">
			<?php
			$items = is_array( $s['manual_items'] ) ? $s['manual_items'] : [];
			foreach ( $items as $i => $item ) :
				?>
				<div class="hgsd-row hgsd-manual-item">
					<input type="text" placeholder="<?php esc_attr_e( 'Author', 'hg-structured-data' ); ?>" name="hgsd_reviews[manual_items][<?php echo (int) $i; ?>][author]" value="<?php echo esc_attr( (string) ( $item['author'] ?? '' ) ); ?>" />
					<input type="number" min="1" max="5" placeholder="1-5" name="hgsd_reviews[manual_items][<?php echo (int) $i; ?>][rating]" value="<?php echo esc_attr( (string) ( $item['rating'] ?? 5 ) ); ?>" />
					<input type="date" name="hgsd_reviews[manual_items][<?php echo (int) $i; ?>][date]" value="<?php echo esc_attr( (string) ( $item['date'] ?? '' ) ); ?>" />
					<input type="url" placeholder="https://" name="hgsd_reviews[manual_items][<?php echo (int) $i; ?>][url]" value="<?php echo esc_attr( (string) ( $item['url'] ?? '' ) ); ?>" />
					<textarea rows="2" placeholder="<?php esc_attr_e( 'Review text', 'hg-structured-data' ); ?>" name="hgsd_reviews[manual_items][<?php echo (int) $i; ?>][text]"><?php echo esc_textarea( (string) ( $item['text'] ?? '' ) ); ?></textarea>
					<button type="button" class="button-link hgsd-remove-manual"><?php esc_html_e( 'Remove', 'hg-structured-data' ); ?></button>
				</div>
			<?php endforeach; ?>
		</div>
		<p><button type="button" class="button" id="hgsd-add-manual"><?php esc_html_e( 'Add review', 'hg-structured-data' ); ?></button></p>

		<?php submit_button( __( 'Save settings', 'hg-structured-data' ) ); ?>
	</form>

	<hr />

	<div style="display:flex; gap:12px; flex-wrap:wrap;">
		<form method="post" action="<?php echo esc_url( $save_url ); ?>">
			<input type="hidden" name="action" value="hgsd_sync_reviews" />
			<?php wp_nonce_field( 'hgsd_sync_reviews' ); ?>
			<?php submit_button( __( 'Sync now', 'hg-structured-data' ), 'secondary', 'submit', false ); ?>
		</form>

		<form method="post" action="<?php echo esc_url( $save_url ); ?>">
			<input type="hidden" name="action" value="hgsd_gbp_connect" />
			<?php wp_nonce_field( 'hgsd_gbp_connect' ); ?>
			<?php submit_button( __( 'Connect Google Business Profile', 'hg-structured-data' ), 'secondary', 'submit', false ); ?>
		</form>
	</div>

	<template id="hgsd-manual-template">
		<div class="hgsd-row hgsd-manual-item">
			<input type="text" placeholder="<?php esc_attr_e( 'Author', 'hg-structured-data' ); ?>" data-name="author" />
			<input type="number" min="1" max="5" placeholder="1-5" value="5" data-name="rating" />
			<input type="date" data-name="date" />
			<input type="url" placeholder="https://" data-name="url" />
			<textarea rows="2" placeholder="<?php esc_attr_e( 'Review text', 'hg-structured-data' ); ?>" data-name="text"></textarea>
			<button type="button" class="button-link hgsd-remove-manual"><?php esc_html_e( 'Remove', 'hg-structured-data' ); ?></button>
		</div>
	</template>

	<script>
	( function () {
		var wrap = document.getElementById( 'hgsd-manual-items' );
		var tpl = document.getElementById( 'hgsd-manual-template' );
		var idx = <?php echo (int) ( ! empty( $items ) ? max( array_keys( $items ) ) + 1 : 0 ); ?>;

		document.getElementById( 'hgsd-add-manual' ).addEventListener( 'click', function () {
			var node = tpl.content.cloneNode( true );
			node.querySelectorAll( '[data-name]' ).forEach( function ( el ) {
				el.name = 'hgsd_reviews[manual_items][' + idx + '][' + el.getAttribute( 'data-name' ) + ']';
				el.removeAttribute( 'data-name' );
			} );
			wrap.appendChild( node );
			idx++;
		} );

		wrap.addEventListener( 'click', function ( e ) {
			if ( e.target.classList.contains( 'hgsd-remove-manual' ) ) {
				e.target.closest( '.hgsd-manual-item' ).remove();
			}
		} );
	} )();
	</script>
</div>
