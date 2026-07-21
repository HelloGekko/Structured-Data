<?php
/**
 * AI-readability audit.
 *
 * AI agents (and Google's AI) read a page through its accessibility tree — the
 * semantic structure of the HTML, not the visual layout. This analyses that
 * structure and flags the things that make a page hard for a machine to parse:
 * images without alt text, non-descriptive link text, a broken heading order
 * and (for a whole page) a missing or duplicated H1.
 *
 * Deliberately scoped to machine parseability only — it is not a WCAG
 * compliance checker or a content-readability score.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\AI;

defined( 'ABSPATH' ) || exit;

/**
 * Analyses an HTML fragment or page for machine-parseability problems.
 */
final class ReadabilityAudit {

	/**
	 * Link texts that carry no meaning out of context (NL + EN).
	 *
	 * @var array<int,string>
	 */
	private const VAGUE_ANCHORS = [
		'lees meer', 'lees verder', 'meer lezen', 'meer info', 'meer informatie', 'meer', 'hier', 'klik hier',
		'klik', 'bekijk', 'ontdek', 'download', 'link', 'deze pagina', 'ga naar',
		'read more', 'more', 'learn more', 'here', 'click here', 'click', 'this', 'this page', 'go', 'view', 'see more',
	];

	/**
	 * Audit an HTML string.
	 *
	 * @param string $html      HTML fragment (content) or full document.
	 * @param bool   $full_page Whether $html is a full page (enables H1 checks).
	 * @return array<string,mixed> Compact issue map (empty when clean).
	 */
	public static function analyze( string $html, bool $full_page = false ): array {
		if ( '' === trim( $html ) || ! class_exists( '\DOMDocument' ) ) {
			return [];
		}

		$dom = new \DOMDocument();
		libxml_use_internal_errors( true );
		$dom->loadHTML( '<?xml encoding="utf-8"?><div>' . $html . '</div>', LIBXML_NOERROR | LIBXML_NOWARNING );
		libxml_clear_errors();

		$issues = [];

		self::audit_images( $dom, $issues );
		self::audit_links( $dom, $issues );
		self::audit_headings( $dom, $issues, $full_page );

		return $issues;
	}

	/**
	 * Images that carry no text alternative a machine can read.
	 *
	 * @param \DOMDocument         $dom    Parsed document.
	 * @param array<string,mixed>  $issues Issue map (by reference).
	 */
	private static function audit_images( \DOMDocument $dom, array &$issues ): void {
		$missing = 0;
		foreach ( $dom->getElementsByTagName( 'img' ) as $img ) {
			// Decorative images are intentionally hidden from the a11y tree.
			if ( 'presentation' === $img->getAttribute( 'role' ) || 'true' === $img->getAttribute( 'aria-hidden' ) ) {
				continue;
			}
			$alt = trim( (string) $img->getAttribute( 'alt' ) );
			if ( '' === $alt && ! $img->hasAttribute( 'aria-label' ) && ! $img->hasAttribute( 'aria-labelledby' ) ) {
				++$missing;
			}
		}
		if ( $missing > 0 ) {
			$issues['img_no_alt'] = [ 'count' => $missing ];
		}
	}

	/**
	 * Links whose visible text tells a machine nothing about the destination.
	 *
	 * @param \DOMDocument         $dom    Parsed document.
	 * @param array<string,mixed>  $issues Issue map (by reference).
	 */
	private static function audit_links( \DOMDocument $dom, array &$issues ): void {
		$examples = [];
		foreach ( $dom->getElementsByTagName( 'a' ) as $a ) {
			$href = trim( (string) $a->getAttribute( 'href' ) );
			if ( '' === $href || '#' === ( $href[0] ?? '' ) || 0 === stripos( $href, 'javascript:' ) ) {
				continue;
			}

			$text = trim( (string) preg_replace( '/\s+/', ' ', (string) $a->textContent ) );

			// A link with no text but a described image has an accessible name.
			if ( '' === $text ) {
				if ( self::link_has_named_image( $a ) || $a->hasAttribute( 'aria-label' ) || $a->hasAttribute( 'aria-labelledby' ) || '' !== trim( (string) $a->getAttribute( 'title' ) ) ) {
					continue;
				}
				$examples['(empty)'] = true;
				continue;
			}

			$normalised = rtrim( mb_strtolower( $text ), " .!:»›>-" );
			if ( in_array( $normalised, self::VAGUE_ANCHORS, true ) || preg_match( '#^https?://#i', $text ) ) {
				$examples[ mb_substr( $text, 0, 40 ) ] = true;
			}
		}

		if ( ! empty( $examples ) ) {
			$issues['vague_links'] = [
				'count'    => count( $examples ),
				'examples' => array_slice( array_keys( $examples ), 0, 3 ),
			];
		}
	}

	/**
	 * Heading structure: skipped levels break the machine-readable outline, and
	 * on a full page there should be exactly one H1.
	 *
	 * @param \DOMDocument         $dom       Parsed document.
	 * @param array<string,mixed>  $issues    Issue map (by reference).
	 * @param bool                 $full_page Whether H1 count is meaningful.
	 */
	private static function audit_headings( \DOMDocument $dom, array &$issues, bool $full_page ): void {
		$levels = [];
		foreach ( [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ] as $tag ) {
			foreach ( $dom->getElementsByTagName( $tag ) as $node ) {
				if ( '' !== trim( (string) $node->textContent ) ) {
					// Record in document order via getLineNo for stable sequencing.
					$levels[] = [ 'level' => (int) $tag[1], 'line' => $node->getLineNo() ];
				}
			}
		}

		usort( $levels, static fn( $a, $b ) => $a['line'] <=> $b['line'] );
		$sequence = array_column( $levels, 'level' );

		// A heading level that jumps more than one deeper than the previous one.
		$previous = 0;
		foreach ( $sequence as $level ) {
			if ( $previous > 0 && $level > $previous + 1 ) {
				$issues['heading_skip'] = [ 'detail' => 'H' . $previous . ' → H' . $level ];
				break;
			}
			$previous = $level;
		}

		if ( $full_page ) {
			$h1 = count( array_filter( $sequence, static fn( $l ) => 1 === $l ) );
			if ( 0 === $h1 ) {
				$issues['no_h1'] = true;
			} elseif ( $h1 > 1 ) {
				$issues['multiple_h1'] = [ 'count' => $h1 ];
			}
		}
	}

	/**
	 * Whether a text-less link wraps an image that has its own alt/label.
	 */
	private static function link_has_named_image( \DOMElement $a ): bool {
		foreach ( $a->getElementsByTagName( 'img' ) as $img ) {
			if ( '' !== trim( (string) $img->getAttribute( 'alt' ) ) || $img->hasAttribute( 'aria-label' ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Turn an issue map into human-readable messages, keyed by issue code.
	 *
	 * @param array<string,mixed> $issues Issue map from analyze().
	 * @return array<string,string> code => message
	 */
	public static function messages( array $issues ): array {
		$out = [];

		if ( isset( $issues['img_no_alt'] ) ) {
			$out['img_no_alt'] = sprintf(
				/* translators: %d: number of images. */
				_n( '%d image has no alt text — AI agents can\'t tell what it shows.', '%d images have no alt text — AI agents can\'t tell what they show.', (int) $issues['img_no_alt']['count'], 'hg-structured-data' ),
				(int) $issues['img_no_alt']['count']
			);
		}

		if ( isset( $issues['vague_links'] ) ) {
			$examples = implode( ', ', array_map( static fn( $e ) => '“' . $e . '”', (array) ( $issues['vague_links']['examples'] ?? [] ) ) );
			$out['vague_links'] = sprintf(
				/* translators: 1: number of links, 2: example link texts. */
				_n( '%1$d link uses non-descriptive text (%2$s) — a machine can\'t tell where it goes.', '%1$d links use non-descriptive text (%2$s) — a machine can\'t tell where they go.', (int) $issues['vague_links']['count'], 'hg-structured-data' ),
				(int) $issues['vague_links']['count'],
				$examples
			);
		}

		if ( isset( $issues['heading_skip'] ) ) {
			$out['heading_skip'] = sprintf(
				/* translators: %s: heading jump, e.g. "H2 → H4". */
				__( 'Heading levels skip (%s) — the outline machines build from your headings is broken.', 'hg-structured-data' ),
				(string) $issues['heading_skip']['detail']
			);
		}

		if ( ! empty( $issues['no_h1'] ) ) {
			$out['no_h1'] = __( 'The page has no H1 — AI agents have no clear main topic to anchor to.', 'hg-structured-data' );
		}

		if ( isset( $issues['multiple_h1'] ) ) {
			$out['multiple_h1'] = sprintf(
				/* translators: %d: number of H1 headings. */
				__( 'The page has %d H1 headings — there should be exactly one main heading.', 'hg-structured-data' ),
				(int) $issues['multiple_h1']['count']
			);
		}

		return $out;
	}
}
