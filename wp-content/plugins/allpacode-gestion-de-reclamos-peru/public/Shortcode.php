<?php
/**
 * Shortcode renderer.
 *
 * @package AllpaCodeGestionReclamos
 */

namespace AllpaCodeGestionReclamos\PublicApp;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Shortcode {
	public function hooks(): void {
		add_shortcode( 'allpacode_gestion_reclamos', array( $this, 'render' ) );
	}

	public function render( array $atts = array() ): string {
		wp_enqueue_style( 'allpacode-gr-public' );
		wp_enqueue_script( 'allpacode-gr-public' );

		$atts = shortcode_atts(
			array(
				'theme'      => 'business',
				'color'      => '',
				'show_title' => 'true',
				'compact'    => 'false',
				'darkmode'   => 'false',
			),
			$atts,
			'allpacode_gestion_reclamos'
		);

		$atts['theme']      = sanitize_key( (string) $atts['theme'] );
		$atts['color']      = sanitize_hex_color( (string) $atts['color'] ) ?: '';
		$atts['show_title'] = 'false' === (string) $atts['show_title'] ? 'false' : 'true';
		$atts['compact']    = 'true' === (string) $atts['compact'] ? 'true' : 'false';
		$atts['darkmode']   = 'true' === (string) $atts['darkmode'] ? 'true' : 'false';

		ob_start();
		include ALLPACODE_GR_PATH . 'templates/form.php';
		return (string) ob_get_clean();
	}
}
