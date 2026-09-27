# Daftar Tugas (Pimpinan)

Halaman manajemen dan pemantauan seluruh Nota Produksi.

## 1. Tujuan

Satu tempat untuk **melihat semua nota**, mencari nama acara, menyaring per
status, membuka detail/ACC, menerbitkan nota baru, dan mengunduh rekap PDF.
Ini halaman operasional utama pimpinan.

## 2. Info Cepat

| Item | Keterangan |
|------|------------|
| Akses role | `pimpinan` |
| URL | `/pimpinan/tugases` |
| Route name | `pimpinan.tugases.index` (`routes/web.php:53`) |
| File Component | `app/Livewire/Pimpinan/Tugas/Index.php` (`$search`, `$status`, `WithPagination`) |
| File Blade | `resources/views/livewire/pimpinan/tugas/index.blade.php` |

## 3. Fungsi Rinci Tiap Elemen Layar

Berdasarkan `Index.php:17-26` dan blade baris 1-66:

- **Judul + dua tombol header** (baris 2-8):
  - `Buat Tugas` (primary, → `pimpinan.tugases.create`): terbitkan nota baru.
  - `Unduh PDF` (subtle, ikon `document-arrow-down`, → `pimpinan.laporan.pdf`):
    unduh rekap semua tugas (file `laporan-tugas-rri.pdf`). Fungsinya arsip/
    laporan ke atasan tanpa membuka satu per satu.
- **Input Cari tugas** (baris 11): `flux:input wire:model.live.debounce.300ms=
  "search" placeholder="Cari tugas..." icon=magnifying-glass`. Query
  `when($search, where nama_tugas like %search%)`. Fungsinya cari nama acara
  realtime (cth. `podcast`).
- **Select status** (baris 12-18): `wire:model.live="status"`, opsi Semua Status
  (value kosong) / Belum Mulai / Proses / Selesai / ACC. Query
  `when($status, where status_tugas=$status)`. Fungsinya fokus ke tahap tertentu,
  mis. hanya `selesai` yang menunggu ACC. Kedua filter **berlaku bersamaan**.
- **Tabel** (baris 21-63): `Tugas::with('pegawai')->latest()->paginate(10)`
  (terbaru dulu). Kolom:
  - **Nama Tugas** (bold): nama acara/nota.
  - **Pegawai** (`pegawai->nama_pegawai ?? '-'`): pegawai utama
    (`tugases.id_pegawai`, diisi fallback produser pertama saat create).
    Bukan daftar seluruh crew — crew lengkap ada di Detail.
  - **Format** (`format`): Talkshow/Liputan/Siaran/dll.
  - **Tanggal Produksi** (`tanggal_produksi->format('d/m/Y')`): jadwal produksi,
    bukan tanggal penerbitan.
  - **Status** badge: abu (belum), kuning (proses), biru (selesai), hijau (acc);
    teks `status_label`.
  - **Aksi**: tombol `Detail` (sm, subtle → `pimpinan.tugases.show` per
    `id_tugas`). Tidak ada tombol hapus/edit langsung — perubahan status lewat
    halaman Detail.
- **Kosong**: `Belum ada tugas.` **Paginasi** `links()` mengikuti filter aktif.

## 4. Tombol dan Aksi

| Tombol | Letak | Fungsi di sistem |
|--------|-------|------------------|
| Buat Tugas (primary) | header kanan | → form nota baru |
| Unduh PDF (subtle) | header kanan | → download rekap PDF seluruh tugas |
| Detail (sm) | per baris | → halaman detail + ACC per `id_tugas` |

## 5. Langkah Penggunaan

1. Buka sidebar **Tugas** (`/pimpinan/tugases`).
2. Untuk mencari acara: ketik di **Cari tugas**.
3. Untuk fokus tahap: pilih **Status** (mis. `Selesai` = antrean ACC).
4. Klik **Detail** pada baris untuk memantau crew / ACC / cetak nota.
5. Klik **Unduh PDF** untuk rekap; **Buat Tugas** untuk nota baru.

## 6. Validasi dan Pesan Sistem

- Tidak ada form simpan; filter kosong = tampil semua.
- Paginasi reset mengikuti perubahan filter (perilaku Livewire standar).

## 7. Relasi Database

- Baca: `tugases` + relasi `pegawai` (utama). Tidak membaca `tugas_crews` di
  daftar (hanya di Detail).

## 8. Screenshot

> 📷 Screenshot: [header 2 tombol + baris filter + tabel 6 kolom + tombol Detail]

| Elemen | Anotasi |
|--------|---------|
| Search | Filter nama realtime |
| Select status | 5 opsi |
| Badge | Arti warna (lihat lampiran) |

## 9. Tips

- Arti badge lengkap lihat Lampiran Status Tugas.
- **Unduhan PDF merekap seluruh data terbaru, bukan hasil filter/paginasi aktif.**
  Untuk laporan per periode, minta pengembang tambah filter tanggal atau cetak
  manual dari layar.
