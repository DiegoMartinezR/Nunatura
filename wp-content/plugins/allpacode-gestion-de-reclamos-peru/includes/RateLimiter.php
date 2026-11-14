<?php
/**
 * Basic IP rate limit.
 *
 * @package AllpaCodeGestionReclamos
 */

namespace AllpaCodeGestionReclamos;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class RateLimiter {
	private const LIMIT = 20;

	public function too_many_attempts(): bool {
		$key      = 'allpacode_gr_rate_' . md5( Utils::ip_address() );
		$attempts = (int) get_transient( $key );

		return $attempts >= self::LIMIT;
	}

	public function record_attempt(): void {
		$key      = 'allpacode_gr_rate_' . md5( Utils::ip_address() );
		$attempts = (int) get_transient( $key );

		set_transient( $key, $attempts + 1, 10 * MINUTE_IN_SECONDS );
	}
}
