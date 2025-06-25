#!/bin/sh

echo "🚀 Starting SmartTV Laravel container..."

# รอ database ถ้าในอนาคตมี DB ใน docker (safe fallback)
# sleep 3

# สร้าง symlink ถ้ายังไม่มี
if [ ! -L "/var/www/html/public/storage" ]; then
    echo "🔗 Running: php artisan storage:link"
    php artisan storage:link
fi

npm run build

# เคลียร์ + แคชใหม่ให้ชัวร์
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public

# 🟢 Start php-fpm in foreground
echo "⚙️ Launching PHP-FPM in foreground..."
exec php-fpm