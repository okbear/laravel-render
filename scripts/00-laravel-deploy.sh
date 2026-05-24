#!/usr/bin/env bash
set -e

required_env_vars=(
  APP_KEY
  ADMIN_EMAILS
  GOOGLE_CLIENT_ID
  GOOGLE_CLIENT_SECRET
  GOOGLE_REDIRECT_URI
)

for var_name in "${required_env_vars[@]}"; do
  if [ -z "${!var_name:-}" ]; then
    echo "${var_name} is required." >&2
    exit 1
  fi
done

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force

echo "Starting services..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
