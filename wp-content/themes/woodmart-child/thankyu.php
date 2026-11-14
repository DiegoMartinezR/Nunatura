<?php
/**
 * Template Name: Tailwind Thank You
 * Description: Una plantilla de página personalizada que utiliza Tailwind CSS para el diseño.
 */

get_header(); ?>

    <style>
        body { font-family: 'Poppins', sans-serif; }

        /* ====================================================
        ARCHIVO: assets/css/thank-you.css (PÁGINA DE AGRADECIMIENTO)
        ==================================================== */

        .nuna-thanks-wrapper {
            --nuna-cyan: #00C2C7;
            --nuna-orange: #F07D00;
            --nuna-cyan-light: rgba(0, 194, 199, 0.08);
            --nuna-shadow: 0 10px 40px rgba(0, 0, 0, 0.06);
            
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-height: 65vh !important; /* Ajusta dinámicamente según la altura de tu header y footer */
            padding: 60px 20px !important;
            background-color: #fafbfc;
            font-family: 'Poppins', sans-serif !important;
        }

        /* Tarjeta contenedora estilo Soft UI */
        .nuna-thanks-card {
            background: #ffffff !important;
            border-radius: 20px !important;
            box-shadow: var(--nuna-shadow) !important;
            padding: 50px 40px !important;
            border: 1px solid rgba(0,0,0,0.03) !important;
            max-width: 550px !important;
            width: 100% !important;
            text-align: center !important;
            margin: 0 auto !important;
        }

        /* --- ICONO DE ÉXITO Y ANIMACIÓN DE PULSO --- */
        .nuna-thanks-icon-box {
            position: relative;
            width: 80px;
            height: 80px;
            margin: 0 auto 30px auto;
            background-color: var(--nuna-cyan);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nuna-thanks-icon {
            color: #ffffff !important;
            font-size: 36px !important;
            font-weight: 800 !important;
            line-height: 1 !important;
        }

        /* Ondas expansivas transparentes */
        .nuna-thanks-pulse {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: transparent;
            border: 2px solid var(--nuna-cyan);
            box-shadow: 0 0 0 0 rgba(0, 194, 199, 0.4);
            animation: nunaPulse 2s infinite cubic-bezier(0.66, 0, 0, 1);
        }

        /* --- TIPOGRAFÍA Y TEXTOS --- */
        .nuna-thanks-title {
            font-size: 36px !important;
            font-weight: 800 !important;
            color: #222222 !important;
            margin-bottom: 8px !important;
            line-height: 1.2 !important;
        }

        .nuna-thanks-subtitle {
            font-size: 18px !important;
            font-weight: 600 !important;
            color: var(--nuna-cyan) !important;
            margin-bottom: 20px !important;
            text-transform: none !important;
            letter-spacing: 0px !important;
        }

        .nuna-thanks-text {
            font-size: 15px !important;
            color: #666666 !important;
            line-height: 1.7 !important;
            margin-bottom: 0 !important;
        }

        /* Línea divisoria delicada */
        .nuna-thanks-divider {
            height: 1px;
            background: linear-gradient(to right, transparent, rgba(0,0,0,0.06), transparent);
            margin: 30px 0 !important;
        }

        /* --- BOTÓN DE RETORNO PRINCIPAL --- */
        .nuna-thanks-btn {
            background-color: var(--nuna-cyan) !important;
            color: #ffffff !important;
            border-radius: 9999px !important; /* Forma de píldora */
            padding: 16px 45px !important;
            font-weight: 700 !important;
            font-size: 15px !important;
            text-transform: none !important;
            letter-spacing: 0.5px !important;
            display: inline-block !important;
            text-decoration: none !important;
            box-shadow: 0 6px 20px var(--nuna-cyan-light) !important;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        /* Efecto hover interactivo: Pasa de Cyan a Naranja flotando hacia arriba */
        .nuna-thanks-btn:hover {
            background-color: var(--nuna-orange) !important;
            transform: translateY(-4px) !important;
            box-shadow: 0 10px 25px rgba(240, 125, 0, 0.2) !important;
        }

        /* ====================================================
        KEYFRAMES DE LA ANIMACIÓN (PULSO OPTICO)
        ==================================================== */
        @keyframes nunaPulse {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(0, 194, 199, 0.5);
            }
            70% {
                transform: scale(1.3);
                box-shadow: 0 0 0 15px rgba(0, 194, 199, 0);
            }
            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(0, 194, 199, 0);
            }
        }

        /* ====================================================
        OPTIMIZACIÓN PARA MULTIPANTALLA (>= 1920px)
        ==================================================== */
        @media (min-width: 1920px) {
            .nuna-thanks-card { max-width: 620px !important; padding: 60px 50px !important; }
            .nuna-thanks-title { font-size: 42px !important; }
            .nuna-thanks-subtitle { font-size: 22px !important; }
            .nuna-thanks-text { font-size: 18px !important; }
            .nuna-thanks-btn { font-size: 17px !important; padding: 18px 55px !important; }
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

    <div class="nuna-thanks-wrapper">
        <div class="nuna-thanks-card">
            
            <div class="nuna-thanks-icon-box">
                <div class="nuna-thanks-pulse"></div>
                <span class="nuna-thanks-icon">✓</span>
            </div>

            <h1 class="reveal-fade-up nuna-thanks-title">¡Muchas Gracias!</h1>
            <h2 class="reveal-fade-up nuna-thanks-subtitle">Tu solicitud ha sido recibida con éxito</h2>
            
            <p class="reveal-fade-up nuna-thanks-text"> Hemos registrado tus datos de manera correcta en nuestro sistema. Nuestro equipo revisará la información y se pondrá en contacto contigo a la brevedad posible a través de tu correo electrónico.</p>
            
            <div class="reveal-fade-up nuna-thanks-divider"></div>

            <div class="reveal-fade-up nuna-thanks-actions">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nuna-thanks-btn">Volver a la tienda</a>
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