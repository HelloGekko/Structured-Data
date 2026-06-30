<?php
/**
 * Resolves a property mapping into a concrete value.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Output;

use HelloGekko\StructuredData\Plugin;

defined( 'ABSPATH' ) || exit;

/**
 * Turns a stored mapping ({property, source, value}) into the value that should
 * be placed in the JSON-LD, pulling from WordPress core data, ACF or literal text.
 */
final class PropertyResolver {

	/**
	 * Resolve a single mapping.
	 *
	 * @param array<string,mixed> $mapping Mapping definition.
	 * @param array<string,mixed> $context Runtime context (post_id, author_id...).
	 * @return mixed Resolved value, or null when nothing is available.
	 */
	public function resolve( array $mapping, array $context ) {
		$source = (string) ( $mapping['source'] ?? 'wp' );
		$value  = (string) ( $mapping['value'] ?? '' );

		switch ( $source ) {
			case 'acf':
				return $this->resolve_acf( $value, $context );
			case 'custom':
				return $this->resolve_custom( $value, $context );
			case 'wp':
			default:
				return $this->resolve_wp( $value, $context );
		}
	}

	/**
	 * Resolve a built-in WordPress data point.
	 *
	 * @param array<string,mixed> $context Runtime context.
	 * @return mixed
	 */
	private function resolve_wp( string $key, array $context ) {
		$post_id   = (int) ( $context['post_id'] ?? 0 );
		$author_id = (int) ( $context['author_id'] ?? ( $post_id ? (int) get_post_field( 'post_author', $post_id ) : 0 ) );

		// Arbitrary post meta: "meta:my_field".
		if ( str_starts_with( $key, 'meta:' ) && $post_id ) {
			$meta = get_post_meta( $post_id, substr( $key, 5 ), true );
			return is_scalar( $meta ) ? (string) $meta : '';
		}

		switch ( $key ) {
			case 'post_title':
				return $post_id ? wp_strip_all_tags( get_the_title( $post_id ) ) : '';
			case 'post_content':
				return $post_id ? wp_strip_all_tags( (string) get_post_field( 'post_content', $post_id ) ) : '';
			case 'post_excerpt':
				return $post_id ? $this->excerpt( $post_id ) : '';
			case 'post_date':
				return $post_id ? (string) get_post_time( 'c', true, $post_id ) : '';
			case 'post_modified':
				return $post_id ? (string) get_post_modified_time( 'c', true, $post_id ) : '';
			case 'permalink':
				return $post_id ? (string) get_permalink( $post_id ) : '';
			case 'featured_image':
				if ( ! $post_id ) {
					return '';
				}
				$img = get_the_post_thumbnail_url( $post_id, 'full' );
				return $img ? (string) $img : '';
			case 'word_count':
				return $post_id ? (string) str_word_count( wp_strip_all_tags( (string) get_post_field( 'post_content', $post_id ) ) ) : '';
			case 'comment_count':
				return $post_id ? (string) get_comments_number( $post_id ) : '';
			case 'post_type':
				return $post_id ? (string) get_post_type( $post_id ) : '';
			case 'post_categories':
				return $post_id ? $this->term_names( $post_id, 'category' ) : '';
			case 'post_tags':
				return $post_id ? $this->term_names( $post_id, 'post_tag' ) : '';
			case 'author_name':
				return $author_id ? get_the_author_meta( 'display_name', $author_id ) : '';
			case 'author_first_name':
				return $author_id ? get_the_author_meta( 'first_name', $author_id ) : '';
			case 'author_last_name':
				return $author_id ? get_the_author_meta( 'last_name', $author_id ) : '';
			case 'author_url':
				return $author_id ? (string) get_author_posts_url( $author_id ) : '';
			case 'author_avatar':
				return $author_id ? (string) get_avatar_url( $author_id, [ 'size' => 512 ] ) : '';
			case 'author_bio':
				return $author_id ? get_the_author_meta( 'description', $author_id ) : '';
			case 'author_email':
				return $author_id ? get_the_author_meta( 'user_email', $author_id ) : '';
			case 'site_name':
				return get_bloginfo( 'name' );
			case 'site_description':
				return get_bloginfo( 'description' );
			case 'site_language':
				return str_replace( '_', '-', get_locale() );
			case 'site_logo':
				$logo_id = (int) get_theme_mod( 'custom_logo' );
				$logo    = $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : '';
				return $logo ? (string) $logo : '';
			case 'site_icon':
				return (string) get_site_icon_url();
			case 'home_url':
				return home_url( '/' );
			case 'post_id':
				return (string) $post_id;
			default:
				return '';
		}
	}

	/**
	 * Resolve an ACF field on the current post.
	 *
	 * @param array<string,mixed> $context Runtime context.
	 * @return mixed
	 */
	private function resolve_acf( string $field, array $context ) {
		if ( '' === $field || ! Plugin::has_acf() ) {
			return '';
		}

		// "option" resolves against an ACF options page; otherwise the post.
		if ( 'option' === ( $context['acf_source'] ?? '' ) ) {
			$target = 'option';
		} else {
			$post_id = (int) ( $context['post_id'] ?? 0 );
			$target  = $post_id ?: false;
		}

		$value = get_field( $field, $target );

		// Reduce common ACF return shapes to a scalar usable in JSON-LD.
		if ( is_array( $value ) ) {
			if ( isset( $value['url'] ) ) { // Image / file array.
				return (string) $value['url'];
			}
			return ''; // Repeaters / complex fields are not single values.
		}

		return is_scalar( $value ) ? (string) $value : '';
	}

	/**
	 * Resolve literal custom text, expanding a small set of merge tags.
	 *
	 * @param array<string,mixed> $context Runtime context.
	 */
	private function resolve_custom( string $text, array $context ): string {
		if ( '' === $text ) {
			return '';
		}

		$replacements = [
			'{{title}}'            => $this->resolve_wp( 'post_title', $context ),
			'{{permalink}}'        => $this->resolve_wp( 'permalink', $context ),
			'{{site_name}}'        => $this->resolve_wp( 'site_name', $context ),
			'{{site_description}}' => $this->resolve_wp( 'site_description', $context ),
			'{{author_name}}'      => $this->resolve_wp( 'author_name', $context ),
			'{{home_url}}'         => $this->resolve_wp( 'home_url', $context ),
		];

		return strtr( $text, $replacements );
	}

	/**
	 * Comma-separated term names for a post in a taxonomy.
	 */
	private function term_names( int $post_id, string $taxonomy ): string {
		$terms = get_the_terms( $post_id, $taxonomy );
		if ( ! $terms || is_wp_error( $terms ) ) {
			return '';
		}
		return implode( ', ', wp_list_pluck( $terms, 'name' ) );
	}

	/**
	 * Produce an excerpt, falling back to a trimmed version of the content.
	 */
	private function excerpt( int $post_id ): string {
		$post = get_post( $post_id );
		if ( ! $post ) {
			return '';
		}

		if ( '' !== trim( (string) $post->post_excerpt ) ) {
			return wp_strip_all_tags( $post->post_excerpt );
		}

		$content = wp_strip_all_tags( strip_shortcodes( (string) $post->post_content ) );
		return wp_trim_words( $content, 55, '' );
	}
}
