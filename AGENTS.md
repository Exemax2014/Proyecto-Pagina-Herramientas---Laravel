# AGENTS.md - Proyecto Hierro & Forja

## Resumen del proyecto

Proyecto: Tienda online "Hierro & Forja".
Stack: Laravel + PHP + MariaDB. Frontend con Blade y CSS/JS tradicional.
Base de usuarios real: tabla `usuarios` y modelo `Usuario`. El sistema usa sesión manual (no usar `auth()->user()` ni Laravel Auth estándar).
MariaDB sigue usando el puerto `3307`.
`vendor/` está versionado en este proyecto.

Variables de sesión obligatorias:
- `session('usuario_id')`
- `session('usuario_nombre')`
- `session('usuario_email')`
- `session('usuario_role')`

No modificar `.env` ni ejecutar comandos de base de datos sin autorización.

## Principios operativos para agentes y desarrolladores

- Trabajar paso a paso: siempre proponer un plan antes de editar.
- No tocar módulos no solicitados (controladores, vistas, modelos, rutas, CSS/JS salvo que se indique explícitamente).
- No ejecutar `php artisan migrate:fresh --seed` ni crear/ejecutar migraciones sin permiso explícito.
- Evitar cambios masivos: mantener commits pequeños y con mensajes claros.

## Funcionalidades públicas confirmadas

- Home conectado a base de datos y renderizado con Blade.
- Catálogo conectado al backend con filtros por categoría, marca, energía, precio y búsqueda.
- Buscador del navbar redirige a `/catalogo?search=texto`.
- Detalle de producto conectado a base de datos.
- Carrito con flujo mixto:
  - visitante: carrito temporal en `hf_cart` / `localStorage`.
  - usuario logueado: carrito persistido en `pedidos` y `pedido_items`.
- Al iniciar sesión, el carrito temporal se migra al backend.
- Al confirmar compra:
  - el pedido pasa de carrito a confirmado.
  - se descuenta stock.
  - se incrementan ventas.
  - se guarda `fecha_confirmacion`.
- Login / Registro / Logout: sesión manual usando la tabla `usuarios` y el modelo `Usuario`.
- Mis datos: ver y editar perfil (sesión manual requerida).
- Mis compras: listado y detalle de pedidos confirmados del usuario logueado (excluye carritos y valida propiedad del pedido).
- PDF de pedido integrado con `dompdf`.
- Código visible de pedido con formato `HF-000003`.
- Contacto: guarda consultas en base y diferencia visitante vs usuario logueado.

## Panel Admin (estado actual)

Dashboard:
- Panel con métricas avanzadas y estilo visual aprobado (no cambiar estética sin coordinar).

Productos:
- Listar, buscar, crear, editar, activar/desactivar.
- Soporta múltiples imágenes por producto y una imagen principal.
- Productos nuevos se crean activos por defecto.
- Marca se selecciona desde un `select`; ya no se crean marcas por texto libre (no usar `Marca::firstOrCreate`).

Categorías (Admin):
- Gestión de categorías: listar, crear y editar. No hay eliminación física para evitar romper productos asociados.
- Cada categoría puede tener una imagen y un flag para mostrarse en home.
- Orden esperado: posiciones 1 a 6 para home.
- Una categoría sin productos no se debe mostrar en home.

Marcas (Admin):
- Gestión de marcas desde una vista específica.
- Soporta creación de nuevas marcas y edición inline.
- Home muestra marcas desde BD; solo se permiten 6, 8 o 12 marcas visibles (configurable desde admin).
- No usar logos de marcas por ahora. Las marcas se muestran como texto/nombre.

Consultas (Admin):
- Listado de consultas con marcado como leída, botón para enviar correo y botón WhatsApp.

Usuarios (Admin):
- Listado separado por rol (compradores/administradores).
- Botones correo/WhatsApp en fichas de usuario (root: botones ocultos para `admin@hierroforja.com`).

Pedidos (Admin):
- Admin Pedidos ya existe.
- Permite gestionar pedidos persistidos y confirmados desde panel.

Nota: No se modificó lógica funcional en controladores, modelos, vistas ni rutas al actualizar este documento.

## Sincronización y datos en el repositorio

- Productos: sincronizados desde `database/data/productos.json`.
- Categorías: sincronizadas desde `database/data/categorias.json`.
- Marcas: sincronizadas desde `database/data/marcas.json`.
- Seeders existentes: `CategoriaSeeder`, `MarcaSeeder`, `ProductoSeeder`.
- El `DatabaseSeeder` ejecuta, en este orden: `CategoriaSeeder`, `MarcaSeeder`, `ProductoSeeder`.
- Comando disponible: `php artisan catalogo:export-base`.

No se sincronizan en el repo: usuarios reales, consultas reales, sesiones ni pedidos.
Las consultas pueden generarse localmente desde `/contacto` y verse desde `/admin/consultas`.

## Diseño y assets (guía visual)

- Admin: fondo cálido/beige, cards marfil, sidebar oscuro, bordes dorados suaves, botones redondeados, métricas con íconos.
- Mantener esta estética en futuras secciones del admin salvo indicación contraria.
- Assets oficiales:
  - CSS: `public/css`
  - JS: `public/js`
- No usar `public/Css` ni `public/JS`.
- CSS principal admin: `public/css/admin.css`.
- CSS home: `public/css/styleIndex.css`.

## Reglas de mantenimiento de código (nuevas y obligatorias)

- No escribir código por escribir: cada cambio debe tener una razón clara en la descripción del commit.
- CSS:
  - Antes de crear una clase nueva, revisar si existe una clase reutilizable.
  - Evitar duplicar reglas; modificar el bloque existente si aplica.
  - No agregar `!important` salvo justificación documentada en el PR.
  - Mantener encabezados/secciones existentes en los archivos CSS.

- Vistas Blade:
  - Mantener estructura legible; evitar mezclar demasiada lógica de negocio en la vista.
  - Comentar sólo la lógica no obvia (por ejemplo: reglas de orden para home, validaciones de stock, sincronización por seeders, sesión manual).

- PHP / Backend:
  - Evitar duplicar lógica en controladores; extraer funciones pequeñas si se reutiliza en 2+ lugares.
  - No crear helpers, services o nuevas abstracciones si la tarea es pequeña y no las requiere.
  - No borrar código funcional sin explicación detallada en la PR.
  - En migraciones, no modificar migraciones antiguas ya ejecutadas; crear nuevas migraciones cuando se requiera.
  - No tocar migraciones, `composer` ni `vendor` sin decisión explícita.

- General:
  - Mantener cambios pequeños, claros y testeables.
  - Si una corrección afecta varias áreas, proponer división por bloques y revisiones antes de editar.
  - Los comandos artisan que modifiquen datos deben documentar claramente qué archivos o datos afectan.

Reglas sobre comentarios:
- Comentar lógica de negocio delicada (orden de categorías, validaciones de stock, sincronización por seeders, reglas especiales de home, sesión manual).
- No comentar lo obvio ("incrementa contador", "retorna vista", "asigna variable").

## Pendientes principales (prioridad alta)

- Panel admin para métricas reales y evolución del dashboard.
- Limpieza técnica: revisar `exportarProductosJson`, `app/Models/User.php` y compatibilidad con hosting Linux.
- Consolidar y mantener el uso exclusivo de assets en minúscula (`public/css` y `public/js`).

## Reglas de trabajo (operativas)

- Primero: plan detallado y acotado.
- Segundo: editar solo los archivos necesarios.
- Informar siempre al cerrar la tarea: archivos modificados, qué cambió, cómo probar, y qué no se tocó.
- No modificar `.env` ni usar Laravel Auth estándar; mantener la sesión manual existente.

## Comandos útiles (referencia)

- `php artisan catalogo:export-base` — exporta categorías y marcas actuales a:
  - `database/data/categorias.json`
  - `database/data/marcas.json`
- `php artisan migrate` — ejecutar migraciones (usar con precaución en entornos locales controlados).

## ¿Qué cambió en este archivo?

- Se actualizó el estado real de sesión manual, carrito mixto y confirmación de compra.
- Se documentó Admin Pedidos, Mis compras y el PDF de pedido con `dompdf`.
- Se consolidó el uso oficial de `public/css` y `public/js`.
- Se aclaró que `vendor/` está versionado y que MariaDB usa el puerto `3307`.

Mantener este documento actualizado es prioritario: si detectas información desactualizada, proponer un cambio en PR con referencias.
