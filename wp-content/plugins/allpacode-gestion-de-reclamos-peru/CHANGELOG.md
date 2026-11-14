# Changelog

## 1.0.12

- Agrega una zona peligrosa en Configuración para borrar todos los reclamos.
- La acción exige confirmación explícita, nonce administrativo y capacidad de gestión.
- Borra reclamos, registros internos y constancias PDF generadas por el plugin.

## 1.0.11

- Evita duplicar las etiquetas de departamento, provincia y distrito al marcar campos obligatorios.

## 1.0.10

- Corrige la codificación del título principal en la constancia PDF.
- Muestra el asterisco de campo obligatorio al final del texto de la etiqueta y en la misma línea.

## 1.0.9

- Ajusta el límite de envíos para no penalizar intentos con errores de validación o captcha.
- Aumenta el margen de envíos válidos permitidos durante la ventana anti-spam.
- Marca campos obligatorios con asterisco rojo en el formulario público.
- Mejora la validación visible para correo, longitudes y rangos antes de enviar.

## 1.0.8

- Corrige la codificación de fuentes en la constancia PDF para mostrar correctamente tildes, eñes y caracteres en español.

## 1.0.7

- Mejora la respuesta AJAX cuando vence el nonce para evitar respuestas `-1` sin mensaje.
- Agrega renovación de nonce antes del envío para mejorar compatibilidad con caché.
- Muestra errores de validación debajo de los campos correspondientes.
- Marca visualmente los campos obligatorios en el formulario público.
- Mejora los mensajes cuando Turnstile o reCAPTCHA requieren atención antes de enviar.

## 1.0.6

- Actualiza la ficha pública y documentación principal a español.
- Agrega assets visuales para WordPress.org.

## 1.0.5

- Validated the shortcode color attribute before rendering inline CSS variables.
- Updated IP handling for rate limiting to use the validated server remote address only.

## 1.0.4

- Updated the plugin name and slug to a more distinctive WordPress.org review name.
- Updated readme and documentation wording for INDECOPI compliance without implying affiliation.
- Documented optional external services: Cloudflare Turnstile, Google reCAPTCHA v3, and webhooks.
- Removed the arbitrary custom CSS setting from the admin appearance screen.
- Restricted the claim status REST endpoint to authorized administrators.
- Removed deprecated character conversion usage from PDF generation.
- Removed the bundled SQL schema file from the release package.
- Sanitized Gutenberg block render output.

## 1.0.3

- Updated public naming to AllpaCode identifiers.
- Updated the public REST namespace to `/wp-json/allpacode-gr/v1/`.
- Updated public hooks and AJAX actions to the `allpacode_gr` prefix.
- Updated the WordPress.org readme for initial review.
- Updated the integration with AllpaCode Ubigeo Perú 1.0.1.

## 1.0.2

- Corrige la visualización de tildes y caracteres en la constancia PDF.
- Muestra la dirección y la ubicación por ubigeo en el detalle administrativo del reclamo.
- Fuerza la actualización de tablas para instalaciones activas.

## 1.0.0

- Primera versión funcional.
- Formulario público AJAX.
- Gestión administrativa con `WP_List_Table`.
- Configuración de empresa, captcha, correos, apariencia y webhook.
- Exportaciones CSV, Excel compatible y reporte imprimible.
- Widget Elementor, bloque Gutenberg y widget clásico.
- REST API, logs, cron de retención y correlativo anual.
