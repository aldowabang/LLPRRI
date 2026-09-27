# Pengaturan Profil

Halaman untuk mengubah data akun sendiri (nama, email, password, tampilan).

## 1. Tujuan

Memberi setiap pengguna (semua role) kendali atas akunnya sendiri — ganti nama,
email, password, tema — tanpa harus meminta Admin, sekaligus menjaga keamanan
akun.

## 2. Info Cepat

| Item | Keterangan |
|------|------------|
| Akses role | Semua role yang sudah login |
| URL | `/settings/profile`, `/settings/password` (security), `/settings/appearance`, dll. |
| File Blade | `resources/views/pages/settings/⚡profile.blade.php`, `⚡security.blade.php`, `⚡appearance.blade.php`, `⚡two-factor-setup-modal.blade.php`, `⚡delete-user-form.blade.php`, layout `resources/views/pages/settings/layout.blade.php` |

> Catatan: nama file berawalan `⚡` adalah file komponen Flux. Jangan diubah
> namanya (lihat `AGENTS.md` Gotchas).

## 3. Fungsi Rinci Tiap Tab / Elemen

- **Profile (`⚡profile`)**: form Nama (`users.name`) + Email (`users.email`).
  Fungsinya identitas yang tampil di sapaan dashboard dan kuitansi. Email baru
  harus unik dan format valid; biasanya memicu verifikasi ulang.
- **Password / Security (`⚡security`)**: form password lama + password baru +
  konfirmasi. Fungsinya mengganti kredensial login (hash baru disimpan).
  Minimal 8 karakter. Ada opsi two-factor (kode pemulihan di
  `settings/two-factor/⚡recovery-codes.blade.php`) bila diaktifkan.
- **Appearance (`⚡appearance`)**: pilihan tema terang/gelap (dark class di
  `sidebar.blade.php:2`). Fungsinya kenyamanan visual saja, tidak memengaruhi data.
- **Two-factor setup modal (`⚡two-factor-setup-modal`)**: panduan mengaktifkan
  2FA. Fungsinya lapisan keamanan tambahan saat login.
- **Delete user (`⚡delete-user-form`, `⚡delete-user-modal`)**: hapus akun sendiri
  dengan konfirmasi + password. Fungsinya menutup akun; gunakan hanya bila
  benar-benar keluar dari organisasi (minta Admin menonaktifkan lebih aman).
- **Navigasi**: heading bersama (`partials/settings-heading.blade.php`) + layout
  settings; diakses dari menu avatar → Settings (`sidebar.blade.php:89-91`).

## 4. Langkah Penggunaan

1. Klik avatar/menu pengguna di header atau sidebar → **Settings / Pengaturan**.
2. Tab **Profile**: ubah nama/email → **Simpan**. Verifikasi email baru bila diminta.
3. Tab **Password**: isi password lama + password baru + konfirmasi → **Simpan**.
4. Tab **Appearance**: pilih terang/gelap sesuai kenyamanan.
5. (Opsional) aktifkan two-factor dan simpan kode pemulihan di tempat aman.

## 5. Validasi dan Pesan Sistem

- Email harus format valid dan unik di `users.email`.
- Password baru minimal 8 karakter dan harus cocok dengan konfirmasi; password
  lama harus benar.
- Perubahan tersimpan menampilkan pesan sukses / toast Flux (`@persist('toast')`
  di sidebar).
- Gagal validasi menampilkan pesan merah di bawah field terkait.

## 6. Relasi Database

- Perubahan disimpan ke tabel `users` (kolom `name`, `email`, `password`
  ter-hash). Tidak menyentuh `pegawais/units/jabatans/tugases`.
- Mengubah email tidak mengubah `pegawai_id` maupun data pegawai.

## 7. Screenshot

> 📷 Screenshot: [halaman Settings: Profile, Password, Appearance]

| Tab | Yang difoto |
|-----|-------------|
| Profile | Field nama + email + tombol simpan |
| Password | Field lama/baru/konfirmasi |
| Appearance | Pilihan tema |

## 8. Tips

- Ganti password bawaan dari Admin segera setelah login pertama.
- Jika lupa password dan tidak bisa login sama sekali, gunakan **Forgot password**
  di halaman Login, bukan halaman ini (halaman ini butuh sesi login).
- Simpan kode pemulihan 2FA di luar perangkat (kertas/ password manager).
