# Plan de Expansión y Monetización para Proyecto Avícola – Instrucciones para Cursor

**Objetivo**: Transformar el actual monolito Laravel 10 en una plataforma SaaS multi-tenant, escalable, con frontend moderno, módulo de genética avanzada y marketplace, lista para monetizar en el contexto venezolano.

**Pre-requisitos para Cursor**:
- Trabajar sobre el proyecto existente (Stack: Laravel 10, Blade, jQuery, MySQL).
- Crear rama `feature/expansive` y seguir las tareas en orden.
- Hacer commit después de cada tarea terminada con mensaje descriptivo.

---

## Fase 0: Estabilización de la Fundación
> Objetivo: corregir deuda técnica, alinear esquema de BD y habilitar API segura.

### Tarea 0.1 – Respaldo y análisis de esquemas existentes
1. Ejecutar `php artisan db:show` y comparar con los dos dumps SQL (`database/gallos.sql` y `gallos.sql` raíz).
2. El esquema de producción más reciente es el usado por el código en PHP (usa `nombre_cliente`, `telefono` en `ventas` y columna `tipo` en `gallos_hijos`). Vamos a normalizarlo.
3. Hacer backup completo de la BD actual con `mysqldump` o similar.

### Tarea 0.2 – Crear migraciones desde el esquema ideal normalizado
Crear archivos de migración en `database/migrations` con el siguiente esquema objetivo:

**Tabla `clients`**:
- id, name, phone, email, tenant_id (nullable por ahora), timestamps.

**Tabla `gallos`**:
- id, nombre, color, color_alternativo, placa, marca, anillo, estatus, observaciones, fecha_nacimiento, tenant_id, timestamps.
- Añadir campos que ya existen en el código (nombre, color_alternativo, etc. revisar modelo `Gallo`).

**Tabla `gallinas`**:
- Similar a gallos, agregar `estatus`, `tenant_id`.

**Tabla `ventas`** (normalizada):
- id, gallo_id (FK), cliente_id (FK), fecha, precio, tipo_venta, observaciones, tenant_id, timestamps.

**Tabla `gallos_hijos`** (polimórfica para hijos):
- id, padre_id (FK→gallos), madre_id (FK→gallinas), hijoable_type (string), hijoable_id (bigInteger), tipo (enum: 'gallo','gallina'), tenant_id, timestamps.
- Esto permite que `hijoable` sea un `Gallo` o `Gallina` mediante relación polimórfica.

Instrucciones para Cursor:
- Crear las migraciones `create_clients_table`, `update_gallos_table`, etc., respetando las columnas reales que usa el código (usar `php artisan schema:dump` o inspeccionar modelos).
- Ejecutar `php artisan migrate` en entorno local.

### Tarea 0.3 – Implementar Form Requests y validación robusta
- Crear `app/Http/Requests/StoreGalloRequest`, `UpdateGalloRequest`, `StoreVentaRequest`, etc.
- Definir reglas estrictas (campos requeridos, numéricos, fechas, FKs existentes).
- Reemplazar en todos los controladores el uso de `$request->all()` por `$request->validated()`.
- En `UserController`, jamás usar `$request->all()`; validar password manualmente o con request.
- Instalar `laravel/sanctum` si no está: `composer require laravel/sanctum` y publicar config.

### Tarea 0.4 – Refactor de VentaController con Service Layer y transacciones
1. Crear `app/Services/VentaService.php` con método `registrarVenta(array $data): Venta`.
2. Dentro de ese método usar `DB::transaction`:
   - Crear venta.
   - Buscar gallo y cambiar su `estatus` a 'Vendido'.
   - Retornar la venta.
3. Modificar `VentaController@store` para llamar a este servicio.
4. Hacer lo mismo para `destroy`: restaurar estado del gallo a 'Activo' y eliminar venta, dentro de transacción.
5. Actualizar modelos: en `Venta`, descomentar/definir `cliente()` (belongsTo) y asegurar `gallo()` (belongsTo).

### Tarea 0.5 – Configurar Sanctum y proteger rutas API
- En `app/Http/Kernel.php`, añadir `\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class` al grupo `api`.
- En todas las rutas de `routes/api.php`, agrupar con `Route::middleware('auth:sanctum')->group(...)`.
- Crear un endpoint `POST /api/auth/login` que devuelva un token (usar `User::createToken()`) y logout para revocarlo.
- Las rutas públicas solo serán login y register (si aplica).

### Tarea 0.6 – Corrección de timestamps y relaciones
1. Revisar todas las migraciones: asegurar `$table->timestamps()` (genera `created_at`, `updated_at`). Si hay columnas `update_at`, eliminarlas.
2. En los modelos, verificar que `public $timestamps = true;` y que no se estén usando nombres incorrectos.
3. Ejecutar migraciones frescas en entorno de prueba o script de alter table si hay datos.

### Tarea 0.7 – Ajustar modelos con nuevas relaciones
- `Gallo`: añadir `belongsTo(Tenant::class)`, `hasMany(Venta::class)`, relación polimórfica `gallosHijos` (como hijos).
- `Gallina`: similar, `hasMany` a gallos_hijos como madre.
- `GallosHijo`: definir `morphTo('hijoable')`, `belongsTo(Gallo::class, 'padre_id')`, `belongsTo(Gallina::class, 'madre_id')`.
- `Venta`: `belongsTo(Gallo::class)`, `belongsTo(Cliente::class)`.
- `Cliente`: nuevo modelo.

---

## Fase 1: Núcleo Monetizable (SaaS)
> Objetivo: multi-tenencia, suscripciones, pedigree visual y pagos.

### Tarea 1.1 – Instalación y configuración de stancl/tenancy
- `composer require stancl/tenancy`
- Publicar assets de tenancy: `php artisan vendor:publish --provider="Stancl\Tenancy\TenancyServiceProvider"`
- Configurar `config/tenancy.php`: usar base de datos por tenant (database per tenant) o por prefijo (recomendado para comenzar: `'tenancy_db_prefix' => 'tenant_'`).
- Registrar el Middleware de inicialización de tenant en `Http/Kernel.php` para rutas web y api.
- Crear modelo `Tenant` y migración para tabla `tenants`.
- Crear el comando de creación de tenant: `php artisan tinker` o un seeder para probar.

### Tarea 1.2 – Migrar todos los modelos a la multi-tenencia
- Añadir trait `Stancl\Tenancy\Traits\BelongsToTenant` en Gallo, Gallina, Venta, GallosHijo, Cliente, y posibles nuevos modelos.
- En migraciones, asegurar que tablas principales tengan `tenant_id` y FK a `tenants(id)`. Crear migración de actualización si no existe.
- En `Tenant` model, añadir relaciones hasMany a los modelos del negocio.
- Actualizar cualquier consulta que no use el scope global del trait (el trait añadirá automáticamente `where tenant_id = current_tenant()`).

### Tarea 1.3 – Panel Super Admin para gestión de tenants
- Crear controlador `SuperAdmin\TenantController`.
- Vistas (aún Blade) en `resources/views/super-admin/` para listar, crear, suspender tenants.
- Rutas protegidas con middleware `auth` y un gate que compruebe que el usuario es `is_superadmin`. Añadir campo `is_superadmin` a la tabla `users`.
- El panel quedará fuera del multi-tenancy (rutas sin el middleware tenant). Define subdominio o prefijo `/admin`.

### Tarea 1.4 – Implementar sistema de suscripciones y límites de plan
- Crear migración `subscriptions` (tenant_id, plan, status, ends_at, etc.).
- Modelo `Subscription` con BelongsToTenant.
- Definir planes: 'free' (límite 20 aves, 1 usuario), 'pro' (ilimitado).
- Crear Middleware `CheckSubscriptionLimit` que verifique en cada petición si el tenant actual excede los límites de su plan. Por ejemplo, al intentar crear un nuevo `Gallo`, contar los existentes y si >=20 y plan free, abortar.
- Crear políticas de autorización (`GalloPolicy`, etc.) para centralizar límites.

### Tarea 1.5 – Integrar pasarela de pagos adaptada a Venezuela
- Crear un sistema de órdenes de pago (`PaymentOrder`): monto, moneda, método, referencia, estado (pendiente, verificado, rechazado).
- Los métodos aceptados: 'zelle', 'pagomovil', 'usdt_binance'.
- Vista para que el usuario suba comprobante de pago (imagen y número de referencia).
- Notifica por email o bot de Telegram al administrador cuando hay un pago pendiente.
- El Super Admin verifica y marca el pago como 'verificado', activando la suscripción del tenant.
- Para automatización futura, se podría integrar con APIs de Binance Pay o Zelle vía bancos, pero inicialmente con verificación manual.
- Comando `php artisan tenant:check-subscriptions` para desactivar suscripciones vencidas.

### Tarea 1.6 – Árbol genealógico visual y cálculo de consanguinidad
1. **Backend**:
   - En `GalloController` y `GallinaController`, añadir método `pedigree($id)` que retorne datos jerárquicos hasta 3 generaciones en formato JSON.
   - Crear endpoint API `GET /api/gallos/{id}/pedigree`.
   - Implementar helper `Services\PedigreeService::calcularConsanguinidad(Gallo $gallo): float` usando el coeficiente de Wright (requiere hasta 5 generaciones, pero podemos hacer una versión simple).
2. **Frontend**:
   - Instalar `d3` (ya en package.json) o `orgchart` (`npm install d3-org-chart`).
   - Crear componente Blade/Vue inicialmente con un `<div id="pedigree-tree">` y JavaScript que consuma el endpoint y pinte el árbol interactivo.
   - Al hacer clic en un nodo, mostrar información detallada (modal).

### Tarea 1.7 – Migrar los límites de plan a políticas y middleware
- Completar `CheckSubscriptionLimit` y aplicarlo a rutas de creación de recursos.
- Mostrar en el dashboard del tenant la cantidad de registros usados/máximos.
- Preparar landing page de planes y flujo de upgrade.

---

## Fase 2: Expansión "Brutal" (SPA, Marketplace, Trazabilidad)
> Objetivo: convertir la app en un ecosistema completo.

### Tarea 2.1 – Migración a SPA con Vue 3 + Inertia.js
- Instalar Inertia: `composer require inertiajs/inertia-laravel` y `npm install @inertiajs/inertia @inertiajs/inertia-vue3 vue@3`.
- Configurar `app.blade.php` con `@inertia` y crear `HandleInertiaRequests` middleware.
- Crear un layout base Vue en `resources/js/Layouts/Authenticated.vue`.
- Convertir progresivamente las vistas Blade a componentes Vue/Inertia:
  - Empezar por el dashboard (usando `Dashboard.vue`).
  - Luego `gallos/index.vue`, `gallos/create.vue`, etc.
- Reemplazar las peticiones fetch/jQuery por llamadas a métodos de Inertia (`this.$inertia.get`, `this.$inertia.post`).
- Conservar temporalmente algunas vistas Blade complejas (reportes) y embeberlas en la SPA con `<iframe>` o convertirlas después.

### Tarea 2.2 – Dashboard interactivo y financiero
- Componente `Dashboard.vue` con tarjetas resumen: Total Aves, Ventas del mes, Gallos de alto valor, etc.
- Gráficos con `Chart.js` (o `apexcharts`): cantidad de aves por estado (Activo, Vendido, Muerto), ingresos mensuales, distribución de razas.
- Datos obtenidos vía endpoints API dedicados dentro de `Tenant\DashboardController`.

### Tarea 2.3 – Módulo de Trazabilidad y Manejo de Eventos
1. Crear modelos `EventoAve` (gallo_id || gallina_id polimórfico, tipo_evento, fecha, notas) y tablas relacionadas.
2. Tipos de evento configurables: 'vacunacion', 'desparasitante', 'pesaje', 'cambio_corral', 'observacion_clinica'.
3. CRUD desde la vista de cada ave, con posibilidad de ingresar datos masivos (seleccionar varias aves y agregar mismo evento).
4. En la ficha del ave, mostrar línea de tiempo con todos sus eventos, similar a historia clínica.
5. Registrar peso y generar gráfica de crecimiento con Chart.js. (peso en tabla aparte con fecha, gallo_id).

### Tarea 2.4 – Convertir la app en PWA
- Crear `manifest.json` y service worker en la raíz pública.
- Instalar `laravel-pwa` o configurar manualmente: `composer require silviolleite/laravelpwa` (compatible Laravel 10).
- Publicar assets y configurar iconos.
- Registrar service worker en `resources/js/app.js`.
- Probar que la app se pueda "instalar" en navegadores móviles y funcione offline con estrategias de cache (páginas principales).

### Tarea 2.5 – Marketplace B2B de reproductores
1. **Modelo y DB**: Crear tabla `publicaciones` (tenant_id, gallo_id nullable, gallina_id nullable, precio, descripcion, activo, destacado, timestamps). Relación polimórfica con ave.
2. **Backend**:
   - Controlador `MarketplaceController` público (sin auth central, pero sí tenant_id para el vendedor) que liste publicaciones activas.
   - Filtros por raza, color, precio.
   - El vendedor puede publicar/despublicar desde su panel.
   - Endpoint de contacto que envía un mensaje interno (modelo `Mensaje`) o redirige a WhatsApp.
3. **Comisiones**: Middleware que al confirmar una venta por marketplace (marcar como vendido), calcule una comisión y la registre en tabla `comisiones` para el Super Admin. Esta lógica se integrará en `VentaService` cuando la venta provenga de una publicación.
4. **Vista pública**: Página web pública (no SPA, Blade simple) donde se muestran los gallos en venta de todos los tenants que activen visibilidad.

### Tarea 2.6 – Reportes avanzados exportables
- Crear controlador `ReportsController` (tenant scoped) que ofrezca:
  - Ficha individual completa con pedigree y fotos, exportable a PDF (usando `barryvdh/laravel-dompdf`).
  - Listado de inventario con filtros, exportable a Excel (`maatwebsite/excel`).
  - Informe de rentabilidad por ave (precio venta - costos asociados). Requiere tabla de gastos opcional.
- Reutilizar vistas Blade para PDF y luego integrar botones en la SPA.

### Tarea 2.7 – Sistema de notificaciones
- Para notificaciones internas, usar `laravel-notify` o base de datos con tabla `notifications` polimórfica.
- En la SPA, crear un componente de campanita que consulte notificaciones no leídas vía API.
- Ejemplos: "El pago de tu suscripción fue verificado", "Tu gallo 'Rayo' ha sido vendido", "Nuevo mensaje de un comprador".
- Configurar envío de correos electrónicos usando servicio SMTP (puede ser gratuito como Mailtrap para pruebas, luego SendGrid o servidor propio).

---

## Instrucciones finales para Cursor
- Ejecutar todas las tareas secuencialmente, haciendo pruebas después de cada fase.
- Para la fase 0 y 1, mantener compatibilidad con el frontend Blade existente mientras se introducen Vue/Inertia en fase 2. No romper la funcionalidad actual.
- El objetivo es llegar a un producto que pueda ser lanzado como SaaS en Venezuela.
- Guarda el progreso con commits regulares.

¡Manos a la obra! 🚀