# Data Pegawai (Pimpinan)

Daftar pegawai versi read-only untuk Pimpinan.

## 1. Tujuan

Memberi Pimpinan referensi lengkap siapa saja yang bisa ditugaskan (NIP, nama,
unit, jabatan, no HP) + pencarian cepat, **tanpa kemampuan mengubah data**.
Perubahan data hanya lewat Admin agar master terjaga.

## 2. Info Cepat

| Item | Keterangan |
|------|------------|
| Akses role | `pimpinan` |
| URL | `/pimpinan/pegawais` |
| Route name | `pimpinan.pegawais.index` (`routes/web.php:52`) |
| File Component | `app/Livewire/Pimpinan/Pegawai/Index.php` (`WithPagination`, `$search`) |
| File Blade | `resources/views/livewire/pimpinan/pegawai/index.blade.php` |

## 3. Fungsi Rinci Tiap Elemen Layar

Berdasarkan `Index.php:15-23`:

- **Kolom pencarian live** (`wire:model.live.debounce.300ms="search"`):
  setiap ketikan (jeda 300ms) memicu query ulang `when($search, where
  nama_pegawai like %search%)`. Fungsinya filter nama tanpa tombol Cari / reload.
- **Tabel**: query `Pegawai::with(['unit','jabatan'])->orderBy('nama_pegawai')
  ->paginate(10)`. Eager load menghindari N+1; urut abjad memudahkan cari orang.
  Kolom:
  - **NIP** (`nip`): identitas resmi.
  - **Nama** (`nama_pegawai`): nama lengkap.
  - **Unit** (`unit->nama_unit`): penempatan; `-` bila tak ada.
  - **Jabatan** (`jabatan->nama_jabatan`): menentukan peran crew yang cocok.
  - **No HP** (`no_hp`): nomor tujuan WA nota (lihat Tips).
- **Paginasi** 10/halaman. Kosong → pesan tidak ada data.
- **Tidak ada**: kolom Aksi, tombol Tambah/Edit/Hapus, modal. Component hanya
  punya properti `$search` + `render()` — tidak ada method CRUD sama sekali.
  Ini pembeda utama dengan Data Pegawai Admin.

## 4. Langkah Penggunaan

1. Buka sidebar **Pegawai**.
2. Ketik nama di pencarian (mis. `mella`) untuk menyaring langsung.
3. Baca unit/jabatan untuk memilih orang yang tepat sebelum buat nota.
4. Catat/ingat NIP bila perlu, lalu pindah ke **Buat Tugas**.

## 5. Validasi dan Pesan Sistem

- Pencarian kosong → tampil semua + paginasi normal.
- Tidak ada validasi form karena tidak ada form.

## 6. Relasi Database

- Baca: `pegawais` + relasi `unit` + `jabatan`. Tidak menulis apa pun.

## 7. Screenshot

> 📷 Screenshot: [search + tabel NIP/Nama/Unit/Jabatan/No HP tanpa kolom Aksi]

## 8. Tips

- Jika data salah (nama/unit/no HP salah): hubungi Admin — Pimpinan tidak bisa
  mengedit dari sini (sengaja dikunci).
- Nomor HP di sini = tujuan `broadcastNotaProduksi()`. Jika crew mengaku tak
  terima WA, cek nomor di halaman ini dulu, lalu minta Admin memperbaiki di
  `/admin/pegawais`.
