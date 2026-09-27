FROM php:8.3-fpm-bookworm

WORKDIR /app

# 1. Install system dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libicu-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    libonig-dev \
    libexif-dev \
    zip \
    unzip \
    git \
    curl \
    wget \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 1b. Install Node.js 22 LTS
RUN curl -fsSL https://nodejs.org/dist/v22.18.0/node-v22.18.0-linux-x64.tar.xz \
        -o /tmp/nodejs.tar.xz \
    && tar -xJf /tmp/nodejs.tar.xz -C /usr/local --strip-components=1 \
    && rm /tmp/nodejs.tar.xz \
    && node --version && npm --version

# 2. Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    gd bcmath intl zip pcntl exif mbstring xml curl pdo_mysql

# 3. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Copy Nginx config
COPY docker/nginx.conf /etc/nginx/sites-available/default

# 5. Copy application files
COPY . /app

# 5b. Drop stale Laravel caches from host (may reference require-dev
# packages like laravel/pail that are absent with --no-dev)
RUN rm -f bootstrap/cache/packages.php bootstrap/cache/services.php \
    bootstrap/cache/config.php bootstrap/cache/routes.php \
    bootstrap/cache/events.php

# 6. Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction \
    --no-scripts --prefer-dist \
    && php artisan package:discover --ansi --no-interaction

# 6b. Build frontend assets at image build time
RUN if [ -f package-lock.json ]; then npm ci --no-audit --no-fund; else npm install --no-audit --no-fund; fi \
    && npm run build

# 7. Fix storage & cache permissions
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache \
    && chown -R www-data:www-data /app \
    && chmod -R 775 storage bootstrap/cache

# 8. Give execute permission to startup script
RUN chmod +x /app/docker/startup.sh

EXPOSE 80

ENTRYPOINT ["/app/docker/startup.sh"]
