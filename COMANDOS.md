# Documentación de Comandos - CRUD Fenix

Este documento explica cada comando utilizado en el proyecto CRUD Fenix.

## Comandos de Configuración Inicial

### 1. Copiar archivo de entorno
```bash
cp .env.example .env
```
**Descripción**: Copia el archivo de ejemplo de variables de entorno para crear tu configuración local.

### 2. Generar clave de aplicación
```bash
php artisan key:generate
```
**Descripción**: Genera una clave única para la aplicación Laravel que se usa para encriptación.

### 3. Instalar dependencias de PHP
```bash
composer install
```
**Descripción**: Instala todas las dependencias de PHP definidas en `composer.json`.

### 4. Instalar dependencias de Node.js
```bash
npm install
```
**Descripción**: Instala todas las dependencias de JavaScript/TypeScript definidas en `package.json`.

## Comandos de Base de Datos

### 5. Ejecutar migraciones
```bash
php artisan migrate
```
**Descripción**: Ejecuta todas las migraciones pendientes para crear las tablas en la base de datos.

### 6. Revertir migraciones
```bash
php artisan migrate:rollback
```
**Descripción**: Revierte la última ejecución de migraciones.

### 7. Refrescar base de datos
```bash
php artisan migrate:fresh
```
**Descripción**: Elimina todas las tablas y vuelve a ejecutar todas las migraciones desde cero.

### 8. Ver estado de migraciones
```bash
php artisan migrate:status
```
**Descripción**: Muestra el estado de cada migración (ejecutada o pendiente).

## Comandos de Desarrollo

### 9. Iniciar servidor de desarrollo
```bash
php artisan serve
```
**Descripción**: Inicia el servidor de desarrollo de Laravel en `http://localhost:8000`.

### 10. Compilar assets en modo desarrollo
```bash
npm run dev
```
**Descripción**: Compila los assets (CSS, JS) con Vite en modo desarrollo con hot-reload.

### 11. Compilar assets para producción
```bash
npm run build
```
**Descripción**: Compila y optimiza los assets para producción.

### 12. Crear enlace simbólico de storage
```bash
php artisan storage:link
```
**Descripción**: Crea un enlace simbólico desde `public/storage` a `storage/app/public` para acceder a archivos subidos.

## Comandos de Artisan (Generadores)

### 13. Crear modelo
```bash
php artisan make:model NombreModelo
```
**Descripción**: Crea un nuevo modelo Eloquent.

### 14. Crear controlador
```bash
php artisan make:controller NombreController --resource
```
**Descripción**: Crea un controlador con métodos CRUD predefinidos.

### 15. Crear migración
```bash
php artisan make:migration nombre_de_la_migracion
```
**Descripción**: Crea un nuevo archivo de migración.

### 16. Crear seeder
```bash
php artisan make:seeder NombreSeeder
```
**Descripción**: Crea un nuevo seeder para poblar la base de datos con datos de prueba.

## Comandos de Docker

### 17. Construir contenedores
```bash
docker-compose build
```
**Descripción**: Construye las imágenes de Docker definidas en `docker-compose.yml`.

### 18. Iniciar contenedores
```bash
docker-compose up -d
```
**Descripción**: Inicia todos los servicios definidos en `docker-compose.yml` en segundo plano.

### 19. Detener contenedores
```bash
docker-compose down
```
**Descripción**: Detiene y elimina todos los contenedores, redes y volúmenes creados.

### 20. Ver logs de contenedores
```bash
docker-compose logs -f
```
**Descripción**: Muestra los logs de todos los servicios en tiempo real.

### 21. Ejecutar comandos dentro del contenedor
```bash
docker-compose exec app php artisan migrate
```
**Descripción**: Ejecuta comandos dentro del contenedor de la aplicación.

### 22. Acceder al contenedor
```bash
docker-compose exec app sh
```
**Descripción**: Abre una terminal interactiva dentro del contenedor de la aplicación.

## Comandos de Limpieza

### 23. Limpiar caché de configuración
```bash
php artisan config:clear
```
**Descripción**: Elimina el archivo de caché de configuración.

### 24. Limpiar caché de rutas
```bash
php artisan route:clear
```
**Descripción**: Elimina el archivo de caché de rutas.

### 25. Limpiar caché de vistas
```bash
php artisan view:clear
```
**Descripción**: Elimina todas las vistas compiladas.

### 26. Limpiar todos los cachés
```bash
php artisan optimize:clear
```
**Descripción**: Limpia todos los cachés de la aplicación (config, routes, views, etc.).

## Comandos de Testing

### 27. Ejecutar tests
```bash
php artisan test
```
**Descripción**: Ejecuta todos los tests de la aplicación usando Pest/PHPUnit.

### 28. Ejecutar tests con cobertura
```bash
php artisan test --coverage
```
**Descripción**: Ejecuta los tests y muestra un reporte de cobertura de código.

## Flujo de Trabajo Recomendado

### Configuración Inicial (Sin Docker)
```bash
# 1. Copiar variables de entorno
cp .env.example .env

# 2. Editar .env con tus credenciales de PostgreSQL

# 3. Instalar dependencias
composer install
npm install

# 4. Generar clave
php artisan key:generate

# 5. Crear enlace de storage
php artisan storage:link

# 6. Ejecutar migraciones
php artisan migrate

# 7. Compilar assets
npm run build

# 8. Iniciar servidor
php artisan serve
```

### Configuración Inicial (Con Docker)
```bash
# 1. Copiar variables de entorno
cp .env.example .env

# 2. Construir e iniciar contenedores
docker-compose up -d

# 3. Ejecutar migraciones dentro del contenedor
docker-compose exec app php artisan migrate

# 4. La aplicación estará disponible en http://localhost:8000
```

### Desarrollo Diario
```bash
# Terminal 1: Servidor Laravel
php artisan serve

# Terminal 2: Compilación de assets con hot-reload
npm run dev
```

### Desarrollo con Docker
```bash
# Iniciar servicios
docker-compose up -d

# Ver logs
docker-compose logs -f app

# Detener servicios
docker-compose down
```

## Notas Importantes

- **PostgreSQL**: Asegúrate de tener PostgreSQL instalado y corriendo, o usa Docker.
- **Storage**: Siempre ejecuta `php artisan storage:link` después de clonar el proyecto.
- **Migraciones**: Ejecuta `php artisan migrate` cada vez que haya nuevas migraciones.
- **Assets**: Ejecuta `npm run build` antes de desplegar a producción.
- **Docker**: Los contenedores deben estar corriendo para que la aplicación funcione.

## Solución de Problemas

### Error de permisos en storage
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Error de conexión a base de datos
- Verifica que PostgreSQL esté corriendo
- Verifica las credenciales en `.env`
- Si usas Docker, verifica que el contenedor de postgres esté activo

### Assets no se cargan
```bash
npm run build
php artisan optimize:clear
```

### Cambios en .env no se reflejan
```bash
php artisan config:clear
```
