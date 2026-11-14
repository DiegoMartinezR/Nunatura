<?php
/**
 * Export handlers.
 *
 * @package AllpaCodeGestionReclamos
 */

namespace AllpaCodeGestionReclamos\Admin;

use AllpaCodeGestionReclamos\Capability;
use AllpaCodeGestionReclamos\Repository;
use AllpaCodeGestionReclamos\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Exporter {
	public function export(): void {
		if ( ! current_user_can( Capability::MANAGE ) ) {
			wp_die( esc_html__( 'No autorizado.', 'allpacode-gestion-de-reclamos-peru' ) );
		}

		check_admin_referer( 'allpacode_gr_export' );

		$format = sanitize_key( wp_unslash( $_GET['format'] ?? 'csv' ) );
		$args   = array(
			'per_page' => 9999,
			'status'   => sanitize_key( wp_unslash( $_GET['status'] ?? '' ) ),
			'type'     => sanitize_key( wp_unslash( $_GET['type'] ?? '' ) ),
			'from'     => sanitize_text_field( wp_unslash( $_GET['from'] ?? '' ) ),
			'to'       => sanitize_text_field( wp_unslash( $_GET['to'] ?? '' ) ),
		);
		$items  = ( new Repository() )->query( $args )['items'];

		if ( 'xls' === $format ) {
			$this->excel_html( $items );
			return;
		}

		if ( 'pdf' === $format ) {
			$this->html_pdf( $items );
			return;
		}

		$this->tabular( $items, 'text/csv; charset=utf-8', 'reclamos.csv', ',' );
	}

	private function tabular( array $items, string $type, string $filename, string $separator ): void {
		nocache_headers();
		header( 'Content-Type: ' . $type );
		header( 'Content-Disposition: attachment; filename=' . $filename );
		echo "\xEF\xBB\xBF";

		$columns = Utils::export_columns();
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CSV output is already normalized by clean_cell().
		echo implode( $separator, array_map( array( $this, 'clean_cell' ), array_values( $columns ) ) ) . "\n";

		foreach ( $items as $item ) {
			$row = array();
			foreach ( array_keys( $columns ) as $key ) {
				$value = $item[ $key ] ?? '';
				if ( 'type' === $key ) {
					$value = Utils::types()[ $value ] ?? $value;
				}
				if ( 'status' === $key ) {
					$value = Utils::statuses()[ $value ] ?? $value;
				}
				if ( 'consent' === $key ) {
					$value = (int) $value ? 'Sí' : 'No';
				}

				$row[] = $this->clean_cell( (string) $value, $separator );
			}
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CSV output is already normalized by clean_cell().
			echo implode( $separator, $row ) . "\n";
		}

		exit;
	}

	private function excel_html( array $items ): void {
		nocache_headers();
		header( 'Content-Type: application/vnd.ms-excel; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=reclamos.xls' );
		echo "\xEF\xBB\xBF";
		$this->html_table( $items );
		exit;
	}

	private function html_pdf( array $items ): void {
		nocache_headers();
		header( 'Content-Type: text/html; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=reclamos.html' );
		$this->html_table( $items );
		exit;
	}

	private function html_table( array $items ): void {
		$columns = Utils::export_columns();
		echo '<!doctype html><html><head><meta charset="utf-8"><title>Exportación del Gestión de Reclamos</title></head><body>';
		echo '<h1>Exportación del Gestión de Reclamos</h1><table border="1" cellpadding="6" cellspacing="0"><tr>';
		foreach ( $columns as $label ) {
			echo '<th>' . esc_html( $label ) . '</th>';
		}
		echo '</tr>';
		foreach ( $items as $item ) {
			echo '<tr>';
			foreach ( array_keys( $columns ) as $key ) {
				$value = $item[ $key ] ?? '';
				if ( 'type' === $key ) {
					$value = Utils::types()[ $value ] ?? $value;
				}
				if ( 'status' === $key ) {
					$value = Utils::statuses()[ $value ] ?? $value;
				}
				if ( 'consent' === $key ) {
					$value = (int) $value ? 'Sí' : 'No';
				}
				echo '<td>' . esc_html( wp_strip_all_tags( (string) $value ) ) . '</td>';
			}
			echo '</tr>';
		}
		echo '</table></body></html>';
	}

	private function clean_cell( string $value, string $separator = ',' ): string {
		$value = wp_strip_all_tags( $value );
		$value = html_entity_decode( $value, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
		$value = str_replace( array( "\r", "\n", "\t" ), ' ', $value );
		if ( ',' === $separator ) {
			$value = str_replace( ',', ' ', $value );
		}

		return $value;
	}
}
