<?php
/**
 * Plugin settings.
 *
 * @package AllpaCodeGestionReclamos
 */

namespace AllpaCodeGestionReclamos;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Settings {
	public const OPTION = 'allpacode_gr_settings';

	public static function defaults(): array {
		return array(
			'business_name'       => get_bloginfo( 'name' ),
			'business_ruc'        => '',
			'business_address'    => '',
			'trade_name'          => get_bloginfo( 'name' ),
			'logo_url'            => '',
			'admin_email'         => get_option( 'admin_email' ),
			'prefix'              => 'LR',
			'retention_years'     => 2,
			'primary_color'       => '#1f2937',
			'accent_color'        => '#374151',
			'border_radius'       => 10,
			'dark_mode'           => false,
			'legal_text'          => 'Conforme al Código de Protección y Defensa del Consumidor, este registro constituye constancia de presentación del reclamo o queja.',
			'privacy_text'        => 'Acepto el tratamiento de mis datos personales para la atención de mi reclamo o queja.',
			'captcha_provider'    => 'none',
			'captcha_site_key'    => '',
			'captcha_secret_key'  => '',
			'webhook_url'         => '',
			'allow_uploads'       => true,
			'max_upload_mb'       => 5,
			'branding_visible'    => false,
			'email_customer'      => '<p>Hola {consumer_name}, recibimos tu {type_label} con código <strong>{code}</strong>.</p>',
			'email_admin'         => '<p>Nuevo registro en el Gestión de Reclamos: <strong>{code}</strong>.</p>',
			'email_response'      => '<p>Hola {consumer_name}, hemos respondido tu caso <strong>{code}</strong>.</p><p>Estado: <strong>{status_label}</strong></p><p>{response}</p>',
		);
	}

	public static function register_defaults(): void {
		$current = get_option( self::OPTION );
		if ( ! is_array( $current ) ) {
			add_option( self::OPTION, self::defaults() );
			return;
		}

		update_option( self::OPTION, wp_parse_args( $current, self::defaults() ) );
	}

	public static function get( ?string $key = null, mixed $default = null ): mixed {
		$settings = wp_parse_args( get_option( self::OPTION, array() ), self::defaults() );

		if ( null === $key ) {
			return $settings;
		}

		return $settings[ $key ] ?? $default;
	}

	public static function sanitize( array $input ): array {
		$defaults = wp_parse_args( get_option( self::OPTION, array() ), self::defaults() );
		$output   = array();

		foreach ( $defaults as $key => $default ) {
			$value = $input[ $key ] ?? $default;

			if ( is_bool( $default ) ) {
				$output[ $key ] = (bool) $value;
			} elseif ( in_array( $key, array( 'email_customer', 'email_admin', 'email_response', 'legal_text', 'privacy_text' ), true ) ) {
				$output[ $key ] = wp_kses_post( $value );
			} elseif ( false !== strpos( $key, 'email' ) ) {
				$output[ $key ] = sanitize_email( $value );
			} elseif ( in_array( $key, array( 'retention_years', 'border_radius', 'max_upload_mb' ), true ) ) {
				$output[ $key ] = absint( $value );
			} elseif ( in_array( $key, array( 'primary_color', 'accent_color' ), true ) ) {
				$output[ $key ] = sanitize_hex_color( $value ) ?: $default;
			} elseif ( in_array( $key, array( 'webhook_url', 'logo_url' ), true ) ) {
				$output[ $key ] = esc_url_raw( $value );
			} else {
				$output[ $key ] = sanitize_text_field( (string) $value );
			}
		}

		return $output;
	}
}
