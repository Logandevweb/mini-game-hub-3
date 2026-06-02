# Étape 1 : Build du frontend
FROM node:22 AS frontend
WORKDIR /app
COPY frontend ./frontend
RUN cd frontend && npm install && npm run build

# Étape 2 : Build du backend Laravel
FROM php:8.2-fpm AS backend

# Installer extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring

# Autoriser Composer à tourner en root
ENV COMPOSER_ALLOW_SUPERUSER=1

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copier SEULEMENT le backend Laravel
COPY backend/ .

# Installer dépendances Laravel
RUN composer install --no-dev --optimize-autoloader

# Copier le build du frontend dans le dossier public
COPY --from=frontend /app/frontend/dist ./public

# Donner les permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Exposer le port Railway
EXPOSE 8080

# Lancer Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
