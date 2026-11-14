<?php
/**
 * Classic WordPress widget.
 *
 * @package AllpaCodeGestionReclamos
 */

namespace AllpaCodeGestionReclamos\PublicApp;

use WP_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class ClassicWidget extends WP_Widget {
	public function __construct() {
		parent::__construct(
			'allpacode_gr_widget',
			__( 'Gestión de Reclamos', 'allpacode-gestion-de-reclamos-peru' ),
			array( 'description' => __( 'Muestra el formulario del Gestión de Reclamos.', 'allpacode-gestion-de-reclamos-peru' ) )
		);
	}

	public function widget( $args, $instance ): void {
		echo wp_kses_post( $args['before_widget'] ?? '' );
		echo do_shortcode( '[allpacode_gestion_reclamos compact="true"]' );
		echo wp_kses_post( $args['after_widget'] ?? '' );
	}
}
