<?php
/**
 * Optional webhook dispatcher.
 *
 * @package AllpaCodeGestionReclamos
 */

namespace AllpaCodeGestionReclamos;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Webhook {
	public function dispatch( string $event, array $payload ): void {
		$url = Settings::get( 'webhook_url', '' );
		if ( ! $url ) {
			return;
		}

		wp_remote_post(
			$url,
			array(
				'timeout' => 5,
				'headers' => array( 'Content-Type' => 'application/json' ),
				'body'    => wp_json_encode(
					array(
						'event'   => $event,
						'payload' => $payload,
					)
				),
			)
		);
	}
}
