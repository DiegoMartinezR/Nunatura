<?php
/**
 * Public assets.
 *
 * @package AllpaCodeGestionReclamos
 */

namespace AllpaCodeGestionReclamos\PublicApp;

use AllpaCodeGestionReclamos\Settings;

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
		wp_register_style( 'allpacode-gr-public', ALLPACODE_GR_URL . 'assets/css/public.css', array(), ALLPACODE_GR_VERSION );
		wp_register_script( 'allpacode-gr-public', ALLPACODE_GR_URL . 'assets/js/public.js', array(), ALLPACODE_GR_VERSION, true );

		wp_localize_script(
			'allpacode-gr-public',
			'AllpaCodeGR',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'allpacode_gr_submit' ),
				'captcha' => array(
					'provider' => Settings::get( 'captcha_provider', 'none' ),
					'siteKey'  => Settings::get( 'captcha_site_key', '' ),
				),
				'i18n'    => array(
					'sending' => __( 'Enviando...', 'allpacode-gestion-de-reclamos-peru' ),
					'sent'    => __( 'Tu registro fue enviado correctamente.', 'allpacode-gestion-de-reclamos-peru' ),
					'error'   => __( 'No pudimos registrar la solicitud. Revisa los campos.', 'allpacode-gestion-de-reclamos-peru' ),
					'required' => __( 'Obligatorio', 'allpacode-gestion-de-reclamos-peru' ),
					'captcha' => __( 'Completa la validación anti-spam antes de enviar.', 'allpacode-gestion-de-reclamos-peru' ),
					'session' => __( 'La sesión del formulario venció. Actualiza la página e inténtalo nuevamente.', 'allpacode-gestion-de-reclamos-peru' ),
				),
			)
		);

		$css = sprintf(
			':root{--allpacode-gr-primary:%1$s;--allpacode-gr-accent:%2$s;--allpacode-gr-radius:%3$dpx;}',
			esc_attr( (string) Settings::get( 'primary_color' ) ),
			esc_attr( (string) Settings::get( 'accent_color' ) ),
			(int) Settings::get( 'border_radius' )
		);
		wp_add_inline_style( 'allpacode-gr-public', $css );
	}
}
