<?php
/**
 * Email notifications.
 *
 * @package AllpaCodeGestionReclamos
 */

namespace AllpaCodeGestionReclamos;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class EmailService {
	public function send_created( array $claim ): void {
		$headers = array( 'Content-Type: text/html; charset=UTF-8' );
		$pdf_url = ( new PdfGenerator() )->url_for( $claim );
		$claim['receipt_url'] = $pdf_url;

		wp_mail(
			$claim['email'],
			sprintf( 'Constancia Gestión de Reclamos %s', $claim['code'] ),
			$this->wrap( Utils::replace_tokens( (string) Settings::get( 'email_customer' ), $claim ) . '<p><a href="' . esc_url( $pdf_url ) . '">Descargar constancia</a></p>' ),
			$headers
		);

		wp_mail(
			(string) Settings::get( 'admin_email' ),
			sprintf( 'Nuevo reclamo %s', $claim['code'] ),
			$this->wrap( Utils::replace_tokens( (string) Settings::get( 'email_admin' ), $claim ) ),
			$headers
		);
	}

	public function send_response( array $claim ): void {
		$status = Utils::statuses()[ $claim['status'] ] ?? $claim['status'];
		$content = '<p><strong>' . esc_html__( 'Estado actual del caso:', 'allpacode-gestion-de-reclamos-peru' ) . '</strong> ' . esc_html( $status ) . '</p>';
		$content .= Utils::replace_tokens( (string) Settings::get( 'email_response' ), $claim );

		wp_mail(
			$claim['email'],
			sprintf( 'Respuesta a tu caso %s', $claim['code'] ),
			$this->wrap( $content ),
			array( 'Content-Type: text/html; charset=UTF-8' )
		);
	}

	private function wrap( string $content ): string {
		$site_name = get_bloginfo( 'name' );
		$logo      = Settings::get( 'logo_url', '' );
		$brand     = $logo
			? '<img src="' . esc_url( $logo ) . '" alt="' . esc_attr( $site_name ) . '" style="max-height:54px;max-width:190px;width:auto;display:block;">'
			: '<strong style="font-size:18px;color:#111827;">' . esc_html( $site_name ) . '</strong>';

		return '<!doctype html><html><body style="margin:0;background:#f4f6f8;padding:28px;font-family:Inter,Arial,sans-serif;color:#111827;">'
			. '<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:680px;margin:0 auto;background:#ffffff;border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;">'
			. '<tr><td style="padding:28px 30px;border-bottom:1px solid #edf0f3;background:#fbfcfd;">' . $brand . '</td></tr>'
			. '<tr><td style="padding:30px;font-size:15px;line-height:1.65;color:#1f2937;">' . wp_kses_post( $content ) . '</td></tr>'
			. '<tr><td style="padding:18px 30px;background:#fbfcfd;border-top:1px solid #edf0f3;color:#6b7280;font-size:12px;line-height:1.5;">'
			. esc_html( $site_name ) . '<br>' . esc_html__( 'Este correo fue generado automáticamente por el Gestión de Reclamos.', 'allpacode-gestion-de-reclamos-peru' )
			. '</td></tr></table></body></html>';
	}
}
