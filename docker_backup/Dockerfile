FROM php:8.3-cli

# Install extensions
RUN apt-get update && apt-get install -y \
    git zip unzip curl libzip-dev libonig-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Node + npm (optional: ใช้ Node image ต่างหากก็ได้)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create app directory
WORKDIR /var/www

# Copy project
COPY . .

# Install dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev
RUN npm install && npm run build
RUN php artisan storage:link

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
