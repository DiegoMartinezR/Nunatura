<?php
/**
 * REST API.
 *
 * @package AllpaCodeGestionReclamos
 */

namespace AllpaCodeGestionReclamos;

use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class RestController {
	public function register_routes(): void {
		register_rest_route(
			'allpacode-gr/v1',
			'/claims',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'claims' ),
				'permission_callback' => array( $this, 'can_manage' ),
			)
		);

		register_rest_route(
			'allpacode-gr/v1',
			'/claims/(?P<code>[A-Za-z0-9\-]+)',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'claim' ),
				'permission_callback' => array( $this, 'can_manage' ),
			)
		);
	}

	public function claims( WP_REST_Request $request ): WP_REST_Response {
		$result = ( new Repository() )->query(
			array(
				'page'     => max( 1, (int) $request->get_param( 'page' ) ),
				'per_page' => min( 100, max( 1, (int) ( $request->get_param( 'per_page' ) ?: 20 ) ) ),
				'status'   => sanitize_key( $request->get_param( 'status' ) ),
				'type'     => sanitize_key( $request->get_param( 'type' ) ),
				'search'   => sanitize_text_field( $request->get_param( 'search' ) ),
			)
		);

		return new WP_REST_Response( $result );
	}

	public function claim( WP_REST_Request $request ): WP_REST_Response|WP_Error {
		$claim = ( new Repository() )->find_by_code( sanitize_text_field( $request['code'] ) );

		if ( ! $claim ) {
			return new WP_Error( 'not_found', __( 'Caso no encontrado.', 'allpacode-gestion-de-reclamos-peru' ), array( 'status' => 404 ) );
		}

		$public = array(
			'code'       => $claim['code'],
			'type'       => $claim['type'],
			'status'     => $claim['status'],
			'created_at' => $claim['created_at'],
			'updated_at' => $claim['updated_at'],
		);

		return new WP_REST_Response( $public );
	}

	public function can_manage(): bool {
		return current_user_can( Capability::MANAGE );
	}
}
