# ✅ Checklist de Configuración - CRUD Fenix

## Pre-requisitos

### Sin Docker
- [ ] PHP >= 8.2 instalado
- [ ] Composer instalado
- [ ] Node.js >= 18 instalado
- [ ] PostgreSQL >= 14 instalado y corriendo
- [ ] Extensión pdo_pgsql de PHP habilitada

### Con Docker
- [ ] Docker instalado
- [ ] Docker Compose instalado

## Configuración Inicial

- [ ] Repositorio clonado
- [ ] Archivo `.env` creado (desde `.env.example`)
- [ ] Credenciales de PostgreSQL configuradas en `.env`
- [ ] Dependencias de PHP instaladas (`composer install`)
- [ ] Dependencias de Node instaladas (`npm install`)
- [ ] Clave de aplicación generada (`php artisan key:generate`)
- [ ] Enlace simbólico de storage creado (`php artisan storage:link`)

## Base de Datos

- [ ] Base de datos `crud_fenix` creada en PostgreSQL
- [ ] Conexión a base de datos verificada
- [ ] Migraciones ejecutadas (`php artisan migrate`)
- [ ] Tabla `productos` creada correctamente
- [ ] Tabla `users` creada correctamente
- [ ] (Opcional) Seeders ejecutados (`php artisan db:seed --class=ProductoSeeder`)

## Assets y Compilación

- [ ] Assets compilados (`npm run build`)
- [ ] Carpeta `public/build` existe
- [ ] Carpeta `public/storage` existe (enlace simbólico)

## Permisos (Linux/Mac)

- [ ] Permisos de `storage` configurados (775)
- [ ] Permisos de `bootstrap/cache` configurados (775)
- [ ] Propietario correcto (www-data o usuario del servidor)

## Servidor

### Desarrollo Local
- [ ] Servidor Laravel iniciado (`php artisan serve`)
- [ ] Aplicación accesible en http://localhost:8000
- [ ] (Opcional) Vite dev server corriendo (`npm run dev`)

### Docker
- [ ] Contenedores iniciados (`docker-compose up -d`)
- [ ] Contenedor `postgres` corriendo
- [ ] Contenedor `app` corriendo
- [ ] Aplicación accesible en http://localhost:8000

## Funcionalidad

- [ ] Página principal carga correctamente
- [ ] Lista de productos se muestra (vacía o con datos)
- [ ] Botón "Nuevo Producto" funciona
- [ ] Formulario de creación se muestra correctamente
- [ ] Se puede crear un producto sin imagen
- [ ] Se puede crear un producto con imagen
- [ ] Imagen se muestra en la lista
- [ ] Se puede editar un producto
- [ ] Se puede eliminar un producto (con confirmación)
- [ ] Búsqueda de productos funciona
- [ ] Botones de exportación están visibles
- [ ] No hay errores en la consola del navegador
- [ ] No hay errores en los logs de Laravel

## Rutas

Verifica que estas rutas existan (`php artisan route:list`):
- [ ] GET `/` (redirige a productos)
- [ ] GET `/productos` (lista)
- [ ] GET `/productos/create` (formulario crear)
- [ ] POST `/productos` (guardar)
- [ ] GET `/productos/{id}/edit` (formulario editar)
- [ ] PUT `/productos/{id}` (actualizar)
- [ ] DELETE `/productos/{id}` (eliminar)
- [ ] GET `/productos/export/pdf` (exportar PDF)
- [ ] GET `/productos/export/excel` (exportar Excel)

## Archivos Importantes

- [ ] `app/Models/Producto.php` existe
- [ ] `app/Http/Controllers/ProductoController.php` existe
- [ ] `database/migrations/*_create_productos_table.php` existe
- [ ] `resources/js/pages/Productos/Index.vue` existe
- [ ] `resources/js/pages/Productos/Create.vue` existe
- [ ] `resources/js/pages/Productos/Edit.vue` existe
- [ ] `docker-compose.yml` existe
- [ ] `Dockerfile` existe

## Documentación

- [ ] `README.md` revisado
- [ ] `COMANDOS.md` disponible
- [ ] `RESUMEN.md` disponible
- [ ] `INICIO-RAPIDO.md` disponible
- [ ] `CHECKLIST.md` (este archivo) disponible

## Testing

- [ ] Crear producto con todos los campos
- [ ] Crear producto solo con campos obligatorios
- [ ] Editar producto sin cambiar imagen
- [ ] Editar producto cambiando imagen
- [ ] Eliminar producto con imagen
- [ ] Buscar producto por código
- [ ] Buscar producto por nombre
- [ ] Buscar producto por marca
- [ ] Validación de campos obligatorios funciona
- [ ] Validación de código único funciona

## Logs y Debugging

- [ ] No hay errores en `storage/logs/laravel.log`
- [ ] No hay errores en la consola del navegador
- [ ] No hay errores en la consola de PHP
- [ ] (Docker) No hay errores en `docker-compose logs`

## Optimización (Producción)

- [ ] Assets compilados para producción (`npm run build`)
- [ ] Caché de configuración (`php artisan config:cache`)
- [ ] Caché de rutas (`php artisan route:cache`)
- [ ] Caché de vistas (`php artisan view:cache`)
- [ ] Optimización de autoload (`composer install --optimize-autoloader --no-dev`)

## Seguridad

- [ ] Archivo `.env` no está en el repositorio
- [ ] `APP_DEBUG=false` en producción
- [ ] `APP_ENV=production` en producción
- [ ] Credenciales de base de datos seguras
- [ ] Permisos de archivos correctos

## Docker Específico

- [ ] Volumen de PostgreSQL persiste datos
- [ ] Contenedores se reinician automáticamente
- [ ] Red interna funciona correctamente
- [ ] Variables de entorno se pasan correctamente
- [ ] Migraciones se ejecutan en el contenedor

## Comandos de Verificación

```bash
# Verificar versión de PHP
php -v

# Verificar extensiones de PHP
php -m | grep pdo_pgsql

# Verificar conexión a base de datos
php artisan tinker
>>> DB::connection()->getPdo();

# Verificar rutas
php artisan route:list

# Verificar estado de migraciones
php artisan migrate:status

# Verificar permisos
ls -la storage/
ls -la bootstrap/cache/

# Verificar Docker
docker-compose ps
docker-compose logs app
```

## Solución de Problemas

Si algo no funciona:

1. **Revisar logs**
   - `storage/logs/laravel.log`
   - Consola del navegador
   - `docker-compose logs` (si usas Docker)

2. **Limpiar cachés**
   ```bash
   php artisan optimize:clear
   ```

3. **Verificar permisos**
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

4. **Verificar base de datos**
   ```bash
   php artisan migrate:status
   ```

5. **Recompilar assets**
   ```bash
   npm run build
   ```

---

## ✅ Proyecto Listo

Si todos los items están marcados, tu proyecto está correctamente configurado y listo para usar.

**Fecha de verificación**: _______________

**Verificado por**: _______________

**Notas adicionales**:
_______________________________________________
_______________________________________________
_______________________________________________
