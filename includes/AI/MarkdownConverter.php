<?php
/**
 * Minimal HTML-to-Markdown converter for the AI-readable page output.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\AI;

defined( 'ABSPATH' ) || exit;

/**
 * Converts a chunk of post HTML into clean Markdown. Intentionally small: it
 * handles the elements that matter for readability and falls back to text.
 */
final class MarkdownConverter {

	/**
	 * Convert an HTML string to Markdown.
	 */
	public static function convert( string $html ): string {
		$html = trim( $html );
		if ( '' === $html ) {
			return '';
		}

		// Drop scripts, styles and SVGs up front.
		$html = (string) preg_replace( '#<(script|style|svg|noscript)\b[^>]*>.*?</\1>#is', '', $html );

		if ( ! class_exists( '\DOMDocument' ) ) {
			return trim( wp_strip_all_tags( $html ) );
		}

		$dom = new \DOMDocument();
		libxml_use_internal_errors( true );
		$dom->loadHTML( '<?xml encoding="utf-8"?><div>' . $html . '</div>', LIBXML_NOERROR | LIBXML_NOWARNING );
		libxml_clear_errors();

		$body = $dom->getElementsByTagName( 'div' )->item( 0 );
		$md   = $body ? self::walk( $body ) : '';

		// Collapse excessive blank lines.
		$md = (string) preg_replace( "/\n{3,}/", "\n\n", $md );
		return trim( $md );
	}

	/**
	 * Recursively walk DOM nodes producing Markdown.
	 */
	private static function walk( \DOMNode $node ): string {
		$out = '';

		foreach ( $node->childNodes as $child ) {
			if ( XML_TEXT_NODE === $child->nodeType ) {
				$out .= preg_replace( '/\s+/', ' ', $child->textContent );
				continue;
			}
			if ( XML_ELEMENT_NODE !== $child->nodeType ) {
				continue;
			}

			$tag   = strtolower( $child->nodeName );
			$inner = self::walk( $child );
			$text  = trim( $inner );

			switch ( $tag ) {
				case 'h1':
				case 'h2':
				case 'h3':
				case 'h4':
				case 'h5':
				case 'h6':
					$level = (int) substr( $tag, 1 );
					if ( '' !== $text ) {
						$out .= "\n\n" . str_repeat( '#', $level ) . ' ' . $text . "\n\n";
					}
					break;

				case 'p':
					if ( '' !== $text ) {
						$out .= "\n\n" . $text . "\n\n";
					}
					break;

				case 'br':
					$out .= "  \n";
					break;

				case 'strong':
				case 'b':
					if ( '' !== $text ) {
						$out .= '**' . $text . '**';
					}
					break;

				case 'em':
				case 'i':
					if ( '' !== $text ) {
						$out .= '_' . $text . '_';
					}
					break;

				case 'a':
					$href = $child->getAttribute( 'href' );
					$out .= ( $href && '' !== $text ) ? '[' . $text . '](' . $href . ')' : $text;
					break;

				case 'ul':
				case 'ol':
					$out .= "\n" . self::list_items( $child, $tag ) . "\n";
					break;

				case 'li':
					$out .= $inner; // Handled by list_items, but keep text if orphaned.
					break;

				case 'blockquote':
					if ( '' !== $text ) {
						$out .= "\n\n> " . str_replace( "\n", "\n> ", $text ) . "\n\n";
					}
					break;

				case 'h7': // Non-standard guard.
				default:
					$out .= $inner;
					break;
			}
		}

		return $out;
	}

	/**
	 * Render <ul>/<ol> children as a Markdown list.
	 */
	private static function list_items( \DOMNode $list, string $tag ): string {
		$out = '';
		$i   = 1;
		foreach ( $list->childNodes as $li ) {
			if ( XML_ELEMENT_NODE !== $li->nodeType || 'li' !== strtolower( $li->nodeName ) ) {
				continue;
			}
			$text = trim( self::walk( $li ) );
			if ( '' === $text ) {
				continue;
			}
			$marker = ( 'ol' === $tag ) ? ( $i . '.' ) : '-';
			$out   .= $marker . ' ' . $text . "\n";
			$i++;
		}
		return $out;
	}
}
