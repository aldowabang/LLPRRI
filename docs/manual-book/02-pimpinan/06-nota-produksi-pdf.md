# Nota Produksi PDF (Pimpinan)

Dokumen resmi penugasan per tugas — hasil cetak yang ditandatangani.

## 1. Tujuan

Menghasilkan arsip/cetakan Nota Produksi **bernomor resmi** (kop RRI + TTD
Kepala LPP) untuk koordinasi lapangan, bukti penugasan ke narasumber/unit,
dan dokumentasi. Ini representasi kertas dari halaman Detail.

## 2. Info Cepat

| Item | Keterangan |
|------|------------|
| Akses role | `pimpinan` |
| URL | `/pimpinan/tugases/{id_tugas}/nota-produksi` |
| Route name | `pimpinan.tugases.nota-produksi` |
| Handler | Closure `routes/web.php:56-64` |
| File Blade | `resources/views/pimpinan/laporan/nota-produksi-pdf.blade.php` (bukan di `livewire/`) |
| Library | `Barryvdh\DomPDF` (`Pdf::loadView()->stream()`) |

## 3. Fungsi Rinci / Isi Dokumen

Alur handler: `Tugas::with(['pegawai','crews.pegawai'])->findOrFail($id_tugas)`
→ `Pdf::loadView('pimpinan.laporan.nota-produksi-pdf', ['tugas'])` →
`stream('Nota-Produksi-'.Str::slug(nama_tugas).'.pdf')` (tampil di tab baru,
bukan download).

Susunan dokumen:

- **Kop**: RADIO REPUBLIK INDONESIA / NOTA PRODUKSI / nomor nota
  (`tugas->nomor_nota`). Fungsinya identitas resmi + nomor arsip.
- **Pembuka penugasan**: paragraf perintah penugasan.
- **1. Acara (a–i)**: Nama, Format, Disiarkan, Rapat Pra-Produksi (nama hari +
  tanggal Indonesia + jam WITA), Tempat Rapat, Produksi (hari/tanggal + jam
  WITA), Tempat Produksi, Narasumber, Topik. Fungsinya jadwal + materi yang
  harus dipenuhi crew.
- **2. Tim (a–l)** via `Tugas::getCrewNamesByRole($peran)` (`Tugas.php:62-71`,
  gabung nama per peran dengan koma, `-` bila kosong): Penanggung Jawab,
  Supervisor, Produser, Asisten Produser, Pengarah Acara, Asisten PA, Presenter,
  Cameraman, Teknisi, Editor, Dokumentasi/Publikasi, Unit Manager. Fungsinya
  daftar personel resmi per peran.
- **Penutup koordinasi**: instruksi koordinasi antar pihak.
- **TTD**: Kepala LPP RRI Kupang — Yuliana Marta Doky, S.Sos,
  NIP 19690702 199903 2 002. Fungsinya pengesahan (statis di template).
- **QR validasi** (`api.qrserver.com`): kode QR dari nomor nota. Fungsinya
  verifikasi cepat; **membutuhkan internet saat render PDF**.

## 4. Langkah Penggunaan

1. Buka **Detail Tugas** (`/pimpinan/tugases/{id}`).
2. Klik **Cetak Nota Produksi (PDF)** (membuka tab baru berisi PDF).
3. Di penampil PDF browser: **Simpan** (arsip) atau **Cetak** (kertas).
4. Bubuhkan tanda tangan/cap basah bila diperlukan prosedural.

## 5. Validasi dan Pesan Sistem

- Tidak ada tombol/validasi di dalam dokumen (output murni).
- `id_tugas` tak ada → 404 (`findOrFail`).
- Gagal render (mis. data tanggal null ekstrem) → error DomPDF; periksa isian
  nota di database.

## 6. Relasi Database

- Baca: `tugases` (semua kolom acara) + `tugas_crews` + `pegawais` (nama crew).
  Tidak menulis DB.

## 7. Screenshot

> 📷 Screenshot: [tab PDF: kop + seksi Acara + seksi Tim + TTD + QR]

| Bagian | Anotasi |
|--------|---------|
| Kop + nomor | Arsip |
| Seksi 1-2 | Jadwal + personel |
| TTD + QR | Pengesahan |

## 8. Tips

- Nama file mengikuti slug nama tugas (`Nota-Produksi-podcastkoe.pdf`) —
  rename saat arsip bila perlu kode tanggal.
- Butuh internet untuk QR; bila offline, PDF tetap jadi tapi QR bisa gagal
  dimuat (tergantung cache).
- Pegawai punya tombol cetak yang sama (`pegawai.tugases.nota-produksi`)
  memakai view ini — isinya identik.
