<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
?>
<div class="wrap allpacode-admin">
	<h1><?php esc_html_e( 'Registros administrativos', 'allpacode-gestion-de-reclamos-peru' ); ?></h1>
	<table class="widefat striped"><thead><tr><th><?php esc_html_e( 'Fecha', 'allpacode-gestion-de-reclamos-peru' ); ?></th><th><?php esc_html_e( 'Caso', 'allpacode-gestion-de-reclamos-peru' ); ?></th><th><?php esc_html_e( 'Acción', 'allpacode-gestion-de-reclamos-peru' ); ?></th><th><?php esc_html_e( 'Detalle', 'allpacode-gestion-de-reclamos-peru' ); ?></th><th><?php esc_html_e( 'Dirección IP', 'allpacode-gestion-de-reclamos-peru' ); ?></th></tr></thead><tbody><?php foreach ( $logs as $log ) : ?><tr><td><?php echo esc_html( $log['created_at'] ); ?></td><td><?php echo esc_html( $log['claim_id'] ); ?></td><td><?php echo esc_html( \AllpaCodeGestionReclamos\Utils::action_label( $log['action'] ) ); ?></td><td><?php echo esc_html( $log['message'] ); ?></td><td><?php echo esc_html( $log['ip_address'] ); ?></td></tr><?php endforeach; ?></tbody></table>
</div>
