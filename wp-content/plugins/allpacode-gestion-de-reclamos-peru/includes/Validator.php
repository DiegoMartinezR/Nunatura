<?php
/**
 * Claim validation and sanitization.
 *
 * @package AllpaCodeGestionReclamos
 */

namespace AllpaCodeGestionReclamos;

use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Validator {
	private const LIMITS = array(
		'consumer_name'    => array( 3, 190 ),
		'document_number'  => array( 6, 20 ),
		'phone'            => array( 6, 30 ),
		'email'            => array( 6, 190 ),
		'address'          => array( 5, 600 ),
		'product_service'  => array( 3, 190 ),
		'description'      => array( 20, 5000 ),
		'consumer_request' => array( 10, 3000 ),
	);

	public function sanitize_claim( array $input ): array|WP_Error {
		$required = array(
			'consumer_name',
			'document_type',
			'document_number',
			'phone',
			'email',
			'address',
			'type',
			'product_service',
			'description',
			'consumer_request',
			'consent',
		);

		$data = array(
			'consumer_name'    => sanitize_text_field( $input['consumer_name'] ?? '' ),
			'document_type'    => sanitize_text_field( $input['document_type'] ?? '' ),
			'document_number'  => sanitize_text_field( $input['document_number'] ?? '' ),
			'phone'            => sanitize_text_field( $input['phone'] ?? '' ),
			'email'            => sanitize_email( $input['email'] ?? '' ),
			'address'          => sanitize_textarea_field( $input['address'] ?? '' ),
			'department_code'  => sanitize_text_field( $input['allpacode_ubigeo_department'] ?? '' ),
			'department_name'  => '',
			'province_code'    => sanitize_text_field( $input['allpacode_ubigeo_province'] ?? '' ),
			'province_name'    => '',
			'district_code'    => sanitize_text_field( $input['allpacode_ubigeo_district'] ?? '' ),
			'district_name'    => '',
			'ubigeo_code'      => sanitize_text_field( $input['allpacode_ubigeo_code'] ?? '' ),
			'type'             => sanitize_key( $input['type'] ?? '' ),
			'product_service'  => sanitize_text_field( $input['product_service'] ?? '' ),
			'claimed_amount'   => isset( $input['claimed_amount'] ) && '' !== $input['claimed_amount'] ? (float) $input['claimed_amount'] : null,
			'description'      => sanitize_textarea_field( $input['description'] ?? '' ),
			'consumer_request' => sanitize_textarea_field( $input['consumer_request'] ?? '' ),
			'consent'          => ! empty( $input['consent'] ) ? 1 : 0,
			'business_name'    => sanitize_text_field( Settings::get( 'business_name' ) ),
			'business_ruc'     => sanitize_text_field( Settings::get( 'business_ruc' ) ),
			'business_address' => sanitize_textarea_field( Settings::get( 'business_address' ) ),
			'trade_name'       => sanitize_text_field( Settings::get( 'trade_name' ) ),
			'ip_address'       => Utils::ip_address(),
			'user_agent'       => Utils::user_agent(),
		);

		$errors = new WP_Error();

		foreach ( $required as $field ) {
			if ( empty( $data[ $field ] ) ) {
				$errors->add( $field, __( 'Este campo es obligatorio.', 'allpacode-gestion-de-reclamos-peru' ) );
			}
		}

		if ( ! is_email( $data['email'] ) ) {
			$errors->add( 'email', __( 'Ingresa un correo electrónico válido.', 'allpacode-gestion-de-reclamos-peru' ) );
		}

		if ( ! in_array( $data['document_type'], array( 'DNI', 'CE', 'RUC', 'PAS' ), true ) ) {
			$errors->add( 'document_type', __( 'Selecciona un tipo de documento válido.', 'allpacode-gestion-de-reclamos-peru' ) );
		}

		if ( ! preg_match( '/^[A-Za-z0-9\-\.]+$/', $data['document_number'] ) ) {
			$errors->add( 'document_number', __( 'Ingresa un número de documento válido.', 'allpacode-gestion-de-reclamos-peru' ) );
		}

		if ( ! preg_match( '/^[0-9\+\-\s\(\)]+$/', $data['phone'] ) ) {
			$errors->add( 'phone', __( 'Ingresa un teléfono válido.', 'allpacode-gestion-de-reclamos-peru' ) );
		}

		if ( ! isset( Utils::types()[ $data['type'] ] ) ) {
			$errors->add( 'type', __( 'Selecciona un tipo válido.', 'allpacode-gestion-de-reclamos-peru' ) );
		}

		if ( function_exists( 'allpacode_ubigeo_is_available' ) ) {
			if ( ! preg_match( '/^\d{2}$/', $data['department_code'] ) || ! preg_match( '/^\d{4}$/', $data['province_code'] ) || ! preg_match( '/^\d{6}$/', $data['district_code'] ) ) {
				$errors->add( 'ubigeo_code', __( 'Selecciona departamento, provincia y distrito válidos.', 'allpacode-gestion-de-reclamos-peru' ) );
			} else {
				$location = allpacode_ubigeo_get_by_code( $data['district_code'] );
				if ( ! $location || $location['province_code'] !== $data['province_code'] || $location['department_code'] !== $data['department_code'] ) {
					$errors->add( 'ubigeo_code', __( 'La combinación de departamento, provincia y distrito no es válida.', 'allpacode-gestion-de-reclamos-peru' ) );
				} else {
					$data['department_name'] = sanitize_text_field( $location['department']['name'] ?? '' );
					$data['province_name']   = sanitize_text_field( $location['province']['name'] ?? '' );
					$data['district_name']   = sanitize_text_field( $location['name'] ?? '' );
					$data['ubigeo_code']     = sanitize_text_field( $location['code'] ?? $data['district_code'] );
				}
			}
		}

		if ( null !== $data['claimed_amount'] && ( $data['claimed_amount'] < 0 || $data['claimed_amount'] > 999999999.99 ) ) {
			$errors->add( 'claimed_amount', __( 'Ingresa un monto válido.', 'allpacode-gestion-de-reclamos-peru' ) );
		}

		foreach ( self::LIMITS as $field => $limits ) {
			$length = function_exists( 'mb_strlen' ) ? mb_strlen( (string) $data[ $field ] ) : strlen( (string) $data[ $field ] );
			if ( $length < $limits[0] ) {
				/* translators: %d: minimum number of characters required. */
				$errors->add( $field, sprintf( __( 'Debe tener al menos %d caracteres.', 'allpacode-gestion-de-reclamos-peru' ), $limits[0] ) );
			}
			if ( $length > $limits[1] ) {
				/* translators: %d: maximum number of characters allowed. */
				$errors->add( $field, sprintf( __( 'No debe superar %d caracteres.', 'allpacode-gestion-de-reclamos-peru' ), $limits[1] ) );
			}
		}

		if ( $errors->has_errors() ) {
			return $errors;
		}

		return apply_filters( 'allpacode_gr_sanitized_claim', $data, $input );
	}
}
