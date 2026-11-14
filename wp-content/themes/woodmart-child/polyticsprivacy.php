<?php
/**
 * Template Name: Tailwind Política de privacidad
 * Description: Una plantilla de página personalizada que utiliza Tailwind CSS para el diseño.
 */

// 1. Almacenar los campos de ACF usando el contexto global de 'option'
$titulo_tyc      = get_field('titulo_de_portada_privacidad', 'option');
$descripcion_tyc = get_field('descripcion_de_privacidad', 'option'); // Editor WYSIWYG

get_header(); ?>

    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>

    <div class="tw-bg-[#F87612] tw-w-full tw-flex tw-flex-row tw-items-center tw-justify-center tw-py-6 xs:tw-py-8 3xl:tw-py-10 tw-px-[5%] xl:px-[7%] 3xl:px-[10%] tw-gap-4">
        <span class="tw-hidden md:tw-block">
            <svg xmlns="http://www.w3.org/2000/svg" width="23" height="30" viewBox="0 0 36 47" fill="none">
                <path d="M35.1321 23.0795C35.1321 19.6432 29.4392 18.8226 27.1312 18.1046C23.7462 17.0788 23.3872 17.3353 22.6179 13.3861C21.9511 9.94982 23.0795 0 17.5404 0C11.6936 0 13.6425 13.1809 12.0013 15.7966C9.64209 19.5406 0 17.3865 0 23.0795C0 28.3621 8.25732 26.4645 10.873 29.3879C13.3861 32.2087 11.8475 46.1077 17.5404 46.1077C22.7205 46.1077 21.7973 37.3375 22.5666 34.0551C23.1821 31.2855 23.7462 29.8495 26.208 28.4134C28.516 27.0799 35.1321 26.9773 35.1321 23.0282V23.0795Z" fill="white"/>
            </svg>
        </span>
        <h1 class="tw-text-4xl lg:tw-text-5xl 3xl:tw-text-6xl tw-font-bold tw-text-white tw-tracking-tight tw-text-center tw-mb-0 tw-leading-none">
            <?php echo $titulo_tyc ? esc_html($titulo_tyc) : 'Política de privacidad'; ?>
        </h1>
        <span class="tw-hidden md:tw-block">
            <svg xmlns="http://www.w3.org/2000/svg" width="23" height="30" viewBox="0 0 36 47" fill="none">
                <path d="M35.1321 23.0795C35.1321 19.6432 29.4392 18.8226 27.1312 18.1046C23.7462 17.0788 23.3872 17.3353 22.6179 13.3861C21.9511 9.94982 23.0795 0 17.5404 0C11.6936 0 13.6425 13.1809 12.0013 15.7966C9.64209 19.5406 0 17.3865 0 23.0795C0 28.3621 8.25732 26.4645 10.873 29.3879C13.3861 32.2087 11.8475 46.1077 17.5404 46.1077C22.7205 46.1077 21.7973 37.3375 22.5666 34.0551C23.1821 31.2855 23.7462 29.8495 26.208 28.4134C28.516 27.0799 35.1321 26.9773 35.1321 23.0282V23.0795Z" fill="white"/>
            </svg>
        </span>
    </div>

    <?php if ( $descripcion_tyc ) : ?>
        <div class="tw-text-[#161616] tw-w-full tw-max-w-7xl 3xl:tw-max-w-[1400px] tw-mx-auto tw-px-[5%] tw-py-12 lg:tw-py-16 [&&_p]:tw-mb-4 last:[&&_p]:tw-mb-0">
            <div class="tw-text-base lg:tw-text-lg 3xl:tw-text-xl tw-w-full">
                <?php echo wp_kses_post( wpautop( $descripcion_tyc ) ); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <?php the_content(); ?>
    <?php endwhile; endif;?>

<?php get_footer(); ?>