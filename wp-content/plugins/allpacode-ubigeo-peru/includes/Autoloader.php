<?php
/**
 * Lightweight autoloader.
 *
 * @package AllpaCodeUbigeoPeru
 */

namespace AllpaCodeUbigeoPeru;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Autoloader {
	public static function register(): void {
		spl_autoload_register( array( __CLASS__, 'autoload' ) );
	}

	public static function autoload( string $class ): void {
		$prefix = __NAMESPACE__ . '\\';

		if ( 0 !== strpos( $class, $prefix ) ) {
			return;
		}

		$relative = substr( $class, strlen( $prefix ) );
		$parts    = explode( '\\', $relative );
		$file     = array_pop( $parts ) . '.php';
		$base     = 'includes';
		$map      = array(
			'Admin'     => 'admin',
			'PublicApp' => 'public',
		);

		if ( ! empty( $parts[0] ) && isset( $map[ $parts[0] ] ) ) {
			$base = $map[ array_shift( $parts ) ];
		}

		$path = ALLPACODE_UBIGEO_PATH . $base . '/' . ( $parts ? implode( '/', $parts ) . '/' : '' ) . $file;

		if ( is_readable( $path ) ) {
			require_once $path;
		}
	}
}
