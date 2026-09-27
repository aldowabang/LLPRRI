# Daftar Tugas (Pegawai)

Daftar seluruh tugas yang ditugaskan ke pegawai yang login.

## 1. Tujuan

Memberi **daftar kerja pribadi lengkap** (bukan 5 terbaru saja) yang bisa
dicari nama acaranya dan dibuka detailnya satu per satu.

## 2. Info Cepat

| Item | Keterangan |
|------|------------|
| Akses role | `pegawai` |
| URL | `/pegawai/tugases` |
| Route name | `pegawai.tugases.index` (`routes/web.php:85`) |
| File Component | `app/Livewire/Pegawai/Tugas/Index.php` (`WithPagination`, `$search`) |
| File Blade | `resources/views/livewire/pegawai/tugas/index.blade.php` |

## 3. Fungsi Rinci Tiap Elemen Layar

Berdasarkan `Index.php:15-29`:

- **Cakupan**: `$pegawai = auth()->user()->pegawai`; bila ada →
  `Tugas::forPegawai(id)->when(search)->latest()->paginate(10)`; bila tidak ada
  → `collect()` kosong. Fungsinya daftar selalu milik sendiri.
- **Pencarian live** (`wire:model.live.debounce.300ms="search"`,
  `where nama_tugas like %search%`): filter nama acara realtime tanpa tombol.
  Fungsinya menemukan nota lama dengan cepat.
- **Tabel** 10/halaman, terbaru dulu. Kolom:
  - **Nama Tugas**: judul acara.
  - **Format**: Talkshow/Liputan/dll.
  - **Tanggal Produksi** (`d/m/Y`): jadwal Hari H.
  - **Status** badge global (abu/kuning/biru/hijau + `status_label`).
  - **Aksi Detail** (→ `pegawai.tugases.show` per `id_tugas`): buka pengerjaan.
- **Tidak ada**: filter status (beda dengan pimpinan), tombol Buat/Unduh/Hapus,
  kolom Pegawai (sudah pasti milik sendiri).
- **Kosong/paginasi**: pesan tidak ada data + `links()`.

## 4. Langkah Penggunaan

1. Buka sidebar **Tugas Saya** (`/pegawai/tugases`).
2. Ketik kata kunci di pencarian bila daftar panjang (mis. `liputan`).
3. Baca badge untuk prioritas (dahulukan Belum/Proses).
4. Klik **Detail** untuk mengerjakan: baca peran, Mulai, Selesaikan, cetak nota.

## 5. Validasi dan Pesan Sistem

- Search kosong = semua milik sendiri + paginasi. Tidak ada validasi lain.

## 6. Relasi Database

- Baca: `tugases` + `tugas_crews` milik `users.pegawai_id` aktif via
  `forPegawai`. Tidak menulis dari halaman ini.

## 7. Screenshot

> 📷 Screenshot: [search + tabel Nama/Format/Tanggal/Status/Detail]

## 8. Tips

- Badge di daftar = status **global** tugas. Status **pribadi** Anda (yang bisa
  Anda ubah) terlihat di banner biru halaman Detail.
- Jika tugas yang dicari tak muncul: (a) salah ketik, (b) Anda memang tidak
  ditugaskan di nota itu (tanya pimpinan), atau (c) akun belum dikaitkan ke
  pegawai (tanya admin).
