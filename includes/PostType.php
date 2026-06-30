<?php
/**
 * Registers the custom post type that stores schema definitions.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData;

defined( 'ABSPATH' ) || exit;

/**
 * The hgsd_schema post type. Each post is one schema definition created by the wizard.
 */
final class PostType {

	/**
	 * Hook registration into WordPress.
	 */
	public function register_hooks(): void {
		add_action( 'init', [ $this, 'register' ] );
	}

	/**
	 * Register the post type.
	 */
	public function register(): void {
		$labels = [
			'name'               => __( 'Structured Data', 'hg-structured-data' ),
			'singular_name'      => __( 'Schema', 'hg-structured-data' ),
			'add_new'            => __( 'Add New Schema', 'hg-structured-data' ),
			'add_new_item'       => __( 'Add New Schema', 'hg-structured-data' ),
			'edit_item'          => __( 'Edit Schema', 'hg-structured-data' ),
			'new_item'           => __( 'New Schema', 'hg-structured-data' ),
			'view_item'          => __( 'View Schema', 'hg-structured-data' ),
			'search_items'       => __( 'Search Schemas', 'hg-structured-data' ),
			'not_found'          => __( 'No schemas found.', 'hg-structured-data' ),
			'not_found_in_trash' => __( 'No schemas found in Trash.', 'hg-structured-data' ),
			'menu_name'          => __( 'Structured Data', 'hg-structured-data' ),
		];

		register_post_type(
			HGSD_CPT,
			[
				'labels'              => $labels,
				'public'              => false,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_icon'           => 'dashicons-editor-code',
				'menu_position'       => 80,
				'capability_type'     => 'post',
				'capabilities'        => [
					'create_posts' => 'manage_options',
				],
				'map_meta_cap'        => true,
				'supports'            => [ 'title' ],
				'has_archive'         => false,
				'rewrite'             => false,
				'query_var'           => false,
				'exclude_from_search' => true,
				'show_in_rest'        => false,
			]
		);
	}
}
