<?php
/**
 * Template Name: Tailwind 404
 * Description: Una plantilla de página personalizada que utiliza Tailwind CSS para el diseño.
 */

get_header(); ?>

    <style>
        body { font-family: 'Poppins', sans-serif; }

        /* ====================================================
            ARCHIVO: assets/css/404.css (PÁGINA 404 CEREAL ROTATIVO)
            ==================================================== */

            .nuna-404-wrapper {
                --nuna-cyan: #00C2C7;
                --nuna-orange: #F07D00;
                --nuna-cyan-light: rgba(0, 194, 199, 0.08);
                
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                min-height: 65vh !important;
                padding: 60px 20px !important;
                background-color: #fafbfc;
                font-family: 'Poppins', sans-serif !important;
            }

            .nuna-404-content {
                text-align: center;
                max-width: 600px;
                margin: 0 auto;
            }

            /* --- CONTENEDOR DE ANIMACIÓN (4 - CEREAL - 4) --- */
            .nuna-404-animation-box {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                margin-bottom: 20px;
            }

            .nuna-404-digit {
                font-size: 130px !important;
                font-weight: 900 !important;
                color: #333333 !important;
                line-height: 1 !important;
            }

            /* 1. Contenedor del Cereal: Se encarga de la levitación (Arriba/Abajo) */
            .nuna-404-cereal-container {
                width: 110px;
                height: 110px;
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                animation: nunaLevitate 4s ease-in-out infinite;
                filter: drop-shadow(0 15px 20px rgba(0, 0, 0, 0.08)); /* Sombra natural para la foto */
                transition: filter 0.4s ease;
            }

            /* 2. Imagen del Cereal: Se encarga exclusivamente del giro en 360° */
            .nuna-404-cereal-img {
                width: 100% !important;
                height: 100% !important;
                object-fit: contain !important;
                animation: nunaSpin 25s linear infinite; /* Giro lento y elegante de alta gama */
            }

            /* Al pasar el mouse, el cereal reacciona brillando sutilmente */
            .nuna-404-cereal-container:hover {
                filter: drop-shadow(0 20px 30px rgba(0, 194, 199, 0.25));
            }

            /* --- TEXTOS E INTERFAZ --- */
            .nuna-404-title {
                font-size: 32px !important;
                font-weight: 800 !important;
                color: #222222 !important;
                margin-bottom: 20px !important;
                line-height: 1.3 !important;
            }

            .nuna-404-text {
                font-size: 16px !important;
                color: #666666 !important;
                line-height: 1.7 !important;
                margin-bottom: 35px !important;
            }

            .nuna-404-btn {
                background-color: var(--nuna-cyan) !important;
                color: #ffffff !important;
                border-radius: 9999px !important;
                padding: 16px 45px !important;
                font-weight: 700 !important;
                font-size: 16px !important;
                text-transform: none !important;
                letter-spacing: 0.5px !important;
                display: inline-block !important;
                text-decoration: none !important;
                box-shadow: 0 6px 20px var(--nuna-cyan-light) !important;
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
            }

            .nuna-404-btn:hover {
                background-color: var(--nuna-orange) !important;
                transform: translateY(-4px) !important;
                box-shadow: 0 10px 25px rgba(240, 125, 0, 0.25) !important;
            }

            /* ====================================================
            KEYFRAMES (EFECTOS DE ANIMACIÓN COMBINADOS)
            ==================================================== */

            /* Animación de flotado (Contenedor) */
            @keyframes nunaLevitate {
                0%, 100% {
                    transform: translateY(0);
                }
                50% {
                    transform: translateY(-12px); /* Levitación limpia */
                }
            }

            /* Animación de Rotación Infinita en 360 grados (Imagen) */
            @keyframes nunaSpin {
                from {
                    transform: rotate(0deg);
                }
                to {
                    transform: rotate(360deg);
                }
            }

            /* ====================================================
            OPTIMIZACIÓN ULTRA ANCHA (>= 1920px)
            ==================================================== */
            @media (min-width: 1920px) {
                .nuna-404-digit { font-size: 160px !important; }
                .nuna-404-cereal-container { width: 145px; height: 145px; }
                .nuna-404-title { font-size: 38px !important; }
                .nuna-404-text { font-size: 19px !important; }
                .nuna-404-btn { font-size: 18px !important; padding: 20px 55px !important; }
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

    <div class="nuna-404-wrapper">
        <div class="nuna-404-content">
            
            <div class="reveal-fade-up nuna-404-animation-box">
                <span class="nuna-404-digit">4</span>
                <div class="nuna-404-cereal-container">
                    <img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/img/cereal404.webp' ); ?>" alt="Cereal de Chocolate Nunatura" class="nuna-404-cereal-img">
                </div>
                <span class="nuna-404-digit">4</span>
            </div>

            <h1 class="reveal-fade-up nuna-404-title">¡Ups! Te desviaste del camino natural</h1>
            <p class="reveal-fade-up nuna-404-text">La página o el producto que estás buscando no existe o ha sido cambiado de lugar. No te preocupes, puedes volver a explorar nuestra variedad saludable.</p>
            
            <div class="reveal-fade-up nuna-404-actions">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nuna-404-btn">Volver al inicio</a>
            </div>
        </div>
    </div>
   

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <?php the_content(); ?>
    <?php endwhile; endif;?>

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

<?php get_footer(); ?>