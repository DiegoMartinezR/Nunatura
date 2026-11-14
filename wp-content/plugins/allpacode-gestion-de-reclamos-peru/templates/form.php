<?php
/**
 * Public form template.
 *
 * @package AllpaCodeGestionReclamos
 */

use AllpaCodeGestionReclamos\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

if ( function_exists( 'allpacode_ubigeo_is_available' ) ) {
	\AllpaCodeUbigeoPeru\PublicApp\Assets::register_assets();
	wp_enqueue_script( 'allpacode-ubigeo-public' );
}

$classes = array(
	'allpacode-gr',
	'allpacode-gr-theme-' . sanitize_html_class( $atts['theme'] ),
	'true' === $atts['compact'] ? 'allpacode-gr-compact' : '',
	'true' === $atts['darkmode'] ? 'allpacode-gr-dark' : '',
);

$shortcode_color = sanitize_hex_color( (string) $atts['color'] );
?>
<section class="<?php echo esc_attr( trim( implode( ' ', $classes ) ) ); ?>" <?php echo $shortcode_color ? 'style="--allpacode-gr-primary:' . esc_attr( $shortcode_color ) . '"' : ''; ?>>
	<?php if ( 'false' !== $atts['show_title'] ) : ?>
		<header class="allpacode-gr__header">
			<h2><?php esc_html_e( 'Gestión de Reclamos', 'allpacode-gestion-de-reclamos-peru' ); ?></h2>
		</header>
	<?php endif; ?>

	<form class="allpacode-gr__form" method="post" enctype="multipart/form-data" novalidate>
		<input type="hidden" name="action" value="allpacode_gr_submit">
		<input type="hidden" name="nonce" value="<?php echo esc_attr( wp_create_nonce( 'allpacode_gr_submit' ) ); ?>">
		<input type="hidden" name="captcha_token" value="">
		<label class="allpacode-gr__hp">Empresa <input type="text" name="allpacode_gr_company" tabindex="-1" autocomplete="off"></label>

		<div class="allpacode-gr__section">
			<h3><?php esc_html_e( 'Datos del consumidor', 'allpacode-gestion-de-reclamos-peru' ); ?></h3>
			<div class="allpacode-gr__grid">
				<label><?php esc_html_e( 'Nombres y apellidos', 'allpacode-gestion-de-reclamos-peru' ); ?><input name="consumer_name" required maxlength="190" autocomplete="name"></label>
				<label><?php esc_html_e( 'Tipo de documento', 'allpacode-gestion-de-reclamos-peru' ); ?><select name="document_type" required><option value="DNI">DNI</option><option value="CE">Carné de extranjería</option><option value="RUC">RUC</option><option value="PAS">Pasaporte</option></select></label>
				<label><?php esc_html_e( 'Número de documento', 'allpacode-gestion-de-reclamos-peru' ); ?><input name="document_number" required maxlength="20" inputmode="text"></label>
				<label><?php esc_html_e( 'Teléfono', 'allpacode-gestion-de-reclamos-peru' ); ?><input name="phone" required maxlength="30" inputmode="tel" autocomplete="tel"></label>
				<label><?php esc_html_e( 'Correo electrónico', 'allpacode-gestion-de-reclamos-peru' ); ?><input type="email" name="email" required maxlength="190" autocomplete="email"></label>
				<?php if ( function_exists( 'allpacode_ubigeo_is_available' ) ) : ?>
					<div class="allpacode-gr__wide allpacode-gr__ubigeo">
						<?php echo do_shortcode( '[allpacode_ubigeo_peru required="true" name_prefix="allpacode_ubigeo"]' ); ?>
					</div>
					<label class="allpacode-gr__wide"><?php esc_html_e( 'Dirección exacta', 'allpacode-gestion-de-reclamos-peru' ); ?><textarea name="address" required maxlength="600" placeholder="<?php esc_attr_e( 'Av., calle, número, interior, referencia u otra información necesaria.', 'allpacode-gestion-de-reclamos-peru' ); ?>"></textarea></label>
				<?php else : ?>
					<label class="allpacode-gr__wide"><?php esc_html_e( 'Dirección', 'allpacode-gestion-de-reclamos-peru' ); ?><textarea name="address" required maxlength="600"></textarea></label>
				<?php endif; ?>
			</div>
		</div>

		<div class="allpacode-gr__section">
			<h3><?php esc_html_e( 'Datos del reclamo', 'allpacode-gestion-de-reclamos-peru' ); ?></h3>
			<div class="allpacode-gr__grid">
				<label><?php esc_html_e( 'Clase de caso', 'allpacode-gestion-de-reclamos-peru' ); ?><select name="type" required><option value="reclamo"><?php esc_html_e( 'Reclamo', 'allpacode-gestion-de-reclamos-peru' ); ?></option><option value="queja"><?php esc_html_e( 'Queja', 'allpacode-gestion-de-reclamos-peru' ); ?></option></select></label>
				<label><?php esc_html_e( 'Producto o servicio', 'allpacode-gestion-de-reclamos-peru' ); ?><input name="product_service" required maxlength="190"></label>
				<label><?php esc_html_e( 'Monto reclamado', 'allpacode-gestion-de-reclamos-peru' ); ?><input type="number" step="0.01" min="0" max="999999999.99" name="claimed_amount"></label>
				<label><?php esc_html_e( 'Evidencia opcional', 'allpacode-gestion-de-reclamos-peru' ); ?><input type="file" name="evidence" accept=".jpg,.jpeg,.png,.pdf"></label>
				<label class="allpacode-gr__wide"><?php esc_html_e( 'Descripción', 'allpacode-gestion-de-reclamos-peru' ); ?><textarea name="description" required minlength="20" maxlength="5000"></textarea></label>
				<label class="allpacode-gr__wide"><?php esc_html_e( 'Pedido del consumidor', 'allpacode-gestion-de-reclamos-peru' ); ?><textarea name="consumer_request" required maxlength="3000"></textarea></label>
			</div>
		</div>

		<div class="allpacode-gr__legal">
			<p><?php echo wp_kses_post( Settings::get( 'legal_text' ) ); ?></p>
			<label><input type="checkbox" name="consent" value="1" required> <?php echo wp_kses_post( Settings::get( 'privacy_text' ) ); ?></label>
		</div>

		<?php if ( 'turnstile' === Settings::get( 'captcha_provider' ) && Settings::get( 'captcha_site_key' ) ) : ?>
			<div class="allpacode-gr__captcha" data-turnstile></div>
		<?php endif; ?>

		<button class="allpacode-gr__submit" type="submit"><?php esc_html_e( 'Registrar reclamo o queja', 'allpacode-gestion-de-reclamos-peru' ); ?></button>
		<div class="allpacode-gr__notice" role="status" aria-live="polite"></div>
	</form>
</section>
