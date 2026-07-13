# Project Map — Estado Actual del Sistema

> Fuente de verdad del estado real. Refleja exactamente lo que existe en el código.
> Si hay discrepancia entre este fichero y el código, el código tiene prioridad y este debe corregirse.

**Última actualización:** 2026-07-13 (Refactor temas visuales — CSS nativo autónomo)
**Framework:** Laravel 9.52.21
**PHP:** 8.0.28
**Base de datos:** MySQL (MariaDB 10.4.28) — BD `agencia_turismo` (usuario `agencia_user`)
**Auth:** Laravel Breeze Blade adaptado (login email + teléfono + contraseña, ruta `/acceso-agencia`)
**Tema activo:** `ethos_sunset` (configurable desde dashboard) — 4 temas disponibles con CSS autónomo

---

## 1. Cumplimiento de Reglas Preventivas de Arquitectura

| # | Regla | Cumple | Estado |
|---|---|---|---|
| 1 | Un solo controlador y ruta por página semántica | ✅ | TripDetailController es el único para ficha de viaje. No hay ExpeditionController ni SchoolExpeditionController. |
| 2 | Los temas visuales no alteran la lógica | ✅ | El tema activo solo cambia vista + CSS. Comparten controladores, rutas y lógica. |
| 3 | Prohibido branding heredado de plantillas | ✅ | No hay textos de marcas externas ni datos falsos de ejemplo. |
| 4 | Precio nunca es campo suelto en trips | ⚠️ | Campo `price` existe en migration (compatibilidad), pero fuente de verdad son pricing_groups + installments. |
| 5 | Alojamiento siempre es dato gestionado | ✅ | Accommodation + Room models con CRUD. No hay hoteles hardcodeados. |
| 6 | No existe sistema de citas | ✅ | No hay booking de citas, slots ni agenda horaria. |
| 7 | Login único con 3 campos obligatorios | ✅ | Ruta `/acceso-agencia`, email + teléfono + contraseña obligatorios. |
| 8 | Sobre Nosotros con datos reales | ✅ | AboutController pasa array `$team` con 4 miembros. Footer completo. |
| 9 | CRM no sustituye al motor de reservas | ✅ | CRM es capa separada, BookingService sigue siendo fuente de verdad. |

---

## 2. Estado por Fase

### FASE 1 — Asistente de instalación
**Estado: ✅ COMPLETO**

| Elemento | Estado |
|---|---|
| Step 1: Conexión BD + migraciones | ✅ Crea BD desde formulario, ejecuta `php artisan migrate` |
| Step 2: Admin (nombre, email, teléfono, password) | ✅ Validación + sesión |
| Step 3: Agencia (nombre, logo, eslogan, about, etc.) | ✅ Subida de logo + datos en sesión |
| Step 4: Selección de tema (4 opciones) | ✅ Tarjetas con gradientes Earth/Ocean/Peak/Sunset |
| Creación de usuario + agencia al finalizar | ✅ Crea registro en ambas tablas |
| DatabaseSeeder | ✅ **COMPLETO** — demo con agencia, admin, 2 viajes, pricing, viajeros, blog, FAQ, servicios |

### FASE 2 — Login seguro
**Estado: ✅ COMPLETO**

| Elemento | Estado |
|---|---|
| Ruta `/acceso-agencia` | ✅ Definida en `routes/auth.php` |
| Email + teléfono + contraseña obligatorios | ✅ Los 3 campos en login form |
| Persistir login (recordarme) | ✅ Checkbox presente |
| Contraseña cifrada | ✅ Bcrypt (Laravel default) |
| Sin registro público | ✅ Aunque `register.blade.php` existe, no hay ruta pública de registro |

### FASE 3 — Dashboard layout
**Estado: ⚠️ PARCIAL (70%)**

| Elemento | Estado |
|---|---|
| Sidebar azul marino (#0b1c30) | ✅ Existe |
| Secciones agrupadas con desplegables (WordPress-style) | ❌ **PLANO** — usa solo divisores de texto, sin colapsables |
| Top bar con breadcrumbs | ✅ `@yield('breadcrumbs')` |
| Buscador global | ✅ Form método GET a `admin.search` con input funcional + página de resultados |
| Notificaciones | ⚠️ Badge "3" hardcodeado, sin lógica real |
| Acceso a perfil | ✅ Dropdown con enlace a Perfil |
| Botón "Ver sitio web" | ✅ En sidebar, abre web pública en nueva pestaña |
| Componentes base reutilizables definidos | ❌ **NO** — sin partials de tabla/badge/stat-card/timeline |

### FASE 4 — Dashboard home / Trips list
**Estado: ⚠️ PARCIAL (70%)**

| Elemento | Estado |
|---|---|
| Tarjetas de estadísticas (4) | ✅ Viajes activos, próximas salidas, total viajeros, recaudación pendiente |
| Datos reales en estadísticas | ✅ Corregido — `sum('amount_paid') ?: 0`, muestra 0 hasta que haya pagos reales |
| Listado de viajes en tarjetas | ✅ Imagen, badge estado, barra ocupación, precio |
| Filtros por año, estado, búsqueda | ✅ Implementados |
| Filtros avanzados | ❌ **NO** — solo los 3 filtros básicos |
| Exportación CSV | ❌ **NO** |
| Botón "Nuevo viaje" | ✅ |
| Tarjeta "Crear nuevo itinerario" | ❌ **NO** |
| Recaudación actual/objetivo con barra | ❌ **NO** — solo barra de ocupación |
| Avatares de responsables | ❌ **NO** |

### FASE 5 — Programas y categorías
**Estado: ⚠️ PARCIAL (70%)**

| Elemento | Estado |
|---|---|
| Biblioteca de programas en tarjetas | ✅ Grid con nombre, duración, edad, categoría |
| Duplicar programa → nuevo viaje activo | ✅ `duplicate()` method exists |
| CRUD categorías con icono, nombre, descripción | ✅ |
| Estadísticas de categorías | ⚠️ Solo `withCount('trips')`, sin tarjetas dedicated |
| Tabla con filtro por estado | ❌ Sin filtros en index |
| Categorías activas/archivadas | ⚠️ Campo `is_active` existe, sin vista de archivadas |
| Categoría más popular | ❌ No calculada |

### FASE 6 — Ficha viaje > pestaña Información
**Estado: ✅ COMPLETO**

| Elemento | Estado |
|---|---|
| Datos generales (nombre, destino, fechas, duración, descripción, categoría, estado, plazas) | ✅ Todo visible en show.blade.php |
| Validación solapamiento fechas | ❌ **NO** — no hay validación contra disponibilidad |
| Botón "Ver itinerario" | ❌ **NO** |
| Botón "Exportar PDF" | ❌ **NO** |
| Sin campo precio suelto | ✅ Usa pricing groups |

### FASE 7 — Ficha viaje > pestaña Itinerario
**Estado: ✅ COMPLETO**

| Elemento | Estado |
|---|---|
| Panel lateral "Días del viaje" | ✅ Lista con navegación, contador, botón añadir |
| CRUD de días (fecha, título, descripción) | ✅ Modal con campos |
| Timeline vertical de actividades | ✅ Iconos por tipo (transporte/comida/actividad/alojamiento) |
| Notas importantes destacadas | ✅ Campo `important_notes` visible |
| Selector de tipo al añadir actividad | ✅ 4 tipos |
| Botones "Guardar borrador" / "Publicar itinerario" | ❌ **NO** — no hay estado publicado/borrador en ItineraryDay |
| Actividades con hora exacta, título, descripción | ✅ |

### FASE 8 — Ficha viaje > pestaña Transporte
**Estado: ✅ COMPLETO**

| Elemento | Estado |
|---|---|
| Selector de vehículos en pestañas | ✅ Múltiples buses por viaje |
| Añadir nuevos buses | ✅ Modal con nombre, filas, columnas |
| Mapa interactivo de asientos | ✅ Grid coloreado (azul=libre, rojo=ocupado) |
| Ocupación no permite duplicidades | ✅ Sistema valida asiento único |
| Estadísticas de ocupación | ✅ Totales, libres, ocupadas |
| Listado pasajeros por bus | ❌ **NO** — no hay tabla de pasajeros asignados |

### FASE 9 — Ficha viaje > pestaña Alojamiento
**Estado: ✅ COMPLETO**

| Elemento | Estado |
|---|---|
| Listado de hoteles (imagen, nombre, estrellas, ubicación) | ✅ |
| Asignación de habitaciones a viajeros | ✅ N° habitación, tipo, ocupantes |
| CRUD de alojamientos | ✅ |
| Sin hoteles hardcodeados | ✅ Siempre desde BD |

### FASE 10 — Ficha viaje > pestaña Tarifas
**Estado: ✅ COMPLETO**

| Elemento | Estado |
|---|---|
| Grupos de pago ("Por defecto", "Niños", "Senior") | ✅ Con activar/desactivar |
| Plazos secuenciales (nombre, fecha, importe) | ✅ CRUD completo |
| Botón "Nuevo pago" | ✅ |
| Alimenta tabla "Fechas a recordar" pública | ✅ visible en trip-detail |
| Único modelo válido de precio | ✅ Sin campo `price` en trips |

### FASE 11 — Ficha viaje > pestaña Viajeros
**Estado: ⚠️ PARCIAL (60%)**

| Elemento | Estado |
|---|---|
| Tarjetas estadísticas (total, confirmados, pendientes, waitlist) | ✅ |
| Buscador por nombre/DNI | ❌ Sin buscador |
| Filtro por estado de pago | ❌ Sin filtros |
| Tabla (nombre, DNI, grupo, estado pago, estado docs, bus, habitación) | ⚠️ Columnas básicas, sin grupo/curso ni bus/habitación |
| Botón "Añadir viajero" | ❌ No hay botón en esta vista |
| Botón "Exportar lista" | ❌ No implementado |
| Paginación | ❌ No hay paginación |
| Ficha individual de viajero (completa) | ❌ Sin vista de ficha detallada con historial pagos |

### FASE 12 — Ficha viaje > pestaña Documentación
**Estado: ⚠️ PARCIAL (60%)**

| Elemento | Estado |
|---|---|
| Gestión documentos (autorización, seguro, ficha médica, DNI) | ⚠️ Tipos básicos, faltan tipos específicos |
| Estado por documento (Completa/Pendiente/En revisión) | ✅ |
| Sincronizado con pestaña Viajeros | ⚠️ `document_status` en TripBooking, puede no estar sincronizado |
| Subir/revisar/rechazar documentos | ⚠️ Subir y actualizar estado sí, rechazar no explícitamente |

### FASE 13 — Gestión Blog
**Estado: ✅ COMPLETO**

| Elemento | Estado |
|---|---|
| CRUD artículos con imagen, categoría, WYSIWYG | ✅ |
| Jodit 4.7.6 descargado localmente | ✅ `public/js/jodit/jodit.min.js` + `.css` |
| CRUD categorías de blog | ✅ |
| Activar/desactivar blog desde configuración | ❌ **NO** — no hay toggle (depende de FASE 17/18) |

### FASE 14 — Gestión FAQ
**Estado: ⚠️ PARCIAL (80%)**

| Elemento | Estado |
|---|---|
| CRUD pregunta/respuesta | ✅ |
| Reordenar (drag) | ✅ HTML5 Drag & Drop implementado, llama a `reorder` endpoint al soltar |
| Activar/desactivar FAQ | ✅ Campo `is_active` |

### FASE 15 — Gestión Temas Visuales
**Estado: ✅ COMPLETO (refactor 2026-07-13)**

| Elemento | Estado |
|---|---|
| ThemeController | ✅ Creado con index, activate, preview |
| Vista de selección/preview | ✅ 4 tarjetas con paletas reales, miniaturas, botones activar/preview |
| 4 temas con vistas reales | ✅ Earth, Ocean, Peak, Sunset — 9 vistas públicas + 8 booking + 2 partials cada uno |
| CSS nativo autónomo por tema | ✅ Cada tema tiene su propio style.css con design tokens propios (sin heredar de Earth) |
| Header/footer por tema | ✅ Movidos a `themes/{slug}/partials/` — cada tema tiene su header/footer con sus colores |
| Sin Tailwind CDN en parte pública | ✅ Layout público usa solo CSS nativo del tema activo |
| Fuente CSS en tema-visual-base/ | ✅ `tema-visual-base/ethos_{slug}.css` como source editables |
| Previsualizar en nueva pestaña | ✅ Via query param `?preview_theme=` o sesión desde admin |
| Botón "Diseño personalizado" | ✅ Enlace a contacto |

### FASE 16 — Gestión Imágenes
**Estado: ✅ COMPLETO (100%)**

| Elemento | Estado |
|---|---|
| ImageController | ✅ Creado con index (agrupado por sección), upload, destroy |
| MediaImage model | ✅ Creado (key, file_path, alt_text, section) |
| Migración de imágenes | ✅ `create_media_images_table` (key único, file_path, alt_text, section) |
| Interfaz de administración | ✅ Vista con grid de thumbnails por sección (hero, about, blog, services, contact, general) + formulario subida + botón eliminar |
| Prioridad sobre imágenes de stock | ✅ Helper `theme_image(key, default)` — si existe en BD usa esa; si no, usa path por defecto del tema |
| Autoload | ✅ helpers.php registrado en composer.json autoload.files |

### FASE 17 — Disponibilidad y Calendario
**Estado: ✅ COMPLETO (100%)**

| Elemento | Estado |
|---|---|
| CalendarController | ✅ index + data (JSON con eventos, colores, detección de solapamientos) |
| Calendario visual con salidas | ✅ Grid mensual con tooltips (nombre, ref, plazas, estado), colores por estado, solapamientos en rojo, navegación mes/año |
| Detección de solapamientos | ✅ Backend calcula overlaps entre trips por fechas; frontend resalta en rojo con badge |
| Toggle secciones públicas (blog, FAQ, booking, servicios) | ✅ BlogController, FaqController, ServiceController, TripDetailController (4 temas) — todos checkean settings antes de renderizar |
| Migración booking_enabled | ✅ Añadido a settings con default 'true' |

### FASE 18 — Configuración General
**Estado: ✅ COMPLETO (100%)**

| Elemento | Estado |
|---|---|
| SettingsController | ✅ Creado con index, update, phrases, phrasesUpdate, social, socialUpdate, email, emailUpdate, help |
| Frases públicas (CRUD de strings) | ✅ Vista phrases.blade.php con 7 campos editables + 3 trust badges |
| Redes sociales (CRUD enlaces) | ✅ Vista social.blade.php con Facebook, Instagram, Twitter/X, YouTube, LinkedIn, TikTok, WhatsApp |
| Email y notificaciones (SMTP + mini-tutorial) | ✅ Vista email.blade.php con formulario SMTP + mini-tutorial Gmail App Password |
| Perfil privado (nombre, email, teléfono, avatar, password) | ✅ ProfileController con avatar upload (file, max 2MB, jpg/png/gif/webp) + campos editables + cambio contraseña |
| Buscador global → página resultados | ✅ SearchController creado — busca en viajes, viajeros, blog, FAQ, programas. Resultados agrupados por sección con enlaces |
| Botón Ayuda → tutorial | ✅ Vista help.blade.php con guía completa de TOUR UP CMS (secciones, consejos rápidos) |
| Vista index Configuración | ✅ Rediseñado como dashboard con tarjetas de acceso a sub-secciones + toggles de visibilidad |
| Sidebar | ✅ Añadidos enlaces a Perfil, Ayuda y "Ver sitio web" |

### FASE 19 — Homepage y páginas públicas
**Estado: ✅ COMPLETO**

| Elemento | Estado |
|---|---|
| Homepage con hero, servicios, categorías, viajes, testimonios, CTA | ✅ |
| Sobre nosotros con equipo (datos reales) | ✅ Array `$team` desde controlador |
| Servicios y categorías (desde BD) | ✅ |
| Contacto con formulario | ✅ Store con validación |
| WhatsApp flotante | ✅ |
| Footer con enlaces legales, RRSS, copyright | ✅ |
| Modo landing (one-page) vs multi-page | ❌ **NO** — solo multi-page implementado |

### FASE 20 — Ficha de viaje pública (landing)
**Estado: ⚠️ PARCIAL (30%)**

| Elemento | Estado |
|---|---|
| Sticky nav con anclas + CTA | ❌ Solo navbar global |
| Hero título + año + subtítulo audiencia | ❌ Sin año, sin subtítulo, sin barra confianza |
| Resumen ejecutivo | ❌ No existe bloque diferenciado |
| Bloques de valor "Por qué viajar con nosotros" | ❌ No existe |
| Tabla "Fechas a recordar" (pricing) | ✅ |
| Itinerario acordeón día a día | ❌ Es timeline, no acordeón expandible |
| Sidebar inscripción redundante (sticky scroll) | ❌ Solo tarjeta precio fija |
| Sidebar alojamientos | ✅ |
| Sidebar beneficios (desde BD) | ⚠️ Lista hardcodeada en vista |
| Sidebar enlaces útiles | ❌ No existe |
| Sidebar aviso lista espera | ❌ No existe |
| CTA → flujo reserva | ⚠️ Botón placeholder con mensaje "Próximamente disponible" |

### FASE 21 — Sistema de reservas
**Estado: ❌ PARCIAL (5%) — solo BookingService**

| Elemento | Estado |
|---|---|
| BookingController | ❌ No existe |
| Rutas del wizard (14+) | ❌ No existen |
| Vistas del wizard (8 pasos × 4 temas) | ❌ No existen |
| Sesión multi-paso | ❌ No existe |
| BookingService mejorado (multi-viajero, waitlist, docs, emails) | ❌ Solo `createBooking` con 1 viajero |
| Paso 0: Origen desde CTA | ❌ |
| Paso 1: Selección fecha | ❌ |
| Paso 2: N° plazas + validación | ❌ |
| Paso 3: Datos viajero(s) + tutor menores | ❌ |
| Paso 4: Grupo tarifa | ❌ |
| Paso 5: Opciones adicionales | ❌ |
| Paso 6: Resumen + confirmación | ❌ |
| Paso 7: Primer pago | ❌ |
| Paso 8: Confirmación final + email | ❌ |
| Conexión viajes (descuento plazas) | ⚠️ BookingService lo hace, pero no hay quien lo llame |
| Conexión tarifas (congelar plan) | ❌ |
| Conexión transporte (asiento auto) | ❌ |
| Conexión alojamiento (preferencias) | ❌ |
| Conexión viajeros (crear ficha) | ⚠️ TravelerObserver existe pero inactivo |
| Conexión documentación (generar docs) | ❌ |
| Conexión notificaciones email | ❌ |
| Lista de espera | ❌ |

### FASE 22 — Blog/FAQ públicos
**Estado: ✅ COMPLETO**

| Elemento | Estado |
|---|---|
| Blog público con paginación y filtro categoría | ✅ 9 por página, filtro por slug |
| FAQ pública ordenada | ✅ Por campo `order` |
| Visibilidad según toggle dashboard | ❌ No hay toggle aún (depende de FASE 17) |

### FASE 23 — CRM de Ventas
**Estado: ⚠️ PARCIAL (50%) — scaffolding completo, integración pendiente**

| Elemento | Estado |
|---|---|
| Customer model + migración | ✅ |
| CustomerInteraction model + migración | ✅ |
| Vinculación automática via TravelerObserver | ✅ Observer registrado |
| Pipeline kanban con 7 etapas | ⚠️ **Estructura HTML + endpoints OK, JS drag & drop NO implementado** |
| Tarjetas kanban (nombre, viaje, valor, último contacto) | ✅ Customer-card partial |
| Cambios etapa automáticos (eventos) | ❌ No implementados (scheduler/rules) |
| Cambios etapa manuales (drag/selector) | ⚠️ Selector POST funciona, drag HTML preparado sin JS |
| Ficha de cliente (contacto, etapa, viajes, viajeros, interacciones, notas) | ✅ Vista completa |
| Registro interacciones (email, WhatsApp, llamada, nota) | ✅ CRUD con canales |
| Motor notificaciones multicanal | ⚠️ NotificationEngine existe, `sendEmail/sendWhatsApp` son TODOs |
| WhatsApp Business API config | ⚠️ Model + Service existen, `sendMessage` es stub que retorna false |
| Métricas CRM | ⚠️ Conversion rate, recurring rate OK; tiempo respuesta y abandono son placeholders |
| No modifica BookingService | ✅ Capa separada |

---

## 3. Estructura de archivos actual

```
/
├── agent.md                          # Especificación funcional
├── agent2.md                         # Versión ampliada (reglas + CRM)
├── plan-implementacion.md            # Plan de implementación
├── project-map.md                    # Este fichero
├── MAP.md                            # Mapa estructural
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php          (stats básicos)
│   │   │   │   ├── TripController.php                (CRUD + duplicar)
│   │   │   │   ├── ItineraryController.php           (días + actividades)
│   │   │   │   ├── TransportController.php           (buses + asientos)
│   │   │   │   ├── TravelerController.php            (CRUD viajeros global)
│   │   │   │   ├── TripTravelerController.php        (viajeros por viaje)
│   │   │   │   ├── TripDocumentController.php        (documentos por viaje)
│   │   │   │   ├── ProgramController.php             (CRUD + duplicar)
│   │   │   │   ├── TripCategoryController.php        (CRUD categorías)
│   │   │   │   ├── PricingController.php             (grupos + plazos)
│   │   │   │   ├── AccommodationController.php       (alojamientos + habitaciones)
│   │   │   │   ├── ProfileController.php             (perfil admin)
│   │   │   │   ├── Blog/BlogPostController.php       (CRUD posts + Jodit)
│   │   │   │   ├── Blog/BlogCategoryController.php   (CRUD categorías blog)
│   │   │   │   ├── FaqController.php                 (CRUD + reorder)
│   │   │   │   ├── ServiceController.php             (CRUD servicios)
│   │   │   │   ├── ContactController.php             (listar mensajes)
│   │   │   │   ├── ThemeController.php               (index, activate, preview)
│   │   │   │   └── Crm/
│   │   │   │       ├── CustomerController.php        (kanban, show, metrics)
│   │   │   │       └── InteractionController.php     (CRUD interacciones)
│   │   │   │
│   │   │   └── Public/
│   │   │       ├── HomeController.php                (home con datos reales)
│   │   │       ├── TripDetailController.php          (ficha única de viaje)
│   │   │       ├── AboutController.php               (sobre nosotros + team)
│   │   │       ├── BlogController.php                (blog paginado + categorías)
│   │   │       ├── FaqController.php                 (FAQ ordenada)
│   │   │       ├── ServiceController.php             (servicios activos)
│   │   │       └── ContactController.php             (form + store)
│   │   │
│   │   ├── Middleware/
│   │   │   ├── CheckInstallation.php
│   │   │   └── CheckAdmin.php
│   │   └── Requests/
│   │       └── LoginRequest.php                      (email + phone + password)
│   │
│   ├── Models/                 (28 modelos — ver MAP.md)
│   ├── Services/
│   │   ├── BookingService.php                        (básico, necesita expansión)
│   │   ├── CustomerService.php                       (CRM vinculación)
│   │   ├── NotificationEngine.php                    (stub: sendEmail/sendWhatsApp = TODO)
│   │   └── WhatsAppService.php                       (stub: sendMessage = false)
│   ├── Observers/
│   │   └── TravelerObserver.php                      (vincula Traveler → Customer)
│   └── Mail/                                         (NO EXISTE — falta)
│
├── resources/views/
│   ├── layouts/
│   │   ├── admin.blade.php             (sidebar sin colapsables, sin botón web)
│   │   ├── public.blade.php            (shell: head + meta + fonts + CSS tema + main + partials dinámicos)
│   │   └── app.blade.php, guest.blade.php (Breeze)
│   ├── admin/
│   │   ├── dashboard.blade.php                     (stats básicos + lista viajes)
│   │   ├── trips/{index,create,edit,show,itinerary,transport,
│   │   │         travelers,documents}.blade.php     (7 tabs)
│   │   ├── _tabs.blade.php                           (navegación 7 pestañas)
│   │   ├── travelers/{index,create,edit}.blade.php
│   │   ├── programs/{index,create,edit}.blade.php
│   │   ├── categories/index.blade.php
│   │   ├── pricing/index.blade.php
│   │   ├── accommodations/index.blade.php
│   │   ├── blog-posts/{index,create,edit}.blade.php
│   │   ├── blog-categories/index.blade.php
│   │   ├── faqs/index.blade.php
│   │   ├── themes/index.blade.php       (tarjetas con paletas reales + info arquitectura)
│   │   ├── services/{index,create,edit}.blade.php
│   │   ├── contacts/{index,show}.blade.php
│   │   └── profile/index.blade.php
│   ├── panel/crm/
│   │   ├── kanban.blade.php              (columnas + tarjetas draggable, sin JS drag)
│   │   ├── customer-card.blade.php       (partial tarjeta)
│   │   ├── customer-show.blade.php       (ficha completa)
│   │   ├── interactions.blade.php        (timeline interacciones)
│   │   ├── list.blade.php                (tabla clientes)
│   │   └── metrics.blade.php             (métricas)
│   ├── installation/{step1,step2,step3,step4}.blade.php
│   ├── auth/login.blade.php              (email + phone + password)
│   └── themes/
│       ├── ethos_earth/
│       │   ├── partials/          (header.blade.php, footer.blade.php — propios)
│       │   └── public/            (9 vistas: home, about, trip-detail, blog, blog-post, faq,
│       │                          #   services, contact, trips + 8 booking + _progress)
│       ├── ethos_ocean/           (misma estructura que Earth, CSS/paleta propios)
│       ├── ethos_peak/            (misma estructura que Earth, CSS/paleta propios)
│       └── ethos_sunset/          (misma estructura que Earth, CSS/paleta propios)
│
├── tema-visual-base/                    # CSS fuente editables (source de los 4 temas)
│   ├── DESIGN.md                        # Sistema diseño Earth
│   ├── ethos_earth.css                  # CSS fuente Earth (forest-deep/copper-earth)
│   ├── ethos_ocean.css                  # CSS fuente Ocean (deep-ocean/teal)
│   ├── ethos_peak.css                   # CSS fuente Peak (slate/red)
│   ├── ethos_sunset.css                 # CSS fuente Sunset (orange/purple)
│   └── detalle_viaje_landing_page.html  # Mockup HTML referencia
│
├── public/
│   ├── css/dashboard/admin.css
│   ├── css/themes/ethos_earth/style.css   (CSS autónomo nativo, ~43KB)
│   ├── css/themes/ethos_ocean/style.css   (CSS autónomo nativo, ~42KB)
│   ├── css/themes/ethos_peak/style.css    (CSS autónomo nativo, ~42KB)
│   ├── css/themes/ethos_sunset/style.css  (CSS autónomo nativo, ~42KB)
│   └── js/jodit/{jodit.min.js, jodit.min.css}
│
├── routes/
│   └── web.php                           (61 rutas admin + 10 públicas + 8 instalación + auth.php)
│
├── database/
│   ├── migrations/                       (35 migraciones)
│   └── seeders/DatabaseSeeder.php        (seed completo con datos demo)
│
├── agent.md, agent2.md, plan-implementacion.md, project-map.md, MAP.md, tareas.md
├── dashboard-design/                     (prototipos visuales referencia)
├── tema-visual-base/                     (maquetación HTML referencia)
```

---

## 4. Resumen cuantitativo

| FASE | Título | % | Estado |
|---|---|---|---|
| 1 | Instalación | 100% | ✅ |
| 2 | Login | 100% | ✅ |
| 3 | Dashboard layout | 70% | ⚠️ Sin colapsables, botón web + buscador funcional añadidos |
| 4 | Dashboard home | 70% | ⚠️ Sin CSV, sin filtros avanzados, sin avatar responsable |
| 5 | Programas y categorías | 70% | ⚠️ Sin filtros, sin stats dedicadas |
| 6 | Info tab | 75% | ⚠️ Sin validación fechas, sin exportar PDF |
| 7 | Itinerario tab | 85% | ⚠️ Sin estados borrador/publicado |
| 8 | Transporte tab | 85% | ⚠️ Sin tabla pasajeros por bus |
| 9 | Alojamiento tab | 95% | ✅ |
| 10 | Tarifas tab | 95% | ✅ |
| 11 | Viajeros tab | 60% | ⚠️ Sin buscador, sin filtros, sin paginación, sin ficha detalle |
| 12 | Documentación tab | 60% | ⚠️ Sin tipos específicos, sin sincronización explícita |
| 13 | Blog | 85% | ⚠️ Sin toggle activar/desactivar |
| 14 | FAQ | 90% | ✅ Drag & drop funcional |
| 15 | Temas visuales | 100% | ✅ Refactor: CSS nativo autónomo, header/footer por tema, sin Tailwind CDN |
| 16 | Imágenes | 100% | ✅ ImageController, MediaImage, helper theme_image(), vista gestión |
| 17 | Calendario | 100% | ✅ CalendarController, grid visual, solapamientos, toggles secciones públicas |
| 18 | Configuración | 100% | ✅ SettingsController completo, frases, RRSS, email, ayuda, perfil con avatar, buscador global |
| 19 | Homepage pública | 90% | ⚠️ Sin modo landing one-page |
| 20 | Ficha viaje pública | 32% | ⚠️ CTA placeholder, sin sticky nav, sin acordeón, sin sidebar completa |
| 21 | Sistema reservas | 5% | ❌ Solo BookingService básico |
| 22 | Blog/FAQ públicos | 85% | ⚠️ Sin toggle visibilidad |
| 23 | CRM | 50% | ⚠️ Sin JS drag, sin notificaciones reales, WhatsApp stub |

**Global:** ~60% del total del proyecto implementado

---

## 5. Dependencias entre fases pendientes

```
FASE 19 (públicas) ── OK ──► FASE 20 (landing) ──► FASE 21 (reservas)
                                                         │
                     FASE 15 (temas) ◄── FASE 16 (imágenes)
                     FASE 17 (calendario) ──► toggles para FASE 13,14,22
                     FASE 18 (config) ──► email para FASE 21
                                               │
                    FASE 23 (CRM) ──► depende de FASE 21 (reservas reales)
                    FASE 10 (temas restantes) ──► independiente
```

**Orden de ejecución recomendado:**
1. FASE 15 (Temas visuales) + FASE 10 (3 temas restantes) — independientes, necesarios para que el usuario pueda cambiar de tema
2. FASE 18 (Configuración) — necesario para email, frases, RRSS
3. FASE 16 (Imágenes) — puede ir en paralelo con 18
4. FASE 17 (Calendario + toggles) — necesario para activar/desactivar secciones
5. FASE 20 (Landing pública mejorada) — antes del wizard de reserva
6. FASE 21 (Wizard reserva) — dependencia mayor, necesita 18 (email) y 20 (CTAs)
7. FASE 23 (CRM completar) — necesita 21 (reservas reales)
8. Pendientes menores de FASE 3,4,5,11,12,14
