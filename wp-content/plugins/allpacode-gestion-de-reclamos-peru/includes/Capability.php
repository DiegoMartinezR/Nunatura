<?php
/**
 * Role capabilities.
 *
 * @package AllpaCodeGestionReclamos
 */

namespace AllpaCodeGestionReclamos;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Capability {
	public const MANAGE = 'manage_allpacode_gr_reclamos';

	public static function register(): void {
		$roles = array( 'administrator', 'shop_manager' );

		foreach ( $roles as $role_name ) {
			$role = get_role( $role_name );
			if ( $role && ! $role->has_cap( self::MANAGE ) ) {
				$role->add_cap( self::MANAGE );
			}
		}
	}
}
