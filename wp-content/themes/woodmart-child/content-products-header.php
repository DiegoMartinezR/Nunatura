<?php
/**
 * Archivo: woodmart-child/contenido-blog-header.php
 */

// 1. OBTENER EL TÍTULO DINÁMICO DE LA CATEGORÍA, ETIQUETA O ATRIBUTO
$titulo_dinamico = '';

if ( is_shop() ) {
    // Si es la página principal de la tienda, usa el título nativo de la página
    $titulo_dinamico = woocommerce_page_title( false );
} elseif ( is_product_category() || is_product_tag() || is_tax() ) {
    // Captura el nombre de la categoría, etiqueta, marca o atributo actual
    $titulo_dinamico = single_term_title( '', false );
} else {
    // Fallback por si acaso
    $titulo_dinamico = 'Ingredientes naturales';
}

// 2. OBTENER LOS COLORES DINÁMICOS DE ACF PARA LA TAXONOMÍA ACTUAL
// Obtenemos el objeto de la consulta actual (categoría, etiqueta, etc.)
$queried_object = get_queried_object();

// Fallbacks por defecto (los colores originales que tenías)
$color_fondo = '#009191';
$color_texto = '#ffffff';

if ( $queried_object && isset( $queried_object->taxonomy, $queried_object->term_id ) ) {
    // Pasamos el objeto del término para que ACF sepa de qué categoría extraer los datos
    $color_fondo_acf = get_field( 'color_de_fondo', $queried_object );
    $color_texto_acf = get_field( 'color_de_texto', $queried_object );

    if ( $color_fondo_acf ) {
        $color_fondo = $color_fondo_acf;
    }
    if ( $color_texto_acf ) {
        $color_texto = $color_texto_acf;
    }
}
?>

<style>
    body { font-family: 'Poppins', sans-serif; }
    
    /* Aplicamos las variables dinámicas de color para mantener el CSS limpio */
    .tw-dynamic-header {
        background-color: <?php echo esc_attr($color_fondo); ?> !important;
    }
    .tw-dynamic-header h1 {
        color: <?php echo esc_attr($color_texto); ?> !important;
    }
    .tw-dynamic-header svg path {
        fill: <?php echo esc_attr($color_texto); ?> !important;
    }
</style>

<div class="tw-dynamic-header tw-w-full tw-flex tw-flex-row tw-items-center tw-justify-center tw-py-6 xs:tw-py-8 3xl:tw-py-10 tw-px-[5%] xl:px-[7%] 3xl:px-[10%] tw-gap-4">
    <span class="tw-hidden md:tw-block">
        <svg xmlns="http://www.w3.org/2000/svg" width="23" height="30" viewBox="0 0 36 47" fill="none">
            <path d="M35.1321 23.0795C35.1321 19.6432 29.4392 18.8226 27.1312 18.1046C23.7462 17.0788 23.3872 17.3353 22.6179 13.3861C21.9511 9.94982 23.0795 0 17.5404 0C11.6936 0 13.6425 13.1809 12.0013 15.7966C9.64209 19.5406 0 17.3865 0 23.0795C0 28.3621 8.25732 26.4645 10.873 29.3879C13.3861 32.2087 11.8475 46.1077 17.5404 46.1077C22.7205 46.1077 21.7973 37.3375 22.5666 34.0551C23.1821 31.2855 23.7462 29.8495 26.208 28.4134C28.516 27.0799 35.1321 26.9773 35.1321 23.0282V23.0795Z"/>
        </svg>
    </span>
    
    <h1 class="tw-text-4xl lg:tw-text-5xl 3xl:tw-text-6xl tw-font-bold tw-tracking-tight tw-text-center tw-mb-0 tw-leading-none">
        <?php echo esc_html($titulo_dinamico); ?>
    </h1>
    
    <span class="tw-hidden md:tw-block">
        <svg xmlns="http://www.w3.org/2000/svg" width="23" height="30" viewBox="0 0 36 47" fill="none">
            <path d="M35.1321 23.0795C35.1321 19.6432 29.4392 18.8226 27.1312 18.1046C23.7462 17.0788 23.3872 17.3353 22.6179 13.3861C21.9511 9.94982 23.0795 0 17.5404 0C11.6936 0 13.6425 13.1809 12.0013 15.7966C9.64209 19.5406 0 17.3865 0 23.0795C0 28.3621 8.25732 26.4645 10.873 29.3879C13.3861 32.2087 11.8475 46.1077 17.5404 46.1077C22.7205 46.1077 21.7973 37.3375 22.5666 34.0551C23.1821 31.2855 23.7462 29.8495 26.208 28.4134C28.516 27.0799 35.1321 26.9773 35.1321 23.0282V23.0795Z"/>
        </svg>
    </span>
</div>