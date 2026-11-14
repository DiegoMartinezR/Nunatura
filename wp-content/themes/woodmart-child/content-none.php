<?php
/**
 * The template for displaying a "No posts found" message
 */
?>

<style>
	/* Estilos personalizados para la página de "No se encontraron resultados" */
	article.not-found::before {
		display: inline-block;
		margin-bottom: 10px;
		color: var(--wd-primary-color);
		font-size: 100px;
		line-height: 1;
		content: "\f130";
		font-family: "woodmart-font";
	}
	
</style>

<article id="post-0" class="post no-results not-found tw-relative tw-w-full tw-max-w-3xl tw-mx-auto tw-px-[5%] tw-py-16 md:tw-py-24 tw-flex tw-flex-col tw-items-center tw-justify-center tw-text-center tw-overflow-hidden">
    

    <div class="tw-relative tw-z-10 tw-flex tw-flex-col tw-items-center tw-gap-4 tw-w-full">
        
        <div class="tw-text-6xl md:tw-text-7xl tw-text-[#F87612] tw-mb-2 tw-animate-bounce tw-duration-1000">
            <i class="mdi mdi-text-search-variant"></i>
        </div>

        <h3 class="tw-text-3xl md:tw-text-4xl tw-font-bold tw-text-[#005656] tw-tracking-tight tw-flex tw-items-center tw-justify-center tw-gap-2 tw-mb-0">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="31" viewBox="0 0 33 43" fill="none" class="tw-w-6 tw-h-auto">
                    <path d="M32.2385 21.1786C32.2385 18.0253 27.0145 17.2723 24.8966 16.6134C21.7973 15.6721 21.461 15.9075 20.755 12.2836C20.1432 9.13032 21.1786 0 16.0957 0C10.7305 0 12.5189 12.0953 11.0129 14.4956C8.84794 17.9312 0 15.9545 0 21.1786C0 26.0261 7.57722 24.2848 9.97746 26.9674C12.2836 29.5559 10.8717 42.3101 16.0957 42.3101C20.8491 42.3101 20.002 34.2622 20.7079 31.2502C21.2727 28.7087 21.7973 27.391 24.0495 26.0732C26.1673 24.8495 32.2385 24.7554 32.2385 21.1315V21.1786Z" fill="#00CCCC"/>
                </svg>
            </span>
            <?php esc_html_e( 'No encontramos resultados', 'woodmart' ); ?>
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="31" viewBox="0 0 33 43" fill="none" class="tw-w-6 tw-h-auto">
                    <path d="M32.2385 21.1786C32.2385 18.0253 27.0145 17.2723 24.8966 16.6134C21.7973 15.6721 21.461 15.9075 20.755 12.2836C20.1432 9.13032 21.1786 0 16.0957 0C10.7305 0 12.5189 12.0953 11.0129 14.4956C8.84794 17.9312 0 15.9545 0 21.1786C0 26.0261 7.57722 24.2848 9.97746 26.9674C12.2836 29.5559 10.8717 42.3101 16.0957 42.3101C20.8491 42.3101 20.002 34.2622 20.7079 31.2502C21.2727 28.7087 21.7973 27.391 24.0495 26.0732C26.1673 24.8495 32.2385 24.7554 32.2385 21.1315V21.1786Z" fill="#00CCCC"/>
                </svg>
            </span>
        </h3>
        
        <p class="tw-text-[#161616] tw-text-base md:tw-text-lg tw-font-light tw-max-w-md tw-mx-auto tw-leading-relaxed tw-mb-6">
            <?php esc_html_e( 'Lo sentimos, no encontramos publicaciones relacionadas. Intenta realizar una nueva búsqueda para encontrar lo que necesitas.', 'woodmart' ); ?>
        </p>

        <div class="tw-w-full tw-max-w-md tw-mx-auto tw-search-wrapper">
            <?php get_search_form(); ?>
        </div>
    </div>
</article>

<style>
    /* Estilización forzada e inyección sobre el formulario por defecto de WordPress/Woodmart */
    .tw-search-wrapper form {
        display: flex !important;
        width: 100% !important;
        position: relative !important;
    }
    /* El input de texto */
    .tw-search-wrapper input[type="search"], 
    .tw-search-wrapper input[type="text"] {
        width: 100% !important;
        padding: 0.85rem 1.25rem !important;
        padding-right: 3.5rem !important;
        border: 2px solid #00CCCC !important;
        border-radius: 9999px !important; /* rounded-full */
        background-color: #ffffff !important;
        color: #161616 !important;
        font-family: 'Poppins', sans-serif !important;
        outline: none !important;
        transition: all 0.3s ease !important;
    }
    .tw-search-wrapper input[type="search"]:focus {
        border-color: #F87612 !important; /* Cambia a naranja al hacer foco */
        box-shadow: 0 4px 12px rgba(248, 118, 18, 0.1) !important;
    }
    /* El botón de enviar */
    .tw-search-wrapper button[type="submit"],
    .tw-search-wrapper input[type="submit"] {
        position: absolute !important;
        right: 4px !important;
        top: 4px !important;
        bottom: 4px !important;
        padding: 0 1.25rem !important;
        background-color: #00CCCC !important;
        color: #ffffff !important;
        border-radius: 9999px !important;
        border: none !important;
        cursor: pointer !important;
        font-weight: 600 !important;
        transition: background-color 0.3s ease !important;
    }
    .tw-search-wrapper button[type="submit"]:hover,
    .tw-search-wrapper input[type="submit"]:hover {
        background-color: #009999 !important;
    }
</style>