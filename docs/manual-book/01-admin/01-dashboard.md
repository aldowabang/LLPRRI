# Dashboard Admin

Halaman ringkasan dan pintu navigasi khusus role Admin.

## 1. Tujuan

Menampilkan jumlah data master (Pegawai, Unit, Jabatan) sekilas dan menyediakan
jalan pintas ke setiap halaman pengelolaan. Ini halaman pertama yang dilihat
Admin setelah login, untuk memastikan fondasi data sudah lengkap sebelum alur
tugas berjalan.

## 2. Info Cepat

| Item | Keterangan |
|------|------------|
| Akses role | `admin` (middleware `role:admin`, prefix `/admin`) |
| URL | `/admin/dashboard` |
| Route name | `admin.dashboard` (`routes/web.php:40`) |
| File Component | `app/Livewire/Admin/Dashboard.php` |
| File Blade | `resources/views/livewire/admin/dashboard.blade.php` |
| Menu sidebar | Dashboard (ikon home), Unit, Jabatan, Pegawai, User (`sidebar.blade.php:16-30`) |

## 3. Fungsi Rinci Tiap Elemen Layar

Berdasarkan `Dashboard.php:12-19` dan `dashboard.blade.php`:

- **Judul + sapaan** (blade baris 2-3): `Dashboard Admin` + `Selamat datang,
  {{ auth()->user()->name }}`. Fungsinya konfirmasi bahwa yang login benar akun
  admin (nama pengelola), bukan akun pimpinan/pegawai.
- **Kartu Total Pegawai** (blade baris 6-9): angka `{{ $totalPegawai }}` dari
  `Pegawai::count()`. Fungsinya mengukur apakah data pegawai sudah diinput.
  Angka 0 = belum ada pegawai, tugas tidak bisa dibuat.
- **Kartu Total Unit** (blade baris 10-13): angka `Unit::count()`. Fungsinya
  memastikan unit kerja (acuan `pegawais.id_unit`) tersedia sebelum isi pegawai.
- **Kartu Total Jabatan** (blade baris 14-17): angka `Jabatan::count()`.
  Fungsinya memastikan jabatan tersedia (acuan `pegawais.id_jabatan` sekaligus
  filter dropdown crew pimpinan).
- **Tombol Kelola Unit** (baris 21): `flux:button href=admin.units.index`.
  Fungsinya lompat ke CRUD unit.
- **Tombol Kelola Jabatan** (baris 22): lompat ke CRUD jabatan.
- **Tombol Kelola Pegawai** (baris 23): lompat ke CRUD pegawai.
- **Tombol Kelola User** (baris 24): lompat ke CRUD akun login + role.
- Halaman ini **read-only**: tidak ada form, tidak ada `wire:click` ubah data,
  tidak ada query tugas/WA. Semua angka dihitung ulang setiap `render()`.

## 4. Langkah Penggunaan

1. Login sebagai Admin → otomatis masuk `/admin/dashboard` (via `/dashboard` match role).
2. Baca tiga angka: jika Unit/Jabatan masih 0, isi itu dulu.
3. Klik tombol kelola sesuai kebutuhan (urutan awal: Unit → Jabatan → Pegawai → User).
4. Kembali ke dashboard kapan saja via sidebar **Dashboard** atau klik logo.

## 5. Validasi dan Pesan Sistem

- Tidak ada validasi form di halaman ini.
- Jika angka tidak bertambah setelah Simpan di halaman CRUD, refresh; data
  dihitung langsung dari DB sehingga selalu akurat.

## 6. Relasi Database

- Baca: `pegawais` (hitung), `units` (hitung), `jabatans` (hitung).
- Tidak menulis tabel apa pun dari halaman ini.

## 7. Screenshot

> 📷 Screenshot: [Dashboard Admin dengan sapaan + 3 kartu statistik + 4 tombol kelola]

| Elemen | Keterangan untuk manual cetak |
|--------|-------------------------------|
| Sapaan | Nama admin yang login |
| 3 kartu | Total Pegawai / Unit / Jabatan |
| 4 tombol | Navigasi ke tiap CRUD |

## 8. Tips

- Urutan pengisian awal yang disarankan: Unit → Jabatan → Pegawai → User.
  Form Pegawai wajib memilih Unit+Jabatan, dan form User sebaiknya mengait ke Pegawai.
- Sidebar memuat menu yang sama; tombol di dashboard hanya jalan pintas.
