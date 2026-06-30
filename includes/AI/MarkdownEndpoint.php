<?php
/**
 * Serves a Markdown version of a page when ".md" is appended to its URL.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\AI;

use HelloGekko\StructuredData\Output\FrontendOutput;

defined( 'ABSPATH' ) || exit;

/**
 * Handles requests like /about.md and prints clean Markdown, optionally
 * including the structured-data facts this plugin generates for the page.
 */
final class MarkdownEndpoint {

	private FrontendOutput $frontend;

	public function __construct( FrontendOutput $frontend ) {
		$this->frontend = $frontend;
	}

	public function register_hooks(): void {
		// Run before the conflict output-buffer (template_redirect @0) so we can exit cleanly.
		add_action( 'template_redirect', [ $this, 'maybe_render' ], -10 );
		add_action( 'template_redirect', [ $this, 'maybe_negotiate' ], -9 );
		add_action( 'wp_head', [ $this, 'discovery_link' ] );
		add_action( 'send_headers', [ $this, 'send_link_header' ] );
	}

	/**
	 * Advertise the Markdown alternate via an HTTP Link header so crawlers can
	 * find it without parsing the HTML.
	 */
	public function send_link_header(): void {
		$settings = AiSettings::get();
		if ( empty( $settings['enable_markdown'] ) || ! is_singular( $settings['post_types'] ) ) {
			return;
		}
		$url = untrailingslashit( (string) get_permalink() ) . '.md';
		header( 'Link: <' . esc_url_raw( $url ) . '>; rel="alternate"; type="text/markdown"', false );
		header( 'Vary: Accept', false );
	}

	/**
	 * Content negotiation: if a client requests the normal page URL but prefers
	 * Markdown (Accept: text/markdown), serve Markdown on the canonical URL.
	 */
	public function maybe_negotiate(): void {
		$settings = AiSettings::get();
		if ( empty( $settings['enable_markdown'] ) || empty( $settings['negotiate'] ) ) {
			return;
		}
		if ( is_admin() || ! is_singular( $settings['post_types'] ) ) {
			return;
		}
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$accept = isset( $_SERVER['HTTP_ACCEPT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_ACCEPT'] ) ) : '';
		if ( ! $this->prefers_markdown( $accept ) ) {
			return;
		}

		$post = get_queried_object();
		if ( ! $post instanceof \WP_Post ) {
			return;
		}

		header( 'Content-Type: text/markdown; charset=utf-8' );
		header( 'Vary: Accept', false );
		header( 'Link: <' . esc_url_raw( (string) get_permalink( $post ) ) . '>; rel="canonical"', false );
		echo $this->page_markdown( $post, $settings ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		exit;
	}

	/**
	 * Whether the Accept header prefers Markdown over HTML.
	 */
	private function prefers_markdown( string $accept ): bool {
		if ( '' === $accept || false === stripos( $accept, 'markdown' ) ) {
			return false;
		}
		$md   = $this->accept_q( $accept, 'text/markdown' );
		$html = max( $this->accept_q( $accept, 'text/html' ), $this->accept_q( $accept, 'application/xhtml+xml' ) );
		return $md > 0 && $md > $html;
	}

	/**
	 * Parse the q-value for a media type from an Accept header (0 if absent).
	 */
	private function accept_q( string $accept, string $type ): float {
		foreach ( explode( ',', $accept ) as $part ) {
			$bits  = explode( ';', trim( $part ) );
			$media = strtolower( trim( $bits[0] ) );
			if ( $media !== $type ) {
				continue;
			}
			$q = 1.0;
			foreach ( array_slice( $bits, 1 ) as $param ) {
				if ( 0 === strpos( trim( $param ), 'q=' ) ) {
					$q = (float) substr( trim( $param ), 2 );
				}
			}
			return $q;
		}
		return 0.0;
	}

	/**
	 * Detect a ".md" request and render it.
	 */
	public function maybe_render(): void {
		$settings = AiSettings::get();
		if ( empty( $settings['enable_markdown'] ) ) {
			return;
		}

		$path = (string) wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ?? '' ), PHP_URL_PATH );
		$path = untrailingslashit( $path );
		if ( '.md' !== substr( $path, -3 ) ) {
			return;
		}

		$clean   = substr( $path, 0, -3 );
		$post_id = '' === $clean || '/' === $clean
			? (int) get_option( 'page_on_front' )
			: url_to_postid( home_url( $clean ) );

		$post = $post_id ? get_post( $post_id ) : null;

		header( 'Content-Type: text/markdown; charset=utf-8' );
		// Never index the Markdown mirror itself — avoids duplicate content.
		header( 'X-Robots-Tag: noindex, follow', true );

		if ( ! $post || 'publish' !== $post->post_status || ! in_array( $post->post_type, $settings['post_types'], true ) ) {
			status_header( 404 );
			echo "# 404 Not Found\n";
			exit;
		}

		// Point crawlers to the canonical HTML page as the original.
		header( 'Link: <' . esc_url_raw( (string) get_permalink( $post ) ) . '>; rel="canonical"', false );

		echo $this->page_markdown( $post, $settings ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		exit;
	}

	/**
	 * Add a discovery link so crawlers find the Markdown version.
	 */
	public function discovery_link(): void {
		$settings = AiSettings::get();
		if ( empty( $settings['enable_markdown'] ) || ! is_singular( $settings['post_types'] ) ) {
			return;
		}
		$url = untrailingslashit( (string) get_permalink() ) . '.md';
		printf( '<link rel="alternate" type="text/markdown" href="%s" />' . "\n", esc_url( $url ) );
	}

	/**
	 * Build the Markdown document for a post.
	 *
	 * @param array<string,mixed> $settings AI settings.
	 */
	private function page_markdown( \WP_Post $post, array $settings ): string {
		$title = wp_strip_all_tags( get_the_title( $post ) );
		$md    = '# ' . $title . "\n\n";

		$excerpt = has_excerpt( $post ) ? get_the_excerpt( $post ) : '';
		if ( '' !== $excerpt ) {
			$md .= '> ' . trim( wp_strip_all_tags( $excerpt ) ) . "\n\n";
		}

		$md .= 'URL: ' . get_permalink( $post ) . "\n\n";

		$nodes = ! empty( $settings['include_schema'] ) ? $this->frontend->nodes_for_post( $post->ID ) : [];

		if ( $nodes ) {
			$md .= "## Key facts\n\n";
			foreach ( $nodes as $node ) {
				$md .= $this->node_facts( $node );
			}
		}

		if ( ! empty( $settings['include_content'] ) ) {
			$body = MarkdownConverter::convert( $this->rendered_html( $post ) );
			if ( '' !== $body ) {
				$md .= "## Content\n\n" . $body . "\n\n";
			}
		}

		if ( $nodes ) {
			$payload = ( 1 === count( $nodes ) ) ? $nodes[0] : $nodes;
			$json    = wp_json_encode( $payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
			$md     .= "## Structured data (JSON-LD)\n\n```json\n" . $json . "\n```\n";
		}

		/**
		 * Filter the generated Markdown for a post.
		 *
		 * @param string   $md   The Markdown document.
		 * @param \WP_Post $post The post.
		 */
		return (string) apply_filters( 'hgsd_page_markdown', $md, $post );
	}

	/**
	 * Render a post's content to HTML, universally: Elementor via its own API,
	 * everything else (Gutenberg, classic, other builders) via the_content with
	 * the loop set up so shortcodes and builders render correctly.
	 */
	private function rendered_html( \WP_Post $wp_post ): string {
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

		// Default path: render the_content within a proper loop context so that
		// Gutenberg blocks, shortcodes and other builders render correctly.
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

	/**
	 * Render one schema node as a readable "key facts" block.
	 *
	 * @param array<string,mixed> $node JSON-LD node.
	 */
	private function node_facts( array $node ): string {
		$type = $node['@type'] ?? 'Thing';
		$out  = '### ' . ( is_array( $type ) ? implode( ' / ', $type ) : $type ) . "\n\n";

		foreach ( $node as $key => $value ) {
			if ( '@context' === $key || '@type' === $key ) {
				continue;
			}

			// FAQ question/answer pairs.
			if ( 'mainEntity' === $key && is_array( $value ) ) {
				foreach ( $value as $qa ) {
					if ( ! is_array( $qa ) ) {
						continue;
					}
					$q = $qa['name'] ?? '';
					$a = $qa['acceptedAnswer']['text'] ?? '';
					$out .= '- **Q: ' . wp_strip_all_tags( (string) $q ) . "**\n";
					$out .= '  ' . trim( wp_strip_all_tags( (string) $a ) ) . "\n";
				}
				continue;
			}

			$out .= '- **' . $key . ':** ' . $this->scalarize( $value ) . "\n";
		}

		return $out . "\n";
	}

	/**
	 * Flatten a JSON-LD value to a short readable string.
	 *
	 * @param mixed $value Value.
	 */
	private function scalarize( $value ): string {
		if ( is_scalar( $value ) ) {
			return (string) $value;
		}
		if ( ! is_array( $value ) ) {
			return '';
		}

		// List of values.
		if ( array_keys( $value ) === range( 0, count( $value ) - 1 ) ) {
			return implode( ', ', array_map( [ $this, 'scalarize' ], $value ) );
		}

		// Typed object — prefer a name/url/value.
		foreach ( [ 'name', 'url', 'value', 'ratingValue' ] as $pref ) {
			if ( isset( $value[ $pref ] ) && is_scalar( $value[ $pref ] ) ) {
				return (string) $value[ $pref ];
			}
		}
		return '';
	}
}
