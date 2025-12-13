# ============================================
# Stage 1: Build frontend assets (Vite)
# ============================================
FROM node:24-alpine AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY resources ./resources
COPY public ./public
COPY vite.config.* tsconfig.json ./
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
RUN composer dump-autoload \
    --optimize \
    --classmap-authoritative


# ============================================
# Stage 3: Runtime (PHP-FPM)
# ============================================
FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    postgresql-libs \
    libpng \
    libzip \
    && apk add --no-cache --virtual .build-deps \
    postgresql-dev \
    libpng-dev \
    libzip-dev \
    && docker-php-ext-install \
    pdo_pgsql \
    gd \
    zip \
    opcache \
    && apk del .build-deps

# PHP producción
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

WORKDIR /var/www

# Dependencias PHP
COPY --from=backend /app/vendor ./vendor

# Assets compilados
COPY --from=frontend /app/public/build ./public/build

# Código de la app
COPY . .

# Permisos
RUN chown -R www-data:www-data storage bootstrap/cache

USER www-data

EXPOSE 9000

CMD ["php-fpm"]
