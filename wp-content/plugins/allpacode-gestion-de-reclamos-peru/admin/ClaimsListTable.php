<?php
/**
 * Claims list table.
 *
 * @package AllpaCodeGestionReclamos
 */

namespace AllpaCodeGestionReclamos\Admin;

use AllpaCodeGestionReclamos\Repository;
use AllpaCodeGestionReclamos\Utils;
use WP_List_Table;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

// phpcs:disable WordPress.Security.NonceVerification.Recommended -- Read-only list table filters and sorting parameters.

final class ClaimsListTable extends WP_List_Table {
	public function get_columns(): array {
		return array(
			'code'          => __( 'Código', 'allpacode-gestion-de-reclamos-peru' ),
			'type'          => __( 'Clase', 'allpacode-gestion-de-reclamos-peru' ),
			'consumer_name' => __( 'Consumidor', 'allpacode-gestion-de-reclamos-peru' ),
			'email'         => __( 'Correo', 'allpacode-gestion-de-reclamos-peru' ),
			'status'        => __( 'Estado', 'allpacode-gestion-de-reclamos-peru' ),
			'created_at'    => __( 'Fecha', 'allpacode-gestion-de-reclamos-peru' ),
		);
	}

	public function get_sortable_columns(): array {
		return array(
			'code'          => array( 'code', false ),
			'consumer_name' => array( 'consumer_name', false ),
			'status'        => array( 'status', false ),
			'created_at'    => array( 'created_at', true ),
		);
	}

	public function prepare_items(): void {
		$per_page = 20;
		$args     = array(
			'page'     => $this->get_pagenum(),
			'per_page' => $per_page,
			'status'   => sanitize_key( wp_unslash( $_GET['status'] ?? '' ) ),
			'type'     => sanitize_key( wp_unslash( $_GET['type'] ?? '' ) ),
			'search'   => sanitize_text_field( wp_unslash( $_REQUEST['s'] ?? '' ) ),
			'orderby'  => sanitize_key( wp_unslash( $_GET['orderby'] ?? 'created_at' ) ),
			'order'    => sanitize_key( wp_unslash( $_GET['order'] ?? 'DESC' ) ),
		);

		$result       = ( new Repository() )->query( $args );
		$this->items  = $result['items'];
		$this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );
		$this->set_pagination_args(
			array(
				'total_items' => $result['total'],
				'per_page'    => $per_page,
			)
		);
	}

	public function column_code( array $item ): string {
		$url = admin_url( 'admin.php?page=allpacode-gr&claim_id=' . absint( $item['id'] ) );
		return '<strong><a href="' . esc_url( $url ) . '">' . esc_html( $item['code'] ) . '</a></strong>';
	}

	public function column_type( array $item ): string {
		return esc_html( Utils::types()[ $item['type'] ] ?? $item['type'] );
	}

	public function column_status( array $item ): string {
		$status = Utils::statuses()[ $item['status'] ] ?? $item['status'];
		return '<span class="allpacode-admin-badge allpacode-admin-badge--' . esc_attr( $item['status'] ) . '">' . esc_html( $status ) . '</span>';
	}

	public function column_default( $item, $column_name ): string {
		return esc_html( (string) ( $item[ $column_name ] ?? '' ) );
	}
}
