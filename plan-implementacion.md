# Plan de Implementación — TOUR UP CMS

> **Fuente de verdad del orden de ejecución.** Define cómo y en qué secuencia se construye.
> Jerarquía: agent2.md > agent.md > este fichero > project-map.md.

---

## Estado actual resumido

| Área | % | Cobertura |
|---|---|---|
| FASE 1-2 (Instalación + Login) | 98% | ✅ Completo (falta DatabaseSeeder) |
| FASE 3-5 (Dashboard layout, home, programas) | 67% | ⚠️ Parcial |
| FASE 6-12 (Trip tabs) | 79% | ⚠️ Parcial (Info 75%, Itinerario 85%, Transporte 85%, Alojamiento 95%, Tarifas 95%, Viajeros 60%, Docs 60%) |
| FASE 13-14 (Blog, FAQ) | 83% | ⚠️ Parcial |
| FASE 15-18 (Temas, Imágenes, Calendario, Config) | 100% | ✅ Las 4 fases completas |
| FASE 19-22 (Páginas públicas) | 52% | ⚠️ Parcial (Home 90%, Ficha 30%, Reservas 5%, Blog/FAQ 85%) |
| FASE 23 (CRM) | 50% | ⚠️ Parcial |
| **GLOBAL** | **55%** | |

---

## Fase 0 — Correcciones pendientes sobre código existente

> **Estado: ✅ COMPLETADA**

| Item | Antes | Después |
|---|---|---|
| DatabaseSeeder | Stub vacío | Seed completo con agencia, admin, 2 viajes, pricing groups + installments, itinerarios, viajeros, blog, FAQ, servicios, settings |
| DashboardController `amount_paid` | `sum('amount_paid')` sin fallback | `sum('amount_paid') ?: 0` (columna sí existe pero datos no significativos sin reservas) |
| Sidebar Temas/Configuración | `href="#"` | `title="Próximamente disponible"` — rutas reales se crearán en FASE 1 y FASE 2 |
| FAQ drag & drop | Solo iconos grip, sin JS | HTML5 Drag & Drop API con endpoint `reorder` funcional |
| Inscribir link | `href` → 404 | Botón placeholder con mensaje "Próximamente disponible" |

---

## FASE 1 — Temas Visuales (agent.md FASE 15) + 3 temas restantes

> **Estado: ✅ COMPLETADA**

### 1.1 — ThemeController

**Archivo:** `app/Http/Controllers/Admin/ThemeController.php`

**Rutas implementadas:**
```
GET  /panel-agencia/temas                    → index     → admin.themes.index
POST /panel-agencia/temas/activar            → activate  → admin.themes.activate
GET  /panel-agencia/temas/preview/{theme}    → preview   → admin.themes.preview
```

### 1.2 — Vista de gestión de temas

**Archivo:** `resources/views/admin/themes/index.blade.php`

**Funcionalidad implementada:**
- 4 tarjetas (Earth, Ocean, Peak, Sunset) con gradiente + icono representativo
- Badge "Activo" en el tema seleccionado
- Botón "Activar" (solo si no es el activo)
- Botón "Vista previa" → nueva pestaña con ?preview_theme=
- Bloque "¿Quieres un diseño personalizado?" con enlace a contacto

### 1.3 — Tema Ocean (`ethos_ocean`)

**Archivos creados (8 vistas + CSS):**
- `public/css/themes/ethos_ocean/style.css` — paleta azul profundo + teal
- 8 vistas blade con colores azules/cyan/teal (basadas en estructura Earth)

**Paleta:** Azul profundo #0A4A7A, Aguamarina #2EC4B6, Arena #F5E6D3, Blanco #F0F7FA
**Tipografía:** Playfair Display + Inter
**Layout:** Tarjetas con borde azul suave, botones primarios azul profundo

### 1.4 — Tema Peak (`ethos_peak`)

**Archivos creados (8 vistas + CSS):**
- `public/css/themes/ethos_peak/style.css` — paleta slate/rojo
- 8 vistas blade con colores stone/red/gray

**Paleta:** Gris pizarra #2D3748, Rojo atardecer #E53E3E, Crema #FFF8F0, Blanco #FFFFFF
**Tipografía:** Merriweather + Inter
**Layout:** Bordes marcados (2px), sombras pronunciadas, tipografía bold, bordes rectos (sin radius)

### 1.5 — Tema Sunset (`ethos_sunset`)

**Archivos creados (8 vistas + CSS):**
- `public/css/themes/ethos_sunset/style.css` — paleta naranja/púrpura degradada
- 8 vistas blade con colores naranja/rose/purple

**Paleta:** Naranja quemado #C05621, Púrpura #6B46C1, Dorado #D69E2E, Crema #FFF5F0
**Tipografía:** Lora + Inter
**Layout:** Gradientes en hero y footer, inputs pill, sombras hover pronunciadas

### 1.6 — Installer step4 actualizado

**Archivo:** `resources/views/installation/step4.blade.php`
- Botón "Vista Previa" que abre el sitio con `?preview_theme=` del tema seleccionado

### 1.7 — Preview system

**Archivos modificados:** Todos los controladores públicos + `layouts/public.blade.php`
- `request('preview_theme')` → `session('preview_theme')` → `agency.active_theme`
- Google Fonts se cargan dinámicamente según el tema activo

---

## FASE 2 — Configuración General (agent.md FASE 18)

**Dependencias:** Ninguna directa
**Esfuerzo estimado:** Medio-Alto

### 2.1 — SettingsController

**Archivo:** `app/Http/Controllers/Admin/SettingsController.php`

**Rutas:**
```
GET   /panel-agencia/configuracion              → index          → admin.settings.index
PUT   /panel-agencia/configuracion              → update         → admin.settings.update
GET   /panel-agencia/configuracion/frases       → phrases        → admin.settings.phrases
PUT   /panel-agencia/configuracion/frases       → phrasesUpdate
GET   /panel-agencia/configuracion/redes        → social         → admin.settings.social
PUT   /panel-agencia/configuracion/redes        → socialUpdate
GET   /panel-agencia/configuracion/email        → email          → admin.settings.email
PUT   /panel-agencia/configuracion/email        → emailUpdate
GET   /panel-agencia/configuracion/ayuda        → help           → admin.settings.help
```

### 2.2 — Vistas de configuración

**Archivos:**
- `resources/views/admin/settings/index.blade.php` — dashboard con pestañas
- `resources/views/admin/settings/phrases.blade.php` — lista editable de frases
- `resources/views/admin/settings/social.blade.php` — formulario RRSS
- `resources/views/admin/settings/email.blade.php` — SMTP + mini-tutorial Gmail
- `resources/views/admin/settings/help.blade.php` — tutorial TOUR UP

**Frases públicas predefinidas** (claves en settings):
`hero_title`, `hero_subtitle`, `about_intro`, `services_intro`, `footer_copyright`, `cta_text`, `cta_button`, `trust_badge_1`, `trust_badge_2`, `trust_badge_3`

**Redes sociales:** Facebook, Instagram, Twitter/X, YouTube, LinkedIn, TikTok, WhatsApp — URL completa cada uno

**Email:**
- Host, puerto, username, password, encryption, from_address, from_name
- Botón "Probar conexión" (envía correo de prueba)
- Mini-tutorial App Password Gmail (pasos numerados)

### 2.3 — Perfil mejorado (avatar)

**Archivo:** `resources/views/admin/profile/index.blade.php`
- Añadir subida de avatar con preview
- Campos: nombre, email, teléfono (editables)
- Cambio de contraseña con validación de actual

### 2.4 — Buscador global funcional

**Archivos:**
- `app/Http/Controllers/Admin/SearchController.php`
- `resources/views/admin/search/index.blade.php`

**Ruta:** `GET /panel-agencia/buscar → SearchController@index → admin.search`

**Funcionalidad:**
- Buscar en viajes (nombre, referencia), viajeros (nombre, DNI), blog (título), FAQ (pregunta, respuesta), programas (nombre)
- Resultados agrupados por sección con enlaces
- Input en topbar debe enviar a esta ruta con `?q=termino`

---

## FASE 3 — Gestión de Imágenes (agent.md FASE 16)

**Dependencias:** Ninguna
**Esfuerzo:** Bajo-Medio

### 3.1 — Migración + Modelo

**Migración:** `create_media_images_table`
- `id`, `key` (string único, ej: hero_bg, about_team), `file_path`, `alt_text`, `section`, `created_at`, `updated_at`

**Modelo:** `MediaImage`

### 3.2 — ImageController

**Archivo:** `app/Http/Controllers/Admin/ImageController.php`

**Rutas:**
```
GET    /panel-agencia/imagenes             → index     → admin.images.index
POST   /panel-agencia/imagenes             → upload    → admin.images.upload
DELETE /panel-agencia/imagenes/{image}     → destroy   → admin.images.destroy
```

### 3.3 — Helper de renderizado

**Archivo:** `app/helpers.php`
- Función `theme_image($key, $default)` que busca en MediaImage por key; si existe usa la subida, si no usa el path por defecto del tema activo
- Registrar en `composer.json` autoload files

### 3.4 — Vista de gestión

**Archivo:** `resources/views/admin/images/index.blade.php`
- Imágenes agrupadas por sección (hero, about, blog)
- Subida con selector de sección + key + archivo + alt text
- Thumbnails de previsualización
- Botón eliminar con confirmación

---

## FASE 4 — Calendario y Disponibilidad (agent.md FASE 17)

> **Estado: ✅ COMPLETADA**

**Dependencias:** FASE 2 (settings para toggles)
**Esfuerzo:** Medio

### 4.1 — CalendarController

**Archivo:** `app/Http/Controllers/Admin/CalendarController.php`

**Rutas:**
```
GET /panel-agencia/calendario       → index   → admin.calendar.index
GET /panel-agencia/calendario/data  → data    → admin.calendar.data (JSON)
```

### 4.2 — Calendario visual

**Archivo:** `resources/views/admin/calendar/index.blade.php`
- Grid mensual con todos los viajes como marcadores
- Colores por estado del viaje
- Tooltip al hover (nombre, plazas, estado)
- Click → redirige a configuración del viaje
- Detección de solapamientos resaltados en rojo
- Navegación mes anterior/siguiente + selector de año

### 4.3 — Toggles de secciones públicas

**Migración:** Añadir claves a `settings`:
`blog_enabled`, `faq_enabled`, `booking_enabled`, `services_enabled` (todas default `true`)

**Modificar controladores públicos:**
- BlogController@index: si `blog_enabled` == false → 404 o empty state
- FaqController@index: igual
- ServiceController@index: igual
- TripDetailController: ocultar CTA reservar si `booking_enabled` == false

---

## FASE 5 — Mejora Landing Pública (agent.md FASE 20)

**Dependencias:** FASE 1 (temas), FASE 2 (frases), FASE 3 (imágenes)
**Esfuerzo:** Alto

### 5.1 — TripDetailController mejorado

**Archivo:** `app/Http/Controllers/Public/TripDetailController.php`
- Pasar datos adicionales a la vista:
  - Settings (frases públicas, RRSS)
  - Número de plazas disponibles real
  - Itinerario SOLO si está publicado (requiere campo `is_published` en ItineraryDay)
  - Pricing groups con instalment schedule

### 5.2 — trip-detail.blade.php rediseñado

**Estructura completa:**

1. **Sticky nav interna** (solo visible en ficha de viaje):
   - Logo pequeño, accesos rápidos (Itinerario, Pagos, Alojamiento — anclas #)
   - CTA "Inscribirse" siempre visible

2. **Hero mejorado:**
   - Imagen destacada + overlay gradiente
   - Título: `{$trip->name} {$trip->start_date->format('Y')}`
   - Subtítulo: público objetivo (desde categoría o campo)
   - CTA "Inscribirse" principal
   - Barra de confianza: "Gestión 100% online | Seguridad total | Pago fraccionado"

3. **Columna principal (2/3):**
   - Resumen ejecutivo: destinos + fecha/duración en formato destacado
   - Bloques de valor "Por qué viajar con nosotros": extraídos de frases públicas o sección dedicada
   - Tabla "Fechas a recordar" con plan de pagos (desde pricing groups)
   - Imagen destacada del destino
   - Itinerario en **acordeón día a día** (expandir/colapsar cada día, mostrar actividades internamente)

4. **Columna lateral (1/3, sticky):**
   - Módulo inscripción rápida: precio desde, plazas disponibles, CTA, método de pago
   - Lista alojamientos (desde accommodations del viaje)
   - Beneficios incluidos (desde settings o tabla propia)
   - Enlaces útiles: "Te interesa" (clima, FAQ viaje, consejos seguro)
   - Aviso lista de espera (si plazas = 0)

5. **CTA redundantes:** hero, sidebar, botón flotante "Inscribirse" en sticky nav

**Nota:** Esta vista debe ser adaptada en los 4 temas (FASE 1) con su propio diseño.

---

## FASE 6 — Sistema de Reservas (agent.md FASE 21)

**Dependencias:** FASE 2 (email), FASE 5 (CTA → flujo), FASE 4 (toggles)
**Esfuerzo:** MUY Alto (es la funcionalidad más compleja)

### 6.1 — BookingService mejorado

**Archivo:** `app/Services/BookingService.php`

**Métodos a añadir:**
- `createBooking(array $data): TripBooking` — acepta array completo del wizard
- `checkAvailability(Trip $trip, int $spots, ?string $date): array` — plazas disponibles, waitlist status
- `addToWaitlist(Trip $trip, array $travelerData): TripBooking`
- `autoAssignSeat(Trip $trip, ?Bus $bus): ?int` — siguiente asiento libre
- `generatePendingDocuments(TripBooking $booking): void` — crear TravelerDocuments
- `sendConfirmationEmails(TripBooking $booking): void` — email al viajero + notificación agencia
- `snapshotPricingAtBooking(TripBooking $booking, PricingGroup $group): void` — congelar plan de pagos

### 6.2 — Mails

**Archivos:**
- `app/Mail/BookingConfirmation.php`
- `app/Mail/NewBookingNotification.php`
- `resources/views/emails/booking-confirmation.blade.php`
- `resources/views/emails/new-booking-notification.blade.php`

### 6.3 — BookingController

**Archivo:** `app/Http/Controllers/Public/BookingController.php`

**Rutas (14):**
```
GET    /viaje/{slug}/reservar           → step1     → public.booking.step1
POST   /viaje/{slug}/reservar/fecha     → postStep1 → public.booking.postStep1
GET    /viaje/{slug}/reservar/plazas    → step2     → public.booking.step2
POST   /viaje/{slug}/reservar/plazas    → postStep2 → public.booking.postStep2
GET    /viaje/{slug}/reservar/datos     → step3     → public.booking.step3
POST   /viaje/{slug}/reservar/datos     → postStep3 → public.booking.postStep3
GET    /viaje/{slug}/reservar/tarifa    → step4     → public.booking.step4
POST   /viaje/{slug}/reservar/tarifa    → postStep4 → public.booking.postStep4
GET    /viaje/{slug}/reservar/extras    → step5     → public.booking.step5
POST   /viaje/{slug}/reservar/extras    → postStep5 → public.booking.postStep5
GET    /viaje/{slug}/reservar/resumen   → step6     → public.booking.step6
POST   /viaje/{slug}/reservar/confirmar → confirm   → public.booking.confirm
GET    /viaje/{slug}/reservar/confirmado→ step8     → public.booking.step8
```

**Gestión de estado:** Datos de reserva en sesión (no BD hasta confirmación)

### 6.4 — Vistas del wizard (× 4 temas)

**Archivos por tema** (en `resources/views/themes/[theme]/public/booking/`):

1. `step1-date.blade.php` — selector de fecha o confirmación
2. `step2-spots.blade.php` — selector número plazas
3. `step3-traveler-form.blade.php` — formularios viajeros + tutor si menor
4. `step4-pricing.blade.php` — selector grupo tarifa + desglose
5. `step5-extras.blade.php` — preferencias habitación + menú
6. `step6-summary.blade.php` — resumen + checkbox privacidad
7. `step8-confirmation.blade.php` — confirmación final con referencia

**Nota:** Paso 7 (pago) se maneja dentro del controller con opción manual (transferencia) por ahora.

**Estructura común:**
- Progress bar 8 pasos (paso actual resaltado, completados en verde)
- Botón "Atrás" en cada paso
- Validación JS en DOM (sin alerts)
- Feedback inline en el formulario

### 6.5 — Integraciones post-reserva

Asegurar que al confirmar reserva:
- `trip.occupied_spots` se incrementa
- Traveler se crea (o actualiza si DNI existe)
- TripBooking con status según disponibilidad
- Asiento libre asignado si hay buses
- Preferencias habitación guardadas para asignación manual
- TravelerDocuments generados en "Pendiente"
- Email confirmación enviado
- Email notificación a agencia enviado
- Customer vinculado via TravelerObserver

---

## FASE 7 — Completar CRM (agent.md FASE 23)

**Dependencias:** FASE 6 (reservas reales para datos CRM)
**Esfuerzo:** Medio-Alto

### 7.1 — Kanban Drag & Drop funcional

**Archivo:** `resources/views/panel/crm/kanban.blade.php` + nuevo JS

- Implementar HTML5 Drag & Drop API (dragstart, dragover, drop)
- Al soltar tarjeta en columna: AJAX POST a `updateStageAjax`
- Feedback visual durante drag (opacidad, placeholder)
- Animación suave al reordenar

### 7.2 — Cambios de etapa automáticos

**Archivos:**
- `app/Observers/TravelerObserver.php` — mejorar para mover a "Confirmado"
- `app/Console/Kernel.php` — añadir scheduler diario

**Eventos:**
1. Viajero creado desde reserva → Cliente → "Confirmado/Cliente"
2. Fecha inicio viaje alcanzada (scheduler) → "En viaje"
3. Fecha fin viaje alcanzada (scheduler) → "Postventa/Lealtad"

### 7.3 — NotificationEngine funcional

**Archivo:** `app/Services/NotificationEngine.php`

- Implementar `sendEmail()` usando Mailables de Laravel
- Implementar `sendWhatsApp()` como stub con log (integración real futura)
- Scheduler que ejecuta `NotificationEngine::process()` cada hora
- Reglas: nueva reserva, pago próximo a vencer (3d antes), documentación faltante (7d antes), recordatorio pre-viaje (1d antes), encuesta post-viaje (3d después), lead sin respuesta (7d sin interacción)

### 7.4 — Métricas reales

**Archivo:** `app/Http/Controllers/Admin/Crm/CustomerController.php` (método metrics)

- Tasa conversión por etapa (viajeros que pasan de Lead a Confirmado)
- Tiempo medio primera respuesta (horas desde creación hasta 1ª interacción saliente)
- Tasa recuperación reservas abandonadas
- Tasa clientes recurrentes (más de 1 viaje)

---

## FASE 8 — Pendientes y mejoras del dashboard

**Dependencias:** Ninguna directa, puede ir en paralelo con FASE 5-6
**Esfuerzo:** Medio

### 8.1 — Sidebar con grupos desplegables (agent.md FASE 3)

**Archivo:** `resources/views/layouts/admin.blade.php`

**Agrupación:**
- **Programas** ▾: Programas, Categorías de viaje
- **Viajes** ▾: Dashboard, Todos los viajes, Calendario de salidas
- **Pasajeros** ▾: Todos los pasajeros, Viajeros por viaje, Documentación
- **Contenido** ▾: Blog, Categorías Blog, FAQ, Servicios, Mensajes
- **Apariencia** ▾: Temas, Imágenes, Frases públicas
- **CRM** (enlace directo)
- **Sistema** ▾: Configuración, Perfil, Ayuda

**JS:** Guardar estado expandido/colapsado en localStorage
**Botón "Ver sitio web":** enlace a `url('/')` con target _blank

### 8.2 — Dashboard home mejorado (agent.md FASE 4)

**Archivo:** `app/Http/Controllers/Admin/DashboardController.php`

- Estadísticas reales (corregir `sum('amount_paid')`)
- Recaudación actual/objetivo con barra de progreso por viaje
- Filtros avanzados (categoría, rango fechas, rango ocupación)
- Exportación CSV de viajes filtrados
- Acceso directo "Crear nuevo itinerario"

### 8.3 — Catálogo programas mejorado (agent.md FASE 5)

- Estadísticas de categorías (totales, popular, activas/archivadas)
- Filtros por estado en tabla de categorías
- Vista "biblioteca" con tarjetas mejoradas

### 8.4 — Pestaña Viajeros completa (agent.md FASE 11)

- Buscador por nombre/DNI (JS en frontend)
- Filtro por estado de pago y curso/grupo
- Paginación
- Botón "Añadir viajero" y "Exportar lista"
- Ficha individual con historial de pagos y documentación

### 8.5 — Pestaña Documentación (agent.md FASE 12)

- Tipos específicos: autorización padres, seguro, ficha médica, DNI/pasaporte
- Sincronizar estado agregado con TripBooking.document_status
- Botón "Rechazar" documento con motivo

### 8.6 — FAQ drag & drop funcional (agent.md FASE 14)

- Implementar SortableJS o HTML5 Drag & Drop en `admin/faqs/index.blade.php`
- Llamar a endpoint `reorder` al soltar

### 8.7 — Itinerario estados borrador/publicado (agent.md FASE 7)

- Añadir campo `is_published` a `itinerary_days` (migración)
- Botones "Guardar borrador" / "Publicar itinerario"
- Solo días publicados se muestran en la ficha pública

### 8.8 — Componentes reutilizables

Crear partials en `resources/views/admin/partials/`:
- `_stat-card.blade.php` — tarjeta de estadística (icono + valor + label + color)
- `_badge.blade.php` — badge de estado (type + text)
- `_table.blade.php` — tabla con filtros + paginación + empty state
- `_timeline.blade.php` — timeline con iconos por tipo
- `_modal.blade.php` — modal reutilizable

---

## FASE 9 — Pulido final, SEO y testing

**Dependencias:** Todo lo anterior
**Esfuerzo:** Medio

### 9.1 — SEO

- Meta tags dinámicos (title, description, og, twitter) en todas las páginas públicas
- Schema.org JSON-LD (Product para viajes, Article para blog, FAQPage para FAQ)
- Sitemap.xml generado dinámicamente
- robots.txt
- Canonical tags
- Alt text en todas las imágenes

### 9.2 — Responsive

- Revisar todas las vistas del dashboard en móvil
- Tablas con scroll horizontal
- Sidebar colapsable (ya existe, verificar)
- Empty states en todas las secciones

### 9.3 — Seguridad

- Verificar `@csrf` en todos los forms
- Verificar middleware `admin` en todas las rutas del panel
- NO raw SQL queries
- NO `{!! !!}` sin escapar contenido confiable

### 9.4 — Performance

- Eager loading donde haya N+1
- Paginación en listados (viajeros, blog posts, contactos)
- Indexar columnas de búsqueda frecuente (dni, email, slug)

---

## Resumen de orden de ejecución

| Orden | Fase | Contenido | Depende de |
|---|---|---|---|
| 1 | **FASE 0** | Correcciones código existente (DB seeder, bug amount_paid, href rotos, FAQ JS) | — |
| 2 | **FASE 1** | ✅ Temas visuales (ThemeController + 4 temas completos + CSS) | — |
| 3 | **FASE 2** | Configuración (SettingsController, frases, RRSS, email, perfil avatar, buscador, ayuda) | — |
| 4 | **FASE 3** | Imágenes (MediaImage, ImageController, helper theme_image) | — |
| 5 | **FASE 4** | Calendario (CalendarController, toggles secciones públicas) | FASE 2 (toggles en settings) |
| 6 | **FASE 5** | Landing pública mejorada (sticky nav, hero, acordeón, sidebar) | FASE 1 (temas), FASE 2 (frases), FASE 3 (imágenes) |
| 7 | **FASE 6** | Sistema reservas (BookingService, BookingController, 14 rutas, 8 pasos, emails) | FASE 2 (email), FASE 5 (CTA) |
| 8 | **FASE 7** | CRM completar (drag & drop kanban, notificaciones reales, métricas) | FASE 6 (reservas reales) |
| 9 | **FASE 8** | Dashboard pendientes (sidebar colapsable, home stats, tabs viajeros/docs, reorder FAQ, itinerario draft, componentes) | Puede ir en paralelo con FASE 5-7 |
| 10 | **FASE 9** | Pulido final (SEO, responsive, seguridad, performance) | Todo lo anterior |
