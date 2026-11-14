<?php
// Archivo: woodmart-child/contenido-blog-header.php

// 1. CAMPOS DE PORTADA DEL BLOG
$titulo_blog      = get_field('titulo_de_blog', 'option');
$descripcion_blog = get_field('descripcion_de_blog', 'option');
$imagen_blog_id   = get_field('imagen_de_portada_blog', 'option');
?>

<style>
    body { font-family: 'Poppins', sans-serif; }

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

<div class="reveal-fade-up tw-w-full tw-flex tw-flex-row tw-items-center tw-justify-center tw-py-6 xs:tw-py-8 3xl:tw-py-10 tw-px-[5%] xl:px-[7%] 3xl:px-[10%] tw-gap-4">
    <span class="tw-hidden md:tw-block">
        <svg xmlns="http://www.w3.org/2000/svg" width="23" height="30" viewBox="0 0 36 47" fill="none">
            <path d="M35.1321 23.0795C35.1321 19.6432 29.4392 18.8226 27.1312 18.1046C23.7462 17.0788 23.3872 17.3353 22.6179 13.3861C21.9511 9.94982 23.0795 0 17.5404 0C11.6936 0 13.6425 13.1809 12.0013 15.7966C9.64209 19.5406 0 17.3865 0 23.0795C0 28.3621 8.25732 26.4645 10.873 29.3879C13.3861 32.2087 11.8475 46.1077 17.5404 46.1077C22.7205 46.1077 21.7973 37.3375 22.5666 34.0551C23.1821 31.2855 23.7462 29.8495 26.208 28.4134C28.516 27.0799 35.1321 26.9773 35.1321 23.0282V23.0795Z" fill="#007070"/>
        </svg>
    </span>
    <h1 class="tw-text-4xl lg:tw-text-5xl 3xl:tw-text-6xl tw-font-bold  tw-text-[#0CC] tw-tracking-tight tw-text-center tw-mb-0 tw-leading-none">
        <?php echo esc_html($titulo_blog); ?>
    </h1>
    <span class="tw-hidden md:tw-block">
        <svg xmlns="http://www.w3.org/2000/svg" width="23" height="30" viewBox="0 0 36 47" fill="none">
            <path d="M35.1321 23.0795C35.1321 19.6432 29.4392 18.8226 27.1312 18.1046C23.7462 17.0788 23.3872 17.3353 22.6179 13.3861C21.9511 9.94982 23.0795 0 17.5404 0C11.6936 0 13.6425 13.1809 12.0013 15.7966C9.64209 19.5406 0 17.3865 0 23.0795C0 28.3621 8.25732 26.4645 10.873 29.3879C13.3861 32.2087 11.8475 46.1077 17.5404 46.1077C22.7205 46.1077 21.7973 37.3375 22.5666 34.0551C23.1821 31.2855 23.7462 29.8495 26.208 28.4134C28.516 27.0799 35.1321 26.9773 35.1321 23.0282V23.0795Z" fill="#007070"/>
        </svg>
    </span>
</div>

<?php 
    if ( $imagen_blog_id ) : 
        $imagen_src = wp_get_attachment_image_src( $imagen_blog_id, 'full' );
        $alt_meta = get_post_meta($imagen_blog_id, '_wp_attachment_image_alt', true);
        $post_attachment = get_post($imagen_blog_id);
        
        $imagen_portada_alt = !empty($alt_meta) ? $alt_meta : $titulo_blog;
        $imagen_portada_title = ($post_attachment && !empty($post_attachment->post_title)) ? $post_attachment->post_title : $imagen_portada_alt;

        if ( $imagen_src ) : 
            $url_portada = $imagen_src[0];
            ?>
            <div class="tw-relative tw-overflow-hidden tw-min-h-[300px] xl:tw-min-h-[600px] 3xl:tw-min-h-[700px] tw-flex tw-flex-col lg:tw-items-center tw-justify-center tw-text-white">
                <img class="reveal-img-organic tw-absolute tw-inset-0 tw-w-full tw-h-full tw-object-cover tw-object-top tw-z-0" 
                src="<?php echo esc_url($url_portada); ?>" 
                alt="<?php echo esc_attr($imagen_portada_alt); ?>" 
                title="<?php echo esc_attr($imagen_portada_title); ?>"
                loading="eager"> 
            </div>
        <?php 
        endif;
    endif; 
?>

<div class="reveal-fade-up tw-text-[#161616] tw-w-full tw-flex tw-flex-col tw-text-center tw-text-base lg:tw-text-lg 3xl:tw-text-xl tw-max-w-5xl 3xl:tw-max-w-6xl tw-mx-auto tw-px-[5%] tw-pt-10 md:tw-pt-12 3xl:tw-pt-16" style="transition-delay: 150ms;">
    <div class="tw-text-[#161616] tw-text-base 2xl:tw-text-lg 3xl:tw-text-xl tw-tracking-normal tw-mb-0" >
        <?php 
            echo wp_kses_post( wpautop( $descripcion_blog ) ); 
        ?>  
    </div>  
</div>

<div class="reveal-slide-left tw-relative tw-hidden lg:tw-block tw--top-[60px] lg:tw--top-[250px] tw-left-0 tw-w-full tw-h-full tw-z-0">
    <svg class="tw-absolute tw-max-w-[80px] lg:tw-max-w-[100px]" xmlns="http://www.w3.org/2000/svg" width="148" height="226" viewBox="0 0 148 226" fill="none">
        <path d="M-119.102 94.6895C-109.544 74.6792 -82.2824 51.7508 -55.2389 50.9718C-43.1328 50.6227 -31.1468 55.6975 -18.9002 55.7087C2.54045 55.7225 9.04434 29.9448 22.3829 17.7952C39.9268 1.82393 64.4898 -2.81683 87.3536 1.56373C195.77 22.3522 138.2 177.426 69.0398 210.844C24.1113 232.553 -30.599 229.354 -73.0554 203.5C-110.911 180.449 -141.758 142.108 -119.102 94.6895Z" fill="#00CCCC"/>
    </svg>
</div>

<div class="reveal-slide-right tw-relative tw-hidden lg:tw-block tw-top-[500px] lg:tw-top-[600px] tw-w-full tw-h-full">
    <svg class="tw-absolute tw-right-0 tw-max-w-[80px] lg:tw-max-w-[100px] tw-z-0" xmlns="http://www.w3.org/2000/svg" width="191" height="226" viewBox="0 0 191 226" fill="none">
        <path d="M7.71155 94.6895C17.2702 74.6792 44.5315 51.7508 71.575 50.9718C83.6811 50.6227 95.6671 55.6975 107.914 55.7087C129.354 55.7225 135.858 29.9448 149.197 17.7952C166.741 1.82393 191.304 -2.81683 214.168 1.56373C322.584 22.3522 265.014 177.426 195.854 210.844C150.925 232.553 96.2149 229.354 53.7586 203.5C15.9025 180.449 -14.9436 142.108 7.71155 94.6895Z" fill="#00CCCC"/>
    </svg>
</div>

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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Seleccionamos el Sidebar y los Cards generados por Woodmart
        const sidebar = document.querySelector('.wd-sidebar');
        const cards = document.querySelectorAll('article.wd-post');

        // 2. Configuramos el Observer
        const blogContentObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('reveal-active');
                } else {
                    entry.target.classList.remove('reveal-active');
                }
            });
        }, { 
            root: null, 
            rootMargin: '0px 0px -50px 0px', // Dispara la animación un poco antes de llegar al borde
            threshold: 0.10 
        });

        // 3. Inyectamos la clase al Sidebar y lo observamos
        if (sidebar) {
            sidebar.classList.add('reveal-fade-up');
            blogContentObserver.observe(sidebar);
        }

        // 4. Inyectamos la clase a los Cards con efecto cascada (Stagger)
        cards.forEach((card, index) => {
            card.classList.add('reveal-fade-up');
            
            // Calculamos un retraso basado en la columna (0ms para la izq, 150ms para la der)
            // Como tu diseño tiene 2 columnas en desktop, usamos (index % 2)
            const delay = (index % 2) * 150; 
            card.style.transitionDelay = `${delay}ms`;
            
            blogContentObserver.observe(card);
        });
    });
</script>