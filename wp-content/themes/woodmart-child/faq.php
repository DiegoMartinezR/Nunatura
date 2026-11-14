<?php
/**
 * Template Name: Tailwind Faq Page
 * Description: Una plantilla de página personalizada que utiliza Tailwind CSS para el diseño.
 */

$titulo_faq       = get_field('titulo_de_portada_faq', 'option');
$imagen_portada_id = get_field('imagen_de_portada_faq', 'option'); // Retorna el ID de imagen

// 1. PROCESAR Y AGRUPAR LAS FAQs DEL REPETIDOR DE ACF
$faq_groups = [];

if ( have_rows('pregunta_frecuente', 'option') ) {
    while ( have_rows('pregunta_frecuente', 'option') ) {
        the_row();
        
        $agrupador = get_sub_field('titulo_agrupador') ?: 'General';
        $pregunta  = get_sub_field('pregunta');
        $respuesta = get_sub_field('respuesta');
        
        // Agrupamos en un array multidimensional usando el título del agrupador como llave
        $faq_groups[$agrupador][] = [
            'pregunta'  => $pregunta,
            'respuesta' => $respuesta
        ];
    }
}

get_header(); ?>

    <style>
        body { font-family: 'Poppins', sans-serif; }

        /* Estilos de transición para el trigger cuando el padre tiene la clase activa */
        .tw-faq-item.tw-is-open .tw-faq-trigger {
            background-color: #0CC !important;
            color: #ffffff !important;
        }

        /* Rotación fluida para tu SVG personalizado */
        .tw-faq-item.tw-is-open .tw-faq-icon-wrapper svg {
            transform: rotate(180deg);
        }
        
        /* Animación y control de color del SVG */
        .tw-faq-icon-wrapper svg {
            transition: transform 0.3s ease, fill 0.3s ease;
        }
        
        /* Cuando está abierto hereda color blanco, y al hacer hover también */
        .tw-faq-item.tw-is-open .tw-faq-icon-wrapper svg path,
        .tw-faq-trigger:hover .tw-faq-icon-wrapper svg path {
            fill: #ffffff !important;
        }

        /* NUEVO SISTEMA DE ANIMACIÓN CON CSS GRID 
           Sustituye la dependencia de JS y el scrollHeight.
        */
        .tw-faq-content {
            display: grid;
            grid-template-rows: 0fr;
            transition: grid-template-rows 0.3s ease-in-out, opacity 0.3s ease-in-out;
            opacity: 0;
        }

        .tw-faq-item.tw-is-open .tw-faq-content {
            grid-template-rows: 1fr;
            opacity: 1;
        }

        /* Esconder el overflow del div interno para el efecto Grid */
        .tw-faq-content-inner {
            overflow: hidden;
        }

        /* --- ESTILOS DE ANIMACIÓN (NUNATURA) --- */
        .reveal-fade-up {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 2s ease, transform 2s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }

        .reveal-img-organic {
            opacity: 0;
            transform: scale(1.05);
            filter: blur(8px);
            transition: opacity 2s ease, transform 2s cubic-bezier(0.16, 1, 0.3, 1), filter 2s ease;
            will-change: opacity, transform, filter;
        }

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
    </style>

    <div class="tw-w-full tw-flex tw-flex-row tw-items-center tw-justify-center tw-py-6 xs:tw-py-8 3xl:tw-py-10 tw-px-[5%] xl:px-[7%] 3xl:px-[10%] tw-gap-4">
        <span class="reveal-fade-up tw-hidden md:tw-block">
            <svg xmlns="http://www.w3.org/2000/svg" width="23" height="30" viewBox="0 0 36 47" fill="none">
                <path d="M35.1321 23.0795C35.1321 19.6432 29.4392 18.8226 27.1312 18.1046C23.7462 17.0788 23.3872 17.3353 22.6179 13.3861C21.9511 9.94982 23.0795 0 17.5404 0C11.6936 0 13.6425 13.1809 12.0013 15.7966C9.64209 19.5406 0 17.3865 0 23.0795C0 28.3621 8.25732 26.4645 10.873 29.3879C13.3861 32.2087 11.8475 46.1077 17.5404 46.1077C22.7205 46.1077 21.7973 37.3375 22.5666 34.0551C23.1821 31.2855 23.7462 29.8495 26.208 28.4134C28.516 27.0799 35.1321 26.9773 35.1321 23.0282V23.0795Z" fill="#007070"/>
            </svg>
        </span>
        <h1 class="reveal-fade-up tw-text-4xl lg:tw-text-5xl 3xl:tw-text-6xl tw-font-bold tw-text-[#0CC] tw-tracking-tight tw-text-center tw-mb-0 tw-leading-none">
           <?php echo esc_html($titulo_faq); ?>
        </h1>
        <span class="reveal-fade-up tw-hidden md:tw-block">
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
            
            $imagen_portada_alt = !empty($alt_meta) ? $alt_meta : $titulo_faq;
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

    <div class="reveal-slide-right tw-relative tw-hidden lg:tw-block tw-top-[150px] lg:tw-top-[180px] tw-w-full tw-h-full">
        <svg class="tw-absolute tw-right-0 tw-max-w-[80px] lg:tw-max-w-[100px] tw-z-0" xmlns="http://www.w3.org/2000/svg" width="191" height="226" viewBox="0 0 191 226" fill="none">
            <path d="M7.71155 94.6895C17.2702 74.6792 44.5315 51.7508 71.575 50.9718C83.6811 50.6227 95.6671 55.6975 107.914 55.7087C129.354 55.7225 135.858 29.9448 149.197 17.7952C166.741 1.82393 191.304 -2.81683 214.168 1.56373C322.584 22.3522 265.014 177.426 195.854 210.844C150.925 232.553 96.2149 229.354 53.7586 203.5C15.9025 180.449 -14.9436 142.108 7.71155 94.6895Z" fill="#00CCCC"/>
        </svg>
    </div>

    <div class="tw-faq-wrapper tw-flex tw-flex-col tw-gap-16 tw-py-12 lg:tw-py-16">
        
        <?php if ( !empty($faq_groups) ) : ?>
            <?php foreach ( $faq_groups as $titulo_bloque => $items ) : ?>
                
                <div class="tw-faq-block tw-flex tw-flex-col lg:tw-items-center tw-justify-center tw-relative tw-z-10">
                    
                    <h2 class="reveal-fade-up tw-text-2xl md:tw-text-3xl 3xl:tw-text-4xl tw-font-semibold tw-pb-6 tw-text-center tw-text-[#005656]">
                        <?php echo esc_html($titulo_bloque); ?>
                    </h2>

                    <div class="tw-w-full tw-max-w-3xl xl:tw-max-w-4xl 3xl:tw-max-w-5xl tw-mx-auto tw-grid tw-grid-cols-1 tw-gap-4 lg:tw-gap-6 tw-items-start tw-px-[5%]">
                        <?php foreach ( $items as $index => $item ) : ?>
                            
                            <div class="reveal-fade-up tw-faq-item tw-bg-white tw-rounded-xl tw-overflow-hidden !tw-border-2 !tw-border-solid !tw-border-[#0CC] tw-transition-all tw-duration-300">
                                
                                <div class="tw-faq-trigger tw-text-base lg:tw-text-lg 3xl:tw-text-xl tw-flex tw-justify-between tw-items-center tw-font-semibold tw-text-[#161616] tw-px-4 lg:tw-px-6 tw-py-4 tw-bg-white tw-cursor-pointer tw-transition-all tw-duration-300 hover:tw-bg-[#0CC] hover:tw-text-white">
                                    <div class="tw-flex tw-gap-2">
                                        <span class="tw-flex-shrink-0"><?php echo $index + 1; ?>.</span>
                                        <span><?php echo esc_html($item['pregunta']); ?></span>
                                    </div>

                                    <span class="tw-faq-icon-wrapper tw-ml-4 tw-flex-shrink-0 tw-flex tw-flex-col tw-items-center tw-justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="10" viewBox="0 0 16 13" fill="none">
                                            <path d="M15.551 1.64138C15.2667 1.35868 14.882 1.20001 14.481 1.20001C14.08 1.20001 13.6953 1.35868 13.4109 1.64138L7.96205 7.01438L2.58905 1.64138C2.30468 1.35868 1.91999 1.20001 1.51901 1.20001C1.11803 1.20001 0.73334 1.35868 0.448962 1.64138C0.306701 1.78247 0.193785 1.95034 0.116729 2.1353C0.0396722 2.32026 0 2.51864 0 2.71901C0 2.91938 0.0396722 3.11776 0.116729 3.30272C0.193785 3.48768 0.306701 3.65555 0.448962 3.79665L6.88442 10.2321C7.02552 10.3744 7.19339 10.4873 7.37835 10.5643C7.5633 10.6414 7.76169 10.6811 7.96205 10.6811C8.16242 10.6811 8.36081 10.6414 8.54576 10.5643C8.73072 10.4873 8.89859 10.3744 9.03969 10.2321L15.551 3.79665C15.6933 3.65555 15.8062 3.48768 15.8833 3.30272C15.9603 3.11776 16 2.91938 16 2.71901C16 2.51864 15.9603 2.32026 15.8833 2.1353C15.8062 1.95034 15.6933 1.78247 15.551 1.64138Z" fill="#00CCCC"/>
                                        </svg>
                                    </span>
                                </div>

                                <div class="tw-faq-content tw-text-base lg:tw-text-lg 3xl:tw-text-xl tw-font-dmsans">
                                    <div class="tw-faq-content-inner tw-px-6">
                                        <div class="tw-py-4 tw-text-[#404040]">
                                            <?php echo nl2br(esc_html($item['respuesta'])); ?>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    </div>

                </div>
                
            <?php endforeach; ?>
        <?php else : ?>
            <p class="tw-text-center tw-text-gray-500">No se encontraron preguntas frecuentes.</p>
        <?php endif; ?>

    </div>

    <div class="reveal-slide-left tw-relative tw-hidden lg:tw-block tw--top-[180px] lg:tw--top-[250px] tw-left-0 tw-w-full tw-h-full tw-z-0">
        <svg class="tw-absolute tw-max-w-[80px] lg:tw-max-w-[100px]" xmlns="http://www.w3.org/2000/svg" width="148" height="226" viewBox="0 0 148 226" fill="none">
            <path d="M-119.102 94.6895C-109.544 74.6792 -82.2824 51.7508 -55.2389 50.9718C-43.1328 50.6227 -31.1468 55.6975 -18.9002 55.7087C2.54045 55.7225 9.04434 29.9448 22.3829 17.7952C39.9268 1.82393 64.4898 -2.81683 87.3536 1.56373C195.77 22.3522 138.2 177.426 69.0398 210.844C24.1113 232.553 -30.599 229.354 -73.0554 203.5C-110.911 180.449 -141.758 142.108 -119.102 94.6895Z" fill="#00CCCC"/>
        </svg>
    </div>

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <?php the_content(); ?>
    <?php endwhile; endif;?>

<?php get_footer(); ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const faqWrapper = document.querySelector('.tw-faq-wrapper');

        if (!faqWrapper) return;

        faqWrapper.addEventListener('click', function (e) {
            const trigger = e.target.closest('.tw-faq-trigger');
            if (!trigger) return;

            const currentItem = trigger.closest('.tw-faq-item');
            const isAlreadyOpen = currentItem.classList.contains('tw-is-open');

            // 1. Cierra todos los demás acordeones simplemente quitando la clase
            faqWrapper.querySelectorAll('.tw-faq-item').forEach(item => {
                if (item !== currentItem) {
                    item.classList.remove('tw-is-open');
                }
            });

            // 2. Abre o cierra el acordeón actual
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
        const animatedElements = document.querySelectorAll('.reveal-fade-up, .reveal-img-organic, .reveal-slide-left, .reveal-slide-right');

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