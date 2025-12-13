# Script de inicialización del proyecto CRUD Fenix para Windows
# Este script configura el proyecto desde cero

Write-Host "🚀 Iniciando configuración de CRUD Fenix..." -ForegroundColor Green

# Verificar si existe .env
if (-Not (Test-Path .env)) {
    Write-Host "📝 Copiando archivo .env..." -ForegroundColor Yellow
    Copy-Item .env.example .env
} else {
    Write-Host "✅ Archivo .env ya existe" -ForegroundColor Green
}

# Instalar dependencias de Composer
Write-Host "📦 Instalando dependencias de PHP..." -ForegroundColor Yellow
composer install

# Instalar dependencias de Node
Write-Host "📦 Instalando dependencias de Node.js..." -ForegroundColor Yellow
npm install

# Generar clave de aplicación
Write-Host "🔑 Generando clave de aplicación..." -ForegroundColor Yellow
php artisan key:generate

# Crear enlace simbólico de storage
Write-Host "🔗 Creando enlace simbólico de storage..." -ForegroundColor Yellow
php artisan storage:link

# Ejecutar migraciones
Write-Host "🗄️ Ejecutando migraciones..." -ForegroundColor Yellow
php artisan migrate

# Preguntar si desea ejecutar seeders
$response = Read-Host "¿Deseas ejecutar los seeders de ejemplo? (s/n)"
if ($response -eq "s" -or $response -eq "S") {
    Write-Host "🌱 Ejecutando seeders..." -ForegroundColor Yellow
    php artisan db:seed --class=ProductoSeeder
}

# Compilar assets
Write-Host "🎨 Compilando assets..." -ForegroundColor Yellow
npm run build

Write-Host ""
Write-Host "✅ ¡Configuración completada!" -ForegroundColor Green
Write-Host ""
Write-Host "Para iniciar el servidor de desarrollo, ejecuta:" -ForegroundColor Cyan
Write-Host "  php artisan serve" -ForegroundColor White
Write-Host ""
Write-Host "Para compilar assets con hot-reload, ejecuta en otra terminal:" -ForegroundColor Cyan
Write-Host "  npm run dev" -ForegroundColor White
Write-Host ""
Write-Host "La aplicación estará disponible en: http://localhost:8000" -ForegroundColor Cyan
