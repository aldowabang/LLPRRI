# Panduan Setup Docker

## Prerequisites

- Docker Engine 20.10+
- Docker Compose v2+
- Minimal 4GB RAM untuk MySQL + PHP-FPM

## Port yang Digunakan

| Service | Port | Keterangan |
|---------|------|------------|
| Laravel App | 8002 | Nginx + PHP-FPM |
| MySQL | 3307 | Database |
| phpMyAdmin | 8083 | Database management |

**Catatan:** Port 8000, 8001, 8080, 8081, 3306, 6379 sudah digunakan oleh container lain di sistem ini.

## Setup Awal

### 1. Clone dan Install Dependencies

```bash
cd /home/devfilosi/joki/penjadwalanRRI/RRI

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Build frontend assets
npm run build
```

### 2. Setup Environment

```bash
# Copy .env.example ke .env
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 3. Update .env untuk Docker

Edit `.env` dan ubah bagian database:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=rri_penjadwalan
DB_USERNAME=root
DB_PASSWORD=secret
```

**PENTING:** Password harus sama dengan `MYSQL_ROOT_PASSWORD` di `docker-compose.yml`.

### 4. Build dan Jalankan Container

**PENTING:** Build assets lokal terlebih dahulu sebelum build Docker image:

```bash
npm run build
```

Lalu build dan start containers:

```bash
# Build image dan start containers
docker compose up -d --build

# Cek status containers
docker compose ps

# Lihat logs
docker compose logs -f app
```

### 5. Aplikasi

| Service | URL |
|---------|-----|
| Laravel App | http://localhost:8002 |
| phpMyAdmin | http://localhost:8083 |

### 6. Jalankan Vite Dev Server (di Host)

```bash
npm run dev
```

Vite dev server akan berjalan di http://localhost:5173

## Perintah Docker Berguna

```bash
# Masuk ke container app
docker compose exec app bash

# Jalankan artisan commands
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed
docker compose exec app php artisan make:model Pegawai -m

# Database commands
docker compose exec db mysql -u root -psecret rri_penjadwalan

# Logs
docker compose logs app
docker compose logs db

# Restart containers
docker compose restart

# Stop containers
docker compose down

# Stop dan hapus volumes
docker compose down -v
```

## Troubleshooting

### Container tidak start

```bash
# Cek logs
docker compose logs app

# Cek apakah port sudah digunakan
docker compose ps
```

### Permission error di storage

```bash
docker compose exec app chown -R www-data:www-data /app/storage /app/bootstrap/cache
docker compose exec app chmod -R 775 /app/storage /app/bootstrap/cache
```

### Database connection error

```bash
# Cek apakah MySQL sudah ready
docker compose logs db | grep "ready for connections"

# Coba reconnect
docker compose exec app php artisan migrate:refresh
```

### Nginx 502 Bad Gateway

```bash
# Cek apakah PHP-FPM running
docker compose exec app ps aux | grep php-fpm

# Restart app container
docker compose restart app
```

## File Structure Docker

```
RRI/
├── Dockerfile              # PHP 8.3 FPM + Nginx
├── docker-compose.yml      # Service orchestration
├── .dockerignore           # Files to exclude from build
└── docker/
    ├── nginx.conf          # Nginx server configuration
    └── startup.sh          # Container entrypoint script
```

## Environment Variables

### App Container

| Variable | Value | Keterangan |
|----------|-------|------------|
| DB_CONNECTION | mysql | Database driver |
| DB_HOST | db | MySQL host (service name) |
| DB_PORT | 3306 | MySQL port (internal) |
| DB_DATABASE | rri_penjadwalan | Database name |
| DB_USERNAME | root | MySQL username |
| DB_PASSWORD | secret | MySQL password |

### MySQL Container

| Variable | Value | Keterangan |
|----------|-------|------------|
| MYSQL_ROOT_PASSWORD | secret | Root password |
| MYSQL_DATABASE | rri_penjadwalan | Default database |
