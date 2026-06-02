# Étape 1 : Build du frontend Vue
FROM node:22 AS frontend
WORKDIR /app
COPY front ./front
RUN cd front && npm install && npm run build

# Étape 2 : Backend Laravel
FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring

ENV COMPOSER_ALLOW_SUPERUSER=1

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copier uniquement le backend Laravel
COPY back/ .

RUN composer install --no-dev --optimize-autoloader

# Copier le build du frontend dans public/
COPY --from=frontend /app/front/dist ./public

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 8080

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
