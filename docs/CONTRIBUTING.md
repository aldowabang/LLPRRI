# Panduan Kontribusi

## Development Setup

### Tanpa Docker

```bash
# Install dependencies
composer setup

# Jalankan dev server
composer dev

# Vite dev server (terminal terpisah)
npm run dev
```

### Dengan Docker

```bash
# Build dan start
docker compose up -d --build

# Install dependencies di container
docker compose exec app composer install
docker compose exec app npm install
docker compose exec app npm run build

# Jalankan Vite dev server di host
npm run dev
```

## Perintah Development

```bash
# Full test suite (lint + typecheck + test)
composer test

# Linting saja
composer lint          # Auto-fix
composer lint:check    # Dry-run

# Static analysis
composer types:check

# Jalankan test tertentu
docker compose exec app php artisan test --filter=PegawaiTest
docker compose exec app php artisan test tests/Feature/Auth/AuthenticationTest.php
```

## Code Style

### PHP

- Menggunakan Laravel Pint dengan preset `laravel`
- Konfigurasi di `pint.json`
- Jalankan `composer lint` sebelum commit

### Frontend

- Tailwind CSS 4 untuk styling
- Flux UI components untuk UI elements
- Alpine.js untuk interaktivitas

### EditorConfig

- 4 space indent untuk PHP, JS, CSS
- 2 space indent untuk YAML
- LF line endings
- Trim trailing whitespace

## Testing

### Structure

```
tests/
├── Feature/           # Feature tests (HTTP, auth, dll)
│   ├── Auth/          # Authentication tests
│   └── Settings/      # Settings page tests
├── Unit/              # Unit tests
└── TestCase.php       # Base test case
```

### Menulis Test

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PegawaiTest extends TestCase
{
    use RefreshDatabase;

    public function test_pegawai_can_be_created(): void
    {
        // Arrange
        $pegawai = [
            'nip' => '1234567890',
            'nama_pegawai' => 'Test User',
            'no_hp' => '08123456789',
        ];

        // Act
        // ...

        // Assert
        // ...
    }
}
```

### Running Tests

```bash
# Semua tests
composer test

# Feature tests saja
docker compose exec app php artisan test --testsuite=Feature

# Unit tests saja
docker compose exec app php artisan test --testsuite=Unit

# Test tertentu
docker compose exec app php artisan test --filter=test_pegawai
```

## Database

### Migrations

```bash
# Buat migration baru
docker compose exec app php artisan make:migration create_pegawais_table

# Jalankan migrations
docker compose exec app php artisan migrate

# Reset migrations
docker compose exec app php artisan migrate:refresh

# Seed database
docker compose exec app php artisan db:seed
```

### Models

```bash
# Buat model dengan migration
docker compose exec app php artisan make:model Pegawai -m

# Buat model dengan factory dan seeder
docker compose exec app php artisan make:model Pegawai -mfs
```

## Git Workflow

### Branch Naming

- `feature/fitur-baru` - Fitur baru
- `fix/fix-bug` - Perbaikan bug
- `refactor/refactor-code` - Refaktor kode

### Commit Message

```
feat: tambah module pegawai
fix: perbaikan validasi form tugas
refactor: pindahkan logic ke service class
docs: update dokumentasi docker
```

### Pre-commit Checklist

- [ ] `composer lint:check` pass
- [ ] `composer types:check` pass
- [ ] `composer test` pass
- [ ] Tidak ada hardcoded secrets
- [ ] Test ditambah jika ada fitur baru
