=== AllpaCode Ubigeo Perú ===
Contributors: allpacode
Tags: ubigeo, peru, departamentos, provincias, distritos
Requires at least: 6.0
Tested up to: 7.0
Requires PHP: 8.0
Stable tag: 1.0.1
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Selectores dependientes de departamento, provincia y distrito del Perú con soporte para código UBIGEO.

== Description ==

AllpaCode Ubigeo Perú permite agregar selectores dependientes de departamento, provincia y distrito en sitios WordPress, formularios personalizados, tiendas WooCommerce y plugins que necesiten datos territoriales del Perú.

El plugin entrega el código UBIGEO final del distrito seleccionado y expone componentes reutilizables mediante shortcode, funciones PHP públicas y endpoints REST.

Características principales:

* Selector dependiente mediante shortcode.
* API PHP pública para integraciones con otros plugins.
* API REST pública para consultas dinámicas.
* Integración opcional con el checkout de WooCommerce.
* Compatibilidad con Contact Form 7 mediante shortcodes.
* Datos de departamento, provincia y distrito derivados de una fuente pública basada en INEI, RENIEC y SUNAT.
* Recursos frontend ligeros, sin dependencia de jQuery.
* Preparado para traducciones.

Shortcode:

`[allpacode_ubigeo_peru]`

Campos obligatorios:

`[allpacode_ubigeo_peru required="true"]`

Prefijo personalizado para nombres de campos:

`[allpacode_ubigeo_peru name_prefix="cliente"]`

Funciones PHP públicas:

* `allpacode_ubigeo_get_departamentos()`
* `allpacode_ubigeo_get_provincias( $codigo_departamento )`
* `allpacode_ubigeo_get_distritos( $codigo_provincia )`
* `allpacode_ubigeo_get_by_code( $codigo_ubigeo )`
* `allpacode_ubigeo_is_available()`

Endpoints REST:

* `/wp-json/allpacode-ubigeo/v1/departamentos`
* `/wp-json/allpacode-ubigeo/v1/provincias?departamento=15`
* `/wp-json/allpacode-ubigeo/v1/distritos?provincia=1501`
* `/wp-json/allpacode-ubigeo/v1/ubicacion/150101`

== Installation ==

1. Sube la carpeta `allpacode-ubigeo-peru` a `/wp-content/plugins/`.
2. Activa el plugin desde la pantalla de plugins de WordPress.
3. Ve a Ajustes > Ubigeo Perú para revisar las opciones disponibles.
4. Inserta `[allpacode_ubigeo_peru]` en páginas, formularios, widgets o constructores visuales.
5. Activa la integración con WooCommerce si deseas agregar campos UBIGEO al checkout.

== Frequently Asked Questions ==

= ¿Cuál es el shortcode? =

Usa `[allpacode_ubigeo_peru]`.

= ¿Puedo usarlo desde otro plugin? =

Sí. El plugin expone funciones PHP públicas, endpoints REST y un shortcode reutilizable.

= ¿Funciona con WooCommerce? =

Sí. Puedes activar los campos de departamento, provincia, distrito y código UBIGEO desde Ajustes > Ubigeo Perú.

= ¿Funciona con Contact Form 7? =

Sí. Inserta el shortcode `[allpacode_ubigeo_peru]` dentro del formulario. El plugin habilita el procesamiento de shortcodes en Contact Form 7.

= ¿Cuál es la fuente de datos? =

El conjunto de datos incluido deriva de la fuente pública CONCYTEC/ubigeo-peru, que documenta equivalencias entre códigos UBIGEO de INEI, RENIEC y SUNAT. Repositorio: https://github.com/CONCYTEC/ubigeo-peru

El conjunto original se distribuye bajo la licencia Creative Commons Attribution 4.0 International. Los detalles de atribución están incluidos en `docs/data-source.md`.

== Changelog ==

= 1.0.1 =

* Actualiza la documentación pública en español.
* Cambia el shortcode público a `[allpacode_ubigeo_peru]`.
* Cambia las funciones PHP públicas al prefijo `allpacode_ubigeo`.
* Cambia los endpoints REST al prefijo `/allpacode-ubigeo/v1/`.
* Agrega iconos para la ficha pública del repositorio de WordPress.

= 1.0.0 =

* Versión pública inicial.
* Selectores dependientes de departamento, provincia y distrito.
* API PHP pública.
* API REST pública.
* Integración opcional con WooCommerce.
* Compatibilidad con shortcodes en Contact Form 7.

== Upgrade Notice ==

= 1.0.1 =

Actualización recomendada para usar nombres públicos de AllpaCode en shortcodes, funciones y endpoints REST.
