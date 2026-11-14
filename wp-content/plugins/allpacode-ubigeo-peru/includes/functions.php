<?php
/**
 * Public helper functions.
 *
 * @package AllpaCodeUbigeoPeru
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function allpacode_ubigeo_get_departamentos(): array {
	return ( new \AllpaCodeUbigeoPeru\Repository() )->departments();
}

function allpacode_ubigeo_get_provincias( string $department_code ): array {
	return ( new \AllpaCodeUbigeoPeru\Repository() )->provinces( $department_code );
}

function allpacode_ubigeo_get_distritos( string $province_code ): array {
	return ( new \AllpaCodeUbigeoPeru\Repository() )->districts( $province_code );
}

function allpacode_ubigeo_get_by_code( string $ubigeo_code ): ?array {
	return ( new \AllpaCodeUbigeoPeru\Repository() )->find( $ubigeo_code );
}

function allpacode_ubigeo_is_available(): bool {
	return true;
}
