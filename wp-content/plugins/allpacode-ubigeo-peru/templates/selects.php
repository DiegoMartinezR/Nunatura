<?php
/**
 * Dependent selects template.
 *
 * @package AllpaCodeUbigeoPeru
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

$prefix   = sanitize_key( $atts['name_prefix'] );
$required = 'true' === $atts['required'];
$class    = sanitize_html_class( $atts['class'] );
$required_attr = $required ? ' required aria-required="true"' : '';
?>
<div class="allpacode-ubigeo <?php echo esc_attr( $class ); ?>" data-allpacode-ubigeo>
	<label>
		<span><?php esc_html_e( 'Departamento / Región', 'allpacode-ubigeo-peru' ); ?></span>
		<select name="<?php echo esc_attr( $prefix ); ?>_department" data-allpacode-ubigeo-department<?php echo $required_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<option value=""><?php esc_html_e( 'Selecciona un departamento', 'allpacode-ubigeo-peru' ); ?></option>
			<?php foreach ( $departments as $department ) : ?>
				<option value="<?php echo esc_attr( $department['code'] ); ?>"><?php echo esc_html( $department['name'] ); ?></option>
			<?php endforeach; ?>
		</select>
	</label>
	<label>
		<span><?php esc_html_e( 'Provincia', 'allpacode-ubigeo-peru' ); ?></span>
		<select name="<?php echo esc_attr( $prefix ); ?>_province" data-allpacode-ubigeo-province<?php echo $required_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<option value=""><?php esc_html_e( 'Selecciona una provincia', 'allpacode-ubigeo-peru' ); ?></option>
		</select>
	</label>
	<label>
		<span><?php esc_html_e( 'Distrito', 'allpacode-ubigeo-peru' ); ?></span>
		<select name="<?php echo esc_attr( $prefix ); ?>_district" data-allpacode-ubigeo-district<?php echo $required_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<option value=""><?php esc_html_e( 'Selecciona un distrito', 'allpacode-ubigeo-peru' ); ?></option>
		</select>
	</label>
	<input type="hidden" name="<?php echo esc_attr( $prefix ); ?>_code" data-allpacode-ubigeo-code value="">
</div>
