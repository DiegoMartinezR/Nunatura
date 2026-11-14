<?php
use AllpaCodeGestionReclamos\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
?>
<div class="wrap allpacode-admin">
	<h1><?php echo esc_html( $claim ? $claim['code'] : __( 'Reclamo no encontrado', 'allpacode-gestion-de-reclamos-peru' ) ); ?></h1>
	<?php if ( ! $claim ) : ?>
		<p><?php esc_html_e( 'El caso solicitado no existe.', 'allpacode-gestion-de-reclamos-peru' ); ?></p>
	<?php else : ?>
		<?php
		$location_parts = array_filter(
			array(
				$claim['department_name'] ?? '',
				$claim['province_name'] ?? '',
				$claim['district_name'] ?? '',
			)
		);
		?>
		<div class="allpacode-admin__panel">
			<h2><?php esc_html_e( 'Resumen', 'allpacode-gestion-de-reclamos-peru' ); ?></h2>
			<p><strong><?php esc_html_e( 'Consumidor:', 'allpacode-gestion-de-reclamos-peru' ); ?></strong> <?php echo esc_html( $claim['consumer_name'] ); ?> · <?php echo esc_html( $claim['document_type'] . ' ' . $claim['document_number'] ); ?></p>
			<p><strong><?php esc_html_e( 'Contacto:', 'allpacode-gestion-de-reclamos-peru' ); ?></strong> <?php echo esc_html( $claim['email'] ); ?> · <?php echo esc_html( $claim['phone'] ); ?></p>
			<?php if ( ! empty( $location_parts ) || ! empty( $claim['ubigeo_code'] ) ) : ?>
				<p><strong><?php esc_html_e( 'Ubicación:', 'allpacode-gestion-de-reclamos-peru' ); ?></strong> <?php if ( ! empty( $location_parts ) ) : ?><?php echo esc_html( implode( ' / ', $location_parts ) ); ?><?php endif; ?><?php if ( ! empty( $location_parts ) && ! empty( $claim['ubigeo_code'] ) ) : ?> · <?php endif; ?><?php if ( ! empty( $claim['ubigeo_code'] ) ) : ?><strong><?php esc_html_e( 'Ubigeo:', 'allpacode-gestion-de-reclamos-peru' ); ?></strong> <?php echo esc_html( $claim['ubigeo_code'] ); ?><?php endif; ?></p>
			<?php endif; ?>
			<p><strong><?php esc_html_e( 'Dirección:', 'allpacode-gestion-de-reclamos-peru' ); ?></strong><br><?php echo nl2br( esc_html( $claim['address'] ) ); ?></p>
			<p><strong><?php esc_html_e( 'Clase de caso:', 'allpacode-gestion-de-reclamos-peru' ); ?></strong> <?php echo esc_html( Utils::types()[ $claim['type'] ] ?? $claim['type'] ); ?> · <strong><?php esc_html_e( 'Estado:', 'allpacode-gestion-de-reclamos-peru' ); ?></strong> <?php echo esc_html( Utils::statuses()[ $claim['status'] ] ?? $claim['status'] ); ?></p>
			<p><strong><?php esc_html_e( 'Producto/servicio:', 'allpacode-gestion-de-reclamos-peru' ); ?></strong> <?php echo esc_html( $claim['product_service'] ); ?> · <strong><?php esc_html_e( 'Monto:', 'allpacode-gestion-de-reclamos-peru' ); ?></strong> <?php echo esc_html( $claim['claimed_amount'] ); ?></p>
			<p><strong><?php esc_html_e( 'Descripción:', 'allpacode-gestion-de-reclamos-peru' ); ?></strong><br><?php echo nl2br( esc_html( $claim['description'] ) ); ?></p>
			<p><strong><?php esc_html_e( 'Pedido:', 'allpacode-gestion-de-reclamos-peru' ); ?></strong><br><?php echo nl2br( esc_html( $claim['consumer_request'] ) ); ?></p>
			<?php if ( $claim['evidence_url'] ) : ?><p><a href="<?php echo esc_url( $claim['evidence_url'] ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Ver evidencia', 'allpacode-gestion-de-reclamos-peru' ); ?></a></p><?php endif; ?>
		</div>
		<form class="allpacode-admin__panel" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<h2><?php esc_html_e( 'Gestión interna', 'allpacode-gestion-de-reclamos-peru' ); ?></h2>
			<input type="hidden" name="action" value="allpacode_gr_update_claim">
			<input type="hidden" name="claim_id" value="<?php echo esc_attr( $claim['id'] ); ?>">
			<?php wp_nonce_field( 'allpacode_gr_update_claim' ); ?>
			<div class="allpacode-admin__grid">
				<label><?php esc_html_e( 'Estado', 'allpacode-gestion-de-reclamos-peru' ); ?><select name="status"><?php foreach ( Utils::statuses() as $key => $label ) : ?><option value="<?php echo esc_attr( $key ); ?>" <?php selected( $claim['status'], $key ); ?>><?php echo esc_html( $label ); ?></option><?php endforeach; ?></select></label>
				<label><?php esc_html_e( 'Enviar respuesta por correo', 'allpacode-gestion-de-reclamos-peru' ); ?><input type="checkbox" name="send_response" value="1"></label>
				<label class="allpacode-admin__wide"><?php esc_html_e( 'Respuesta al cliente', 'allpacode-gestion-de-reclamos-peru' ); ?><textarea name="admin_response"><?php echo esc_textarea( $claim['admin_response'] ); ?></textarea></label>
				<label class="allpacode-admin__wide"><?php esc_html_e( 'Notas internas', 'allpacode-gestion-de-reclamos-peru' ); ?><textarea name="internal_notes"><?php echo esc_textarea( $claim['internal_notes'] ); ?></textarea></label>
			</div>
			<?php submit_button( __( 'Guardar cambios', 'allpacode-gestion-de-reclamos-peru' ) ); ?>
		</form>
		<div class="allpacode-admin__panel">
			<h2><?php esc_html_e( 'Historial', 'allpacode-gestion-de-reclamos-peru' ); ?></h2>
			<table class="widefat striped"><thead><tr><th><?php esc_html_e( 'Fecha', 'allpacode-gestion-de-reclamos-peru' ); ?></th><th><?php esc_html_e( 'Acción', 'allpacode-gestion-de-reclamos-peru' ); ?></th><th><?php esc_html_e( 'Detalle', 'allpacode-gestion-de-reclamos-peru' ); ?></th></tr></thead><tbody><?php foreach ( $logs as $log ) : ?><tr><td><?php echo esc_html( $log['created_at'] ); ?></td><td><?php echo esc_html( Utils::action_label( $log['action'] ) ); ?></td><td><?php echo esc_html( $log['message'] ); ?></td></tr><?php endforeach; ?></tbody></table>
		</div>
	<?php endif; ?>
</div>
