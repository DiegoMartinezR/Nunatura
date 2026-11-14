<?php
/**
 * Template Name: Tailwind Products Page
 * Description: Una plantilla de página personalizada que utiliza Tailwind CSS para el diseño.
 */

// 0. CAMPOS DE LA SUSCRIPCION
$titulo_suscripcion  = get_field('titulo_de_suscripcion', 'option');
$descripcion_suscripcion  = get_field('descripcion_de_seccion_suscripcion', 'option');
$imagen_portada_suscripcion = get_field('imagen_de_seccion_suscripcion', 'option');

// 1. CAMPOS DE LA PORTADA
$titulo_productos = get_field('titulo_de_productos', 'option');
$imagen_productos = get_field('imagen_de_portada_productos', 'option');


// 2. CAMPOS DE LA SECCIÓN PRODUCTOS
$titulo_de_seccion_productos      = get_field('titulo_de_seccion_productos', 'option');
$descripcion_de_seccion_productos = get_field('descripcion_de_seccion_productos', 'option');
$imagen_de_seccion_productos   = get_field('imagen_de_seccion_productos', 'option');
$texto_para_boton_de_seccion_productos   = get_field('texto_para_boton_de_seccion_productos', 'option');
$url_para_boton_de_seccion_productos   = get_field('url_para_boton_de_seccion_productos', 'option');

// 3. CAMPOS DE LA SECCIÓN HISTORIA
$titulo_historia      = get_field('titulo_de_seccion_productos_2', 'option');
$descripcion_historia = get_field('descripcion_de_seccion_productos_2', 'option');
$imagen_historia_id   = get_field('imagen_de_seccion_productos_2', 'option');
$texto_para_boton_de_seccion_productos_2   = get_field('texto_para_boton_de_seccion_productos_2', 'option');
$url_para_boton_de_seccion_productos_2   = get_field('url_para_boton_de_seccion_productos_2', 'option');

get_header(); ?>

    <style>
        body { font-family: 'Poppins', sans-serif; }

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
            <?php echo esc_html($titulo_productos); ?>
        </h1> 

        <span class="tw-hidden md:tw-block">
            <svg xmlns="http://www.w3.org/2000/svg" width="23" height="30" viewBox="0 0 36 47" fill="none">
                <path d="M35.1321 23.0795C35.1321 19.6432 29.4392 18.8226 27.1312 18.1046C23.7462 17.0788 23.3872 17.3353 22.6179 13.3861C21.9511 9.94982 23.0795 0 17.5404 0C11.6936 0 13.6425 13.1809 12.0013 15.7966C9.64209 19.5406 0 17.3865 0 23.0795C0 28.3621 8.25732 26.4645 10.873 29.3879C13.3861 32.2087 11.8475 46.1077 17.5404 46.1077C22.7205 46.1077 21.7973 37.3375 22.5666 34.0551C23.1821 31.2855 23.7462 29.8495 26.208 28.4134C28.516 27.0799 35.1321 26.9773 35.1321 23.0282V23.0795Z" fill="#007070"/>
            </svg>
        </span>
    </div>

    <?php 
        if ( $imagen_productos ) : 
            $imagen_src = wp_get_attachment_image_src( $imagen_productos, 'full' );
            $alt_meta = get_post_meta($imagen_productos, '_wp_attachment_image_alt', true);
            $post_attachment = get_post($imagen_productos);
            
            $imagen_portada_alt = !empty($alt_meta) ? $alt_meta : $titulo_productos;
            $imagen_portada_title = ($post_attachment && !empty($post_attachment->post_title)) ? $post_attachment->post_title : $imagen_portada_alt;

            if ( $imagen_src ) : 
                $url_portada = $imagen_src[0];
                ?>
                <div class="reveal-img-organic  tw-relative tw-overflow-hidden tw-min-h-[300px] xl:tw-min-h-[600px] 3xl:tw-min-h-[700px] tw-flex tw-flex-col lg:tw-items-center tw-justify-center tw-text-white">
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
        <svg class="tw-absolute tw-max-w-[80px] lg:tw-max-w-[120px]" xmlns="http://www.w3.org/2000/svg" width="215" height="227" viewBox="0 0 215 227" fill="none">
            <path d="M-8.37085 2.98035C12.7667 -3.72673 48.1379 0.4888 67.1975 19.6901C75.7298 28.2854 80.2221 40.5017 88.5876 49.4459C103.238 65.1008 126.495 52.2212 144.481 53.6472C168.131 55.5288 188.313 70.2796 200.749 89.9588C259.71 183.286 107.187 247.309 35.5124 219.692C-11.0489 201.75 -46.123 159.64 -56.2866 110.981C-65.3497 67.5955 -58.4631 18.871 -8.37085 2.98035Z" fill="#00CCCC"/>
        </svg>
    </div>

    <?php 
        if ( $imagen_de_seccion_productos ) : 
            // Extraemos la URL de la imagen original usando el ID obtenido de 'option'
            $imagen_src_seccion = wp_get_attachment_image_src( $imagen_de_seccion_productos, 'full' );
            $alt_meta_seccion = get_post_meta($imagen_de_seccion_productos, '_wp_attachment_image_alt', true);
            $post_attachment_seccion = get_post($imagen_de_seccion_productos);
            
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


    <div class="tw-flex tw-flex-col lg:tw-flex-row tw-gap-10 xl:tw-gap-16 2xl:tw-gap-20 tw-w-full tw-items-center tw-justify-center tw-px-[5%] xl:tw-px-[10%] tw-pt-12 tw-pb-0 lg:tw-pt-16 xl:tw-pt-20">
        
        <div class="reveal-slide-left-section tw-w-full xl:tw-w-1/2 tw-flex tw-flex-col tw-justify-center tw-items-center tw-order-2 lg:tw-order-1">
            <div class="tw-relative tw-rounded-xl xl:tw-rounded-2xl 4xl:tw-rounded-3xl tw-w-full">
                 <img src="<?php echo esc_url($url_portada_seccion); ?>" 
                     alt="<?php echo esc_attr($imagen_portada_alt_seccion); ?>" 
                     title="<?php echo esc_attr($imagen_portada_title_seccion ?? $imagen_portada_alt_seccion); ?>"
                    class="w-full tw-mx-auto sm:tw-max-w-lg tw-h-auto tw-rounded-3xl tw-object-contain tw-transition-transform tw-duration-500 hover:tw-scale-105">
            </div>
        </div>
    
        <div class="reveal-slide-right-section tw-w-full xl:tw-w-1/2 tw-flex tw-flex-col tw-gap-5 tw-justify-center tw-items-start tw-order-1 lg:tw-order-2">
            <h3 class="tw-text-[#0CC] tw-text-3xl sm:tw-text-3xl 2xl:tw-text-4xl tw-font-bold tw-tracking-tight !tw-leading-tight">
               <?php echo esc_html($titulo_de_seccion_productos); ?>
            </h3>

             <div class="tw-text-[#161616] tw-text-base 2xl:tw-text-lg 3xl:tw-text-xl tw-tracking-normal tw-mb-0" >
                <?php 
                    echo wp_kses_post( wpautop( $descripcion_de_seccion_productos ) ); 
                ?>
            </div>

            <a href="<?php echo esc_url($url_para_boton_de_seccion_productos); ?>"  class="tw-bg-[#0CC] tw-text-base 3xl:tw-text-xl tw-text-white hover:tw-text-white tw-px-16 tw-py-3.5 3xl:tw-px-20 3xl:tw-py-4 tw-rounded-full tw-font-semibold ">
                <?php echo esc_html($texto_para_boton_de_seccion_productos); ?>
            </a>
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
            <h2 class="tw-text-[#F87612] tw-text-3xl sm:tw-text-3xl 2xl:tw-text-4xl tw-font-bold tw-tracking-tight !tw-leading-tight">
               <?php echo esc_html($titulo_historia); ?>
            </h2>

            <div class="tw-text-[#161616] tw-text-base 2xl:tw-text-lg 3xl:tw-text-xl tw-tracking-normal tw-mb-0" >
                <?php 
                    echo wp_kses_post( wpautop( $descripcion_historia ) ); 
                ?>
            </div>

             <a href="<?php echo esc_url($url_para_boton_de_seccion_productos_2); ?>"  class="tw-bg-[#0CC] tw-text-base 3xl:tw-text-xl tw-text-white hover:tw-text-white tw-px-16 tw-py-3.5 3xl:tw-px-20 3xl:tw-py-4 tw-rounded-full tw-font-semibold ">
                 <?php echo esc_html($texto_para_boton_de_seccion_productos_2); ?>
            </a>
        </div>
        
        <div class="reveal-slide-right-section tw-w-full xl:tw-w-1/2 tw-flex tw-flex-col tw-justify-center tw-items-center order-1 lg:order-2 tw-z-50">
            <div class="tw-relative tw-rounded-xl xl:tw-rounded-2xl 4xl:tw-rounded-3xl tw-w-full">
               <img src="<?php echo $url_portada_historia; ?>" 
                     alt="<?php echo esc_attr($imagen_portada_alt_historia); ?>" 
                     title="<?php echo esc_attr($imagen_portada_title_historia ?? $imagen_portada_alt_historia); ?>"
                     class="w-full tw-mx-auto sm:tw-max-w-lg tw-h-auto tw-rounded-3xl tw-object-contain tw-transition-transform tw-duration-500 hover:tw-scale-105">
            </div>
        </div>
    </div>

    
    <div class="reveal-slide-right tw-relative tw-hidden lg:tw-block tw--top-[130px] lg:tw--top-[250px] tw-w-full tw-h-full">
        <svg class="tw-absolute tw-right-0 tw-max-w-[80px] lg:tw-max-w-[120px] tw-z-0" xmlns="http://www.w3.org/2000/svg" width="198" height="226" viewBox="0 0 198 226" fill="none">
            <path d="M266.765 94.6895C257.206 74.6792 229.945 51.7508 202.901 50.9718C190.795 50.6227 178.809 55.6975 166.562 55.7087C145.122 55.7225 138.618 29.9448 125.279 17.7952C107.735 1.82393 83.1723 -2.81683 60.3085 1.56373C-48.1079 22.3522 9.46195 177.426 78.6223 210.844C123.551 232.553 178.261 229.354 220.717 203.5C258.574 180.449 289.42 142.108 266.765 94.6895Z" fill="#F87612"/>
        </svg>
    </div>

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