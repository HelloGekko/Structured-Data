<?php
/**
 * PSR-4 style autoloader for the plugin namespace.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData;

defined( 'ABSPATH' ) || exit;

/**
 * Maps the HelloGekko\StructuredData namespace onto the includes/ directory.
 */
final class Autoloader {

	private const PREFIX = 'HelloGekko\\StructuredData\\';

	/**
	 * Register the autoloader with SPL.
	 */
	public static function register(): void {
		spl_autoload_register( [ self::class, 'load' ] );
	}

	/**
	 * Load a class file based on its fully-qualified name.
	 */
	public static function load( string $class ): void {
		if ( ! str_starts_with( $class, self::PREFIX ) ) {
			return;
		}

		$relative = substr( $class, strlen( self::PREFIX ) );
		$relative = str_replace( '\\', DIRECTORY_SEPARATOR, $relative );
		$file     = HGSD_PATH . 'includes' . DIRECTORY_SEPARATOR . $relative . '.php';

		if ( is_readable( $file ) ) {
			require_once $file;
		}
	}
}
