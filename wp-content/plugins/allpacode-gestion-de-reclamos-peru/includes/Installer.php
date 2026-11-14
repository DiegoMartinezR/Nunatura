<?php
/**
 * Activation, schema, cron.
 *
 * @package AllpaCodeGestionReclamos
 */

namespace AllpaCodeGestionReclamos;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Installer {
	public static function activate(): void {
		self::create_tables();
		Settings::register_defaults();
		Capability::register();

		if ( ! wp_next_scheduled( 'allpacode_gr_daily_cleanup' ) ) {
			wp_schedule_event( time() + HOUR_IN_SECONDS, 'daily', 'allpacode_gr_daily_cleanup' );
		}

		update_option( 'allpacode_gr_version', ALLPACODE_GR_VERSION );
	}

	public static function deactivate(): void {
		wp_clear_scheduled_hook( 'allpacode_gr_daily_cleanup' );
	}

	public static function create_tables(): void {
		global $wpdb;

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$charset = $wpdb->get_charset_collate();
		$claims  = Repository::claims_table();
		$logs    = Repository::logs_table();

		$sql_claims = "CREATE TABLE {$claims} (
			id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			code VARCHAR(40) NOT NULL,
			claim_year SMALLINT UNSIGNED NOT NULL,
			claim_number INT UNSIGNED NOT NULL,
			type VARCHAR(20) NOT NULL,
			status VARCHAR(30) NOT NULL DEFAULT 'pending',
			consumer_name VARCHAR(190) NOT NULL,
			document_type VARCHAR(30) NOT NULL,
			document_number VARCHAR(40) NOT NULL,
			phone VARCHAR(40) NOT NULL,
			email VARCHAR(190) NOT NULL,
			address TEXT NOT NULL,
			department_code VARCHAR(2) NULL,
			department_name VARCHAR(120) NULL,
			province_code VARCHAR(4) NULL,
			province_name VARCHAR(120) NULL,
			district_code VARCHAR(6) NULL,
			district_name VARCHAR(120) NULL,
			ubigeo_code VARCHAR(6) NULL,
			product_service VARCHAR(190) NOT NULL,
			claimed_amount DECIMAL(12,2) NULL,
			description LONGTEXT NOT NULL,
			consumer_request LONGTEXT NOT NULL,
			business_name VARCHAR(190) NOT NULL,
			business_ruc VARCHAR(20) NOT NULL,
			business_address TEXT NOT NULL,
			trade_name VARCHAR(190) NOT NULL,
			evidence_url TEXT NULL,
			admin_response LONGTEXT NULL,
			internal_notes LONGTEXT NULL,
			ip_address VARCHAR(80) NOT NULL,
			user_agent TEXT NOT NULL,
			consent TINYINT(1) NOT NULL DEFAULT 0,
			created_at DATETIME NOT NULL,
			updated_at DATETIME NOT NULL,
			responded_at DATETIME NULL,
			deleted_at DATETIME NULL,
			PRIMARY KEY  (id),
			UNIQUE KEY code (code),
			KEY status (status),
			KEY type (type),
			KEY claim_year (claim_year),
			KEY created_at (created_at),
			KEY document_number (document_number),
			KEY ubigeo_code (ubigeo_code)
		) {$charset};";

		$sql_logs = "CREATE TABLE {$logs} (
			id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			claim_id BIGINT UNSIGNED NULL,
			user_id BIGINT UNSIGNED NULL,
			action VARCHAR(80) NOT NULL,
			message TEXT NOT NULL,
			context LONGTEXT NULL,
			ip_address VARCHAR(80) NOT NULL,
			created_at DATETIME NOT NULL,
			PRIMARY KEY (id),
			KEY claim_id (claim_id),
			KEY action (action),
			KEY created_at (created_at)
		) {$charset};";

		dbDelta( $sql_claims );
		dbDelta( $sql_logs );
	}
}
