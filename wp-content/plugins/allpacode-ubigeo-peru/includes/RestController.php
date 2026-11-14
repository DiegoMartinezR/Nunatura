<?php
/**
 * REST API.
 *
 * @package AllpaCodeUbigeoPeru
 */

namespace AllpaCodeUbigeoPeru;

use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class RestController {
	public function register_routes(): void {
		register_rest_route(
			'allpacode-ubigeo/v1',
			'/departamentos',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'departments' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			'allpacode-ubigeo/v1',
			'/provincias',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'provinces' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			'allpacode-ubigeo/v1',
			'/distritos',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'districts' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			'allpacode-ubigeo/v1',
			'/ubicacion/(?P<code>\d{6})',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'location' ),
				'permission_callback' => '__return_true',
			)
		);
	}

	public function departments(): WP_REST_Response {
		return new WP_REST_Response( ( new Repository() )->departments() );
	}

	public function provinces( WP_REST_Request $request ): WP_REST_Response {
		return new WP_REST_Response( ( new Repository() )->provinces( sanitize_text_field( $request->get_param( 'departamento' ) ) ) );
	}

	public function districts( WP_REST_Request $request ): WP_REST_Response {
		return new WP_REST_Response( ( new Repository() )->districts( sanitize_text_field( $request->get_param( 'provincia' ) ) ) );
	}

	public function location( WP_REST_Request $request ): WP_REST_Response|WP_Error {
		$item = ( new Repository() )->find( sanitize_text_field( $request['code'] ) );

		if ( ! $item ) {
			return new WP_Error( 'not_found', __( 'Ubigeo no encontrado.', 'allpacode-ubigeo-peru' ), array( 'status' => 404 ) );
		}

		return new WP_REST_Response( $item );
	}
}
