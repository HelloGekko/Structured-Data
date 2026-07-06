<?php
/**
 * Reads ACF field definitions for the admin UI.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Admin;

use HelloGekko\StructuredData\Plugin;

defined( 'ABSPATH' ) || exit;

/**
 * Provides flat lists of ACF fields, repeater fields and repeater subfields.
 */
final class AcfFields {

	/**
	 * Get fields for the requested mode.
	 *
	 * @param string $mode     "all" | "repeaters" | "subfields".
	 * @param string $repeater Field key/name when mode is "subfields".
	 * @return array<int,array{name:string,label:string,type:string,choices:array<int,array{value:string,label:string}>}>
	 */
	public function get( string $mode, string $repeater = '' ): array {
		if ( ! Plugin::has_acf() || ! function_exists( 'acf_get_field_groups' ) ) {
			return [];
		}

		if ( 'subfields' === $mode ) {
			return $this->subfields( $repeater );
		}

		$only_repeaters = 'repeaters' === $mode;
		$results        = [];

		foreach ( acf_get_field_groups() as $group ) {
			$fields = function_exists( 'acf_get_fields' ) ? acf_get_fields( $group['key'] ) : [];
			if ( ! is_array( $fields ) ) {
				continue;
			}
			foreach ( $fields as $field ) {
				if ( $only_repeaters && 'repeater' !== ( $field['type'] ?? '' ) ) {
					continue;
				}
				// Skip structural/visual fields for scalar mapping.
				if ( ! $only_repeaters && in_array( $field['type'] ?? '', [ 'tab', 'message', 'accordion', 'group', 'repeater', 'flexible_content' ], true ) ) {
					continue;
				}
				$results[] = $this->describe( $field );
			}
		}

		return $results;
	}

	/**
	 * Normalise a single ACF field to name, label, type and (for choice fields)
	 * its selectable options — so the display-condition UI can render a matching
	 * value control.
	 *
	 * @param array<string,mixed> $field ACF field definition.
	 * @return array{name:string,label:string,type:string,choices:array<int,array{value:string,label:string}>}
	 */
	private function describe( array $field ): array {
		$choices = [];
		if ( isset( $field['choices'] ) && is_array( $field['choices'] ) ) {
			foreach ( $field['choices'] as $value => $label ) {
				$choices[] = [
					'value' => (string) $value,
					'label' => is_scalar( $label ) ? (string) $label : (string) $value,
				];
			}
		}

		return [
			'name'    => (string) ( $field['name'] ?? '' ),
			'label'   => (string) ( $field['label'] ?? $field['name'] ?? '' ),
			'type'    => (string) ( $field['type'] ?? 'text' ),
			'choices' => $choices,
		];
	}

	/**
	 * Subfields of a given repeater (matched by name or key).
	 *
	 * @return array<int,array{name:string,label:string}>
	 */
	private function subfields( string $repeater ): array {
		if ( '' === $repeater || ! function_exists( 'acf_get_field_groups' ) || ! function_exists( 'acf_get_fields' ) ) {
			return [];
		}

		foreach ( acf_get_field_groups() as $group ) {
			$fields = acf_get_fields( $group['key'] );
			if ( ! is_array( $fields ) ) {
				continue;
			}
			foreach ( $fields as $field ) {
				$matches = ( $field['name'] ?? '' ) === $repeater || ( $field['key'] ?? '' ) === $repeater;
				if ( 'repeater' === ( $field['type'] ?? '' ) && $matches ) {
					$results = [];
					foreach ( (array) ( $field['sub_fields'] ?? [] ) as $sub ) {
						$results[] = $this->describe( $sub );
					}
					return $results;
				}
			}
		}

		return [];
	}
}
