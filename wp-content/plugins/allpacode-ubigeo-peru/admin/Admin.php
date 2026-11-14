<?php
/**
 * Admin settings.
 *
 * @package AllpaCodeUbigeoPeru
 */

namespace AllpaCodeUbigeoPeru\Admin;

use AllpaCodeUbigeoPeru\Repository;
use AllpaCodeUbigeoPeru\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Admin {
	public function hooks(): void {
		add_action( 'admin_menu', array( $this, 'menu' ) );
		add_action( 'admin_init', array( $this, 'settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );
	}

	public function menu(): void {
		add_options_page(
			__( 'Ubigeo Perú', 'allpacode-ubigeo-peru' ),
			__( 'Ubigeo Perú', 'allpacode-ubigeo-peru' ),
			'manage_options',
			'allpacode-ubigeo-peru',
			array( $this, 'page' )
		);
	}

	public function settings(): void {
		register_setting( 'allpacode_ubigeo_settings', Settings::OPTION, array( Settings::class, 'sanitize' ) );
	}

	public function assets( string $hook ): void {
		if ( 'settings_page_allpacode-ubigeo-peru' !== $hook ) {
			return;
		}

		wp_enqueue_style( 'allpacode-ubigeo-admin', ALLPACODE_UBIGEO_URL . 'assets/css/admin.css', array(), ALLPACODE_UBIGEO_VERSION );
	}

	public function page(): void {
		$settings   = Settings::get();
		$repository = new Repository();
		$stats      = array(
			'departments' => count( $repository->departments() ),
			'provinces'   => count( $repository->data()['provinces'] ?? array() ),
			'districts'   => count( $repository->data()['districts'] ?? array() ),
		);
		include ALLPACODE_UBIGEO_PATH . 'templates/admin-settings.php';
	}
}
