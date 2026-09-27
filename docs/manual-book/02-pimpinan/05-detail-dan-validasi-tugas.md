# Detail dan Validasi Tugas (Pimpinan)

Halaman pemantauan per tugas dan pengesahan (ACC) — kewenangan eksklusif pimpinan.

## 1. Tujuan

Melihat detail acara per nota, memantau **status tiap personel** (bukan hanya
status global), mencetak nota resmi, dan melakukan **ACC bertahap** (per crew)
hingga **ACC final** (per tugas). Tidak ada tempat lain untuk ACC.

## 2. Info Cepat

| Item | Keterangan |
|------|------------|
| Akses role | `pimpinan` |
| URL | `/pimpinan/tugases/{id_tugas}` |
| Route name | `pimpinan.tugases.show` (`routes/web.php:55`) |
| File Component | `app/Livewire/Pimpinan/Tugas/Show.php` |
| File Blade | `resources/views/livewire/pimpinan/tugas/show.blade.php` |

## 3. Fungsi Rinci Tiap Elemen Layar

Berdasarkan `Show.php` dan blade baris 1-174:

- **Notifikasi sukses** (baris 2-6): hijau `session('success')` tiap ACC/tandai.
- **Header** (baris 8-27): `flux:heading nama_tugas` + subheading
  `Nota Produksi No: nomor_nota`. Sisi kanan 4 tombol (lihat §4).
- **Blok 1 — Detail Acara & Produksi** (baris 29-98, grid 2 kolom): daftar
  definisi 3 kolom (label + nilai span-2):
  Nomor Nota, Nama Acara, Format Acara, Disiarkan, Rapat Pra-Produksi
  (`d/m/Y` + `pukul HH:MM WITA` via `substr(waktu,0,5)`), Tempat Rapat,
  Jadwal Produksi (pola sama), Tempat Produksi (`tempat_produksi ?? tempat`),
  Narasumber, Topik, **Status Tugas badge** (`match`: abu/kuning/biru/hijau +
  `status_label`). Fungsinya verifikasi isi nota sebelum ACC.
- **Blok 2 — Tim** (baris 100-116): Penanggung Jawab + Supervisor (teks nama,
  bukan link). Fungsinya tahu penanggung lapangan.
- **Blok 3 — Status Kerabat Kerja** (baris 118-173):
  - Keterangan `Menampilkan status pekerjaan masing-masing kerabat kerja.`
  - Kosong: `Belum ada kerabat kerja yang ditugaskan.` (tak seharusnya terjadi
    karena produser+cameraman wajib).
  - Tabel kolom: **Nama** (`crew->pegawai->nama_pegawai`, bold), **Peran**
    (`peran_label`, 10 peran), **Status** badge per crew (`status_label` +
    warna sama), **Aksi** (tombol ACC kondisional).
  - **Ringkasan** (baris 167-171): hitung `Belum Mulai / Proses / Selesai`
    dari koleksi crews. Fungsinya progres cepat tanpa hitung manual.
- **Data**: `mount($id_tugas)` memuat
  `Tugas::with(['pegawai','crews.pegawai'])->findOrFail` (`Show.php:13-16`).
  ID tak ada → 404.

## 4. Tombol dan Aksi Rinci

| Tombol | Syarat tampil (blade) | Method & efek DB |
|--------|----------------------|------------------|
| Cetak Nota Produksi (PDF) (primary, ikon dokumen, `target=_blank`) | selalu | Buka `pimpinan.tugases.nota-produksi` (stream PDF, tidak ubah DB) |
| Tandai Semua Selesai (kuning) | `crews contains belum_mulai/proses` (baris 18) | `tandaiSemuaSelesai()` (`Show.php:40-49`): crew belum/proses → `selesai`; tugas → `selesai`. Flash `Semua kerabat kerja ditandai selesai.` |
| ACC Tugas (hijau) | `status_tugas === 'selesai'` (baris 22) | `acc()` (`Show.php:51-56`): tugas → `acc`. Flash `berhasil di-ACC.` |
| ACC kecil hijau (xs, per baris) | `crew.status === 'selesai'` (baris 156) | `accPerCrew(id)` (`Show.php:18-38`): crew → `acc` bila `selesai`; bila **semua** crew `acc` (`doesntExist where != acc`), tugas ikut `acc`. Flash `Berhasil ACC <nama> (<peran>).` |
| Kembali (subtle) | selalu | → `pimpinan.tugases.index` |

Catatan: tidak ada tombol tolak/kembalikan; alur hanya maju. `accPerCrew`
menolak crew yang belum `selesai` (diam, tanpa error).

## 5. Langkah Penggunaan

1. Dari Daftar Tugas klik **Detail** pada baris nota.
2. Baca Blok 1 (jadwal, narasumber, topik) + Blok 3 (siapa sudah/belum).
3. (Opsional) **Cetak Nota Produksi** untuk arsip/rapat.
4. Untuk tiap crew berstatus Selesai (biru): klik **ACC** kecil di barisnya.
   Ulangi hingga ringkasan Selesai = 0.
5. Bila status tugas sudah `selesai`: klik **ACC Tugas** hingga badge hijau ACC.
6. **Kembali** ke daftar. Tugas ACC = final, siap direkap PDF.

## 6. Validasi dan Pesan Sistem

- Tombol hanya dirender pada kondisi di atas (bukan disabled) — jadi tidak bisa
  diklik di luar urutan.
- Setiap aksi `refresh()` model + flash hijau; badge langsung berubah tanpa reload.
- `tandaiSemuaSelesai` = jalan pintas, bukan ACC: status jadi `selesai` (biru),
  masih butuh ACC untuk hijau.

## 7. Relasi Database

- Baca: `tugases` + `pegawai` + `crews.pegawai`.
- Tulis: `tugas_crews.status` (per crew / massal) dan `tugases.status_tugas`
  (massal / final). Tidak menyentuh `pegawais/users`.

## 8. Screenshot

> 📷 Screenshot: [header + 4 tombol] + [Blok 1-2] + [tabel crew + ACC per baris + ringkasan]

| Elemen | Anotasi |
|--------|---------|
| Badge global vs badge crew | Bedakan keduanya |
| Tombol kondisional | Kapan muncul |
| Ringkasan | Tiga angka progres |

## 9. Tips

- ACC bersifat final dan satu arah dari UI. Periksa kualitas sebelum ACC Tugas.
- Urutan aman: ACC per crew dulu (teliti per orang), baru ACC Tugas.
  `Tandai Semua Selesai` hanya untuk keadaan mendesak (mis. laporan lapangan
  sudah terkonfirmasi manual).
- Status macet di `selesai` (biru) = masih menunggu ACC pimpinan, bukan error.
