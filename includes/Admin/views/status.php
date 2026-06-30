<?php
/**
 * Sidebar status meta box.
 *
 * @var \HelloGekko\StructuredData\SchemaDefinition $def Current definition.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;
?>
<p>
	<label>
		<input type="checkbox" name="hgsd[enabled]" value="1" <?php checked( $def->enabled() ); ?> />
		<?php esc_html_e( 'Enable this schema output', 'hg-structured-data' ); ?>
	</label>
</p>
<p class="description">
	<?php esc_html_e( 'When enabled, the JSON-LD is printed on every page that matches the display conditions.', 'hg-structured-data' ); ?>
</p>
