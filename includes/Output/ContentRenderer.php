<?php
/**
 * Universal post-content renderer, shared by the AI Markdown output and the
 * link indexer.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Output;

defined( 'ABSPATH' ) || exit;

/**
 * Renders a post's content to HTML regardless of how it was built: Elementor
 * via its own API, everything else (Gutenberg, classic, other builders) via
 * the_content with the loop set up so shortcodes render correctly.
 */
final class ContentRenderer {

	/**
	 * Render a post's content to HTML.
	 */
	public static function render( \WP_Post $wp_post ): string {
		// Elementor stores its content separately and renders via its own engine.
		if ( did_action( 'elementor/loaded' ) && class_exists( '\Elementor\Plugin' ) ) {
			$elementor = \Elementor\Plugin::$instance;
			if ( isset( $elementor->documents ) ) {
				$document = $elementor->documents->get( $wp_post->ID );
				if ( $document && $document->is_built_with_elementor() ) {
					$html = $elementor->frontend->get_builder_content_for_display( $wp_post->ID, false );
					if ( '' !== trim( (string) $html ) ) {
						return (string) $html;
					}
				}
			}
		}

		// Default path: render the_content within a proper loop context.
		global $post;
		$previous = $post;
		$post     = $wp_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		setup_postdata( $post );

		$html = apply_filters( 'the_content', $wp_post->post_content );

		$post = $previous; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		if ( $previous instanceof \WP_Post ) {
			setup_postdata( $previous );
		} else {
			wp_reset_postdata();
		}

		return (string) $html;
	}
}
