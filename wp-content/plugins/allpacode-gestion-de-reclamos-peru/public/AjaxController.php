<?php
/**
 * AJAX endpoints.
 *
 * @package AllpaCodeGestionReclamos
 */

namespace AllpaCodeGestionReclamos\PublicApp;

use AllpaCodeGestionReclamos\Captcha;
use AllpaCodeGestionReclamos\EmailService;
use AllpaCodeGestionReclamos\PdfGenerator;
use AllpaCodeGestionReclamos\RateLimiter;
use AllpaCodeGestionReclamos\Repository;
use AllpaCodeGestionReclamos\Settings;
use AllpaCodeGestionReclamos\Validator;
use AllpaCodeGestionReclamos\Webhook;
use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class AjaxController {
	public function hooks(): void {
		add_action( 'wp_ajax_allpacode_gr_nonce', array( $this, 'nonce' ) );
		add_action( 'wp_ajax_nopriv_allpacode_gr_nonce', array( $this, 'nonce' ) );
		add_action( 'wp_ajax_allpacode_gr_submit', array( $this, 'submit' ) );
		add_action( 'wp_ajax_nopriv_allpacode_gr_submit', array( $this, 'submit' ) );
	}

	public function nonce(): void {
		wp_send_json_success(
			array(
				'nonce' => wp_create_nonce( 'allpacode_gr_submit' ),
			)
		);
	}

	public function submit(): void {
		if ( false === check_ajax_referer( 'allpacode_gr_submit', 'nonce', false ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'La sesión del formulario venció. Actualiza la página e inténtalo nuevamente.', 'allpacode-gestion-de-reclamos-peru' ),
				),
				403
			);
		}

		$honeypot = sanitize_text_field( wp_unslash( $_POST['allpacode_gr_company'] ?? '' ) );
		if ( ! empty( $honeypot ) ) {
			wp_send_json_error( array( 'message' => __( 'Validación anti-spam fallida.', 'allpacode-gestion-de-reclamos-peru' ) ), 400 );
		}

		$captcha_token = sanitize_text_field(
			wp_unslash(
				$_POST['captcha_token']
				?? $_POST['cf-turnstile-response']
				?? $_POST['g-recaptcha-response']
				?? ''
			)
		);
		if ( ! ( new Captcha() )->verify( $captcha_token ) ) {
			wp_send_json_error( array( 'message' => __( 'No se pudo validar el captcha.', 'allpacode-gestion-de-reclamos-peru' ) ), 400 );
		}

		$input = wp_unslash( $_POST );
		$data  = ( new Validator() )->sanitize_claim( is_array( $input ) ? $input : array() );

		if ( $data instanceof WP_Error ) {
			wp_send_json_error(
				array(
					'message' => __( 'Revisa los campos marcados antes de enviar.', 'allpacode-gestion-de-reclamos-peru' ),
					'errors'  => $data->errors,
				),
				422
			);
		}

		$rate_limiter = new RateLimiter();
		if ( $rate_limiter->too_many_attempts() ) {
			wp_send_json_error( array( 'message' => __( 'Demasiados envíos válidos desde esta conexión. Intenta nuevamente en unos minutos.', 'allpacode-gestion-de-reclamos-peru' ) ), 429 );
		}

		$rate_limiter->record_attempt();

		$upload = $this->handle_upload();
		if ( $upload instanceof WP_Error ) {
			wp_send_json_error( array( 'message' => $upload->get_error_message() ), 422 );
		}

		if ( $upload ) {
			$data['evidence_url'] = $upload;
		}

		$repository = new Repository();
		$id         = $repository->create_claim( $data );
		$claim      = $repository->find( $id );

		if ( ! $claim ) {
			wp_send_json_error( array( 'message' => __( 'No se pudo recuperar el registro creado.', 'allpacode-gestion-de-reclamos-peru' ) ), 500 );
		}

		( new PdfGenerator() )->generate( $claim );
		( new EmailService() )->send_created( $claim );
		( new Webhook() )->dispatch( 'claim.created', $claim );

		do_action( 'allpacode_gr_claim_created', $claim );

		wp_send_json_success(
			array(
				'message'     => __( 'Tu reclamo fue registrado correctamente.', 'allpacode-gestion-de-reclamos-peru' ),
				'code'        => $claim['code'],
				'receipt_url' => ( new PdfGenerator() )->url_for( $claim ),
			)
		);
	}

	private function handle_upload(): string|WP_Error {
		// Nonce verification is performed in submit() before this method is called.
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		if ( empty( $_FILES['evidence']['name'] ) || ! Settings::get( 'allow_uploads', true ) ) {
			return '';
		}

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- wp_handle_upload() validates the file array and MIME type below.
		$file = $_FILES['evidence'];
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		$max  = (int) Settings::get( 'max_upload_mb', 5 ) * MB_IN_BYTES;

		if ( $file['size'] > $max ) {
			return new WP_Error( 'file_size', __( 'El archivo supera el tamaño permitido.', 'allpacode-gestion-de-reclamos-peru' ) );
		}

		$allowed = array(
			'jpg|jpeg|jpe' => 'image/jpeg',
			'png'          => 'image/png',
			'pdf'          => 'application/pdf',
		);

		require_once ABSPATH . 'wp-admin/includes/file.php';
		$result = wp_handle_upload(
			$file,
			array(
				'test_form' => false,
				'mimes'     => $allowed,
			)
		);

		if ( isset( $result['error'] ) ) {
			return new WP_Error( 'upload_error', $result['error'] );
		}

		return esc_url_raw( $result['url'] );
	}
}
