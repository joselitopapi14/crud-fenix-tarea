# CRUD Fenix - Sistema de Gestión de Productos

Sistema CRUD completo para la gestión de productos con Laravel, Vue.js, Inertia.js y PostgreSQL.

## 🚀 Características

- ✅ **CRUD Completo**: Crear, leer, actualizar y eliminar productos
- 📸 **Gestión de Imágenes**: Subida y almacenamiento de imágenes de productos
- 🔍 **Búsqueda en Tiempo Real**: Filtrado de productos por código, nombre o marca
- 📊 **Exportación**: Exportar datos a PDF y Excel
- 🐳 **Docker Ready**: Configuración completa para Docker y Docker Compose
- 🎨 **UI Moderna**: Interfaz construida con Shadcn/UI y TailwindCSS
- 🔒 **Validación**: Validación robusta en frontend y backend

## 📋 Campos del Producto

- **Código**: Identificador único del producto
- **Nombre**: Nombre del producto
- **Presentación**: Tipo (unidad/peso) y valor
- **Imagen**: Foto del producto
- **Valor Costo**: Precio de costo
- **Valor Venta**: Precio de venta
- **Marca**: Marca del producto
- **Observaciones**: Notas adicionales

## 🛠️ Tecnologías

### Backend
- **Laravel 12**: Framework PHP
- **PostgreSQL**: Base de datos relacional
- **Inertia.js**: Adaptador para SPA

### Frontend
- **Vue.js 3**: Framework JavaScript
- **TypeScript**: Tipado estático
- **TailwindCSS**: Framework CSS
- **Shadcn/UI**: Componentes de UI
- **Vite**: Build tool

### DevOps
- **Docker**: Contenedorización
- **Docker Compose**: Orquestación de contenedores

## 📦 Requisitos

### Sin Docker
- PHP >= 8.2
- Composer
- Node.js >= 18
- PostgreSQL >= 14

### Con Docker
- Docker
- Docker Compose

## 🚀 Instalación

### Opción 1: Sin Docker

1. **Clonar el repositorio**
```bash
git clone <url-del-repositorio>
cd crud-fenix
```

2. **Instalar dependencias**
```bash
composer install
npm install
```

3. **Configurar variables de entorno**
```bash
cp .env.example .env
```

Edita el archivo `.env` con tus credenciales de PostgreSQL:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=crud_fenix
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

4. **Generar clave de aplicación**
```bash
php artisan key:generate
```

5. **Crear enlace simbólico para storage**
```bash
php artisan storage:link
```

6. **Ejecutar migraciones**
```bash
php artisan migrate
```

7. **Compilar assets**
```bash
npm run build
```

8. **Iniciar servidor**
```bash
# Terminal 1
php artisan serve

# Terminal 2 (para desarrollo con hot-reload)
npm run dev
```

La aplicación estará disponible en `http://localhost:8000`

### Opción 2: Con Docker

1. **Clonar el repositorio**
```bash
git clone <url-del-repositorio>
cd crud-fenix
```

2. **Copiar variables de entorno**
```bash
cp .env.example .env
```

3. **Construir e iniciar contenedores**
```bash
docker-compose up -d
```

4. **Ejecutar migraciones**
```bash
docker-compose exec app php artisan migrate
```

La aplicación estará disponible en `http://localhost:8000`

## 📖 Uso

### Gestión de Productos

1. **Listar Productos**: La página principal muestra todos los productos en una tabla
2. **Crear Producto**: Click en "Nuevo Producto" para abrir el formulario
3. **Editar Producto**: Click en el ícono de edición en la tabla
4. **Eliminar Producto**: Click en el ícono de eliminar (requiere confirmación)
5. **Buscar Productos**: Usa la barra de búsqueda para filtrar por código, nombre o marca

### Exportación

- **PDF**: Click en el botón "PDF" para exportar la lista de productos
- **Excel**: Click en el botón "Excel" para exportar a formato XLS

## 🗂️ Estructura del Proyecto

```
crud-fenix/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── ProductoController.php
│   └── Models/
│       └── Producto.php
├── database/
│   └── migrations/
│       ├── 0001_01_01_000000_create_users_table.php
│       └── 2025_12_13_061623_create_productos_table.php
├── resources/
│   └── js/
│       ├── pages/
│       │   └── Productos/
│       │       ├── Index.vue
│       │       ├── Create.vue
│       │       └── Edit.vue
│       └── layouts/
│           └── AppLayout.vue
├── routes/
│   └── web.php
├── docker-compose.yml
├── Dockerfile
├── .dockerignore
├── COMANDOS.md
└── README.md
```

## 🔧 Comandos Útiles

Ver el archivo [COMANDOS.md](COMANDOS.md) para una lista completa de comandos con explicaciones.

### Comandos Frecuentes

```bash
# Desarrollo
php artisan serve          # Iniciar servidor
npm run dev               # Compilar assets con hot-reload

# Base de datos
php artisan migrate       # Ejecutar migraciones
php artisan migrate:fresh # Refrescar base de datos

# Docker
docker-compose up -d      # Iniciar contenedores
docker-compose down       # Detener contenedores
docker-compose logs -f    # Ver logs

# Limpieza
php artisan optimize:clear # Limpiar cachés
```

## 🐛 Solución de Problemas

### Error de conexión a PostgreSQL
- Verifica que PostgreSQL esté corriendo
- Verifica las credenciales en `.env`
- Si usas Docker, verifica: `docker-compose ps`

### Error de permisos en storage
```bash
chmod -R 775 storage bootstrap/cache
```

### Assets no se cargan
```bash
npm run build
php artisan optimize:clear
```

### Cambios en .env no se reflejan
```bash
php artisan config:clear
```

## 📝 Notas de Desarrollo

- Las imágenes se almacenan en `storage/app/public/productos`
- El enlace simbólico permite acceder a ellas desde `public/storage`
- Las validaciones están implementadas tanto en frontend como backend
- Los formularios usan Inertia.js para una experiencia SPA

## 🔐 Seguridad

- Validación de tipos de archivo para imágenes
- Protección CSRF en todos los formularios
- Validación de datos en el servidor
- Sanitización de entradas de usuario

## 📄 Licencia

Este proyecto está bajo la licencia MIT.

## 👥 Contribuir

Las contribuciones son bienvenidas. Por favor:

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📧 Contacto

Para preguntas o sugerencias, por favor abre un issue en el repositorio.
