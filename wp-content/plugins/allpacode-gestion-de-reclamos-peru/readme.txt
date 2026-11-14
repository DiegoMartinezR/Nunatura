=== AllpaCode Gestión de Reclamos Perú ===
Contributors: allpacode
Tags: reclamos, quejas, peru, consumidor, libro de reclamaciones
Requires at least: 6.0
Tested up to: 7.0
Requires PHP: 8.0
Stable tag: 1.0.12
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Gestión digital de reclamos y quejas para empresas peruanas, con registros requeridos para el cumplimiento ante INDECOPI.

== Description ==

AllpaCode Gestión de Reclamos Perú permite implementar en WordPress un flujo digital para recibir, registrar y administrar reclamos o quejas de consumidores en Perú.

El plugin incluye formulario público, gestión administrativa de casos, estados de atención, constancias descargables, correos automáticos, exportaciones, registros administrativos y compatibilidad con Elementor, Gutenberg y widgets clásicos.

Funciones principales:

* Formulario público mediante shortcode, bloque Gutenberg, widget clásico y widget Elementor.
* Campos para datos del consumidor, datos del reclamo o queja, datos del negocio y metadatos.
* Correlativo anual configurable para cada registro.
* Estados de atención: Pendiente, En revisión, Respondido y Cerrado.
* Constancia PDF descargable.
* Plantillas HTML para confirmación al consumidor, notificación interna y respuesta administrativa.
* Exportaciones en CSV, archivo compatible con Excel y reporte HTML imprimible.
* Historial administrativo, notas internas y trazabilidad básica.
* Validación de campos, verificación con nonce, protección honeypot y límite de envíos.
* Soporte opcional para reCAPTCHA v3 y Cloudflare Turnstile.
* Integración opcional con AllpaCode Ubigeo Perú para selectores de departamento, provincia y distrito.
* Preparado para Elementor, Gutenberg, WooCommerce, plugins SMTP y WordPress Multisite.

Nota legal: este plugin ofrece una herramienta técnica para registrar y gestionar reclamos o quejas en Perú. No está afiliado, avalado ni conectado oficialmente con INDECOPI. Cada empresa debe validar sus textos legales, políticas de privacidad y procedimientos con su asesor legal.

El plugin utiliza AllpaCode Ubigeo Perú como dependencia de WordPress.org para ofrecer selectores estructurados de departamento, provincia y distrito.

Shortcode:

`[allpacode_gestion_reclamos]`

Shortcode con opciones:

`[allpacode_gestion_reclamos theme="business" color="#1f2937" show_title="true" compact="false" darkmode="false"]`

== Installation ==

1. Instala y activa AllpaCode Ubigeo Perú si deseas usar selectores estructurados de departamento, provincia y distrito.
2. Sube la carpeta `allpacode-gestion-de-reclamos-peru` a `/wp-content/plugins/`.
3. Activa el plugin desde la pantalla de plugins de WordPress.
4. Ingresa a Gestión de Reclamos > Configuración y completa los datos de la empresa.
5. Inserta el formulario usando `[allpacode_gestion_reclamos]`, el bloque Gutenberg, el widget clásico o el widget Elementor.
6. Configura correos, apariencia, exportaciones y protección antispam según las necesidades del sitio.

== Frequently Asked Questions ==

= ¿Cuál es el shortcode? =

Usa:

`[allpacode_gestion_reclamos]`

= ¿Funciona con Elementor? =

Sí. El plugin incluye un widget nativo para Elementor y también permite insertar el formulario mediante shortcode dentro de cualquier contenedor.

= ¿Funciona con Gutenberg? =

Sí. El plugin incluye un bloque dinámico para Gutenberg.

= ¿Puedo exportar los reclamos? =

Sí. Puedes exportar casos como CSV, archivos compatibles con Excel y reportes HTML imprimibles. Las exportaciones incluyen datos del consumidor, datos del caso, datos del negocio, respuestas, notas internas y fechas.

= ¿Incluye protección antispam? =

Sí. Incluye verificación con nonce, campo honeypot, límite de envíos y soporte opcional para reCAPTCHA v3 o Cloudflare Turnstile.

= ¿El plugin se conecta con servicios externos? =

No por defecto. Los servicios antispam opcionales y el webhook solo se ejecutan cuando el administrador del sitio los activa y configura.

= ¿Este plugin está afiliado a INDECOPI? =

No. El plugin ayuda a gestionar registros digitales para flujos de cumplimiento en Perú, pero no está afiliado, avalado ni conectado oficialmente con INDECOPI.

= ¿Este plugin reemplaza una asesoría legal? =

No. El plugin proporciona un flujo técnico para registrar y administrar reclamos o quejas. Cada empresa es responsable de validar sus textos legales, políticas y procedimientos.

== External Services ==

Este plugin no se conecta con servicios externos por defecto. Puede conectarse opcionalmente con los siguientes servicios solo cuando el administrador del sitio los activa y configura.

Cloudflare Turnstile: se utiliza para validación antispam. Cuando Turnstile está activo, el plugin carga el script de Turnstile desde Cloudflare en las páginas que contienen el formulario público. Cloudflare procesa los datos necesarios para generar el token de validación, y el plugin envía ese token desde el servidor al endpoint de verificación de Cloudflare cuando se envía el formulario.

Proveedor del servicio: Cloudflare, Inc.  
Términos: https://www.cloudflare.com/website-terms/  
Política de privacidad: https://www.cloudflare.com/privacypolicy/

Google reCAPTCHA v3: se utiliza para puntuación antispam. Cuando reCAPTCHA está activo, el plugin carga el script de reCAPTCHA de Google en las páginas que contienen el formulario público. Google procesa datos del navegador y de interacción necesarios para generar el token de reCAPTCHA, y el plugin envía ese token desde el servidor al endpoint de verificación de Google cuando se envía el formulario.

Proveedor del servicio: Google LLC  
Términos: https://policies.google.com/terms  
Política de privacidad: https://policies.google.com/privacy

URL de webhook: endpoint opcional configurado por el administrador. Cuando se configura, el plugin envía los datos del caso creado a esa URL después de un envío correcto. El servicio de destino, sus términos y su política de privacidad dependen del administrador del sitio que configura el webhook.

== Changelog ==

= 1.0.12 =

* Agrega una zona peligrosa en Configuración para borrar todos los reclamos.
* La acción exige confirmación explícita, nonce administrativo y capacidad de gestión.
* Borra reclamos, registros internos y constancias PDF generadas por el plugin.

= 1.0.11 =

* Evita duplicar las etiquetas de departamento, provincia y distrito al marcar campos obligatorios.

= 1.0.10 =

* Corrige la codificación del título principal en la constancia PDF.
* Muestra el asterisco de campo obligatorio al final del texto de la etiqueta y en la misma línea.

= 1.0.9 =

* Ajusta el límite de envíos para no penalizar intentos con errores de validación o captcha.
* Aumenta el margen de envíos válidos permitidos durante la ventana anti-spam.
* Marca campos obligatorios con asterisco rojo en el formulario público.
* Mejora la validación visible para correo, longitudes y rangos antes de enviar.

= 1.0.8 =

* Corrige la codificación de fuentes en la constancia PDF para mostrar correctamente tildes, eñes y caracteres en español.

= 1.0.7 =

* Mejora la respuesta AJAX cuando vence el nonce para evitar respuestas `-1` sin mensaje.
* Agrega renovación de nonce antes del envío para mejorar compatibilidad con caché.
* Muestra errores de validación debajo de los campos correspondientes.
* Marca visualmente los campos obligatorios en el formulario público.
* Mejora los mensajes cuando Turnstile o reCAPTCHA requieren atención antes de enviar.

= 1.0.6 =

* Se actualizó la ficha pública del plugin en español.
* Se agregaron los assets visuales para WordPress.org.

= 1.0.5 =

* Se validó el atributo `color` del shortcode antes de renderizar variables CSS en línea.
* Se actualizó el manejo de IP para el límite de envíos usando solo la dirección remota validada del servidor.

= 1.0.4 =

* Se actualizó el nombre y slug del plugin para WordPress.org.
* Se actualizó la documentación sobre servicios externos y cumplimiento ante INDECOPI.
* Se retiró la opción de CSS arbitrario en la pantalla de apariencia.
* Se restringió el endpoint REST de consulta de casos a administradores autorizados.
* Se retiró el uso de conversión de caracteres obsoleta en la generación de PDF.
* Se retiró el archivo SQL del paquete público.
* Se saneó la salida del bloque Gutenberg.

= 1.0.3 =

* Se actualizaron identificadores públicos con la marca AllpaCode.
* Se actualizó el namespace REST público a `/wp-json/allpacode-gr/v1/`.
* Se actualizaron hooks y acciones AJAX con el prefijo `allpacode_gr`.
* Se actualizó la integración con AllpaCode Ubigeo Perú 1.0.1.

= 1.0.2 =

* Se corrigieron caracteres acentuados en las constancias PDF.
* Se agregó dirección y datos de ubigeo al detalle administrativo del caso.
* Se forzó la actualización de tablas en instalaciones activas.

= 1.0.0 =

* Primera versión pública.
* Formulario público AJAX con validaciones.
* Panel administrativo, gestión de casos e historial.
* Seguimiento por estados: Pendiente, En revisión, Respondido y Cerrado.
* Constancia PDF descargable.
* Plantillas de correo HTML.
* Exportaciones CSV, Excel compatible y reporte HTML.
* Widget Elementor, bloque Gutenberg y widget clásico.
* Compatibilidad opcional con reCAPTCHA v3 y Cloudflare Turnstile.
* Registros administrativos y retención configurable.

== Upgrade Notice ==

= 1.0.12 =

Agrega una acción administrativa irreversible para borrar todos los reclamos desde Configuración.

= 1.0.11 =

Corrección visual para las etiquetas de ubicación del formulario público.

= 1.0.10 =

Corrección visual para campos obligatorios y constancias PDF en español.

= 1.0.9 =

Actualización recomendada para mejorar la experiencia del formulario y evitar bloqueos durante correcciones de campos.

= 1.0.8 =

Corrección recomendada para constancias PDF con textos en español.

= 1.0.7 =

Actualización recomendada para mejorar la experiencia de envío del formulario, especialmente en sitios con caché y protección antispam.

= 1.0.6 =

Actualización de ficha pública en español y assets visuales para WordPress.org.

= 1.0.5 =

Validación del color del shortcode y manejo reforzado de IP para límite de envíos.
