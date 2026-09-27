# Nota Produksi PDF (Pegawai)

Cetakan nota resmi sebagai pegangan kerja lapangan.

## 1. Tujuan

Memberi pegawai **dokumen penugasan resmi yang sama persis** dengan yang dilihat
pimpinan — untuk dibawa ke lokasi, koordinasi dengan narasumber/unit, dan bukti
penugasan. Isinya identik dengan Nota pimpinan (satu view dipakai bersama).

## 2. Info Cepat

| Item | Keterangan |
|------|------------|
| Akses role | `pegawai` |
| URL | `/pegawai/tugases/{id_tugas}/nota-produksi` |
| Route name | `pegawai.tugases.nota-produksi` |
| Handler | Closure `routes/web.php:87-95` |
| File Blade | `resources/views/pimpinan/laporan/nota-produksi-pdf.blade.php` (dipakai bersama pimpinan) |
| Library | `Barryvdh\DomPDF` (`stream()`) |

## 3. Fungsi Rinci / Isi Dokumen

Alur handler: `Tugas::with(['pegawai','crews.pegawai'])->findOrFail($id_tugas)`
→ `Pdf::loadView('pimpinan.laporan.nota-produksi-pdf', ['tugas'])` →
`stream('Nota-Produksi-'.Str::slug(nama_tugas).'.pdf')` (terbuka di tab baru).

Isi (sama dengan `02-pimpinan/06-nota-produksi-pdf.md`):

- **Kop**: RADIO REPUBLIK INDONESIA / NOTA PRODUKSI / nomor nota.
- **Pembuka penugasan** + **1. Acara (a–i)**: Nama, Format, Disiarkan, Rapat
  (hari/tanggal Indonesia + WITA), Tempat Rapat, Produksi (hari/tanggal + WITA),
  Tempat Produksi, Narasumber, Topik.
- **2. Tim (a–l)** via `getCrewNamesByRole()`: PJ, Supervisor, Produser,
  Asisten Produser, Pengarah Acara, Asisten PA, Presenter, Cameraman, Teknisi,
  Editor, Dokumentasi/Publikasi, Unit Manager (lebih lengkap dari Kartu 2 di
  layar yang hanya 6 peran).
- **Penutup koordinasi** + **TTD** Kepala LPP RRI Kupang (Yuliana Marta Doky,
  S.Sos, NIP 19690702 199903 2 002) + **QR** `api.qrserver.com` (butuh internet
  saat render).

## 4. Langkah Penggunaan

1. Buka **Detail Tugas** (`/pegawai/tugases/{id}`).
2. Klik **Cetak Nota Produksi (PDF)** (tab baru).
3. Simpan PDF ke HP (pegangan offline) dan/atau cetak kertas untuk lokasi.
4. Tunjukkan ke narasumber/unit terkait sebagai bukti penugasan resmi bila diminta.

## 5. Validasi dan Pesan Sistem

- Output stream PDF `Nota-Produksi-<slug-nama-tugas>.pdf`; tidak ada tombol di
  dalam dokumen.
- `id_tugas` tak ada / bukan milik Anda (tergantung proteksi tambahan) → 404/403.

## 6. Relasi Database

- Baca: `tugases` + `tugas_crews` + `pegawais`. Tidak menulis DB.

## 7. Screenshot

> 📷 Screenshot: [tombol Cetak di detail pegawai + tab PDF: kop, seksi Tim penuh, TTD/QR]

## 8. Tips

- Kartu tim di layar hanya 6 peran; **nota PDF memuat 12 baris tim penuh** —
  jadikan PDF acuan personel lengkap.
- Simpan PDF sebelum ke lokasi minim sinyal; QR butuh internet hanya saat
  pembuatan, tidak saat dibaca dari file tersimpan.
