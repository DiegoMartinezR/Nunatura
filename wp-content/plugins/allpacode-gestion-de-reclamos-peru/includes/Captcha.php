<?php
/**
 * Captcha verification.
 *
 * @package AllpaCodeGestionReclamos
 */

namespace AllpaCodeGestionReclamos;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Captcha {
	public function verify( string $token ): bool {
		$provider = Settings::get( 'captcha_provider', 'none' );
		$secret   = Settings::get( 'captcha_secret_key', '' );

		if ( 'none' === $provider || ! $secret ) {
			return true;
		}

		$url = 'turnstile' === $provider
			? 'https://challenges.cloudflare.com/turnstile/v0/siteverify'
			: 'https://www.google.com/recaptcha/api/siteverify';

		$response = wp_remote_post(
			$url,
			array(
				'timeout' => 8,
				'body'    => array(
					'secret'   => $secret,
					'response' => $token,
					'remoteip' => Utils::ip_address(),
				),
			)
		);

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		return ! empty( $body['success'] );
	}
}
