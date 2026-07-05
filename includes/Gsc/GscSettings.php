<?php
/**
 * Settings screen + OAuth flow for the Search Console connection.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Gsc;

defined( 'ABSPATH' ) || exit;

/**
 * Renders and persists the GSC settings and handles the Google OAuth
 * connect/callback flow (same pattern as the Business Profile connection).
 */
final class GscSettings {

	// Read-only Search Console access (URL inspection) plus the Indexing API
	// scope so the same connection can submit URLs for (re)crawling.
	private const SCOPE    = 'https://www.googleapis.com/auth/webmasters.readonly https://www.googleapis.com/auth/indexing';
	private const AUTH_URL = 'https://accounts.google.com/o/oauth2/v2/auth';
	private const TOKEN_URL = 'https://oauth2.googleapis.com/token';

	private GscClient $client;

	public function __construct( GscClient $client ) {
		$this->client = $client;
	}

	public function register_hooks(): void {
		add_action( 'admin_menu', [ $this, 'menu' ] );
		add_action( 'admin_post_hgsd_save_gsc', [ $this, 'save' ] );
		add_action( 'admin_post_hgsd_gsc_connect', [ $this, 'oauth_connect' ] );
		add_action( 'admin_post_hgsd_gsc_callback', [ $this, 'oauth_callback' ] );
	}

	public function menu(): void {
		add_submenu_page(
			'edit.php?post_type=' . HGSD_CPT,
			__( 'Search Console', 'hg-structured-data' ),
			__( 'Search Console', 'hg-structured-data' ),
			'manage_options',
			'hgsd-gsc',
			[ $this, 'render' ]
		);
	}

	private function redirect_uri(): string {
		return admin_url( 'admin-post.php?action=hgsd_gsc_callback' );
	}

	private function settings_url( array $args = [] ): string {
		return add_query_arg(
			array_merge( [ 'post_type' => HGSD_CPT, 'page' => 'hgsd-gsc' ], $args ),
			admin_url( 'edit.php' )
		);
	}

	public function render(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$s          = $this->client->settings();
		$configured = $this->client->configured();
		$redirect   = $this->redirect_uri();
		require HGSD_PATH . 'includes/Gsc/views/settings.php';
	}

	public function save(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Not allowed.', 'hg-structured-data' ) );
		}
		check_admin_referer( 'hgsd_save_gsc' );

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- verified above.
		$in       = isset( $_POST['hgsd_gsc'] ) ? wp_unslash( $_POST['hgsd_gsc'] ) : [];
		$in       = is_array( $in ) ? $in : [];
		$existing = $this->client->settings();

		update_option(
			GscClient::OPTION,
			[
				'client_id'     => sanitize_text_field( (string) ( $in['client_id'] ?? '' ) ),
				'client_secret' => sanitize_text_field( (string) ( $in['client_secret'] ?? '' ) ),
				'refresh_token' => '' !== (string) ( $in['refresh_token'] ?? '' )
					? sanitize_text_field( (string) $in['refresh_token'] )
					: $existing['refresh_token'],
				'property'      => sanitize_text_field( (string) ( $in['property'] ?? '' ) ),
				'batch'         => ! empty( $in['batch'] ),
			]
		);

		delete_transient( GscClient::TOKEN_CACHE );
		$this->client->sync_schedule();

		wp_safe_redirect( $this->settings_url( [ 'updated' => '1' ] ) );
		exit;
	}

	public function oauth_connect(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Not allowed.', 'hg-structured-data' ) );
		}
		check_admin_referer( 'hgsd_gsc_connect' );

		$s = $this->client->settings();
		if ( '' === $s['client_id'] ) {
			wp_safe_redirect( $this->settings_url( [ 'error' => rawurlencode( __( 'Add your OAuth Client ID and Secret first.', 'hg-structured-data' ) ) ] ) );
			exit;
		}

		$url = add_query_arg(
			[
				'client_id'     => rawurlencode( $s['client_id'] ),
				'redirect_uri'  => rawurlencode( $this->redirect_uri() ),
				'response_type' => 'code',
				'scope'         => rawurlencode( self::SCOPE ),
				'access_type'   => 'offline',
				'prompt'        => 'consent',
				'state'         => rawurlencode( wp_create_nonce( 'hgsd_gsc_state' ) ),
			],
			self::AUTH_URL
		);

		wp_redirect( $url ); // phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect -- external OAuth endpoint.
		exit;
	}

	public function oauth_callback(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Not allowed.', 'hg-structured-data' ) );
		}

		$state = isset( $_GET['state'] ) ? sanitize_text_field( wp_unslash( (string) $_GET['state'] ) ) : '';
		if ( ! wp_verify_nonce( $state, 'hgsd_gsc_state' ) ) {
			wp_safe_redirect( $this->settings_url( [ 'error' => rawurlencode( __( 'Invalid OAuth state.', 'hg-structured-data' ) ) ] ) );
			exit;
		}

		$code = isset( $_GET['code'] ) ? sanitize_text_field( wp_unslash( (string) $_GET['code'] ) ) : '';
		if ( '' === $code ) {
			$err = isset( $_GET['error'] ) ? sanitize_text_field( wp_unslash( (string) $_GET['error'] ) ) : __( 'No authorization code returned.', 'hg-structured-data' );
			wp_safe_redirect( $this->settings_url( [ 'error' => rawurlencode( $err ) ] ) );
			exit;
		}

		$s        = $this->client->settings();
		$response = wp_remote_post(
			self::TOKEN_URL,
			[
				'timeout' => 15,
				'body'    => [
					'code'          => $code,
					'client_id'     => $s['client_id'],
					'client_secret' => $s['client_secret'],
					'redirect_uri'  => $this->redirect_uri(),
					'grant_type'    => 'authorization_code',
				],
			]
		);

		if ( is_wp_error( $response ) ) {
			wp_safe_redirect( $this->settings_url( [ 'error' => rawurlencode( $response->get_error_message() ) ] ) );
			exit;
		}

		$body = json_decode( (string) wp_remote_retrieve_body( $response ), true );
		if ( empty( $body['refresh_token'] ) ) {
			$message = $body['error_description'] ?? __( 'Google did not return a refresh token. Remove the app from your Google account and try again.', 'hg-structured-data' );
			wp_safe_redirect( $this->settings_url( [ 'error' => rawurlencode( (string) $message ) ] ) );
			exit;
		}

		$s['refresh_token'] = (string) $body['refresh_token'];
		update_option( GscClient::OPTION, $s );
		$this->client->sync_schedule();

		wp_safe_redirect( $this->settings_url( [ 'connected' => '1' ] ) );
		exit;
	}
}
