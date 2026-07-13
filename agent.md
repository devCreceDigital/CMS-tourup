# Proyecto: TOUR UP - TurismoCMS

## Rol del agente:
Desarrollador web senior con 12 años de experiencia en Laravel, CMS turísticos, motores de reserva, CRM de ventas y arquitectura monolítica mantenible.

---

## Objetivo general:

Crear una aplicación web (CMS) para agencias de turismo, donde puedan:

- Tener una web administrable.
- Elegir entre varios temas visuales.
- Tener blog.
- Gestionar reservas.
- Tener un panel de administración.
- Gestionar viajeros.
- Gestionar viajes, itinerarios, transporte y alojamiento.
- Gestionar disponibilidad y calendario de salidas.
- Administrar toda la información pública de la web.
- Gestionar la relación comercial con sus clientes a través de un CRM integrado centrado en el ciclo de vida del viajero.

El objetivo es cubrir el flujo de trabajo completo de una agencia de turismo, con especial foco en programas de viajes escolares y grupales, aunque debe servir para cualquier tipo de viaje que la agencia ofrezca.

Todo se podrá administrar desde un panel privado.

---

## Consideraciones generales:

Estas reglas aplican SIEMPRE a todas las fases y funcionalidades:

- Protección de rutas.
- Validación de solapamientos (fechas de viajes, asientos de autobús, habitaciones, disponibilidad).
- Priorizar la sencillez, que todo sea intuitivo y fácil de entender.
- Priorizar buenas prácticas y seguridad.
- Mostrar mensajes de confirmación.
- Si no existen datos en una sección, mostrar un "empty state" agradable.
- Usar Font Awesome (tenemos la fuente en concreto para usar en la carpeta `tema-visual-base/assets/fonts`).
- Todas las acciones del dashboard requieren autenticación.
- Todas las urls del panel deben comenzar por: `/panel-agencia`.
- Todas las funcionalidades deben ser totalmente funcionales.
- No romper funcionalidades anteriores.
- Mantener la consistencia visual en todo el dashboard.
- Mantener coherencia responsive.
- Priorizar UX.
- Priorizar reutilización de componentes (tarjetas de estadísticas, tablas con filtros, badges de estado, timelines, etc. deben ser componentes reutilizables entre secciones).
- No usar `alert`, `confirm` ni `prompt`; todo el feedback debe ser visual en el DOM.
- No usar `innerHTML`; todo el contenido debe insertarse con `appendChild` o creando previamente un elemento con `document.createElement`.
- Usar siempre `let` o `const`, nunca `var`.
- Toda la parte pública debe estar optimizada para SEO a nivel de código y buenas prácticas.
- Todas las decisiones ambiguas deben resolverse con la opción más simple que no rompa funcionalidad existente.

---

## Reglas preventivas de arquitectura:

Estas reglas son obligatorias desde la FASE 0 en adelante y prevalecen sobre cualquier decisión de implementación posterior que las contradiga.

### Regla 1 — Un solo controlador y una sola ruta por página semántica
- Los 4 temas visuales son exclusivamente pieles visuales.
- El tema activo solo cambia la vista Blade y el CSS dentro de `resources/views/themes/{slug}/...`.
- Nunca deben existir rutas o controladores distintos por tema.
- Prohibido crear controladores separados por concepto de plantilla o tipo visual como `ExpeditionController`, `SchoolExpeditionController` o un `HomeController` distinto por tema.
- Debe existir un único controlador para cada página pública semántica: homepage, sobre nosotros, servicios, contacto, blog, FAQ, ficha de viaje y reserva.
- Las variantes visuales específicas de cada tema (timeline con checkpoints, badges, banners, etc.) se resuelven en la vista del tema, nunca duplicando backend.

### Regla 2 — Los temas visuales no alteran la lógica
- Los 4 temas comparten siempre el mismo contenido, las mismas rutas, los mismos controladores y la misma lógica.
- Solo cambia la presentación visual.
- La selección del tema activo se gestiona desde la configuración de la agencia.

### Regla 3 — Prohibido branding o contenido real heredado de plantillas de inspiración
- Si una plantilla visual trae textos, branding o datos de ejemplo, deben usarse solo como referencia de maquetación.
- Prohibido dejar textos reales de otra marca, país, RUC, claims comerciales o secciones institucionales ajenas al proyecto.
- Todo el contenido real visible debe salir de la instalación inicial, del dashboard o de seeds explícitamente definidos para TOUR UP.

### Regla 4 — El precio del viaje nunca se modela como un campo suelto
- Prohibido usar un único campo `precio` en `trips` como representación del costo del viaje.
- El precio y el calendario de cobro deben modelarse siempre mediante grupos de tarifa y plazos de pago.
- Incluso si un viaje solo tiene una tarifa simple, debe existir al menos un grupo `Por defecto` con un plazo configurado.

### Regla 5 — El alojamiento siempre es dato gestionado, nunca contenido estático
- Prohibido hardcodear hoteles, habitaciones o textos de alojamiento directamente en las vistas públicas.
- Todo alojamiento mostrado en la ficha pública del viaje debe venir de los módulos `Alojamiento` y `Habitaciones` del dashboard.

### Regla 6 — No existe ningún sistema de citas
- Este proyecto no gestiona citas, turnos médicos, franjas horarias de agenda ni reservas por slots.
- El único flujo de reserva válido es la inscripción a viaje definida en la FASE 21.
- Cualquier patrón heredado de plantillas de “booking de citas” queda expresamente prohibido.

### Regla 7 — Login único de agencia con 3 campos obligatorios
- La única ruta válida de login es `/acceso-agencia`.
- El login debe requerir obligatoriamente email + teléfono + contraseña.
- No puede convivir con un login alternativo tipo `/login` por defecto de Breeze u otro scaffolding sin adaptar.

### Regla 8 — Sobre Nosotros debe salir de datos reales
- La sección de equipo debe recibir un array o colección real desde el controlador.
- El mapa, presencia o impacto debe estar respaldado por datos reales configurables, no por placeholders visuales sin soporte de datos.
- El footer debe quedar estructuralmente completo desde que se implementa el layout público.

### Regla 9 — El CRM no sustituye al motor de reservas
- El CRM es una capa superior de relación con el cliente.
- No puede romper ni duplicar la lógica de `BookingService`.
- `BookingService` sigue siendo la única fuente de verdad para reservas, viajeros, pagos iniciales y documentación generada desde el flujo público.

---

## Arquitectura general:

### Parte pública:

La parte pública incluirá:

- Homepage.
- Sobre nosotros.
- Servicios.
- Categorías de viajes.
- Ficha de viaje / expedición (landing de conversión de cada viaje).
- Blog.
- Preguntas frecuentes.
- Sistema de reservas / inscripción.
- Contacto.

### Dashboard privado:

El dashboard permitirá:

- Gestionar viajes (instancias reales con fechas y plazas).
- Gestionar programas y categorías de viaje (plantillas base).
- Gestionar itinerarios detallados día a día.
- Gestionar transporte y asientos.
- Gestionar alojamiento.
- Gestionar tarifas y planes de pago.
- Gestionar viajeros (pasajeros).
- Gestionar documentación de viajeros.
- Gestionar blog.
- Gestionar preguntas frecuentes.
- Gestionar temas visuales.
- Gestionar imágenes.
- Gestionar configuración de la web.
- Gestionar disponibilidad y calendario de salidas.
- Gestionar frases públicas.
- Gestionar notificaciones por email y WhatsApp.
- Gestionar redes sociales.
- Gestionar el perfil privado.
- Gestionar clientes y seguimiento comercial mediante un CRM integrado.

---

## Funcionalidades de la aplicación:

### FASE 1:
- Asistente de instalación donde se rellenan al inicio los datos más importantes de la agencia:
    - Crear la base de datos automáticamente con los datos de nuestro servidor y nuestra conexión.
    - Nombre del administrador y nombre de la agencia.
    - Email, número de teléfono y contraseña (para hacer login con esos 3 datos, único y privado para la agencia; no habrá múltiples usuarios ni registro, más allá de la instalación inicial).
    - Rellenar la información básica de la agencia para la web: nombre del administrador, frase de bienvenida/eslogan, sobre la agencia, tipos de servicios que ofrecen, planes y precios, experiencias, número de teléfono de contacto, dirección.
    - Rellenar un primer programa/viaje de ejemplo: nombre del viaje, categoría del viaje, fechas de viaje, duración, descripción del viaje, disponibilidad/plazas, dirección/destino.
    - Rellenar un primer viajero de ejemplo: nombre, DNI, fecha de nacimiento, sexo, email, teléfono.
    - Subir logo de la agencia, preferiblemente sin fondo (indicarlo).
    - Todos estos datos serán después modificables y ampliables en el dashboard.
    - Selección de tema o plantilla visual (habrá 4 para elegir; basarse en el que ya tenemos en la carpeta `tema-visual-base` —será prácticamente idéntico— y usarlo como base para crear los otros 3, con variaciones de layout y paleta).
    - Cuando el asistente termine, llegaremos al dashboard de administración.
- Restricciones funcionales de esta fase:
    - El asistente no crea múltiples usuarios.
    - Debe dejar listo el tema activo inicial.
    - Debe sembrar contenido mínimo real para que la parte pública y el panel no se vean vacíos al terminar.

### FASE 2:
- Panel de administración privado:
    - Login seguro, con email, número de teléfono y contraseña.
    - Será obligatorio introducir los 3 datos y debe existir la opción de persistir el login.
    - Usa el método más adecuado para el login y la autenticación segura, sin pasarse de complejo, con la contraseña bien cifrada.
    - Login de la agencia en la url: `/acceso-agencia`.
- Restricciones funcionales de esta fase:
    - Todas las rutas de `/panel-agencia` deben quedar protegidas.
    - No debe existir una ruta alternativa de login distinta a `/acceso-agencia`.

### FASE 3:
- Dashboard en la url: `/panel-agencia` (todas las urls dentro del dashboard irán a partir de esta y todas requieren autenticación de la agencia, al igual que cualquier acción del backend relacionada con el panel).
- Layout, estructura y menú del dashboard:
    - Sidebar persistente en azul marino con las secciones agrupadas: Programas, Pasajeros, Viajes, Tiendas (recaudación), Sistema, y más adelante CRM.
    - Ciertos elementos serán desplegables (como en WordPress) para agrupar lo que tenga sentido, y las opciones más importantes tendrán acceso directo.
    - Top bar con: migas de pan (breadcrumbs), buscador global, notificaciones y acceso al perfil del administrador.
    - Botón en la barra lateral para ir a ver la parte pública de la web.
- Requisito estructural:
    - Deben quedar definidos los componentes base reutilizables del dashboard, porque serán usados por múltiples fases posteriores.

### FASE 4:
- Página de inicio del dashboard (Gestión de viajes / Trips Management List):
    - Tarjetas de estadísticas: viajes activos, próximas salidas, viajeros totales y porcentaje de recaudación pendiente (inicialmente con datos de prueba, cuando se completen el resto de fases aparecerán datos reales).
    - Listado de viajes en tarjetas con: imagen del destino, badge de estado (Activo, Completo, En venta), contador de plazas ocupadas/totales con barra de progreso, recaudación actual/objetivo con barra de progreso, avatares de responsables asignados y botón `Configurar`.
    - Filtros por año, estado del viaje y búsqueda por referencia; filtros avanzados y exportación a CSV.
    - Botón `Nuevo viaje` y tarjeta de acceso directo `Crear nuevo itinerario`.
    - Al entrar a `Configurar` un viaje se accede a su ficha de configuración con pestañas: Información, Transporte, Alojamiento, Itinerario, Tarifas, Viajeros, Documentación.
- Regla de integración:
    - Esta fase actúa como entrada principal al bloque de gestión de viajes y debe anticipar todas las pestañas futuras aunque algunas se completen en fases posteriores.

### FASE 5:
- Catálogo de programas y categorías (plantillas base, antes de convertirse en viajes activos):
    - Biblioteca de programas: listado de destinos con duración, rango de edad/curso y categoría (Aventura, Idiomas, Cultural, etc.).
    - Acción de duplicar un programa existente para crear una nueva temporada o adaptarlo a un nuevo centro/grupo, generando un nuevo viaje activo a partir de él.
    - Gestión de categorías de viaje: CRUD con icono, nombre, descripción y estado (Activo/Oculto en el portal público).
    - Tarjetas de estadísticas de categorías: total de categorías, categoría más popular, categorías activas y categorías archivadas.
    - Tabla de categorías con filtro por estado, ordenación, número de viajes activos vinculados a cada una, y acciones de editar, ver y archivar.
- Regla funcional:
    - Los programas son plantillas base; los viajes activos son instancias reales operables en fechas concretas.

### FASE 6:
- Ficha de viaje > pestaña Información:
    - Datos generales del viaje: nombre, destino(s), fechas de inicio y fin, duración, descripción, categoría, estado (Activo/Completo/En venta), plazas totales.
    - Validación de que las fechas no se solapen de forma incoherente con la disponibilidad configurada.
    - Botones `Ver itinerario` y `Exportar` (ficha del viaje en PDF).
- Restricción estructural:
    - No introducir aquí un campo de precio simple que contradiga el módulo de Tarifas de la FASE 10.

### FASE 7:
- Ficha de viaje > pestaña Itinerario (Creador de itinerario):
    - Panel lateral `Días del viaje` con navegación entre jornadas, contador de días totales y botón `Añadir día`.
    - Por cada día: fecha, título del día, descripción corta y botón de editar.
    - Timeline vertical de actividades con icono según tipo (transporte, comida, cultura/actividad, alojamiento), hora exacta, título y descripción extendida.
    - Bloques de `Nota importante` destacados dentro de una actividad para información crítica (ej. puntualidad, qué incluye la entrada).
    - Botón `Añadir actividad` al final de cada día, con selector de tipo (Transporte, Comida, Actividad, Alojamiento).
    - Botones `Guardar borrador` y `Publicar itinerario` (el itinerario publicado es el que se muestra en la parte pública).
- Restricción funcional:
    - Esta fase solo construye el itinerario del viaje. No debe introducir lógica de reserva por slots ni agendas horarias de citas.

### FASE 8:
- Ficha de viaje > pestaña Transporte (gestión de transporte y asientos):
    - Selector de vehículos en pestañas (Bus 1, Bus 2, etc.) con opción de añadir nuevos.
    - Mapa interactivo de asientos del autobús, distinguiendo por color los asientos libres y los ocupados, con selección de asiento al asignar un viajero.
    - Estadísticas de ocupación por vehículo: plazas totales, libres y ocupadas, junto a fechas de inicio y fin de trayecto.
    - Listado de pasajeros asignados a cada bus, con nombre, número de plaza y estado de su documentación.
- Regla funcional:
    - La ocupación de asientos debe respetar unicidad y no permitir duplicidades.

### FASE 9:
- Ficha de viaje > pestaña Alojamiento:
    - Listado de hoteles/alojamientos previstos para el viaje, con imagen, nombre, categoría (estrellas), ubicación y enlace a `Ver más`.
    - Asignación de habitaciones a viajeros (número de habitación, tipo de habitación, ocupantes).
    - CRUD de alojamientos vinculados al viaje.
- Regla funcional:
    - El alojamiento público y privado debe alimentarse siempre desde esta fase, nunca desde contenido estático en la landing.

### FASE 10:
- Ficha de viaje > pestaña Tarifas (planes de pago):
    - Grupos de pago: `Por defecto` activo, y grupos opcionales adicionales según edad del viajero (`Niños`, `Senior`) que se pueden activar/desactivar, más la opción de `Añadir grupo` personalizado.
    - Por cada grupo de pago, listado de pagos secuenciales (ej. Reserva de plaza, 2º pago, Pago final) con: nombre del concepto, fecha máxima de pago e importe, con acciones de editar y eliminar.
    - Botón `Nuevo pago` para añadir más plazos dentro de un grupo.
    - Esta configuración de tarifas es la que alimenta la tabla de `Fechas a recordar` que se muestra en la parte pública del viaje.
- Regla funcional:
    - Este es el único modelo válido de precio del viaje.

### FASE 11:
- Ficha de viaje > pestaña Viajeros (listado maestro de pasajeros del viaje):
    - Tarjetas de estadísticas: total de viajeros, confirmados, pendientes y lista de espera.
    - Buscador por nombre/DNI, filtro por estado de pago y filtro por curso/grupo.
    - Tabla de viajeros con: nombre y DNI, colegio/grupo y curso, estado de pago (Pagado, Pendiente, Vencido), estado de documentación (Completa, Pendiente, En revisión), asignación de bus y habitación, y acciones (ver ficha, editar, eliminar).
    - Botones `Añadir viajero` y `Exportar lista`.
    - Paginación de la tabla.
    - Ficha individual del viajero con todos sus datos: nombre completo, DNI, fecha de nacimiento, sexo, email, teléfono, teléfono de contacto de emergencia, curso/colegio, historial de pagos y documentación adjunta.
- Regla funcional:
    - Este módulo representa la verdad operativa de pasajeros por viaje.

### FASE 12:
- Ficha de viaje > pestaña Documentación:
    - Gestión de documentos requeridos por viaje (autorización de padres/tutores, seguro, ficha médica, DNI/pasaporte).
    - Estado de cada documento por viajero (Completa, Pendiente, En revisión) sincronizado con lo mostrado en la pestaña Viajeros.
    - Posibilidad de subir/revisar/rechazar documentos desde el dashboard.
- Regla funcional:
    - El estado agregado de documentación del viajero debe derivarse de sus documentos asociados.

### FASE 13:
- Dentro del panel de administración:
    - Gestión del blog: CRUD de artículos, con imagen destacada, categoría de blog (con CRUD de categorías propio) y editor de texto WYSIWYG.
    - Todos los campos de texto grandes del dashboard deben tener incluido un editor WYSIWYG para hacer agradable la edición (por ejemplo: `https://xdsoft.net/jodit/`).
    - Copiar la librería dentro del proyecto (descargarla del CDN, no enlazarla en caliente):
      - `https://cdnjs.cloudflare.com/ajax/libs/jodit/4.7.6/es2021/jodit.min.css`
      - `https://cdnjs.cloudflare.com/ajax/libs/jodit/4.7.6/es2021/jodit.min.js`
    - Activar/desactivar la sección de blog en la parte pública desde configuración.
- Regla técnica:
    - Las dependencias externas de edición deben quedar locales dentro del proyecto.

### FASE 14:
- Dentro del panel de administración:
    - Gestión de preguntas frecuentes (FAQ), con CRUD de pregunta/respuesta y posibilidad de reordenarlas.
    - Activar/desactivar la sección de FAQ en la parte pública.

### FASE 15:
- Dentro del panel de administración:
    - Gestión de temas visuales con 4 plantillas diferentes; al seleccionar una se activa automáticamente en la parte pública.
    - Botón `¿Quieres un diseño personalizado? Pídemelo aquí` enlazando a una url de contacto configurable.
    - Los temas se guardarán en una carpeta `themes` para poder modificarlos o añadir nuevos de forma sencilla (`plug and play`).
    - Al hacer click en un tema se puede previsualizar (nueva pestaña) con los datos reales ya rellenados por la agencia.
- Regla arquitectónica:
    - Cambiar de tema no cambia rutas, controladores ni lógica; solo cambia la capa visual.

### FASE 16:
- Dentro del panel de administración:
    - Gestión de imágenes: administración de todas las imágenes estáticas que aparecen en la parte pública (los temas visuales traen imágenes de stock de `tema-visual-base` por defecto, pero las imágenes subidas por la agencia tienen prioridad de renderizado).
- Regla funcional:
    - Debe existir fallback a imágenes de stock mientras la agencia no cargue imágenes propias.

### FASE 17:
- Dentro del panel de administración:
    - Gestión de disponibilidad y calendario de salidas: calendario visual con las fechas de salida de cada viaje/programa, útil para detectar solapamientos y planificar próximas temporadas.
    - Casi todas las secciones de la web pública serán activables/desactivables (blog, FAQ, sistema de reservas, etc.) porque habrá agencias que no quieran usarlas todas.
- Regla funcional:
    - La disponibilidad es base para la reserva pública y para la detección de conflictos de operación.

### FASE 18:
- Dentro del panel de administración:
    - Sección de configuración de `Frases públicas`: todos los strings o frases que aparecen por defecto en los temas visuales, para que la agencia los pueda personalizar.
    - Sección de configuración de redes sociales, con los enlaces que aparecerán en la parte pública.
    - Sección de `Email y notificaciones`: email de Gmail para enviar avisos de nuevas inscripciones/reservas con PHPMailer o el mailer de Laravel, con mini tutorial de cómo obtener las credenciales.
    - En el header del dashboard, al hacer click en el nombre/avatar del administrador: sección de perfil privado (nombre, email interno, teléfono interno, cambio de contraseña, subida de avatar).
    - Buscador general del header que lleve a una página de resultados que filtre coincidencias entre viajes, viajeros, blog, FAQ, etc.
    - Botón de `Ayuda` del header con hover, que lleve a un tutorial general en texto explicando de forma sencilla cómo funciona TOUR UP completo.
- Regla evolutiva:
    - Esta fase será ampliada por la FASE 23 para incorporar automatizaciones y WhatsApp sin romper la configuración base de email.

### FASE 19:
- Parte pública > Homepage y páginas institucionales:
    - Basarse en la estructura de `tema-visual-base` (que ya cubre todas las secciones que una web de agencia de turismo podría necesitar); quedarse con las secciones necesarias para nuestro caso.
    - Homepage con hero de conversión, resumen de servicios/experiencias, categorías de viaje destacadas, viajes/próximas salidas destacadas, testimonios/confianza y llamada a la acción.
    - Sección `Sobre nosotros`: historia de la agencia, equipo y elementos de confianza (certificaciones, años de experiencia).
    - Sección `Servicios` y `Categorías de viaje` (listado filtrable por categoría, alimentado por la gestión de programas/categorías del dashboard).
    - Sección de contacto con formulario, teléfono, email, dirección y mapa.
    - Botón flotante de WhatsApp de soporte.
    - Footer con enlaces legales (Privacidad, Términos, Seguro de viaje, Contacto), redes sociales y copyright.
    - Si la agencia elige un tema en modo `landing`, toda la web es una sola página con scroll suavizado; si no, cada sección vive en su propia url.
- Reglas de esta fase:
    - Debe existir un único controlador semántico para estas páginas.
    - El equipo y los datos institucionales deben venir de datos reales, no de placeholders.

### FASE 20:
- Parte pública > Ficha de viaje / landing de expedición (página de conversión de un viaje concreto):
    - Navegación superior fija (sticky) con logo, accesos rápidos a anclas (Itinerario, Pagos, Alojamiento) y CTA principal `Inscribirse`.
    - Hero: título dinámico con el nombre del viaje y año, subtítulo indicando a quién va dirigido, CTA principal de inscripción y barra de confianza (Gestión 100% online, Seguridad total, Pago fraccionado).
    - Columna principal: resumen ejecutivo (destinos y fechas/duración), bloques de valor (`Por qué viajar con nosotros`: seguros, financiación), tabla de `Fechas a recordar` con el plan de pagos (alimentada por la pestaña Tarifas del dashboard), imagen destacada del destino, e itinerario interactivo en formato acordeón día a día (alimentado por la pestaña Itinerario del dashboard) con horarios, descripciones y notas importantes.
    - Columna lateral: módulos de inscripción rápida redundantes (siempre visibles al hacer scroll), lista de alojamientos previstos (imagen, nombre, categoría, ubicación), módulo de beneficios incluidos (seguridad 24h, guía local, todo incluido), enlaces útiles (`Te interesa`: clima del destino, FAQ, consejos de seguro) y aviso sobre lista de espera y plan de pagos.
    - Todos los CTA de `Inscribirse` / `Reservar` de esta ficha (hero, sidebar, botones flotantes) abren el mismo flujo de reserva paso a paso, detallado en la FASE 21.
- Reglas de esta fase:
    - Debe existir una sola ruta semántica de ficha de viaje.
    - Los 4 temas visuales renderizan la misma entidad y la misma lógica.

### FASE 21:
- Parte pública > Sistema de reservas / inscripción (flujo de reserva paso a paso).

Este flujo es el mismo para los 4 temas visuales seleccionables: la lógica y los datos (motor de reservas) son compartidos y viven en el backend de Laravel; lo único que cambia entre temas es la piel visual (colores, tipografía, si el asistente se muestra como modal con pasos, como página dedicada `/reservar/{viaje}` con pasos, o como wizard tipo acordeón dentro de la misma ficha del viaje). El agente debe implementar el flujo una sola vez a nivel de lógica/controlador y solo adaptar la vista (blade/partial) por cada tema.

    - **Paso 0 — Origen:** el usuario llega desde el CTA `Inscribirse` de la ficha de viaje (FASE 20). El viaje, su categoría y su itinerario publicado ya quedan fijados como contexto de toda la reserva (no se puede cambiar de viaje a mitad del flujo).

    - **Paso 1 — Selección de fecha/salida:**
        - Si el programa solo tiene una fecha de salida (caso típico de viaje escolar cerrado), este paso se omite automáticamente y se muestra la fecha ya fijada a modo de confirmación.
        - Si el programa tiene varias salidas disponibles (mismo programa, distintas temporadas), se muestra un calendario/selector de fechas con las salidas disponibles, generado a partir de la disponibilidad configurada en el dashboard (FASE 17) y de las plazas restantes de cada instancia de viaje (FASE 4).
        - Las fechas de salida sin plazas libres aparecen bloqueadas/deshabilitadas en el calendario, igual que ocurre con los asientos ocupados en el mapa de autobús (FASE 8).

    - **Paso 2 — Número de plazas a reservar:**
        - Selector de cuántos viajeros se van a inscribir en esta misma reserva (1 por defecto para viajes escolares individuales; permite más de 1 en viajes familiares o de grupo).
        - Validación en tiempo real contra las plazas disponibles del viaje (no se puede reservar más plazas de las que quedan libres); si no quedan suficientes plazas, se ofrece automáticamente la opción de anotarse en `Lista de espera` (mismo indicador que aparece en las estadísticas de la pestaña Viajeros, FASE 11).

    - **Paso 3 — Datos del/los viajero(s):**
        - Formulario por cada plaza reservada con: nombre y apellidos, DNI, fecha de nacimiento, sexo, email y teléfono.
        - Si el viajero es menor de edad, se añade un bloque adicional con los datos del tutor/contacto (nombre, teléfono, email) y checkbox de autorización.
        - Validación de formato de DNI y de que el teléfono solo contenga números.
        - Cada viajero introducido aquí es exactamente el mismo registro que luego aparecerá en la ficha del viajero del dashboard (FASE 11), no se duplican modelos ni tablas.

    - **Paso 4 — Selección de grupo de tarifa y plan de pagos:**
        - Si el viaje tiene más de un grupo de tarifa activo (`Por defecto`, `Niños`, `Senior`, o grupos personalizados — ver FASE 10), el usuario elige el que le corresponde; si solo hay un grupo activo, se aplica directamente sin preguntar.
        - Se muestra el desglose de pagos de ese grupo (Reserva de plaza, 2º pago, Pago final) con sus importes y fechas máximas, exactamente igual a como está configurado en la pestaña Tarifas del viaje (FASE 10) y a como se muestra en la tabla `Fechas a recordar` de la ficha pública (FASE 20).

    - **Paso 5 — Opciones adicionales (si el viaje las tiene configuradas):**
        - Preferencia de habitación/alojamiento si el viaje permite elegir tipo de habitación (dato ligado a la pestaña Alojamiento, FASE 9).
        - Preferencia de menú especial (vegetariano, celíaco, alergias) si el viaje lo contempla en su itinerario/notas.

    - **Paso 6 — Resumen y confirmación:**
        - Pantalla-resumen con todos los datos introducidos (fecha/salida, viajero(s), tarifa elegida, plan de pagos) antes de confirmar, con opción de volver atrás a cualquier paso anterior para corregir.
        - Checkbox obligatorio de aceptación de política de privacidad y condiciones del viaje.
        - Botón final `Confirmar reserva` (nunca usar alert/confirm nativos; toda validación y feedback debe ser visual en el DOM, según las preferencias de código).

    - **Paso 7 — Primer pago (Reserva de plaza):**
        - Se solicita el pago del primer plazo definido en el plan de pagos elegido (Paso 4).
        - Si la agencia no tiene pasarela de pago online configurada, se muestra la alternativa de pago manual (transferencia/efectivo) y la reserva queda con estado de pago `Pendiente` hasta que la agencia lo marque como `Pagado` desde el dashboard.
        - En caso de pago online, al confirmarse el pago se actualiza automáticamente el estado a `Pagado` para ese primer plazo.

    - **Paso 8 — Confirmación final:**
        - Pantalla/modal de `Reserva confirmada` con número de referencia, resumen y próximos pasos (qué documentación falta por subir, cuándo vence el siguiente pago).
        - Envío automático de email de confirmación al viajero/tutor y de notificación de `nueva reserva` a la agencia (usando la configuración de email de la FASE 18).

    - **Conexión con los módulos del panel de administración** (debe cumplirse siempre, en los 4 temas):
        - **Viajes:** la reserva descuenta plazas del contador `Viajeros inscritos X/Y` y actualiza la recaudación del viaje (FASE 4) en tiempo real.
        - **Programas y categorías:** el viaje reservado siempre pertenece a la categoría configurada en el catálogo de programas (FASE 5); la landing de reserva hereda el nombre, rango de edad y categoría del programa.
        - **Itinerarios:** el resumen del viaje mostrado en los pasos 1 y 6 usa el itinerario ya publicado desde el dashboard (FASE 7); si el itinerario está en borrador, no se muestra en la parte pública.
        - **Transporte:** si el viaje ya tiene autobuses configurados y el dashboard exige asignación en el momento de la reserva, se asigna automáticamente el siguiente asiento libre del bus con más plazas disponibles (FASE 8); si no, la asignación de bus/asiento se hace después, manualmente, desde el dashboard.
        - **Alojamiento:** las preferencias de habitación del Paso 5 quedan como solicitud pendiente de asignación definitiva de habitación en la pestaña Alojamiento (FASE 9).
        - **Tarifas:** el plan de pagos mostrado y cobrado en los pasos 4 y 7 es siempre el vigente en la pestaña Tarifas del viaje (FASE 10); si la agencia lo modifica después, no afecta a reservas ya confirmadas, solo a las nuevas.
        - **Pasajeros/Viajeros:** cada viajero del Paso 3 crea (o completa, si ya existía por DNI) su ficha en la gestión de viajeros del viaje (FASE 11), con estado inicial `Pendiente` hasta que se confirme el primer pago.
        - **Documentación:** al confirmar la reserva se generan automáticamente las entradas de documentación pendientes de ese viajero (FASE 12), en estado `Pendiente`, listas para que suba o la agencia revise los documentos.
        - **Notificaciones por email:** el aviso de nueva reserva a la agencia y la confirmación al viajero usan la configuración de email de la FASE 18.
        - **Lista de espera:** si el viaje está completo, la reserva se guarda igualmente pero con estado `Lista de espera`, reflejado en las estadísticas de la pestaña Viajeros (FASE 11) y en el listado de viajes (FASE 4).
- Reglas de esta fase:
    - Toda la lógica vive en un único `BookingService`.
    - Los 4 temas solo adaptan la vista.
    - Esta fase no debe degradarse nunca a un sistema de citas.

### FASE 22:
- Parte pública > Blog, FAQ y utilidades:
    - Blog público con artículos paginados y filtrado por categoría (visible solo si está activado desde el dashboard).
    - Sección de preguntas frecuentes públicas (visible solo si está activada desde el dashboard).
    - Botones de redes sociales en el footer, según lo configurado en el dashboard.

### FASE 23:
- Dentro del panel de administración - Módulo CRM de ventas centrado en el ciclo de vida del viajero.
- Objetivo: gestionar la relación con el cliente de forma transversal a los viajes, sin duplicar ni modificar el flujo de reserva actual (FASE 21), que sigue siendo la única fuente de verdad para crear/actualizar viajeros.
- Entidad nueva `Cliente`: perfil único por DNI/email/teléfono que puede estar vinculado a uno o varios `Viajeros` a lo largo de distintos viajes y temporadas.
- Vinculación automática: cada vez que el flujo de reserva (FASE 21) crea o actualiza un Viajero, el sistema busca coincidencia por DNI o email en Cliente; si existe, lo vincula; si no existe, lo crea. Esta vinculación ocurre en segundo plano y nunca bloquea ni modifica el proceso de reserva existente.
- Pipeline de ventas (kanban) con las etapas: `Lead nuevo`, `Cotizado`, `En negociación`, `Reserva iniciada`, `Confirmado/Cliente`, `En viaje`, `Postventa/Lealtad`.
- Cada Cliente tiene una etapa actual del pipeline, un agente asignado y fecha de último contacto.
- Cambios de etapa automáticos según eventos ya existentes en el sistema: crear un Viajero desde una reserva mueve al Cliente a `Confirmado`, alcanzar la fecha de inicio del viaje lo mueve a `En viaje`, alcanzar la fecha de fin lo mueve a `Postventa`.
- Cambios de etapa manuales disponibles para el agente (arrastrar tarjeta en el tablero, o selector de estado).
- Registro de interacciones: cada comunicación con un Cliente (email, WhatsApp, llamada, nota interna) queda guardada con canal, tipo (entrante/saliente), contenido y fecha, visible en una bandeja unificada por cliente.
- Panel kanban en el dashboard: tarjetas con nombre del cliente, viaje de interés, valor potencial y último contacto, reutilizando el mismo componente de tarjetas de estadísticas y badges de estado ya usados en el resto del panel.
- Ficha de Cliente: datos de contacto, etapa actual, historial de interacciones, viajeros asociados, viajes realizados y notas del agente.
- Motor de notificaciones multicanal: sustituye/amplía la configuración de `Email y notificaciones` de la FASE 18 para poder disparar, según reglas configurables, mensajes automáticos por email y por WhatsApp (API de WhatsApp Business) en los siguientes momentos: nueva reserva, pago pendiente próximo a vencer, documentación faltante, recordatorio pre-viaje, encuesta de satisfacción post-viaje, y seguimiento de lead sin respuesta.
- Configuración de WhatsApp Business API en el panel de Sistema, siguiendo el mismo patrón de mini-tutorial ya usado para las credenciales de Gmail (FASE 18).
- Métricas del CRM: tasa de conversión por etapa, tiempo medio de primera respuesta, tasa de recuperación de reservas abandonadas y tasa de clientes recurrentes, mostradas como tarjetas de estadísticas en la home del módulo CRM.
- Esta fase no debe modificar el modelo de datos de `bookings`, `travelers`, `pricing_groups` ni la lógica de `BookingService`; solo se añade una capa de datos y de comunicación por encima, mediante relaciones y observadores/eventos de Laravel.
- Reglas de esta fase:
    - El CRM se integra sobre el ciclo de vida del viajero existente.
    - No duplica viajeros.
    - No reemplaza reservas.
    - Añade trazabilidad comercial, automatización y seguimiento.

---

## Ideas de optimización a valorar (indicar en el plan de implementación):
- Reutilizar el mismo componente de `timeline con iconos por tipo de actividad` tanto en el dashboard (creador de itinerario) como en la parte pública (itinerario de la ficha del viaje), para no duplicar lógica ni marcado.
- Reutilizar el mismo componente de tabla con filtros/paginación para viajeros, categorías, blog y clientes CRM.
- Los badges de estado (`Activo/Completo/En venta`, `Pagado/Pendiente/Vencido`, `Completa/Pendiente/En revisión`, etapas del CRM) deben ser un único componente parametrizable por color y texto.
- El flujo de reserva (FASE 21) debe tener su lógica de negocio (validación de plazas, cálculo de plan de pagos, creación de viajero/documentación) centralizada en un único servicio/controlador de Laravel, e independiente de la vista; cada uno de los 4 temas visuales solo debe aportar su propia plantilla/partial para pintar los mismos pasos, sin duplicar validaciones ni reglas de negocio por tema.
- El CRM de la FASE 23 debe apoyarse en observers, listeners, jobs y servicios desacoplados en lugar de meter lógica comercial directamente dentro de `BookingService`.

---

## Stack de tecnología:

- HTML5.
- CSS3 nativo, basándose en el código de la carpeta `tema-visual-base`.
- JavaScript nativo, sin frameworks.
- Lenguaje de programación backend: PHP.
- Framework para PHP: Laravel.
- Base de datos MySQL / MariaDB.
- Aplicación web monolítica con la arquitectura de Laravel.

---

## Preferencias generales:
- Todos los textos visibles en la web deben estar en español y el agente también debe comunicarse conmigo en español.
- Usa todas las imágenes de stock de `tema-visual-base` para tener algo de imágenes en las plantillas. Luego estas imágenes podrán editarse o cambiarse en una sección del dashboard.

---

## Preferencias de diseño para la parte pública:
- Basarse en el diseño de la carpeta `tema-visual-base`.
- Los 4 temas deben sentirse distintos visualmente, pero compartir la misma arquitectura funcional.

---

## Preferencias de diseño para la parte privada:
- Crear un diseño de dashboard visualmente agradable, sencillo de entender y muy intuitivo para un director de agencia de turismo que no es un usuario avanzado de informática.
- Tener en cuenta todas las funcionalidades e inspirarse mucho en la carpeta `dashboard-design` que tienes en la raíz del proyecto.
- Dentro de esa carpeta hay un prototipo de diseño, puede tener fallos, pero la idea y el concepto es muy similar a lo que necesitamos, con diferentes pantallas para inspirarte en ellas e intentar imitarlas y mejorarlas.
- En el diseño hay ciertos datos que están mal, como el nombre del CMS o algunos textos en inglés; ten criterio y ten muy en cuenta las funcionalidades descritas en este documento en lugar de copiar literalmente esos detalles.

---

## Preferencias de estilos:
- Colores: cada tema visual puede tener los suyos; `tema-visual-base` ya tiene los suyos, y los del panel de administración los tienes en la carpeta `dashboard-design`.
- Uso de medidas en rem, usando un font-size base de 10px.
- Uso de HTML5 y CSS3 nativo.
- Uso de buenas prácticas de maquetación CSS, y si es necesario, usar flexbox y CSS grid.
- Que la webapp sea responsive, tanto en la parte pública como en el dashboard.

---

## Preferencias de código:
- No mezclar el código CSS entre los diferentes componentes; el CSS de cada tema visual y el del dashboard debe estar separado.
- Si puedes tenerlo en una carpeta de CSS, como en `tema-visual-base`, mejor.
- HTML debe ser semántico.
- La parte pública debe estar completamente optimizada para SEO (a nivel de código y buenas prácticas).
- Usa siempre `let` o `const`, y no uses nunca `var`.
- No uses `alert`, `confirm` ni `prompt`; todo el feedback debe ser visual en el DOM.
- Toda alerta o ventana modal que aparezca debe tener el mismo estilo que la web.
- No uses `innerHTML`; todo el contenido debe insertarse con `appendChild` o creando previamente un elemento con `document.createElement`.
- Cuidado con olvidar prevenir el default en los eventos `submit` o `click`.
- Prioriza el código legible y mantenible.
- Prioriza que el código sea sencillo de entender.
- Si el agente duda, debe revisar las especificaciones del proyecto y, si no encuentra respuesta, preguntar al usuario.

---

## Estructura de archivos:
- carpeta `tema-visual-base` (es una maquetación web de una de las plantillas o temas visuales de la parte pública).
- carpeta `dashboard-design` (contiene imágenes con un diseño provisional del dashboard).
- `agent.md` (fuente de verdad funcional: qué hay que construir).
- `agent2.md` (versión ampliada y reorganizada por fases, incluyendo reglas preventivas y CRM).
- `plan-implementacion.md` (fuente de verdad de orden: cómo y en qué secuencia se construye).
- `MAP.md` (mapa estructural del repositorio: módulos, carpetas y archivos principales; sin lógica de negocio).
- `project-map.md` (fuente de verdad de estado real del sistema: qué está ya implementado).
- `tareas.md`, `prompts.md`, `fixes.md` (bitácoras de trabajo, ver `Otras consideraciones`).
- carpeta para el proyecto de Laravel (usar la estructura de archivos más adecuada para proyectos de PHP y Laravel).

---

## Metodología de trabajo (SDD - Spec Driven Development):

Este proyecto se desarrolla en modalidad **SDD híbrido**: este fichero contiene tanto reglas y límites concretos (SDD estricto) como contexto descriptivo de cómo debe quedar cada pantalla (vibe coding descriptivo), para minimizar la ambigüedad sin perder fluidez.

- Primero se define exactamente qué debe hacer cada funcionalidad; después el agente escribe el código.
- No se escribe código sin haber consultado antes la especificación de la fase correspondiente.
- Jerarquía de conflicto entre documentos, de mayor a menor prioridad:
    1. `agent2.md` → qué hay que construir (fuente de verdad funcional actualizada).
    2. `agent.md` → especificación funcional anterior, útil como referencia histórica si no contradice `agent2.md`.
    3. `plan-implementacion.md` → cómo y en qué orden hay que construirlo (orden de ejecución obligatorio).
    4. `project-map.md` → estado actual real del sistema.
- Si hay discrepancia entre el código real y lo anotado en `project-map.md`, el código tiene siempre prioridad, y `project-map.md` debe corregirse para reflejar la realidad.
- Antes de empezar cualquier tarea o fase nueva, consultar primero `project-map.md` para saber qué existe ya y evitar repetir trabajo o romper algo hecho.
- `MAP.md` es distinto de `project-map.md`: `MAP.md` es solo un mapa estructural (carpetas, módulos y archivos principales) generado explorando el repositorio, sin entrar en lógica de negocio, sin modificar código y sin hacer refactors.
- Cuando el proyecto crezca, evitar recargar el contexto: apoyarse en `MAP.md` y `project-map.md` en lugar de releer todo el repositorio, y respetar los cortes de fase/feature para no mezclar contexto de una fase con otra.

---

## Fases del desarrollo:

- Estudia las características del proyecto (`agent2.md`).
- Crea la base de datos.
- Antes de cada fase, crea o actualiza `plan-implementacion.md` con el detalle de esa fase si aún no existe, y consulta `project-map.md` para conocer el estado real del sistema antes de empezar.
- Sigue las fases del desarrollo especificadas en la sección de funcionalidades de `agent2.md`, en el orden exacto marcado por `plan-implementacion.md`, sin saltarte ninguna y sin replantear el plan.
- Mientras trabajas en una fase: no entres en modo de planificación, análisis global ni rediseño de fases ya cerradas; mantente dentro del alcance de la fase actual y no modifiques funcionalidades de otras fases salvo que exista una dependencia directa e inevitable.
- No pruebes tú mismo el resultado de forma exhaustiva ni asumas que ya funciona: implementa la fase y avísame en cuanto esté lista para que yo la valide.
- Solo pregúntame si existe un bloqueo técnico real que impida continuar; si hay ambigüedad menor, aplica la regla de `decisión más simple que no rompa nada` y sigue adelante.
- Para de trabajar después de cada fase, para poder probar lo desarrollado, corregir y mejorar algo si es necesario, y poder continuar con la siguiente.
- Al finalizar cada fase:
    - Resume brevemente lo implementado.
    - Marca cada sub-tarea de esa fase como completada en `tareas.md`.
    - Sincroniza `project-map.md` por completo con el estado real del sistema: cambios realizados, nuevos módulos/rutas/entidades, decisiones técnicas tomadas e impacto en el resto del sistema.
    - Marca la fase como completada en `project-map.md`, en `tareas.md` y en `plan-implementacion.md`.
- Si crees que se puede optimizar algo (estructura, reutilización de componentes, rendimiento), indícamelo en `plan-implementacion.md`, no lo implementes por tu cuenta sin decírmelo primero.

---

## Otras consideraciones:
- Guarda el plan de implementación en un fichero `plan-implementacion.md` en la raíz del proyecto (fuente de verdad del orden de ejecución de las fases).
- Genera y mantén un fichero `MAP.md` en la raíz del proyecto con la estructura del repositorio (módulos, carpetas y archivos principales), sin entrar en lógica de negocio profunda, sin modificar código y sin hacer refactors; actualízalo solo cuando la estructura general del proyecto cambie de forma relevante.
- Genera y mantén un fichero `project-map.md` en la raíz del proyecto con el estado real del sistema (qué fases y funcionalidades están implementadas, qué módulos/rutas/entidades existen y qué decisiones técnicas se han tomado); actualízalo al finalizar cada fase, como se indica en `Fases del desarrollo`.
- Guarda las tareas y su estado en un fichero `tareas.md` en la raíz del proyecto, y cada vez que se cumpla una, modifícalo para actualizarla.
- Guarda cada uno de los prompts nuevos que haga en un fichero `prompts.md` en la raíz del proyecto (todos ordenados uno detrás de otro dentro del fichero); cada vez que yo haga un prompt aparte, guárdalo ahí.
- Una vez el proyecto esté en marcha (fases ya completadas), los cambios, correcciones o mejoras puntuales sobre funcionalidad ya existente se piden y registran en un fichero `fixes.md` en la raíz del proyecto (en lugar de crear nuevas fases), siguiendo el mismo formato de una entrada por petición, ordenadas igual que `tareas.md` y `prompts.md`.

---

## Modo implementación:
- Solo código, mínimos comentarios; el código ya debe ser autoexplicativo.
- No expliques qué hace el código en el chat del agente; simplemente avísame cuando hayas terminado (modo `caveman`).
- Responde en español si preguntas, pero en prompts usa inglés/español libremente.
- Si hay ambigüedad, asume la decisión más simple que no rompa algo.
