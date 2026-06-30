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
	 * @return array<int,array{name:string,label:string}>
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
				$results[] = [
					'name'  => (string) ( $field['name'] ?? '' ),
					'label' => (string) ( $field['label'] ?? $field['name'] ?? '' ),
				];
			}
		}

		return $results;
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
						$results[] = [
							'name'  => (string) ( $sub['name'] ?? '' ),
							'label' => (string) ( $sub['label'] ?? $sub['name'] ?? '' ),
						];
					}
					return $results;
				}
			}
		}

		return [];
	}
}
