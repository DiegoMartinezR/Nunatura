<?php
// Archivo: woodmart-child/contenido-producto.php
?>

<style>
    body { font-family: 'Poppins', sans-serif; }
</style>
<?php
/**
 * Template Name: Tailwind Ingredientes Page
 * Description: Plantilla personalizada para beneficios usando vectores SVG inline con prefijo tw-.
 */

// 1. Sección Beneficios
$imagen_de_seccion_beneficio = get_field('imagen_de_seccion_beneficio');
$color_fondo_i = get_field('color_de_fondo_de_items_beneficios'); 
$color_fondo_s = get_field('color_de_fondo_seccion_de_productos');

$color_fondo_items = '#00B1EB';
$color_fondo_seccion = '#ffffff';
$queried_object = get_queried_object();

if ( $color_fondo_i ) {
    $color_fondo_items = $color_fondo_i;
}

if ( $color_fondo_s ) {
    $color_fondo_seccion = $color_fondo_s;
}


$ingredientes = [];
$contador = 1;

if ( have_rows('item_de_beneficio') ) { // Eliminado $queried_object
    while ( have_rows('item_de_beneficio') && $contador <= 6 ) {
        the_row();
        $icon_benefit = get_sub_field('icono_de_beneficio_producto');
        $url_img_benefit = '';

        if ( is_numeric($icon_benefit) ) {
            $imagen_src = wp_get_attachment_image_src( $icon_benefit, 'full' );
            $url_img_benefit = $imagen_src ? $imagen_src[0] : '';
        } elseif ( is_array($icon_benefit) && isset($icon_benefit['url']) ) {
            $url_img_benefit = $icon_benefit['url'];
        } elseif ( is_string($icon_benefit) ) {
            $url_img_benefit = $icon_benefit;
        }

        $ingredientes[] = [
            'id'          => $contador,
            'name'        => get_sub_field('titulo_de_beneficio_producto'),
            'description' => get_sub_field('descripcion_de_beneficio_producto'),
            'icon'        => $url_img_benefit
        ];
        
        $contador++;
    }
}


// 2. Sección Descripción
    $imagen_de_seccion_descripcion = get_field('imagen_de_seccion_descripcion');
    $color_de_fondo_de_seccion_descripcion = get_field('color_de_fondo_de_seccion_descripcion');
    $titulo_de_seccion_de_descripcion = get_field('titulo_de_seccion_de_descripcion');
    $descripcion_de_seccion_de_producto = get_field('descripcion_de_seccion_de_producto');

    $color_fondo_descripcion = '#0cc';

    if ( $color_de_fondo_de_seccion_descripcion ) {
        $color_fondo_descripcion = $color_de_fondo_de_seccion_descripcion;
    }


    // 3. Sección Valor técnico
    $imagen_de_seccion_valor_tecnico = get_field('imagen_de_seccion_valor_tecnico');
    $color_de_fondo_de_seccion_valor_tecnico = get_field('color_de_fondo_de_seccion_valor_tecnico');
    $titulo_de_seccion_de_valor_tecnico = get_field('titulo_de_seccion_de_valor_tecnico');
    $descripcion_de_seccion_de_valor_tecnico = get_field('descripcion_de_seccion_de_valor_tecnico');

    $color_fondo_valor_tecnico = '#0cc';

    if ( $color_de_fondo_de_seccion_valor_tecnico ) {
        $color_fondo_valor_tecnico = $color_de_fondo_de_seccion_valor_tecnico;
    }
?>

<style>
    body { font-family: 'Poppins', sans-serif; }
    
    /* Forzar que los SVGs de fondo actúen como capas vectoriales responsivas */
    .tw-svg-background svg {
        width: 100%;
        height: auto;
        display: block;
    }

    .tw-fondo-seccion {
        background-color: <?php echo esc_attr($color_fondo_seccion); ?> !important;
    }

    .tw-fondo-items {
        background-color: <?php echo esc_attr($color_fondo_items); ?> !important;
    }

    .tw-fondo-descripcion {
        background-color: <?php echo esc_attr($color_fondo_descripcion); ?> !important;
    }

    .tw-fondo-valor-tecnico {
        background-color: <?php echo esc_attr($color_fondo_valor_tecnico); ?> !important;
    }

    .tw-fondo-seccion svg path, .tw-fondo-seccion svg {
        fill: <?php echo esc_attr($color_fondo_items); ?> !important;
    }
   
</style>


<?php 
    $url_portada_seccion = '';
    $imagen_portada_alt_seccion = '';
    $imagen_portada_title_seccion = '';

    if ( $imagen_de_seccion_beneficio ) : 
        // Extraemos la URL de la imagen original usando el ID obtenido de 'option'
        $imagen_src_seccion = wp_get_attachment_image_src( $imagen_de_seccion_beneficio, 'full' );
        $alt_meta_seccion = get_post_meta($imagen_de_seccion_beneficio, '_wp_attachment_image_alt', true);
        $post_attachment_seccion = get_post($imagen_de_seccion_beneficio);
        
        if ( !empty($alt_meta_seccion) ) {
            $imagen_portada_alt_seccion = $alt_meta_seccion;
        }
        
        if ( $post_attachment_seccion && !empty($post_attachment_seccion->post_title) ) {
            $imagen_portada_title_seccion = $post_attachment_seccion->post_title;
        }

        if ( $imagen_src_seccion ) : 
            $url_portada_seccion = $imagen_src_seccion[0];
        endif;
    endif; 
?>

<?php if ( !empty($ingredientes) && !empty($imagen_de_seccion_beneficio)) : ?>
    <div class="tw-w-full tw-fondo-seccion tw-py-10 xl:tw-py-14 3xl:tw-py-18 mn:tw-px-[3%] xl:tw-px-[5%] 2xl:tw-px-[7%] 3xl:tw-px-[10%]">
        <div class="tw-max-w-3xl lgl:tw-max-w-[1400px] 3xl:tw-max-w-[1600px] tw-mx-auto tw-grid tw-grid-cols-1 lgl:tw-grid-cols-12 tw-gap-12 tw-items-center">
            
            <div class="lgl:tw-col-span-4 xl:tw-col-span-5 tw-flex tw-justify-center tw-relative tw-z-10">
                <img src="<?php echo esc_url($url_portada_seccion); ?>" 
                    alt="<?php echo esc_attr($imagen_portada_alt_seccion); ?>" 
                    title="<?php echo esc_attr($imagen_portada_title_seccion ?? $imagen_portada_alt_seccion); ?>"
                    class="tw-w-full tw-max-w-[300px] 2xl:tw-max-w-[370px] tw-h-auto tw-object-contain">
            </div>

            <div class="lgl:tw-col-span-8 xl:tw-col-span-7 tw-grid tw-grid-cols-1 md:tw-grid-cols-2 md:tw-gap-x-10 xl:tw-gap-x-0 tw-relative tw-z-10">
                
                <?php foreach ($ingredientes as $item) : ?>
                    
                    <?php if ($item['id'] === 1) : ?>
                        <div class="tw-relative tw-flex tw-items-center tw-w-full tw-max-w-[340px] 3xl:tw-max-w-[360px] tw-mx-auto">
        
                            <div class="tw-absolute tw-inset-0 tw-flex tw-items-center tw-w-full tw-z-0">
                                <div class="tw-flex tw-items-center tw-w-full">
                                    <div class="tw-w-[140px] tw-h-[140px] 3xl:tw-w-[160px] 3xl:tw-h-[160px] tw-flex-shrink-0 tw-mr-[-30px] tw-z-10">
                                        <svg class="tw-w-full tw-h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 160" fill="none">
                                            <path d="M79.8601 159.721C123.966 159.721 159.721 123.966 159.721 79.8606C159.721 35.7548 123.966 0 79.8601 0C35.7543 0 -0.000488281 35.7548 -0.000488281 79.8606C-0.000488281 123.966 35.7543 159.721 79.8601 159.721Z" fill="#00B1EB"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="tw-relative tw-z-20 tw-w-full tw-flex tw-flex-row tw-items-center tw-justify-end tw-py-4 tw-px-5 tw-min-h-[95px]">
                                <div class="tw-w-[70px] tw-h-[70px] tw-flex-shrink-0 tw-flex tw-items-center tw-justify-center tw-mx-2 3xl:tw-mx-4">
                                    <img src="<?php echo esc_url($item['icon']); ?>"
                                        alt="<?php echo esc_attr($item['name']); ?>"
                                        class="tw-w-full tw-h-full tw-object-contain">
                                </div>
                                <div class="tw-text-white tw-fondo-items tw-p-3  tw-rounded-r-[35px] tw-min-h-[120px] tw-max-w-[225px] 3xl:tw-min-h-[130px] 3xl:tw-max-w-[260px] tw-flex tw-flex-col tw-justify-center tw-gap-1">
                                    <h2 class="tw-font-bold tw-text-[22px] tw-mb-0 tw-leading-tight tw-text-white"><?php echo esc_html($item['name']); ?></h2>
                                    <p class="tw-text-base tw-opacity-95 tw-leading-tight tw-mb-0"><?php echo esc_html($item['description']); ?></p>
                                </div>
                            </div>

                        </div>

                    <?php elseif ($item['id'] === 2) : ?>
                        <div class="tw-relative tw-flex tw-flex-row tw-items-center tw-justify-start tw-w-full tw-max-w-[340px] 3xl:tw-max-w-[360px] tw-mx-auto">
        
                            <div class="tw-absolute tw-inset-0 tw-flex tw-items-center tw-w-full tw-z-0">
                                <div class="tw-flex tw-items-center tw-w-full tw-justify-end"> 
                                    <div class="tw-w-[125px] tw-h-[125px] 3xl:tw-w-[140px] 3xl:tw-h-[140px] tw-flex-shrink-0 tw-ml-[-30px] tw-z-10"> 
                                        <svg class="tw-w-full tw-h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 160" fill="none">
                                            <path d="M79.8601 159.721C123.966 159.721 159.721 123.966 159.721 79.8606C159.721 35.7548 123.966 0 79.8601 0C35.7543 0 -0.000488281 35.7548 -0.000488281 79.8606C-0.000488281 123.966 35.7543 159.721 79.8601 159.721Z" fill="#00B1EB"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="tw-relative tw-z-20 tw-w-full tw-flex tw-flex-row tw-items-center tw-justify-end tw-py-4 tw-px-5 tw-min-h-[95px]">
                                <div class="tw-text-white tw-fondo-items tw-py-4 tw-px-6 tw-rounded-l-[35px] tw-min-h-[100px] tw-max-w-[240px] 3xl:tw-min-h-[110px] 3xl:tw-max-w-[260px] tw-flex tw-flex-col tw-justify-center tw-gap-1 tw-text-left tw-mr-[-10px]">
                                    <h2 class="tw-font-bold tw-text-[22px] tw-mb-0 tw-leading-tight tw-text-white"><?php echo esc_html($item['name']); ?></h2>
                                    <p class="tw-text-base tw-opacity-95 tw-leading-tight tw-mb-0"><?php echo esc_html($item['description']); ?></p>
                                </div>

                                <div class="tw-w-[70px] tw-h-[70px] tw-flex-shrink-0 tw-flex tw-items-center tw-justify-center tw-mr-2">
                                <img src="<?php echo esc_url($item['icon']); ?>"
                                        alt="<?php echo esc_attr($item['name']); ?>"
                                        class="tw-w-full tw-h-full tw-object-contain">
                                </div>
                            </div>

                        </div>

                    <?php elseif ($item['id'] === 3) : ?>
                        <div class="tw-relative tw-flex tw-flex-row tw-items-center tw-justify-start tw-w-full tw-max-w-[340px] 3xl:tw-max-w-[360px] tw-mx-auto md:tw--ml-5 lgl:tw-mx-0 3xl:tw-mx-auto ">
                            <div class="tw-flex tw-flex-row tw-justify-start">
                                <div class="tw-absolute tw-inset-0 tw-flex tw-items-center tw-w-full tw-z-0">
                                    <div class="tw-flex tw-items-center tw-w-full tw-justify-end"> 
                                        <div class="tw-w-[125px] tw-h-[125px] 3xl:tw-w-[140px] 3xl:tw-h-[140px] tw-flex-shrink-0 tw-ml-[-30px] tw-z-10"> 
                                            <svg class="tw-w-full tw-h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 160" fill="none">
                                                <path d="M79.8601 159.721C123.966 159.721 159.721 123.966 159.721 79.8606C159.721 35.7548 123.966 0 79.8601 0C35.7543 0 -0.000488281 35.7548 -0.000488281 79.8606C-0.000488281 123.966 35.7543 159.721 79.8601 159.721Z" fill="#00B1EB"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="tw-relative tw-z-20 tw-w-full tw-flex tw-flex-row tw-items-center tw-justify-end tw-py-4 tw-px-5 tw-min-h-[95px]">
                                    <div class="tw-text-white tw-fondo-items tw-py-4 tw-px-6 tw-rounded-l-[35px] tw-min-h-[100px] tw-max-w-[240px] 3xl:tw-min-h-[110px] 3xl:tw-max-w-[260px] tw-flex tw-flex-col tw-justify-center tw-gap-1 tw-text-left tw-mr-[-10px]">
                                        <h2 class="tw-font-bold tw-text-[22px] tw-mb-0 tw-leading-tight tw-text-white"><?php echo esc_html($item['name']); ?></h2>
                                        <p class="tw-text-base tw-opacity-95 tw-leading-tight tw-mb-0"><?php echo esc_html($item['description']); ?></p>
                                    </div>

                                    <div class="tw-w-[70px] tw-h-[70px] tw-flex-shrink-0 tw-flex tw-items-center tw-justify-center tw-mr-2">
                                        <img src="<?php echo esc_url($item['icon']); ?>"
                                        alt="<?php echo esc_attr($item['name']); ?>"
                                        class="tw-w-full tw-h-full tw-object-contain">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php elseif ($item['id'] === 4) : ?>
                        <div class="tw-relative tw-flex tw-flex-row tw-items-center tw-justify-end tw-w-full tw-max-w-[340px] 3xl:tw-max-w-[360px] tw-mx-auto md:tw--mr-5 lgl:tw-mx-0 lgl:tw-ml-auto 3xl:tw-mx-auto ">
                            <div class="tw-flex tw-flex-row tw-justify-end">
                                <div class="tw-absolute tw-inset-0 tw-flex tw-items-center tw-w-full tw-z-0">
                                    <div class="tw-flex tw-items-center tw-w-full">
                                        <div class="tw-w-[140px] tw-h-[140px] 3xl:tw-w-[160px] 3xl:tw-h-[160px] tw-flex-shrink-0 tw-mr-[-30px] tw-z-10">
                                            <svg class="tw-w-full tw-h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 160" fill="none">
                                                <path d="M79.8601 159.721C123.966 159.721 159.721 123.966 159.721 79.8606C159.721 35.7548 123.966 0 79.8601 0C35.7543 0 -0.000488281 35.7548 -0.000488281 79.8606C-0.000488281 123.966 35.7543 159.721 79.8601 159.721Z" fill="#00B1EB"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="tw-relative tw-z-20 tw-w-full tw-flex tw-items-center tw-py-4 tw-px-5 tw-min-h-[95px]">
                                    <div class="tw-w-[70px] tw-h-[70px] tw-flex-shrink-0 tw-flex tw-items-center tw-justify-center tw-mx-2 3xl:tw-mx-4">
                                        <img src="<?php echo esc_url($item['icon']); ?>"
                                        alt="<?php echo esc_attr($item['name']); ?>"
                                        class="tw-w-full tw-h-full tw-object-contain">
                                    </div>
                                    <div class="tw-text-white tw-fondo-items tw-p-3  tw-rounded-r-[35px] tw-min-h-[120px] tw-max-w-[225px] 3xl:tw-min-h-[130px] 3xl:tw-max-w-[260px] tw-flex tw-flex-col tw-justify-center tw-gap-1">
                                        <h2 class="tw-font-bold tw-text-[22px] tw-mb-0 tw-leading-tight tw-text-white"><?php echo esc_html($item['name']); ?></h2>
                                        <p class="tw-text-base tw-opacity-95 tw-leading-tight tw-mb-0"><?php echo esc_html($item['description']); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php elseif ($item['id'] === 5) : ?>
                        <div class="tw-relative tw-flex tw-items-center tw-w-full tw-max-w-[340px] 3xl:tw-max-w-[360px] tw-mx-auto">
        
                            <div class="tw-absolute tw-inset-0 tw-flex tw-items-center tw-w-full tw-z-0">
                                <div class="tw-flex tw-items-center tw-w-full">
                                    <div class="tw-w-[140px] tw-h-[140px] 3xl:tw-w-[160px] 3xl:tw-h-[160px] tw-flex-shrink-0 tw-mr-[-30px] tw-z-10">
                                        <svg class="tw-w-full tw-h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 160" fill="none">
                                            <path d="M79.8601 159.721C123.966 159.721 159.721 123.966 159.721 79.8606C159.721 35.7548 123.966 0 79.8601 0C35.7543 0 -0.000488281 35.7548 -0.000488281 79.8606C-0.000488281 123.966 35.7543 159.721 79.8601 159.721Z" fill="#00B1EB"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="tw-relative tw-z-20 tw-w-full tw-flex tw-items-center tw-py-4 tw-px-5 tw-min-h-[95px]">
                                <div class="tw-w-[70px] tw-h-[70px] tw-flex-shrink-0 tw-flex tw-items-center tw-justify-center tw-mx-2 3xl:tw-mx-4">
                                    <img src="<?php echo esc_url($item['icon']); ?>"
                                        alt="<?php echo esc_attr($item['name']); ?>"
                                        class="tw-w-full tw-h-full tw-object-contain">
                                </div>
                                <div class="tw-text-white tw-fondo-items tw-p-3  tw-rounded-r-[35px] tw-min-h-[120px] tw-max-w-[225px] 3xl:tw-min-h-[130px] 3xl:tw-max-w-[260px] tw-flex tw-flex-col tw-justify-center tw-gap-1">
                                    <h2 class="tw-font-bold tw-text-[22px] tw-mb-0 tw-leading-tight tw-text-white"><?php echo esc_html($item['name']); ?></h2>
                                    <p class="tw-text-base tw-opacity-95 tw-leading-tight tw-mb-0"><?php echo esc_html($item['description']); ?></p>
                                </div>
                            </div>

                        </div>

                    <?php elseif ($item['id'] === 6) : ?>
                        <div class="tw-relative tw-flex tw-flex-row tw-items-center tw-justify-end tw-w-full tw-max-w-[340px] 3xl:tw-max-w-[360px] tw-mx-auto">
        
                            <div class="tw-absolute tw-inset-0 tw-flex tw-items-center tw-w-full tw-z-0">
                                <div class="tw-flex tw-items-center tw-w-full tw-justify-end"> 
                                    <div class="tw-w-[140px] tw-h-[140px] 3xl:tw-w-[160px] 3xl:tw-h-[160px] tw-flex-shrink-0 tw-ml-[-30px] tw-z-10"> 
                                        <svg class="tw-w-full tw-h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 160" fill="none">
                                            <path d="M79.8601 159.721C123.966 159.721 159.721 123.966 159.721 79.8606C159.721 35.7548 123.966 0 79.8601 0C35.7543 0 -0.000488281 35.7548 -0.000488281 79.8606C-0.000488281 123.966 35.7543 159.721 79.8601 159.721Z" fill="#00B1EB"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="tw-relative tw-z-20 tw-w-full tw-flex tw-flex-row tw-items-center tw-justify-end tw-py-4 tw-px-5 tw-min-h-[95px]">
                                <div class="tw-text-white tw-fondo-items tw-py-4 tw-px-6 tw-rounded-l-[35px] tw-min-h-[100px] tw-max-w-[240px] 3xl:tw-min-h-[130px] 3xl:tw-max-w-[280px] tw-flex tw-flex-col tw-justify-center tw-gap-1 tw-text-left tw-mr-[-10px]">
                                    <h2 class="tw-font-bold tw-text-[22px] tw-mb-0 tw-leading-tight tw-text-white"><?php echo esc_html($item['name']); ?></h2>
                                    <p class="tw-text-base tw-opacity-95 tw-leading-tight tw-mb-0"><?php echo esc_html($item['description']); ?></p>
                                </div>

                                <div class="tw-w-[70px] tw-h-[70px] tw-flex-shrink-0 tw-flex tw-items-center tw-justify-center tw-mr-2">
                                    <img src="<?php echo esc_url($item['icon']); ?>"
                                        alt="<?php echo esc_attr($item['name']); ?>"
                                        class="tw-w-full tw-h-full tw-object-contain">
                                </div>
                            </div>

                        </div>
                    <?php endif; ?>

                <?php endforeach; ?>

            </div>

        </div>
    </div>
<?php endif;?>


<?php 
    $url_portada_descripcion = '';
    $imagen_portada_alt_descripcion = '';
    $imagen_portada_title_descripcion = '';

    if ( $imagen_de_seccion_descripcion ) : 
        // Extraemos la URL de la imagen original usando el ID obtenido de 'option'
        $imagen_src_des = wp_get_attachment_image_src( $imagen_de_seccion_descripcion, 'full' );
        $alt_meta_des = get_post_meta($imagen_de_seccion_descripcion, '_wp_attachment_image_alt', true);
        $post_attachment_des = get_post($imagen_de_seccion_descripcion);
        
        if ( !empty($alt_meta_des) ) {
            $imagen_portada_alt_descripcion = $alt_meta_des;
        }
        
        if ( $post_attachment_des && !empty($post_attachment_des->post_title) ) {
            $imagen_portada_title_descripcion = $post_attachment_des->post_title;
        }

        if ( $imagen_src_des ) : 
            $url_portada_descripcion = $imagen_src_des[0];
        endif;
    endif; 
?>

<?php if ( !empty($descripcion_de_seccion_de_producto) && !empty($url_portada_descripcion)) : ?>
    <div class="tw-fondo-descripcion tw-min-h-[300px] tw-bg-cover tw-bg-center tw-flex tw-flex-col lg:tw-flex-row tw-gap-5 lg:tw-gap-10 xl:tw-gap-16 2xl:tw-gap-20 tw-w-full tw-items-center tw-justify-center tw-pt-10 tw-pb-0">
        
        <div class="tw-w-full xl:tw-w-1/2 3xl:tw-w-1/2 tw-flex tw-flex-col tw-gap-4 tw-justify-center tw-items-start tw-px-[5%] sm:tw-pl-[5%] 2xl:tw-pl-[8%] 2xl:tw-pr-[4%] 3xl:tw-px-[8%]">
            <h2 class="tw-text-left tw-text-white tw-text-2xl sm:tw-text-3xl 2xl:tw-text-4xl 3xl:tw-text-[38px] tw-font-semibold tw-tracking-normal !tw-leading-[1.2] tw-mb-0">
                <?php echo esc_html($titulo_de_seccion_de_descripcion); ?>      
            </h2>
            <div class="tw-mb-0 tw-text-base xl:tw-text-lg 3xl:tw-text-xl tw-text-white">
                <?php echo do_shortcode($descripcion_de_seccion_de_producto); ?>          
            </div>
        </div>

        <div class="tw-relative tw-w-full xl:tw-w-1/2 3xl:tw-w-1/2 tw-flex tw-flex-row tw-justify-center tw-items-center lg:tw-items-end">
            
            <div class="tw-absolute tw-inset-0 tw-z-10">
                <svg class="tw-w-full tw-h-full tw-opacity-60" xmlns="http://www.w3.org/2000/svg" width="655" height="502" viewBox="0 0 655 502" fill="none">
                    <path d="M60.1392 53.9848C101.158 16.7464 185.873 -10.5065 249.148 13.2552C277.473 23.8913 300.393 46.9567 328.703 58.5588C378.271 78.8577 417.678 25.3948 460.008 9.90732C515.675 -10.4427 576.864 2.04399 625.595 33.7862C856.657 184.341 576.942 488.529 385.421 500.435C261.003 508.168 137.51 449.055 63.7685 349.135C-1.98361 260.045 -37.0731 142.224 60.1392 53.9848Z" fill="#000"/>
                </svg>      
            </div>
        
            <div class="tw-relative tw-z-20 tw-h-[300px] sm:tw-h-[400px] xl:tw-h-[450px] 2xl:tw-h-[520px] 3xl:tw-h-[600px] tw-w-full">
                    <img src="<?php echo esc_url($url_portada_descripcion); ?>" 
                        alt="<?php echo esc_attr($imagen_portada_alt_descripcion); ?>" 
                        title="<?php echo esc_attr($imagen_portada_title_descripcion ?? $imagen_portada_alt_descripcion); ?>"
                        class="tw-w-full tw-h-full tw-object-contain tw-object-center">
            </div>

        </div>
        
    </div>
<?php endif;?>

<?php 
    $url_portada_valor_tecnico = '';
    $imagen_portada_alt_valor_tecnico = '';
    $imagen_portada_title_valor_tecnico = '';

    if ( $imagen_de_seccion_valor_tecnico ) : 
        // Extraemos la URL de la imagen original usando el ID obtenido de 'option'
        $imagen_src_val = wp_get_attachment_image_src( $imagen_de_seccion_valor_tecnico, 'full' );
        $alt_meta_val = get_post_meta($imagen_de_seccion_valor_tecnico, '_wp_attachment_image_alt', true);
        $post_attachment_val = get_post($imagen_de_seccion_valor_tecnico);
        
        if ( !empty($alt_meta_val) ) {
            $imagen_portada_alt_valor_tecnico = $alt_meta_val;
        }
        
        if ( $post_attachment_val && !empty($post_attachment_val->post_title) ) {
            $imagen_portada_title_valor_tecnico = $post_attachment_val->post_title;
        }

        if ( $imagen_src_val ) : 
            $url_portada_valor_tecnico = $imagen_src_val[0];
        endif;
    endif; 
?>

<?php if ( !empty($descripcion_de_seccion_de_valor_tecnico) && !empty($url_portada_valor_tecnico)) : ?>
    <div class="tw-fondo-descripcion tw-min-h-[300px] tw-bg-cover tw-bg-center tw-flex tw-flex-col lg:tw-flex-row tw-gap-5 lg:tw-gap-10 xl:tw-gap-16 2xl:tw-gap-20 tw-w-full tw-items-center tw-justify-center tw-py-10 tw-px-[5%] lg:tw-px-[8%]">
        
        <div class="tw-w-full xl:tw-w-1/2 3xl:tw-w-1/2 tw-flex tw-flex-col tw-gap-4 tw-justify-center tw-items-start lg:tw-pr-[5%]">
            <h2 class="tw-text-left tw-text-white tw-text-2xl sm:tw-text-3xl 2xl:tw-text-4xl 3xl:tw-text-[38px] tw-font-semibold tw-tracking-normal !tw-leading-[1.2] tw-mb-0">
                <?php echo esc_html($titulo_de_seccion_de_valor_tecnico); ?>      
            </h2>
            <div class="tw-mb-0 tw-text-base xl:tw-text-lg 3xl:tw-text-xl tw-text-white">
                <?php echo do_shortcode($descripcion_de_seccion_de_valor_tecnico); ?>          
            </div>
        </div>

        <div class="tw-relative tw-w-full xl:tw-w-1/2 3xl:tw-w-1/2 tw-flex tw-flex-row tw-justify-center tw-items-center lg:tw-items-center">
            
            <div class="tw-relative tw-z-20 tw-h-[300px] sm:tw-h-[400px] xl:tw-h-[450px] 2xl:tw-h-[520px] 3xl:tw-h-[600px] tw-w-full">
                    <img src="<?php echo esc_url($url_portada_valor_tecnico); ?>" 
                        alt="<?php echo esc_attr($imagen_portada_alt_valor_tecnico); ?>" 
                        title="<?php echo esc_attr($imagen_portada_title_valor_tecnico ?? $imagen_portada_alt_valor_tecnico); ?>"
                        class="tw-w-full tw-h-full tw-object-contain tw-object-center">
            </div>

        </div>
        
    </div>
<?php endif;?>


<?php the_content(); ?>
