# ============================================
# Stage 1: Build frontend assets (Vite)
# ============================================
FROM node:24-alpine AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . . 
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
    --no-interaction
COPY . .
RUN composer dump-autoload --optimize --classmap-authoritative

# ============================================
# Stage 3: Runtime (Application)
# ============================================
# CAMBIO 1: Usamos la versión CLI, no FPM, porque usaremos "artisan serve"
FROM php:8.3-alpine 

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
    && apk del .build-deps

# Configuración de PHP para producción
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

WORKDIR /var/www

# CAMBIO 2: Copiamos el código base primero
COPY . .

# CAMBIO 3: Sobreescribimos con los assets compilados de los stages anteriores
COPY --from=backend /app/vendor ./vendor
COPY --from=frontend /app/public/build ./public/build

# Permisos (Importante para que Laravel pueda escribir logs y cache)
RUN chown -R www-data:www-data storage bootstrap/cache

# Cambiamos al usuario www-data
USER www-data

# Exponemos el puerto que queremos usar
EXPOSE 8000

# CAMBIO 4: El comando mágico para que hable HTTP
# --host=0.0.0.0 es OBLIGATORIO para que Docker escuche fuera del contenedor
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]