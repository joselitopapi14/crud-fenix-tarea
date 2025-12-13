# 🚀 Inicio Rápido - CRUD Fenix

## Opción 1: Configuración Automática (Recomendado)

### Windows (PowerShell)
```powershell
.\setup.ps1
```

### Linux/Mac
```bash
chmod +x setup.sh
./setup.sh
```

## Opción 2: Configuración Manual

### 1. Variables de Entorno
```bash
cp .env.example .env
```

Edita `.env` y configura PostgreSQL:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=crud_fenix
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### 2. Dependencias
```bash
composer install
npm install
```

### 3. Configuración Laravel
```bash
php artisan key:generate
php artisan storage:link
php artisan migrate
```

### 4. Assets
```bash
npm run build
```

### 5. Iniciar Servidor
```bash
# Terminal 1
php artisan serve

# Terminal 2 (opcional, para desarrollo)
npm run dev
```

Abre: http://localhost:8000

## Opción 3: Docker

### 1. Iniciar Contenedores
```bash
cp .env.example .env
docker-compose up -d
```

### 2. Ejecutar Migraciones
```bash
docker-compose exec app php artisan migrate
```

### 3. (Opcional) Datos de Prueba
```bash
docker-compose exec app php artisan db:seed --class=ProductoSeeder
```

Abre: http://localhost:8000

## ⚡ Comandos Útiles

```bash
# Ver rutas disponibles
php artisan route:list

# Limpiar cachés
php artisan optimize:clear

# Ejecutar seeders
php artisan db:seed --class=ProductoSeeder

# Ver logs (Docker)
docker-compose logs -f

# Detener Docker
docker-compose down
```

## 📚 Documentación Completa

- **README.md** - Documentación general del proyecto
- **COMANDOS.md** - Lista completa de comandos con explicaciones
- **RESUMEN.md** - Resumen de cambios y configuración

## 🆘 Problemas Comunes

### Error de conexión a PostgreSQL
```bash
# Verifica que PostgreSQL esté corriendo
# Windows: Servicios
# Linux: sudo systemctl status postgresql
# Mac: brew services list
```

### Error de permisos
```bash
# Linux/Mac
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Windows: Ejecutar PowerShell como Administrador
```

### Assets no se cargan
```bash
npm run build
php artisan optimize:clear
```

## ✅ Verificación

Después de la instalación, deberías poder:
1. ✅ Acceder a http://localhost:8000
2. ✅ Ver la lista de productos (vacía o con datos de prueba)
3. ✅ Crear un nuevo producto
4. ✅ Subir una imagen
5. ✅ Editar y eliminar productos
6. ✅ Buscar productos
7. ✅ Exportar a PDF/Excel (endpoints preparados)

---

**¿Necesitas ayuda?** Revisa la documentación completa en README.md
