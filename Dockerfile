# ----- PHP-FPM image -----
FROM php:8.3-fpm

# ติดตั้งส่วนเสริมที่ Laravel ต้องใช้
RUN apt-get update && apt-get install -y \
    git zip unzip curl libzip-dev libpng-dev libonig-dev \
    && docker-php-ext-install pdo pdo_mysql zip gd

# ติดตั้ง Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# คัดลอก source code (ยกเว้นไฟล์/โฟลเดอร์ที่ .dockerignore ระบุ)
COPY . .

# ติดตั้ง dependency แบบ production
RUN composer install --no-dev --optimize-autoloader --prefer-dist

RUN php artisan storage:link

# Build Vite asset (ต้องมี package.json)
RUN if [ -f package.json ]; then \
      curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
      apt-get install -y nodejs && \
      npm install && npm run build ; \
    fi

# Cache Laravel (เพิ่มความเร็ว)
RUN php artisan config:cache \
 && php artisan route:cache \
 && php artisan view:cache

# ไฟล์ static อยู่ใน public
ENV WEB_ROOT=/var/www/html/public
