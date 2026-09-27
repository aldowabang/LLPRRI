# Buat Tugas (Nota Produksi Baru)

Form pembuatan Nota Produksi beserta susunan tim dan pengiriman WhatsApp otomatis.

## 1. Tujuan

Menerbitkan penugasan resmi yang sah (bernomor nota): detail acara + jadwal
rapat/produksi + penanggung jawab + **susunan 10 peran crew**. Sekali terbit,
sistem otomatis mengirim WA ke seluruh crew + pegawai utama.

## 2. Info Cepat

| Item | Keterangan |
|------|------------|
| Akses role | `pimpinan` |
| URL | `/pimpinan/tugases/create` |
| Route name | `pimpinan.tugases.create` (`routes/web.php:54`) |
| File Component | `app/Livewire/Pimpinan/Tugas/Create.php` |
| File Blade | `resources/views/livewire/pimpinan/tugas/create.blade.php` (`wire:submit="save"`) |

## 3. Fungsi Rinci Tiap Elemen Layar

### Header (blade baris 2-8)

- Judul `Buat Nota Produksi Baru` + subheading `Isi formulir penugasan produksi
  dan tentukan tim/kerabat kerja.` + tombol **Kembali** (subtle →
  `pimpinan.tugases.index`). Fungsinya keluar tanpa menyimpan.

### Seksi 1 — Informasi Nota & Acara (blade baris 12-98)

| Field (property) | Tipe input | Fungsi / aturan |
|------------------|------------|-----------------|
| Nomor Nota (`nomor_nota`) | text, placeholder `1198/RRI.KPG/...` | Nomor resmi; **terisi otomatis** di `mount()` (`Create.php:58-64`): `rand(1000-9999)/RRI.KPG/XVII.PPS.01.02/MM/YYYY`. Bisa diubah manual. `required\|max:100`. |
| Nama Acara (`nama_tugas`) | text, cth. Podcastkoe | Judul nota/tugas. `required\|max:150`. |
| Format (`format`) | text, cth. Talkshow/Liputan/Siaran | Jenis acara. `required\|max:50`. |
| Disiarkan (`disiarkan`) | date | Jadwal tayang. `required\|max:100` (disimpan string). |
| Tanggal Rapat (`tanggal_rapat`) | date | Rapat pra-produksi. `required\|date`. |
| Waktu Rapat (`waktu_rapat`) | time | Jam rapat (WITA). `required`. |
| Tempat Rapat (`tempat_rapat`) | text | Lokasi rapat. `required\|max:150`. |
| Tanggal Produksi (`tanggal_produksi`) | date | Hari H produksi. `required\|date`. |
| Waktu Produksi (`waktu_produksi`) | time | Jam produksi. `required`. |
| Tempat Produksi (`tempat_produksi`) | text, cth. Studio 1 | Lokasi produksi. `required\|max:150`. |
| Narasumber (`narasumber`) | text | Nama narasumber. `required\|max:255`. |
| Penanggung Jawab (`penanggung_jawab`) | select semua pegawai (`nama — jabatan`) | Nama PJ umum (teks). `required\|max:150`. |
| Supervisor (`supervisor`) | select semua pegawai | Nama supervisor (teks). `required\|max:150`. |
| Topik (`topik`) | textarea | Topik/judul bahasan. `required`. |

Setiap field punya `@error` merah di bawahnya (blade) dengan pesan Indonesia
khusus (`messages():91-126`, mis. `Produser wajib dipilih minimal 1 orang.`).

### Seksi 2 — Tim / Kerabat Kerja (blade baris 101-230, `crew_roles` 10 kunci)

Opsi diambil di `render()` (`Create.php:128-142`): semua pegawai + filter per
jabatan. **Ejaan jabatan harus persis** (lihat Data Jabatan).

Single-select (satu orang per peran, `wire:model="crew_roles.<peran>.0"`):

| Dropdown | Filter jabatan | Wajib? |
|----------|----------------|--------|
| Produser | `Produser` | **Ya, min 1** |
| Asisten Produser | `Staf` | Tidak |
| Pengarah Acara (PA) | `Penyiar` | Tidak |
| Asisten PA | `Staf` | Tidak |
| Presenter | `Penyiar` | Tidak |
| Editor | `Editor` | Tidak |

Multi-checkbox (bisa >1, box scroll `max-h-40`, `wire:model="crew_roles.<peran>"`):

| Kelompok | Sumber opsi | Wajib? |
|----------|-------------|--------|
| Cameraman | jabatan `Cameraman` | **Ya, min 1** |
| Teknisi | jabatan `Teknisi` | Tidak |
| Dokumentasi/Publikasi | gabungan Cameraman + Staf | Tidak |
| Unit Manager | `Kepala Bidang` / `Kepala Seksi` | Tidak |

Bila tidak ada pegawai berjabatan terkait, tampil teks miring
`Tidak ada pegawai dengan jabatan …` (blade baris 179/195/224).

### Tombol bawah (blade baris 232-235)

- **Terbitkan Nota Produksi & Kirim WA** (`type=submit primary`): validasi →
  tulis DB → broadcast WA → redirect index + flash.
- **Batal** (subtle → index): buang isian.

### Proses `save()` (`Create.php:144-206`)

1. `validate()` semua aturan (`rules():66-89`).
2. Tentukan pegawai utama: `$id_pegawai` (nullable) atau fallback
   `crew_roles['produser'][0]`.
3. `Tugas::create(...)` status awal `belum_mulai`; `tempat` fallback
   produksi→rapat→`RRI Kupang`.
4. Loop `crew_roles`: tiap id terisi → `TugasCrew::create(id_tugas,id_pegawai,
   peran)` (mendukung array maupun tunggal).
5. `broadcastNotaProduksi($tugas)` dalam try/catch → gagal WA hanya `Log::warning`,
   tugas tetap tersimpan.
6. Flash `Nota Produksi berhasil dibuat.` → redirect `pimpinan.tugases.index`.

## 4. Langkah Penggunaan

1. Buka **Tugas → Buat Tugas**.
2. Periksa **Nomor Nota** otomatis (ubah bila ada penomoran manual).
3. Isi Nama, Format, Disiarkan, jadwal Rapat (tgl+jam+tempat), jadwal Produksi
   (tgl+jam+tempat), Narasumber, Topik.
4. Pilih **Penanggung Jawab** dan **Supervisor** dari dropdown.
5. Susun tim: minimal **Produser (1)** + **Cameraman (1)**; lengkapi peran lain.
6. Klik **Terbitkan Nota Produksi & Kirim WA**.
7. Pastikan redirect ke daftar + pesan sukses; konfirmasi ke 1-2 crew bahwa WA
   berisi nota (nama, no nota, format, 2 tanggal+jam, tempat, topik) sudah masuk.

## 5. Validasi dan Pesan Sistem

Wajib (`rules()`): nomor_nota, nama_tugas, format, disiarkan, tanggal/waktu/
tempat rapat, tanggal/waktu/tempat produksi, narasumber, topik,
penanggung_jawab, supervisor, `crew_roles.produser required|array|min:1`,
`crew_roles.cameraman required|array|min:1` (+ tiap id `exists:pegawais`).
Gagal → pesan merah per field (Bahasa Indonesia). Sukses → flash + redirect.

## 6. Relasi Database & WA

- Tulis: 1 baris `tugases` (`status_tugas=belum_mulai`) + N baris `tugas_crews`
  (satu per personel per peran).
- Baca: `pegawais` (+`jabatan` untuk filter), `jabatans` (implisit via nama).
- WA: `WhatsAppService::broadcastNotaProduksi()` (Fonnte: `target,message,
  countryCode 62,delay 2`, `Authorization` token; 08→62). Dedup: satu orang
  dengan 2 peran hanya terima 1 pesan (pertama menyebut peran). Tanpa `no_hp`/
  token → skip + log, tidak error ke user.

## 7. Screenshot

> 📷 Screenshot: [Seksi 1 lengkap] + [Seksi 2 single-select] + [checkbox multi + tombol Terbitkan]

| Elemen | Anotasi |
|--------|---------|
| Nomor auto | Bisa diubah |
| Tanda * | Field wajib |
| Error merah | Contoh validasi |

## 8. Tips

- Jika dropdown crew kosong: minta Admin cek ejaan Jabatan (satu huruf beda =
  kosong). Lihat tabel filter di §3.
- `id_pegawai` utama tidak ada di form; otomatis = produser pertama. Itu yang
  tampil sebagai kolom Pegawai di daftar + penerima WA `sendTugasSelesai`.
- Kegagalan WA tidak menggagalkan nota — cek `no_hp` pegawai + token Fonnte
  (`config/services.fonnte`) bila crew tak terima pesan.
