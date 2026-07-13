# MAP.md — Mapa estructural del repositorio

> Referencia rápida de estructura. Sin lógica de negocio. Actualizado: 2026-07-13

```
/
├── agent.md                     # Especificación funcional completa
├── agent2.md                    # Versión ampliada con reglas + CRM
├── plan-implementacion.md       # Plan de implementación (orden)
├── project-map.md               # Estado real del sistema
├── MAP.md                       # Este fichero
├── tareas.md                    # Tareas pendientes/completadas
│
├── tema-visual-base/            # Maquetación HTML referencia pública
│   ├── DESIGN.md                # Sistema diseño Earth (Minimalist-Organic)
│   ├── ethos_earth.css          # CSS fuente tema Earth (forest/copper)
│   ├── ethos_ocean.css          # CSS fuente tema Ocean (deep/teal)
│   ├── ethos_peak.css           # CSS fuente tema Peak (slate/red)
│   ├── ethos_sunset.css         # CSS fuente tema Sunset (orange/purple)
│   └── detalle_viaje_landing_page.html  # Mockup HTML referencia
│
├── dashboard-design/            # Prototipo visual referencia dashboard
│   ├── DESIGN.md                # Sistema diseño Scholar Journey
│   ├── admin_trips_management_list.*
│   ├── admin_detailed_itinerary_creator.*
│   ├── admin_travel_category_management.*
│   ├── admin_travelers_list_management.*
│   └── parent_trip_management_dashboard.*
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/           # 18 controladores (dashboard, trips, itinerario,
│   │   │   │                    #   transporte, viajeros, programas, categorías,
│   │   │   │                    #   pricing, alojamiento, perfil, blog, faq,
│   │   │   │                    #   servicios, contactos, documentos, CRM)
│   │   │   │   ├── Blog/       # BlogPostController, BlogCategoryController
│   │   │   │   └── Crm/        # CustomerController, InteractionController
│   │   │   └── Public/          # 7 controladores (home, trip-detail, about,
│   │   │                        #   blog, faq, services, contact)
│   │   ├── Middleware/           # CheckInstallation, CheckAdmin
│   │   ├── Requests/            # LoginRequest
│   │   └── Kernel.php
│   ├── Models/                  # 28 modelos (Trip, Traveler, TripBooking,
│   │                            #   ItineraryDay, ItineraryActivity, Bus, BusSeat,
│   │                            #   Program, PricingGroup, PricingInstallment,
│   │                            #   Payment, Accommodation, Room, TripCategory,
│   │                            #   User, Agency, BlogCategory, BlogPost, Faq,
│   │                            #   Service, Contact, Setting, TravelerDocument,
│   │                            #   Customer, CustomerInteraction, NotificationRule,
│   │                            #   NotificationTemplate, WhatsAppSettings)
│   ├── Services/                # BookingService, CustomerService,
│   │                            #   NotificationEngine, WhatsAppService
│   ├── Observers/               # TravelerObserver
│   └── Providers/               # RouteServiceProvider
│
├── resources/views/
│   ├── layouts/                 # admin.blade.php, public.blade.php,
│   │                            #   app.blade.php, guest.blade.php
│   ├── admin/                   # 30+ vistas del dashboard
│   │   ├── trips/               # 9 vistas (index, create, edit, show + 5 tabs)
│   │   ├── blog-posts/          # 3 vistas
│   │   ├── blog-categories/     # 1 vista
│   │   ├── programs/            # 3 vistas
│   │   ├── categories/          # 1 vista
│   │   ├── travelers/           # 3 vistas
│   │   ├── pricing/             # 1 vista
│   │   ├── accommodations/      # 1 vista
│   │   ├── faqs/                # 1 vista
│   │   ├── services/            # 3 vistas
│   │   ├── contacts/            # 2 vistas
│   │   └── profile/             # 1 vista
│   ├── panel/crm/               # 6 vistas (kanban, list, customer-show,
│   │                            #   customer-card, interactions, metrics)
│   ├── installation/            # 4 vistas (step1-4)
│   ├── auth/                    # login, register, forgot-password, etc.
│   ├── components/              # Componentes Blade Breeze
│   └── themes/
│       ├── ethos_earth/public/   # 9 vistas + 8 booking + 2 partials (completo)
│       │   ├── partials/         # header.blade.php, footer.blade.php (propios)
│       │   ├── home, about, trip-detail, blog, blog-post, faq,
│       │   │   services, contact, trips (9 vistas públicas)
│       │   └── booking/          # step1-8 + _progress (8 vistas)
│       ├── ethos_ocean/public/   # Igual que Earth (CSS/paleta propio)
│       ├── ethos_peak/public/    # Igual que Earth (CSS/paleta propio)
│       └── ethos_sunset/public/  # Igual que Earth (CSS/paleta propio)
│
├── public/
│   ├── css/
│   │   ├── dashboard/admin.css  # CSS panel admin
│   │   └── themes/
│   │       ├── ethos_earth/style.css   # CSS autónomo nativo
│   │       ├── ethos_ocean/style.css   # CSS autónomo nativo
│   │       ├── ethos_peak/style.css    # CSS autónomo nativo
│   │       └── ethos_sunset/style.css  # CSS autónomo nativo
│   └── js/jodit/                # Jodit WYSIWYG (offline)
│
├── routes/web.php               # ~75 rutas (públicas + admin + CRM + auth)
├── database/
│   ├── migrations/              # 35 migraciones
│   └── seeders/DatabaseSeeder.php  # STUB vacío
│
├── node_modules/, vendor/       # Dependencias
├── storage/                     # Logs, cache, uploads
├── config/, bootstrap/, lang/   # Config Laravel
├── composer.json, package.json, tailwind.config.js, vite.config.js
└── .env, .gitignore, README.md
```

## Módulos actuales

| Módulo | Estado | Archivos clave |
|---|---|---|
| Auth | ✅ Completo | LoginRequest, AuthenticatedSessionController |
| Instalación | ✅ Completo | InstallController, 4 steps |
| Dashboard | ⚠️ 60% | DashboardController, admin.blade.php |
| Viajes | ⚠️ 80% | TripController, 7 tabs |
| Programas | ⚠️ 70% | ProgramController, TripCategoryController |
| Tarifas | ✅ 95% | PricingGroup, PricingInstallment |
| Alojamiento | ✅ 95% | Accommodation, Room |
| Blog | ✅ 85% | BlogPostController + Jodit |
| FAQ | ⚠️ 80% | FaqController (sin JS drag) |
| Temas | ✅ 100% | ThemeController + 4 temas completos (CSS autónomo, sin herencia) |
| Imágenes | ❌ 0% | No existe |
| Calendario | ❌ 0% | No existe |
| Configuración | ❌ 10% | Solo perfil sin avatar |
| Páginas públicas | ✅ 90% | HomeController, AboutController |
| Landing viaje | ⚠️ 30% | TripDetailController |
| Reservas | ❌ 5% | Solo BookingService |
| Blog/FAQ público | ✅ 85% | BlogController, FaqController |
| CRM | ⚠️ 50% | CustomerController, kanban sin JS drag |

## Reglas de mantenimiento

- Actualizar solo cuando cambia la estructura general (nuevos módulos/carpetas)
- No contiene lógica de negocio
- No implica refactors
