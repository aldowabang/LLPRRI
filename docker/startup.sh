#!/bin/bash
set -e

echo "Starting RRI Application..."

# Drop stale caches first (e.g. host bind-mount may carry require-dev
# providers like laravel/pail that are not installed with --no-dev).
# Must run BEFORE any artisan command, otherwise artisan itself fatals.
rm -f bootstrap/cache/packages.php bootstrap/cache/services.php \
    bootstrap/cache/config.php bootstrap/cache/routes.php \
    bootstrap/cache/events.php

# Clear cached config so .env changes take effect
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan package:discover --ansi

# Wait for MySQL to be ready
echo "Waiting for MySQL..."
until php -r "
    \$host = getenv('DB_HOST') ?: 'db';
    \$port = getenv('DB_PORT') ?: '3306';
    \$user = getenv('DB_USERNAME') ?: 'root';
    \$pass = getenv('DB_PASSWORD') ?: '';
    try {
        new PDO(\"mysql:host=\$host;port=\$port\", \$user, \$pass);
        echo 'connected';
    } catch (PDOException \$e) {
        exit(1);
    }
" 2>/dev/null; do
  echo "MySQL not ready, retrying in 2s..."
  sleep 2
done
echo "MySQL is ready."

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Frontend assets are built at image build time. The ./:/app bind-mount
# hides the image's public/build, so build at runtime only as fallback
# when the host has no built manifest (keeps restarts fast).
if [ ! -f public/build/manifest.json ]; then
  echo "Building frontend assets (manifest missing)..."
  if [ ! -d node_modules ]; then
    echo "Installing npm dependencies..."
    npm install
  fi
  echo "Building frontend assets..."
  npm run build
else
  echo "Frontend assets already built, skipping npm build."
fi

# Cache configuration
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
chown -R www-data:www-data /app/storage /app/bootstrap/cache
chmod -R 775 /app/storage /app/bootstrap/cache

echo "Starting Nginx and PHP-FPM..."

# Start PHP-FPM in background
php-fpm -D

# Start Nginx in foreground
nginx -g "daemon off;"
