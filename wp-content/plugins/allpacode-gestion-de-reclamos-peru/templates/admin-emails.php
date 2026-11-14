<?php
use AllpaCodeGestionReclamos\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

$s = Settings::get();
?>
<div class="wrap allpacode-admin">
	<div class="allpacode-admin__hero">
		<div>
			<span class="allpacode-admin__eyebrow"><?php esc_html_e( 'Plantillas HTML', 'allpacode-gestion-de-reclamos-peru' ); ?></span>
			<h1><?php esc_html_e( 'Correos automáticos', 'allpacode-gestion-de-reclamos-peru' ); ?></h1>
			<p><?php esc_html_e( 'Personaliza las comunicaciones que reciben el consumidor y el equipo interno.', 'allpacode-gestion-de-reclamos-peru' ); ?></p>
		</div>
	</div>
	<form method="post" action="options.php">
		<?php settings_fields( 'allpacode_gr_settings' ); ?>
		<div class="allpacode-admin__tokenbar">
			<strong><?php esc_html_e( 'Variables disponibles', 'allpacode-gestion-de-reclamos-peru' ); ?></strong>
			<code>{code}</code><code>{consumer_name}</code><code>{type_label}</code><code>{status_label}</code><code>{email}</code><code>{response}</code><code>{product_service}</code>
		</div>

		<div class="allpacode-admin__panel">
			<h2><?php esc_html_e( 'Confirmación al consumidor', 'allpacode-gestion-de-reclamos-peru' ); ?></h2>
			<p class="allpacode-admin__muted"><?php esc_html_e( 'Se envía apenas el formulario queda registrado e incluye el enlace a la constancia PDF.', 'allpacode-gestion-de-reclamos-peru' ); ?></p>
			<?php
			wp_editor(
				$s['email_customer'],
				'allpacode_gr_email_customer',
				array(
					'textarea_name' => Settings::OPTION . '[email_customer]',
					'textarea_rows' => 8,
					'media_buttons' => false,
					'teeny'         => true,
				)
			);
			?>
		</div>

		<div class="allpacode-admin__panel">
			<h2><?php esc_html_e( 'Notificación interna', 'allpacode-gestion-de-reclamos-peru' ); ?></h2>
			<p class="allpacode-admin__muted"><?php esc_html_e( 'Correo que recibe la empresa cuando ingresa un nuevo reclamo o queja.', 'allpacode-gestion-de-reclamos-peru' ); ?></p>
			<?php
			wp_editor(
				$s['email_admin'],
				'allpacode_gr_email_admin',
				array(
					'textarea_name' => Settings::OPTION . '[email_admin]',
					'textarea_rows' => 8,
					'media_buttons' => false,
					'teeny'         => true,
				)
			);
			?>
		</div>

		<div class="allpacode-admin__panel">
			<h2><?php esc_html_e( 'Respuesta administrativa', 'allpacode-gestion-de-reclamos-peru' ); ?></h2>
			<p class="allpacode-admin__muted"><?php esc_html_e( 'Plantilla usada cuando el administrador responde desde la ficha del caso.', 'allpacode-gestion-de-reclamos-peru' ); ?></p>
			<?php
			wp_editor(
				$s['email_response'],
				'allpacode_gr_email_response',
				array(
					'textarea_name' => Settings::OPTION . '[email_response]',
					'textarea_rows' => 8,
					'media_buttons' => false,
					'teeny'         => true,
				)
			);
			?>
		</div>

		<?php foreach ( array_diff_key( $s, array_flip( array( 'email_customer', 'email_admin', 'email_response' ) ) ) as $key => $value ) : ?>
			<input type="hidden" name="<?php echo esc_attr( Settings::OPTION . '[' . $key . ']' ); ?>" value="<?php echo esc_attr( is_bool( $value ) ? (int) $value : $value ); ?>">
		<?php endforeach; ?>
		<?php submit_button(); ?>
	</form>
</div>
