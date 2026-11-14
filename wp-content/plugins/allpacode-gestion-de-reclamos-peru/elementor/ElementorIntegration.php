<?php
/**
 * Elementor integration.
 *
 * @package AllpaCodeGestionReclamos
 */

namespace AllpaCodeGestionReclamos\Elementor;

use AllpaCodeGestionReclamos\PublicApp\Assets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class ElementorIntegration {
	public function hooks(): void {
		add_action( 'elementor/widgets/register', array( $this, 'register_widget' ) );
		add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'enqueue_assets' ) );
		add_action( 'elementor/frontend/after_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	public function register_widget( $widgets_manager ): void {
		if ( ! class_exists( '\Elementor\Widget_Base' ) ) {
			return;
		}

		$widgets_manager->register( new ElementorWidget() );
	}

	public function enqueue_assets(): void {
		Assets::register_assets();
		wp_enqueue_style( 'allpacode-gr-public' );
		wp_enqueue_script( 'allpacode-gr-public' );
	}
}
