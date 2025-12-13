# ============================================
# Stage 1: Build frontend assets (Vite)
# ============================================
FROM node:24-alpine AS frontend
WORKDIR /app

# Copia archivos de dependencias
COPY package*.json ./

RUN npm ci --prefer-offline --no-audit

# Copia TODO el código fuente (más seguro)
COPY . .

# Build de producción
RUN npm run build

# ============================================
# Stage 2: Build PHP dependencies
# ============================================
FROM composer:2 AS backend
WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --no-interaction \
    --ignore-platform-reqs

COPY . .
RUN composer dump-autoload --optimize --classmap-authoritative

# ============================================
# Stage 3: Runtime (Application)
# ============================================
FROM php:8.4-alpine

# Instalación de extensiones necesarias
RUN apk add --no-cache \
    postgresql-libs \
    libpng \
    libzip \
    icu-libs \
    && apk add --no-cache --virtual .build-deps \
    postgresql-dev \
    libpng-dev \
    libzip-dev \
    icu-dev \
    && docker-php-ext-install \
    pdo_pgsql \
    gd \
    zip \
    intl \
    opcache \
    pcntl \
    && apk del .build-deps

# Configuración de OPcache para producción
RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.memory_consumption=256'; \
    echo 'opcache.interned_strings_buffer=16'; \
    echo 'opcache.max_accelerated_files=10000'; \
    echo 'opcache.validate_timestamps=0'; \
    echo 'opcache.save_comments=1'; \
    echo 'opcache.fast_shutdown=1'; \
    } > /usr/local/etc/php/conf.d/opcache.ini

# Configuración de PHP para producción
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" && \
    sed -i 's/memory_limit = .*/memory_limit = 256M/' "$PHP_INI_DIR/php.ini"

WORKDIR /var/www

# ⚠️ ORDEN CORREGIDO: Primero copiamos vendor y build, LUEGO el resto
# Esto evita que se sobrescriban con versiones locales/desactualizadas

# 1. Copiar vendor desde el stage de backend
COPY --from=backend --chown=www-data:www-data /app/vendor ./vendor

# 2. Copiar assets compilados desde el stage de frontend
COPY --from=frontend --chown=www-data:www-data /app/public/build ./public/build

# 3. Copiar el resto del código (esto NO sobrescribirá vendor ni build)
COPY --chown=www-data:www-data . .

# Permisos críticos para Laravel
RUN mkdir -p storage/framework/{sessions,views,cache} \
    storage/logs \
    bootstrap/cache && \
    chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

# Cambiamos al usuario www-data
USER www-data

# Exponemos el puerto
EXPOSE 8000

# Health check para que Dokploy sepa cuándo está listo
HEALTHCHECK --interval=10s --timeout=3s --start-period=30s --retries=3 \
    CMD php artisan inspire || exit 1

# Script de inicio con verificaciones
CMD set -e && \
    echo "🔍 Verificando configuración..." && \
    php artisan --version && \
    echo "✅ Laravel OK" && \
    php artisan config:cache && \
    echo "✅ Config cacheado" && \
    php artisan route:cache && \
    echo "✅ Rutas cacheadas" && \
    php artisan view:cache && \
    echo "✅ Vistas cacheadas" && \
    echo "🚀 Iniciando servidor en 0.0.0.0:8000..." && \
    exec php artisan serve --host=0.0.0.0 --port=8000