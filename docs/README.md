# Dokumentasi Sistem Manajemen Tugas LPP RRI Kupang

## Daftar Isi

- [Arsitektur Sistem](ARCHITECTURE.md)
- [Setup Docker](DOCKER.md)
- [Kontribusi](CONTRIBUTING.md)
- [Deployment](DEPLOYMENT.md)

## Ringkasan Proyek

Sistem manajemen tugas berbasis web untuk LPP RRI Kupang yang mengotomatisasi alur penugasan, pelacakan status, dan notifikasi real-time melalui WhatsApp.

### Tech Stack

| Komponen | Teknologi |
|----------|-----------|
| Backend | Laravel 13 (PHP 8.3) |
| Frontend | Livewire 4 + Flux UI + Tailwind CSS 4 |
| Database | MySQL 8.0 (Docker) / SQLite (local dev) |
| Container | Docker + Docker Compose |
| Asset Bundler | Vite 8 |
| Auth | Laravel Fortify |
| PDF | barryvdh/laravel-dompdf |
| WhatsApp | WAHA API |

### Port yang Digunakan

| Service | Port | Keterangan |
|---------|------|------------|
| Laravel App | 8002 | Nginx + PHP-FPM |
| MySQL | 3307 | Database |
| phpMyAdmin | 8083 | Database management |
| Vite Dev | 5173 | Frontend dev server (host) |
| WAHA | 3000 | WhatsApp API |

### Perintah Dasar

```bash
# Setup awal
composer setup

# Development (tanpa Docker)
composer dev

# Development (dengan Docker)
docker compose up -d --build
npm run dev

# Testing
composer test

# Linting
composer lint
composer lint:check

# Static Analysis
composer types:check
```

## Struktur Dokumentasi

```
docs/
├── README.md          # File ini
├── ARCHITECTURE.md    # Arsitektur dan struktur aplikasi
├── DOCKER.md          # Panduan setup Docker
├── CONTRIBUTING.md    # Panduan kontribusi
└── DEPLOYMENT.md      # Panduan deployment
```

## Status Proyek

- [x] Setup Laravel + Livewire + Flux
- [x] Setup Docker (App + MySQL + phpMyAdmin)
- [x] Setup authentication (Fortify)
- [x] Modul Admin (CRUD Unit, Jabatan, Pegawai, User)
- [x] Modul Pimpinan (Penugasan, Validasi, Lihat Pegawai)
- [x] Modul Pegawai (Lihat Tugas, Update Status)
- [x] Integrasi WhatsApp WAHA API
- [x] Ekspor Laporan PDF
- [ ] Testing lengkap
