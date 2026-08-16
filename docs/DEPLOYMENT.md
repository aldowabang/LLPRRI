# Panduan Deployment

## Environment Variables

### Production .env

```env
APP_NAME="RRI Kupang"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=rri_penjadwalan
DB_USERNAME=your-db-user
DB_PASSWORD=your-secure-password

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

## Docker Production Setup

### 1. Build Image

```bash
# Build production image
docker build -t rri-app:production .

# Atau dengan docker compose
docker compose -f docker-compose.prod.yml build
```

### 2. Production Docker Compose

Buat `docker-compose.prod.yml`:

```yaml
services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: rri-app-prod
    ports:
      - "80:80"
    env_file:
      - .env.production
    depends_on:
      db:
        condition: service_healthy
    restart: always
    networks:
      - rri-network

  db:
    image: mysql:8.0
    container_name: rri-db-prod
    environment:
      MYSQL_ROOT_PASSWORD: ${DB_PASSWORD}
      MYSQL_DATABASE: rri_penjadwalan
    volumes:
      - rri_dbdata:/var/lib/mysql
    healthcheck:
      test: ["CMD", "mysqladmin", "ping", "-h", "localhost"]
      interval: 10s
      timeout: 5s
      retries: 10
    restart: always
    networks:
      - rri-network

  nginx:
    image: nginx:alpine
    container_name: rri-nginx-prod
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./docker/nginx.prod.conf:/etc/nginx/conf.d/default.conf
      - ./public:/app/public
      - ssl_certs:/etc/nginx/ssl
    depends_on:
      - app
    restart: always
    networks:
      - rri-network

volumes:
  rri_dbdata:
  ssl_certs:

networks:
  rri-network:
    driver: bridge
```

### 3. Nginx Production Config

Buat `docker/nginx.prod.conf`:

```nginx
server {
    listen 80;
    server_name your-domain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name your-domain.com;

    ssl_certificate /etc/nginx/ssl/fullchain.pem;
    ssl_certificate_key /etc/nginx/ssl/privkey.pem;

    root /app/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass app:9000;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

## Manual Deployment (Tanpa Docker)

### 1. Server Requirements

- PHP 8.3+
- MySQL 8.0+
- Nginx atau Apache
- Composer
- Node.js 20+ (untuk build assets)

### 2. Install Dependencies

```bash
# Clone repository
git clone your-repo-url
cd RRI

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install Node dependencies dan build
npm install
npm run build
```

### 3. Setup Environment

```bash
cp .env.example .env
php artisan key:generate

# Edit .env sesuai server
```

### 4. Database Setup

```bash
php artisan migrate --force
php artisan db:seed
```

### 5. Cache Configuration

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 6. Permissions

```bash
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### 7. Nginx Config

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/RRI/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

## SSL/TLS Setup

### Let's Encrypt

```bash
# Install certbot
sudo apt install certbot python3-certbot-nginx

# Get certificate
sudo certbot --nginx -d your-domain.com

# Auto-renew
sudo crontab -e
# Add: 0 12 * * * /usr/bin/certbot renew --quiet
```

## Monitoring

### Logs

```bash
# Laravel logs
tail -f storage/logs/laravel.log

# Nginx logs
tail -f /var/log/nginx/error.log

# Docker logs
docker compose logs -f
```

### Health Check

```bash
# Check app health
curl https://your-domain.com/up

# Check database
docker compose exec db mysqladmin ping
```

## Backup Database

```bash
# Manual backup
docker compose exec db mysqldump -u root -psecret rri_penjadwalan > backup.sql

# Automated backup (cron)
0 2 * * * docker compose exec db mysqldump -u root -psecret rri_penjadwalan | gzip > /backups/rri_$(date +\%Y\%m\%d).sql.gz
```

## Rollback

```bash
# Git rollback
git rollback
git push origin main --force

# Docker rollback
docker compose pull
docker compose up -d

# Database rollback
php artisan migrate:rollback
```
