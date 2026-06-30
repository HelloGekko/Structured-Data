<?php
/**
 * Settings screen for overruling other structured-data plugins.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Compat;

defined( 'ABSPATH' ) || exit;

/**
 * Renders and persists the conflict / overrule settings.
 */
final class ConflictSettings {

	private ConflictManager $manager;

	public function __construct( ConflictManager $manager ) {
		$this->manager = $manager;
	}

	public function register_hooks(): void {
		add_action( 'admin_menu', [ $this, 'menu' ] );
		add_action( 'admin_post_hgsd_save_conflicts', [ $this, 'save' ] );
	}

	public function menu(): void {
		add_submenu_page(
			'edit.php?post_type=' . HGSD_CPT,
			__( 'Conflicts', 'hg-structured-data' ),
			__( 'Conflicts', 'hg-structured-data' ),
			'manage_options',
			'hgsd-conflicts',
			[ $this, 'render' ]
		);
	}

	private function settings_url( array $args = [] ): string {
		return add_query_arg(
			array_merge( [ 'post_type' => HGSD_CPT, 'page' => 'hgsd-conflicts' ], $args ),
			admin_url( 'edit.php' )
		);
	}

	public function render(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$manager      = $this->manager;
		$settings     = $manager->settings();
		$integrations = $manager->integrations();
		$detected     = $manager->detected();
		require HGSD_PATH . 'includes/Compat/views/settings.php';
	}

	public function save(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Not allowed.', 'hg-structured-data' ) );
		}
		check_admin_referer( 'hgsd_save_conflicts' );

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- verified above.
		$in = isset( $_POST['hgsd_conflicts'] ) ? wp_unslash( $_POST['hgsd_conflicts'] ) : [];
		$in = is_array( $in ) ? $in : [];

		$suppress = [];
		foreach ( array_keys( $this->manager->integrations() ) as $key ) {
			$suppress[ $key ] = ! empty( $in['suppress'][ $key ] );
		}

		$mode = in_array( $in['mode'] ?? '', [ 'off', 'dedupe', 'all' ], true ) ? $in['mode'] : 'off';

		update_option(
			ConflictManager::OPTION,
			[
				'suppress' => $suppress,
				'mode'     => $mode,
			]
		);

		wp_safe_redirect( $this->settings_url( [ 'updated' => '1' ] ) );
		exit;
	}
}
