<?php
/**
 * Plugin Name:       Structured data for WordPress
 * Plugin URI:        https://hellogekko.nl/structured-data
 * Update URI:        https://github.com/HelloGekko/Structured-Data
 * Description:       Build perfect, schema.org-compliant structured data (JSON-LD) for your WordPress site through a visual wizard. Supports display conditions and property mapping to WordPress, ACF or custom values.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      8.0
 * Author:            HelloGekko
 * Author URI:        https://hellogekko.nl
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       hg-structured-data
 * Domain Path:       /languages
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;

define( 'HGSD_VERSION', '1.0.0' );
define( 'HGSD_FILE', __FILE__ );
define( 'HGSD_PATH', plugin_dir_path( __FILE__ ) );
define( 'HGSD_URL', plugin_dir_url( __FILE__ ) );
define( 'HGSD_BASENAME', plugin_basename( __FILE__ ) );

// Custom post type that stores each schema definition.
define( 'HGSD_CPT', 'hgsd_schema' );

require_once HGSD_PATH . 'includes/Autoloader.php';

\HelloGekko\StructuredData\Autoloader::register();

// Activation / deactivation.
register_activation_hook( __FILE__, [ \HelloGekko\StructuredData\Plugin::class, 'activate' ] );
register_deactivation_hook( __FILE__, [ \HelloGekko\StructuredData\Plugin::class, 'deactivate' ] );

/**
 * Boot the plugin once all plugins are loaded so we can detect ACF etc.
 */
add_action(
	'plugins_loaded',
	static function (): void {
		\HelloGekko\StructuredData\Plugin::instance()->boot();
	}
);
