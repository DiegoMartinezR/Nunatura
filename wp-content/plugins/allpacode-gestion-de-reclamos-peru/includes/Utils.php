<?php
/**
 * Shared helpers.
 *
 * @package AllpaCodeGestionReclamos
 */

namespace AllpaCodeGestionReclamos;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Utils {
	public static function ip_address(): string {
		if ( empty( $_SERVER['REMOTE_ADDR'] ) ) {
			return '0.0.0.0';
		}

		$ip = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );

		return filter_var( $ip, FILTER_VALIDATE_IP ) ? $ip : '0.0.0.0';
	}

	public static function user_agent(): string {
		return isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '';
	}

	public static function statuses(): array {
		return array(
			'pending'   => __( 'Pendiente', 'allpacode-gestion-de-reclamos-peru' ),
			'reviewing' => __( 'En revisión', 'allpacode-gestion-de-reclamos-peru' ),
			'answered'  => __( 'Respondido', 'allpacode-gestion-de-reclamos-peru' ),
			'closed'    => __( 'Cerrado', 'allpacode-gestion-de-reclamos-peru' ),
		);
	}

	public static function types(): array {
		return array(
			'reclamo' => __( 'Reclamo', 'allpacode-gestion-de-reclamos-peru' ),
			'queja'   => __( 'Queja', 'allpacode-gestion-de-reclamos-peru' ),
		);
	}

	public static function replace_tokens( string $template, array $claim ): string {
		$tokens = array();
		foreach ( $claim as $key => $value ) {
			if ( is_scalar( $value ) || null === $value ) {
				$tokens[ '{' . $key . '}' ] = (string) $value;
			}
		}

		$tokens['{response}'] = (string) ( $claim['admin_response'] ?? '' );
		$tokens['{status_label}'] = self::statuses()[ $claim['status'] ?? '' ] ?? (string) ( $claim['status'] ?? '' );
		$tokens['{type_label}']   = self::types()[ $claim['type'] ?? '' ] ?? (string) ( $claim['type'] ?? '' );

		return strtr( $template, $tokens );
	}

	public static function action_label( string $action ): string {
		$labels = array(
			'created'        => __( 'Registro creado', 'allpacode-gestion-de-reclamos-peru' ),
			'admin_updated'  => __( 'Actualización administrativa', 'allpacode-gestion-de-reclamos-peru' ),
			'case_reviewed'  => __( 'Revisión guardada', 'allpacode-gestion-de-reclamos-peru' ),
			'no_changes'     => __( 'Sin cambios', 'allpacode-gestion-de-reclamos-peru' ),
			'status_changed' => __( 'Estado actualizado', 'allpacode-gestion-de-reclamos-peru' ),
			'response_changed' => __( 'Respuesta actualizada', 'allpacode-gestion-de-reclamos-peru' ),
			'notes_changed'  => __( 'Notas internas actualizadas', 'allpacode-gestion-de-reclamos-peru' ),
			'response_sent'  => __( 'Correo enviado al consumidor', 'allpacode-gestion-de-reclamos-peru' ),
		);

		return $labels[ $action ] ?? ucwords( str_replace( '_', ' ', $action ) );
	}

	public static function export_columns(): array {
		return array(
			'code'             => __( 'Código correlativo', 'allpacode-gestion-de-reclamos-peru' ),
			'claim_year'       => __( 'Año', 'allpacode-gestion-de-reclamos-peru' ),
			'claim_number'     => __( 'Número anual', 'allpacode-gestion-de-reclamos-peru' ),
			'type'             => __( 'Clase de caso', 'allpacode-gestion-de-reclamos-peru' ),
			'status'           => __( 'Estado de atención', 'allpacode-gestion-de-reclamos-peru' ),
			'consumer_name'    => __( 'Nombres y apellidos', 'allpacode-gestion-de-reclamos-peru' ),
			'document_type'    => __( 'Tipo de documento', 'allpacode-gestion-de-reclamos-peru' ),
			'document_number'  => __( 'Número de documento', 'allpacode-gestion-de-reclamos-peru' ),
			'phone'            => __( 'Teléfono', 'allpacode-gestion-de-reclamos-peru' ),
			'email'            => __( 'Correo electrónico', 'allpacode-gestion-de-reclamos-peru' ),
			'address'          => __( 'Dirección', 'allpacode-gestion-de-reclamos-peru' ),
			'department_name'  => __( 'Departamento / Región', 'allpacode-gestion-de-reclamos-peru' ),
			'province_name'    => __( 'Provincia', 'allpacode-gestion-de-reclamos-peru' ),
			'district_name'    => __( 'Distrito', 'allpacode-gestion-de-reclamos-peru' ),
			'ubigeo_code'      => __( 'Código ubigeo', 'allpacode-gestion-de-reclamos-peru' ),
			'product_service'  => __( 'Producto o servicio', 'allpacode-gestion-de-reclamos-peru' ),
			'claimed_amount'   => __( 'Monto reclamado', 'allpacode-gestion-de-reclamos-peru' ),
			'description'      => __( 'Descripción', 'allpacode-gestion-de-reclamos-peru' ),
			'consumer_request' => __( 'Pedido del consumidor', 'allpacode-gestion-de-reclamos-peru' ),
			'business_name'    => __( 'Razón social', 'allpacode-gestion-de-reclamos-peru' ),
			'business_ruc'     => __( 'RUC', 'allpacode-gestion-de-reclamos-peru' ),
			'business_address' => __( 'Dirección fiscal', 'allpacode-gestion-de-reclamos-peru' ),
			'trade_name'       => __( 'Nombre comercial', 'allpacode-gestion-de-reclamos-peru' ),
			'admin_response'   => __( 'Respuesta al consumidor', 'allpacode-gestion-de-reclamos-peru' ),
			'internal_notes'   => __( 'Notas internas', 'allpacode-gestion-de-reclamos-peru' ),
			'ip_address'       => __( 'Dirección IP', 'allpacode-gestion-de-reclamos-peru' ),
			'user_agent'       => __( 'Navegador del usuario', 'allpacode-gestion-de-reclamos-peru' ),
			'consent'          => __( 'Aceptó tratamiento de datos', 'allpacode-gestion-de-reclamos-peru' ),
			'created_at'       => __( 'Fecha de registro', 'allpacode-gestion-de-reclamos-peru' ),
			'updated_at'       => __( 'Fecha de actualización', 'allpacode-gestion-de-reclamos-peru' ),
			'responded_at'     => __( 'Fecha de respuesta', 'allpacode-gestion-de-reclamos-peru' ),
			'deleted_at'       => __( 'Fecha de eliminación lógica', 'allpacode-gestion-de-reclamos-peru' ),
		);
	}
}
