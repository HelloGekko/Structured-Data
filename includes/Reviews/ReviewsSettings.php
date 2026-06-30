<?php
/**
 * Settings screen for the reviews integration.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Reviews;

defined( 'ABSPATH' ) || exit;

/**
 * Renders and persists the reviews settings, handles "sync now" and the Google
 * Business Profile OAuth connect/callback flow.
 */
final class ReviewsSettings {

	private const SCOPE       = 'https://www.googleapis.com/auth/business.manage';
	private const AUTH_URL    = 'https://accounts.google.com/o/oauth2/v2/auth';
	private const TOKEN_URL   = 'https://oauth2.googleapis.com/token';

	private ReviewsManager $manager;
	private string $hook = '';

	public function __construct( ReviewsManager $manager ) {
		$this->manager = $manager;
	}

	public function register_hooks(): void {
		add_action( 'admin_menu', [ $this, 'menu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue' ] );
		add_action( 'admin_post_hgsd_save_reviews', [ $this, 'save' ] );
		add_action( 'admin_post_hgsd_sync_reviews', [ $this, 'sync_now' ] );
		add_action( 'admin_post_hgsd_gbp_connect', [ $this, 'oauth_connect' ] );
		add_action( 'admin_post_hgsd_gbp_callback', [ $this, 'oauth_callback' ] );
	}

	public function menu(): void {
		$this->hook = (string) add_submenu_page(
			'edit.php?post_type=' . HGSD_CPT,
			__( 'Reviews', 'hg-structured-data' ),
			__( 'Reviews', 'hg-structured-data' ),
			'manage_options',
			'hgsd-reviews',
			[ $this, 'render' ]
		);
	}

	/**
	 * Load the admin styles on the reviews settings screen.
	 *
	 * @param string $hook Current admin page hook.
	 */
	public function enqueue( string $hook ): void {
		if ( $hook === $this->hook ) {
			wp_enqueue_style( 'hgsd-admin', HGSD_URL . 'assets/css/admin.css', [], HGSD_VERSION );
		}
	}

	/**
	 * The admin-post.php redirect URI used for the OAuth callback.
	 */
	private function redirect_uri(): string {
		return admin_url( 'admin-post.php?action=hgsd_gbp_callback' );
	}

	private function settings_url( array $args = [] ): string {
		return add_query_arg(
			array_merge( [ 'post_type' => HGSD_CPT, 'page' => 'hgsd-reviews' ], $args ),
			admin_url( 'edit.php' )
		);
	}

	/**
	 * Render the settings page.
	 */
	public function render(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$s    = $this->manager->settings();
		$data = $this->manager->data();
		require HGSD_PATH . 'includes/Reviews/views/settings.php';
	}

	/**
	 * Persist the submitted settings.
	 */
	public function save(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Not allowed.', 'hg-structured-data' ) );
		}
		check_admin_referer( 'hgsd_save_reviews' );

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- verified above.
		$in       = isset( $_POST['hgsd_reviews'] ) ? wp_unslash( $_POST['hgsd_reviews'] ) : [];
		$in       = is_array( $in ) ? $in : [];
		$existing = $this->manager->settings();

		$clean = [
			'provider'         => in_array( $in['provider'] ?? '', [ 'places', 'business', 'manual' ], true ) ? $in['provider'] : 'manual',
			'places_api_key'   => sanitize_text_field( (string) ( $in['places_api_key'] ?? '' ) ),
			'place_id'         => sanitize_text_field( (string) ( $in['place_id'] ?? '' ) ),
			'bp_client_id'     => sanitize_text_field( (string) ( $in['bp_client_id'] ?? '' ) ),
			'bp_client_secret' => sanitize_text_field( (string) ( $in['bp_client_secret'] ?? '' ) ),
			// Allow pasting a refresh token by hand, but keep the OAuth-obtained one if left blank.
			'bp_refresh_token' => '' !== (string) ( $in['bp_refresh_token'] ?? '' )
				? sanitize_text_field( (string) $in['bp_refresh_token'] )
				: (string) $existing['bp_refresh_token'],
			'bp_location'      => sanitize_text_field( (string) ( $in['bp_location'] ?? '' ) ),
			'sync_interval'    => in_array( $in['sync_interval'] ?? '', [ 'hourly', 'twicedaily', 'daily' ], true ) ? $in['sync_interval'] : 'hourly',
			'manual_aggregate' => [
				'ratingValue' => '' === ( $in['manual_aggregate']['ratingValue'] ?? '' ) ? '' : (string) (float) $in['manual_aggregate']['ratingValue'],
				'reviewCount' => '' === ( $in['manual_aggregate']['reviewCount'] ?? '' ) ? '' : (string) (int) $in['manual_aggregate']['reviewCount'],
			],
			'manual_items'     => $this->sanitize_manual_items( $in['manual_items'] ?? [] ),
		];

		update_option( ReviewsManager::OPTION_SETTINGS, $clean );

		wp_safe_redirect( $this->settings_url( [ 'updated' => '1' ] ) );
		exit;
	}

	/**
	 * @param mixed $raw Raw manual items.
	 * @return array<int,array<string,mixed>>
	 */
	private function sanitize_manual_items( $raw ): array {
		$raw    = is_array( $raw ) ? $raw : [];
		$result = [];
		foreach ( $raw as $item ) {
			if ( ! is_array( $item ) ) {
				continue;
			}
			$author = sanitize_text_field( (string) ( $item['author'] ?? '' ) );
			$text   = sanitize_textarea_field( (string) ( $item['text'] ?? '' ) );
			if ( '' === $author && '' === $text ) {
				continue;
			}
			$result[] = [
				'author' => $author,
				'rating' => max( 1, min( 5, (int) ( $item['rating'] ?? 5 ) ) ),
				'text'   => $text,
				'date'   => sanitize_text_field( (string) ( $item['date'] ?? '' ) ),
				'url'    => esc_url_raw( (string) ( $item['url'] ?? '' ) ),
			];
		}
		return $result;
	}

	/**
	 * Trigger an immediate sync.
	 */
	public function sync_now(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Not allowed.', 'hg-structured-data' ) );
		}
		check_admin_referer( 'hgsd_sync_reviews' );

		$result = $this->manager->sync();
		$args   = is_wp_error( $result )
			? [ 'synced' => '0', 'error' => rawurlencode( $result->get_error_message() ) ]
			: [ 'synced' => '1' ];

		wp_safe_redirect( $this->settings_url( $args ) );
		exit;
	}

	/**
	 * Kick off the Google OAuth consent flow.
	 */
	public function oauth_connect(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Not allowed.', 'hg-structured-data' ) );
		}
		check_admin_referer( 'hgsd_gbp_connect' );

		$s = $this->manager->settings();
		if ( '' === (string) $s['bp_client_id'] ) {
			wp_safe_redirect( $this->settings_url( [ 'error' => rawurlencode( __( 'Add your OAuth Client ID and Secret first.', 'hg-structured-data' ) ) ] ) );
			exit;
		}

		$url = add_query_arg(
			[
				'client_id'     => rawurlencode( (string) $s['bp_client_id'] ),
				'redirect_uri'  => rawurlencode( $this->redirect_uri() ),
				'response_type' => 'code',
				'scope'         => rawurlencode( self::SCOPE ),
				'access_type'   => 'offline',
				'prompt'        => 'consent',
				'state'         => rawurlencode( wp_create_nonce( 'hgsd_gbp_state' ) ),
			],
			self::AUTH_URL
		);

		wp_redirect( $url ); // phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect -- external OAuth endpoint.
		exit;
	}

	/**
	 * Handle the OAuth callback and store the refresh token.
	 */
	public function oauth_callback(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Not allowed.', 'hg-structured-data' ) );
		}

		$state = isset( $_GET['state'] ) ? sanitize_text_field( wp_unslash( (string) $_GET['state'] ) ) : '';
		if ( ! wp_verify_nonce( $state, 'hgsd_gbp_state' ) ) {
			wp_safe_redirect( $this->settings_url( [ 'error' => rawurlencode( __( 'Invalid OAuth state.', 'hg-structured-data' ) ) ] ) );
			exit;
		}

		$code = isset( $_GET['code'] ) ? sanitize_text_field( wp_unslash( (string) $_GET['code'] ) ) : '';
		if ( '' === $code ) {
			$err = isset( $_GET['error'] ) ? sanitize_text_field( wp_unslash( (string) $_GET['error'] ) ) : __( 'No authorization code returned.', 'hg-structured-data' );
			wp_safe_redirect( $this->settings_url( [ 'error' => rawurlencode( $err ) ] ) );
			exit;
		}

		$s        = $this->manager->settings();
		$response = wp_remote_post(
			self::TOKEN_URL,
			[
				'timeout' => 15,
				'body'    => [
					'code'          => $code,
					'client_id'     => (string) $s['bp_client_id'],
					'client_secret' => (string) $s['bp_client_secret'],
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

		$s['bp_refresh_token'] = (string) $body['refresh_token'];
		update_option( ReviewsManager::OPTION_SETTINGS, $s );

		wp_safe_redirect( $this->settings_url( [ 'connected' => '1' ] ) );
		exit;
	}
}
