# Lampiran: Skema Database

Referensi tabel yang relevan dengan manual book: dari mana setiap layar membaca
dan ke mana setiap tombol menulis. Nama kolom mengikuti migrasi di
`database/migrations/`. Produksi memakai MySQL (Docker port 3307); dev/test
memakai SQLite (`phpunit.xml` `:memory:`) — hati-hati perbedaan perilaku FK
cascade saat testing.

## 1. `units` (migrasi `2026_08_16_070609`)

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id_unit` | PK increment | Kunci, tampil sebagai kolom ID |
| `nama_unit` | string(100) | Satu-satunya field input |
| `created_at`, `updated_at` | timestamps | Audit |

Ditulis dari: Data Unit Admin (create/update/delete).
Dibaca oleh: dropdown Unit form Pegawai, kolom Unit tabel pegawai + laporan.

## 2. `jabatans` (migrasi `2026_08_16_070610`)

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id_jabatan` | PK increment | Kunci |
| `nama_jabatan` | string(100) | Ejaan menentukan filter crew pimpinan |
| `created_at`, `updated_at` | timestamps | |

Ditulis dari: Data Jabatan Admin. Dibaca oleh: dropdown Jabatan form Pegawai,
filter `render()` form Buat Tugas (perbandingan string persis).

## 3. `pegawais` (migrasi `2026_08_16_070611`)

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id_pegawai` | PK increment | Kunci acuan tugas/crew/user |
| `id_unit` | FK → `units.id_unit`, `cascadeOnDelete` | Hapus unit = hapus pegawai |
| `id_jabatan` | FK → `jabatans.id_jabatan`, `cascadeOnDelete` | Hapus jabatan = hapus pegawai |
| `nip` | string(30), **unik** | Kunci bisnis, tampil di opsi crew |
| `nama_pegawai` | string(100) | Nama lengkap |
| `jenis_kelamin` | ENUM `L`/`P` | Laki-laki/Perempuan |
| `no_hp` | string(20) | **Tujuan WhatsApp** (dinormalkan 08→62 oleh `WhatsAppService::formatPhone`) |
| `alamat` | text | Tidak tampil di tabel |
| `created_at`, `updated_at` | timestamps | |

Ditulis dari: Data Pegawai Admin. Dibaca oleh: hampir semua layar
(crew, laporan, WA). Relasi model `Pegawai.php`: `unit`, `jabatan`,
`user` (hasOne via `pegawai_id`), `tugases` (hasMany utama),
`tugasCrews` (hasMany).

## 4. `users` (bawaan `0001_01_01_000000` + `2026_08_16_070612`)

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | PK increment | Kunci |
| `name` | string(255) | Nama akun/tampilan |
| `role` | ENUM `admin`/`pimpinan`/`pegawai`, default `pegawai` | **Penentu menu manual ini** |
| `pegawai_id` | nullable FK → `pegawais.id_pegawai`, `nullOnDelete` | Kaitan akun–pegawai; hapus pegawai → NULL, akun tetap |
| `email` | string, **unik** | Identitas login |
| `email_verified_at` | nullable timestamp | Diisi `now()` saat create via Admin |
| `password` | string hash | Tidak reversible |
| `remember_token`, timestamps | standar | Sesi |

Ditulis dari: Data User Admin + halaman Settings (diri sendiri).
Dibaca oleh: Fortify login, `/dashboard` match role, sidebar `isAdmin/dll`,
scope pegawai (`auth()->user()->pegawai`), pencarian pimpinan pertama untuk WA
selesai (`User::where('role','pimpinan')->first()`).

## 5. `tugases` (migrasi `2026_08_16_070613` + lanjutan)

| Kolom | Keterangan |
|-------|------------|
| `id_tugas` | PK increment ( Sheriff: route `{id_tugas}`) |
| `nomor_nota` | Nomor resmi (auto `rand/MM/YYYY`, bisa edit) |
| `id_pegawai` | FK pegawai **utama** (fallback produser pertama) |
| `nama_tugas` | Judul acara (slug untuk nama file PDF) |
| `format` | Talkshow/Liputan/dll |
| `disiarkan` | Jadwal tayang (string tanggal) |
| `tanggal_rapat`, `waktu_rapat`, `tempat_rapat` | Rapat pra-produksi |
| `tanggal_produksi` (`date` cast), `waktu_produksi`, `tempat_produksi` | Hari H |
| `tempat` | Fallback produksi→rapat→`RRI Kupang` |
| `narasumber`, `topik`, `penanggung_jawab` (teks), `supervisor` (teks) | Isi nota |
| `status_tugas` | ENUM `belum_mulai`/`proses`/`selesai`/`acc` (global) |
| timestamps | Audit |

Ditulis dari: Buat Tugas pimpinan (status awal `belum_mulai`), Detail pimpinan
(`tandaiSemuaSelesai`, `acc`). Dibaca oleh: dashboard/daftar/detail/laporan
kedua role + kedua PDF. Cast: `tanggal_produksi`, `tanggal_rapat` → `date`.

## 6. `tugas_crews` (+ migrasi `2026_08_16_110000` tambah `acc`)

| Kolom | Keterangan |
|-------|------------|
| `id` | PK |
| `id_tugas` | FK → `tugases.id_tugas` |
| `id_pegawai` | FK → `pegawais.id_pegawai` |
| `peran` | 10 nilai tetap (lihat lampiran status) |
| `status` | `belum_mulai`/`proses`/`selesai`/`acc` (personal) |

Ditulis dari: Buat Tugas (N baris per nota), Detail pimpinan (ACC/massal),
Detail pegawai (`mulai`/`selesai` milik sendiri). Dibaca oleh: tabel crew,
banner pegawai, broadcast WA, ringkasan count, PDF tim.

## 7. Diagram Relasi (teks)

```text
units 1───* pegawais *───1 jabatans
pegawais 1───0..1 users            (users.pegawai_id, nullOnDelete)
pegawais 1───* tugases             (tugases.id_pegawai, pegawai utama)
tugases 1───* tugas_crews *───1 pegawais   (tim per peran)
```

Aturan hapus penting: hapus Unit/Jabatan → pegawai ikut terhapus (cascade);
hapus Pegawai → kaitan user jadi NULL (akun tetap, tapi tugasnya kosong);
hapus Tugas → crew ikut terhapus (tergantung FK tugas); hapus User → tidak
menyentuh pegawai.
