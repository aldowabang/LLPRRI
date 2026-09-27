# Welcome dan Redirect Dashboard

Halaman awal publik dan mekanisme pengarah otomatis ke dashboard per role.

## 1. Tujuan

Menyambut pengunjung yang belum login (`/`) dan mengembalikan pengguna yang
sudah login (`/dashboard`) ke halaman kerja yang tepat sesuai role-nya, tanpa
pengguna harus menghafal URL per role.

## 2. Info Cepat

| Item | Keterangan |
|------|------------|
| Akses role | Publik (`/`) + semua role login (`/dashboard`) |
| URL | `/` (nama route `home`, view `welcome`), `/dashboard` (nama route `dashboard`) |
| File kode | `routes/web.php:21-34`, `resources/views/welcome.blade.php`, `resources/views/dashboard.blade.php` |

## 3. Fungsi Rinci Tiap Elemen / Logika

- **Halaman `/` (`welcome`)**: tampilan sambutan aplikasi (judul sistem +
  tautan Masuk/Dashboard). Fungsinya landing page publik; tidak menampilkan
  data sensitif. Pengunjung klik Masuk → `/login`.
- **Route `/dashboard`**: tidak punya tampilan sendiri. Fungsinya dispatcher
  dengan logika `match ($user->role)` (`routes/web.php:27-33`):
  - `admin` → redirect `admin.dashboard` (`/admin/dashboard`)
  - `pimpinan` → redirect `pimpinan.dashboard` (`/pimpinan/dashboard`)
  - `pegawai` → redirect `pegawai.dashboard` (`/pegawai/dashboard`)
  - selain itu → redirect `home` (`/`)
- **Grup middleware** (`routes/web.php:23`): `['auth','verified']` — hanya user
  login + email terverifikasi yang bisa memakai dispatcher.
- **Proteksi per role** (`routes/web.php:37-96`): prefix `admin/pimpinan/pegawai`
  masing-masing dikunci `role:<nama>`. Jadi walau tahu URL, pegawai tidak bisa
  membuka `/admin/*` dan sebaliknya.

## 4. Langkah Penggunaan

1. Buka `/`. Jika belum login, klik **Masuk**.
2. Jika sudah login, buka `/dashboard` untuk kembali ke halaman kerja Anda
   (berguna setelah tersesat atau setelah bookmark kadaluarsa).
3. Bookmark URL dashboard sesuai role agar lebih cepat:
   - Admin: `/admin/dashboard`
   - Pimpinan: `/pimpinan/dashboard`
   - Pegawai: `/pegawai/dashboard`
4. Logo aplikasi di sidebar (`sidebar.blade.php:9`) juga mengarah ke
   `route('dashboard')`, jadi klik logo = kembali ke halaman kerja.

## 5. Validasi dan Pesan Sistem

- Akses `/dashboard` tanpa login → dialihkan ke `/login` (middleware `auth`).
- Akun belum verifikasi email → dialihkan ke alur verifikasi (middleware `verified`).
- Akses URL role lain (mis. pegawai membuka `/admin/units`) → ditolak
  middleware `role` (403/redirect sesuai `RoleMiddleware`).
- Role tak dikenal → dikembalikan ke `home`.

## 6. Relasi Database

- Keputusan redirect membaca `users.role` milik pengguna yang sedang login
  (`auth()->user()`). Tidak membaca tabel lain.

## 7. Screenshot

> 📷 Screenshot: [halaman Welcome `/` dan contoh ketiga Dashboard]

| Elemen | Keterangan |
|--------|------------|
| Welcome | Tautan Masuk/Dashboard |
| Dispatcher | Tidak terlihat, terjadi otomatis |

## 8. Tips

- Jika setelah login Anda kembali ke halaman awal, buka manual `/dashboard`.
- Untuk manual cetak, cukup tampilkan satu screenshot Welcome + tiga screenshot
  dashboard (Admin/Pimpinan/Pegawai) berdampingan.
