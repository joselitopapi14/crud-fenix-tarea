# 📁 Estructura del Proyecto - CRUD Fenix

```
crud-fenix/
│
├── 📄 Archivos de Configuración
│   ├── .env.example              # Variables de entorno (PostgreSQL)
│   ├── .dockerignore             # Archivos excluidos de Docker
│   ├── docker-compose.yml        # Orquestación de contenedores
│   ├── Dockerfile                # Imagen de la aplicación
│   ├── composer.json             # Dependencias PHP
│   ├── package.json              # Dependencias Node.js
│   └── vite.config.ts            # Configuración de Vite
│
├── 📚 Documentación
│   ├── README.md                 # Documentación principal
│   ├── COMANDOS.md               # Lista de comandos explicados
│   ├── RESUMEN.md                # Resumen de cambios
│   ├── INICIO-RAPIDO.md          # Guía de inicio rápido
│   ├── CHECKLIST.md              # Checklist de verificación
│   └── ESTRUCTURA.md             # Este archivo
│
├── 🔧 Scripts de Configuración
│   ├── setup.sh                  # Script de setup para Linux/Mac
│   └── setup.ps1                 # Script de setup para Windows
│
├── 🎯 Backend (Laravel)
│   │
│   ├── app/
│   │   ├── Http/
│   │   │   └── Controllers/
│   │   │       └── ProductoController.php    # Controlador CRUD
│   │   │
│   │   └── Models/
│   │       └── Producto.php                  # Modelo Eloquent
│   │
│   ├── config/
│   │   └── database.php                      # Config PostgreSQL
│   │
│   ├── database/
│   │   ├── migrations/
│   │   │   ├── 0001_01_01_000000_create_users_table.php
│   │   │   └── 2025_12_13_*_create_productos_table.php
│   │   │
│   │   └── seeders/
│   │       └── ProductoSeeder.php            # Datos de ejemplo
│   │
│   └── routes/
│       └── web.php                           # Rutas de la aplicación
│
├── 🎨 Frontend (Vue.js)
│   │
│   └── resources/
│       ├── css/
│       │   └── app.css                       # Estilos globales
│       │
│       └── js/
│           ├── app.ts                        # Punto de entrada
│           │
│           ├── components/                   # Componentes Shadcn/UI
│           │   └── ui/
│           │       ├── button/
│           │       ├── input/
│           │       ├── table/
│           │       ├── card/
│           │       ├── badge/
│           │       ├── select/
│           │       ├── label/
│           │       └── textarea/
│           │
│           ├── layouts/
│           │   └── AppLayout.vue             # Layout principal
│           │
│           └── pages/
│               └── Productos/
│                   ├── Index.vue             # Lista de productos
│                   ├── Create.vue            # Crear producto
│                   └── Edit.vue              # Editar producto
│
├── 🗄️ Almacenamiento
│   │
│   ├── public/
│   │   ├── build/                            # Assets compilados
│   │   └── storage/                          # Enlace simbólico
│   │
│   └── storage/
│       ├── app/
│       │   └── public/
│       │       └── productos/                # Imágenes de productos
│       │
│       └── logs/
│           └── laravel.log                   # Logs de la aplicación
│
└── 🐳 Docker
    ├── Contenedor: postgres
    │   ├── Imagen: postgres:16-alpine
    │   ├── Puerto: 5432
    │   └── Volumen: postgres_data
    │
    └── Contenedor: app
        ├── Imagen: PHP 8.2 Alpine + Node.js
        ├── Puerto: 8000
        └── Depende de: postgres
```

## 🔄 Flujo de Datos

```
┌─────────────┐
│   Usuario   │
└──────┬──────┘
       │
       ▼
┌─────────────────────────────────┐
│      Navegador (Vue.js)         │
│  ┌──────────────────────────┐   │
│  │  Productos/Index.vue     │   │
│  │  Productos/Create.vue    │   │
│  │  Productos/Edit.vue      │   │
│  └──────────────────────────┘   │
└────────────┬────────────────────┘
             │ Inertia.js
             ▼
┌─────────────────────────────────┐
│    Laravel (Backend)            │
│  ┌──────────────────────────┐   │
│  │  ProductoController      │   │
│  │  - index()               │   │
│  │  - create()              │   │
│  │  - store()               │   │
│  │  - edit()                │   │
│  │  - update()              │   │
│  │  - destroy()             │   │
│  │  - exportPdf()           │   │
│  │  - exportExcel()         │   │
│  └──────────────────────────┘   │
└────────────┬────────────────────┘
             │ Eloquent ORM
             ▼
┌─────────────────────────────────┐
│    PostgreSQL Database          │
│  ┌──────────────────────────┐   │
│  │  Tabla: productos        │   │
│  │  - id                    │   │
│  │  - codigo                │   │
│  │  - nombre                │   │
│  │  - presentacion_tipo     │   │
│  │  - presentacion_valor    │   │
│  │  - imagen                │   │
│  │  - valor_costo           │   │
│  │  - valor_venta           │   │
│  │  - marca                 │   │
│  │  - observaciones         │   │
│  │  - created_at            │   │
│  │  - updated_at            │   │
│  └──────────────────────────┘   │
└─────────────────────────────────┘
```

## 📦 Componentes Principales

### Backend

1. **ProductoController**
   - Maneja todas las operaciones CRUD
   - Validación de datos
   - Gestión de imágenes
   - Exportación de datos

2. **Producto Model**
   - Representa la tabla productos
   - Define campos fillable
   - Casts para tipos de datos

3. **Migraciones**
   - Estructura de la base de datos
   - Versionamiento de esquema

4. **Seeders**
   - Datos de ejemplo
   - Testing

### Frontend

1. **Index.vue**
   - Lista de productos
   - Búsqueda en tiempo real
   - Acciones (crear, editar, eliminar)
   - Exportación

2. **Create.vue**
   - Formulario de creación
   - Validación
   - Preview de imagen
   - Subida de archivos

3. **Edit.vue**
   - Formulario de edición
   - Datos pre-cargados
   - Actualización de imagen

4. **Componentes UI**
   - Button, Input, Table
   - Card, Badge, Select
   - Label, Textarea
   - (Shadcn/UI)

## 🔐 Seguridad

```
┌─────────────────────────────────┐
│  Capas de Seguridad             │
├─────────────────────────────────┤
│  1. Validación Frontend         │
│     - Tipos de datos            │
│     - Campos requeridos         │
│     - Formatos de archivo       │
├─────────────────────────────────┤
│  2. Validación Backend          │
│     - Request validation        │
│     - Reglas de negocio         │
│     - Sanitización              │
├─────────────────────────────────┤
│  3. Base de Datos               │
│     - Constraints               │
│     - Unique indexes            │
│     - Foreign keys              │
├─────────────────────────────────┤
│  4. Almacenamiento              │
│     - Validación de tipos       │
│     - Límites de tamaño         │
│     - Nombres seguros           │
└─────────────────────────────────┘
```

## 🚀 Proceso de Desarrollo

```
1. Desarrollo Local
   ├── Editar código
   ├── npm run dev (hot-reload)
   ├── php artisan serve
   └── Probar en navegador

2. Testing
   ├── Crear producto
   ├── Editar producto
   ├── Eliminar producto
   └── Verificar validaciones

3. Build para Producción
   ├── npm run build
   ├── php artisan config:cache
   ├── php artisan route:cache
   └── php artisan view:cache

4. Despliegue
   ├── Docker build
   ├── Docker compose up
   └── Verificar funcionamiento
```

## 📊 Tecnologías por Capa

### Presentación
- Vue.js 3
- TypeScript
- TailwindCSS
- Shadcn/UI
- Lucide Icons

### Aplicación
- Laravel 12
- Inertia.js
- PHP 8.2

### Datos
- PostgreSQL 16
- Eloquent ORM

### Build & Deploy
- Vite
- Composer
- NPM
- Docker
- Docker Compose

## 🎯 Rutas de la Aplicación

```
GET    /                           → Redirige a /productos
GET    /productos                  → Lista de productos
GET    /productos/create           → Formulario crear
POST   /productos                  → Guardar producto
GET    /productos/{id}/edit        → Formulario editar
PUT    /productos/{id}             → Actualizar producto
DELETE /productos/{id}             → Eliminar producto
GET    /productos/export/pdf       → Exportar PDF
GET    /productos/export/excel     → Exportar Excel
```

## 💾 Estructura de Datos

### Tabla: productos

| Campo              | Tipo           | Restricciones    |
|--------------------|----------------|------------------|
| id                 | BIGSERIAL      | PRIMARY KEY      |
| codigo             | VARCHAR(255)   | UNIQUE, NOT NULL |
| nombre             | VARCHAR(255)   | NOT NULL         |
| presentacion_tipo  | ENUM           | NOT NULL         |
| presentacion_valor | VARCHAR(255)   | NULLABLE         |
| imagen             | VARCHAR(255)   | NULLABLE         |
| valor_costo        | DECIMAL(10,2)  | NOT NULL         |
| valor_venta        | DECIMAL(10,2)  | NOT NULL         |
| marca              | VARCHAR(255)   | NULLABLE         |
| observaciones      | TEXT           | NULLABLE         |
| created_at         | TIMESTAMP      | AUTO             |
| updated_at         | TIMESTAMP      | AUTO             |

---

**Última actualización**: 2025-12-13
