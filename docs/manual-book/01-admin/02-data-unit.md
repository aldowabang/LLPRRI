# Data Unit

Halaman master data Unit Kerja (mis. Bidang Pemberitaan, Teknik, dan sebagainya).

## 1. Tujuan

Mengelola daftar unit kerja yang menjadi acuan penempatan pegawai
(`pegawais.id_unit`). Tanpa unit, form Pegawai tidak bisa disimpan karena field
Unit wajib.

## 2. Info Cepat

| Item | Keterangan |
|------|------------|
| Akses role | `admin` |
| URL | `/admin/units` |
| Route name | `admin.units.index` (`routes/web.php:41`) |
| File Component | `app/Livewire/Admin/Unit/Index.php` (trait `WithPagination`) |
| File Blade | `resources/views/livewire/admin/unit/index.blade.php` |

## 3. Fungsi Rinci Tiap Elemen Layar

Berdasarkan `Unit/Index.php` dan blade:

- **Judul + tombol Tambah Unit** (blade baris 2-5): header `Unit Kerja` +
  `flux:button wire:click="openCreate" variant=primary`. Fungsinya membuka modal
  kosong untuk unit baru.
- **Notifikasi sukses** (blade baris 7-11): kotak hijau `session('success')`
  muncul setelah tambah/ubah/hapus. Fungsinya konfirmasi operasi berhasil.
- **Tabel** (blade baris 13-39): query `Unit::orderBy('id_unit')->paginate(10)`
  (`Index.php:28`). Kolom:
  - **ID** (`$unit->id_unit`): kunci primer, urutan tampil.
  - **Nama Unit** (`$unit->nama_unit`): nama resmi unit.
  - **Aksi** (rata kanan): tombol Edit + Hapus per baris.
- **Baris kosong** (blade baris 32-36): `Belum ada data unit.` bila tabel kosong.
  Fungsinya memberi tahu admin harus mulai mengisi.
- **Paginasi** (blade baris 41): `{{ $units->links() }}`. Fungsinya navigasi
  halaman 1,2,… tiap 10 baris.
- **Modal `unit-form`** (blade baris 43-52, `wire:close="close"`): judul dinamis
  `{{ $editMode ? 'Edit Unit' : 'Tambah Unit' }}`. Isi satu field
  `flux:input wire:model="nama_unit" label="Nama Unit"`. Fungsinya satu-satunya
  tempat input nama unit. Tombol **Batal** (`wire:click="close"`) menutup tanpa
  simpan; **Simpan** (`wire:click="save"`) memvalidasi + menyimpan.
- **State component** (`Index.php:13-19`): `$unitId`, `$nama_unit`, `$showModal`,
  `$editMode`. `openCreate()` (baris 32-38) mereset field + `dispatch modal-show`;
  `openEdit($unitId)` (baris 40-48) memuat `Unit::findOrFail` ke field;
  `save()` (baris 50-64) `validate()` lalu `create` atau `update`;
  `delete($unitId)` (baris 72-76) `findOrFail->delete()`.

## 4. Tombol dan Aksi

| Tombol | Letak | Fungsi di sistem |
|--------|-------|------------------|
| Tambah Unit (primary) | header | `openCreate()` → modal kosong |
| Edit (subtle, sm) | per baris | `openEdit(id)` → modal terisi nama lama |
| Hapus (danger, sm) | per baris | `delete(id)` + `wire:confirm="Hapus unit ini?"` → hapus permanen |
| Batal (subtle) | modal | `close()` → tutup + `dispatch modal-close` |
| Simpan (primary) | modal | `save()` → validasi + tulis DB + flash + tutup modal |

## 5. Langkah Penggunaan

1. Buka **Dashboard Admin → Kelola Unit** (atau sidebar **Unit**).
2. Klik **Tambah Unit**, ketik Nama Unit resmi, klik **Simpan**. Tunggu notifikasi
   hijau `Unit berhasil ditambahkan.`
3. Untuk mengubah: klik **Edit** pada baris terkait, ubah nama, klik **Simpan**
   (`Unit berhasil diupdate.`).
4. Untuk menghapus: klik **Hapus**, konfirmasi dialog browser.
   (`Unit berhasil dihapus.`).

## 6. Validasi dan Pesan Sistem

- Aturan (`Index.php:21-23`): `nama_unit` wajib (`required`), teks (`string`),
  maksimal 100 karakter (`max:100`).
- Kosong / >100 karakter →NIC gagal simpan + pesan merah di bawah field
  (error Livewire standar).
- Pesan sukses: `Unit berhasil ditambahkan. / diupdate. / dihapus.`

## 7. Relasi Database

- Tabel `units` (migrasi `2026_08_16_070609`): `id_unit` PK increment,
  `nama_unit` string(100), timestamps.
- Anak: `pegawais.id_unit` FK → `units.id_unit` dengan `cascadeOnDelete`.
  Artinya **menghapus unit menghapus semua pegawai di unit itu**. Periksa tabel
  Pegawai dulu sebelum menghapus unit.

## 8. Screenshot

> 📷 Screenshot: [tabel Unit dengan kolom ID/Nama/Aksi + modal Tambah/Edit]

| Elemen | Anotasi |
|--------|---------|
| Tombol Tambah | Pojok kanan atas |
| Modal | Satu field Nama Unit + Batal/Simpan |
| Konfirmasi hapus | Dialog browser |

## 9. Tips

- Isi Unit sebelum mengisi Pegawai (field Unit di form Pegawai wajib + dropdown
  diambil dari tabel ini `Unit::orderBy('nama_unit')`).
- Gunakan nama resmi/nomenklatur organisasi agar konsisten di laporan PDF
  (kolom Unit di laporan pimpinan diambil dari relasi ini).
- Hindari hapus unit yang masih dipakai; lebih aman rename via Edit.
