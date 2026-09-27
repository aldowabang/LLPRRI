# Dashboard Pimpinan

Halaman pantau ringkas untuk Pimpinan — pusat komando harian.

## 1. Tujuan

Menampilkan jumlah pegawai dan tugas per status global serta 5 tugas terbaru
sebagai pintu masuk cepat ke detail/ACC. Pimpinan melihat beban kerja tanpa
harus membuka daftar penuh.

## 2. Info Cepat

| Item | Keterangan |
|------|------------|
| Akses role | `pimpinan` (middleware `role:pimpinan`) |
| URL | `/pimpinan/dashboard` |
| Route name | `pimpinan.dashboard` (`routes/web.php:51`) |
| File Component | `app/Livewire/Pimpinan/Dashboard.php` |
| File Blade | `resources/views/livewire/pimpinan/dashboard.blade.php` |
| Menu sidebar | Dashboard, Pegawai, Tugas (`sidebar.blade.php:32-40`) |

## 3. Fungsi Rinci Tiap Elemen Layar

Berdasarkan `Dashboard.php:12-23` dan blade baris 1-73:

- **Judul + sapaan** (baris 2-3): `Dashboard Pimpinan` + `Selamat datang,
  {{ auth()->user()->name }}`. Fungsinya konfirmasi akun pimpinan yang login.
- **Kartu Total Pegawai** (baris 6-9): `Pegawai::count()`. Fungsinya jumlah SDM
  yang bisa ditugaskan. Berbeda dengan admin: tidak ada tombol kelola, hanya angka.
- **Kartu Total Tugas** (baris 10-13): `Tugas::count()`. Fungsinya total nota
  yang pernah diterbitkan.
- **Kartu Belum Mulai** (baris 14-17, kuning): `Tugas::where(status=belum_mulai)
  ->count()`. Fungsinya antrean tugas baru yang belum dikerjakan siapa pun.
- **Kartu Proses** (baris 18-21, biru): `where(status=proses)->count()`.
  Fungsinya tugas sedang berjalan di lapangan.
- **Kartu Selesai / ACC** (baris 22-25, hijau): `{{ $tugasSelesai + $tugasAcc }}`
  — gabungan `selesai` (menunggu ACC) + `acc` (final). Fungsinya satu angka
  penyelesaian; untuk memisahkan keduanya buka Daftar Tugas + filter.
- **Header Tugas Terbaru + tombol Buat Tugas** (baris 28-32): judul + tombol
  primary `href=pimpinan.tugases.create`. Fungsinya jalan pintas terbitkan nota
  tanpa lewat daftar.
- **Tabel Tugas Terbaru** (baris 33-71): query
  `Tugas::with('pegawai')->latest()->take(5)`. Kolom:
  - **Nama Tugas** (link biru → `pimpinan.tugases.show`, baris 47): klik untuk
    pantau/ACC. Fungsinya navigasi utama.
  - **Pegawai** (`pegawai->nama_pegawai ?? '-'`, baris 49): pegawai utama
    (`tugases.id_pegawai`, fallback produser pertama saat create).
  - **Status** badge (baris 51-60): warna per `match(status)`: belum abu,
    proses kuning, selesai biru, acc hijau; teks `status_label`. Fungsinya
    status global tugas (bukan per crew).
  - **Tanggal** (baris 62): `tanggal_produksi->format('d/m/Y')` (jadwal produksi,
    bukan tanggal buat).
- **Kosong**: `Belum ada tugas.` bila belum ada nota.

## 4. Langkah Penggunaan

1. Login sebagai Pimpinan → mendarat di sini.
2. Baca kartu: jika Belum Mulai menumpuk, prioritaskan pemantauan; jika Proses
   banyak, cek detail satu per satu.
3. Klik nama tugas di tabel untuk membuka detail (cek crew, cetak nota, ACC).
4. Klik **Buat Tugas** untuk nota baru.

## 5. Validasi dan Pesan Sistem

- Tidak ada form; read-only + navigasi. Tidak ada pesan sukses di halaman ini.

## 6. Relasi Database

- Baca: `pegawais` (hitung), `tugases` + relasi `pegawai` (5 terbaru).
- Tidak membaca `tugas_crews` di sini (rincian crew ada di Detail).

## 7. Screenshot

> 📷 Screenshot: [5 kartu + tabel Tugas Terbaru dengan badge warna]

| Elemen | Anotasi |
|--------|---------|
| Kartu kuning/biru/hijau | Status global |
| Link nama tugas | Ke halaman detail |
| Tombol Buat Tugas | Ke form nota |

## 8. Tips

- Jadikan halaman ini titik awal harian: cek `belum_mulai/proses` sebelum
  menerbitkan nota baru agar crew tidak overload.
- Kartu Selesai/ACC menggabung dua status; untuk melihat yang benar-benar final,
  filter `acc` di Daftar Tugas.
