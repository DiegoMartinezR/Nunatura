<?php
/**
 * Plugin Name: AllpaCode Ubigeo Perú
 * Plugin URI: https://www.allpacode.com/plugins/ubigeo-peru
 * Description: Selectores dependientes de departamento, provincia y distrito del Perú con código ubigeo.
 * Version: 1.0.1
 * Author: AllpaCode
 * Author URI: https://www.allpacode.com
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: allpacode-ubigeo-peru
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 8.0
 *
 * @package AllpaCodeUbigeoPeru
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ALLPACODE_UBIGEO_VERSION', '1.0.1' );
define( 'ALLPACODE_UBIGEO_FILE', __FILE__ );
define( 'ALLPACODE_UBIGEO_PATH', plugin_dir_path( __FILE__ ) );
define( 'ALLPACODE_UBIGEO_URL', plugin_dir_url( __FILE__ ) );
define( 'ALLPACODE_UBIGEO_BASENAME', plugin_basename( __FILE__ ) );

require_once ALLPACODE_UBIGEO_PATH . 'includes/Autoloader.php';
\AllpaCodeUbigeoPeru\Autoloader::register();
require_once ALLPACODE_UBIGEO_PATH . 'includes/functions.php';

register_activation_hook( __FILE__, array( \AllpaCodeUbigeoPeru\Installer::class, 'activate' ) );

add_action(
	'plugins_loaded',
	static function (): void {
		\AllpaCodeUbigeoPeru\Plugin::instance()->boot();
	}
);
