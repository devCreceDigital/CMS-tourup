# TAREAS — TOUR UP CMS

> Registro de tareas y su estado. Se actualiza cada vez que se cumple una.

---

## FASE 0 — Correcciones sobre código existente

- [x] **DatabaseSeeder** — seed con datos demo completos (agencia, admin, 2 viajes, pricing, itinerarios, viajeros, blog, FAQ, servicios, settings)
- [x] **DashboardController** — corregido `sum('amount_paid') ?: 0` (columna existe, datos no significativos sin reservas)
- [x] **Sidebar href="#"** — añadido `title="Próximamente disponible"` a Temas y Configuración
- [x] **FAQ drag & drop JS** — HTML5 DnD implementado con reorder endpoint
- [x] **Ruta `/viaje/{slug}/inscribir`** — reemplazado por botón placeholder con mensaje informativo

## FASE 1 — Temas Visuales

- [x] **ThemeController** — creado con index, activate, preview
- [x] **Vista gestión temas** — 4 tarjetas con badge activo + previsualización
- [x] **Tema Ocean** — 8 vistas + CSS (paleta azul/cyan/teal, Playfair Display + Inter)
- [x] **Tema Peak** — 8 vistas + CSS (paleta stone/red, Merriweather + Inter)
- [x] **Tema Sunset** — 8 vistas + CSS (paleta orange/purple/gold, Lora + Inter)
- [x] **Installer step4** — botón "Vista Previa" que abre el sitio con el tema seleccionado
- [x] **Public controllers** — actualizados para soportar preview via query param `?preview_theme=` y sesión

## FASE 2 — Configuración General ✅

- [x] **SettingsController** — CRUD frases, RRSS, email, help + phrases methods
- [x] **Vista frases públicas** — lista editable por clave (phrases.blade.php)
- [x] **Vista redes sociales** — formulario enlaces (tab Frases añadido)
- [x] **Vista email** — SMTP + mini-tutorial Gmail (tab Frases añadido)
- [x] **Vista ayuda** — tutorial TOUR UP completo (help.blade.php)
- [x] **Vista index** — rediseñado como dashboard con tarjetas de acceso
- [x] **Perfil avatar** — subida con preview (file upload + Storage)
- [x] **Buscador global** — SearchController + vista resultados
- [x] **Sidebar** — añadidos Perfil, Ayuda y "Ver sitio web"
- [x] **Topbar search** — ahora funcional (form método GET a admin.search)

## FASE 3 — Imágenes ✅

- [x] **Migración + MediaImage model** — create_media_images_table (key, file_path, alt_text, section)
- [x] **ImageController** — index (agrupado por sección), upload, destroy
- [x] **Helper `theme_image()`** — busca en BD por key, fallback a path por defecto
- [x] **Vista gestión imágenes** — grid por sección con thumbnails + subida + eliminar
- [x] **Autoload** — helpers.php registrado en composer.json autoload.files

## FASE 4 — Calendario ✅

- [x] **CalendarController** — index + data JSON (viajes del año, colores por estado, detección de solapamientos)
- [x] **Calendario visual** — grid mensual con tooltips, colores por estado, solapamientos en rojo, navegación mes/año
- [x] **Migración** — booking_enabled añadido a settings
- [x] **Toggles en BlogController** — blog_enabled check en index, category, show
- [x] **Toggles en FaqController** — faq_enabled check en index
- [x] **Toggles en ServiceController** — services_enabled check en index
- [x] **Toggles en TripDetailController** — booking_enabled check, oculta CTA reservar si desactivado (4 temas)
- [x] **Sidebar** — enlace Calendario añadido

## FASE 5 — Landing pública (FASE 20)

- [ ] **Sticky nav** con anclas + CTA fijo
- [ ] **Hero mejorado** — año, subtítulo, barra confianza
- [ ] **Resumen ejecutivo** — destinos + duración
- [ ] **Bloques valor** — "Por qué viajar con nosotros"
- [ ] **Itinerario acordeón** — expandir/colapsar días
- [ ] **Sidebar completa** — inscripción, alojamientos, beneficios, enlaces útiles, aviso waitlist
- [ ] **Adaptar a 4 temas**

## FASE 6 — Sistema de Reservas (FASE 21)

- [ ] **BookingService mejorado** — multi-viajero, waitlist, asientos, docs, emails, congelar tarifa
- [ ] **Mailables** — BookingConfirmation + NewBookingNotification
- [ ] **BookingController** — 14 rutas, 8 pasos, sesión
- [ ] **Vista step1** — selección fecha
- [ ] **Vista step2** — número plazas + validación
- [ ] **Vista step3** — formularios viajeros + tutor menores
- [ ] **Vista step4** — selector tarifa + desglose
- [ ] **Vista step5** — extras (habitación, menú)
- [ ] **Vista step6** — resumen + checkbox privacidad
- [ ] **Vista step7** — pago (manual por ahora)
- [ ] **Vista step8** — confirmación + referencia
- [ ] **Integraciones post-reserva** — plazas, viajeros, docs, emails, CRM

## FASE 7 — CRM completar

- [ ] **Kanban drag & drop JS** — HTML5 DnD funcional
- [ ] **Cambios etapa automáticos** — observer + scheduler
- [ ] **NotificationEngine** — sendEmail real, sendWhatsApp stub
- [ ] **Métricas reales** — conversión, respuesta, recuperación, recurrencia

## FASE 8 — Dashboard pendientes

- [ ] **Sidebar colapsable** — grupos con localStorage
- [ ] **Botón "Ver sitio web"** — en sidebar
- [ ] **Dashboard home stats reales** — corregir + barras recaudación
- [ ] **Filtros avanzados** — categoría, rango fechas, ocupación
- [ ] **Exportación CSV** — viajes filtrados
- [ ] **Programas stats categorías** — total, popular, activas/archivadas
- [ ] **Pestaña Viajeros** — buscador, filtros, paginación, exportar, ficha detalle
- [ ] **Pestaña Documentación** — tipos específicos, rechazar, sync estado
- [ ] **FAQ drag & drop JS** — Sortable funcional
- [ ] **Itinerario draft/published** — campo + botones + filtro público
- [ ] **Componentes reutilizables** — stat-card, badge, table, timeline, modal

## FASE 9 — Pulido final

- [ ] **SEO** — meta tags, schema.org, sitemap.xml, robots.txt
- [ ] **Responsive check** — todas las vistas
- [ ] **Seguridad** — CSRF, middleware, XSS
- [ ] **Performance** — eager loading, paginación, índices

## REFACTOR — Temas visuales autónomos (2026-07-13) ✅

- [x] **4 CSS nativos autónomos** — tema-visual-base/ethos_{earth,ocean,peak,sunset}.css + copia a public/css/themes/
- [x] **Layout public.blade.php** — quitado Tailwind CDN + tailwind.config inline + header/footer hardcodeados
- [x] **Partials dinámicos** — themes/{slug}/partials/header.blade.php + footer.blade.php en los 4 temas
- [x] **9 vistas públicas × 4 temas** — home, about, trip-detail, blog, blog-post, faq, services, contact, trips con CSS nativo
- [x] **8 vistas booking × 4 temas** — _progress + step1-8 con CSS nativo e include dinámico del tema
- [x] **admin/themes/index.blade.php** — rediseñada con paletas reales, info de arquitectura y miniaturas
- [x] **Design tokens --theme-*** — aliases compartidos en los 4 CSS para inline styles en vistas
- [x] **Sincronización documentación** — MAP.md, project-map.md, tareas.md actualizados
- [x] **Usuario BD agencia_user** — creado en MySQL con privilegios sobre agencia_turismo (reparadas tablas Aria corruptas)
