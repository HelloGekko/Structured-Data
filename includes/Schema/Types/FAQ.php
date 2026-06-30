<?php
/**
 * FAQ (FAQPage) schema type with automatic ACF repeater + manual support.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Schema\Types;

use HelloGekko\StructuredData\Plugin;
use HelloGekko\StructuredData\Schema\AbstractSchemaType;
use HelloGekko\StructuredData\Output\PropertyResolver;

defined( 'ABSPATH' ) || exit;

/**
 * Schema.org FAQPage. Builds a mainEntity list of Question/Answer pairs either
 * automatically from an ACF repeater field or from manually entered pairs.
 */
class FAQ extends AbstractSchemaType {

	public function key(): string {
		return 'FAQPage';
	}

	public function label(): string {
		return __( 'FAQ', 'hg-structured-data' );
	}

	public function group(): string {
		return __( 'Content', 'hg-structured-data' );
	}

	/**
	 * FAQ uses its own dedicated UI, so it exposes no generic properties.
	 */
	public function recommended(): array {
		return [];
	}

	/**
	 * The FAQ UI is fully custom; do not surface the generic property catalog.
	 */
	public function properties(): array {
		return [];
	}

	/**
	 * Build the FAQPage node from the FAQ configuration.
	 *
	 * @param array{faq?:array<string,mixed>} $config  Schema config.
	 * @param array<string,mixed>             $context Runtime context.
	 * @return array<string,mixed>|null
	 */
	public function build( array $config, PropertyResolver $resolver, array $context ): ?array {
		$faq   = isset( $config['faq'] ) && is_array( $config['faq'] ) ? $config['faq'] : [];
		$pairs = 'automatic' === ( $faq['method'] ?? 'manual' )
			? $this->collect_automatic( $faq, $context )
			: $this->collect_manual( $faq, $context );

		$entities = [];
		foreach ( $pairs as $pair ) {
			$question = trim( wp_strip_all_tags( (string) $pair['question'] ) );
			$answer   = trim( (string) $pair['answer'] );

			if ( '' === $question || '' === $answer ) {
				continue;
			}

			$entities[] = [
				'@type'          => 'Question',
				'name'           => $question,
				'acceptedAnswer' => [
					'@type' => 'Answer',
					'text'  => wp_kses_post( $answer ),
				],
			];
		}

		if ( empty( $entities ) ) {
			return null;
		}

		return [
			'@context'   => 'https://schema.org',
			'@type'      => 'FAQPage',
			'mainEntity' => $entities,
		];
	}

	/**
	 * Pull question/answer pairs from an ACF repeater on the current post.
	 *
	 * @param array<string,mixed> $config  FAQ config.
	 * @param array<string,mixed> $context Runtime context.
	 * @return array<int,array{question:string,answer:string}>
	 */
	private function collect_automatic( array $config, array $context ): array {
		$repeater = (string) ( $config['acf_repeater'] ?? '' );
		$q_field  = (string) ( $config['question_subfield'] ?? '' );
		$a_field  = (string) ( $config['answer_subfield'] ?? '' );
		$post_id  = (int) ( $context['post_id'] ?? 0 );

		if ( '' === $repeater || '' === $q_field || '' === $a_field || ! $post_id ) {
			return [];
		}

		if ( ! Plugin::has_acf() ) {
			return [];
		}

		$rows = get_field( $repeater, $post_id );
		if ( ! is_array( $rows ) ) {
			return [];
		}

		$pairs = [];
		foreach ( $rows as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}
			$pairs[] = [
				'question' => (string) ( $row[ $q_field ] ?? '' ),
				'answer'   => (string) ( $row[ $a_field ] ?? '' ),
			];
		}

		return $pairs;
	}

	/**
	 * Read manually configured pairs.
	 *
	 * @param array<string,mixed> $config FAQ config.
	 * @return array<int,array{question:string,answer:string}>
	 */
	private function collect_manual( array $config, array $context ): array {
		$items = $config['items'] ?? [];
		if ( ! is_array( $items ) ) {
			return [];
		}

		$pairs = [];
		foreach ( $items as $item ) {
			if ( ! is_array( $item ) ) {
				continue;
			}
			$pairs[] = [
				'question' => (string) ( $item['question'] ?? '' ),
				'answer'   => (string) ( $item['answer'] ?? '' ),
			];
		}

		return $pairs;
	}
}
