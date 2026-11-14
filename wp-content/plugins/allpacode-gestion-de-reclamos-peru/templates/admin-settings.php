<?php
use AllpaCodeGestionReclamos\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$s = Settings::get();
// phpcs:disable WordPress.Security.NonceVerification.Recommended -- Admin notices read redirect query args only.
$deleted_claims = isset( $_GET['deleted_claims'] ) ? absint( wp_unslash( $_GET['deleted_claims'] ) ) : 0;
$delete_error   = sanitize_key( wp_unslash( $_GET['delete_error'] ?? '' ) );
// phpcs:enable WordPress.Security.NonceVerification.Recommended
?>
<div class="wrap allpacode-admin">
	<div class="allpacode-admin__hero">
		<div>
			<span class="allpacode-admin__eyebrow"><?php esc_html_e( 'Datos legales y operación', 'allpacode-gestion-de-reclamos-peru' ); ?></span>
			<h1><?php esc_html_e( 'Configuración del Libro', 'allpacode-gestion-de-reclamos-peru' ); ?></h1>
			<p><?php esc_html_e( 'Define la información que aparecerá en el formulario, constancias, correos y registros administrativos.', 'allpacode-gestion-de-reclamos-peru' ); ?></p>
		</div>
	</div>
	<form method="post" action="options.php">
		<?php settings_fields( 'allpacode_gr_settings' ); ?>
		<div class="allpacode-admin__layout">
			<div class="allpacode-admin__panel">
				<h2><?php esc_html_e( 'Empresa responsable', 'allpacode-gestion-de-reclamos-peru' ); ?></h2>
				<p class="allpacode-admin__muted"><?php esc_html_e( 'Estos datos se imprimen en la constancia y se guardan junto al reclamo.', 'allpacode-gestion-de-reclamos-peru' ); ?></p>
				<div class="allpacode-admin__grid">
					<label><span><?php esc_html_e( 'Razón social registrada', 'allpacode-gestion-de-reclamos-peru' ); ?></span><input name="<?php echo esc_attr( Settings::OPTION ); ?>[business_name]" value="<?php echo esc_attr( $s['business_name'] ); ?>" placeholder="Ej. Empresa Peruana SAC"></label>
					<label><span><?php esc_html_e( 'RUC de la empresa', 'allpacode-gestion-de-reclamos-peru' ); ?></span><input name="<?php echo esc_attr( Settings::OPTION ); ?>[business_ruc]" value="<?php echo esc_attr( $s['business_ruc'] ); ?>" placeholder="Ej. 20600000000"></label>
					<label><span><?php esc_html_e( 'Nombre comercial visible', 'allpacode-gestion-de-reclamos-peru' ); ?></span><input name="<?php echo esc_attr( Settings::OPTION ); ?>[trade_name]" value="<?php echo esc_attr( $s['trade_name'] ); ?>"></label>
					<label><span><?php esc_html_e( 'Correo que recibirá notificaciones', 'allpacode-gestion-de-reclamos-peru' ); ?></span><input type="email" name="<?php echo esc_attr( Settings::OPTION ); ?>[admin_email]" value="<?php echo esc_attr( $s['admin_email'] ); ?>"></label>
					<label class="allpacode-admin__wide"><span><?php esc_html_e( 'Logo para correos y constancias', 'allpacode-gestion-de-reclamos-peru' ); ?></span><div class="allpacode-admin__media"><input type="url" class="allpacode-admin__logo-url" name="<?php echo esc_attr( Settings::OPTION ); ?>[logo_url]" value="<?php echo esc_url( $s['logo_url'] ); ?>" placeholder="https://..."><button type="button" class="button allpacode-admin__upload-logo"><?php esc_html_e( 'Seleccionar logo', 'allpacode-gestion-de-reclamos-peru' ); ?></button></div><small><?php esc_html_e( 'Recomendado: JPG o PNG horizontal. Si no subes logo, se usará el nombre del sitio.', 'allpacode-gestion-de-reclamos-peru' ); ?></small></label>
					<label class="allpacode-admin__wide"><span><?php esc_html_e( 'Dirección fiscal completa', 'allpacode-gestion-de-reclamos-peru' ); ?></span><textarea name="<?php echo esc_attr( Settings::OPTION ); ?>[business_address]" placeholder="Av., distrito, provincia y departamento"><?php echo esc_textarea( $s['business_address'] ); ?></textarea></label>
				</div>
			</div>

			<div class="allpacode-admin__panel">
				<h2><?php esc_html_e( 'Numeración y retención', 'allpacode-gestion-de-reclamos-peru' ); ?></h2>
				<p class="allpacode-admin__muted"><?php esc_html_e( 'Controla el correlativo anual y el tiempo mínimo de conservación de registros.', 'allpacode-gestion-de-reclamos-peru' ); ?></p>
				<div class="allpacode-admin__grid">
					<label><span><?php esc_html_e( 'Prefijo del código correlativo', 'allpacode-gestion-de-reclamos-peru' ); ?></span><input name="<?php echo esc_attr( Settings::OPTION ); ?>[prefix]" value="<?php echo esc_attr( $s['prefix'] ); ?>"><small><?php esc_html_e( 'Ejemplo final: LR-2026-000001.', 'allpacode-gestion-de-reclamos-peru' ); ?></small></label>
					<label><span><?php esc_html_e( 'Años de retención legal', 'allpacode-gestion-de-reclamos-peru' ); ?></span><input type="number" min="2" name="<?php echo esc_attr( Settings::OPTION ); ?>[retention_years]" value="<?php echo esc_attr( $s['retention_years'] ); ?>"><small><?php esc_html_e( 'El mínimo recomendado es 2 años.', 'allpacode-gestion-de-reclamos-peru' ); ?></small></label>
				</div>
			</div>

			<div class="allpacode-admin__panel">
				<h2><?php esc_html_e( 'Protección contra envíos no deseados', 'allpacode-gestion-de-reclamos-peru' ); ?></h2>
				<p class="allpacode-admin__muted"><?php esc_html_e( 'Si ya tienes una protección global de Turnstile o reCAPTCHA para formularios, deja esta opción en "Ninguno" para evitar doble validación visual. El plugin mantiene verificación de seguridad, campo trampa y límite de intentos internos.', 'allpacode-gestion-de-reclamos-peru' ); ?></p>
				<div class="allpacode-admin__grid">
					<label><span><?php esc_html_e( 'Proveedor captcha nativo del plugin', 'allpacode-gestion-de-reclamos-peru' ); ?></span><select name="<?php echo esc_attr( Settings::OPTION ); ?>[captcha_provider]"><option value="none" <?php selected( $s['captcha_provider'], 'none' ); ?>><?php esc_html_e( 'Ninguno / usar protección externa', 'allpacode-gestion-de-reclamos-peru' ); ?></option><option value="recaptcha" <?php selected( $s['captcha_provider'], 'recaptcha' ); ?>>reCAPTCHA v3</option><option value="turnstile" <?php selected( $s['captcha_provider'], 'turnstile' ); ?>>Cloudflare Turnstile</option></select></label>
					<label><span><?php esc_html_e( 'Clave pública del sitio', 'allpacode-gestion-de-reclamos-peru' ); ?></span><input name="<?php echo esc_attr( Settings::OPTION ); ?>[captcha_site_key]" value="<?php echo esc_attr( $s['captcha_site_key'] ); ?>"></label>
					<label><span><?php esc_html_e( 'Clave secreta de validación', 'allpacode-gestion-de-reclamos-peru' ); ?></span><input name="<?php echo esc_attr( Settings::OPTION ); ?>[captcha_secret_key]" value="<?php echo esc_attr( $s['captcha_secret_key'] ); ?>"></label>
					<label><span><?php esc_html_e( 'URL para notificaciones externas', 'allpacode-gestion-de-reclamos-peru' ); ?></span><input type="url" name="<?php echo esc_attr( Settings::OPTION ); ?>[webhook_url]" value="<?php echo esc_url( $s['webhook_url'] ); ?>" placeholder="https://..."></label>
				</div>
			</div>

			<div class="allpacode-admin__panel">
				<h2><?php esc_html_e( 'Textos legales del formulario', 'allpacode-gestion-de-reclamos-peru' ); ?></h2>
				<p class="allpacode-admin__muted"><?php esc_html_e( 'Estos mensajes se muestran antes del envío. Puedes adaptarlos con tu asesor legal.', 'allpacode-gestion-de-reclamos-peru' ); ?></p>
				<label class="allpacode-admin__wide"><span><?php esc_html_e( 'Mensaje legal informativo', 'allpacode-gestion-de-reclamos-peru' ); ?></span><textarea name="<?php echo esc_attr( Settings::OPTION ); ?>[legal_text]"><?php echo esc_textarea( $s['legal_text'] ); ?></textarea></label>
				<label class="allpacode-admin__wide"><span><?php esc_html_e( 'Casilla de consentimiento de datos personales', 'allpacode-gestion-de-reclamos-peru' ); ?></span><textarea name="<?php echo esc_attr( Settings::OPTION ); ?>[privacy_text]"><?php echo esc_textarea( $s['privacy_text'] ); ?></textarea></label>
			</div>
		</div>
		<?php submit_button(); ?>
	</form>

	<?php if ( $deleted_claims ) : ?>
		<div class="notice notice-success is-dismissible">
			<p><?php echo esc_html( sprintf( __( 'Se borraron %d reclamos y sus registros internos.', 'allpacode-gestion-de-reclamos-peru' ), $deleted_claims ) ); ?></p>
		</div>
	<?php endif; ?>

	<?php if ( 'confirmation' === $delete_error ) : ?>
		<div class="notice notice-error is-dismissible">
			<p><?php esc_html_e( 'No se borró nada porque la confirmación no coincidió.', 'allpacode-gestion-de-reclamos-peru' ); ?></p>
		</div>
	<?php endif; ?>

	<div class="allpacode-admin__panel allpacode-admin__danger">
		<h2><?php esc_html_e( 'Zona peligrosa', 'allpacode-gestion-de-reclamos-peru' ); ?></h2>
		<p class="allpacode-admin__muted"><?php esc_html_e( 'Esta acción borra todos los reclamos, registros internos y constancias PDF generadas por el plugin. No borra la configuración. Es irreversible.', 'allpacode-gestion-de-reclamos-peru' ); ?></p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" onsubmit="return confirm('<?php echo esc_js( __( 'Esta acción es irreversible. Se borrarán todos los reclamos y sus constancias PDF. ¿Deseas continuar?', 'allpacode-gestion-de-reclamos-peru' ) ); ?>');">
			<?php wp_nonce_field( 'allpacode_gr_delete_all_claims' ); ?>
			<input type="hidden" name="action" value="allpacode_gr_delete_all_claims">
			<label>
				<span><?php esc_html_e( 'Escribe BORRAR TODO para confirmar', 'allpacode-gestion-de-reclamos-peru' ); ?></span>
				<input type="text" name="confirmation" autocomplete="off" required>
			</label>
			<button type="submit" class="button button-link-delete allpacode-admin__danger-button"><?php esc_html_e( 'Borrar todos los reclamos', 'allpacode-gestion-de-reclamos-peru' ); ?></button>
		</form>
	</div>
</div>
