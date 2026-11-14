<?php
/**
 * Template Name: Tailwind Contact
 * Description: Una plantilla de página personalizada que utiliza Tailwind CSS para el diseño con efectos de scroll interactivos.
 */

// 1. Almacenar los campos de ACF apuntando directamente a la Página de Opciones ('option')
$titulo_contacto      = get_field('titulo_de_contacto', 'option');
$imagen_portada_id    = get_field('imagen_de_portada_contacto', 'option'); // Retorna el ID de imagen
$descripcion_contacto = get_field('descripcion_de_contacto', 'option');

get_header(); ?>

    <style>
        body { font-family: 'Poppins', sans-serif; }

        /* --- ESTILOS DE ANIMACIÓN (NUNATURA) --- */
        
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
            opacity: 1;
            transform: translateY(0) scale(1);
            filter: blur(0);
        }
    </style>

    <div class="reveal-fade-up tw-w-full tw-flex tw-flex-row tw-items-center tw-justify-center tw-py-6 xs:tw-py-8 3xl:tw-py-10 tw-px-[5%] xl:px-[7%] 3xl:px-[10%] tw-gap-4">
        <span class="tw-hidden md:tw-block">
            <svg xmlns="http://www.w3.org/2000/svg" width="23" height="30" viewBox="0 0 36 47" fill="none">
                <path d="M35.1321 23.0795C35.1321 19.6432 29.4392 18.8226 27.1312 18.1046C23.7462 17.0788 23.3872 17.3353 22.6179 13.3861C21.9511 9.94982 23.0795 0 17.5404 0C11.6936 0 13.6425 13.1809 12.0013 15.7966C9.64209 19.5406 0 17.3865 0 23.0795C0 28.3621 8.25732 26.4645 10.873 29.3879C13.3861 32.2087 11.8475 46.1077 17.5404 46.1077C22.7205 46.1077 21.7973 37.3375 22.5666 34.0551C23.1821 31.2855 23.7462 29.8495 26.208 28.4134C28.516 27.0799 35.1321 26.9773 35.1321 23.0282V23.0795Z" fill="#007070"/>
            </svg>
        </span>
        <h1 class="tw-text-4xl lg:tw-text-5xl 3xl:tw-text-6xl tw-font-bold tw-text-[#0CC] tw-tracking-tight tw-text-center tw-mb-0 tw-leading-none">
            <?php echo esc_html($titulo_contacto); ?>
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
            
            if ( !empty($alt_meta) ) {
                $imagen_portada_alt = $alt_meta;
            }
           
            if ( $post_attachment && !empty($post_attachment->post_title) ) {
                $imagen_portada_title = $post_attachment->post_title;
            }

            if ( $imagen_src ) : 
                $url_portada = $imagen_src[0];
                ?>
                <div class="tw-relative tw-overflow-hidden tw-min-h-[300px] xl:tw-min-h-[600px] 3xl:tw-min-h-[700px] tw-flex tw-flex-col lg:tw-items-center tw-justify-center tw-text-white">

                    <img class="reveal-img-organic tw-absolute tw-inset-0 tw-w-full tw-h-full tw-object-cover tw-object-top tw-z-0" 
                    src="<?php echo esc_url($url_portada); ?>" 
                    alt="<?php echo esc_attr($imagen_portada_alt); ?>" 
                    title="<?php echo esc_attr($imagen_portada_title ?? $imagen_portada_alt); ?>"
                    loading="eager"> 

                </div>
            <?php 
            endif;
        endif; 
    ?>

    <div class="tw-pt-10 tw-pb-5">

        <?php if ( $descripcion_contacto ) : ?>
            <div class="reveal-fade-up tw-text-[#161616] tw-w-full tw-flex tw-flex-row tw-text-center tw-text-base lg:tw-text-lg 3xl:tw-text-xl tw-max-w-5xl 3xl:tw-max-w-6xl tw-mx-auto tw-px-[5%]" style="transition-delay: 150ms;">
                <p>
                    <?php echo esc_html($descripcion_contacto); ?>
                </p>
            </div>
        <?php endif; ?>

        <div class="tw-w-full tw-mx-auto tw-py-6 tw-px-[5%]">
            <?php echo do_shortcode('[html_block id="142"]'); ?>
        </div>

    </div>

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <?php the_content(); ?>
    <?php endwhile; endif;?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Selecciona el título, la imagen y la descripción
            const animatedElements = document.querySelectorAll('.reveal-fade-up, .reveal-img-organic');

            const observerOptions = {
                root: null, // Usa el viewport del navegador
                rootMargin: '0px',
                threshold: 0.15 // Se activa cuando el 15% del elemento entra en la pantalla
            };

            const scrollObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        // Al estar visible: agrega clase activa (hace Fade-In Up o Zoom-In)
                        entry.target.classList.add('reveal-active');
                    } else {
                        // Al dejar de ser visible: remueve la clase activa (vuelve a esconderse)
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