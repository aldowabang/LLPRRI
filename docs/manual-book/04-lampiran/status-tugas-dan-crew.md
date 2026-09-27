# Lampiran: Status Tugas dan Peran Crew

Referensi arti badge warna dan alur perubahan status di seluruh aplikasi.

## 1. Status Tugas

Dua level status berjalan paralel:

- **Status global** (`tugases.status_tugas`, ENUM `belum_mulai, proses, selesai,
  acc`; migrasi `2026_08_16_070613_create_tugases_table.php:20`): satu nilai per
  nota. Ditampilkan sebagai badge di dashboard/daftar/detail/laporan.
- **Status personal** (`tugas_crews.status`, awal `belum_mulai,proses,selesai`
  + `acc` via migrasi `2026_08_16_110000`): satu nilai **per personel per nota**.
  Ditampilkan di tabel crew (pimpinan) dan banner biru (pegawai).

Label + warna didefinisikan di accessor model (`Tugas.php:73-93`,
`TugasCrew.php:29-49`): `status_label` + `status_color`. Blade memakai
`match()` ke class Tailwind (abu/kuning/biru/hijau).

| Nilai | Label (`status_label`) | Warna (`status_color`) | Arti operasional |
|-------|------------------------|------------------------|------------------|
| `belum_mulai` | Belum Mulai | abu-abu (`gray`) | Nota diterbitkan / personel belum mulai |
| `proses` | Proses | kuning (`yellow`) | Sedang dikerjakan |
| `selesai` | Selesai | biru (`blue`) | Dilaporkan selesai, **menunggu ACC pimpinan** |
| `acc` | ACC | hijau (`green`) | Disetujui pimpinan (**final**) |

Diagram alur resmi:

```text
belum_mulai --(pegawai: Mulai [myCrew] / pimpinan: Tandai Semua)--> proses
proses --(pegawai: Selesai [myCrew + WA pimpinan] / pimpinan: Tandai Semua)--> selesai
selesai --(pimpinan: ACC per crew [accPerCrew] / ACC Tugas [acc])--> acc
```

Aturan kewenangan:

- Pegawai: hanya `belum→proses` (`mulai()`, `Show.php:30-37`) dan
  `proses→selesai` (`selesai()`, `Show.php:39-58`) **atas baris crew sendiri**.
  Tidak bisa `acc`, tidak bisa menyentuh crew orang lain, tidak menulis
  `tugases.status_tugas`.
- Pimpinan: `tandaiSemuaSelesai()` (crew belum/proses → selesai + tugas →
  selesai), `accPerCrew(id)` (crew selesai → acc; bila semua crew acc, tugas
  ikut acc), `acc()` (tugas → acc). Tidak ada tombol tolak/kembali dari UI.
- Kondisi tombol ACC: per crew hanya bila `crew.status==selesai`; ACC Tugas
  hanya bila `tugas.status==selesai`. Di luar itu tombol tidak dirender.

## 2. Peran Crew (`tugas_crews.peran`)

10 peran tetap (`TugasCrew::getPeranLabelAttribute`, `TugasCrew.php:51-66`):

| Nilai `peran` | Label (`peran_label`) | Sifat di form Buat Tugas |
|---------------|----------------------|--------------------------|
| `produser` | Produser | Single-select, **wajib min 1** (filter jabatan Produser) |
| `asisten_produser` | Asisten Produser | Single-select (Staf) |
| `pengarah_acara` | Pengarah Acara | Single-select (Penyiar) |
| `asisten_pa` | Asisten PA | Single-select (Staf) |
| `presenter` | Presenter | Single-select (Penyiar) |
| `cameraman` | Cameraman | Checkbox multi, **wajib min 1** (Cameraman) |
| `teknisi` | Teknisi | Checkbox multi (Teknisi) |
| `editor` | Editor | Single-select (Editor) |
| `dokumentasi` | Dokumentasi/Publikasi | Checkbox multi (Cameraman+Staf) |
| `unit_manager` | Unit Manager | Checkbox multi (Kepala Bidang/Seksi) |

Helper `Tugas::getCrewNamesByRole($peran)` (`Tugas.php:62-71`): mengumpulkan
`crew->pegawai->nama_pegawai` per peran, gabung koma, `-` bila kosong. Dipakai
di tabel Detail (pimpinan), Kartu Kerabat (pegawai, 6 peran), dan seksi Tim
nota/laporan PDF (12 baris penuh).
