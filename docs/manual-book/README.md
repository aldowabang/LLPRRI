# Manual Book — Sistem Manajemen Tugas LPP RRI Kupang

Panduan penggunaan aplikasi berbasis peran (role). Ditujukan untuk pengguna awam
(Admin, Pimpinan, Pegawai) serta penyusun dokumentasi / manual book cetak.
Setiap bab menjelaskan **fungsi rinci tiap tampilan**: apa yang terlihat di layar,
apa kegunaan setiap tombol/kolom/field, apa yang terjadi di sistem saat diklik,
serta aturan database di belakangnya.

## 1. Tentang Sistem

Sistem manajemen tugas internal LPP RRI Kupang berbasis web (Laravel + Livewire +
Flux UI). Mengotomatisasi alur penugasan, pelacakan status, dan notifikasi
WhatsApp (Fonnte API). Referensi kebutuhan: `prd.md`.

Alur bisnis ringkas (lihat `prd.md` §8):

1. Pengguna login dan diarahkan ke dashboard sesuai role (`/dashboard` → match role).
2. Admin menginput Unit, Jabatan, Data Pegawai (termasuk nomor HP untuk WA), dan akun User.
3. Pimpinan membuat Nota Produksi / tugas dan menugaskan tim (crew, 10 peran).
4. Sistem mengirim notifikasi WhatsApp ke setiap pegawai yang ditugaskan (dedup per orang).
5. Pegawai login, melihat tugas miliknya, menekan **Mulai Tugas**, mengerjakan, lalu **Tandai Selesai**.
6. Sistem mengirim notifikasi WhatsApp ke pimpinan.
7. Pimpinan melakukan **ACC** (validasi akhir) per crew maupun per tugas hingga status `acc`.
8. Pimpinan mencetak Nota Produksi per tugas dan Laporan PDF rekap semua tugas.

## 2. Role di Database

Role disimpan di tabel `users.role` (ENUM: `admin`, `pimpinan`, `pegawai`,
default `pegawai`; migrasi `2026_08_16_070612_add_role_and_pegawai_to_users_table.php`).
Kolom `users.pegawai_id` (nullable FK → `pegawais.id_pegawai`, `nullOnDelete`)
menautkan akun login ke data pegawai. Pembatasan akses memakai middleware
`role:<nama-role>` di `routes/web.php`. Helper `isAdmin()/isPimpinan()/isPegawai()`
di model `User` dipakai sidebar (`layouts/app/sidebar.blade.php:15-48`) untuk
menampilkan menu yang berbeda per role.

| Role | Tugas utama | Tidak dilakukan |
|------|-------------|-----------------|
| **Admin** | Kelola data master: Unit, Jabatan, Pegawai, User | Tidak terlibat penugasan harian |
| **Pimpinan** | Buat tugas, pantau progres, ACC per crew/tugas, cetak nota + laporan | Tidak mengelola data master |
| **Pegawai** | Lihat tugas milik sendiri, mulai & selesaikan bagian sendiri, cetak nota | Tidak bisa ACC, tidak melihat tugas orang lain |

## 3. Matriks Menu per Role

| Tampilan | URL | Admin | Pimpinan | Pegawai |
|----------|-----|-------|----------|---------|
| Login | `/login` | v | v | v |
| Welcome | `/` | v | v | v |
| Redirect dashboard | `/dashboard` | v | v | v |
| Pengaturan profil | `/settings/*` | v | v | v |
| Dashboard Admin | `/admin/dashboard` | v | – | – |
| Data Unit | `/admin/units` | v | – | – |
| Data Jabatan | `/admin/jabatans` | v | – | – |
| Data Pegawai (admin) | `/admin/pegawais` | v | – | – |
| Data User | `/admin/users` | v | – | – |
| Dashboard Pimpinan | `/pimpinan/dashboard` | – | v | – |
| Data Pegawai (pimpinan, read-only) | `/pimpinan/pegawais` | – | v | – |
| Daftar Tugas (pimpinan) | `/pimpinan/tugases` | – | v | – |
| Buat Tugas | `/pimpinan/tugases/create` | – | v | – |
| Detail & Validasi Tugas | `/pimpinan/tugases/{id_tugas}` | – | v | – |
| Nota Produksi PDF | `/pimpinan/tugases/{id_tugas}/nota-produksi` | – | v | – |
| Laporan PDF | `/pimpinan/laporan/pdf` | – | v | – |
| Dashboard Pegawai | `/pegawai/dashboard` | – | – | v |
| Daftar Tugas (pegawai) | `/pegawai/tugases` | – | – | v |
| Detail & Update Status | `/pegawai/tugases/{id_tugas}` | – | – | v |
| Nota Produksi PDF | `/pegawai/tugases/{id_tugas}/nota-produksi` | – | – | v |

## 4. Daftar Isi Manual

- `00-umum/` — dipakai semua role
  - [Login](00-umum/01-login.md)
  - [Welcome dan Redirect Dashboard](00-umum/02-welcome-dan-redirect-dashboard.md)
  - [Pengaturan Profil](00-umum/03-pengaturan-profil.md)
- `01-admin/` — khusus Admin
  - [Dashboard Admin](01-admin/01-dashboard.md)
  - [Data Unit](01-admin/02-data-unit.md)
  - [Data Jabatan](01-admin/03-data-jabatan.md)
  - [Data Pegawai](01-admin/04-data-pegawai.md)
  - [Data User](01-admin/05-data-user.md)
- `02-pimpinan/` — khusus Pimpinan
  - [Dashboard Pimpinan](02-pimpinan/01-dashboard.md)
  - [Data Pegawai](02-pimpinan/02-data-pegawai.md)
  - [Daftar Tugas](02-pimpinan/03-daftar-tugas.md)
  - [Buat Tugas](02-pimpinan/04-buat-tugas.md)
  - [Detail dan Validasi Tugas](02-pimpinan/05-detail-dan-validasi-tugas.md)
  - [Nota Produksi PDF](02-pimpinan/06-nota-produksi-pdf.md)
  - [Laporan PDF](02-pimpinan/07-laporan-pdf.md)
- `03-pegawai/` — khusus Pegawai
  - [Dashboard Pegawai](03-pegawai/01-dashboard.md)
  - [Daftar Tugas](03-pegawai/02-daftar-tugas.md)
  - [Detail dan Update Status](03-pegawai/03-detail-dan-update-status.md)
  - [Nota Produksi PDF](03-pegawai/04-nota-produksi-pdf.md)
- `04-lampiran/`
  - [Status Tugas dan Peran Crew](04-lampiran/status-tugas-dan-crew.md)
  - [Skema Database](04-lampiran/skema-database.md)

## 5. Versi PDF Satu Berkas

Seluruh isi manual ini dapat diunduh sebagai satu PDF (sampul + daftar isi +
semua bab + lembar pengesahan) lewat route `manual-book.pdf`
(`GET /manual-book/pdf`, akses role `admin` dan `pimpinan`,
generator `app/Services/ManualBookPdfService.php` + view `manual-book.pdf`).
Tombol **Manual Book (PDF)** tersedia di Dashboard Admin dan Dashboard Pimpinan.

## 6. Cara Memakai Dokumen Ini

1. Kenali role Anda (tanya Admin jika tidak tahu).
2. Baca bab `00-umum` (login + welcome + pengaturan).
3. Lanjut ke bab sesuai role (`01-admin`, `02-pimpinan`, atau `03-pegawai`).
4. Setiap bab tampilan memuat: tujuan, info cepat (URL + route + file kode),
   **fungsi rinci tiap elemen layar**, tombol & aksi, langkah penggunaan,
   validasi & pesan sistem, relasi database, dan placeholder screenshot.
5. Lampiran menjelaskan arti status (`belum_mulai/proses/selesai/acc`),
   10 peran crew, dan skema database untuk penyusun laporan.

> 📷 Setiap file tampilan memiliki bagian **Screenshot**. Tempelkan tangkapan
> layar asli aplikasi di sana saat menyusun manual book cetak.
