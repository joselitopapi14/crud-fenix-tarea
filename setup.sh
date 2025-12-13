#!/bin/bash

# Script de inicialización del proyecto CRUD Fenix
# Este script configura el proyecto desde cero

echo "🚀 Iniciando configuración de CRUD Fenix..."

# Verificar si existe .env
if [ ! -f .env ]; then
    echo "📝 Copiando archivo .env..."
    cp .env.example .env
else
    echo "✅ Archivo .env ya existe"
fi

# Instalar dependencias de Composer
echo "📦 Instalando dependencias de PHP..."
composer install

# Instalar dependencias de Node
echo "📦 Instalando dependencias de Node.js..."
npm install

# Generar clave de aplicación
echo "🔑 Generando clave de aplicación..."
php artisan key:generate

# Crear enlace simbólico de storage
echo "🔗 Creando enlace simbólico de storage..."
php artisan storage:link

# Ejecutar migraciones
echo "🗄️ Ejecutando migraciones..."
php artisan migrate

# Preguntar si desea ejecutar seeders
read -p "¿Deseas ejecutar los seeders de ejemplo? (s/n): " -n 1 -r
echo
if [[ $REPLY =~ ^[Ss]$ ]]
then
    echo "🌱 Ejecutando seeders..."
    php artisan db:seed --class=ProductoSeeder
fi

# Compilar assets
echo "🎨 Compilando assets..."
npm run build

echo ""
echo "✅ ¡Configuración completada!"
echo ""
echo "Para iniciar el servidor de desarrollo, ejecuta:"
echo "  php artisan serve"
echo ""
echo "Para compilar assets con hot-reload, ejecuta en otra terminal:"
echo "  npm run dev"
echo ""
echo "La aplicación estará disponible en: http://localhost:8000"
