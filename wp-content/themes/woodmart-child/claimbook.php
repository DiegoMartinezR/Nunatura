<?php
/**
 * Template Name: Tailwind Claim Book
 * Description: Una plantilla de página personalizada que utiliza Tailwind CSS para el diseño.
 */

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
            opacity: 1;
            transform: translateY(0) scale(1);
            filter: blur(0);
        }
    </style>

    <div class="tw-bg-[#F87612] tw-w-full tw-flex tw-flex-row tw-items-center tw-justify-center tw-py-6 xs:tw-py-8 3xl:tw-py-10 tw-px-[5%] xl:px-[7%] 3xl:px-[10%] tw-gap-4">
        <span class="reveal-fade-up tw-hidden md:tw-block">
            <svg xmlns="http://www.w3.org/2000/svg" width="23" height="30" viewBox="0 0 36 47" fill="none">
                <path d="M35.1321 23.0795C35.1321 19.6432 29.4392 18.8226 27.1312 18.1046C23.7462 17.0788 23.3872 17.3353 22.6179 13.3861C21.9511 9.94982 23.0795 0 17.5404 0C11.6936 0 13.6425 13.1809 12.0013 15.7966C9.64209 19.5406 0 17.3865 0 23.0795C0 28.3621 8.25732 26.4645 10.873 29.3879C13.3861 32.2087 11.8475 46.1077 17.5404 46.1077C22.7205 46.1077 21.7973 37.3375 22.5666 34.0551C23.1821 31.2855 23.7462 29.8495 26.208 28.4134C28.516 27.0799 35.1321 26.9773 35.1321 23.0282V23.0795Z" fill="white"/>
            </svg>
        </span>
        <h1 class="reveal-fade-up tw-text-4xl lg:tw-text-5xl 3xl:tw-text-6xl tw-font-bold tw-text-white tw-tracking-tight tw-text-center tw-mb-0 tw-leading-none">
            Libro de reclamaciones
        </h1>
        <span class="reveal-fade-up tw-hidden md:tw-block">
            <svg xmlns="http://www.w3.org/2000/svg" width="23" height="30" viewBox="0 0 36 47" fill="none">
                <path d="M35.1321 23.0795C35.1321 19.6432 29.4392 18.8226 27.1312 18.1046C23.7462 17.0788 23.3872 17.3353 22.6179 13.3861C21.9511 9.94982 23.0795 0 17.5404 0C11.6936 0 13.6425 13.1809 12.0013 15.7966C9.64209 19.5406 0 17.3865 0 23.0795C0 28.3621 8.25732 26.4645 10.873 29.3879C13.3861 32.2087 11.8475 46.1077 17.5404 46.1077C22.7205 46.1077 21.7973 37.3375 22.5666 34.0551C23.1821 31.2855 23.7462 29.8495 26.208 28.4134C28.516 27.0799 35.1321 26.9773 35.1321 23.0282V23.0795Z" fill="white"/>
            </svg>
        </span>
    </div>

   
    <div class="tw-text-[#161616] tw-w-full tw-max-w-7xl 3xl:tw-max-w-[1400px] tw-mx-auto tw-px-[5%] tw-py-12 lg:tw-py-16 [&&_p]:tw-mb-4 last:[&&_p]:tw-mb-0">
        <div class="reveal-fade-up tw-text-base lg:tw-text-lg 3xl:tw-text-xl tw-w-full">
            <?php echo do_shortcode('[allpacode_gestion_reclamos]'); ?>
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
                threshold: 0.05 // Se activa cuando el 15% del elemento entra en la pantalla
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