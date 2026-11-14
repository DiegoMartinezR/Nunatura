<?php
/**
 * Main plugin orchestrator.
 *
 * @package AllpaCodeGestionReclamos
 */

namespace AllpaCodeGestionReclamos;

use AllpaCodeGestionReclamos\Admin\Admin;
use AllpaCodeGestionReclamos\Blocks\GutenbergBlock;
use AllpaCodeGestionReclamos\Elementor\ElementorIntegration;
use AllpaCodeGestionReclamos\PublicApp\AjaxController;
use AllpaCodeGestionReclamos\PublicApp\Assets;
use AllpaCodeGestionReclamos\PublicApp\Shortcode;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Plugin {
	private static ?Plugin $instance = null;

	public static function instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function boot(): void {
		Settings::register_defaults();
		$this->maybe_upgrade();

		add_action( 'init', array( $this, 'register_post_independent_features' ) );
		add_action( 'widgets_init', array( $this, 'register_widget' ) );
		add_action( 'rest_api_init', array( new RestController(), 'register_routes' ) );
		add_action( 'allpacode_gr_daily_cleanup', array( new Cron(), 'cleanup' ) );

		( new Assets() )->hooks();
		( new Shortcode() )->hooks();
		( new AjaxController() )->hooks();
		( new GutenbergBlock() )->hooks();
		( new ElementorIntegration() )->hooks();

		if ( is_admin() ) {
			add_action( 'admin_notices', array( $this, 'ubigeo_dependency_notice' ) );
			( new Admin() )->hooks();
		}
	}

	private function maybe_upgrade(): void {
		if ( get_option( 'allpacode_gr_version' ) !== ALLPACODE_GR_VERSION ) {
			Installer::create_tables();
			update_option( 'allpacode_gr_version', ALLPACODE_GR_VERSION );
		}
	}

	public function ubigeo_dependency_notice(): void {
		if ( function_exists( 'allpacode_ubigeo_is_available' ) || ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		echo '<div class="notice notice-warning"><p><strong>' . esc_html__( 'Gestión de Reclamos:', 'allpacode-gestion-de-reclamos-peru' ) . '</strong> ' . esc_html__( 'Para usar selección oficial de departamento, provincia y distrito, instala y activa el plugin AllpaCode Ubigeo Perú.', 'allpacode-gestion-de-reclamos-peru' ) . '</p></div>';
	}

	public function register_post_independent_features(): void {
		Capability::register();
	}

	public function register_widget(): void {
		register_widget( \AllpaCodeGestionReclamos\PublicApp\ClassicWidget::class );
	}
}
