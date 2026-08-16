#!/bin/bash
set -e

echo "Starting RRI Application..."

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

# Install npm dependencies and build assets
echo "Installing npm dependencies..."
npm install
echo "Building frontend assets..."
npm run build

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
