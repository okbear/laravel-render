#!/usr/bin/env bash
# Render のビルド時に実行されるスクリプト

set -e  # エラーがあったら即停止

echo "=== Installing PHP dependencies ==="
composer install --no-dev --optimize-autoloader --no-interaction

echo "=== Installing Node dependencies ==="
npm ci

echo "=== Building frontend assets ==="
npm run build

echo "=== Caching config/routes/views ==="
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "=== Running migrations ==="
php artisan migrate --force

echo "=== Build complete ==="
