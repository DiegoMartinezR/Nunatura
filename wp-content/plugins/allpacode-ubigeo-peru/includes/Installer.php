<?php
/**
 * Activation tasks.
 *
 * @package AllpaCodeUbigeoPeru
 */

namespace AllpaCodeUbigeoPeru;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Installer {
	public static function activate(): void {
		Settings::register_defaults();
		update_option( 'allpacode_ubigeo_version', ALLPACODE_UBIGEO_VERSION );
	}
}
