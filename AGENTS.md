# Backlog de remediación técnica — CMS-tourup

Documento de backlog técnico para remediar los hallazgos del code review del repositorio `devCreceDigital/CMS-tourup`. La estructura prioriza primero errores que pueden romper producción, luego riesgos altos de seguridad e integridad de dominio, y finalmente mejoras de robustez, concurrencia y performance.

## Criterios de priorización

Se usan tres niveles de prioridad:

- **P0**: rompe producción, persistencia, reservas o renderización crítica.
- **P1**: seguridad, integridad de negocio, consistencia de dominio o UX severamente degradada.
- **P2**: robustez, concurrencia, optimización o deuda técnica relevante.

Se recomienda ejecutar el backlog en cuatro PRs lógicos para reducir riesgo de despliegue y facilitar rollback por módulo: búsqueda/estados/slugs, booking core, themes y wizard, y hardening de seguridad/dominio.

## Tickets

### TASK-001 — Corregir búsqueda de viajeros por columna inexistente

- **Prioridad:** P0
- **Archivo principal:** `app/Http/Controllers/SearchController.php`
- **Problema:** la búsqueda usa `full_name`, pero la tabla `travelers` tiene `first_name` y `last_name`; esto causa error SQL seguro al ejecutar la consulta.
- **Objetivo:** permitir búsqueda funcional por nombre, apellido o nombre completo sin romper la consulta.
- **Remediación propuesta:**
  - Reemplazar `where('full_name', ...)` por búsqueda compuesta sobre `first_name` y `last_name`.
  - Preferir `CONCAT(first_name, ' ', last_name)` si la UX requiere búsqueda por nombre completo.
  - Mantener scoping por agencia si el módulo ya opera en contexto multi-tenant.
- **Criterios de aceptación:**
  - Buscar por nombre devuelve viajeros correctos.
  - Buscar por apellido devuelve viajeros correctos.
  - Buscar por nombre completo devuelve coincidencias esperadas.
  - La búsqueda no lanza SQL error con resultados vacíos.
- **Tests sugeridos:** feature test de búsqueda y test con datos vacíos.
- **Dependencias:** ninguna.

### TASK-002 — Unificar estados válidos de viajes entre controlador y base de datos

- **Prioridad:** P0
- **Archivo principal:** `app/Http/Controllers/TripController.php`
- **Archivos relacionados:** migración/enum de `trips`, vistas de formulario, factories, seeds.
- **Problema:** el validador acepta estados que la BD no soporta, provocando error al guardar.
- **Objetivo:** tener una sola fuente de verdad para los estados de viaje.
- **Remediación propuesta:**
  - Crear enum PHP o clase de constantes `TripStatus`.
  - Reemplazar validaciones ad hoc por `Rule::in(...)` usando esa fuente central.
  - Revisar formularios, seeds, badges, filtros y lógica de actualización para los mismos valores.
- **Criterios de aceptación:**
  - Crear o editar viaje con estados permitidos persiste correctamente.
  - Estados no permitidos responden 422 y no llegan a la BD.
  - UI y filtros reflejan exactamente los valores válidos.
- **Tests sugeridos:** unit test de enum y feature tests create/update.
- **Dependencias:** puede coordinarse con TASK-011.

### TASK-003 — Corregir autoasignación de asientos con comparación estricta

- **Prioridad:** P0
- **Archivo principal:** `app/Services/BookingService.php`
- **Problema:** la lógica compara enteros con strings tipo `1A`, y la coerción laxa de PHP puede dar coincidencias falsas.
- **Objetivo:** asignar asientos correctamente según códigos reales y disponibilidad.
- **Remediación propuesta:**
  - Normalizar representación de asientos como strings (`1A`, `1B`, etc.).
  - Reemplazar `in_array` por comparación estricta `in_array($seatCode, $occupiedSeats, true)`.
  - Extraer la lógica a una clase o método aislable para testear mejor.
- **Criterios de aceptación:**
  - El sistema encuentra el primer asiento libre real.
  - Un valor `1` no coincide con `1A`.
  - Si no hay asientos libres, devuelve estado controlado sin asignación inválida.
- **Tests sugeridos:** unit tests de asignación de asientos y escenarios de disponibilidad total/parcial.
- **Dependencias:** recomendable coordinar con TASK-019.

### TASK-004 — Preservar sesión de booking si falla validación

- **Prioridad:** P0
- **Archivo principal:** `app/Http/Controllers/BookingController.php`
- **Problema:** `session()->pull('booking_data')` elimina el estado antes de validar, por lo que un error de validación destruye el progreso de la reserva.
- **Objetivo:** conservar el estado del wizard hasta la confirmación exitosa.
- **Remediación propuesta:**
  - Cambiar `pull()` por `get()` al inicio del flujo.
  - Limpiar sesión solo al completar satisfactoriamente la confirmación.
  - Evaluar encapsular el manejo de sesión del wizard en un helper o service dedicado.
- **Criterios de aceptación:**
  - Si la validación falla, la sesión mantiene `booking_data`.
  - Si la reserva se confirma, la sesión se limpia al final.
  - El usuario puede corregir el formulario sin perder el progreso.
- **Tests sugeridos:** feature tests para validación fallida y éxito final.
- **Dependencias:** ninguna.

### TASK-005 — Migrar snapshots JSON a tipo de dato seguro

- **Prioridad:** P0
- **Archivo principal:** migración de tabla de bookings o equivalente.
- **Archivos relacionados:** modelo Eloquent con casts.
- **Problema:** `pricing_group_snapshot` y `extras_snapshot` definidos como `VARCHAR(255)` pueden truncar JSON reales.
- **Objetivo:** garantizar persistencia íntegra de snapshots de reserva.
- **Remediación propuesta:**
  - Migrar columnas a `TEXT` o `JSON` según soporte y uso.
  - Añadir casts `array` en el modelo si se procesan como estructuras.
  - Verificar compatibilidad con datos ya existentes y entorno productivo.
- **Criterios de aceptación:**
  - Se pueden guardar snapshots mayores a 255 caracteres.
  - Los snapshots se leen completos tras persistencia.
  - No hay truncamiento silencioso.
- **Tests sugeridos:** test de persistencia de payload largo y lectura posterior.
- **Dependencias:** coordinar con TASK-004 y TASK-003 por impacto en booking.

### TASK-006 — Blindar acceso a `$agency` en themes

- **Prioridad:** P0
- **Archivos principales:** 32 archivos de theme donde aparece `$agency->active_theme ?? 'ethos_earth'`.
- **Problema:** el acceso a propiedad ocurre antes del coalescing; si `$agency` es null, la vista rompe.
- **Objetivo:** permitir render seguro con o sin agencia cargada.
- **Remediación propuesta:**
  - Sustituir por `optional($agency)->active_theme ?? 'ethos_earth'` o `$agency?->active_theme ?? 'ethos_earth'`.
  - Mejorar arquitectura resolviendo `$activeTheme` una sola vez en controller o view composer.
- **Criterios de aceptación:**
  - Las vistas renderizan con agencia presente.
  - Las vistas renderizan sin agencia sin lanzar excepción.
  - El fallback `ethos_earth` se aplica cuando no existe tema activo.
- **Tests sugeridos:** render tests de vistas críticas y smoke test multi-theme.
- **Dependencias:** ninguna.

### TASK-007 — Reemplazar URLs hardcodeadas en JS por rutas nombradas

- **Prioridad:** P1
- **Archivo principal:** `resources/views/admin/trips/itinerary.blade.php`
- **Problema:** rutas `/panel-agencia/...` hardcodeadas dentro de JavaScript.
- **Objetivo:** desacoplar el front embebido de paths físicos y prefijos cambiantes.
- **Remediación propuesta:**
  - Inyectar URLs con `route()` y serializarlas con `@json()`.
  - Eliminar concatenaciones manuales de strings de ruta.
- **Criterios de aceptación:**
  - El JS usa rutas nombradas provenientes del backend.
  - Un cambio de prefijo o nombre base no obliga a editar strings hardcodeados en la vista.
- **Tests sugeridos:** prueba manual del flujo JS y revisión estática de referencias.
- **Dependencias:** ninguna.

### TASK-008 — Sustituir `addslashes()` por serialización segura a JavaScript

- **Prioridad:** P1
- **Archivo principal:** `resources/views/admin/trips/itinerary.blade.php`
- **Problema:** `addslashes()` no es apropiado para contexto JS embebido.
- **Objetivo:** serializar datos correctamente hacia scripts inline.
- **Remediación propuesta:**
  - Reemplazar por `@json($variable)` o equivalente.
  - Revisar todos los bloques inline con interpolación en JavaScript.
- **Criterios de aceptación:**
  - Los datos embebidos en JS se serializan correctamente.
  - Strings con comillas, saltos de línea o caracteres especiales no rompen el script.
- **Tests sugeridos:** caso con contenido especial y revisión manual de consola.
- **Dependencias:** puede agruparse con TASK-007.

### TASK-009 — Evitar duración incorrecta cuando fechas son nulas

- **Prioridad:** P1
- **Archivo principal:** `resources/views/admin/trips/show.blade.php`
- **Problema:** `Carbon::parse(null)` puede derivar en fecha actual y mostrar duración falsa.
- **Objetivo:** calcular duración solo cuando ambas fechas existen.
- **Remediación propuesta:**
  - Añadir guardas nulas antes del parseo.
  - Mostrar fallback claro si falta una fecha.
- **Criterios de aceptación:**
  - No se muestra duración falsa cuando faltan fechas.
  - Con fechas válidas, la duración es correcta.
- **Tests sugeridos:** test de vista o unit test del helper de duración.
- **Dependencias:** ninguna.

### TASK-010 — Proteger acceso a `spots` en wizard de viajeros

- **Prioridad:** P1
- **Archivo principal:** `resources/views/themes/*/public/booking/step3-traveler-form.blade.php`
- **Problema:** acceso directo a `$data['spots']` sin null check.
- **Objetivo:** evitar error de vista cuando el estado parcial está incompleto.
- **Remediación propuesta:**
  - Usar `data_get($data, 'spots', [])` o validación previa.
  - Replicar la corrección en todos los themes equivalentes.
- **Criterios de aceptación:**
  - La vista no rompe si `spots` no existe.
  - El wizard maneja estado incompleto con fallback controlado.
- **Tests sugeridos:** render test con `spots` presente y ausente.
- **Dependencias:** coordinable con TASK-004.

### TASK-011 — Garantizar unicidad de slug en viajes

- **Prioridad:** P1
- **Archivo principal:** `app/Http/Controllers/TripController.php`
- **Problema:** el slug puede duplicarse y terminar en error de BD.
- **Objetivo:** asegurar unicidad consistente al crear o editar viajes.
- **Remediación propuesta:**
  - Añadir `Rule::unique(...)` en validación.
  - Si el slug es automático, crear helper o servicio de slug único.
  - Verificar índice único en la base de datos.
- **Criterios de aceptación:**
  - Crear viaje con slug existente responde validación amigable.
  - Editar viaje mantiene slug actual sin falso positivo.
  - La BD conserva restricción de unicidad.
- **Tests sugeridos:** feature tests create/update duplicado/no duplicado.
- **Dependencias:** coordinable con TASK-002.

### TASK-012 — Cifrar contraseña de email almacenada en settings

- **Prioridad:** P1
- **Archivo principal:** `app/Http/Controllers/SettingsController.php`
- **Problema:** la contraseña de correo se almacena en texto plano.
- **Objetivo:** proteger secretos sensibles en reposo.
- **Remediación propuesta:**
  - Usar `Crypt::encryptString()` al guardar y desencriptar solo al usar.
  - Evitar exponer el valor real en formularios de edición.
  - Evaluar mover secretos fijos a `.env` si el negocio no requiere edición dinámica.
- **Criterios de aceptación:**
  - El valor persistido en BD no es legible en texto plano.
  - El envío de correo sigue funcionando.
  - Un update parcial no borra la contraseña existente por accidente.
- **Tests sugeridos:** test de cifrado/desencriptado y test funcional de settings.
- **Dependencias:** coordinable con TASK-022.

### TASK-013 — Validar pertenencia del viajero al viaje antes de asignar alojamiento

- **Prioridad:** P1
- **Archivo principal:** `app/Http/Controllers/AccommodationController.php`
- **Problema:** se puede asignar un viajero a una habitación sin verificar que pertenezca al viaje.
- **Objetivo:** mantener integridad de dominio entre viaje, viajero y habitación.
- **Remediación propuesta:**
  - Validar que el viajero pertenezca al `trip` antes de asignarlo.
  - Validar también que la habitación pertenezca al mismo viaje.
  - Mover la invariante a policy, action o service si el flujo se repite.
- **Criterios de aceptación:**
  - No se puede asignar viajero externo al viaje.
  - Sí se puede asignar viajero perteneciente al viaje.
  - La respuesta de error es controlada y no produce corrupción de datos.
- **Tests sugeridos:** feature tests con combinaciones válidas e inválidas.
- **Dependencias:** ninguna.

### TASK-014 — Cambiar duplicación de viaje de GET a POST con CSRF

- **Prioridad:** P1
- **Archivo principal:** `resources/views/admin/trips/show.blade.php`
- **Archivos relacionados:** rutas y controlador de duplicación.
- **Problema:** duplicar viaje es una acción mutante expuesta vía GET.
- **Objetivo:** alinear la operación con semántica HTTP y protección CSRF.
- **Remediación propuesta:**
  - Crear ruta POST para duplicar.
  - Actualizar botón o formulario de UI con `@csrf`.
  - Revisar permisos/policies del endpoint.
- **Criterios de aceptación:**
  - La duplicación solo funciona vía POST.
  - Un GET a la antigua ruta no muta datos.
  - La acción conserva permisos y feedback correcto en UI.
- **Tests sugeridos:** feature tests de método permitido/prohibido.
- **Dependencias:** ninguna.

### TASK-015 — Alinear indicador de progreso con pasos reales del wizard

- **Prioridad:** P1
- **Archivos principales:** `_progress.blade.php` de todos los themes.
- **Problema:** se muestra un paso 7 "Pago" aunque no existe la vista correspondiente.
- **Objetivo:** que el progreso refleje el flujo real.
- **Remediación propuesta:**
  - Eliminar temporalmente el paso inexistente, o
  - Implementar el paso real si forma parte del alcance actual, o
  - Marcarlo explícitamente como paso externo/no disponible si aplica.
- **Criterios de aceptación:**
  - El número de pasos del indicador coincide con el flujo implementado.
  - El usuario no ve pasos fantasmas.
- **Tests sugeridos:** revisión visual del wizard en todos los themes.
- **Dependencias:** coordinable con TASK-004 y TASK-010.

### TASK-016 — Eliminar eager load duplicado en TripTravelerController

- **Prioridad:** P2
- **Archivo principal:** `app/Http/Controllers/TripTravelerController.php`
- **Problema:** eager loading repetido que genera consultas desperdiciadas.
- **Objetivo:** reducir query overhead y simplificar la consulta.
- **Remediación propuesta:**
  - Revisar cadena de `with()` y remover duplicados.
  - Medir con Laravel Debugbar o Telescope.
- **Criterios de aceptación:**
  - La consulta carga relaciones solo una vez.
  - Se reduce el número de queries sin cambiar el resultado funcional.
- **Tests sugeridos:** comparación de queries y smoke funcional.
- **Dependencias:** ninguna.

### TASK-017 — Remover eager load innecesario en AccommodationController

- **Prioridad:** P2
- **Archivo principal:** `app/Http/Controllers/AccommodationController.php`
- **Problema:** relaciones cargadas sin uso.
- **Objetivo:** optimizar performance del controlador.
- **Remediación propuesta:**
  - Eliminar cargas no usadas.
  - Verificar impacto en serialización o vistas relacionadas.
- **Criterios de aceptación:**
  - El controlador funciona igual con menos consultas o payload.
- **Tests sugeridos:** smoke test funcional y revisión de queries.
- **Dependencias:** ninguna.

### TASK-018 — Regenerar mapa de asientos al cambiar filas/columnas

- **Prioridad:** P2
- **Archivo principal:** `app/Http/Controllers/TransportController.php`
- **Problema:** al modificar filas o columnas del bus, el mapa de asientos no se reconstruye.
- **Objetivo:** mantener coherencia entre layout configurado e inventario de asientos.
- **Remediación propuesta:**
  - Recalcular asientos al cambiar dimensiones.
  - Definir estrategia para conservar asientos ocupados válidos o bloquear cambios incompatibles.
- **Criterios de aceptación:**
  - Cambiar filas/columnas actualiza el mapa de asientos.
  - El inventario final coincide con la configuración del transporte.
- **Tests sugeridos:** feature tests de reconfiguración de layout.
- **Dependencias:** coordinable con TASK-003.

### TASK-019 — Blindar disponibilidad de booking con control transaccional

- **Prioridad:** P2 alta
- **Archivo principal:** `app/Services/BookingService.php`
- **Problema:** existe race condition en availability check por ausencia de `lockForUpdate()` o estrategia equivalente.
- **Objetivo:** evitar sobreventa o doble asignación concurrente.
- **Remediación propuesta:**
  - Envolver verificación y reserva en transacción.
  - Aplicar `lockForUpdate()` sobre los registros que controlan disponibilidad.
  - Revisar idempotencia básica del flujo de confirmación.
- **Criterios de aceptación:**
  - Dos intentos concurrentes no consumen el mismo cupo/asiento de forma doble.
  - La disponibilidad final queda consistente tras concurrencia.
- **Tests sugeridos:** test concurrente o simulación secuencial con transacciones controladas.
- **Dependencias:** muy relacionada con TASK-003, TASK-004 y TASK-005.

### TASK-020 — Validar parámetro year en dashboard

- **Prioridad:** P2
- **Archivo principal:** `app/Http/Controllers/DashboardController.php`
- **Problema:** `year` entra sin validación.
- **Objetivo:** endurecer entrada de datos del dashboard.
- **Remediación propuesta:**
  - Añadir validación `integer` y rango razonable.
  - Definir fallback si el año no es válido.
- **Criterios de aceptación:**
  - Años inválidos no rompen el dashboard.
  - El controlador responde con valor por defecto o error controlado.
- **Tests sugeridos:** feature test con años válidos e inválidos.
- **Dependencias:** ninguna.

### TASK-021 — Preservar estado rejected en sincronización documental

- **Prioridad:** P2
- **Archivo principal:** `app/Http/Controllers/TripDocumentController.php`
- **Problema:** `syncDocStatus` sobrescribe el estado `rejected`.
- **Objetivo:** respetar la máquina de estados documental sin borrar decisiones previas.
- **Remediación propuesta:**
  - Revisar transiciones válidas y bloquear sobrescritura indebida.
  - Convertir la lógica en una máquina de estados explícita o tabla de transición.
- **Criterios de aceptación:**
  - Un documento rechazado no se reactiva automáticamente sin transición permitida.
  - Los estados evolucionan según reglas explícitas.
- **Tests sugeridos:** unit tests de transiciones de estado.
- **Dependencias:** ninguna.

### TASK-022 — Añadir validación estructurada al módulo de settings

- **Prioridad:** P2
- **Archivo principal:** `app/Http/Controllers/SettingsController.php`
- **Problema:** settings sin validación formal.
- **Objetivo:** sanear inputs y estabilizar configuración del sistema.
- **Remediación propuesta:**
  - Crear Form Requests por tipo de setting.
  - Validar strings, puertos, emails, flags y credenciales según formato esperado.
- **Criterios de aceptación:**
  - Inputs inválidos no se guardan.
  - El usuario recibe errores claros por campo.
- **Tests sugeridos:** feature tests de settings válidos e inválidos.
- **Dependencias:** coordinable con TASK-012.

### TASK-023 — Eliminar cast huérfano is_active del modelo Trip

- **Prioridad:** P2
- **Archivo principal:** `app/Models/Trip.php`
- **Problema:** existe cast para una columna que no está en el esquema.
- **Objetivo:** alinear el modelo con la base de datos real.
- **Remediación propuesta:**
  - Eliminar el cast si la columna no existe.
  - Si la columna debería existir por dominio, documentar y crear task aparte para reintroducirla correctamente.
- **Criterios de aceptación:**
  - El modelo no contiene casts de columnas inexistentes.
  - No hay side effects en serialización ni lógica derivada.
- **Tests sugeridos:** revisión de modelo y tests existentes del dominio Trip.
- **Dependencias:** puede revisarse junto con TASK-002.

### TASK-024 — Garantizar unicidad de slug en programas

- **Prioridad:** P2
- **Archivo principal:** `app/Http/Controllers/ProgramController.php`
- **Problema:** mismo patrón de slug duplicado que en viajes.
- **Objetivo:** unificar estrategia de slug único en el dominio.
- **Remediación propuesta:**
  - Aplicar validación `unique`.
  - Reutilizar helper/servicio de generación de slug si ya se creó en TASK-011.
- **Criterios de aceptación:**
  - No se pueden guardar programas con slug duplicado.
  - La UX devuelve mensaje de validación claro.
- **Tests sugeridos:** feature tests create/update.
- **Dependencias:** recomendable reutilizar artefactos de TASK-011.

## Secuencia sugerida de ejecución

| PR | Objetivo | Tasks |
|----|----------|-------|
| PR-1 | Estabilidad de búsqueda, estados y slugs | TASK-001, TASK-002, TASK-011, TASK-024 |
| PR-2 | Booking core y persistencia segura | TASK-003, TASK-004, TASK-005, TASK-019 |
| PR-3 | Themes y consistencia del wizard | TASK-006, TASK-007, TASK-008, TASK-009, TASK-010, TASK-014, TASK-015 |
| PR-4 | Seguridad, dominio y cleanup | TASK-012, TASK-013, TASK-016, TASK-017, TASK-018, TASK-020, TASK-021, TASK-022, TASK-023 |

## Definition of Done del backlog

Un ticket puede considerarse cerrado cuando cumple estas condiciones mínimas:

- El fix está implementado en código y revisado por PR.
- Existe al menos un test automatizado o, cuando no sea viable, un smoke test documentado.
- No rompe flujos críticos de viajes, reservas, viajeros, alojamiento o themes.
- Si afecta booking, se valida manualmente el flujo completo de reserva.
- Si afecta vistas multi-theme, se prueba con y sin agencia cargada.
- Si afecta seguridad o settings, se valida persistencia segura y comportamiento en UI.
