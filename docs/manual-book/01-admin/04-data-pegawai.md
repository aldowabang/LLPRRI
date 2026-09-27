# Data Pegawai

Halaman master data pegawai beserta unit, jabatan, dan nomor HP untuk notifikasi WA.

## 1. Tujuan

Menyimpan data kepegawaian lengkap yang dipakai di **seluruh alur tugas**:
opsi crew di form pimpinan, tujuan WhatsApp (`no_hp`), dan kolom laporan PDF.
Ini tabel jembatan antara master (Unit/Jabatan) dan transaksi (Tugas/Crew/User).

## 2. Info Cepat

| Item | Keterangan |
|------|------------|
| Akses role | `admin` |
| URL | `/admin/pegawais` |
| Route name | `admin.pegawais.index` (`routes/web.php:43`) |
| File Component | `app/Livewire/Admin/Pegawai/Index.php` (`WithPagination`) |
| File Blade | `resources/views/livewire/admin/pegawai/index.blade.php` |

## 3. Fungsi Rinci Tiap Elemen Layar

Berdasarkan `Pegawai/Index.php:48-55` dan blade baris 13-80:

- **Header + Tambah Pegawai** (blade baris 2-5): judul `Data Pegawai` + tombol
  primary `openCreate()`. Fungsinya buka modal kosong 7 field.
- **Notifikasi hijau** (baris 7-11): `session('success')` tiap simpan/hapus.
- **Tabel** (baris 13-45): query
  `Pegawai::with(['unit','jabatan'])->orderBy('id_pegawai')->paginate(10)`.
  Kolom yang tampil:
  - **NIP** (`nip`): identitas unik pegawai.
  - **Nama** (`nama_pegawai`, bold): nama lengkap.
  - **Unit** (`unit->nama_unit ?? '-'`): tanda `-` berarti unit terhapus/tak ada.
  - **Jabatan** (`jabatan->nama_jabatan ?? '-'`): sama.
  - **No HP** (`no_hp`): nomor tujuan WA.
  - **Aksi**: Edit + Hapus per baris.
  - Yang **tidak tampil** di tabel tapi wajib diisi: Jenis Kelamin, Alamat
    (hanya terlihat saat Edit).
- **Kosong**: `Belum ada data pegawai.` **Paginasi** `links()`.
- **Modal `pegawai-form max-w-lg`** (baris 49-79, `wire:close="close"`):
  judul Tambah/Edit. Isi:
  - Grid 2 kolom: **Unit** select (`wire:model=id_unit`, opsi `Pilih Unit` +
    `Unit::orderBy('nama_unit')->get()`), **Jabatan** select (pola sama).
    Fungsinya memilih FK; daftar selalu terbaru dari master.
  - **NIP** input: kunci unik.
  - **Nama Pegawai** input.
  - **Jenis Kelamin** select `L=Laki-laki/P=Perempuan`, default `L`
    (`Index.php:25`).
  - **No. HP** input: nomor WA aktif.
  - **Alamat** textarea.
  - **Batal** (`close()`) / **Simpan** (`save()`).
- **State & method** (`Index.php:15-33,57-117`): `$pegawaiId,$id_unit,$id_jabatan,
  $nip,$nama_pegawai,$jenis_kelamin,$no_hp,$alamat,$showModal,$editMode`.
  `openCreate()` reset semua; `openEdit($id)` prefill dari `findOrFail`;
  `save()` `validate()` → `Pegawai::create/update($data)`;
  `delete($id)` hapus + flash.

## 4. Tombol dan Aksi

| Tombol | Fungsi di sistem |
|--------|------------------|
| Tambah Pegawai | `openCreate()` → modal kosong |
| Edit | `openEdit(id)` → modal terisi 7 field lama |
| Hapus | `delete(id)` + `wire:confirm="Hapus pegawai ini?"` |
| Batal / Simpan | tutup / validasi + tulis DB + flash + tutup modal |

## 5. Langkah Penggunaan

1. Pastikan **Unit dan Jabatan sudah terisi** (dropdown modal diambil dari sana).
2. Buka **Kelola Pegawai → Tambah Pegawai**.
3. Pilih Unit + Jabatan, isi NIP unik, Nama lengkap, Jenis Kelamin, **No HP WA
   aktif** (format 08…), Alamat lengkap.
4. Klik **Simpan** → notifikasi `Pegawai berhasil ditambahkan.` Ulangi per orang.
5. Mutasi/ganti nomor: **Edit** → ubah → **Simpan**.
6. Setelah pegawai terisi, lanjut kaitkan akun di **Data User**.

## 6. Validasi dan Pesan Sistem

Aturan (`Index.php:35-46`):

| Field | Aturan |
|-------|--------|
| `id_unit` | `required\|exists:units,id_unit` |
| `id_jabatan` | `required\|exists:jabatans,id_jabatan` |
| `nip` | `required\|string\|max:30\|unique:pegawais,nip` (saat edit dikecualikan milik sendiri `,id_pegawai`) |
| `nama_pegawai` | `required\|string\|max:100` |
| `jenis_kelamin` | `required\|in:L,P` |
| `no_hp` | `required\|string\|max:20` |
| `alamat` | `required\|string` |

- NIP ganda → ditolak dengan pesan unik. Unit/Jabatan tak valid → ditolak exists.
- Pesan: `Pegawai berhasil ditambahkan. / diupdate. / dihapus.`

## 7. Relasi Database

- Tabel `pegawais` (migrasi `2026_08_16_070611`): `id_pegawai` PK;
  `id_unit` FK→units cascade; `id_jabatan` FK→jabatans cascade;
  `nip(30)` unik; `nama_pegawai(100)`; `jenis_kelamin` ENUM L/P;
  `no_hp(20)`; `alamat` text.
- Relasi keluar: `users.pegawai_id` (1 pegawai ↔ 0/1 user),
  `tugases.id_pegawai` (pegawai utama), `tugas_crews.id_pegawai` (anggota tim).
- Menghapus pegawai: melepas kaitan user (`nullOnDelete`), dan tergantung FK
  tugas (cek migrasi tugas sebelum hapus pegawai yang punya tugas aktif).

## 8. Screenshot

> 📷 Screenshot: [tabel NIP/Nama/Unit/Jabatan/No HP + modal 7 field]

| Elemen | Anotasi |
|--------|---------|
| Dropdown Unit/Jabatan | Diambil dari master |
| No HP | Penentu tujuan WA |
| NIP | Harus unik |

## 9. Tips

- Nomor HP harus aktif WhatsApp. `WhatsAppService::formatPhone()` menormalkan
  08…→62…; karakter non-angka dibuang. Salah nomor = crew tidak terima nota
  (cek log `WhatsApp skipped`), tapi tugas tetap tersimpan.
- Gunakan NIP resmi dan unik; NIP dipakai di opsi crew (`nama (nip)`) dan laporan.
- Jenis Kelamin/Alamat tidak tampil di tabel — buka Edit untuk memeriksanya.
