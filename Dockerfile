# Base PHP + Node.js + Composer
FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git zip unzip curl libzip-dev libpng-dev libonig-dev nodejs npm

# PHP extensions
RUN docker-php-ext-install pdo pdo_mysql zip gd

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy Laravel project
COPY ./app ./

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Build frontend assets if Vite used
RUN npm install && npm run build

# Laravel optimizations
RUN php artisan config:cache && php artisan route:cache && php artisan view:cache

# Create storage symlink
RUN php artisan storage:link

COPY ./entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]