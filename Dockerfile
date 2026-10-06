#ASSETS VITE
FROM node:22-alpine AS assets

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY vite.config.js ./
COPY tailwind.config.js ./
COPY postcss.config.js ./

COPY resources ./resources


RUN npm run build


#APP LARAVEL
FROM php:8.2-fpm

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

RUN apt-get update \
    && apt-get install -y unzip libzip-dev \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/*

# Dépendances PHP
COPY composer.json composer.lock ./

RUN composer install \
    --no-interaction \
    --prefer-dist \
    --no-scripts

# Code Laravel
COPY . .

# Assets compilés par Vite
COPY --from=assets /app/public/build ./public/build

# Permissions Laravel
RUN chown -R www-data:www-data \
    /app/storage \
    /app/bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]
