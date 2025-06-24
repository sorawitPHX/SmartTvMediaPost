# Stage 1: Build Stage (สำหรับติดตั้ง Composer dependencies และ Node.js assets)
FROM php:8.3-fpm AS base_php

# ติดตั้ง System Dependencies ที่จำเป็น
RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libpng-dev libonig-dev \
    # เพิ่มแพ็คเกจที่จำเป็นอื่นๆ ถ้ามี เช่น imagemagick, libwebp-dev เป็นต้น
    && docker-php-ext-install pdo_mysql zip opcache

# ติดตั้ง Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy เฉพาะ composer.json และ composer.lock เพื่อรัน composer install
COPY ./app/composer.json ./app/composer.lock ./

RUN composer install --no-interaction --optimize-autoloader --no-dev

# ติดตั้ง Node.js และ NPM สำหรับ Vite
# ใช้ Node.js LTS version ที่เหมาะสมกับ Vite
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Copy package.json และ package-lock.json (หรือ yarn.lock)
COPY ./app/package.json ./app/package-lock.json ./

RUN npm install
RUN npm run build

# Stage 2: Production Stage (ใช้ PHP-FPM image ที่สะอาดกว่าและมีขนาดเล็กกว่า)
FROM php:8.3-fpm-alpine

# ติดตั้ง System Dependencies ที่จำเป็นสำหรับ Production
RUN apk add --no-cache \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    # เพิ่มแพ็คเกจที่จำเป็นอื่นๆ สำหรับ Production
    && docker-php-ext-install pdo_mysql zip opcache

# กำหนด User ID/Group ID เพื่อรัน PHP-FPM (เพิ่มความปลอดภัย)
# เช่น กำหนดเป็น user/group www-data (ID 82)
RUN addgroup -g 82 -S www-data && adduser -u 82 -D -S -G www-data www-data

WORKDIR /var/www

# Copy เฉพาะไฟล์ที่จำเป็นจาก build stage
COPY --from=base_php /var/www/vendor ./vendor/
COPY --from=base_php /var/www/public/build ./public/build/
COPY ./app . 
# ตั้งค่า Permission
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage \
    && chmod -R 775 /var/www/bootstrap/cache

# รัน Artisan commands สำหรับ Production
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php artisan event:cache \
    && php artisan storage:link

# ตั้งค่า ENV สำหรับ Production (ถ้ามี)
# ENV APP_ENV=production

USER www-data

CMD ["php-fpm"]