<?php
/**
 * Ubigeo data repository.
 *
 * @package AllpaCodeUbigeoPeru
 */

namespace AllpaCodeUbigeoPeru;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Repository {
	private static ?array $data = null;

	public function data(): array {
		if ( null !== self::$data ) {
			return self::$data;
		}

		$file = ALLPACODE_UBIGEO_PATH . 'data/ubigeo-peru.json';
		$json = is_readable( $file ) ? file_get_contents( $file ) : '';
		$data = $json ? json_decode( $json, true ) : array();

		self::$data = is_array( $data ) ? $data : array(
			'departments' => array(),
			'provinces'   => array(),
			'districts'   => array(),
		);

		return self::$data;
	}

	public function departments(): array {
		return $this->data()['departments'] ?? array();
	}

	public function provinces( string $department_code ): array {
		$department_code = $this->normalize_code( $department_code, 2 );

		return array_values(
			array_filter(
				$this->data()['provinces'] ?? array(),
				static fn( array $province ): bool => $province['department_code'] === $department_code
			)
		);
	}

	public function districts( string $province_code ): array {
		$province_code = $this->normalize_code( $province_code, 4 );

		return array_values(
			array_filter(
				$this->data()['districts'] ?? array(),
				static fn( array $district ): bool => $district['province_code'] === $province_code
			)
		);
	}

	public function find( string $ubigeo_code ): ?array {
		$ubigeo_code = $this->normalize_code( $ubigeo_code, 6 );

		foreach ( $this->data()['districts'] ?? array() as $district ) {
			if ( $district['code'] === $ubigeo_code ) {
				return $this->with_parents( $district );
			}
		}

		return null;
	}

	public function with_parents( array $district ): array {
		$department = null;
		$province   = null;

		foreach ( $this->departments() as $item ) {
			if ( $item['code'] === $district['department_code'] ) {
				$department = $item;
				break;
			}
		}

		foreach ( $this->data()['provinces'] ?? array() as $item ) {
			if ( $item['code'] === $district['province_code'] ) {
				$province = $item;
				break;
			}
		}

		$district['department'] = $department;
		$district['province']   = $province;

		return $district;
	}

	private function normalize_code( string $code, int $length ): string {
		$code = preg_replace( '/\D+/', '', $code ) ?: '';
		return str_pad( substr( $code, 0, $length ), $length, '0', STR_PAD_LEFT );
	}
}
