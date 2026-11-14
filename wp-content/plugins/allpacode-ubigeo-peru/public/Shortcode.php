<?php
/**
 * Shortcode renderer.
 *
 * @package AllpaCodeUbigeoPeru
 */

namespace AllpaCodeUbigeoPeru\PublicApp;

use AllpaCodeUbigeoPeru\Repository;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Shortcode {
	public function hooks(): void {
		add_shortcode( 'allpacode_ubigeo_peru', array( $this, 'render' ) );
	}

	public function render( array $atts = array() ): string {
		Assets::register_assets();
		wp_enqueue_script( 'allpacode-ubigeo-public' );

		$atts = shortcode_atts(
			array(
				'name_prefix' => 'allpacode_ubigeo',
				'required'    => 'false',
				'class'       => '',
			),
			$atts,
			'allpacode_ubigeo_peru'
		);

		$departments = ( new Repository() )->departments();
		ob_start();
		include ALLPACODE_UBIGEO_PATH . 'templates/selects.php';
		return (string) ob_get_clean();
	}
}
