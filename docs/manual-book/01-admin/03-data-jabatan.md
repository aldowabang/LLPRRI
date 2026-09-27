# Data Jabatan

Halaman master data Jabatan (mis. Produser, Penyiar, Cameraman, Teknisi, Editor, Staf).

## 1. Tujuan

Mengelola daftar jabatan yang menjadi (a) acuan wajib pegawai
(`pegawais.id_jabatan`) dan (b) **filter dropdown crew** saat Pimpinan membuat
Nota Produksi. Ejaan jabatan di sini menentukan apakah dropdown crew terisi.

## 2. Info Cepat

| Item | Keterangan |
|------|------------|
| Akses role | `admin` |
| URL | `/admin/jabatans` |
| Route name | `admin.jabatans.index` (`routes/web.php:42`) |
| File Component | `app/Livewire/Admin/Jabatan/Index.php` (`WithPagination`) |
| File Blade | `resources/views/livewire/admin/jabatan/index.blade.php` |

## 3. Fungsi Rinci Tiap Elemen Layar

Pola kode identik dengan Data Unit (`Jabatan/Index.php:25-76`):

- **Header + Tambah Jabatan**: `openCreate()` mereset `$jabatanId/$nama_jabatan`,
  `editMode=false`, tampilkan modal `jabatan-form`.
- **Notifikasi hijau** `session('success')` tiap operasi.
- **Tabel** `Jabatan::orderBy('id_jabatan')->paginate(10)`, kolom:
  - **ID** (`id_jabatan`): urutan tampil.
  - **Nama Jabatan** (`nama_jabatan`): cth. Produser, Penyiar.
  - **Aksi**: Edit (subtle) + Hapus (danger + `wire:confirm="Hapus jabatan ini?"`).
- **Kosong**: `Belum ada data jabatan.`
- **Paginasi** `links()` tiap 10 baris.
- **Modal**: satu `flux:input wire:model="nama_jabatan"`, judul Tambah/Edit,
  tombol Batal (`close()`) / Simpan (`save()` → `Jabatan::create/update`).
- **Edit**: `openEdit($id)` memuat `Jabatan::findOrFail` ke field.
- **Hapus**: `delete($id)` hapus permanen.

Kaitan ke form pimpinan (`Pimpinan/Tugas/Create.php:133-140`, render):
dropdown crew difilter dengan perbandingan string persis terhadap
`$p->jabatan->nama_jabatan`:

| Dropdown crew | Filter jabatan |
|---------------|----------------|
| Produser | `Produser` |
| Cameraman | `Cameraman` |
| Teknisi | `Teknisi` |
| Editor | `Editor` |
| Pengarah Acara, Presenter | `Penyiar` |
| Asisten Produser, Asisten PA | `Staf` |
| Unit Manager | `Kepala Bidang` / `Kepala Seksi` |
| Dokumentasi | gabungan Cameraman + Staf |

## 4. Tombol dan Aksi

| Tombol | Fungsi di sistem |
|--------|------------------|
| Tambah Jabatan | `openCreate()` → modal kosong |
| Edit | `openEdit(id)` → modal terisi |
| Hapus | `delete(id)` + konfirmasi → hapus permanen |
| Batal / Simpan | tutup / validasi + tulis DB |

## 5. Langkah Penggunaan

1. Buka **Dashboard Admin → Kelola Jabatan** (atau sidebar **Jabatan**).
2. Klik **Tambah Jabatan**, ketik nama persis (lihat tabel filter di atas),
   klik **Simpan**.
3. Ulangi untuk 7+ jabatan standar: Produser, Penyiar, Cameraman, Teknisi,
   Editor, Staf, Kepala Bidang, Kepala Seksi.
4. Ubah via **Edit**, hapus via **Hapus** + konfirmasi.

## 6. Validasi dan Pesan Sistem

- `nama_jabatan required|string|max:100` (`Index.php:21-23`).
- Pesan: `Jabatan berhasil ditambahkan. / diupdate. / dihapus.`

## 7. Relasi Database

- Tabel `jabatans` (migrasi `2026_08_16_070610`): `id_jabatan` PK,
  `nama_jabatan(100)`.
- Anak: `pegawais.id_jabatan cascadeOnDelete` — hapus jabatan menghapus
  pegawainya. Cek dulu.
- Tidak ada FK langsung ke `tugas_crews`; pengaruhnya tidak langsung via filter
  nama jabatan di form pimpinan.

## 8. Screenshot

> 📷 Screenshot: [tabel Jabatan + modal Tambah/Edit + contoh dropdown crew yang terisi]

## 9. Tips

- Samakan ejaan persis (kapitalisasi): `Produser` bukan `produser/produser RRI`.
  Satu huruf beda membuat dropdown crew kosong + pesan `Tidak ada pegawai
  dengan jabatan …` di form Buat Tugas.
- Jika dropdown crew kosong untuk peran tertentu, periksa halaman ini dulu
  sebelum mengira bug.
