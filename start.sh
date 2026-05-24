#!/bin/bash
set -e

echo "=== Caching config/routes/views ==="
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "=== Running migrations ==="
php artisan migrate --force

echo "=== Starting PHP-FPM ==="
php-fpm -D

# PHP-FPM の TCP ポートが開くまで待つ
echo "=== Waiting for PHP-FPM ==="
for i in $(seq 1 15); do
    if nc -z 127.0.0.1 9000 2>/dev/null; then
        echo "PHP-FPM ready"
        break
    fi
    echo "Waiting... ($i)"
    sleep 1
done

echo "=== Starting NGINX ==="
nginx -g "daemon off;"
