# Dashboard Pegawai

Halaman kerja pribadi pegawai — hanya tugas milik sendiri.

## 1. Tujuan

Menampilkan ringkasan tugas **milik sendiri** (sebagai pegawai utama atau crew)
per status global + 5 tugas terbaru sebagai jalan pintas ke detail. Pegawai
tidak melihat tugas orang lain.

## 2. Info Cepat

| Item | Keterangan |
|------|------------|
| Akses role | `pegawai` (middleware `role:pegawai`) |
| URL | `/pegawai/dashboard` |
| Route name | `pegawai.dashboard` (`routes/web.php:84`) |
| File Component | `app/Livewire/Pegawai/Dashboard.php` |
| File Blade | `resources/views/livewire/pegawai/dashboard.blade.php` |
| Menu sidebar | Dashboard, Tugas Saya (`sidebar.blade.php:42-47`) |

## 3. Fungsi Rinci Tiap Elemen Layar

Berdasarkan `Dashboard.php:10-23` dan blade baris 1-68:

- **Cakupan data** (baris 12-13): `$pegawai = auth()->user()->pegawai; $query =
  $pegawai ? Tugas::forPegawai($pegawai->id_pegawai) : Tugas::query()->whereRaw(
  '1 = 0')`. Fungsinya: akun yang dikaitkan ke pegawai melihat tugasnya;
  akun tanpa kaitan melihat kosong total. Scope `forPegawai` (`Tugas.php:54-60`):
  `where id_pegawai=X OR whereHas crews.id_pegawai=X` — kena sebagai penanggung
  utama ATAU anggota tim.
- **Judul + sapaan** (baris 2-3): `Dashboard Pegawai` + nama user login.
- **5 kartu** (baris 5-26), masing-masing `clone $query` + filter:
  - Total Tugas (`count()` semua miliknya).
  - Belum Mulai (kuning, `where status=belum_mulai`).
  - Proses (biru, `where status=proses`).
  - Selesai (indigo, `where status=selesai` = dilaporkan, menunggu ACC).
  - ACC (hijau, `where status=acc` = final).
  Fungsinya prioritas: kerjakan yang Belum/Proses dulu; Selesai tinggal tunggu
  pimpinan. Perhatikan: ini **status global tugas**, bukan status pribadi
  (status pribadi ada di banner Detail).
- **Tabel Tugas Saya** (baris 28-67): `(clone $query)->latest()->take(5)`.
  Kolom: Nama Tugas (**link biru** → `pegawai.tugases.show`, baris 43),
  Status badge global (abu/kuning/biru/hijau + `status_label`), Tanggal produksi
  (`d/m/Y`). Fungsinya akses cepat 5 terbaru; selebihnya di Daftar Tugas.
- **Kosong**: `Belum ada tugas.`

## 4. Langkah Penggunaan

1. Login sebagai Pegawai → mendarat di sini.
2. Baca kartu: mulai dari Belum Mulai → Proses.
3. Klik nama tugas untuk membuka detail (baca peran, mulai/selesaikan, cetak nota).
4. Untuk daftar penuh + pencarian, buka **Tugas Saya**.

## 5. Validasi dan Pesan Sistem

- Tidak ada form. Isolasi data dijamin scope `forPegawai` — tidak ada cara dari
  UI untuk melihat tugas orang lain.

## 6. Relasi Database

- Baca: `tugases` + `tugas_crews` (via scope), berdasar `users.pegawai_id` →
  `pegawais.id_pegawai`.

## 7. Screenshot

> 📷 Screenshot: [sapaan + 5 kartu + tabel Tugas Saya]

| Elemen | Anotasi |
|--------|---------|
| 5 kartu | Total/Belum/Proses/Selesai/ACC milik sendiri |
| Link nama | Ke detail tugas |

## 8. Tips

- Dashboard kosong padahal merasa punya tugas → akun belum dikaitkan ke data
  pegawai (`users.pegawai_id` NULL). Hubungi Admin untuk mengaitkan di
  `/admin/users` (pilih `nama - nip`).
- Badge di sini = status global; untuk tahu giliran Anda, buka Detail dan baca
  banner biru (status pribadi).
