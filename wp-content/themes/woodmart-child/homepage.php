<?php
/**
 * Template Name: Tailwind Home Page
 * Description: Una plantilla de página personalizada que utiliza Tailwind CSS para el diseño.
 */

get_header(); ?>

<?php
   $sliders = [];

    if ( have_rows('sliders', 'option') ) {
        while ( have_rows('sliders', 'option') ) {
            the_row();
            
            $tituloslider = get_sub_field('titulo_de_slider');
            $descslider        = get_sub_field('descripcion_de_slider');
            $img_main_id = get_sub_field('imagen_de_slider');
            $imgfondo_main_id = get_sub_field('fondo_de_slider');
            $titulobotonslider = get_sub_field('texto_de_boton');
            $urlslider = get_sub_field('link_de_boton');

            
            // Obtener URL de la imagen principal del evento
            $main_image_url = 'https://images.unsplash.com/photo-1505252585461-04db1eb84625?q=80&w=722&auto=format&fit=crop'; // Fallback por defecto
            if ( $img_main_id ) {
                $src_main = wp_get_attachment_image_src($img_main_id, 'full');
                if ( $src_main ) {
                    $main_image_url = $src_main[0];
                }

                $alt_meta_main = get_post_meta($img_main_id, '_wp_attachment_image_alt', true);
                $post_attachment_main = get_post($img_main_id);
                $imagen_main_title = ($post_attachment_main && !empty($post_attachment_main->post_title)) ? $post_attachment_main->post_title : $alt_meta_main;
            }

            $main_fondoimage_url = 'https://images.unsplash.com/photo-1505252585461-04db1eb84625?q=80&w=722&auto=format&fit=crop'; // Fallback por defecto
            if ( $imgfondo_main_id ) {
                $src_main_fondo = wp_get_attachment_image_src($imgfondo_main_id, 'full');
                if ( $src_main_fondo ) {
                    $main_fondoimage_url = $src_main_fondo[0];
                }
            }
            
            if ( $tituloslider ) {
                $sliders[] = [
                    'tituloslider' => $tituloslider,
                    'descslider' => $descslider,
                    'main_image'  => $main_image_url,
                    'alt_meta_main'     => $alt_meta_main,   
                    'imagen_main_title' => $imagen_main_title, 
                    'main_fondoimage_url'  => $main_fondoimage_url,
                    'text_boton' => $titulobotonslider,
                    'urlslider' => $urlslider,
                ];
            }
        }
    }

    // 2. CAMPOS DEL BANNER 1
    $titulo_izquierda     = get_field('titulo-izquierda', 'option');
    $titulo_centro    = get_field('titulo-centro', 'option');
    $titulo_derecha = get_field('titulo-derecha', 'option');
    $descripcion_de_banner = get_field('descripcion_de_banner', 'option');
    $imagen_de_banner   = get_field('imagen_de_banner', 'option');
    $texto_de_boton_de_banner   = get_field('texto_de_boton_de_banner', 'option');
    $link_de_boton_de_banner   = get_field('link_de_boton_de_banner', 'option');

    if ( $imagen_de_banner ) {
        $src_main_bammer_s1 = wp_get_attachment_image_src($imagen_de_banner, 'full');
        if ( $src_main_bammer_s1 ) {
            $main_image_url_banner_s1 = $src_main_bammer_s1[0];
        }

        $alt_meta_main_banner1 = get_post_meta($imagen_de_banner, '_wp_attachment_image_alt', true);
        $post_attachment_main_banner1 = get_post($imagen_de_banner);
        $imagen_main_title_banner1 = ($post_attachment_main_banner1 && !empty($post_attachment_main_banner1->post_title)) ? $post_attachment_main_banner1->post_title : $alt_meta_main_banner1;
    }


    // 2. CAMPOS DEL BANNER 2
    $imagen_banner_2_contorno     = get_field('imagen_borde_izquierda', 'option');
    $imagen_banner_2_centro    = get_field('imagen_centro_izquierda', 'option');
    $imagen_banner_2_derecha = get_field('imagen_derecha', 'option');

    if ( $imagen_banner_2_contorno ) {
        $src_imagen_banner_2_contorno = wp_get_attachment_image_src($imagen_banner_2_contorno, 'full');
        if ( $src_imagen_banner_2_contorno ) {
            $main_image_url_imagen_banner_2_contorno = $src_imagen_banner_2_contorno[0];
        }

        $alt_meta_imagen_banner_2_contorno = get_post_meta($imagen_banner_2_contorno, '_wp_attachment_image_alt', true);
        $post_attachment_imagen_banner_2_contorno = get_post($imagen_banner_2_contorno);
        $imagen_main_title_imagen_banner_2_contorno = ($post_attachment_imagen_banner_2_contorno && !empty($post_attachment_imagen_banner_2_contorno->post_title)) ? $post_attachment_imagen_banner_2_contorno->post_title : $alt_meta_imagen_banner_2_contorno;
    }

    if ( $imagen_banner_2_centro ) {
        $src_imagen_banner_2_centro = wp_get_attachment_image_src($imagen_banner_2_centro, 'full');
        if ( $src_imagen_banner_2_centro ) {
            $main_image_url_imagen_banner_2_centro = $src_imagen_banner_2_centro[0];
        }

        $alt_meta_imagen_banner_2_centro = get_post_meta($imagen_banner_2_centro, '_wp_attachment_image_alt', true);
        $post_attachment_imagen_banner_2_centro = get_post($imagen_banner_2_centro);
        $imagen_main_title_imagen_banner_2_centro = ($post_attachment_imagen_banner_2_centro && !empty($post_attachment_imagen_banner_2_centro->post_title)) ? $post_attachment_imagen_banner_2_centro->post_title : $alt_meta_imagen_banner_2_centro;
    }

    if ( $imagen_banner_2_derecha ) {
        $src_imagen_banner_2_derecha = wp_get_attachment_image_src($imagen_banner_2_derecha, 'full');
        if ( $src_imagen_banner_2_derecha ) {
            $main_image_url_imagen_banner_2_derecha= $src_imagen_banner_2_derecha[0];
        }

        $alt_meta_imagen_banner_2_derecha = get_post_meta($imagen_banner_2_derecha, '_wp_attachment_image_alt', true);
        $post_attachment_imagen_banner_2_derecha = get_post($imagen_banner_2_derecha);
        $imagen_main_title_imagen_banner_2_derecha = ($post_attachment_imagen_banner_2_derecha && !empty($post_attachment_imagen_banner_2_derecha->post_title)) ? $post_attachment_imagen_banner_2_derecha->post_title : $alt_meta_imagen_banner_2_derecha;
    }


    // 2. CAMPOS DE BENEFICIOS
    $titulo_beneficios     = get_field('titulo_de_seccion_beneficio', 'option');
    $imagen_beneficios   = get_field('imagen_de_seccion_beneficios', 'option');
   

    if ( $imagen_beneficios ) {
        $src_main_beneficio = wp_get_attachment_image_src($imagen_beneficios, 'full');
        if ( $src_main_beneficio ) {
            $main_image_url_beneficio = $src_main_beneficio[0];
        }

        $alt_meta_main_beneficios = get_post_meta($imagen_beneficios, '_wp_attachment_image_alt', true);
        $post_attachment_main_beneficios = get_post($imagen_beneficios);
        $imagen_main_title_beneficios= ($post_attachment_main_beneficios && !empty($post_attachment_main_beneficios->post_title)) ? $post_attachment_main_beneficios->post_title : $alt_meta_main_beneficios;
    }


    // 3. LISTA DEL REPETIDOR DE BENEFICIOS
    $beneficios_lista = [];

    if ( have_rows('beneficios', 'option') ) {
        while ( have_rows('beneficios', 'option') ) {
            the_row();
            
            $icono_id = get_sub_field('icono_de_beneficio');
            $titulo = get_sub_field('titulo_de_beneficio');
            $descripcion = get_sub_field('descripcion_de_beneficio');
            
            // Procesar la URL del icono
            $icono_url = '';
            if ( $icono_id ) {
                $icono_src = wp_get_attachment_image_src($icono_id, 'full');
                if ( $icono_src ) {
                    $icono_url = $icono_src[0];
                }
            }
            
            // Guardar en el arreglo
            if ( $titulo || $descripcion ) {
                $beneficios_lista[] = [
                    'icono_url'   => $icono_url,
                    'titulo'      => $titulo,
                    'descripcion' => $descripcion,
                ];
            }
        }
    }

    // 2. CAMPOS DE BENEFICIOS
    $titulo_categorias    = get_field('titulo_de_categorias', 'option');
    $titulo_blog   = get_field('titulo_de_blog', 'option');
?>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        body { font-family: 'Poppins', sans-serif; }

        #conocenos{
            font-family: 'amnesty', sans-serif;
        }

        @keyframes spin-clockwise {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin-slow {
            animation: spin-clockwise 30s linear infinite;
        }

        .heroSwiper .swiper-pagination-bullet {
            background: #ffffff;
            opacity: 0.6;
            width: 10px;
            height: 10px;
            transition: all 0.3s ease;
        }
        .heroSwiper .swiper-pagination-bullet-active {
            background: #00C2C7; /* El turquesa de Nunatura */
            opacity: 1;
            width: 12px;
            height: 12px;
        }


        /* --- ESTILOS PARA ACORDEÓN DE BENEFICIOS --- */
        .tw-beneficio-item.tw-is-open .tw-beneficio-icon-wrapper svg {
            transform: rotate(180deg);
        }
        
        .tw-beneficio-icon-wrapper svg {
            transition: transform 0.3s ease;
        }

        .tw-beneficio-content {
            display: grid;
            grid-template-rows: 0fr;
            transition: grid-template-rows 0.3s ease-in-out, opacity 0.3s ease-in-out;
            opacity: 0;
        }

        .tw-beneficio-item.tw-is-open .tw-beneficio-content {
            grid-template-rows: 1fr;
            opacity: 1;
        }

        .tw-beneficio-content-inner {
            overflow: hidden;
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

        .reveal-slide-right-rotate {
            opacity: 0;
            transform: translateX(250px) rotate(180deg);
            transition: opacity 1.5s cubic-bezier(0.16, 1, 0.3, 1), 
                        transform 6s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }

        /* Estado activo para el giro */
        .reveal-active.reveal-slide-right-rotate {
            opacity: 1 !important;
            transform: translateX(0) rotate(0deg) !important;
        }
       
    </style>

    
    <div class="swiper heroSwiper tw-w-full tw-relative">
        <div class="swiper-wrapper">
            
            <?php if ( !empty($sliders) ) : ?>
                <?php foreach ($sliders as $slide) : ?>
                    <div class="swiper-slide">
                        <div class="tw-min-h-[350px] xl:tw-min-h-[500px] tw-bg-cover tw-bg-center tw-flex tw-flex-col lg:tw-flex-row tw-gap-0 lg:tw-gap-10 xl:tw-gap-16 2xl:tw-gap-20 tw-w-full tw-items-center tw-justify-center tw-px-[5%] xl:tw-px-[7%] 3xl:tw-px-[10%]"
                             style="background-image: url('<?php echo esc_url($slide['main_fondoimage_url']); ?>');">
                            
                            <div class="reveal-slide-left-section tw-w-full xl:tw-w-1/2 3xl:tw-w-1/2 tw-flex tw-flex-row tw-justify-center tw-items-center lg:tw-items-end lg:tw-pt-12 tw-order-2 lg:tw-order-1">
                                <div class="tw-relative tw-h-[300px] sm:tw-h-[550px] 3xl:tw-h-[650px]">
                                    <img src="<?php echo esc_url($slide['main_image']); ?>" 
                                         alt="<?php echo esc_attr($slide['alt_meta_main']); ?>"
                                         title="<?php echo esc_attr($slide['imagen_main_title']); ?>" 
                                         class="tw-w-full tw-h-full tw-object-contain tw-transition-transform tw-duration-500 hover:tw-scale-105">
                                </div>
                            </div>
                        
                            <div class="tw-min-h-80 tw-w-full xl:tw-w-1/2 3xl:tw-w-1/2 tw-flex tw-flex-col tw-gap-3 sm:tw-gap-5 tw-justify-center tw-items-end tw-text-end tw-pt-2 tw-pb-8 lg:tw-py-12 tw-order-1 lg:tw-order-2">
                                <h1 class="reveal-slide-right-section tw-text-white tw-text-3xl sm:tw-text-4xl 2xl:tw-text-5xl 3xl:tw-text-6xl tw-font-bold tw-tracking-tight !tw-leading-[1.1] tw-pb-2 tw-line-clamp-3 tw-mb-0">
                                   <?php echo esc_html($slide['tituloslider']); ?>
                                </h1>

                                <p class="reveal-slide-right-section tw-text-white tw-text-base 2xl:tw-text-xl 3xl:tw-text-2xl tw-tracking-normal tw-font-light tw-mb-0 tw-line-clamp-4" >
                                    <?php echo esc_html($slide['descslider']); ?>
                                </p>
                                
                                <?php if ( !empty($slide['text_boton']) && !empty($slide['urlslider']) ) : ?>
                                    <a href="<?php echo esc_url($slide['urlslider']); ?>" class="reveal-slide-right-section tw-bg-white tw-text-base 3xl:tw-text-xl tw-text-[#161616] tw-px-10 tw-py-3.5 3xl:tw-px-12 3xl:tw-py-4 tw-rounded-full tw-font-semibold ">
                                        <?php echo esc_html($slide['text_boton']); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                            
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            
        </div>
        
        <div class="swiper-pagination tw-bottom-2 sm:!tw-bottom-4"></div>
    </div>


    <div class=" tw-w-full tw-flex tw-flex-row tw-items-center tw-justify-center tw-bg-[#0CC] tw-pt-8 md:tw-pt-10 lg:tw-pt-12 lg:tw-pb-6 tw-px-[5%] xl:px-[7%] 3xl:px-[10%] tw-gap-4">
        <span class="reveal-fade-up tw-hidden md:tw-block">
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="33" viewBox="0 0 26 33" fill="none">
                <path d="M25.034 16.4217C25.034 13.9767 20.9833 13.3928 19.3411 12.8819C16.9326 12.1521 16.6771 12.3345 16.1297 9.5246C15.6553 7.07959 16.4582 0 12.517 0C8.35681 0 9.74358 9.37863 8.57581 11.2398C6.86065 13.9037 0 12.371 0 16.4217C0 20.1805 5.87531 18.8302 7.73644 20.9103C9.52458 22.9174 8.42976 32.807 12.4805 32.807C16.1662 32.807 15.5094 26.5667 16.0568 24.2312C16.4947 22.2606 16.8961 21.2388 18.6477 20.217C20.2899 19.2682 24.9975 19.1952 24.9975 16.3852L25.034 16.4217Z" fill="white"/>
            </svg>
        </span> 
        <h1 id="conocenos" class="reveal-fade-up tw-text-[38px] sm:tw-text-[40px] lg:tw-text-6xl tw-font-light tw-text-white tw-tracking-tight tw-text-center tw-mb-0 tw-leading-none">
            <?php echo esc_html($titulo_izquierda); ?> <span class="tw-pb-2 tw-text-3xl md:tw-text-4xl tw-block md:tw-inline-block tw-underline tw-decoration-dotted tw-underline-offset-8"><?php echo esc_html($titulo_centro); ?></span> <?php echo esc_html($titulo_derecha); ?>
        </h1>
        <span class="reveal-fade-up tw-hidden md:tw-block">
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="33" viewBox="0 0 26 33" fill="none">
                <path d="M25.034 16.4217C25.034 13.9767 20.9833 13.3928 19.3411 12.8819C16.9326 12.1521 16.6771 12.3345 16.1297 9.5246C15.6553 7.07959 16.4582 0 12.517 0C8.35681 0 9.74358 9.37863 8.57581 11.2398C6.86065 13.9037 0 12.371 0 16.4217C0 20.1805 5.87531 18.8302 7.73644 20.9103C9.52458 22.9174 8.42976 32.807 12.4805 32.807C16.1662 32.807 15.5094 26.5667 16.0568 24.2312C16.4947 22.2606 16.8961 21.2388 18.6477 20.217C20.2899 19.2682 24.9975 19.1952 24.9975 16.3852L25.034 16.4217Z" fill="white"/>
            </svg>
        </span>
    </div>


    <div class="tw-bg-[#0CC] tw-min-h-[300px] tw-bg-cover tw-bg-center tw-flex tw-flex-col lg:tw-flex-row tw-gap-0 lg:tw-gap-10 xl:tw-gap-16 2xl:tw-gap-20 tw-w-full tw-items-center tw-justify-center  tw-pt-5 tw-pb-10 md:tw-pt-0 lg:tw-pb-8 3xl:tw-pb-12">
        
        <div class="reveal-slide-left-section tw-w-full xl:tw-w-1/2 3xl:tw-w-1/2 tw-flex tw-flex-row tw-justify-center tw-items-center lg:tw-items-center">
            <div class="tw-relative tw-h-[300px] sm:tw-h-[400px] xl:tw-h-[450px] 2xl:tw-h-[520px] 3xl:tw-h-[600px] tw-w-full">
                <img src="<?php echo esc_url($main_image_url_banner_s1); ?>" 
                alt="<?php echo esc_attr($alt_meta_main_banner1); ?>"
                title="<?php echo esc_attr($imagen_main_title_banner1); ?>"
                class="tw-w-full tw-h-full tw-object-contain tw-object-left">
            </div>
        </div>
    
        <div class="reveal-slide-right-section tw-w-full xl:tw-w-1/2 3xl:tw-w-1/2 tw-flex tw-flex-col tw-gap-4 tw-justify-center tw-items-start tw-px-[5%] 3xl:tw-px-[6%]">
            <h2 class="tw-text-left tw-text-white tw-text-2xl sm:tw-text-3xl 2xl:tw-text-4xl 3xl:tw-text-[38px] tw-font-semibold tw-tracking-normal !tw-leading-[1.2] tw-mb-0">
               <?php echo esc_html($descripcion_de_banner); ?>
            </h2>   

            <?php if ( !empty($link_de_boton_de_banner) && !empty($texto_de_boton_de_banner) ) : ?>                        
                <a href="<?php echo esc_url($link_de_boton_de_banner); ?>" class="tw-text-left tw-bg-white tw-text-base 3xl:tw-text-xl tw-text-[#161616] tw-px-12 tw-py-3.5 3xl:tw-px-16 3xl:tw-py-4 tw-rounded-full tw-font-semibold">
                    <?php echo esc_html($texto_de_boton_de_banner); ?>
                </a>
            <?php endif; ?>
        </div>
        
    </div>


    <div class="tw-flex tw-flex-col lg:tw-flex-row tw-gap-0 sm:tw-gap-5 lg:tw-gap-10 xl:tw-gap-16 2xl:tw-gap-20 tw-w-full tw-items-center tw-justify-center tw-px-[5%] xl:tw-px-[10%] tw-py-8 sm:tw-py-6 lg:tw-py-8 xl:tw-pb-8 xl:tw-pt-12">
        
        <div class="reveal-slide-left-section tw-w-full xl:tw-w-2/5 tw-flex tw-flex-col tw-justify-center tw-items-center lg:tw-items-center tw-pt-2">
            <div class="tw-relative tw-h-[300px] sm:tw-h-[350px] xl:tw-h-[370px] 3xl:tw-h-[450px]">
               <img src="<?php echo esc_url($main_image_url_imagen_banner_2_contorno); ?>" 
                    alt="<?php echo esc_attr($alt_meta_imagen_banner_2_contorno); ?>" 
                    title="<?php echo esc_attr($imagen_main_title_imagen_banner_2_contorno ?? $alt_meta_imagen_banner_2_contorno); ?>"
                    class="tw-absolute tw-w-full tw-h-full tw-object-contain animate-spin-slow">
                
               <img src="<?php echo esc_url($main_image_url_imagen_banner_2_centro); ?>" 
                    alt="<?php echo esc_attr($alt_meta_imagen_banner_2_centro); ?>" 
                    title="<?php echo esc_attr($imagen_main_title_imagen_banner_2_centro ?? $alt_meta_imagen_banner_2_centro); ?>" 
                    class="tw-relative tw-w-full tw-h-full tw-object-contain">
            </div>
        </div>
    
        <div class="reveal-slide-right-section tw-w-full xl:tw-w-3/5 tw-flex tw-flex-col tw-gap-5 tw-justify-center tw-items-center">
            <div class="tw-relative tw-h-[300px] sm:tw-h-[350px] xl:tw-h-[450px] 3xl:tw-h-[500px]">
                <img src="<?php echo esc_url($main_image_url_imagen_banner_2_derecha); ?>" 
                     alt="<?php echo esc_attr($alt_meta_imagen_banner_2_derecha); ?>" 
                     title="<?php echo esc_attr($imagen_main_title_imagen_banner_2_derecha ?? $alt_meta_imagen_banner_2_derecha); ?>" 
                     class="tw-w-full tw-h-full tw-object-contain tw-transition-transform tw-duration-500 hover:tw-scale-105">
            </div>
        </div>
        
    </div>


    <?php if ( !empty($beneficios_lista) ) : ?>                  
    
        <div class="reveal-fade-up tw-w-full tw-flex tw-flex-row tw-items-center tw-justify-center tw-pb-6 xs:tw-py-6 tw-px-[5%] xl:px-[7%] 3xl:px-[10%] tw-gap-4">
            <span class="tw-hidden md:tw-block">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="31" viewBox="0 0 24 31" fill="none">
                    <path d="M23.2412 15.268C23.2412 12.9947 19.4751 12.4519 17.9483 11.9769C15.709 11.2983 15.4715 11.4679 14.9626 8.85542C14.5215 6.58219 15.268 0 11.6037 0C7.73577 0 9.02506 8.7197 7.93934 10.4501C6.37861 12.9269 0 11.5019 0 15.268C0 18.7626 5.46254 17.5073 7.19291 19.4412C8.85542 21.3073 7.83755 30.502 11.6037 30.502C15.0305 30.502 14.4197 24.7002 14.9287 22.5287C15.3358 20.6966 15.709 19.7466 17.3376 18.7966C18.8644 17.9144 23.2412 17.8465 23.2412 15.234V15.268Z" fill="#00CCCC"/>
                </svg>
            </span> 
            <h1 class="tw-text-4xl md:tw-text-5xl 3xl:tw-text-6xl tw-font-semibold tw-text-center tw-text-[#009191] tw-mb-0 tw-leading-[1.1] tw-tracking-tight">
                <?php echo esc_html($titulo_beneficios); ?>
            </h1>
            <span class="tw-hidden md:tw-block">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="31" viewBox="0 0 24 31" fill="none">
                    <path d="M23.2412 15.268C23.2412 12.9947 19.4751 12.4519 17.9483 11.9769C15.709 11.2983 15.4715 11.4679 14.9626 8.85542C14.5215 6.58219 15.268 0 11.6037 0C7.73577 0 9.02506 8.7197 7.93934 10.4501C6.37861 12.9269 0 11.5019 0 15.268C0 18.7626 5.46254 17.5073 7.19291 19.4412C8.85542 21.3073 7.83755 30.502 11.6037 30.502C15.0305 30.502 14.4197 24.7002 14.9287 22.5287C15.3358 20.6966 15.709 19.7466 17.3376 18.7966C18.8644 17.9144 23.2412 17.8465 23.2412 15.234V15.268Z" fill="#00CCCC"/>
                </svg>
            </span>
        </div>
          
        <div class="tw-flex tw-flex-col lg:tw-flex-row tw-gap-10 xl:tw-gap-16 3xl:tw-gap-20 tw-w-full tw-items-center tw-justify-center tw-px-[5%] 2xl:tw-px-[10%] tw-py-12 xl:tw-py-16">
            
            <div class="tw-beneficios-wrapper tw-w-full lg:tw-w-1/2 tw-flex tw-flex-col tw-justify-center tw-gap-4 xl:tw-gap-5">
                <?php foreach ( $beneficios_lista as $beneficio ) : ?>
                    <div class="reveal-slide-left-section tw-beneficio-item tw-bg-white  tw-overflow-hidden tw-transition-all tw-duration-300">
                        
                        <!-- Trigger del acordeón (Fondo #0CC y texto blanco fijos) -->
                        <div class="tw-beneficio-trigger tw-text-base lg:tw-text-lg 3xl:tw-text-xl tw-flex tw-justify-between tw-items-center tw-font-semibold tw-text-white tw-px-4 lg:tw-px-6 tw-py-4 tw-bg-[#0CC] tw-rounded-l-[35px] tw-rounded-br-[35px] tw-cursor-pointer tw-transition-all tw-duration-300 tw-group">
                            
                            <div class="tw-flex tw-items-center tw-gap-4">
                                <?php if ( !empty($beneficio['icono_url']) ) : ?>
                                    <img src="<?php echo esc_url($beneficio['icono_url']); ?>" alt="<?php echo esc_attr($beneficio['titulo']); ?>" class="tw-w-10 tw-h-10 tw-object-contain tw-transition-transform tw-duration-500 tw-group-hover:tw-scale-105">
                                <?php endif; ?>
                                
                                <?php if ( !empty($beneficio['titulo']) ) : ?>
                                    <h2 class="tw-mb-0 tw-text-xl xl:tw-text-2xl 3xl:tw-text-3xl tw-font-semibold tw-text-inherit">
                                        <?php echo esc_html($beneficio['titulo']); ?>
                                    </h2>
                                <?php endif; ?>
                            </div>

                            <!-- Icono SVG en color blanco (fill="white") -->
                            <span class="tw-beneficio-icon-wrapper tw-ml-4 tw-flex-shrink-0 tw-flex tw-flex-col tw-items-center tw-justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 16 13" fill="none">
                                    <path d="M15.551 1.64138C15.2667 1.35868 14.882 1.20001 14.481 1.20001C14.08 1.20001 13.6953 1.35868 13.4109 1.64138L7.96205 7.01438L2.58905 1.64138C2.30468 1.35868 1.91999 1.20001 1.51901 1.20001C1.11803 1.20001 0.73334 1.35868 0.448962 1.64138C0.306701 1.78247 0.193785 1.95034 0.116729 2.1353C0.0396722 2.32026 0 2.51864 0 2.71901C0 2.91938 0.0396722 3.11776 0.116729 3.30272C0.193785 3.48768 0.306701 3.65555 0.448962 3.79665L6.88442 10.2321C7.02552 10.3744 7.19339 10.4873 7.37835 10.5643C7.5633 10.6414 7.76169 10.6811 7.96205 10.6811C8.16242 10.6811 8.36081 10.6414 8.54576 10.5643C8.73072 10.4873 8.89859 10.3744 9.03969 10.2321L15.551 3.79665C15.6933 3.65555 15.8062 3.48768 15.8833 3.30272C15.9603 3.11776 16 2.91938 16 2.71901C16 2.51864 15.9603 2.32026 15.8833 2.1353C15.8062 1.95034 15.6933 1.78247 15.551 1.64138Z" fill="white"/>
                                </svg>
                            </span>
                        </div>

                        <!-- Contenido Desplegable (Fondo blanco y texto negro) -->
                        <div class="tw-beneficio-content tw-text-base lg:tw-text-lg 3xl:tw-text-xl tw-font-dmsans tw-bg-white">
                            <div class="tw-beneficio-content-inner tw-px-6 lg:tw-px-8">
                                <div class="tw-py-4 tw-text-black">
                                    <?php if ( !empty($beneficio['descripcion']) ) : ?>
                                        <p class="tw-mb-0">
                                            <?php echo nl2br(esc_html($beneficio['descripcion'])); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
        
            <div class="reveal-slide-right-rotate tw-w-full lg:tw-w-1/2 tw-flex tw-flex-col tw-justify-center tw-items-center">
                <div class="tw-relative tw-h-[350px] sm:tw-h-[480px] 3xl:tw-h-[500px] tw-rounded-xl xl:tw-rounded-2xl 4xl:tw-rounded-3xl tw-mt-5 lg:tw-mt-10">
                    <img src="<?php echo esc_url($main_image_url_beneficio); ?>" 
                        alt="<?php echo esc_attr($alt_meta_main_beneficios); ?>" 
                        title="<?php echo esc_attr($imagen_main_title_beneficios ?? $alt_meta_main_beneficios); ?>"
                        class="tw-w-full tw-h-full tw-object-contain tw-object-center">
                </div>
            </div>
            
        </div>
    <?php endif; ?>


    <div class="reveal-fade-up tw-w-full tw-flex tw-flex-row tw-items-center tw-justify-center tw-pt-6 tw-pb-12 tw-px-[5%] xl:px-[7%] 3xl:px-[10%] tw-gap-4">
        <span class="tw-hidden md:tw-block">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="31" viewBox="0 0 24 31" fill="none">
                <path d="M23.2412 15.268C23.2412 12.9947 19.4751 12.4519 17.9483 11.9769C15.709 11.2983 15.4715 11.4679 14.9626 8.85542C14.5215 6.58219 15.268 0 11.6037 0C7.73577 0 9.02506 8.7197 7.93934 10.4501C6.37861 12.9269 0 11.5019 0 15.268C0 18.7626 5.46254 17.5073 7.19291 19.4412C8.85542 21.3073 7.83755 30.502 11.6037 30.502C15.0305 30.502 14.4197 24.7002 14.9287 22.5287C15.3358 20.6966 15.709 19.7466 17.3376 18.7966C18.8644 17.9144 23.2412 17.8465 23.2412 15.234V15.268Z" fill="#E26B10"/>
            </svg>
        </span> 
        <h1 class="tw-text-4xl md:tw-text-5xl 3xl:tw-text-6xl tw-font-semibold tw-text-center tw-text-[#F87612] tw-mb-0 leading-[1.1] tw-tracking-tight">
            <?php echo esc_html($titulo_categorias); ?>
        </h1>
        <span class="tw-hidden md:tw-block">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="31" viewBox="0 0 24 31" fill="none">
                <path d="M23.2412 15.268C23.2412 12.9947 19.4751 12.4519 17.9483 11.9769C15.709 11.2983 15.4715 11.4679 14.9626 8.85542C14.5215 6.58219 15.268 0 11.6037 0C7.73577 0 9.02506 8.7197 7.93934 10.4501C6.37861 12.9269 0 11.5019 0 15.268C0 18.7626 5.46254 17.5073 7.19291 19.4412C8.85542 21.3073 7.83755 30.502 11.6037 30.502C15.0305 30.502 14.4197 24.7002 14.9287 22.5287C15.3358 20.6966 15.709 19.7466 17.3376 18.7966C18.8644 17.9144 23.2412 17.8465 23.2412 15.234V15.268Z" fill="#E26B10"/>
            </svg>
        </span>
    </div>
    <div class="reveal-img-organic tw-w-full tw-mx-auto">
        <?php echo do_shortcode('[html_block id="182"]'); ?>
    </div>


    <div class="tw-w-full tw-flex tw-flex-row tw-items-center tw-justify-center tw-py-6 tw-px-[5%] xl:px-[7%] 3xl:px-[10%] tw-gap-4 tw-bg-[#0CC] tw-pt-12 lg:tw-pt-16 lg:tw-pb-6">
        <span class="reveal-fade-up tw-hidden md:tw-block">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="31" viewBox="0 0 24 31" fill="none">
                <path d="M23.2412 15.268C23.2412 12.9947 19.4751 12.4519 17.9483 11.9769C15.709 11.2983 15.4715 11.4679 14.9626 8.85542C14.5215 6.58219 15.268 0 11.6037 0C7.73577 0 9.02506 8.7197 7.93934 10.4501C6.37861 12.9269 0 11.5019 0 15.268C0 18.7626 5.46254 17.5073 7.19291 19.4412C8.85542 21.3073 7.83755 30.502 11.6037 30.502C15.0305 30.502 14.4197 24.7002 14.9287 22.5287C15.3358 20.6966 15.709 19.7466 17.3376 18.7966C18.8644 17.9144 23.2412 17.8465 23.2412 15.234V15.268Z" fill="white"/>
            </svg>
        </span> 
        <h1 class="reveal-fade-up tw-text-4xl md:tw-text-5xl 3xl:tw-text-6xl tw-font-semibold tw-text-center tw-text-white tw-mb-0">
            <?php echo esc_html($titulo_blog); ?>
        </h1>
        <span class="reveal-fade-up tw-hidden md:tw-block">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="31" viewBox="0 0 24 31" fill="none">
                <path d="M23.2412 15.268C23.2412 12.9947 19.4751 12.4519 17.9483 11.9769C15.709 11.2983 15.4715 11.4679 14.9626 8.85542C14.5215 6.58219 15.268 0 11.6037 0C7.73577 0 9.02506 8.7197 7.93934 10.4501C6.37861 12.9269 0 11.5019 0 15.268C0 18.7626 5.46254 17.5073 7.19291 19.4412C8.85542 21.3073 7.83755 30.502 11.6037 30.502C15.0305 30.502 14.4197 24.7002 14.9287 22.5287C15.3358 20.6966 15.709 19.7466 17.3376 18.7966C18.8644 17.9144 23.2412 17.8465 23.2412 15.234V15.268Z" fill="white"/>
            </svg>
        </span>
    </div>

    <div class="reveal-img-organic tw-w-full tw-mx-auto tw-bg-[#0CC] tw-pt-6 lg:tw-pt-10 tw-pb-12 xl:tw-pb-16 3xl:tw-pt-12 3xl:tw-pb-20 tw-px-[5%] xl:tw-px-[7%] 3xl:tw-px-[10%]">
        <?php echo do_shortcode('[html_block id="231"]'); ?>
    </div>
   

    <div class="tw-min-h-[500px] 3xl:tw-min-h-[600px] tw-bg-cover tw-flex tw-flex-col lg:tw-items-center tw-justify-center tw-text-white tw-py-12"
         style="background-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/img/fondo_boletin.webp');">
        <div class="tw-text-center tw-px-[5%] tw-flex tw-flex-col tw-gap-4">
            <h2 class="reveal-fade-up tw-text-4xl md:tw-text-5xl tw-font-bold tw-text-white tw-tracking-[0.42px]">
                ¿Quieres descuentos exclusivos?
            </h2>
            <p class="reveal-fade-up tw-text-lg md:tw-text-xl tw-leading-relaxed tw-font-medium tw-text-white tw-max-w-2xl tw-mx-auto tw-tracking-[0.42px]">
                Regístrate en nuestro newsletter y sé el primero en enterarte de nuestras promociones, lanzamientos de nuevos productos y ofertas relámpago. ¡No te quedes fuera!
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
        const heroSwiper = new Swiper('.heroSwiper', {
            slidesPerView: 1,
            loop: true,
            effect: 'fade',
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.heroSwiper .swiper-pagination',
                clickable: true,
            },
            
            on: {
                init: function (swiper) {
                    // Animamos el primer slide al iniciar
                    animateSlide(swiper.slides[swiper.activeIndex]);
                },
                slideChange: function (swiper) {
                    // Al cambiar, limpiamos clases y animamos el nuevo slide
                    const allSlides = swiper.slides;
                    allSlides.forEach(slide => {
                        const animatedElements = slide.querySelectorAll('.reveal-slide-left-section, .reveal-slide-right-section');
                        animatedElements.forEach(el => el.classList.remove('reveal-active'));
                    });
                    
                    // Animamos el slide actual
                    animateSlide(swiper.slides[swiper.activeIndex]);
                }
            }
        });

        // Función auxiliar para activar la animación
        function animateSlide(slideElement) {
            const elementsToAnimate = slideElement.querySelectorAll('.reveal-slide-left-section, .reveal-slide-right-section');
            elementsToAnimate.forEach(el => {
                // Un pequeño timeout para que el navegador procese la remoción/adición
                setTimeout(() => {
                    el.classList.add('reveal-active');
                }, 500);
            });
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const beneficiosWrapper = document.querySelector('.tw-beneficios-wrapper');

            if (!beneficiosWrapper) return;

            beneficiosWrapper.addEventListener('click', function (e) {
                // Buscamos si el clic fue en el trigger
                const trigger = e.target.closest('.tw-beneficio-trigger');
                if (!trigger) return;

                const currentItem = trigger.closest('.tw-beneficio-item');
                const isAlreadyOpen = currentItem.classList.contains('tw-is-open');

                // 1. Cierra todos los demás beneficios
                beneficiosWrapper.querySelectorAll('.tw-beneficio-item').forEach(item => {
                    if (item !== currentItem) {
                        item.classList.remove('tw-is-open');
                    }
                });

                // 2. Abre o cierra el beneficio actual
                if (!isAlreadyOpen) {
                    currentItem.classList.add('tw-is-open');
                } else {
                    currentItem.classList.remove('tw-is-open');
                }
            });
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
            .reveal-slide-right-section,
            .reveal-slide-right-rotate
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