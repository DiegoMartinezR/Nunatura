<?php
/**
 * Plugin Name: AllpaCode Gestión de Reclamos Perú
 * Plugin URI: https://www.allpacode.com/plugins/libro-de-reclamaciones/
 * Description: Gestión digital de reclamos y quejas para empresas peruanas, con registros requeridos para el cumplimiento ante INDECOPI.
 * Version: 1.0.12
 * Author: AllpaCode
 * Author URI: https://www.allpacode.com
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: allpacode-gestion-de-reclamos-peru
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 8.0
 * Requires Plugins: allpacode-ubigeo-peru
 *
 * @package AllpaCodeGestionReclamos
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ALLPACODE_GR_VERSION', '1.0.12' );
define( 'ALLPACODE_GR_FILE', __FILE__ );
define( 'ALLPACODE_GR_PATH', plugin_dir_path( __FILE__ ) );
define( 'ALLPACODE_GR_URL', plugin_dir_url( __FILE__ ) );
define( 'ALLPACODE_GR_BASENAME', plugin_basename( __FILE__ ) );

require_once ALLPACODE_GR_PATH . 'includes/Autoloader.php';

\AllpaCodeGestionReclamos\Autoloader::register();

register_activation_hook( __FILE__, array( \AllpaCodeGestionReclamos\Installer::class, 'activate' ) );
register_deactivation_hook( __FILE__, array( \AllpaCodeGestionReclamos\Installer::class, 'deactivate' ) );

add_action(
	'plugins_loaded',
	static function (): void {
		\AllpaCodeGestionReclamos\Plugin::instance()->boot();
	}
);
