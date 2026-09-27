# Data User

Halaman pengelolaan akun login dan role.

## 1. Tujuan

Membuat dan mengatur **akun yang dipakai login** (email+password), menentukan
**role** (`admin/pimpinan/pegawai` di `users.role`), dan menautkan akun ke data
pegawai (`users.pegawai_id`) agar alur tugas + WA mengenali pemilik akun.
Tanpa halaman ini, tidak ada yang bisa login selain akun bawaan.

## 2. Info Cepat

| Item | Keterangan |
|------|------------|
| Akses role | `admin` |
| URL | `/admin/users` |
| Route name | `admin.users.index` (`routes/web.php:44`) |
| File Component | `app/Livewire/Admin/User/Index.php` (`WithPagination`) |
| File Blade | `resources/views/livewire/admin/user/index.blade.php` |

## 3. Fungsi Rinci Tiap Elemen Layar

Berdasarkan `User/Index.php` dan blade baris 13-75:

- **Header + Tambah User** (baris 2-5): judul `Manajemen User` + tombol primary
  `openCreate()`. Fungsinya buka modal akun baru.
- **Notifikasi hijau** (baris 7-11): `session('success')`.
- **Tabel** (baris 13-48): `User::with('pegawai')->orderBy('id')->paginate(10)`.
  Kolom:
  - **Nama** (`name`, bold): nama akun/tampilan.
  - **Email**: identitas login, unik.
  - **Role** badge pill `ucfirst(role)`: merah `bg-red-100` untuk admin, biru
    untuk pimpinan, hijau untuk pegawai (blade baris 30-33). Fungsinya penanda
    visual cepat + penentu menu yang didapat user.
  - **Pegawai** (`pegawai->nama_pegawai ?? '-'`): `-` berarti tidak dikaitkan
    (wajar untuk admin, masalah untuk pimpinan/pegawai).
  - **Aksi**: Edit + Hapus.
- **Kosong**: `Belum ada data.` **Paginasi** `links()`.
- **Modal `user-form max-w-lg`** (baris 52-74):
  - **Nama** input `wire:model=name`.
  - **Email** input `type=email`.
  - **Password** input `type=password`, label dinamis: `Password` saat tambah,
    `Password (kosongkan jika tidak diubah)` saat edit (baris 57).
    Fungsinya: tambah wajib isi; edit boleh kosong = password lama dipertahankan.
  - **Role** select: Admin / Pimpinan / Pegawai (`Index.php:23` default pegawai).
  - **Pegawai (opsional)** select: opsi `Tidak dikaitkan` + daftar
    `nama_pegawai - nip` dari `$pegawais` (bukan query langsung, melainkan array
    hasil `loadPegawais()`).
  - **Batal** / **Simpan**.
- **Method kunci**:
  - `openCreate()` (baris 51-58): reset field + `loadPegawais()`.
  - `openEdit($id)` (baris 60-73): isi nama/email/role/pegawai, **password
    dikosongkan** (`$this->password=''`), lalu `loadPegawais()`.
  - `loadPegawais()` (baris 75-88): ambil `pegawai_id` yang sudah dipakai user
    lain (`whereNotNull + where id != diri sendiri saat edit`), lalu tampilkan
    hanya pegawai yang belum dipakai. Fungsinya mencegah satu pegawai dikait ke
    dua akun (kecuali milik sendiri saat edit).
  - `save()` (baris 90-121): `validate()`; mode edit `update(name,email,role,
    pegawai_id)` + update password terpisah **hanya bila diisi**
    (`Hash::make`); mode tambah `User::create(... + Hash::make(password) +
    email_verified_at=now())`. Flash sesuai mode.
  - `delete($id)`: hapus akun (bukan hapus data pegawai).

## 4. Tombol dan Aksi

| Tombol | Fungsi di sistem |
|--------|------------------|
| Tambah User | `openCreate()` → modal kosong + daftar pegawai tersedia |
| Edit | `openEdit(id)` → modal terisi, password kosong |
| Hapus | `delete(id)` + `wire:confirm="Hapus user ini?"` → hapus akun login |
| Batal / Simpan | tutup / validasi + tulis DB |

## 5. Langkah Penggunaan

1. Buka **Kelola User → Tambah User**.
2. Isi Nama, Email unik, Password min 8, pilih Role.
3. Untuk pimpinan/pegawai: pilih kaitan **Pegawai** (`nama - nip`). Untuk admin:
   biarkan `Tidak dikaitkan`.
4. Klik **Simpan** → `User berhasil ditambahkan.` Serahkan email+password ke pengguna.
5. Ganti role / reset password / (un)kait pegawai: **Edit** → ubah → **Simpan**.
   Kosongkan password bila tidak ingin mengubahnya.
6. Pegawai keluar: **Hapus** akunnya (data pegawai tetap ada).

## 6. Validasi dan Pesan Sistem

Aturan (`Index.php:33-42`):

| Field | Aturan |
|-------|--------|
| `name` | `required\|string\|max:255` |
| `email` | `required\|email\|max:255\|unique:users,email` (edit dikecualikan milik sendiri) |
| `password` | tambah: `required\|string\|min:8`; edit: `nullable\|string\|min:8` |
| `role` | `required\|in:admin,pimpinan,pegawai` |
| `pegawai_id` | `nullable\|exists:pegawais,id_pegawai` |

- Email ganda / password <8 / role tak dikenal → gagal + pesan merah.
- Pesan: `User berhasil ditambahkan. / diupdate. / dihapus.`

## 7. Relasi Database

- Tabel `users`: `name`, `email` unik, `password` hash (tidak reversible),
  `role` ENUM default pegawai, `pegawai_id` nullable FK→pegawais
  (`nullOnDelete`: hapus pegawai → kaitan jadi NULL, akun tetap ada),
  `email_verified_at` (diisi `now()` saat create admin agar langsung bisa login).
- Tanpa `pegawai_id`, akun pimpinan/pegawai lolos login tapi dashboard tugasnya
  kosong (`Pegawai/Dashboard.php:13` fallback `whereRaw('1=0')`).

## 8. Screenshot

> 📷 Screenshot: [tabel Nama/Email/Role badge/Pegawai + modal 5 field]

| Elemen | Anotasi |
|--------|---------|
| Badge role | Warna merah/biru/hijau |
| Dropdown pegawai | `nama - nip`, yang sudah dipakai disembunyikan |
| Label password edit | Kosongkan bila tak diubah |

## 9. Tips

- Akun `admin` tidak perlu dikaitkan ke pegawai.
- Satu pegawai idealnya satu akun (difilter otomatis, tapi tetap periksa).
- Segera hapus/nonaktifkan akun pegawai yang sudah tidak aktif; data pegawai
  boleh dipertahankan untuk arsip laporan.
- Catat password awal di tempat aman; setelah serah terima, minta pengguna
  menggantinya di Pengaturan.
