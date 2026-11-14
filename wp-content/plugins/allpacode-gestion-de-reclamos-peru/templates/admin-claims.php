<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound, WordPress.Security.NonceVerification.Recommended

$labels = array(
	'total'     => __( 'Total registrados', 'allpacode-gestion-de-reclamos-peru' ),
	'pending'   => __( 'Pendientes', 'allpacode-gestion-de-reclamos-peru' ),
	'reviewing' => __( 'En revisión', 'allpacode-gestion-de-reclamos-peru' ),
	'answered'  => __( 'Respondidos', 'allpacode-gestion-de-reclamos-peru' ),
	'closed'    => __( 'Cerrados', 'allpacode-gestion-de-reclamos-peru' ),
);

$current_search = sanitize_text_field( wp_unslash( $_GET['s'] ?? '' ) );
$current_status = sanitize_key( wp_unslash( $_GET['status'] ?? '' ) );
$current_type   = sanitize_key( wp_unslash( $_GET['type'] ?? '' ) );
?>
<div class="wrap allpacode-admin">
	<div class="allpacode-admin__hero">
		<div>
			<span class="allpacode-admin__eyebrow"><?php esc_html_e( 'Panel operativo', 'allpacode-gestion-de-reclamos-peru' ); ?></span>
			<h1><?php esc_html_e( 'Gestión de Reclamos', 'allpacode-gestion-de-reclamos-peru' ); ?></h1>
			<p><?php esc_html_e( 'Monitorea reclamos, quejas, tiempos de atención y trazabilidad desde un solo lugar.', 'allpacode-gestion-de-reclamos-peru' ); ?></p>
		</div>
		<a class="allpacode-admin__button" href="<?php echo esc_url( admin_url( 'admin.php?page=allpacode-gr-exports' ) ); ?>"><?php esc_html_e( 'Exportar casos', 'allpacode-gestion-de-reclamos-peru' ); ?></a>
	</div>

	<div class="allpacode-admin__stats allpacode-admin__stats--dashboard">
		<?php foreach ( $stats as $key => $value ) : ?>
			<div class="allpacode-admin__stat allpacode-admin__stat--<?php echo esc_attr( $key ); ?>">
				<span><?php echo esc_html( $labels[ $key ] ?? ucfirst( $key ) ); ?></span>
				<strong><?php echo esc_html( (string) $value ); ?></strong>
				<small><?php echo 'total' === $key ? esc_html__( 'Histórico activo', 'allpacode-gestion-de-reclamos-peru' ) : esc_html__( 'Casos en este estado', 'allpacode-gestion-de-reclamos-peru' ); ?></small>
			</div>
		<?php endforeach; ?>
	</div>

	<div class="allpacode-admin__panel allpacode-admin__panel--filters">
		<form method="get" class="allpacode-admin__toolbar">
			<input type="hidden" name="page" value="allpacode-gr">
			<label>
				<span><?php esc_html_e( 'Buscar por código, cliente, documento o correo', 'allpacode-gestion-de-reclamos-peru' ); ?></span>
				<input type="search" name="s" value="<?php echo esc_attr( $current_search ); ?>" placeholder="LR-2026-000001, DNI, correo...">
			</label>
			<label>
				<span><?php esc_html_e( 'Estado de atención', 'allpacode-gestion-de-reclamos-peru' ); ?></span>
				<select name="status">
					<option value=""><?php esc_html_e( 'Todos los estados', 'allpacode-gestion-de-reclamos-peru' ); ?></option>
					<option value="pending" <?php selected( $current_status, 'pending' ); ?>><?php esc_html_e( 'Pendiente', 'allpacode-gestion-de-reclamos-peru' ); ?></option>
					<option value="reviewing" <?php selected( $current_status, 'reviewing' ); ?>><?php esc_html_e( 'En revisión', 'allpacode-gestion-de-reclamos-peru' ); ?></option>
					<option value="answered" <?php selected( $current_status, 'answered' ); ?>><?php esc_html_e( 'Respondido', 'allpacode-gestion-de-reclamos-peru' ); ?></option>
					<option value="closed" <?php selected( $current_status, 'closed' ); ?>><?php esc_html_e( 'Cerrado', 'allpacode-gestion-de-reclamos-peru' ); ?></option>
				</select>
			</label>
			<label>
				<span><?php esc_html_e( 'Clase de registro', 'allpacode-gestion-de-reclamos-peru' ); ?></span>
				<select name="type">
					<option value=""><?php esc_html_e( 'Reclamos y quejas', 'allpacode-gestion-de-reclamos-peru' ); ?></option>
					<option value="reclamo" <?php selected( $current_type, 'reclamo' ); ?>><?php esc_html_e( 'Reclamo', 'allpacode-gestion-de-reclamos-peru' ); ?></option>
					<option value="queja" <?php selected( $current_type, 'queja' ); ?>><?php esc_html_e( 'Queja', 'allpacode-gestion-de-reclamos-peru' ); ?></option>
				</select>
			</label>
			<button class="button button-primary"><?php esc_html_e( 'Filtrar', 'allpacode-gestion-de-reclamos-peru' ); ?></button>
		</form>
	</div>

	<div class="allpacode-admin__panel">
		<h2><?php esc_html_e( 'Bandeja de casos', 'allpacode-gestion-de-reclamos-peru' ); ?></h2>
		<p class="allpacode-admin__muted"><?php esc_html_e( 'Haz clic en el código para revisar el historial, responder al consumidor y agregar notas internas.', 'allpacode-gestion-de-reclamos-peru' ); ?></p>
	<form method="get">
		<input type="hidden" name="page" value="allpacode-gr">
		<?php $table->display(); ?>
	</form>
	</div>
</div>
