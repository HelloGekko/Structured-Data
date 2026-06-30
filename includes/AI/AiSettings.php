<?php
/**
 * Settings for the AI-readable output (per-page .md and /llms.txt).
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\AI;

defined( 'ABSPATH' ) || exit;

/**
 * Stores and renders the AI / Markdown settings.
 */
final class AiSettings {

	public const OPTION = 'hgsd_ai_settings';

	/**
	 * Merged settings with defaults.
	 *
	 * @return array{enable_markdown:bool,enable_llms:bool,include_content:bool,include_schema:bool,post_types:array<int,string>}
	 */
	public static function get(): array {
		$stored = get_option( self::OPTION, [] );
		$stored = is_array( $stored ) ? $stored : [];

		return [
			'enable_markdown' => ! array_key_exists( 'enable_markdown', $stored ) || ! empty( $stored['enable_markdown'] ),
			'enable_llms'     => ! array_key_exists( 'enable_llms', $stored ) || ! empty( $stored['enable_llms'] ),
			'include_content' => ! array_key_exists( 'include_content', $stored ) || ! empty( $stored['include_content'] ),
			'include_schema'  => ! array_key_exists( 'include_schema', $stored ) || ! empty( $stored['include_schema'] ),
			'post_types'      => is_array( $stored['post_types'] ?? null ) ? $stored['post_types'] : [ 'post', 'page' ],
		];
	}

	public function register_hooks(): void {
		add_action( 'admin_menu', [ $this, 'menu' ] );
		add_action( 'admin_post_hgsd_save_ai', [ $this, 'save' ] );
	}

	public function menu(): void {
		add_submenu_page(
			'edit.php?post_type=' . HGSD_CPT,
			__( 'AI / Markdown', 'hg-structured-data' ),
			__( 'AI / Markdown', 'hg-structured-data' ),
			'manage_options',
			'hgsd-ai',
			[ $this, 'render' ]
		);
	}

	private function settings_url( array $args = [] ): string {
		return add_query_arg(
			array_merge( [ 'post_type' => HGSD_CPT, 'page' => 'hgsd-ai' ], $args ),
			admin_url( 'edit.php' )
		);
	}

	public function render(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$s          = self::get();
		$post_types = get_post_types( [ 'public' => true ], 'objects' );
		require HGSD_PATH . 'includes/AI/views/settings.php';
	}

	public function save(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Not allowed.', 'hg-structured-data' ) );
		}
		check_admin_referer( 'hgsd_save_ai' );

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- verified above.
		$in = isset( $_POST['hgsd_ai'] ) ? wp_unslash( $_POST['hgsd_ai'] ) : [];
		$in = is_array( $in ) ? $in : [];

		$types = [];
		foreach ( (array) ( $in['post_types'] ?? [] ) as $slug ) {
			$types[] = sanitize_key( (string) $slug );
		}

		update_option(
			self::OPTION,
			[
				'enable_markdown' => ! empty( $in['enable_markdown'] ),
				'enable_llms'     => ! empty( $in['enable_llms'] ),
				'include_content' => ! empty( $in['include_content'] ),
				'include_schema'  => ! empty( $in['include_schema'] ),
				'post_types'      => $types,
			]
		);

		// The llms.txt index is cached; clear it on save.
		delete_transient( LlmsTxt::TRANSIENT );

		wp_safe_redirect( $this->settings_url( [ 'updated' => '1' ] ) );
		exit;
	}
}
