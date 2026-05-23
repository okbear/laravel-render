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

echo "=== Starting NGINX ==="
nginx -g "daemon off;"
