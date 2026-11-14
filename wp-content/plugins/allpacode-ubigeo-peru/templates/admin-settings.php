<?php
/**
 * Settings page.
 *
 * @package AllpaCodeUbigeoPeru
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap allpacode-ubigeo-admin">
	<div class="allpacode-ubigeo-admin__hero">
		<div>
			<span><?php esc_html_e( 'Datos territoriales del Perú', 'allpacode-ubigeo-peru' ); ?></span>
			<h1><?php esc_html_e( 'Ubigeo Perú', 'allpacode-ubigeo-peru' ); ?></h1>
			<p><?php esc_html_e( 'Selectores dependientes de departamento, provincia y distrito con código ubigeo para formularios, tiendas y plugins.', 'allpacode-ubigeo-peru' ); ?></p>
		</div>
	</div>

	<div class="allpacode-ubigeo-admin__stats">
		<div><strong><?php echo esc_html( (string) $stats['departments'] ); ?></strong><span><?php esc_html_e( 'Departamentos / regiones', 'allpacode-ubigeo-peru' ); ?></span></div>
		<div><strong><?php echo esc_html( (string) $stats['provinces'] ); ?></strong><span><?php esc_html_e( 'Provincias', 'allpacode-ubigeo-peru' ); ?></span></div>
		<div><strong><?php echo esc_html( (string) $stats['districts'] ); ?></strong><span><?php esc_html_e( 'Distritos', 'allpacode-ubigeo-peru' ); ?></span></div>
	</div>

	<form method="post" action="options.php" class="allpacode-ubigeo-admin__panel">
		<?php settings_fields( 'allpacode_ubigeo_settings' ); ?>
		<h2><?php esc_html_e( 'Configuración', 'allpacode-ubigeo-peru' ); ?></h2>
		<div class="allpacode-ubigeo-admin__grid">
			<label>
				<span><?php esc_html_e( 'Activar en WooCommerce', 'allpacode-ubigeo-peru' ); ?></span>
				<input type="checkbox" name="<?php echo esc_attr( \AllpaCodeUbigeoPeru\Settings::OPTION ); ?>[enable_woocommerce]" value="1" <?php checked( $settings['enable_woocommerce'] ); ?>>
				<small><?php esc_html_e( 'Agrega departamento, provincia, distrito y ubigeo al checkout.', 'allpacode-ubigeo-peru' ); ?></small>
			</label>
			<label>
				<span><?php esc_html_e( 'Cargar estilos públicos', 'allpacode-ubigeo-peru' ); ?></span>
				<input type="checkbox" name="<?php echo esc_attr( \AllpaCodeUbigeoPeru\Settings::OPTION ); ?>[load_styles]" value="1" <?php checked( $settings['load_styles'] ); ?>>
				<small><?php esc_html_e( 'Puedes desactivarlo si tu tema ya controla el diseño.', 'allpacode-ubigeo-peru' ); ?></small>
			</label>
			<label>
				<span><?php esc_html_e( 'Modo de dirección en WooCommerce', 'allpacode-ubigeo-peru' ); ?></span>
				<select name="<?php echo esc_attr( \AllpaCodeUbigeoPeru\Settings::OPTION ); ?>[address_mode]">
					<option value="append" <?php selected( $settings['address_mode'], 'append' ); ?>><?php esc_html_e( 'Agregar campos sin reemplazar dirección', 'allpacode-ubigeo-peru' ); ?></option>
					<option value="replace" <?php selected( $settings['address_mode'], 'replace' ); ?>><?php esc_html_e( 'Reservado para integración avanzada', 'allpacode-ubigeo-peru' ); ?></option>
				</select>
			</label>
		</div>
		<?php submit_button(); ?>
	</form>

	<div class="allpacode-ubigeo-admin__panel">
		<h2><?php esc_html_e( 'Uso rápido', 'allpacode-ubigeo-peru' ); ?></h2>
		<p><code>[allpacode_ubigeo_peru required="true"]</code></p>
		<p><code>GET /wp-json/allpacode-ubigeo/v1/departamentos</code></p>
		<p><code>GET /wp-json/allpacode-ubigeo/v1/provincias?departamento=15</code></p>
		<p><code>GET /wp-json/allpacode-ubigeo/v1/distritos?provincia=1501</code></p>
	</div>
</div>
