<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap allpacode-admin">
	<h1><?php esc_html_e( 'Ayuda', 'allpacode-gestion-de-reclamos-peru' ); ?></h1>
	<div class="allpacode-admin__panel">
		<h2><?php esc_html_e( 'Código corto', 'allpacode-gestion-de-reclamos-peru' ); ?></h2>
		<p><code>[allpacode_gestion_reclamos]</code></p>
		<p><code>[allpacode_gestion_reclamos theme="business" color="#2563eb" show_title="true" compact="false" darkmode="false"]</code></p>
		<h2><?php esc_html_e( 'Puntos de extensión', 'allpacode-gestion-de-reclamos-peru' ); ?></h2>
		<p><code>allpacode_gr_claim_created</code>, <code>allpacode_gr_sanitized_claim</code>, <code>allpacode_gr_claim_code</code></p>
		<h2><?php esc_html_e( 'API REST', 'allpacode-gestion-de-reclamos-peru' ); ?></h2>
		<p><code>/wp-json/allpacode-gr/v1/claims</code> y <code>/wp-json/allpacode-gr/v1/claims/{code}</code></p>
	</div>
</div>
