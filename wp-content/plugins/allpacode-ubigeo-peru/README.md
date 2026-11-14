# AllpaCode Ubigeo Perú

Selectores dependientes de departamento, provincia y distrito del Perú con soporte para código UBIGEO.

Autor: AllpaCode  
Web: https://www.allpacode.com  
Text Domain: `allpacode-ubigeo-peru`

## Uso con shortcode

```text
[allpacode_ubigeo_peru]
[allpacode_ubigeo_peru required="true" name_prefix="cliente"]
```

## Funciones PHP públicas

```php
allpacode_ubigeo_get_departamentos();
allpacode_ubigeo_get_provincias( '15' );
allpacode_ubigeo_get_distritos( '1501' );
allpacode_ubigeo_get_by_code( '150101' );
allpacode_ubigeo_is_available();
```

## API REST

```text
/wp-json/allpacode-ubigeo/v1/departamentos
/wp-json/allpacode-ubigeo/v1/provincias?departamento=15
/wp-json/allpacode-ubigeo/v1/distritos?provincia=1501
/wp-json/allpacode-ubigeo/v1/ubicacion/150101
```

## Integraciones

- WooCommerce checkout.
- Contact Form 7 mediante shortcodes.
- Formularios personalizados mediante PHP/REST.
- Plugins que requieran datos territoriales del Perú.

## Fuente de datos

El conjunto de datos deriva de la fuente pública CONCYTEC/ubigeo-peru, con equivalencias de códigos UBIGEO de INEI, RENIEC y SUNAT.

Ver `docs/data-source.md`.
