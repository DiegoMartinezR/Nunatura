<?php
/**
 * Settings storage.
 *
 * @package AllpaCodeUbigeoPeru
 */

namespace AllpaCodeUbigeoPeru;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Settings {
	public const OPTION = 'allpacode_ubigeo_settings';

	public static function defaults(): array {
		return array(
			'enable_woocommerce' => false,
			'address_mode'       => 'append',
			'default_department' => '',
			'load_styles'        => true,
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
		return array(
			'enable_woocommerce' => ! empty( $input['enable_woocommerce'] ),
			'address_mode'       => in_array( $input['address_mode'] ?? 'append', array( 'append', 'replace' ), true ) ? $input['address_mode'] : 'append',
			'default_department' => preg_match( '/^\d{2}$/', (string) ( $input['default_department'] ?? '' ) ) ? (string) $input['default_department'] : '',
			'load_styles'        => ! empty( $input['load_styles'] ),
		);
	}
}
