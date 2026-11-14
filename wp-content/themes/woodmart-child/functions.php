<?php
/**
 * Enqueue script and styles for child theme with Tailwind support
 */
function woodmart_child_enqueue_styles() {
    
    // 1. Mantenemos la carga original de Woodmart (Estilos del tema hijo estándar)
    wp_enqueue_style( 
        'child-style', 
        get_stylesheet_directory_uri() . '/style.css', 
        array( 'woodmart-style' ), 
        woodmart_get_theme_info( 'Version' ) 
    );

    // 2. Cargamos TU Tailwind (Ubicado en la carpeta /dist/)
    // Usamos 'child-style' como dependencia para que Tailwind cargue AL FINAL de todo.
    $tailwind_path = get_stylesheet_directory() . '/dist/output.css';
    
    if ( file_exists( $tailwind_path ) ) {
        wp_enqueue_style(
            'tailwind-custom',
            get_stylesheet_directory_uri() . '/dist/output.css',
            array( 'child-style' ), 
            filemtime( $tailwind_path )
        );
    }
}
// Mantenemos la prioridad 1000 para asegurar que cargue después de los plugins
add_action( 'wp_enqueue_scripts', 'woodmart_child_enqueue_styles', 1000 );

add_action( 'woodmart_after_header', 'cargar_html_personalizado_blog', 15 );

function cargar_html_personalizado_blog() {
    // is_home() detecta si estás en la página principal del Blog (Archive Blog)
    if ( is_home() || ( is_archive() && ! is_post_type_archive( 'product' ) && ! is_tax( array( 'product_cat', 'product_tag' ) ) ) ) {
        
        $file_path = get_stylesheet_directory() . '/content-blog-header.php';
        
        if ( file_exists( $file_path ) ) {
            include $file_path;
        }
    }
}

add_action( 'get_footer', 'mi_shortcode_antes_del_footer', 2 );

function mi_shortcode_antes_del_footer() {
    // Si quieres que salga en TODO el sitio web:
   if ( is_home() || is_archive()) {
        // Busca el archivo dentro de la carpeta de tu tema hijo
        include get_stylesheet_directory() . '/content-blog-footer.php';
    }elseif ( is_single() ) {
        include get_stylesheet_directory() . '/content-blog-footer-simple.php';
    }
}



add_filter( 'the_title', 'agregar_etiquetas_limpias_card', 10, 2 );
function agregar_etiquetas_limpias_card( $title, $id ) {
    if ( ! is_singular() && get_post_type( $id ) === 'post' && in_the_loop() ) {
        
        // Obtenemos los objetos de las etiquetas directamente para controlarlas mejor
        $tags = get_the_tags($id);
        
        if ( $tags ) {
            $html = '<div class="card-tags">';
            foreach ( $tags as $tag ) {
                // SÓLO si la etiqueta tiene un nombre real y no está vacía
                if ( ! empty( trim( $tag->name ) ) ) {
                    $html .= '<a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '">' . esc_html( $tag->name ) . '</a>';
                }
            }
            $html .= '</div>';
            
            return $html . $title;
        }
    }
    return $title;
}


add_action( 'woodmart_after_header', 'cargar_html_personalizado_tienda', 15 );

function cargar_html_personalizado_tienda() {
    if ( ( is_shop() || is_product_category() || is_product_tag() ) && ! is_product() ) {
        
        $file_path = get_stylesheet_directory() . '/content-products-header.php';
        
        if ( file_exists( $file_path ) ) {
            include $file_path;
        }
    }
}

add_action( 'woocommerce_after_single_product_summary', 'mostrar_texto_personalizado_detalle', 5 );

function mostrar_texto_personalizado_detalle() {
        $file_path = get_stylesheet_directory() . '/content-product-benefit.php';
        
        if ( file_exists( $file_path ) ) {
            include $file_path;
        }
}


add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );
function woo_remove_product_tabs( $tabs ) {
    unset( $tabs['additional_information'] ); // Oculta la pestaña de información adicional
    return $tabs;
}


function custom_wc_category_breadcrumbs_shortcode($atts) {
    // 1. Definir los atributos del shortcode
    $atts = shortcode_atts(
        array(
            'category' => '',
        ), 
        $atts, 
        'wc_cat_breadcrumbs'
    );

    if (empty($atts['category'])) {
        return '';
    }

    // 2. Obtener la categoría y validar
    $term = is_numeric($atts['category']) 
        ? get_term_by('id', $atts['category'], 'product_cat') 
        : get_term_by('slug', $atts['category'], 'product_cat');

    if (!$term || is_wp_error($term)) {
        return '';
    }

    // Condición: Si no hay productos, no se muestra nada
    if ($term->count === 0) {
        return '';
    }

    // 3. Simular el entorno de WooCommerce
    global $wp_query;
    $original_queried_object = $wp_query->get_queried_object();
    $wp_query->queried_object = $term;

    // 4. Capturar el HTML con el separador y contenedor adecuados para el diseño
    ob_start();
    
    woocommerce_breadcrumb(array(
        'delimiter'   => '',
        'wrap_before' => '<nav class="woocommerce-breadcrumb custom-styled-breadcrumbs">',
        'wrap_after'  => '</nav>',
        'before'      => '',
        'after'       => '',
    ));
    
    $breadcrumbs = ob_get_clean();

    // 5. Restaurar entorno original
    $wp_query->queried_object = $original_queried_object;

    return $breadcrumbs;
}
add_shortcode('wc_cat_breadcrumbs', 'custom_wc_category_breadcrumbs_shortcode');


add_action( 'woocommerce_shipping_zone_before_methods_table', 'add_custom_shipping_zone_header_content' );

function add_custom_shipping_zone_header_content() {
    if ( ! isset( $_GET['zone_id'] ) ) return;
    
    // Activar Thickbox (el modal nativo de WordPress)
    add_thickbox();
    ?>
    <div class="nunatura-admin-notice">
        <div class="nunatura-notice-main">
            <span class="dashicons dashicons-info-outline"></span>
            <div class="nunatura-notice-text">
                <strong>Guía de Configuración:</strong> Recuerda que puedes asignar costos de envío a distritos de Lima Metropolitana y El Callao.
                <p>Por ejemplo: Puedes crear costos de envío para algunos distritos de Lima Metropolitana</p>
                <p>1. Nombre de la zona: Lima Metropolitana - Zona Norte</p>
                <p>2. Region(es) de la zona: Seleccionar Lima Metropolitana y hacer click en "Limitar a códigos postales específicos" para agregar los códigos de los distritos a escoger.</p>
                <p>3. Luego agregar un código por cada linea:</p>
                <p>LIM_ANCON</p>
                <p>LIM_ATE</p>
                <p>...</p>
                <p>4. Finalmente, realizar los mismos procedimientos para crear un costo de envío</p>
            </div>
        </div>
        
        <div class="nunatura-notice-actions">
            <a href="#TB_inline?width=600&height=500&inlineId=tabla-distritos-modal" class="button button-primary thickbox">Ver códigos de distritos</a>
        </div>
    </div>

    <div id="tabla-distritos-modal" style="display:none;">
        <div class="nunatura-table-wrapper" style="overflow-x: auto; margin: 20px 0; background: #fff; padding: 15px; ">
            <table class="wp-list-table widefat fixed striped" style="min-width: 400px;">
                <thead>
                    <tr>
                        <th style="width: 40%; font-weight: bold;">Value (Código Interno)</th>
                        <th style="width: 60%; font-weight: bold;">Nombre del Distrito</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="background-color: #e7f7f7;"><td colspan="2" style="font-weight: bold; text-align: center;">LIMA METROPOLITANA</td></tr>
                    <tr><td>LIM_ANCON</td><td>Ancón</td></tr>
                    <tr><td>LIM_ATE</td><td>Ate</td></tr>
                    <tr><td>LIM_BARRANCO</td><td>Barranco</td></tr>
                    <tr><td>LIM_BRENA</td><td>Breña</td></tr>
                    <tr><td>LIM_CARABAYLLO</td><td>Carabayllo</td></tr>
                    <tr><td>LIM_CHACLACAYO</td><td>Chaclacayo</td></tr>
                    <tr><td>LIM_CHORRILLOS</td><td>Chorrillos</td></tr>
                    <tr><td>LIM_CIENEGUILLA</td><td>Cieneguilla</td></tr>
                    <tr><td>LIM_COMAS</td><td>Comas</td></tr>
                    <tr><td>LIM_AGUSTINO</td><td>El Agustino</td></tr>
                    <tr><td>LIM_INDEPENDENCIA</td><td>Independencia</td></tr>
                    <tr><td>LIM_JESUS_MARIA</td><td>Jesús María</td></tr>
                    <tr><td>LIM_LA_MOLINA</td><td>La Molina</td></tr>
                    <tr><td>LIM_LA_VICTORIA</td><td>La Victoria</td></tr>
                    <tr><td>LIM_CERCADO</td><td>Lima (Cercado)</td></tr>
                    <tr><td>LIM_LINCE</td><td>Lince</td></tr>
                    <tr><td>LIM_LOS_OLIVOS</td><td>Los Olivos</td></tr>
                    <tr><td>LIM_LURIGANCHO</td><td>Lurigancho - Chosica</td></tr>
                    <tr><td>LIM_LURIN</td><td>Lurín</td></tr>
                    <tr><td>LIM_MAGDALENA</td><td>Magdalena del Mar</td></tr>
                    <tr><td>LIM_MIRAFLORES</td><td>Miraflores</td></tr>
                    <tr><td>LIM_PACHACAMAC</td><td>Pachacámac</td></tr>
                    <tr><td>LIM_PUCUSANA</td><td>Pucusana</td></tr>
                    <tr><td>LIM_PUEBLO_LIBRE</td><td>Pueblo Libre</td></tr>
                    <tr><td>LIM_PUENTE_PIEDRA</td><td>Puente Piedra</td></tr>
                    <tr><td>LIM_PUNTA_HERMOSA</td><td>Punta Hermosa</td></tr>
                    <tr><td>LIM_PUNTA_NEGRA</td><td>Punta Negra</td></tr>
                    <tr><td>LIM_RIMAC</td><td>Rímac</td></tr>
                    <tr><td>LIM_SAN_BARTOLO</td><td>San Bartolo</td></tr>
                    <tr><td>LIM_SAN_BORJA</td><td>San Borja</td></tr>
                    <tr><td>LIM_SAN_ISIDRO</td><td>San Isidro</td></tr>
                    <tr><td>LIM_SJL</td><td>San Juan de Lurigancho</td></tr>
                    <tr><td>LIM_SJM</td><td>San Juan de Miraflores</td></tr>
                    <tr><td>LIM_SAN_LUIS</td><td>San Luis</td></tr>
                    <tr><td>LIM_SAN_MARTIN</td><td>San Martín de Porres</td></tr>
                    <tr><td>LIM_SAN_MIGUEL</td><td>San Miguel</td></tr>
                    <tr><td>LIM_SANTA_ANITA</td><td>Santa Anita</td></tr>
                    <tr><td>LIM_SMM</td><td>Santa María del Mar</td></tr>
                    <tr><td>LIM_SANTA_ROSA</td><td>Santa Rosa</td></tr>
                    <tr><td>LIM_SANTIAGO_SURCO</td><td>Santiago de Surco</td></tr>
                    <tr><td>LIM_SURQUILLO</td><td>Surquillo</td></tr>
                    <tr><td>LIM_VES</td><td>Villa El Salvador</td></tr>
                    <tr><td>LIM_VMT</td><td>Villa María del Triunfo</td></tr>
                    <!-- CALLAO -->
                    <tr style="background-color: #e7f7f7;"><td colspan="2" style="font-weight: bold; text-align: center;">CALLAO</td></tr>
                    <tr><td>CAL_BELLAVISTA</td><td>Bellavista</td></tr>
                    <tr><td>CAL_CERCADO</td><td>Callao</td></tr>
                    <tr><td>CAL_CLR</td><td>Carmen de la Legua</td></tr>
                    <tr><td>CAL_LA_PERLA</td><td>La Perla</td></tr>
                    <tr><td>CAL_LA_PUNTA</td><td>La Punta</td></tr>
                    <tr><td>CAL_MI_PERU</td><td>Mi Perú</td></tr>
                    <tr><td>CAL_VENTANILLA</td><td>Ventanilla</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <style>
        .nunatura-admin-notice {
            position: relative; /* Necesario para posicionar el botón */
            background: #fff;
            border-left: 4px solid #00C2C7;
            padding: 16px;
            padding-bottom: 40px; /* Espacio extra para el botón */
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        /* Botón en la esquina inferior derecha */
        .nunatura-notice-actions {
            position: absolute;
            bottom: 10px;
            right: 15px;
        }

        .nunatura-notice-main { display: flex; align-items: flex-start; gap: 12px; }
        .nunatura-notice-text { font-size: 13px; color: #3c434a; margin: 0; }
        .nunatura-notice-text p{ margin: 0px }
        .dashicons { color: #00C2C7; font-size: 24px; }
        
        @media (max-width: 600px) {
            .nunatura-notice-actions { position: static; margin-top: 15px; }
        }
    </style>
    <?php
}


add_action( 'wp_footer', 'nunatura_script_checkout_avanzado', 99 );
function nunatura_script_checkout_avanzado() {
    if ( ! is_checkout() || is_wc_endpoint_url( 'order-received' ) ) return;
    
    // Definimos las listas aquí mismo para que estén disponibles en JS
   $distritos = array(
        'LMA' => array(
            'LIM_ANCON' => 'Ancón',
            'LIM_ATE' => 'Ate',
            'LIM_BARRANCO' => 'Barranco',
            'LIM_BRENA' => 'Breña',
            'LIM_CARABAYLLO' => 'Carabayllo',
            'LIM_CHACLACAYO' => 'Chaclacayo',
            'LIM_CHORRILLOS' => 'Chorrillos',
            'LIM_CIENEGUILLA' => 'Cieneguilla',
            'LIM_COMAS' => 'Comas',
            'LIM_AGUSTINO' => 'El Agustino',
            'LIM_INDEPENDENCIA' => 'Independencia',
            'LIM_JESUS_MARIA' => 'Jesús María',
            'LIM_LA_MOLINA' => 'La Molina',
            'LIM_LA_VICTORIA' => 'La Victoria',
            'LIM_CERCADO' => 'Lima (Cercado)',
            'LIM_LINCE' => 'Lince',
            'LIM_LOS_OLIVOS' => 'Los Olivos',
            'LIM_LURIGANCHO' => 'Lurigancho - Chosica',
            'LIM_LURIN' => 'Lurín',
            'LIM_MAGDALENA' => 'Magdalena del Mar',
            'LIM_MIRAFLORES' => 'Miraflores',
            'LIM_PACHACAMAC' => 'Pachacámac',
            'LIM_PUCUSANA' => 'Pucusana',
            'LIM_PUEBLO_LIBRE' => 'Pueblo Libre',
            'LIM_PUENTE_PIEDRA' => 'Puente Piedra',
            'LIM_PUNTA_HERMOSA' => 'Punta Hermosa',
            'LIM_PUNTA_NEGRA' => 'Punta Negra',
            'LIM_RIMAC' => 'Rímac',
            'LIM_SAN_BARTOLO' => 'San Bartolo',
            'LIM_SAN_BORJA' => 'San Borja',
            'LIM_SAN_ISIDRO' => 'San Isidro',
            'LIM_SJL' => 'San Juan de Lurigancho',
            'LIM_SJM' => 'San Juan de Miraflores',
            'LIM_SAN_LUIS' => 'San Luis',
            'LIM_SAN_MARTIN' => 'San Martín de Porres',
            'LIM_SAN_MIGUEL' => 'San Miguel',
            'LIM_SANTA_ANITA' => 'Santa Anita',
            'LIM_SMM' => 'Santa María del Mar',
            'LIM_SANTA_ROSA' => 'Santa Rosa',
            'LIM_SANTIAGO_SURCO' => 'Santiago de Surco',
            'LIM_SURQUILLO' => 'Surquillo',
            'LIM_VES' => 'Villa El Salvador',
            'LIM_VMT' => 'Villa María del Triunfo'
        ),
        'CAL' => array(
            'CAL_BELLAVISTA' => 'Bellavista',
            'CAL_CERCADO' => 'Callao',
            'CAL_CLR' => 'Carmen de la Legua',
            'CAL_LA_PERLA' => 'La Perla',
            'CAL_LA_PUNTA' => 'La Punta',
            'CAL_MI_PERU' => 'Mi Perú',
            'CAL_VENTANILLA' => 'Ventanilla'
        )
    );
    ?>
    <script>
    jQuery(document).ready(function($) {
        const datosDistritos = <?php echo json_encode($distritos); ?>;

        function actualizarDistritos() {
            const depto = $('#billing_state').val();
            const selectDistrito = $('#billing_postcode'); 

            // Limpiamos opciones previas
            let opciones = '<option value="">Selecciona un distrito...</option>';

            if (datosDistritos[depto]) {
                // Usamos $.each para obtener la llave (codigo) y el valor (nombre)
                $.each(datosDistritos[depto], function(codigo, nombre) {
                    opciones += `<option value="${codigo}">${nombre}</option>`;
                });
            }
            
            // Inyectamos las nuevas opciones
            selectDistrito.html(opciones);
            
            // Si usas Select2/SelectWoo, dispara el evento para que se refresque
            selectDistrito.trigger('change.select2');
        }

        actualizarDistritos();
        // Evento cuando cambia el Departamento
        $(document.body).on('change', '#billing_state', function() {
            actualizarDistritos();
        });
        
    });
    
    </script>
    <?php
}


add_filter( 'woocommerce_checkout_fields' , 'nunatura_ocultar_campo_pais' );

function nunatura_ocultar_campo_pais( $fields ) {
    // Cambiamos el tipo de campo a 'hidden' (oculto) pero mantiene el valor PE (Perú)
    $fields['billing']['billing_country']['type'] = 'hidden';
    $fields['shipping']['shipping_country']['type'] = 'hidden';
    
    return $fields;
}

// 1. Deshabilitamos el formulario de cupón en su posición nativa superior
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );

// 2. Inyectamos los inputs puros (sin etiquetas <form>) antes de los pagos
add_action( 'woocommerce_checkout_order_review', 'nunatura_input_cupon_limpio', 9 );

function nunatura_input_cupon_limpio() {
    if ( ! wc_coupons_enabled() ) return;
    ?>
    <div class="checkout-custom-coupon-wrapper tw-my-6 tw-p-4 tw-border tw-border-dashed tw-border-[#00C2C7] tw-rounded-xl">
        <h4 class="tw-text-base tw-font-semibold tw-font-poppins tw-mb-3 tw-text-slate-700">¿Tienes un cupón de descuento?</h4>
        <div class="custom-coupon-fields" style="display: flex; gap: 10px; align-items: center;">
            <input type="text" id="custom_coupon_code" class="input-text" placeholder="Código de cupón" value="" style="flex: 1;" />
            <button type="button" id="custom_apply_coupon" class="button" style="white-space: nowrap;">APLICAR CUPÓN</button>
        </div>
        <div id="custom-coupon-message" style="margin-top: 10px; font-size: 14px; font-family: 'Poppins', sans-serif;"></div>
    </div>

    <!-- Script para comunicar el botón con el sistema AJAX de WooCommerce sin recargar -->
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $(document).on('click', '#custom_apply_coupon', function(e) {
                e.preventDefault(); // Evita por completo que el checkout intente comprar
                
                var couponCode = $('#custom_coupon_code').val();
                if (!couponCode) {
                    $('#custom-coupon-message').html('<span style="color:red;">Por favor, ingresa un código.</span>');
                    return;
                }

                $('#custom_apply_coupon').text('APLICANDO...').prop('disabled', true);

                // Enviamos el cupón de manera segura usando el AJAX nativo de WooCommerce
                var data = {
                    security: wc_checkout_params.apply_coupon_nonce,
                    coupon_code: couponCode
                };

                $.ajax({
                    type: 'POST',
                    url: wc_checkout_params.wc_ajax_url.toString().replace('%%endpoint%%', 'apply_coupon'),
                    data: data,
                    success: function(code) {
                        $('.woocommerce-error, .woocommerce-message').remove();
                        
                        // Recargamos el resumen de pedido para ver reflejado el descuento
                        $(document.body).trigger('update_checkout');
                        
                        // Restauramos el botón
                        $('#custom_apply_coupon').text('APLICAR CUPÓN').prop('disabled', false);
                        $('#custom_coupon_code').val('');
                    }
                });
            });
        });
    </script>
    <?php
}

/**
 * When WooCommerce asks for the template file for the location of the
 * myaccount/form-login.php file, we can override it with our own page if
 * "?action=register" is in the URL.
 */
function wptwcur_wc_get_template($template, $template_name, $args, $template_path, $default_path) {
   if (($template_name == 'myaccount/form-login.php') && array_key_exists('action', $_GET) && ($_GET['action'] == 'register')) {
      $template = dirname(__FILE__) . '/wpt-wc-form-register.php';
   }

   return $template;
}
add_filter('wc_get_template', 'wptwcur_wc_get_template', 10, 5);


/* ==========================================================================
   NUNATURA: CARGA MODULAR DE ESTILOS CSS
   ========================================================================== */
add_action( 'wp_enqueue_scripts', 'nunatura_cargar_estilos_modulares', 99 );

function nunatura_cargar_estilos_modulares() {
    // 1. Ruta base a tu carpeta CSS
    $css_dir = get_stylesheet_directory_uri() . '/assets/css/';
    $version = '1.0.0'; // Cambia esto (ej. 1.0.1) cuando actualices tu CSS para limpiar la caché

    wp_enqueue_style( 'nuna-global', $css_dir . 'global.css', array(), $version );
    wp_enqueue_style( 'nuna-login', $css_dir . 'login.css', array(), $version );
    wp_enqueue_style( 'nuna-register', $css_dir . 'register.css', array(), $version );
    wp_enqueue_style( 'nuna-dashboard', $css_dir . 'dashboard.css', array(), $version );
    wp_enqueue_style( 'nuna-checkout', $css_dir . 'checkout.css', array(), $version );
    wp_enqueue_style( 'nuna-recovery', $css_dir . 'recovery.css', array(), $version );
    wp_enqueue_style( 'nuna-claim', $css_dir . 'claim.css', array(), $version );
    wp_enqueue_style( 'nuna-thanks', $css_dir . 'thankyu.css', array(), $version );
    wp_enqueue_style( 'nuna-mobile', $css_dir . 'menumobile.css', array(), $version );
   
}


/* ==========================================================================
   NUNATURA: AGREGAR ENLACE DE REGISTRO AL FINAL DEL FORMULARIO DE LOGIN
   ========================================================================== */
add_action( 'woocommerce_login_form_end', 'nuna_agregar_enlace_registro_js' );

function nuna_agregar_enlace_registro_js() {
    // Obtenemos la URL dinámica de "Mi Cuenta" y le añadimos el parámetro register
    // Esto es mejor que poner "localhost", así no se romperá cuando subas la web a producción.
    $url_registro = wc_get_page_permalink( 'myaccount' ) . '?action=register';
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Buscamos el contenedor de contraseña perdida e inyectamos el nuevo enlace al lado
            if ($('.woocommerce-LostPassword.lost_password').length) {
                $('.woocommerce-LostPassword.lost_password').append('<a href="<?php echo esc_url($url_registro); ?>" class="nuna-crear-cuenta-link">Crear una cuenta</a>');
            }
        });
    </script>
    <?php
}


add_action( 'woocommerce_single_product_summary', 'renderizar_atributos_y_shortcode', 25 );

function renderizar_atributos_y_shortcode() {
    global $product;

    if ( ! is_a( $product, 'WC_Product' ) || ! function_exists( 'get_field' ) ) {
        return;
    }

    // 1. Verificar si es cotizable mediante ACF
    if ( ! get_field( 'es_producto_cotizable', $product->get_id() ) ) return;

    // 2. Guard clause: Salimos si no es un producto simple
    if ( ! $product->is_type('simple') ) return;

    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
    
    // 2. Renderizar los atributos dinámicos
    $attributes = $product->get_attributes();
    if ( ! empty( $attributes ) ) {
        echo '<div class="cotizacion-atributos-wrapper" style="margin-bottom: 20px;">';
        
        foreach ( $attributes as $name => $attribute ) {
            if ( $attribute->is_taxonomy() ) {
                $terms = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'all' ) );
                
                if ( ! empty( $terms ) ) {
                    $label = wc_attribute_label( $attribute->get_name() );
                    echo '<div class="attr-row" style="margin-bottom: 10px;">';
                    echo '<label style="display:block; font-weight:bold;">' . esc_html( $label ) . ':</label>';
                    echo '<select class="attr-select" name="attr_' . esc_attr( $attribute->get_name() ) . '" style="width: 100%;">';
                    echo '<option value="">Selecciona una opción</option>';
                    foreach ( $terms as $term ) {
                        echo '<option value="' . esc_attr( $term->name ) . '">' . esc_html( $term->name ) . '</option>';
                    }
                    echo '</select>';
                    echo '</div>';
                }
            }
        }
        echo '</div>';
    }

    // 3. Renderizar tu Shortcode (El botón que abre el formulario de Elementor)
    echo '<div class="cotizacion-shortcode-container" style="margin-top: 20px;">';
    echo do_shortcode( '[html_block id="707"]' );
    echo '</div>';
}




add_action( 'wp_footer', 'agregar_script_cotizacion_footer' );

function agregar_script_cotizacion_footer() {
    // 1. Verificamos que estamos en una página de producto para no cargar JS innecesario
    if ( ! is_product() ) return;
    ?>
    <script>
        jQuery(document).ready(function($) {
            var $btn = $('#contactoasesor');
            var $selects = $('.attr-select');
            var $tituloDisplay = $('#tituloformproducto p');
            var nombreProductoBase = "<?php echo esc_js(get_the_title()); ?>";

            function actualizarInterfaz() {
                var seleccionados = [];
                var completo = true;

                // Si no hay selects, está completo por defecto
                if ($selects.length === 0) {
                    $tituloDisplay.text(nombreProductoBase);
                    return;
                }

                $selects.each(function() {
                    var val = $(this).val();
                    if (val === "") {
                        completo = false;
                    } else {
                        seleccionados.push(val);
                    }
                });

                if (completo) {
                    $btn.css({'opacity': '1', 'pointer-events': 'auto'});
                    $tituloDisplay.text(nombreProductoBase + ' - ' + seleccionados.join(' - '));
                    $('input[name="form_fields[atributos]"]').val(seleccionados.join(' | '));
                } else {
                    $btn.css({'opacity': '0.5', 'pointer-events': 'none'});
                    $tituloDisplay.text('Selecciona todos los atributos');
                }
            }

            // 1. Ejecutar al cargar
            actualizarInterfaz();

            // 2. Escuchar cambios en selects
            $selects.on('change', function() {
                actualizarInterfaz();
            });

            // 3. NUEVO: Asegurar que al hacer clic en el botón se fuerce la lectura
            $btn.on('click', function(e) {
                // Forzamos una actualización inmediata
                actualizarInterfaz();
            
                // Verificamos si realmente está bloqueado visualmente
                if ($btn.css('pointer-events') === 'none') {
                    e.preventDefault();
                    alert('Por favor, selecciona los atributos primero.');
                    return false;
                }

                // El truco: Esperamos unos milisegundos a que el Popup se abra 
                setTimeout(function() {
                    var seleccionados = [];
                    $selects.each(function() {
                        seleccionados.push($(this).val());
                    });
                    
                    var textoFinal = seleccionados.length > 0 
                        ? nombreProductoBase + ' (' + seleccionados.join(' - ') + ')' 
                        : nombreProductoBase;

                    // Seteamos el texto directamente otra vez para que el Popup lo muestre bien
                    $('#tituloformproducto p').text(textoFinal);

                    var valorInput = seleccionados.length > 0 
                        ? seleccionados.join(' | ') 
                        : "Sin atributos";

                    $('input[name="form_fields[atributos]"]').val(valorInput);

                }, 200); 
            });
        });
    </script>
    <?php
}


/* ==========================================================================
   NUNATURA: SHORTCODE PARA ENLACE DINÁMICO DE WHATSAPP (ACF OPTIONS)
   ========================================================================== */
add_shortcode( 'nunatura_enlace_wa', 'nuna_generar_enlace_whatsapp' );

function nuna_generar_enlace_whatsapp() {
    // 1. Obtenemos el número desde la página de opciones de ACF
    // Cambia 'numero_whatsapp' por el ID (nombre) real de tu campo en ACF
    $numero = get_field('nro_whattsapp', 'option');
    
    // Si por alguna razón el campo está vacío en el panel, devolvemos un '#' para no romper el botón
    if ( empty($numero) ) {
        return '#';
    }

    // 2. Limpieza estricta: Eliminamos espacios, guiones o símbolos raros del número
    // Para que "999 999 999" o "+51-999" se convierta puramente en "51999999999"
    $numero_limpio = preg_replace('/[^0-9]/', '', $numero);

    // 3. Opcional: Si tienes un campo en ACF para un mensaje por defecto, lo traemos
    // Si no tienes este campo, simplemente ignora esta parte.
    $mensaje = get_field('mensaje_de_whatsapp', 'option'); 
    $texto_url = '';
    
    if ( !empty($mensaje) ) {
        // urlencode convierte los espacios en %20 para que la URL sea válida
        $texto_url = '?text=' . urlencode($mensaje);
    }

    // 4. Retornamos la URL oficial de WhatsApp armada
    return 'https://wa.me/' . $numero_limpio . $texto_url;
}

add_filter( 'woocommerce_gateway_description', 'mostrar_cuentas_bancarias_mejorado', 20, 2 );

function mostrar_cuentas_bancarias_mejorado( $description, $payment_id ) {
    if ( $payment_id === 'bacs' ) {
        $bacs_accounts = get_option( 'woocommerce_bacs_accounts' );
        
        if ( ! empty( $bacs_accounts ) ) {
            $html = '<div class="bacs-checkout-container" style="margin-top: 20px; display: grid; gap: 15px;">';
            
            foreach ( $bacs_accounts as $account ) {
                // Si el campo 'bic' tiene URL, lo usamos como logo, si no, usamos un placeholder
                $logo_url = !empty($account['bic']) ? esc_url($account['bic']) : 'https://via.placeholder.com/50';
                
                $html .= '
                <div class="bancos_transferecias" style="display: flex; align-items: center; gap: 15px; background: #ffffff; padding: 16px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.05); transition: transform 0.2s;">
                    <img class="img_bancos" src="' . $logo_url . '" width="50" style="border-radius: 8px; object-fit: contain;" alt="Banco">
                    <div style="line-height: 1.4;">
                        <div style="font-weight: 700; color: #2d3748; font-size: 15px;">' . esc_html($account['bank_name']) . '</div>
                        <div style="font-size: 14px; color: #4a5568;">C.Corriente: <span style="font-family: monospace; font-weight: bold;">' . esc_html($account['account_number']) . '</span></div>
                        <div style="font-size: 14px; color: #4a5568;">CCI: <span style="font-family: monospace; font-weight: bold;">' . esc_html($account['iban']) . '</span></div>
                        <div style="font-size: 13px; color: #718096;">Titular: ' . esc_html($account['account_name']) . '</div>
                    </div>
                </div>';
            }
            
            $html .= '</div>';
            $description .= $html;
        }
    }
    return $description;
}


add_action( 'woocommerce_review_order_before_submit', 'agregar_campo_archivo_voucher_inteligente' );

function agregar_campo_archivo_voucher_inteligente() {
    // Inicialmente lo ocultamos (display:none) por si carga otro método por defecto
    echo '<div id="campo_voucher_pago" style="display:none; margin: 15px 0; padding: 15px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f9fbfc;">';
    
    echo '<label style="font-weight:bold; display:block; margin-bottom:8px; color:#2d3748;">Adjuntar comprobante de pago <abbr class="required" title="obligatorio">*</abbr></label>';
    echo '<input type="file" id="voucher_pago_file" accept="image/png,image/jpeg,image/webp,application/pdf" style="width: 100%; padding: 8px; border: 1px dashed #cbd5e0; border-radius: 4px; background: #fff;" />';
    
    // Campo oculto para la URL
    echo '<input type="hidden" name="voucher_pago_url_hidden" id="voucher_pago_url_hidden" value="" />';
    
    // Textos de estado
    echo '<p id="voucher_upload_status" style="font-size: 13px; margin-top: 8px; font-weight: bold;"></p>';
    echo '<p style="font-size: 12px; color: #718096; margin-bottom: 0;">Formatos aceptados: png, jpg, webp, jpeg o pdf.</p>';
    echo '</div>';

    // Script resistente a AJAX y Plugins
    echo '<script>
    // Variable global para sobrevivir a las recargas de WooCommerce
    var voucher_url_guardada_nunatura = ""; 

    jQuery(document).ready(function($) {
        
        // --- 1. LÓGICA DE MOSTRAR/OCULTAR ---
        function verificarMetodoPago() {
            var method = $("input[name=\'payment_method\']:checked").val();
            if (method === "bacs") {
                $("#campo_voucher_pago").slideDown(); // Animación suave
            } else {
                $("#campo_voucher_pago").slideUp();
            }
        }

        // Ejecutar al cargar la página
        verificarMetodoPago();

        // Ejecutar cuando el usuario cambia de método haciendo clic
        $(document.body).on("change", "input[name=\'payment_method\']", function() {
            verificarMetodoPago();
        });
        // -------------------------------------
        
        // 2. Restaurar la URL y estado si WooCommerce recarga el formulario
        $(document.body).on("updated_checkout", function() {
            verificarMetodoPago(); // Asegurar que se muestre/oculte bien tras recargar
            
            if (voucher_url_guardada_nunatura !== "") {
                $("#voucher_pago_url_hidden").val(voucher_url_guardada_nunatura);
                $("#voucher_upload_status").css("color", "green").text("✅ Archivo adjuntado (listo para procesar).");
            }
        });

        // 3. Subir archivo asíncronamente
        $(document.body).on("change", "#voucher_pago_file", function() {
            var files = $(this).prop("files");
            
            // Protección contra el error de ThemeHigh
            if (!files || files.length === 0) return; 
            
            var file_data = files[0];
            var form_data = new FormData();
            form_data.append("file", file_data);
            form_data.append("action", "subir_voucher_ajax");

            $("#voucher_upload_status").css("color", "#007cba").text("⏳ Subiendo archivo, por favor espera...");

            $.ajax({
                url: "' . admin_url('admin-ajax.php') . '",
                type: "POST",
                contentType: false,
                processData: false,
                data: form_data,
                success: function(response) {
                    if (response.success) {
                        voucher_url_guardada_nunatura = response.data.url; // Guardamos en memoria
                        $("#voucher_pago_url_hidden").val(response.data.url);
                        $("#voucher_upload_status").css("color", "green").text("✅ Archivo adjuntado correctamente.");
                    } else {
                        $("#voucher_upload_status").css("color", "red").text("❌ Error: " + response.data);
                        voucher_url_guardada_nunatura = "";
                        $("#voucher_pago_url_hidden").val("");
                    }
                },
                error: function() {
                    $("#voucher_upload_status").css("color", "red").text("❌ Error de conexión al servidor.");
                    voucher_url_guardada_nunatura = "";
                    $("#voucher_pago_url_hidden").val("");
                }
            });
        });
    });
    </script>';
}


add_action('wp_ajax_subir_voucher_ajax', 'procesar_subida_voucher_ajax');
add_action('wp_ajax_nopriv_subir_voucher_ajax', 'procesar_subida_voucher_ajax');

function procesar_subida_voucher_ajax() {
    if ( empty($_FILES['file']) ) {
        wp_send_json_error('No se recibió ningún archivo.');
    }

    $file = $_FILES['file'];
    
    // Validación de seguridad en el backend
    $allowed_mimes = array('image/jpeg', 'image/png', 'image/webp', 'application/pdf');
    if ( ! in_array( $file['type'], $allowed_mimes ) ) {
        wp_send_json_error('Formato de archivo no válido.');
    }

    // Usamos las funciones nativas de WP para guardar el archivo
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
    $uploaded = wp_handle_upload( $file, array( 'test_form' => false ) );

    if ( $uploaded && ! isset( $uploaded['error'] ) ) {
        // Devolvemos la URL del archivo subido al JavaScript
        wp_send_json_success( array( 'url' => $uploaded['url'] ) );
    } else {
        wp_send_json_error( $uploaded['error'] );
    }
}

// Validar que el campo oculto contenga la URL
add_action('woocommerce_checkout_process', 'validar_voucher_pago');

function validar_voucher_pago() {
    if ( isset($_POST['payment_method']) && $_POST['payment_method'] === 'bacs' ) {
        // Validamos el input hidden
        if ( empty($_POST['voucher_pago_url_hidden']) ) {
            wc_add_notice( 'Por favor, asegúrate de haber subido tu comprobante de pago para proceder.', 'error' );
        }
    }
}

// Guardar la URL como meta-dato del pedido
add_action( 'woocommerce_checkout_update_order_meta', 'guardar_archivo_voucher' );

function guardar_archivo_voucher( $order_id ) {
    if ( ! empty( $_POST['voucher_pago_url_hidden'] ) ) {
        // Limpiamos la URL por seguridad y la guardamos
        $url_segura = esc_url_raw( $_POST['voucher_pago_url_hidden'] );
        update_post_meta( $order_id, '_voucher_pago_url', $url_segura );
    }
}

// Mostrar enlace al voucher en el detalle del pedido (admin)
add_action( 'woocommerce_admin_order_data_after_billing_address', 'mostrar_voucher_en_admin', 10, 1 );

function mostrar_voucher_en_admin( $order ){
    $voucher_url = get_post_meta( $order->get_id(), '_voucher_pago_url', true );
    if ( $voucher_url ) {
        echo '<p><strong>Comprobante:</strong> <a href="'. esc_url($voucher_url) .'" target="_blank">Ver comprobante aquí</a></p>';
    }
}


/* ==========================================================================
   SOBRESCRIBIR TOTALMENTE EL RESUMEN DE PEDIDO DE WOODMART (DESDE EL CHILD)
   ========================================================================== */
if ( ! function_exists( 'woodmart_order_overview' ) ) {
    function woodmart_order_overview( $order ) {
        if ( ! $order || ! is_a( $order, 'WC_Order' ) ) {
            return;
        }
        ?>
        <div class="nuna-order-overview-grid woocommerce-thankyou-order-details">
            
            <div class="nuna-overview-card order">
                <div class="nuna-overview-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                </div>
                <div class="nuna-overview-data">
                    <span><?php esc_html_e( 'Order number:', 'woocommerce' ); ?></span>
                    <strong><?php echo $order->get_order_number(); ?></strong>
                </div>
            </div>

            <div class="nuna-overview-card date">
                <div class="nuna-overview-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                </div>
                <div class="nuna-overview-data">
                    <span><?php esc_html_e( 'Date:', 'woocommerce' ); ?></span>
                    <strong><?php echo wc_format_datetime( $order->get_date_created() ); ?></strong>
                </div>
            </div>

            <?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
                <div class="nuna-overview-card email">
                    <div class="nuna-overview-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    </div>
                    <div class="nuna-overview-data">
                        <span><?php esc_html_e( 'Email:', 'woocommerce' ); ?></span>
                        <strong><?php echo $order->get_billing_email(); ?></strong>
                    </div>
                </div>
            <?php endif; ?>

            

            <?php if ( $order->get_payment_method_title() ) : ?>
                <div class="nuna-overview-card method">
                    <div class="nuna-overview-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                    </div>
                    <div class="nuna-overview-data">
                        <span><?php esc_html_e( 'Payment method:', 'woocommerce' ); ?></span>
                        <strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
                    </div>
                </div>
            <?php endif; ?>

            <div class="nuna-overview-card total nuna-highlight">
                <div class="nuna-overview-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                </div>
                <div class="nuna-overview-data">
                    <span><?php esc_html_e( 'Total:', 'woocommerce' ); ?></span>
                    <strong><?php echo $order->get_formatted_order_total(); ?></strong>
                </div>
            </div>

        </div>
        <?php
    }
}

/* ==========================================================================
   NUNATURA: SHORTCODE PARA MOSTRAR CUENTAS BANCARIAS
   Uso: [mostrar_cuentas_bancarias]
   ========================================================================== */
add_shortcode( 'mostrar_cuentas_bancarias', 'nuna_shortcode_cuentas_bancarias' );

function nuna_shortcode_cuentas_bancarias( $atts ) {
    // 1. Obtenemos las cuentas guardadas en WooCommerce
    $bacs_accounts = get_option( 'woocommerce_bacs_accounts' );
    
    // 2. Si no hay cuentas configuradas, no retornamos nada
    if ( empty( $bacs_accounts ) ) {
        return '';
    }

    // 3. Contamos cuántas cuentas hay
    $total_cuentas = count( $bacs_accounts );

    // 4. Lógica condicional para el diseño (Grid vs Centrado)
    if ( $total_cuentas === 1 ) {
        // Si es 1: Usamos flexbox para centrarlo
        $contenedor_clases = 'tw-flex tw-justify-center';
        // Limitamos el ancho de la tarjeta para que no se estire por toda la pantalla
        $tarjeta_estilos = 'width: 100%; max-width: 320px;'; 
    } else {
        // Si son 2 o más: Usamos Grid (1 columna en móvil, 2 en PC con md:)
        $contenedor_clases = 'tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-6';
        // La tarjeta ocupa todo el espacio de su columna en el grid
        $tarjeta_estilos = 'width: 100%;'; 
    }

    // 5. Iniciamos la construcción del HTML aplicando las clases dinámicas
    $html = '<div class="bacs-checkout-container ' . esc_attr( $contenedor_clases ) . '" style="margin-top: 20px;">';
    
    foreach ( $bacs_accounts as $account ) {
        // Si el campo 'bic' (SWIFT) tiene URL, lo usamos como logo, si no, un placeholder
        $logo_url = !empty($account['bic']) ? esc_url($account['bic']) : 'https://via.placeholder.com/50';
        
        $html .= '
        <div class="bancos_transferecias" style="' . $tarjeta_estilos . ' display: flex; align-items: center; gap: 15px; background: #ffffff; padding: 16px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.05); transition: transform 0.2s;">
            <img class="img_bancos" src="' . $logo_url . '" width="50" style="border-radius: 8px; object-fit: contain; min-width: 50px;" alt="Banco">
            <div style="line-height: 1.4;">
                <div style="font-weight: 700; color: #2d3748; font-size: 15px;">' . esc_html($account['bank_name']) . '</div>
                <div style="font-size: 14px; color: #4a5568;">C.Corriente: <span style="font-family: monospace; font-weight: bold;">' . esc_html($account['account_number']) . '</span></div>
                <div style="font-size: 14px; color: #4a5568;">CCI: <span style="font-family: monospace; font-weight: bold;">' . esc_html($account['iban']) . '</span></div>
                <div style="font-size: 13px; color: #718096;">Titular: ' . esc_html($account['account_name']) . '</div>
            </div>
        </div>';
    }
    
    $html .= '</div>';
    
    // 6. Retornamos el HTML limpio
    return $html;
}



add_action( 'woocommerce_single_product_summary', 'mostrar_imagen_packaging_en_producto', 21 );

function mostrar_imagen_packaging_en_producto() {
    $product_id = get_the_ID();
    $imagen_packaging = get_field( 'imagen_de_packaking', $product_id );

    if ( $imagen_packaging ) {
        echo '<div class="packaging-wrapper" style="margin: 0px 0px 15px 0px;">';
        
        // Si es un ID (int), usamos wp_get_attachment_image para obtener la imagen optimizada
        if ( is_numeric( $imagen_packaging ) ) {
            echo wp_get_attachment_image( $imagen_packaging, 'full', false, array( 'style' => 'height: 130px; width: auto; display: block;' ) );
        } 
        // Si es un array (común en ACF), usamos la URL pero aseguramos que sea la real
        elseif ( is_array( $imagen_packaging ) ) {
            $url_imagen = $imagen_packaging['url'];
            echo '<img src="' . esc_url( $url_imagen ) . '" alt="Packaging" style="height: 130px; width: auto; display: block;">';
        }
        
        echo '</div>';
    }
}


// 1. Ocultar el precio general en la página de producto variable hasta que se seleccione una opción
add_filter( 'woocommerce_variable_price_html', 'nuna_ocultar_precio_variable_inicial', 10, 2 );

function nuna_ocultar_precio_variable_inicial( $price, $product ) {
    // Si estamos en la página individual del producto (single) y es variable
    if ( is_product() && ! is_admin() ) {
        return ''; // Devuelve vacío para que no muestre ningún rango ni precio mínimo inicial
    }
    return $price;
}

// 2. Asegurar que WooCommerce permita mostrar el precio de la variación al seleccionarla dinámicamente
add_filter( 'woocommerce_show_variation_price', '__return_true' );