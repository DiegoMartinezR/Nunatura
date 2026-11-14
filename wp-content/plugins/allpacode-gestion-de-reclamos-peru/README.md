# AllpaCode Gestión de Reclamos Perú

Gestión digital de reclamos y quejas para Perú con formulario público, constancias, gestión administrativa, exportaciones y trazabilidad. Permite gestionar registros requeridos para cumplimiento ante INDECOPI, sin estar afiliado ni avalado por INDECOPI.

Author: AllpaCode  
Website: https://www.allpacode.com  
Email: info@allpacode.com  
Text domain: `allpacode-gestion-de-reclamos-peru`

## Funciones Principales

- Formulario público AJAX con shortcode `[allpacode_gestion_reclamos]`.
- Campos para consumidor, caso, negocio y metadatos.
- Correlativo anual configurable como `LR-2026-000001`.
- Estados: Pendiente, En revisión, Respondido y Cerrado.
- Panel administrativo para casos, configuración, correos, exportaciones, apariencia, registros y ayuda.
- Constancia descargable, correo de confirmación al consumidor y notificación interna.
- Exportaciones CSV, Excel compatible y reporte HTML imprimible.
- Honeypot, verificación con nonce, límite de envíos, validación, sanitización y escape.
- Soporte opcional para reCAPTCHA v3 o Cloudflare Turnstile.
- Webhook opcional.
- Periodo de retención legal configurable desde dos años.
- Integración con AllpaCode Ubigeo Perú para departamento, provincia y distrito.

## Installation

1. Instala y activa AllpaCode Ubigeo Perú.
2. Sube la carpeta `allpacode-gestion-de-reclamos-peru` a `/wp-content/plugins/`.
3. Activa el plugin desde la pantalla de plugins de WordPress.
4. Configura los datos del negocio en Gestión de Reclamos > Configuración.
5. Inserta el formulario con el shortcode, bloque Gutenberg, widget clásico o widget Elementor.

## Shortcode

```text
[allpacode_gestion_reclamos]
[allpacode_gestion_reclamos theme="business" color="#2563eb" show_title="true" compact="false" darkmode="false"]
```

Parámetros:

- `theme`: variante visual.
- `color`: color principal.
- `show_title`: `true` o `false`.
- `compact`: `true` o `false`.
- `darkmode`: `true` o `false`.

## Elementor

Busca el widget "Gestión de Reclamos". Incluye controles de título, modo compacto, modo oscuro, color, tipografía, espaciado y bordes.

## Gutenberg

Inserta el bloque "Gestión de Reclamos" desde la categoría Widgets. Renderiza el formulario público real en el frontend.

## REST API

- `GET /wp-json/allpacode-gr/v1/claims`: requiere la capacidad `manage_allpacode_gr_reclamos`.
- `GET /wp-json/allpacode-gr/v1/claims/{code}`: requiere la capacidad `manage_allpacode_gr_reclamos`.

## External Services

El plugin no se conecta con servicios externos por defecto.

- Cloudflare Turnstile: validación antispam opcional. Cuando se activa, el script de Turnstile se carga en páginas con el formulario y el token generado se verifica desde el servidor con Cloudflare.
- Google reCAPTCHA v3: puntuación antispam opcional. Cuando se activa, el script de reCAPTCHA se carga en páginas con el formulario y el token generado se verifica desde el servidor con Google.
- URL de webhook: endpoint opcional configurado por el administrador que recibe los datos del caso después de un envío correcto.

## Hooks

- `allpacode_gr_claim_created`: se ejecuta después de crear un caso.
- `allpacode_gr_sanitized_claim`: filtra los datos saneados antes de guardar.
- `allpacode_gr_claim_code`: filtra el correlativo anual generado.

## Seguridad

El plugin usa nonces, consultas preparadas, sanitización, escape, `WP_List_Table`, validación de archivos, restricción MIME, límite por IP y protección honeypot.

## Nota Legal

El plugin proporciona un flujo técnico para gestionar reclamos y quejas digitales en Perú. No está afiliado, avalado ni conectado oficialmente con INDECOPI. Cada empresa es responsable de validar sus textos legales, políticas de datos personales y procedimientos con su asesor legal.
