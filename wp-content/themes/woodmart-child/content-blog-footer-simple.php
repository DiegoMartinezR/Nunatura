<?php
// Archivo: woodmart-child/contenido-blog-header.php

// 0. CAMPOS DE LA SUSCRIPCION
$titulo_suscripcion  = get_field('titulo_de_suscripcion', 'option');
$descripcion_suscripcion  = get_field('descripcion_de_seccion_suscripcion', 'option');
$imagen_portada_suscripcion = get_field('imagen_de_seccion_suscripcion', 'option');
?>

<style>
    body { font-family: 'Poppins', sans-serif; }

    /* --- ESTILOS DE ANIMACIÓN (NUNATURA) --- */
    .reveal-fade-up {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 1.2s ease, transform 1.2s cubic-bezier(0.16, 1, 0.3, 1);
        will-change: opacity, transform;
    }

    .reveal-img-organic {
        opacity: 0;
        transform: scale(1.05);
        filter: blur(8px);
        transition: opacity 2s ease, transform 2s cubic-bezier(0.16, 1, 0.3, 1), filter 2s ease;
        will-change: opacity, transform, filter;
    }

    .reveal-slide-left-short {
        opacity: 0;
        transform: translateX(-50px);
        transition: opacity 0.8s ease, transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        will-change: opacity, transform;
    }

    .reveal-active {
        opacity: 1 !important;
        transform: translate(0, 0) scale(1) !important;
        filter: blur(0) !important;
    }
</style>

<?php 
    if ( $imagen_portada_suscripcion ) : 
        $imagen_src_suscripcion = wp_get_attachment_image_src( $imagen_portada_suscripcion, 'full' );
        $url_portada_suscripcion = $imagen_src_suscripcion[0];
    endif; 
?>

<div class="tw-min-h-[500px] 3xl:tw-min-h-[600px] tw-bg-cover tw-flex tw-flex-col lg:tw-items-center tw-justify-center tw-text-white tw-py-12 "
        style="background-image: url('<?php echo esc_url($url_portada_suscripcion); ?>');">
    <div class="tw-text-center tw-px-[5%] tw-flex tw-flex-col tw-gap-4">
        
        <h2 class="reveal-fade-up tw-text-4xl lg:tw-text-5xl tw-font-bold tw-text-white tw-tracking-[0.42px]">
            <?php echo esc_html($titulo_suscripcion); ?> 
        </h2>
        
        <p class="reveal-fade-up tw-text-lg lg:tw-text-xl tw-leading-relaxed tw-font-medium tw-text-white tw-max-w-2xl tw-mx-auto tw-tracking-[0.42px]" style="transition-delay: 150ms;">
            <?php echo esc_html($descripcion_suscripcion); ?>
        </p>
        
        <div class="reveal-fade-up tw-w-full tw-mx-auto" style="transition-delay: 300ms;">
            <?php echo do_shortcode('[html_block id="104"]'); ?>
        </div>
        
    </div>                                        
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const animatedElements = document.querySelectorAll('.reveal-fade-up, .reveal-img-organic');

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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // 1. SELECCIONAR LOS ELEMENTOS POR SUS CLASES NATIVAS
        const postImage = document.querySelector('.wd-post-image img');
        const breadcrumbNav = document.querySelector('.custom-blog-breadcrumb');
        const breadcrumbHr = document.querySelector('.custom-blog-breadcrumb'); // La línea separadora
        const postTitle = document.querySelector('.post-single-page h1');
        const postAuthor = document.querySelector('.wd-post-meta');
        const postContent = document.querySelector('.wd-entry-content');
        const socialIcons = document.querySelectorAll('.wd-social-icons .wd-social-icon');

        // 2. INYECTAR LAS CLASES DE ANIMACIÓN
        // Usamos 'if' para asegurar que el código no falle si algún elemento no existe en la página
        if (postImage) postImage.classList.add('reveal-img-organic');
        if (breadcrumbNav) breadcrumbNav.classList.add('reveal-fade-up');
        if (breadcrumbHr) breadcrumbHr.classList.add('reveal-fade-up');
        if (postTitle) postTitle.classList.add('reveal-fade-up');
        if (postAuthor) postAuthor.classList.add('reveal-fade-up');
        if (postContent) postContent.classList.add('reveal-fade-up');

        // Para las Redes Sociales, inyectamos la clase y el retraso matemático (Stagger)
        if (socialIcons.length > 0) {
            socialIcons.forEach((icon, index) => {
                icon.classList.add('reveal-slide-left-short');
                // Cada icono se retrasa 150ms más que el anterior
                icon.style.transitionDelay = `${index * 150}ms`;
            });
        }

        // 3. CONFIGURAR Y ACTIVAR EL OBSERVER
        const observerOptions = {
            root: null,
            rootMargin: '0px 0px -30px 0px', // Se activa apenas roza la pantalla
            threshold: 0.10
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

        // 4. EMPEZAR A OBSERVAR LOS ELEMENTOS YA INYECTADOS
        const elementsToObserve = document.querySelectorAll('.reveal-fade-up, .reveal-img-organic, .reveal-slide-left-short');
        
        elementsToObserve.forEach(element => {
            scrollObserver.observe(element);
        });
    });
</script>