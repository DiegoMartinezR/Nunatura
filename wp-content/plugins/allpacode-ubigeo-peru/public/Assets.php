<?php
/**
 * Public assets.
 *
 * @package AllpaCodeUbigeoPeru
 */

namespace AllpaCodeUbigeoPeru\PublicApp;

use AllpaCodeUbigeoPeru\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Assets {
	public function hooks(): void {
		add_action( 'wp_enqueue_scripts', array( $this, 'register' ) );
	}

	public function register(): void {
		self::register_assets();
	}

	public static function register_assets(): void {
		wp_register_script( 'allpacode-ubigeo-public', ALLPACODE_UBIGEO_URL . 'assets/js/public.js', array(), ALLPACODE_UBIGEO_VERSION, true );
		wp_localize_script(
			'allpacode-ubigeo-public',
			'AllpaCodeUbigeo',
			array(
				'restUrl' => esc_url_raw( rest_url( 'allpacode-ubigeo/v1' ) ),
				'i18n'    => array(
					'department' => __( 'Selecciona un departamento', 'allpacode-ubigeo-peru' ),
					'province'   => __( 'Selecciona una provincia', 'allpacode-ubigeo-peru' ),
					'district'   => __( 'Selecciona un distrito', 'allpacode-ubigeo-peru' ),
				),
			)
		);

		wp_register_style( 'allpacode-ubigeo-public', ALLPACODE_UBIGEO_URL . 'assets/css/public.css', array(), ALLPACODE_UBIGEO_VERSION );

		if ( Settings::get( 'load_styles', true ) ) {
			wp_enqueue_style( 'allpacode-ubigeo-public' );
		}
	}
}
