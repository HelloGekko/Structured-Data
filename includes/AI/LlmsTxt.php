<?php
/**
 * Serves an /llms.txt site index for LLMs.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\AI;

defined( 'ABSPATH' ) || exit;

/**
 * Publishes a Markdown site index at /llms.txt (per the emerging llms.txt
 * convention), listing key pages with links and short descriptions.
 */
final class LlmsTxt {

	public const TRANSIENT = 'hgsd_llms_txt';

	public function register_hooks(): void {
		add_action( 'template_redirect', [ $this, 'maybe_render' ], -10 );
		// Keep the cached index fresh.
		add_action( 'save_post', [ $this, 'flush' ] );
		add_action( 'deleted_post', [ $this, 'flush' ] );
	}

	public function flush(): void {
		delete_transient( self::TRANSIENT );
	}

	/**
	 * Render /llms.txt when requested.
	 */
	public function maybe_render(): void {
		$settings = AiSettings::get();
		if ( empty( $settings['enable_llms'] ) ) {
			return;
		}

		$path = (string) wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ?? '' ), PHP_URL_PATH );
		if ( '/llms.txt' !== untrailingslashit( $path ) ) {
			return;
		}

		$body = get_transient( self::TRANSIENT );
		if ( false === $body || ! is_string( $body ) ) {
			$body = $this->build( $settings );
			set_transient( self::TRANSIENT, $body, DAY_IN_SECONDS );
		}

		header( 'Content-Type: text/markdown; charset=utf-8' );
		header( 'X-Robots-Tag: noindex, follow', true );
		echo $body; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		exit;
	}

	/**
	 * Build the llms.txt document.
	 *
	 * @param array<string,mixed> $settings AI settings.
	 */
	private function build( array $settings ): string {
		$name = get_bloginfo( 'name' );
		$desc = get_bloginfo( 'description' );

		$md = '# ' . wp_strip_all_tags( $name ) . "\n\n";
		if ( '' !== $desc ) {
			$md .= '> ' . wp_strip_all_tags( $desc ) . "\n\n";
		}
		$md .= 'Home: ' . home_url( '/' ) . "\n\n";

		$types = ! empty( $settings['post_types'] ) ? $settings['post_types'] : [ 'page', 'post' ];
		$md_on = ! empty( $settings['enable_markdown'] );

		foreach ( $types as $type ) {
			$obj = get_post_type_object( $type );
			if ( ! $obj ) {
				continue;
			}

			$posts = get_posts(
				[
					'post_type'   => $type,
					'post_status' => 'publish',
					'numberposts' => 200,
					'orderby'     => 'menu_order title',
					'order'       => 'ASC',
				]
			);
			if ( ! $posts ) {
				continue;
			}

			$md .= '## ' . wp_strip_all_tags( $obj->labels->name ) . "\n\n";
			foreach ( $posts as $post ) {
				$url   = get_permalink( $post );
				$title = wp_strip_all_tags( get_the_title( $post ) );
				$line  = '- [' . $title . '](' . $url . ')';

				$excerpt = has_excerpt( $post ) ? wp_strip_all_tags( get_the_excerpt( $post ) ) : '';
				if ( '' !== $excerpt ) {
					$line .= ': ' . wp_trim_words( $excerpt, 25, '…' );
				}
				$md .= $line . "\n";

				if ( $md_on ) {
					$md .= '  - Markdown: ' . untrailingslashit( (string) $url ) . ".md\n";
				}
			}
			$md .= "\n";
		}

		/**
		 * Filter the generated llms.txt content.
		 *
		 * @param string $md The document.
		 */
		return (string) apply_filters( 'hgsd_llms_txt', $md );
	}
}
