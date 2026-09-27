# Login

Halaman masuk aplikasi untuk semua role (Admin, Pimpinan, Pegawai).

## 1. Tujuan

Memverifikasi identitas pengguna (email + password, opsional passkey) sebelum
memberi akses ke area ber-login. Ini gerbang tunggal semua role.

## 2. Info Cepat

| Item | Keterangan |
|------|------------|
| Akses role | Semua (belum login / tamu) |
| URL | `/login` (POST ke `login.store`) |
| File Blade | `resources/views/pages/auth/login.blade.php`, layout `resources/views/layouts/auth.blade.php` |
| Auth backend | Laravel Fortify (lihat `routes/settings.php` + config Fortify) |

## 3. Fungsi Rinci Tiap Elemen Layar

Berdasarkan `login.blade.php`:

- **Judul + deskripsi** (`x-auth-header`, baris 3): `Log in to your account` /
  `Enter your email and password below to log in`. Fungsinya memberi tahu
  pengguna apa yang harus dilakukan.
- **Status sesi** (`x-auth-session-status`, baris 6): menampilkan pesan seperti
  kredensial salah, link reset terkirim, atau info verifikasi. Muncul hanya bila
  ada `session('status')`.
- **Verifikasi passkey** (`x-passkey-verify`, baris 8): opsi login tanpa password
  memakai passkey perangkat (`resources/js/passkeys.js`). Bisa diabaikan bila
  memakai email+password biasa.
- **Kolom Email** (`flux:input name=email type=email`, baris 14-23): wajib
  (`required`), autofocus, autocomplete email, placeholder `email@example.com`,
  mempertahankan isian lama (`old('email')`) bila gagal login. Fungsinya sebagai
  identitas akun (bukan NIP/username).
- **Kolom Password** (`flux:input name=password type=password viewable`, baris
  27-35): wajib, autocomplete `current-password`, ada tombol intip (viewable).
  Fungsinya kata sandi yang dicocokkan dengan hash di DB.
- **Link Lupa password** (baris 37-41): `Forgot your password?` → route
  `password.request`. Fungsinya alur reset bila pengguna lupa sandi.
- **Remember me** (`flux:checkbox`, baris 45): bila dicentang, sesi login
  dipertahankan lebih lama (cookie remember).
- **Tombol Log in** (`flux:button type=submit`, baris 48-50): mengirim form
  (`POST` + `@csrf`) ke `login.store`. Fungsinya memproses autentikasi.
- **Link Sign up** (baris 54-57): → route `register`. Fungsinya pendaftaran akun
  baru bila fitur register diaktifkan (di lingkungan produksi biasanya
  dinonaktifkan dan akun dibuat Admin).

Setelah login berhasil, sistem mengarahkan ke `/dashboard`, yang kemudian
meneruskan ke dashboard per role (`routes/web.php:24-34`).

## 4. Langkah Penggunaan

1. Buka URL aplikasi, klik **Masuk** / buka `/login`.
2. Isi **Email** dan **Password** yang diberikan Admin.
3. (Opsional) centang **Remember me** di perangkat pribadi.
4. Klik tombol **Log in**.
5. Jika berhasil, Anda masuk ke Dashboard sesuai role (Admin/Pimpinan/Pegawai).
6. Jika gagal, baca pesan di status sesi, periksa kembali email/password,
   atau gunakan **Forgot your password?**.
7. Untuk keluar, gunakan menu pengguna (avatar) → **Log out**
   (lihat sidebar `layouts/app/sidebar.blade.php:96-107`).

## 5. Validasi dan Pesan Sistem

- Email wajib, format email, harus terdaftar di `users.email`.
- Password wajib; saat dibuat Admin minimal 8 karakter.
- Kredensial salah → kembali ke halaman login dengan pesan error, isian email
  dipertahankan.
- Email belum verifikasi → diminta verifikasi dulu (middleware `verified`
  pada grup route ber-login).
- Akses halaman ber-login tanpa sesi → dialihkan ke login (middleware `auth`).

## 6. Relasi Database

- Kredensial dicek ke tabel `users` (kolom `email`, `password` ter-hash
  bcrypt/argon2, tidak tersimpan plain).
- Kolom `users.role` menentukan dashboard tujuan setelah login.
- Kolom `users.pegawai_id` menautkan akun ke data `pegawais` (wajib terisi untuk
  pimpinan/pegawai agar tugas dan WA berfungsi; admin boleh kosong).

## 7. Screenshot

> 📷 Screenshot: [tambah tangkapan layar halaman Login di sini]

| Elemen | Keterangan untuk manual cetak |
|--------|-------------------------------|
| Kolom email | Isi dengan email akun dari Admin |
| Kolom password | Bertanda titik/bintang, ada tombol intip |
| Remember me | Opsional, perangkat pribadi saja |
| Tombol Log in | Klik setelah form terisi |
| Link lupa password | Alur reset bila lupa sandi |

## 8. Tips

- Minta email dan password awal ke Admin; segera ubah password di menu
  Pengaturan setelah login pertama.
- Satu akun satu role. Jika role salah (mis. pegawai mendapat menu admin),
  hubungi Admin untuk diperbaiki di Data User (`/admin/users`).
- Jangan centang Remember me di komputer bersama.
