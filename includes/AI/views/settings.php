<?php
/**
 * AI / Markdown settings screen.
 *
 * @var array<string,mixed>      $s          Current settings.
 * @var array<string,\WP_Post_Type> $post_types Public post types.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;

// phpcs:ignore WordPress.Security.NonceVerification.Recommended
$show_updated = isset( $_GET['updated'] );
?>
<div class="wrap">
	<h1><?php esc_html_e( 'AI / Markdown output', 'hg-structured-data' ); ?></h1>

	<?php if ( $show_updated ) : ?>
		<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Settings saved.', 'hg-structured-data' ); ?></p></div>
	<?php endif; ?>

	<p class="description">
		<?php esc_html_e( 'Expose a clean, AI-readable version of your content so LLMs and search engines can understand your site more easily. Adds a Markdown version of each page (append .md to its URL) and a site index at /llms.txt.', 'hg-structured-data' ); ?>
	</p>

	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<input type="hidden" name="action" value="hgsd_save_ai" />
		<?php wp_nonce_field( 'hgsd_save_ai' ); ?>

		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><?php esc_html_e( 'Per-page Markdown', 'hg-structured-data' ); ?></th>
				<td>
					<label>
						<input type="checkbox" name="hgsd_ai[enable_markdown]" value="1" <?php checked( $s['enable_markdown'] ); ?> />
						<?php esc_html_e( 'Serve a Markdown version of pages when “.md” is appended to the URL', 'hg-structured-data' ); ?>
					</label>
					<p class="description"><?php esc_html_e( 'Example: https://example.com/about.md', 'hg-structured-data' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Content negotiation', 'hg-structured-data' ); ?></th>
				<td>
					<label>
						<input type="checkbox" name="hgsd_ai[negotiate]" value="1" <?php checked( $s['negotiate'] ); ?> />
						<?php esc_html_e( 'Serve Markdown on the normal page URL to clients that ask for it (Accept: text/markdown)', 'hg-structured-data' ); ?>
					</label>
					<p class="description"><?php esc_html_e( 'The most reliable way for AI agents to get the Markdown — no .md needed. Browsers and Googlebot still get the normal HTML.', 'hg-structured-data' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'llms.txt index', 'hg-structured-data' ); ?></th>
				<td>
					<label>
						<input type="checkbox" name="hgsd_ai[enable_llms]" value="1" <?php checked( $s['enable_llms'] ); ?> />
						<?php
						printf(
							/* translators: %s: /llms.txt URL. */
							esc_html__( 'Publish a site index at %s', 'hg-structured-data' ),
							'<code>' . esc_html( home_url( '/llms.txt' ) ) . '</code>'
						);
						?>
					</label>
					<p class="description"><?php esc_html_e( 'Note: Google has said it does not use llms.txt. It is supported by parts of the AI tooling ecosystem; the Link header and content negotiation above are the reliable discovery methods.', 'hg-structured-data' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Markdown contents', 'hg-structured-data' ); ?></th>
				<td>
					<label style="display:block;margin-bottom:6px;">
						<input type="checkbox" name="hgsd_ai[include_schema]" value="1" <?php checked( $s['include_schema'] ); ?> />
						<?php esc_html_e( 'Include structured-data key facts + JSON-LD', 'hg-structured-data' ); ?>
					</label>
					<label style="display:block;">
						<input type="checkbox" name="hgsd_ai[include_content]" value="1" <?php checked( $s['include_content'] ); ?> />
						<?php esc_html_e( 'Include the page content as Markdown', 'hg-structured-data' ); ?>
					</label>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Apply to post types', 'hg-structured-data' ); ?></th>
				<td>
					<?php foreach ( $post_types as $pt ) : ?>
						<label style="display:inline-block;margin-right:14px;">
							<input type="checkbox" name="hgsd_ai[post_types][]" value="<?php echo esc_attr( $pt->name ); ?>" <?php checked( in_array( $pt->name, $s['post_types'], true ) ); ?> />
							<?php echo esc_html( $pt->labels->singular_name ); ?>
						</label>
					<?php endforeach; ?>
				</td>
			</tr>
		</table>

		<?php submit_button( __( 'Save settings', 'hg-structured-data' ) ); ?>
	</form>
</div>
