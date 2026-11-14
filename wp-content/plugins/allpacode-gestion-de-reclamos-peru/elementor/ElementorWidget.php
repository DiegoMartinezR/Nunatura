<?php
/**
 * Elementor widget.
 *
 * @package AllpaCodeGestionReclamos
 */

namespace AllpaCodeGestionReclamos\Elementor;

use AllpaCodeGestionReclamos\PublicApp\Assets;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class ElementorWidget extends Widget_Base {
	public function get_name(): string {
		return 'allpacode_gestion_reclamos';
	}

	public function get_title(): string {
		return __( 'Gestión de Reclamos', 'allpacode-gestion-de-reclamos-peru' );
	}

	public function get_icon(): string {
		return 'eicon-form-horizontal';
	}

	public function get_categories(): array {
		return array( 'general' );
	}

	public function get_style_depends(): array {
		return array( 'allpacode-gr-public' );
	}

	public function get_script_depends(): array {
		return array( 'allpacode-gr-public' );
	}

	protected function register_controls(): void {
		$this->start_controls_section( 'content', array( 'label' => __( 'Contenido', 'allpacode-gestion-de-reclamos-peru' ) ) );
		$this->add_control( 'show_title', array( 'label' => __( 'Mostrar título', 'allpacode-gestion-de-reclamos-peru' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ) );
		$this->add_control( 'compact', array( 'label' => __( 'Compacto', 'allpacode-gestion-de-reclamos-peru' ), 'type' => Controls_Manager::SWITCHER ) );
		$this->add_control( 'darkmode', array( 'label' => __( 'Modo oscuro', 'allpacode-gestion-de-reclamos-peru' ), 'type' => Controls_Manager::SWITCHER ) );
		$this->end_controls_section();

		$this->start_controls_section( 'style', array( 'label' => __( 'Estilo', 'allpacode-gestion-de-reclamos-peru' ), 'tab' => Controls_Manager::TAB_STYLE ) );
		$this->add_control( 'color', array( 'label' => __( 'Color principal', 'allpacode-gestion-de-reclamos-peru' ), 'type' => Controls_Manager::COLOR, 'default' => '#1f2937' ) );
		$this->add_responsive_control( 'spacing', array( 'label' => __( 'Espaciado', 'allpacode-gestion-de-reclamos-peru' ), 'type' => Controls_Manager::SLIDER, 'selectors' => array( '{{WRAPPER}} .allpacode-gr' => 'padding: {{SIZE}}{{UNIT}};' ) ) );
		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'typography', 'selector' => '{{WRAPPER}} .allpacode-gr' ) );
		$this->add_group_control( Group_Control_Border::get_type(), array( 'name' => 'border', 'selector' => '{{WRAPPER}} .allpacode-gr' ) );
		$this->end_controls_section();
	}

	protected function render(): void {
		Assets::register_assets();
		wp_enqueue_style( 'allpacode-gr-public' );
		wp_enqueue_script( 'allpacode-gr-public' );

		$settings = $this->get_settings_for_display();
		$shortcode = sprintf(
			'[allpacode_gestion_reclamos color="%s" show_title="%s" compact="%s" darkmode="%s"]',
			esc_attr( $settings['color'] ?? '#1f2937' ),
			'yes' === ( $settings['show_title'] ?? 'yes' ) ? 'true' : 'false',
			'yes' === ( $settings['compact'] ?? '' ) ? 'true' : 'false',
			'yes' === ( $settings['darkmode'] ?? '' ) ? 'true' : 'false'
		);

		echo do_shortcode( $shortcode );
	}
}
