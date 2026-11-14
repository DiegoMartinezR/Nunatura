<?php
// Archivo: woodmart-child/contenido-blog-header.php

// 0. CAMPOS DE LA SUSCRIPCION
$titulo_suscripcion  = get_field('titulo_de_suscripcion', 'option');
$descripcion_suscripcion  = get_field('descripcion_de_seccion_suscripcion', 'option');
$imagen_portada_suscripcion = get_field('imagen_de_seccion_suscripcion', 'option');
?>

<style>
    body { font-family: 'Poppins', sans-serif; }
</style>

<?php 
    if ( $imagen_portada_suscripcion ) : 
        $imagen_src_suscripcion = wp_get_attachment_image_src( $imagen_portada_suscripcion, 'full' );
        $url_portada_suscripcion = $imagen_src_suscripcion[0];
    endif; 
?>

<div class="tw-min-h-[500px] 3xl:tw-min-h-[600px] tw-bg-cover tw-flex tw-flex-col lg:tw-items-center tw-justify-center tw-text-white tw-py-12 tw--mx-[5.5%] lg:tw--mx-[8.5%] tw-mt-12 lg:tw-mt-14"
        style="background-image: url('<?php echo esc_url($url_portada_suscripcion); ?>');">
    <div class="tw-text-center tw-px-[5%] tw-flex tw-flex-col tw-gap-4">
        <h2 class="reveal-fade-up tw-text-4xl lg:tw-text-5xl tw-font-bold tw-text-white tw-tracking-[0.42px]">
            <?php echo esc_html($titulo_suscripcion); ?> 
        </h2>
        <p class="reveal-fade-up tw-text-lg lg:tw-text-xl tw-leading-relaxed tw-font-medium tw-text-white tw-max-w-2xl tw-mx-auto tw-tracking-[0.42px]">
            <?php echo esc_html($descripcion_suscripcion); ?>
        </p>
        <div class="reveal-fade-up tw-w-full tw-mx-auto" style="transition-delay: 300ms;">
            <?php echo do_shortcode('[html_block id="104"]'); ?>
        </div>
    </div>                                        
</div>

