# Arsitektur Sistem

## Overview

Sistem ini dibangun dengan pola MVC (Model-View-Controller) menggunakan framework Laravel dengan Livewire untuk interaktivitas frontend.

## Struktur Direktori

```
RRI/
├── app/
│   ├── Actions/Fortify/     # Auth actions (register, reset password)
│   ├── Console/             # Artisan commands
│   ├── Http/                # Controllers, Middleware, Requests
│   ├── Livewire/            # Livewire components
│   │   └── Actions/         # Livewire action classes
│   ├── Models/              # Eloquent models
│   └── Providers/           # Service providers
├── config/                  # Configuration files
├── database/
│   ├── factories/           # Model factories
│   ├── migrations/          # Database migrations
│   └── seeders/             # Database seeders
├── docker/                  # Docker configuration
│   ├── nginx.conf           # Nginx server config
│   └── startup.sh           # Container entrypoint
├── public/                  # Public assets (index.php, etc.)
├── resources/
│   ├── css/                 # Tailwind CSS + Flux
│   ├── js/                  # JavaScript (Alpine.js)
│   └── views/               # Blade templates
│       ├── components/      # Reusable components
│       ├── flux/            # Flux UI components
│       ├── layouts/         # Layout templates
│       └── pages/           # Page-specific views
│           ├── auth/        # Authentication pages
│           └── settings/    # User settings pages
├── routes/
│   ├── web.php              # Web routes
│   ├── settings.php         # Settings routes
│   └── console.php          # Console commands
├── storage/                 # App storage (logs, cache, sessions)
├── tests/                   # PHPUnit tests
│   ├── Feature/             # Feature tests
│   └── Unit/                # Unit tests
└── vendor/                  # Composer dependencies
```

## Alur Aplikasi

### Request Lifecycle

1. Request masuk ke `public/index.php`
2. Bootstrap Laravel (`bootstrap/app.php`)
3. Route matching (`routes/web.php`)
4. Middleware execution (auth, verified, dll)
5. Controller/Livewire component handling
6. Response generation

### Authentication Flow

```
User → Login (Fortify) → Dashboard (role-based)
                         ├── Admin → Kelola Data
                         ├── Pimpinan → Kelola Tugas
                         └── Pegawai → Lihat Tugas
```

### Database Schema (Planned)

```
┌─────────────┐     ┌─────────────┐
│    unit      │     │   jabatan   │
├─────────────┤     ├─────────────┤
│ id_unit (PK)│     │ id_jabatan  │
│ nama_unit   │     │ nama_jabatan│
└──────┬──────┘     └──────┬──────┘
       │                   │
       └─────────┬─────────┘
                 │
        ┌────────▼────────┐
        │     pegawai     │
        ├─────────────────┤
        │ id_pegawai (PK) │
        │ id_unit (FK)    │
        │ id_jabatan (FK) │
        │ nip             │
        │ nama_pegawai    │
        │ no_hp           │
        │ username        │
        │ password        │
        └────────┬────────┘
                 │
        ┌────────▼────────┐
        │      tugas      │
        ├─────────────────┤
        │ id_tugas (PK)   │
        │ id_pegawai (FK) │
        │ nama_tugas      │
        │ format          │
        │ tanggal_produksi│
        │ tanggal_rapat   │
        │ tempat          │
        │ topik           │
        │ status_tugas    │
        └─────────────────┘
```

## Key Technologies

### Livewire + Flux UI

- Livewire: Component-based PHP frontend
- Flux UI: Pre-built UI component library
- View files di `resources/views/pages/settings/` menggunakan prefix `⚡`

### Tailwind CSS 4

- Configuration di `resources/css/app.css`
- Custom theme: Instrument Sans font
- Dark mode support via `.dark` class

### Laravel Fortify

- Authentication scaffolding
- Features: registration, password reset, 2FA, passkeys
- Config di `config/fortify.php`
- Actions di `app/Actions/Fortify/`

### Vite

- Entry points: `resources/css/app.css`, `resources/js/app.js`, `resources/js/passkeys.js`
- Plugins: Laravel Vite Plugin, Tailwind CSS
- Hot reload via `npm run dev`
