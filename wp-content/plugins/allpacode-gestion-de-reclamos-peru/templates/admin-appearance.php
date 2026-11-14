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
			<span class="allpacode-admin__eyebrow"><?php esc_html_e( 'Diseño del formulario público', 'allpacode-gestion-de-reclamos-peru' ); ?></span>
			<h1><?php esc_html_e( 'Apariencia del formulario', 'allpacode-gestion-de-reclamos-peru' ); ?></h1>
			<p><?php esc_html_e( 'Estos ajustes modifican cómo se ve el Gestión de Reclamos incrustado en páginas, Elementor, Gutenberg o widgets.', 'allpacode-gestion-de-reclamos-peru' ); ?></p>
		</div>
	</div>
	<form method="post" action="options.php" class="allpacode-admin__panel">
		<?php settings_fields( 'allpacode_gr_settings' ); ?>
		<h2><?php esc_html_e( 'Identidad visual', 'allpacode-gestion-de-reclamos-peru' ); ?></h2>
			<p class="allpacode-admin__muted"><?php esc_html_e( 'Define colores, radio de bordes y visibilidad de marca del formulario que verá el consumidor.', 'allpacode-gestion-de-reclamos-peru' ); ?></p>
		<div class="allpacode-admin__grid">
			<label><span><?php esc_html_e( 'Color principal del formulario', 'allpacode-gestion-de-reclamos-peru' ); ?></span><input type="color" name="<?php echo esc_attr( Settings::OPTION ); ?>[primary_color]" value="<?php echo esc_attr( $s['primary_color'] ); ?>"><small><?php esc_html_e( 'Botones, foco de campos y acentos.', 'allpacode-gestion-de-reclamos-peru' ); ?></small></label>
			<label><span><?php esc_html_e( 'Color secundario de acento', 'allpacode-gestion-de-reclamos-peru' ); ?></span><input type="color" name="<?php echo esc_attr( Settings::OPTION ); ?>[accent_color]" value="<?php echo esc_attr( $s['accent_color'] ); ?>"><small><?php esc_html_e( 'Detalles visuales y estados del formulario.', 'allpacode-gestion-de-reclamos-peru' ); ?></small></label>
			<label><span><?php esc_html_e( 'Redondez de bordes en píxeles', 'allpacode-gestion-de-reclamos-peru' ); ?></span><input type="number" min="0" max="28" name="<?php echo esc_attr( Settings::OPTION ); ?>[border_radius]" value="<?php echo esc_attr( $s['border_radius'] ); ?>"></label>
			<label class="allpacode-admin__switch"><span><?php esc_html_e( 'Mostrar marca en el formulario', 'allpacode-gestion-de-reclamos-peru' ); ?></span><input type="checkbox" name="<?php echo esc_attr( Settings::OPTION ); ?>[branding_visible]" value="1" <?php checked( $s['branding_visible'] ); ?>></label>
		</div>
		<?php foreach ( array_diff_key( $s, array_flip( array( 'primary_color', 'accent_color', 'border_radius', 'branding_visible' ) ) ) as $key => $value ) : ?>
			<input type="hidden" name="<?php echo esc_attr( Settings::OPTION . '[' . $key . ']' ); ?>" value="<?php echo esc_attr( is_bool( $value ) ? (int) $value : $value ); ?>">
		<?php endforeach; ?>
		<?php submit_button(); ?>
	</form>
</div>
