<?php
/**
 * Template Name: Tailwind About Page
 * Description: Una plantilla de página personalizada que utiliza Tailwind CSS para el diseño.
 */

// 0. CAMPOS DE LA SUSCRIPCION
$titulo_suscripcion  = get_field('titulo_de_suscripcion', 'option');
$descripcion_suscripcion  = get_field('descripcion_de_seccion_suscripcion', 'option');
$imagen_portada_suscripcion = get_field('imagen_de_seccion_suscripcion', 'option');

// 1. CAMPOS DE LA PORTADA
$titulo_about  = get_field('titulo_de_portada_conocenos', 'option');
$imagen_portada_id = get_field('imagen_de_portada_conocenos', 'option');

// 2. CAMPOS DE LA SECCIÓN QUIÉNES SOMOS
$titulo_seccion      = get_field('titulo_de_seccion', 'option') ?: 'Quienes somos';
$descripcion_seccion = get_field('descripcion_de_seccion', 'option') ?: 'Añade una descripción desde el administrador.';
$imagen_seccion_id   = get_field('imagen_de_seccion', 'option');

// 3. CAMPOS DE LA SECCIÓN HISTORIA
$titulo_historia      = get_field('titulo_de_seccion_2', 'option') ?: 'Nuestra Historia';
$descripcion_historia = get_field('descripcion_de_seccion_2', 'option') ?: 'Añade una descripción desde el administrador.';
$imagen_historia_id   = get_field('imagen_de_seccion_2', 'option');

// 4. TITULO DE LA LÍNEA DE TIEMPO
$titulo_timeline = get_field('titulo_de_seccion_linea_de_tiempo', 'option') ?: 'Naturales';

// 5. PROCESAR EL REPETIDOR DE LA LÍNEA DE TIEMPO (eventos)
$timelineData = [];

if ( have_rows('eventos', 'option') ) {
    while ( have_rows('eventos', 'option') ) {
        the_row();
        
        $ano         = get_sub_field('ano_de_evento');
        $desc        = get_sub_field('descripcion_de_evento');
        $img_main_id = get_sub_field('imagen_de_evento');
        $galeria     = get_sub_field('galeria_de_evento'); // Array de IDs de imágenes o URLs según config de ACF
        
        // Obtener URL de la imagen principal del evento
        $main_image_url = 'https://images.unsplash.com/photo-1505252585461-04db1eb84625?q=80&w=600&auto=format&fit=crop'; // Fallback por defecto
        if ( $img_main_id ) {
            $src_main = wp_get_attachment_image_src($img_main_id, 'large');
            if ( $src_main ) {
                $main_image_url = $src_main[0];
            }

            $alt_meta_main = get_post_meta($img_main_id, '_wp_attachment_image_alt', true);
            $post_attachment_main = get_post($img_main_id);
            $imagen_main_title = ($post_attachment_main && !empty($post_attachment_main->post_title)) ? $post_attachment_main->post_title : $alt_meta_main;
        }
        
        // Procesar las miniaturas de la galería
        $thumbnails = [];
        if ( !empty($galeria) ) {
            foreach ( $galeria as $image ) {
                // Si la galería devuelve un ID (Recomendado)
                if ( is_numeric($image) ) {
                    $src_thumb = wp_get_attachment_image_src($image, 'thumbnail');
                    if ( $src_thumb ) {
                        $thumbnails[] = $src_thumb[0];
                    }
                } else if ( is_array($image) ) { // Si devuelve Array
                    $thumbnails[] = $image['sizes']['thumbnail'] ?: $image['url'];
                } else { // Si devuelve URL de frente
                    $thumbnails[] = $image;
                }
            }
        }
        
        if ( $ano ) {
            $timelineData[] = [
                'year'        => $ano,
                'description' => $desc,
                'main_image'  => $main_image_url,
                'alt_meta_main'     => $alt_meta_main,   
                'imagen_main_title' => $imagen_main_title, 
                'thumbnails'  => $thumbnails
            ];
        }
    }
}

$yearsList = array_column($timelineData, 'year');

get_header(); ?>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        body { font-family: 'Poppins', sans-serif; }
        
        /* Personalización estricta de los bullets de Swiper */
        .swiper-pagination-bullet {
            width: 3rem !important;
            height: 3rem !important;
            background: transparent !important;
            opacity: 1 !important;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            transition: all 0.3s ease;
            flex-shrink: 0;
            font-family: 'Poppins', sans-serif;
        }

        @media (min-width: 1600px) {
            .swiper-pagination-bullet {
                font-size: 14px;
                width: 3.5rem !important;
                height: 3.5rem !important;
            }
            
        }

        .swiper-pagination-bullet-active {
            background: white !important;
            color: #0CC !important;
            border-radius: 9999px;
        }
        .swiper-button-disabled {
            opacity: 0.6 !important; 
            cursor: default !important; 
            pointer-events: none !important; 
        }

         /* 1. Título y Descripción: Fade-In Up */
        .reveal-fade-up {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 2s ease, transform 2s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }

        /* 2. Imagen: Revelación Orgánica (Zoom-In Sutil + Blur/Desenfoque) */
        .reveal-img-organic {
            opacity: 0;
            transform: scale(1.05);
            filter: blur(8px);
            transition: opacity 2s ease, transform 2s cubic-bezier(0.16, 1, 0.3, 1), filter 2s ease;
            will-change: opacity, transform, filter;
        }

        /* 3. Estado Activo (Cuando entran en pantalla) */
        .reveal-active {
            opacity: 1 !important;
            transform: translate(0, 0) scale(1) !important;
            filter: blur(0) !important;
        }

        .reveal-slide-left {
            opacity: 0;
            transform: translateX(-150px);
            transition: opacity 2s ease, transform 2s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }

        .reveal-slide-right {
            opacity: 0;
            transform: translateX(150px);
            transition: opacity 2s ease, transform 2s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }

        .reveal-slide-left-section {
            opacity: 0;
            transform: translateX(-150px);
            transition: opacity 3s ease, transform 3s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }

        .reveal-slide-right-section {
            opacity: 0;
            transform: translateX(150px);
            transition: opacity 3s ease, transform 3s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }
        
    </style>

    
    <div class="reveal-fade-up tw-w-full tw-flex tw-flex-row tw-items-center tw-justify-center tw-py-6 xs:tw-py-8 3xl:tw-py-10 tw-px-[5%] xl:px-[7%] 3xl:px-[10%] tw-gap-4">
        <span class="tw-hidden md:tw-block">
            <svg xmlns="http://www.w3.org/2000/svg" width="23" height="30" viewBox="0 0 36 47" fill="none">
                <path d="M35.1321 23.0795C35.1321 19.6432 29.4392 18.8226 27.1312 18.1046C23.7462 17.0788 23.3872 17.3353 22.6179 13.3861C21.9511 9.94982 23.0795 0 17.5404 0C11.6936 0 13.6425 13.1809 12.0013 15.7966C9.64209 19.5406 0 17.3865 0 23.0795C0 28.3621 8.25732 26.4645 10.873 29.3879C13.3861 32.2087 11.8475 46.1077 17.5404 46.1077C22.7205 46.1077 21.7973 37.3375 22.5666 34.0551C23.1821 31.2855 23.7462 29.8495 26.208 28.4134C28.516 27.0799 35.1321 26.9773 35.1321 23.0282V23.0795Z" fill="#007070"/>
            </svg>
        </span>
        <h1 class="tw-text-4xl lg:tw-text-5xl 3xl:tw-text-6xl tw-font-bold  tw-text-[#0CC] tw-tracking-tight tw-text-center tw-mb-0 tw-leading-none">
            <?php echo esc_html($titulo_about); ?>
        </h1>
        <span class="tw-hidden md:tw-block">
            <svg xmlns="http://www.w3.org/2000/svg" width="23" height="30" viewBox="0 0 36 47" fill="none">
                <path d="M35.1321 23.0795C35.1321 19.6432 29.4392 18.8226 27.1312 18.1046C23.7462 17.0788 23.3872 17.3353 22.6179 13.3861C21.9511 9.94982 23.0795 0 17.5404 0C11.6936 0 13.6425 13.1809 12.0013 15.7966C9.64209 19.5406 0 17.3865 0 23.0795C0 28.3621 8.25732 26.4645 10.873 29.3879C13.3861 32.2087 11.8475 46.1077 17.5404 46.1077C22.7205 46.1077 21.7973 37.3375 22.5666 34.0551C23.1821 31.2855 23.7462 29.8495 26.208 28.4134C28.516 27.0799 35.1321 26.9773 35.1321 23.0282V23.0795Z" fill="#007070"/>
            </svg>
        </span>
    </div>

    <?php 
        if ( $imagen_portada_id ) : 
            $imagen_src = wp_get_attachment_image_src( $imagen_portada_id, 'full' );
            $alt_meta = get_post_meta($imagen_portada_id, '_wp_attachment_image_alt', true);
            $post_attachment = get_post($imagen_portada_id);
            
            $imagen_portada_alt = !empty($alt_meta) ? $alt_meta : $titulo_about;
            $imagen_portada_title = ($post_attachment && !empty($post_attachment->post_title)) ? $post_attachment->post_title : $imagen_portada_alt;

            if ( $imagen_src ) : 
                $url_portada = $imagen_src[0];
                ?>
                <div class="reveal-img-organic tw-relative tw-overflow-hidden tw-min-h-[300px] xl:tw-min-h-[600px] 3xl:tw-min-h-[700px] tw-flex tw-flex-col lg:tw-items-center tw-justify-center tw-text-white">
                    <img class="tw-absolute tw-inset-0 tw-w-full tw-h-full tw-object-cover tw-object-top tw-z-0" 
                    src="<?php echo esc_url($url_portada); ?>" 
                    alt="<?php echo esc_attr($imagen_portada_alt); ?>" 
                    title="<?php echo esc_attr($imagen_portada_title); ?>"
                    loading="eager"> 
                </div>
            <?php 
            endif;
        endif; 
    ?>

    <div class="reveal-slide-left tw-relative tw-hidden lg:tw-block tw--top-[120px] lg:tw--top-[20px] tw-left-0 tw-w-full tw-h-full tw-z-0">
        <svg class="tw-absolute tw-max-w-[80px] lg:tw-max-w-[110px]" xmlns="http://www.w3.org/2000/svg" width="215" height="227" viewBox="0 0 215 227" fill="none">
            <path d="M-8.37085 2.98035C12.7667 -3.72673 48.1379 0.4888 67.1975 19.6901C75.7298 28.2854 80.2221 40.5017 88.5876 49.4459C103.238 65.1008 126.495 52.2212 144.481 53.6472C168.131 55.5288 188.313 70.2796 200.749 89.9588C259.71 183.286 107.187 247.309 35.5124 219.692C-11.0489 201.75 -46.123 159.64 -56.2866 110.981C-65.3497 67.5955 -58.4631 18.871 -8.37085 2.98035Z" fill="#00CCCC"/>
        </svg>
    </div>

   <?php 
        if ( $imagen_seccion_id ) : 
            // Extraemos la URL de la imagen original usando el ID obtenido de 'option'
            $imagen_src_seccion = wp_get_attachment_image_src( $imagen_seccion_id, 'full' );
            $alt_meta_seccion = get_post_meta($imagen_seccion_id, '_wp_attachment_image_alt', true);
            $post_attachment_seccion = get_post($imagen_seccion_id);
            
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

    <div class="tw-flex tw-flex-col lg:tw-flex-row tw-gap-4 lg:tw-gap-10 xl:tw-gap-16 2xl:tw-gap-20 tw-w-full tw-items-center tw-justify-center tw-px-[5%] xl:tw-px-[10%] tw-pt-12 tw-pb-0 lg:tw-pt-16 xl:tw-pt-20">
        <div class="reveal-slide-left tw-w-full xl:tw-w-1/2 tw-flex tw-flex-col tw-justify-center tw-items-center lg:tw-items-start tw-order-2 lg:tw-order-1">
            <div class="tw-relative tw-rounded-xl xl:tw-rounded-2xl 4xl:tw-rounded-3xl tw-w-full">
                <img src="<?php echo esc_url($url_portada_seccion); ?>" 
                     alt="<?php echo esc_attr($imagen_portada_alt_seccion); ?>" 
                     title="<?php echo esc_attr($imagen_portada_title_seccion ?? $imagen_portada_alt_seccion); ?>"
                 class="w-full tw-mx-auto sm:tw-max-w-lg tw-h-auto tw-rounded-3xl tw-object-contain tw-transition-transform tw-duration-500 hover:tw-scale-105">
            </div>
        </div>
    
        <div class="reveal-slide-right tw-w-full xl:tw-w-1/2 tw-flex tw-flex-col tw-gap-5 tw-justify-center tw-items-start tw-order-1 lg:tw-order-2">
            <h2 class="tw-text-[#0CC] tw-text-3xl sm:tw-text-3xl 2xl:tw-text-4xl tw-font-bold tw-tracking-tight !tw-leading-tight">
                <?php echo esc_html($titulo_seccion); ?>
            </h2>

            <div class="tw-text-[#161616] tw-text-base 2xl:tw-text-lg 3xl:tw-text-xl tw-tracking-normal tw-mb-0" >
                <?php 
                    echo wp_kses_post( wpautop( $descripcion_seccion ) ); 
                ?>
            </div>
        </div>
    </div>

   <?php 
        if ( $imagen_historia_id ) : 
            // Extraemos la URL de la imagen original usando el ID obtenido de 'option'
            $imagen_src_historia = wp_get_attachment_image_src( $imagen_historia_id, 'full' );
            $alt_meta_historia = get_post_meta($imagen_historia_id, '_wp_attachment_image_alt', true);
            $post_attachment_historia = get_post($imagen_historia_id);
            
            if ( !empty($alt_meta_historia) ) {
                $imagen_portada_alt_historia = $alt_meta_historia;
            }
           
            if ( $post_attachment_historia && !empty($post_attachment_historia->post_title) ) {
                $imagen_portada_title_historia = $post_attachment_historia->post_title;
            }

            if ( $imagen_src_historia ) : 
                $url_portada_historia = $imagen_src_historia[0];
            endif;
        endif; 
    ?>

    <div class="tw-flex tw-flex-col lg:tw-flex-row tw-gap-10 xl:tw-gap-16 2xl:tw-gap-20 tw-w-full tw-items-center tw-justify-center tw-px-[5%] xl:tw-px-[10%] tw-py-12 lg:tw-py-16 xl:tw-py-20 3xl:tw-mt-5">
        <div class="reveal-slide-left-section tw-w-full xl:tw-w-1/2 tw-flex tw-flex-col tw-gap-5 tw-justify-center tw-items-start order-2 lg:order-1">
            <h2 class="tw-text-[#0CC] tw-text-3xl sm:tw-text-3xl 2xl:tw-text-4xl tw-font-bold tw-tracking-tight !tw-leading-tight">
              <?php echo esc_html($titulo_historia); ?>
            </h2>

            <div class="tw-text-[#161616] tw-text-base 2xl:tw-text-lg 3xl:tw-text-xl tw-tracking-normal tw-mb-0" >
                <?php 
                    echo wp_kses_post( wpautop( $descripcion_historia ) ); 
                ?>
            </div>
        </div>
        
        <div class="reveal-slide-right-section tw-w-full xl:tw-w-1/2 tw-flex tw-flex-col tw-justify-center tw-items-center lg:tw-items-end order-1 lg:order-2 tw-z-50">
            <div class="tw-relative tw-rounded-xl xl:tw-rounded-2xl 4xl:tw-rounded-3xl tw-w-full ">
                <img src="<?php echo $url_portada_historia; ?>" 
                     alt="<?php echo esc_attr($imagen_portada_alt_historia); ?>" 
                     title="<?php echo esc_attr($imagen_portada_title_historia ?? $imagen_portada_alt_historia); ?>"
                     class="w-full tw-mx-auto sm:tw-max-w-lg tw-h-auto tw-rounded-3xl tw-object-contain tw-transition-transform tw-duration-500 hover:tw-scale-105">
            </div>
        </div>
    </div>

    
    <div class="reveal-slide-right tw-relative tw-hidden lg:tw-block tw--top-[180px] lg:tw--top-[250px] tw-w-full tw-h-full">
        <svg class="tw-absolute tw-right-0 tw-max-w-[80px] lg:tw-max-w-[110px] tw-z-0" xmlns="http://www.w3.org/2000/svg" width="198" height="226" viewBox="0 0 198 226" fill="none">
            <path d="M266.765 94.6895C257.206 74.6792 229.945 51.7508 202.901 50.9718C190.795 50.6227 178.809 55.6975 166.562 55.7087C145.122 55.7225 138.618 29.9448 125.279 17.7952C107.735 1.82393 83.1723 -2.81683 60.3085 1.56373C-48.1079 22.3522 9.46195 177.426 78.6223 210.844C123.551 232.553 178.261 229.354 220.717 203.5C258.574 180.449 289.42 142.108 266.765 94.6895Z" fill="#F87612"/>
        </svg>
    </div>

    <?php if ( !empty($timelineData) ) : ?>
        <div class="tw-bg-[#0CC] tw-text-white tw-overflow-x-hidden tw-flex tw-items-center tw-justify-center">

            <div class="tw-max-w-7xl tw-w-full tw-mx-auto tw-px-[5%] tw-py-9 md:tw-p-14 tw-flex tw-flex-col lg:tw-flex-row tw-gap-6 md:tw-gap-10  xl:tw-gap-14 2xl:tw-gap-16 tw-items-center lg:tw-items-stretch">
                

                <h1 class="reveal-fade-up tw-flex tw-text-4xl lg:tw-text-5xl tw-font-bold lg:tw-hidden tw-items-center tw-justify-center lg:tw-justify-start tw-gap-3 tw-text-white tw-mb-0">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="23" height="30" viewBox="0 0 33 43" fill="none">
                            <path d="M32.2385 21.1786C32.2385 18.0253 27.0145 17.2723 24.8966 16.6134C21.7904 15.6721 21.461 15.9075 20.755 12.2836C20.1432 9.13032 21.1786 0 16.0957 0C10.7305 0 12.5189 12.0953 11.0129 14.4956C8.84794 17.9312 0 15.9545 0 21.1786C0 26.0261 7.57722 24.2848 9.97746 26.9674C12.2836 29.5559 10.8717 42.3101 16.0957 42.3101C20.8491 42.3101 20.002 34.2622 20.7079 31.2502C21.2727 28.7087 21.7904 27.391 24.0495 26.0732C26.1673 24.8495 32.2385 24.7554 32.2385 21.1315V21.1786Z" fill="white"/>
                        </svg>
                    </span> 
                    <?php echo esc_html($titulo_timeline); ?> 
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="23" height="30" viewBox="0 0 33 43" fill="none">
                            <path d="M32.2385 21.1786C32.2385 18.0253 27.0145 17.2723 24.8966 16.6134C21.7904 15.6721 21.461 15.9075 20.755 12.2836C20.1432 9.13032 21.1786 0 16.0957 0C10.7305 0 12.5189 12.0953 11.0129 14.4956C8.84794 17.9312 0 15.9545 0 21.1786C0 26.0261 7.57722 24.2848 9.97746 26.9674C12.2836 29.5559 10.8717 42.3101 16.0957 42.3101C20.8491 42.3101 20.002 34.2622 20.7079 31.2502C21.2727 28.7087 21.7904 27.391 24.0495 26.0732C26.1673 24.8495 32.2385 24.7554 32.2385 21.1315V21.1786Z" fill="white"/>
                        </svg>
                    </span>
                </h1>

                <div class="reveal-slide-left-section tw-flex lg:tw-flex-col tw-items-center tw-justify-center tw-gap-6 tw-z-10 lg:tw-mt-[5%]">
                    <a id="prev-btn" class="tw-w-12 tw-h-12 tw-flex tw-items-center tw-justify-center tw-rounded-full  hover:tw-opacity-80 hover:tw-text-[#00c9b7] tw-transition tw-flex-shrink-0">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/up-arrow.png" alt="Anterior" class="tw-w-12 tw-h-12 -tw-rotate-90 lg:tw-rotate-0">
                    </a>

                    <div class="tw-w-[216px] lg:tw-w-auto lg:tw-h-[216px] tw-overflow-hidden tw-flex tw-justify-start lg:tw-justify-center tw-items-start">
                        <div id="custom-pagination" class="tw-flex lg:tw-flex-col tw-gap-2 tw-items-center tw-transition-transform tw-duration-300 tw-ease-in-out"></div>
                    </div>

                    <a id="next-btn" class="tw-w-12 tw-h-12 tw-flex tw-items-center tw-justify-center tw-rounded-full  hover:tw-opacity-80 hover:tw-text-[#00c9b7] tw-transition tw-flex-shrink-0">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/down-arrow.png" alt="Siguiente" class="tw-w-12 tw-h-12 -tw-rotate-90 lg:tw-rotate-0">
                    </a>
                </div>

                <div class="tw-flex-1 tw-flex tw-flex-col tw-w-full tw-overflow-hidden">
                    
                    <h1 class="reveal-fade-up tw-hidden tw-text-4xl lg:tw-text-5xl tw-font-bold tw-mb-4 lg:tw-flex tw-items-center tw-justify-center lg:tw-justify-start tw-gap-3 tw-text-white">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="23" height="30" viewBox="0 0 33 43" fill="none">
                                <path d="M32.2385 21.1786C32.2385 18.0253 27.0145 17.2723 24.8966 16.6134C21.7904 15.6721 21.461 15.9075 20.755 12.2836C20.1432 9.13032 21.1786 0 16.0957 0C10.7305 0 12.5189 12.0953 11.0129 14.4956C8.84794 17.9312 0 15.9545 0 21.1786C0 26.0261 7.57722 24.2848 9.97746 26.9674C12.2836 29.5559 10.8717 42.3101 16.0957 42.3101C20.8491 42.3101 20.002 34.2622 20.7079 31.2502C21.2727 28.7087 21.7904 27.391 24.0495 26.0732C26.1673 24.8495 32.2385 24.7554 32.2385 21.1315V21.1786Z" fill="white"/>
                            </svg>
                        </span> 
                        <?php echo esc_html($titulo_timeline); ?> 
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="23" height="30" viewBox="0 0 33 43" fill="none">
                                <path d="M32.2385 21.1786C32.2385 18.0253 27.0145 17.2723 24.8966 16.6134C21.7904 15.6721 21.461 15.9075 20.755 12.2836C20.1432 9.13032 21.1786 0 16.0957 0C10.7305 0 12.5189 12.0953 11.0129 14.4956C8.84794 17.9312 0 15.9545 0 21.1786C0 26.0261 7.57722 24.2848 9.97746 26.9674C12.2836 29.5559 10.8717 42.3101 16.0957 42.3101C20.8491 42.3101 20.002 34.2622 20.7079 31.2502C21.2727 28.7087 21.7904 27.391 24.0495 26.0732C26.1673 24.8495 32.2385 24.7554 32.2385 21.1315V21.1786Z" fill="white"/>
                            </svg>
                        </span>
                    </h1>

                    <div class="reveal-slide-right-section swiper timelineSwiper tw-w-full tw-flex-1">
                        <div class="swiper-wrapper">
                            
                            <?php foreach($timelineData as $item): ?>
                                <div class="swiper-slide tw-cursor-grab">
                                    <div class="tw-flex tw-flex-col lg:tw-flex-row tw-items-center tw-gap-5 md:tw-gap-8 lg:tw-gap-16 tw-h-full">
                                        
                                        <div class="tw-w-full lg:tw-w-1/3 tw-text-center lg:tw-text-left">
                                            <h2 class="tw-text-6xl md:tw-text-7xl tw-font-bold tw-mb-6 tw-tracking-tight tw-text-white">
                                                <?php echo esc_html($item['year']); ?>
                                            </h2>
                                            <p class="tw-text-lg md:tw-text-xl tw-leading-relaxed tw-font-medium tw-text-white">
                                                <?php echo esc_html($item['description']); ?>
                                            </p>
                                        </div>

                                        <div class="tw-w-full lg:tw-w-2/3 tw-flex tw-flex-col tw-items-center">
                                            <div class="tw-rounded-[3rem] tw-shadow-2xl tw-w-full tw-max-w-sm md:tw-max-w-md">
                                                <img src="<?= esc_url($item['main_image']) ?>" 
                                                     alt="<?= esc_html($item['alt_meta_main']) ?>" 
                                                     title="<?= esc_html($item['imagen_main_title']) ?>"
                                                     class="tw-main-image tw-w-full tw-h-80 md:tw-h-96 xl:tw-h-[400px] tw-object-cover tw-rounded-[2.5rem] tw-transition tw-duration-300">
                                            </div>
                                            
                                            <?php if(!empty($item['thumbnails'])): ?>
                                            <div class="tw-flex tw-gap-4 tw-mt-6">
                                                <?php foreach($item['thumbnails'] as $thumb): ?>
                                                <img src="<?= esc_url($thumb) ?>" alt="Miniatura" onclick="swapImage(this)" class="tw-cursor-pointer tw-w-16 tw-h-16 md:tw-w-20 md:tw-h-20 tw-rounded-xl tw-object-cover tw-border-2 tw-border-white tw-shadow-md hover:tw-opacity-80 tw-transition">
                                                <?php endforeach; ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>

                                    </div>
                                </div>
                            <?php endforeach; ?>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    <?php endif;?>
    
    <?php 
        if ( $imagen_portada_suscripcion ) : 
            $imagen_src_suscripcion = wp_get_attachment_image_src( $imagen_portada_suscripcion, 'full' );
            $url_portada_suscripcion = $imagen_src_suscripcion[0];
        endif; 
    ?>

    <div class="tw-min-h-[500px] 3xl:tw-min-h-[600px] tw-bg-cover tw-flex tw-flex-col lg:tw-items-center tw-justify-center tw-text-white tw-py-12"
         style="background-image: url('<?php echo esc_url($url_portada_suscripcion); ?>');">
        <div class="tw-text-center tw-px-[5%] tw-flex tw-flex-col tw-gap-4">
            <h2 class="reveal-fade-up tw-text-4xl md:tw-text-5xl tw-font-bold tw-text-white tw-tracking-[0.42px]">
                <?php echo esc_html($titulo_suscripcion); ?> 
            </h2>
            <p class="reveal-fade-up tw-text-lg md:tw-text-xl tw-leading-relaxed tw-font-medium tw-text-white tw-max-w-2xl tw-mx-auto tw-tracking-[0.42px]">
                <?php echo esc_html($descripcion_suscripcion); ?>
            </p>
            <div class="reveal-fade-up tw-w-full tw-mx-auto">
                <?php echo do_shortcode('[html_block id="104"]'); ?>
            </div>
        </div>                                        
    </div>
    
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <?php the_content(); ?>
    <?php endwhile; endif;?>
    
    
    <script>
        function swapImage(thumbElement) {
            // Buscamos el contenedor padre del slide actual
            const slideContainer = thumbElement.closest('.swiper-slide');
            // Encontramos la imagen principal dentro de este slide
            const mainImg = slideContainer.querySelector('.tw-main-image');
            
            // Guardamos la ruta de la imagen principal actual
            const currentMainSrc = mainImg.src;
            
            // Intercambiamos las rutas
            mainImg.src = thumbElement.src;
            thumbElement.src = currentMainSrc;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        const years = <?= json_encode($yearsList) ?>;

        const swiper = new Swiper('.timelineSwiper', {
            direction: 'horizontal',
            loop: false,
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            speed: 600,
            
            navigation: {
                nextEl: '#next-btn',
                prevEl: '#prev-btn',
            },
            
            pagination: {
                el: '#custom-pagination',
                clickable: true,
                renderBullet: function (index, className) {
                    return '<div class="' + className + '">' + years[index] + '</div>';
                },
            },
        });

        // Lógica para desplazar la paginación de años (Máximo 4 visibles)
        swiper.on('slideChange', function () {
            const activeIndex = swiper.activeIndex;
            const paginationEl = document.getElementById('custom-pagination');
            
            // Detectamos si estamos en versión escritorio (flex-col) o móvil (flex-row)
            const isDesktop = window.innerWidth >= 1024; // lg breakpoint de Tailwind
            
            // Solo desplazamos si hay más de 4 años en el arreglo
            if (years.length > 4) {
                let itemsToShift = 0;
                
                // Si el índice activo es mayor al 3ro (es decir, el 5to elemento o más), calculamos cuánto desplazar
                if (activeIndex >= 3) {
                    // Mantendremos el elemento activo al final de los 4 visibles
                    itemsToShift = activeIndex - 3; 
                }
                
                // 48px de alto/ancho del bullet (3rem) + 8px del gap (0.5rem) = 56px
                const stepSize = 56; 
                const translateValue = itemsToShift * stepSize;
                
                if (isDesktop) {
                    paginationEl.style.transform = `translateY(-${translateValue}px)`;
                } else {
                    paginationEl.style.transform = `translateX(-${translateValue}px)`;
                }
            }
        });

        // Recalcular al redimensionar la pantalla para evitar bugs de transformaciones
        window.addEventListener('resize', () => {
            swiper.emit('slideChange'); 
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const animatedElements = document.querySelectorAll(`
            .reveal-fade-up, 
            .reveal-img-organic, 
            .reveal-slide-left, 
            .reveal-slide-right,
            .reveal-slide-left-section,
            .reveal-slide-right-section
            `
        );

            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.15
            };

            const scrollObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('reveal-active');
                    } else {
                        entry.target.classList.remove('reveal-active');
                    }
                });
            }, observerOptions);

            animatedElements.forEach(element => {
                scrollObserver.observe(element);
            });
        });
    </script>

<?php get_footer(); ?>