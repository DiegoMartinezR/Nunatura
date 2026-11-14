<?php
/**
 * WooCommerce checkout integration.
 *
 * @package AllpaCodeUbigeoPeru
 */

namespace AllpaCodeUbigeoPeru;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class WooCommerceIntegration {
	public function enqueue_assets(): void {
		if ( ! Settings::get( 'enable_woocommerce', false ) || ! function_exists( 'is_checkout' ) || ! is_checkout() ) {
			return;
		}

		\AllpaCodeUbigeoPeru\PublicApp\Assets::register_assets();
		wp_enqueue_script( 'allpacode-ubigeo-public' );
	}

	public function checkout_fields( array $fields ): array {
		if ( ! Settings::get( 'enable_woocommerce', false ) ) {
			return $fields;
		}

		$fields['billing']['billing_allpacode_department'] = array(
			'type'     => 'select',
			'label'    => __( 'Departamento / Región', 'allpacode-ubigeo-peru' ),
			'required' => false,
			'class'    => array( 'form-row-first', 'allpacode-ubigeo-wc-field' ),
			'options'  => $this->options( ( new Repository() )->departments(), __( 'Selecciona un departamento', 'allpacode-ubigeo-peru' ) ),
			'priority' => 61,
		);
		$fields['billing']['billing_allpacode_province'] = array(
			'type'     => 'select',
			'label'    => __( 'Provincia', 'allpacode-ubigeo-peru' ),
			'required' => false,
			'class'    => array( 'form-row-last', 'allpacode-ubigeo-wc-field' ),
			'options'  => array( '' => __( 'Selecciona una provincia', 'allpacode-ubigeo-peru' ) ),
			'priority' => 62,
		);
		$fields['billing']['billing_allpacode_district'] = array(
			'type'     => 'select',
			'label'    => __( 'Distrito', 'allpacode-ubigeo-peru' ),
			'required' => false,
			'class'    => array( 'form-row-first', 'allpacode-ubigeo-wc-field' ),
			'options'  => array( '' => __( 'Selecciona un distrito', 'allpacode-ubigeo-peru' ) ),
			'priority' => 63,
		);
		$fields['billing']['billing_allpacode_ubigeo'] = array(
			'type'     => 'hidden',
			'required' => false,
			'priority' => 64,
		);

		return $fields;
	}

	public function save_order_meta( int $order_id ): void {
		// phpcs:disable WordPress.Security.NonceVerification.Missing -- WooCommerce validates checkout submission before this hook runs.
		if ( isset( $_POST['woocommerce-process-checkout-nonce'] ) && ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['woocommerce-process-checkout-nonce'] ) ), 'woocommerce-process_checkout' ) ) {
			return;
		}

		$fields = array(
			'billing_allpacode_department',
			'billing_allpacode_province',
			'billing_allpacode_district',
			'billing_allpacode_ubigeo',
		);

		foreach ( $fields as $field ) {
			if ( isset( $_POST[ $field ] ) ) {
				update_post_meta( $order_id, '_' . $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
			}
		}
		// phpcs:enable WordPress.Security.NonceVerification.Missing
	}

	private function options( array $items, string $placeholder ): array {
		$options = array( '' => $placeholder );
		foreach ( $items as $item ) {
			$options[ $item['code'] ] = $item['name'];
		}

		return $options;
	}
}
