# AGENTS.md - Proyecto Hierro & Forja

## Contexto general

Estamos trabajando sobre un e-commerce de herramientas llamado "Hierro & Forja" para Taller de Programacion Web 2026.

Stack confirmado en el repo:
- Laravel
- PHP
- Herd
- MariaDB
- Blade
- CSS/JS tradicional

Notas importantes:
- Verificar siempre la ruta actual antes de ejecutar comandos.
- No tocar MySQL del puerto 3306.
- La base real del proyecto usa MariaDB en puerto 3307.
- La base de datos esperada es `baseDeDatosHerramientas`.
- Cada alumno puede tener su propio `.env`.
- No modificar ni commitear `.env`.

## Reglas de trabajo

- Revisar primero la estructura real del proyecto.
- Usar el codigo actual del repo como fuente principal de verdad.
- Si este archivo queda desactualizado, indicar que punto conviene corregir.
- No asumir faltantes sin revisar archivos reales.
- No hacer cambios masivos sin explicar antes el plan corto.
- Resolver una tarea por vez.
- Priorizar codigo simple y entendible para nivel universitario.
- Mantener el estilo actual del proyecto.
- No migrar a Laravel Auth estandar salvo pedido explicito.
- El login real usa sesion manual con tabla `usuarios`.
- No ejecutar `php artisan migrate:fresh --seed` sin confirmacion del usuario.

## Sesion de usuario

La sesion manual usa estas claves:
- `session('usuario_id')`
- `session('usuario_nombre')`
- `session('usuario_email')`
- `session('usuario_role')`

Roles usados:
- `admin`
- `comprador`

## Estructura real confirmada

Modelos presentes:
- `app/Models/Categoria.php`
- `app/Models/Marca.php`
- `app/Models/Producto.php`
- `app/Models/ProductoImagen.php`
- `app/Models/Usuario.php`
- `app/Models/User.php` sigue existiendo, pero el flujo real usa `Usuario`

Controladores presentes:
- `app/Http/Controllers/AuthController.php`
- `app/Http/Controllers/ProductoController.php`
- `app/Http/Controllers/Admin/AdminController.php`
- `app/Http/Controllers/Admin/AdminProductoController.php`
- `app/Http/Controllers/Admin/AdminUsuarioController.php`

Middleware presente:
- `app/Http/Middleware/AdminMiddleware.php`

Vistas confirmadas:
- `resources/views/layouts/app.blade.php`
- `resources/views/layouts/admin.blade.php`
- `resources/views/layouts/navbar.blade.php`
- `resources/views/layouts/footer.blade.php`
- `resources/views/pages/index.blade.php`
- `resources/views/pages/catalogo.blade.php`
- `resources/views/pages/producto.blade.php`
- `resources/views/pages/carrito.blade.php`
- `resources/views/pages/contacto.blade.php`
- `resources/views/pages/login.blade.php`
- `resources/views/pages/registro.blade.php`
- `resources/views/pages/quienes-somos.blade.php`
- `resources/views/pages/comercializacion.blade.php`
- `resources/views/pages/terminos.blade.php`
- `resources/views/admin/dashboard.blade.php`
- `resources/views/admin/productos/create.blade.php`
- `resources/views/admin/productos/edit.blade.php`
- `resources/views/admin/productos/index.blade.php`
- `resources/views/admin/Usuarios/index.blade.php`
- `resources/views/admin/Usuarios/create-admin.blade.php`

Detalle a tener en cuenta:
- La ruta `/pagos-envios` existe en `routes/web.php`, pero hoy no esta la vista `resources/views/pages/pagos-envios.blade.php`.

## Relaciones esperadas del dominio

- `Categoria` hasMany `Producto`
- `Marca` hasMany `Producto`
- `Producto` belongsTo `Categoria`
- `Producto` belongsTo `Marca`
- `Producto` hasMany `ProductoImagen`
- `Producto` hasOne `imagenPrincipal`
- `ProductoImagen` belongsTo `Producto`
- `Usuario` es independiente y usa password hasheado

## Tablas principales esperadas

`categorias`
- `id`
- `nombre`
- `slug`
- `timestamps`

`marcas`
- `id`
- `nombre`
- `logo_url`
- `timestamps`

`productos`
- `id`
- `nombre`
- `descripcion`
- `precio`
- `precio_anterior`
- `stock`
- `ventas`
- `energia`
- `etiqueta`
- `etiqueta_clase`
- `activo`
- `categoria_id`
- `marca_id`
- `timestamps`

`producto_imagenes`
- `id`
- `producto_id`
- `url`
- `orden`
- `es_principal`
- `timestamps`

`usuarios`
- `id`
- `nombre`
- `apellido`
- `email`
- `password`
- `dni`
- `telefono`
- `direccion`
- `ciudad`
- `provincia`
- `codigo_postal`
- `role`
- `activo`
- `timestamps`

## Estado funcional confirmado en el repo

Ya esta implementado o parcialmente implementado:
- Catalogo conectado al backend.
- Filtros y busqueda en catalogo.
- Vista detalle de producto conectada al backend.
- Login y registro contra tabla `usuarios`.
- Password hasheado con cast en `Usuario`.
- Logout.
- Redireccion al panel admin si el usuario tiene role `admin`.
- Middleware `admin` registrado en `bootstrap/app.php`.
- Dashboard admin basico.
- Panel admin de productos con listado, alta, edicion, activacion y desactivacion.
- Carga de multiples imagenes para productos.
- Exportacion de `database/data/productos.json` desde el admin de productos.
- Panel admin de usuarios con listado separado por rol, alta de administradores, activacion y desactivacion.
- Validacion para no desactivar el admin root `admin@hierroforja.com`.

Pendiente o no confirmado en el codigo actual:
- Middleware general de autenticacion para compradores.
- CRUD admin de categorias.
- Consultas de contacto guardadas en base.
- Pedidos y `pedido_items`.
- Panel comprador.
- Emails.

## Datos cargados esperados

Segun `database/data/productos.json`:
- 6 categorias base:
  `ferreteria`, `herreria`, `construccion`, `carpinteria`, `pintureria`, `durlok`
- 9 marcas base:
  `Bosch`, `Lusqtoff`, `DeWalt`, `Makita`, `Ingco`, `Total`, `Bremen`, `Stanley`, `Milwaukee`
- 22 productos en el JSON actual

Usuario admin esperado:
- `admin@hierroforja.com / admin1234`

## Rutas importantes confirmadas

Publicas:
- `/`
- `/catalogo`
- `/catalogo/filtrar`
- `/producto/{id}`
- `/login`
- `/registro`
- `/carrito`
- `/quienes-somos`
- `/comercializacion`
- `/contacto`
- `/terminos`
- `/pagos-envios`

Admin protegidas por middleware `admin`:
- `/admin/dashboard`
- `/admin/productos`
- `/admin/productos/crear`
- `/admin/productos/{producto}/editar`
- `/admin/usuarios`
- `/admin/usuarios/crear-admin`

## Comandos utiles

- `php artisan migrate`
- `php artisan db:show`
- `php artisan tinker`
- `php artisan make:controller Admin/NombreController`
- `php artisan make:middleware NombreMiddleware`

Usar con confirmacion previa:
- `php artisan migrate:fresh --seed`

## Forma esperada de trabajar

Cuando se pida una tarea:

1. Revisar archivos relacionados.
2. Explicar brevemente que se encontro.
3. Proponer un plan corto.
4. Modificar solo los archivos necesarios.
5. Al final informar:
   - archivos modificados
   - que se agrego o cambio
   - como probarlo
   - comandos necesarios
