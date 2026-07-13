<?php
/**
 * Settings screen for the automatic BreadcrumbList.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Output;

use HelloGekko\StructuredData\ContentTypes;

defined( 'ABSPATH' ) || exit;

/**
 * Renders and persists the breadcrumb settings.
 */
final class BreadcrumbSettings {

	public function register_hooks(): void {
		add_action( 'admin_menu', [ $this, 'menu' ] );
		add_action( 'admin_post_hgsd_save_breadcrumbs', [ $this, 'save' ] );
	}

	public function menu(): void {
		add_submenu_page(
			'edit.php?post_type=' . HGSD_CPT,
			__( 'Breadcrumbs', 'hg-structured-data' ),
			__( 'Breadcrumbs', 'hg-structured-data' ),
			'manage_options',
			'hgsd-breadcrumbs',
			[ $this, 'render' ]
		);
	}

	private function settings_url( array $args = [] ): string {
		return add_query_arg(
			array_merge( [ 'post_type' => HGSD_CPT, 'page' => 'hgsd-breadcrumbs' ], $args ),
			admin_url( 'edit.php' )
		);
	}

	public function save(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Not allowed.', 'hg-structured-data' ) );
		}
		check_admin_referer( 'hgsd_save_breadcrumbs' );

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- verified above.
		$in = isset( $_POST['hgsd_breadcrumbs'] ) ? wp_unslash( $_POST['hgsd_breadcrumbs'] ) : [];
		$in = is_array( $in ) ? $in : [];

		$types = isset( $in['post_types'] ) && is_array( $in['post_types'] )
			? array_values( array_map( 'sanitize_key', $in['post_types'] ) )
			: [];

		update_option(
			Breadcrumbs::OPTION,
			[
				'enabled'    => ! empty( $in['enabled'] ),
				'home_label' => sanitize_text_field( (string) ( $in['home_label'] ?? 'Home' ) ),
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

		$s          = Breadcrumbs::settings();
		$post_types = get_post_types( [ 'public' => true ], 'objects' );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Breadcrumbs', 'hg-structured-data' ); ?></h1>
			<p class="description">
				<?php esc_html_e( 'Output an automatic BreadcrumbList (Home → parent pages or primary category → this page) so search engines and AI assistants understand your site structure. Useful when this plugin is your only source of structured data.', 'hg-structured-data' ); ?>
			</p>

			<?php if ( isset( $_GET['updated'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
				<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Settings saved.', 'hg-structured-data' ); ?></p></div>
			<?php endif; ?>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="hgsd_save_breadcrumbs" />
				<?php wp_nonce_field( 'hgsd_save_breadcrumbs' ); ?>

				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><?php esc_html_e( 'Enable breadcrumbs', 'hg-structured-data' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="hgsd_breadcrumbs[enabled]" value="1" <?php checked( $s['enabled'] ); ?> />
								<?php esc_html_e( 'Output BreadcrumbList JSON-LD on the selected post types.', 'hg-structured-data' ); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="hgsd-bc-home"><?php esc_html_e( 'Home label', 'hg-structured-data' ); ?></label></th>
						<td>
							<input type="text" id="hgsd-bc-home" name="hgsd_breadcrumbs[home_label]" value="<?php echo esc_attr( $s['home_label'] ); ?>" class="regular-text" />
							<p class="description"><?php esc_html_e( 'The name of the first crumb linking to your homepage.', 'hg-structured-data' ); ?></p>
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
										<input type="checkbox" name="hgsd_breadcrumbs[post_types][]" value="<?php echo esc_attr( $type->name ); ?>" <?php checked( in_array( $type->name, $s['post_types'], true ) ); ?> />
										<?php echo esc_html( $type->labels->name ); ?>
									</label>
								<?php endforeach; ?>
							</fieldset>
						</td>
					</tr>
				</table>

				<?php submit_button( __( 'Save settings', 'hg-structured-data' ) ); ?>
			</form>

			<p class="description">
				<?php esc_html_e( 'Tip: intermediate crumbs come from a page\'s parent pages (or a post\'s primary category). If a level is missing, set the correct parent page — or use the hgsd_breadcrumb_items filter for a custom trail.', 'hg-structured-data' ); ?>
			</p>
		</div>
		<?php
	}
}
