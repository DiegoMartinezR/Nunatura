<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap allpacode-admin">
	<div class="allpacode-admin__hero">
		<div>
			<span class="allpacode-admin__eyebrow"><?php esc_html_e( 'Reportes y respaldo', 'allpacode-gestion-de-reclamos-peru' ); ?></span>
			<h1><?php esc_html_e( 'Exportaciones', 'allpacode-gestion-de-reclamos-peru' ); ?></h1>
			<p><?php esc_html_e( 'Descarga registros por rango de fechas, estado de atención y clase de caso.', 'allpacode-gestion-de-reclamos-peru' ); ?></p>
		</div>
	</div>
	<form class="allpacode-admin__panel" method="get" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<input type="hidden" name="action" value="allpacode_gr_export">
		<?php wp_nonce_field( 'allpacode_gr_export' ); ?>
		<h2><?php esc_html_e( 'Configurar exportación', 'allpacode-gestion-de-reclamos-peru' ); ?></h2>
		<p class="allpacode-admin__muted"><?php esc_html_e( 'Usa filtros claros para preparar reportes internos o respaldo documental.', 'allpacode-gestion-de-reclamos-peru' ); ?></p>
		<div class="allpacode-admin__grid">
			<label><span><?php esc_html_e( 'Formato del archivo', 'allpacode-gestion-de-reclamos-peru' ); ?></span><select name="format"><option value="csv">CSV</option><option value="xls">Excel compatible</option><option value="pdf">Reporte imprimible</option></select></label>
			<label><span><?php esc_html_e( 'Estado de atención', 'allpacode-gestion-de-reclamos-peru' ); ?></span><select name="status"><option value=""><?php esc_html_e( 'Todos los estados', 'allpacode-gestion-de-reclamos-peru' ); ?></option><option value="pending"><?php esc_html_e( 'Pendiente', 'allpacode-gestion-de-reclamos-peru' ); ?></option><option value="reviewing"><?php esc_html_e( 'En revisión', 'allpacode-gestion-de-reclamos-peru' ); ?></option><option value="answered"><?php esc_html_e( 'Respondido', 'allpacode-gestion-de-reclamos-peru' ); ?></option><option value="closed"><?php esc_html_e( 'Cerrado', 'allpacode-gestion-de-reclamos-peru' ); ?></option></select></label>
			<label><span><?php esc_html_e( 'Clase de caso', 'allpacode-gestion-de-reclamos-peru' ); ?></span><select name="type"><option value=""><?php esc_html_e( 'Reclamos y quejas', 'allpacode-gestion-de-reclamos-peru' ); ?></option><option value="reclamo"><?php esc_html_e( 'Solo reclamos', 'allpacode-gestion-de-reclamos-peru' ); ?></option><option value="queja"><?php esc_html_e( 'Solo quejas', 'allpacode-gestion-de-reclamos-peru' ); ?></option></select></label>
			<label><span><?php esc_html_e( 'Desde', 'allpacode-gestion-de-reclamos-peru' ); ?></span><input type="date" name="from"></label>
			<label><span><?php esc_html_e( 'Hasta', 'allpacode-gestion-de-reclamos-peru' ); ?></span><input type="date" name="to"></label>
		</div>
		<?php submit_button( __( 'Exportar', 'allpacode-gestion-de-reclamos-peru' ) ); ?>
	</form>
</div>
