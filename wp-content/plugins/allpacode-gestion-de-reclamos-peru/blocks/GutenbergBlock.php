<?php
/**
 * Gutenberg block integration.
 *
 * @package AllpaCodeGestionReclamos
 */

namespace AllpaCodeGestionReclamos\Blocks;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class GutenbergBlock {
	public function hooks(): void {
		add_action( 'init', array( $this, 'register' ) );
	}

	public function register(): void {
		wp_register_script( 'allpacode-gr-block', ALLPACODE_GR_URL . 'assets/js/block.js', array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-block-editor' ), ALLPACODE_GR_VERSION, true );

		register_block_type(
			'allpacode/gestion-reclamos',
			array(
				'editor_script'   => 'allpacode-gr-block',
				'render_callback' => array( $this, 'render' ),
				'attributes'      => array(
					'showTitle' => array( 'type' => 'boolean', 'default' => true ),
				),
			)
		);
	}

	public function render( array $attributes = array() ): string {
		$show_title = ! array_key_exists( 'showTitle', $attributes ) || (bool) $attributes['showTitle'];
		$html       = do_shortcode( '[allpacode_gestion_reclamos show_title="' . ( $show_title ? 'true' : 'false' ) . '"]' );

		return wp_kses( $html, self::allowed_form_html() );
	}

	private static function allowed_form_html(): array {
		$form_attributes = array(
			'class'       => true,
			'id'          => true,
			'style'       => true,
			'method'      => true,
			'action'      => true,
			'enctype'     => true,
			'novalidate'  => true,
			'role'        => true,
			'aria-live'   => true,
			'aria-hidden' => true,
			'data-*'      => true,
		);

		$field_attributes = array(
			'class'        => true,
			'id'           => true,
			'name'         => true,
			'type'         => true,
			'value'        => true,
			'placeholder'  => true,
			'required'     => true,
			'checked'      => true,
			'selected'     => true,
			'disabled'     => true,
			'maxlength'    => true,
			'minlength'    => true,
			'min'          => true,
			'max'          => true,
			'step'         => true,
			'autocomplete' => true,
			'inputmode'    => true,
			'tabindex'     => true,
			'accept'       => true,
			'data-*'       => true,
		);

		return array(
			'section'  => $form_attributes,
			'form'     => $form_attributes,
			'header'   => $form_attributes,
			'div'      => $form_attributes,
			'p'        => $form_attributes,
			'h2'       => $form_attributes,
			'h3'       => $form_attributes,
			'label'    => $form_attributes,
			'span'     => $form_attributes,
			'strong'   => $form_attributes,
			'br'       => array(),
			'input'    => $field_attributes,
			'select'   => $field_attributes,
			'option'   => $field_attributes,
			'textarea' => $field_attributes,
			'button'   => $field_attributes,
			'a'        => array(
				'href'   => true,
				'target' => true,
				'rel'    => true,
				'class'  => true,
			),
		);
	}
}
