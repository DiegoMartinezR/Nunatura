<?php
/**
 * Data access layer.
 *
 * @package AllpaCodeGestionReclamos
 */

namespace AllpaCodeGestionReclamos;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQL.NotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter

final class Repository {
	public static function claims_table(): string {
		global $wpdb;
		return $wpdb->base_prefix . 'allpacode_gr_reclamos';
	}

	public static function logs_table(): string {
		global $wpdb;
		return $wpdb->base_prefix . 'allpacode_gr_reclamos_logs';
	}

	public function create_claim( array $data ): int {
		global $wpdb;

		$year   = (int) current_time( 'Y' );
		$number = $this->next_number( $year );
		$code   = $this->build_code( $year, $number );
		$now    = current_time( 'mysql' );

		$row = wp_parse_args(
			$data,
			array(
				'code'          => $code,
				'claim_year'    => $year,
				'claim_number'  => $number,
				'status'        => 'pending',
				'created_at'    => $now,
				'updated_at'    => $now,
				'admin_response'=> null,
				'internal_notes'=> null,
				'responded_at'  => null,
				'deleted_at'    => null,
			)
		);

		$wpdb->insert( self::claims_table(), $row );

		$id = (int) $wpdb->insert_id;
		$this->log( $id, 'created', __( 'Reclamo creado desde formulario público.', 'allpacode-gestion-de-reclamos-peru' ), $row );

		return $id;
	}

	public function update_claim( int $id, array $data, string $action = 'updated' ): bool {
		global $wpdb;

		$data['updated_at'] = current_time( 'mysql' );

		$updated = false !== $wpdb->update(
			self::claims_table(),
			$data,
			array( 'id' => $id ),
			null,
			array( '%d' )
		);

		if ( $updated ) {
			$this->log( $id, $action, sprintf( 'Caso #%d actualizado.', $id ), $data );
		}

		return $updated;
	}

	public function find( int $id ): ?array {
		global $wpdb;
		$table = self::claims_table();
		$row   = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE id = %d AND deleted_at IS NULL", $id ), ARRAY_A );

		return $row ?: null;
	}

	public function find_by_code( string $code ): ?array {
		global $wpdb;
		$table = self::claims_table();
		$row   = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE code = %s AND deleted_at IS NULL", $code ), ARRAY_A );

		return $row ?: null;
	}

	public function query( array $args = array() ): array {
		global $wpdb;

		$args = wp_parse_args(
			$args,
			array(
				'page'     => 1,
				'per_page' => 20,
				'status'   => '',
				'type'     => '',
				'search'   => '',
				'from'     => '',
				'to'       => '',
				'orderby'  => 'created_at',
				'order'    => 'DESC',
			)
		);

		$table  = self::claims_table();
		$where  = array( 'deleted_at IS NULL' );
		$params = array();

		if ( $args['status'] ) {
			$where[]  = 'status = %s';
			$params[] = $args['status'];
		}
		if ( $args['type'] ) {
			$where[]  = 'type = %s';
			$params[] = $args['type'];
		}
		if ( $args['from'] ) {
			$where[]  = 'created_at >= %s';
			$params[] = $args['from'] . ' 00:00:00';
		}
		if ( $args['to'] ) {
			$where[]  = 'created_at <= %s';
			$params[] = $args['to'] . ' 23:59:59';
		}
		if ( $args['search'] ) {
			$like     = '%' . $wpdb->esc_like( $args['search'] ) . '%';
			$where[]  = '(code LIKE %s OR consumer_name LIKE %s OR document_number LIKE %s OR email LIKE %s)';
			$params[] = $like;
			$params[] = $like;
			$params[] = $like;
			$params[] = $like;
		}

		$allowed_orderby = array( 'created_at', 'code', 'status', 'type', 'consumer_name' );
		$orderby         = in_array( $args['orderby'], $allowed_orderby, true ) ? $args['orderby'] : 'created_at';
		$order           = 'ASC' === strtoupper( $args['order'] ) ? 'ASC' : 'DESC';
		$offset          = max( 0, ( (int) $args['page'] - 1 ) * (int) $args['per_page'] );

		$where_sql = implode( ' AND ', $where );
		$sql       = "SELECT SQL_CALC_FOUND_ROWS * FROM {$table} WHERE {$where_sql} ORDER BY {$orderby} {$order} LIMIT %d OFFSET %d";
		$params[]  = (int) $args['per_page'];
		$params[]  = $offset;

		$items = $wpdb->get_results( $wpdb->prepare( $sql, $params ), ARRAY_A );
		$total = (int) $wpdb->get_var( 'SELECT FOUND_ROWS()' );

		return array(
			'items' => $items,
			'total' => $total,
		);
	}

	public function stats(): array {
		global $wpdb;
		$table = self::claims_table();

		$rows = $wpdb->get_results(
			"SELECT status, COUNT(*) total FROM {$table} WHERE deleted_at IS NULL GROUP BY status",
			ARRAY_A
		);

		$stats = array(
			'total'     => 0,
			'pending'   => 0,
			'reviewing' => 0,
			'answered'  => 0,
			'closed'    => 0,
		);

		foreach ( $rows as $row ) {
			$stats[ $row['status'] ] = (int) $row['total'];
			$stats['total']        += (int) $row['total'];
		}

		return $stats;
	}

	public function log( ?int $claim_id, string $action, string $message, array $context = array() ): void {
		global $wpdb;

		$wpdb->insert(
			self::logs_table(),
			array(
				'claim_id'   => $claim_id,
				'user_id'    => get_current_user_id() ?: null,
				'action'     => sanitize_key( $action ),
				'message'    => sanitize_text_field( $message ),
				'context'    => wp_json_encode( $context ),
				'ip_address' => Utils::ip_address(),
				'created_at' => current_time( 'mysql' ),
			)
		);
	}

	public function logs( int $claim_id = 0, int $limit = 100 ): array {
		global $wpdb;
		$table = self::logs_table();

		if ( $claim_id ) {
			return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$table} WHERE claim_id = %d ORDER BY created_at DESC LIMIT %d", $claim_id, $limit ), ARRAY_A );
		}

		return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$table} ORDER BY created_at DESC LIMIT %d", $limit ), ARRAY_A );
	}

	public function delete_all_claims(): array {
		global $wpdb;

		$claims_table = self::claims_table();
		$logs_table   = self::logs_table();
		$claims_count = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$claims_table}" );
		$logs_count   = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$logs_table}" );

		$wpdb->query( "TRUNCATE TABLE {$logs_table}" );
		$wpdb->query( "TRUNCATE TABLE {$claims_table}" );

		return array(
			'claims' => $claims_count,
			'logs'   => $logs_count,
		);
	}

	private function next_number( int $year ): int {
		global $wpdb;
		$table = self::claims_table();
		$max   = (int) $wpdb->get_var( $wpdb->prepare( "SELECT MAX(claim_number) FROM {$table} WHERE claim_year = %d", $year ) );

		return $max + 1;
	}

	private function build_code( int $year, int $number ): string {
		$prefix = strtoupper( sanitize_key( (string) Settings::get( 'prefix', 'LR' ) ) );
		$code   = sprintf( '%s-%d-%06d', $prefix, $year, $number );

		return apply_filters( 'allpacode_gr_claim_code', $code, $year, $number );
	}
}
