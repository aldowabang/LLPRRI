# AGENTS.md

## Project

Sistem Manajemen Tugas LPP RRI Kupang — task management app for an Indonesian broadcasting organization.
Laravel 13 + Livewire 4 + Flux UI + Tailwind CSS 4 + Vite 8. PHP 8.3+.

See `prd.md` for full requirements. See `docs/` for detailed documentation.

## Commands

- `composer setup` — full first-time setup (install, .env, key, migrate, npm, build)
- `composer dev` — start dev server (artisan + Vite)
- `composer test` — lint (Pint) → typecheck (Larastan) → test (PHPUnit) — runs in sequence
- `composer lint` — Pint auto-fix
- `composer lint:check` — Pint dry-run
- `composer types:check` — Larastan level 7
- `composer ci:check` — CI entrypoint (runs `composer test`)

Tests use SQLite `:memory:` (configured in `phpunit.xml`). No external services needed.

## Docker

Ports (no conflicts with existing containers):

| Service | Port |
|---------|------|
| Laravel App | 8002 |
| MySQL | 3307 |
| phpMyAdmin | 8083 |
| WAHA | 3000 |

```bash
docker compose up -d --build    # Start containers (builds assets first)
docker compose exec app bash    # Shell into app
docker compose down             # Stop containers
docker compose down -v          # Stop and remove volumes
```

Vite dev server runs on host via `npm run dev` (port 5173).
Assets must be built (`npm run build`) before `docker compose up --build`.

## Structure

- `app/Livewire/Admin/` — Admin components (Dashboard, Unit, Jabatan, Pegawai, User)
- `app/Livewire/Pimpinan/` — Pimpinan components (Dashboard, Pegawai, Tugas)
- `app/Livewire/Pegawai/` — Pegawai components (Dashboard, Tugas)
- `app/Models/` — Eloquent models (User, Unit, Jabatan, Pegawai, Tugas)
- `app/Services/WhatsAppService.php` — WAHA API integration
- `app/Http/Middleware/RoleMiddleware.php` — Role-based access control
- `routes/web.php` — main routes (settings in `routes/settings.php`)
- `resources/views/livewire/` — Blade views for Livewire components
- `resources/css/app.css` — Tailwind + Flux theme imports
- `docker/` — Docker config (nginx.conf, startup.sh)

## Roles

- `admin` — Manages units, jabatans, pegawais, and users
- `pimpinan` — Creates tasks, validates completion, views reports
- `pegawai` — Views assigned tasks, updates task status

## Conventions

- Code style: Laravel Pint with `laravel` preset (`pint.json`)
- Static analysis: Larastan at level 7 (`phpstan.neon`)
- EditorConfig: 4-space indent, LF line endings, 2-space for YAML
- Tests in `tests/Feature/` and `tests/Unit/`. Base class `Tests\TestCase` has a `skipUnlessFortifyHas()` helper.
- Vite entrypoints: `resources/css/app.css`, `resources/js/app.js`, `resources/js/passkeys.js`

## Gotchas

- `composer test` runs lint + typecheck + PHPUnit in order — don't skip steps
- Flux view files in `resources/views/pages/settings/` use `⚡` prefix — don't rename
- PRD specifies MySQL for production but dev/tests use SQLite — be aware when writing queries
- Docker uses MySQL (port 3307) — update `.env` when switching between Docker and local
- `docker/startup.sh` runs migrations automatically on container start
- `DB_PASSWORD` in `docker-compose.yml` must match `MYSQL_ROOT_PASSWORD` (both `secret`)
- Build assets (`npm run build`) before `docker compose up —build` — no Node.js in container
- WhatsApp notifications use WAHA API on port 3000 — configure `WAHA_URL` in `.env`
