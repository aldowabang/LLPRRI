# Laporan PDF (Pimpinan)

Rekap seluruh tugas dalam satu dokumen unduhan.

## 1. Tujuan

Menyediakan **laporan gabungan lintas tugas** untuk pimpinan/arsip/audit dalam
format PDF siap cetak — tanpa membuka detail satu per satu.

## 2. Info Cepat

| Item | Keterangan |
|------|------------|
| Akses role | `pimpinan` |
| URL | `/pimpinan/laporan/pdf` |
| Route name | `pimpinan.laporan.pdf` |
| Handler | Closure `routes/web.php:65-77` |
| File Blade | `resources/views/livewire/pimpinan/laporan/pdf.blade.php` |
| Library | `Barryvdh\DomPDF` (`Pdf::loadView()->download()`) |

## 3. Fungsi Rinci / Isi Dokumen

Alur handler: `Tugas::with('pegawai.unit','pegawai.jabatan')->latest()->get()`
→ `Pdf::loadView('livewire.pimpinan.laporan.pdf', ['tugases','title' =>
'Laporan Tugas - LPP RRI Kupang'])` → `download('laporan-tugas-rri.pdf')`
(**langsung terunduh**, beda dengan nota yang stream).

Susunan dokumen:

- **Header**: LPP RRI KUPANG / Laporan Data Tugas / `Dicetak <now d/m/Y H:i>`.
  Fungsinya judul + stempel waktu penarikan data.
- **Tabel rekap** (satu baris per tugas, terbaru dulu):
  No | Nama Tugas | Pegawai (utama) | Unit (`pegawai->unit`) | Format |
  Tanggal Produksi (`d/m/Y`) | Tempat | Status (badge: abu/kuning/biru/hijau).
  Fungsinya potret portofolio tugas + penanggung + jadwal + progres.
- **Footer**: `Kupang, <tanggal>` + TTD Kepala LPP RRI Kupang.
  Fungsinya pengesahan laporan.
- **Akses**: hanya via tombol **Unduh PDF** di Daftar Tugas
  (`pimpinan/tugas/index.blade.php:5`); tidak ada menu tersendiri.

## 4. Langkah Penggunaan

1. Buka **Manajemen Tugas** (`/pimpinan/tugases`).
2. Klik **Unduh PDF** (kanan atas, ikon dokumen).
3. Tunggu file `laporan-tugas-rri.pdf` terunduh otomatis.
4. Buka, periksa, simpan ke arsip / cetak untuk laporan.

## 5. Validasi dan Pesan Sistem

- Tidak ada parameter/filter: berkas selalu merekap **seluruh** tugas
  (`->get()` tanpa where), terbaru dulu. Filter search/status di layar
  **tidak memengaruhi** isi PDF.
- Tugas kosong → PDF berisi tabel kosong + header/footer (tidak error).

## 6. Relasi Database

- Baca: `tugases` + `pegawai.unit` + `pegawai.jabatan`. Tidak menulis DB.
- Kolom Unit/Jabatan kosong (`-`) bila relasi pegawai rusak (unit/jabatan
  dihapus) — perbaiki via Admin.

## 7. Screenshot

> 📷 Screenshot: [tombol Unduh PDF di daftar + halaman pertama laporan terunduh]

| Elemen | Anotasi |
|--------|---------|
| Tombol | Kanan atas daftar |
| Header laporan | Judul + waktu cetak |
| Tabel | 8 kolom rekap |

## 8. Tips

- Untuk laporan per periode/orang tertentu: saring dulu di layar untuk analisa,
  lalu unduh penuh dan potong manual — atau minta pengembang menambahkan
  parameter tanggal/status pada closure laporan (pengembangan kecil di
  `routes/web.php:65-77`).
- Rename file unduhan dengan tanggal (`laporan-tugas-2026-09.pdf`) agar arsip
  rapi, karena nama default selalu sama.
