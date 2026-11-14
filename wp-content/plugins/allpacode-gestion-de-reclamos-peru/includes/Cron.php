<?php
/**
 * Retention cleanup.
 *
 * @package AllpaCodeGestionReclamos
 */

namespace AllpaCodeGestionReclamos;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter

final class Cron {
	public function cleanup(): void {
		global $wpdb;

		$years = max( 2, (int) Settings::get( 'retention_years', 2 ) );
		$table = Repository::claims_table();
		$date  = gmdate( 'Y-m-d H:i:s', strtotime( '-' . $years . ' years' ) );

		$wpdb->query(
			$wpdb->prepare(
				"UPDATE {$table} SET deleted_at = %s WHERE deleted_at IS NULL AND created_at < %s",
				current_time( 'mysql' ),
				$date
			)
		);
	}
}
