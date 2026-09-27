# Detail dan Update Status (Pegawai)

Halaman pengerjaan tugas: lihat detail, cetak nota, dan laporkan progres bagian sendiri.

## 1. Tujuan

Memungkinkan pegawai **memulai dan menyelesaikan bagian tugas miliknya**
(baris crew sendiri), yang otomatis memberi tahu pimpinan via WhatsApp agar
segera di-ACC. Ini satu-satunya tempat pegawai mengubah status.

## 2. Info Cepat

| Item | Keterangan |
|------|------------|
| Akses role | `pegawai` |
| URL | `/pegawai/tugases/{id_tugas}` |
| Route name | `pegawai.tugases.show` (`routes/web.php:86`) |
| File Component | `app/Livewire/Pegawai/Tugas/Show.php` |
| File Blade | `resources/views/livewire/pegawai/tugas/show.blade.php` |

## 3. Fungsi Rinci Tiap Elemen Layar

Berdasarkan `Show.php` dan blade baris 1-137:

- **Notifikasi sukses** (baris 2-6): hijau tiap Mulai/Selesai.
- **Header** (baris 8-25): `flux:heading nama_tugas` + subheading
  `Nota Produksi No: nomor_nota`. Kanan: 4 tombol (lihat §4).
- **Identifikasi crew** (`mount`, `Show.php:18-28`):
  `Tugas::with(['pegawai','crews.pegawai'])->findOrFail($id_tugas)`, lalu
  `$myCrew = TugasCrew where id_tugas + id_pegawai login->first()`.
  Fungsinya menemukan **baris crew milik sendiri**; semua aksi status menyasar
  baris ini, bukan tugas global. Bila `myCrew` null (kasus tepi: hanya pegawai
  utama tanpa baris crew), tombol Mulai/Selesai dan banner tidak tampil,
  halaman jadi read-only.
- **Banner biru peran+status pribadi** (baris 27-47, hanya bila `$myCrew` ada):
  `Peran Anda: peran_label` (satu dari 10 peran) + `Status: status_label`
  pribadi (badge: belum abu / proses kuning / selesai hijau). **Ini status yang
  bisa Anda ubah.** Bedakan dengan badge global di kartu.
- **Kartu 1 — Detail Acara & Produksi** (baris 49-103): Format, Disiarkan,
  Rapat Pra-Produksi (`d/m/Y` + `jam HH:MM` + `(tempat)`), Jadwal Produksi
  (pola sama), Narasumber, Topik, **Status global** badge
  (abu/kuning/biru/hijau). Fungsinya bekal kerja: kapan-di mana-apa yang
  dibahas. Status global tidak berubah oleh aksi Anda langsung.
- **Kartu 2 — Kerabat Kerja Produksi** (baris 105-135): 6 baris via
  `getCrewNamesByRole()` (`Tugas.php:62-71`, gabung koma, `-` bila kosong):
  Produser, Pengarah Acara, Presenter, Cameraman, Teknisi, Editor.
  Fungsinya tahu rekan setim (koordinasi). Peran lain (asisten, dokumentasi,
  unit manager, PJ, supervisor) tidak ditampilkan di kartu pegawai —
  lihat nota PDF untuk daftar penuh.

## 4. Tombol dan Aksi Rinci

| Tombol | Syarat tampil (blade) | Method & efek |
|--------|----------------------|---------------|
| Cetak Nota Produksi (PDF) (primary, ikon dokumen, `target=_blank`) | selalu | Buka `pegawai.tugases.nota-produksi` (stream PDF, tak ubah DB) |
| Mulai Tugas (kuning) | `$myCrew && status==belum_mulai` (baris 17-19) | `mulai()` (`Show.php:30-37`): `myCrew belum→proses` + `refresh` + flash `Tugas Anda telah dimulai.` |
| Tandai Selesai (hijau) | `$myCrew && status==proses` (baris 20-22) | `selesai()` (`Show.php:39-58`): `myCrew proses→selesai` + cari `User where role=pimpinan first` + `sendTugasSelesai()` try/catch + flash `Tugas Anda telah selesai. Menunggu ACC dari pimpinan.` |
| Kembali (subtle) | selalu | → `pegawai.tugases.index` |

Batasan keras: pegawai **tidak** menulis `tugases.status_tugas` dan **tidak**
bisa ACC (`acc` hanya di component pimpinan). Menekan di luar urutan tidak
berefek (guard `if status ===`).

## 5. Langkah Penggunaan

1. Buka tugas dari Dashboard / Daftar Tugas (klik nama/Detail).
2. Baca **banner biru**: pastikan Peran Anda benar dan catat Statusnya.
3. Baca Kartu 1 (jadwal + lokasi + narasumber + topik) dan Kartu 2 (rekan tim).
4. (Opsional) **Cetak Nota** sebagai pegangan lapangan.
5. Saat mulai bekerja: klik **Mulai Tugas** (banner → Proses).
6. Saat bagian Anda beres: klik **Tandai Selesai** (banner → Selesai).
   Sistem mengirim WA ke pimpinan.
7. Selesai. Tunggu pimpinan ACC (badge global jadi hijau). Tidak ada aksi lagi
   dari sisi Anda.

## 6. Validasi dan Pesan Sistem

- Tombol dirender kondisional (bukan disabled) sesuai status pribadi.
- WA ke pimpinan dalam try/catch (`Log::warning` bila gagal); perubahan status
  tetap tersimpan walau WA gagal.
- Isi WA (`WhatsAppService.php:89-95`): `Konfirmasi Tugas Selesai` + nama
  pimpinan + nama penyelesai + nama/format/tanggal tugas + ajakan ACC.

## 7. Relasi Database

- Tulis: `tugas_crews.status` **milik sendiri** saja (satu baris).
- Baca: `tugases` (detail), `tugas_crews` + `pegawais` (tim), `users`
  (cari pimpinan pertama untuk WA).

## 8. Screenshot

> 📷 Screenshot: [header + banner biru] + [Kartu 1 + Kartu 2] + [tombol Mulai/Selesai kondisional]

| Elemen | Anotasi |
|--------|---------|
| Banner biru | Peran + status pribadi (bisa diubah) |
| Badge global | Tidak bisa diubah pegawai |
| Tombol | Muncul bergantian sesuai status |

## 9. Tips

- Alur Anda: `belum_mulai → proses → selesai → (pimpinan) acc`. Tidak bisa
  loncat (Selesai menuntut Proses dulu).
- Jika tombol tidak muncul: (a) Anda sudah `selesai` → tinggal tunggu ACC;
  (b) sudah `acc` → selesai total; (c) `myCrew` null → hubungi pimpinan
  (kemungkinan Anda tercatat sebagai pegawai utama tanpa baris crew).
- Jika pimpinan mengaku tak terima WA selesai: cek log server + nomor tujuan
  (catatan bug: implementasi mengambil nomor dari `tugas->pegawai->no_hp`;
  laporkan ke pengembang bila notifikasi sering salah sasaran).
