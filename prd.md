Berikut adalah pembaruan **Product Requirements Document (PRD)** yang telah disesuaikan dengan penambahan teknologi **Docker** (untuk containerization/deployment) dan penggantian UI framework menjadi **Tailwind CSS**, serta penegasan penggunaan **Laravel** dan **MySQL**.

---

# PRODUCT REQUIREMENTS DOCUMENT (PRD)
## Sistem Manajemen Tugas LPP RRI Kupang

**Versi Dokumen:** 1.1  
**Tanggal:** April 2026  
**Pemilik Produk / Penyusun:** Queen Qua Gesima Geniti Willa  
**Institusi:** Politeknik Negeri Kupang - Jurusan Teknik Elektro  

---

## 1. Gambaran Umum Produk (Product Overview)

### 1.1 Latar Belakang
Saat ini, LPP RRI Kupang masih mengandalkan metode manual (memo tertulis dan komunikasi lisan) dalam pengelolaan tugas harian. Hal ini menyebabkan miskomunikasi, keterlambatan pelaporan, minimnya dokumentasi terstruktur, dan rendahnya akuntabilitas pekerjaan. 

### 1.2 Visi Produk
Membangun sistem manajemen tugas berbasis web yang terotomatisasi untuk meningkatkan efisiensi, akuntabilitas, dan kecepatan komunikasi di lingkungan LPP RRI Kupang.

### 1.3 Solusi Produk
Sebuah aplikasi web berbasis framework Laravel yang terintegrasi dengan WhatsApp API Fonte. Sistem ini mengotomatisasi alur penugasan, pelacakan status, dan memberikan notifikasi real-time melalui WhatsApp kepada pegawai dan pimpinan. Sistem dikemas dalam lingkungan Docker untuk memastikan konsistensi deployment dan kemudahan konfigurasi.

---

## 2. Batasan dan Ruang Lingkup (Scope & Constraints)

### 2.1 In-Scope (Termasuk dalam pengembangan)
*   Sistem manajemen tugas internal LPP RRI Kupang berbasis web.
*   Modul autentikasi (Login/Logout) dengan pembagian peran (Admin, Pegawai, Pimpinan).
*   Modul manajemen data induk (Unit Kerja, Jabatan, Data Pegawai).
*   Modul manajemen tugas (Pembuatan tugas, Konfirmasi selesai, Validasi/Acc pimpinan).
*   Integrasi pengiriman notifikasi otomatis menggunakan WhatsApp Fonte API.
*   Ekspor laporan tugas dalam format PDF.
*   Containerization aplikasi menggunakan Docker untuk lingkungan development dan production.

### 2.2 Out-of-Scope (Tidak termasuk dalam pengembangan)
*   Aplikasi mobile terpisah (native Android/iOS). Sistem hanya akan diakses via web browser (responsive).
*   Integrasi selain WhatsApp Fonte (misal: SMS, Email, atau API WhatsApp lainnya).
*   Sistem untuk publik/eksternal (murni internal RRI Kupang).

---

## 3. Peran Pengguna (User Roles)

| Peran | Deskripsi |
| :--- | :--- |
| **Admin** | Mengelola data master (Pengguna, Unit Kerja, Jabatan, Pegawai). Tidak terlibat langsung dalam alur penugasan harian. |
| **Pimpinan** | Memberikan tugas kepada pegawai, memantau progres, dan memvalidasi (ACC) tugas yang dilaporkan selesai oleh pegawai. |
| **Pegawai** | Menerima notifikasi tugas, melihat detail tugas yang diberikan, dan mengkonfirmasi/melaporkan jika tugas telah selesai dikerjakan. |

---

## 4. Kebutuhan Fungsional (Functional Requirements)

### 4.1 Modul Autentikasi (UC-01)
*   **FR-AUTH-01:** Sistem harus menampilkan halaman login untuk semua peran.
*   **FR-AUTH-02:** Sistem harus memverifikasi username dan password (hashing).
*   **FR-AUTH-03:** Sistem harus mengarahkan pengguna ke dashboard sesuai perannya (Admin, Pegawai, Pimpinan) setelah login berhasil.
*   **FR-AUTH-04:** Sistem harus menyediakan fungsi Logout.

### 4.2 Modul Admin (Manajemen Data Induk)
*   **FR-ADM-01:** Admin dapat menambah, mengedit, dan menghapus data Pengguna (UC-02, UC-03).
*   **FR-ADM-02:** Admin dapat menambah, mengedit, dan menghapus data Unit Kerja (UC-04, UC-05).
*   **FR-ADM-03:** Admin dapat menambah, mengedit, dan menghapus data Jabatan.
*   **FR-ADM-04:** Admin dapat menambah, mengedit, dan menghapus data Pegawai (termasuk NIP, No HP untuk notifikasi WA, Unit, dan Jabatan).

### 4.3 Modul Pimpinan (Manajemen Tugas)
*   **FR-PMJ-01:** Pimpinan dapat melihat seluruh data pegawai (UC-10).
*   **FR-PMJ-02:** Pimpinan dapat membuat/menambahkan tugas baru kepada pegawai tertentu (UC-06). Atribut tugas: Nama tugas, format, tanggal produksi, tanggal rapat, tempat, dan topik.
*   **FR-PMJ-03:** Sistem secara otomatis mengirim notifikasi ke WhatsApp pegawai (menggunakan Fonte API) saat tugas baru diberikan (Ac-15).
*   **FR-PMJ-04:** Pimpinan dapat melihat status tugas (Belum Mulai, Proses, Selesai).
*   **FR-PMJ-05:** Pimpinan dapat meng-ACC/validasi tugas yang dilaporkan selesai oleh pegawai (UC-11).

### 4.4 Modul Pegawai (Pelaksanaan Tugas)
*   **FR-PGW-01:** Pegawai dapat melihat daftar tugas yang diberikan kepada dirinya (UC-08).
*   **FR-PGW-02:** Pegawai dapat mengubah status tugas menjadi "Proses" atau "Selesai" (Konfirmasi tugas) (UC-09).
*   **FR-PGW-03:** Sistem mengirim notifikasi ke WhatsApp Pimpinan saat pegawai mengkonfirmasi tugas selesai.

### 4.5 Modul Laporan
*   **FR-RPT-01:** Sistem dapat generate laporan data tugas pegawai.
*   **FR-RPT-02:** Laporan dapat diunduh/dicetak dalam format PDF.

---

## 5. Kebutuhan Non-Fungsional (Non-Functional Requirements)

*   **NFR-SEC-01:** Password harus di-hash menggunakan algoritma hashing bawaan Laravel (Bcrypt/Argon2).
*   **NFR-PERF-01:** Notifikasi WhatsApp harus terkirim dalam waktu maksimal 5 detik setelah event trigger (penugasan/konfirmasi).
*   **NFR-USAB-01:** Antarmuka harus responsif dan mendukung akses via Desktop, Tablet, dan Mobile menggunakan **Tailwind CSS**.
*   **NFR-COMP-01:** Sistem harus kompatibel dengan browser modern (Google Chrome, Mozilla Firefox, Microsoft Edge, Safari).
*   **NFR-TECH-01:** Sistem dibangun menggunakan arsitektur MVC (Model-View-Controller) pada Framework **Laravel**.
*   **NFR-OPS-01:** Aplikasi dan database harus berjalan dalam lingkungan terkontainerisasi menggunakan **Docker** untuk memastikan konsistensi environment antara development dan production, serta mempermudah proses deployment tanpa konflik versi PHP/MySQL di server lokal/produksi.

---

## 6. Arsitektur Sistem dan Tech Stack

| Komponen | Teknologi | Keterangan |
| :--- | :--- | :--- |
| **Backend Framework** | **Laravel** (PHP) | Menggunakan arsitektur MVC untuk logika bisnis. |
| **Frontend Styling** | **Tailwind CSS** | Utility-first CSS framework untuk membangun antarmuka responsif secara cepat dan kustom. |
| **Frontend Script** | JavaScript (Alpine.js/Vite) | Vite bawaan Laravel untuk asset bundling. |
| **Database** | **MySQL** | Relational Database Management System. |
| **Containerization** | **Docker** & Docker Compose | Memisahkan service App (Laravel/Nginx/Apache) dan DB (MySQL) dalam container terpisah. |
| **External API** | WhatsApp Fonte API | Gateway untuk pengiriman notifikasi instan. |
| **Reporting** | Laravel DomPDF | Library untuk generate laporan PDF. |

### 6.1 Arsitektur Docker (Docker Compose)
Sistem akan diorkestrasi menggunakan `docker-compose.yml` yang minimal berisi 2 service:
1.  **App Container:** Berisi image PHP-FPM + Nginx/Apache dengan kode basis Laravel.
2.  **DB Container:** Berisi image MySQL untuk menyimpan data persisten (menggunakan Docker Volumes agar data tidak hilang saat container dimatikan).

---

## 7. Perancangan Basis Data (Database Schema)

Berdasarkan Class Diagram dan Kamus Data pada proposal (Berjalan di atas **MySQL**):

### 7.1 Tabel `unit`
| Field | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| id_unit | INT | Primary Key, Auto Increment |
| nama_unit | VARCHAR(100) | Nama unit/bagian |

### 7.2 Tabel `jabatan`
| Field | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| id_jabatan | INT | Primary Key, Auto Increment |
| nama_jabatan | VARCHAR(100) | Nama jabatan |

### 7.3 Tabel `pegawai`
| Field | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| id_pegawai | INT | Primary Key, Auto Increment |
| id_unit | INT | Foreign Key -> unit.id_unit |
| id_jabatan | INT | Foreign Key -> jabatan.id_jabatan |
| nip | VARCHAR(30) | Unik |
| nama_pegawai | VARCHAR(100) | |
| jenis_kelamin | ENUM('L', 'P') | |
| no_hp | VARCHAR(20) | Digunakan untuk tujuan WhatsApp API |
| alamat | TEXT | |
| username | VARCHAR(50) | Unik, untuk login |
| password | VARCHAR(255) | Hashed |

### 7.4 Tabel `tugas`
| Field | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| id_tugas | INT | Primary Key, Auto Increment |
| id_pegawai | INT | Foreign Key -> pegawai.id_pegawai |
| nama_tugas | VARCHAR(150) | |
| format | VARCHAR(50) | |
| tanggal_produksi | DATE | |
| tanggal_rapat | DATE | |
| tempat | VARCHAR(150) | |
| topik | TEXT | |
| status_tugas | ENUM('belum_mulai', 'proses', 'selesai') | Default: 'belum_mulai' |

---

## 8. Alur Kerja Sistem (Business Flow)

1.  **Login:** User (Admin/Pimpinan/Pegawai) mengakses sistem melalui halaman login dan diarahkan ke dashboard masing-masing.
2.  **Setup Data (Admin):** Admin menginputkan Unit, Jabatan, dan Data Pegawai (termasuk nomor HP yang valid).
3.  **Penugasan (Pimpinan):** Pimpinan membuat tugas baru dan menugaskan ke pegawai A.
4.  **Notifikasi Masuk:** Sistem memanggil Fonte API mengirim pesan WhatsApp ke nomor HP Pegawai A.
5.  **Pengerjaan (Pegawai):** Pegawai A login, melihat tugas baru, dan mengerjakan tugas tersebut.
6.  **Konfirmasi Selesai (Pegawai):** Pegawai A mengubah status tugas menjadi "Selesai". Sistem mengirim notifikasi ke Pimpinan via WA.
7.  **Validasi (Pimpinan):** Pimpinan melihat laporan tugas selesai, lalu melakukan ACC (Validasi akhir).
8.  **Pelaporan:** Pimpinan/Admin mencetak laporan tugas dalam bentuk PDF.

---

## 9. Perancangan Antarmuka (UI/UX Wireframes)

Semua halaman dibangun menggunakan **Tailwind CSS** untuk memastikan desain yang modern, clean, dan responsif:

1.  **Halaman Login:** Form Username & Password dengan styling center-aligned Tailwind.
2.  **Dashboard Admin:** Ringkasan data pegawai, unit, dan jabatan. Navigasi sidebar menggunakan komponen Tailwind.
3.  **Data Pegawai:** Tabel daftar pegawai dengan fitur CRUD. Menggunakan Tailwind Tables & Action Buttons.
4.  **Form Tambah Tugas:** Form input detail tugas dan dropdown pemilihan pegawai.
5.  **Dashboard Pegawai:** Tabel daftar tugas ditugaskan beserta statusnya. Badge status berwarna (Tailwind utility classes).
6.  **Dashboard Pimpinan:** Tabel pemantauan progres seluruh pegawai. Tombol "ACC Tugas".
7.  **Halaman Laporan PDF:** Tampilan cetak laporan tugas yang dapat di-export ke PDF.

---

## 10. Strategi Pengujian (Testing Strategy)

1.  **Pengujian Fungsionalitas (Blackbox Testing):**
    *   Menguji CRUD pada Tugas, Unit, dan Pegawai.
    *   Menguji relasi foreign key antar tabel.
    *   Menguji trigger pengiriman WhatsApp Fonte API.
2.  **Pengujian Kompatibilitas & UI:**
    *   Cross-browser testing (Chrome, Firefox, Edge, Safari).
    *   Responsive testing (Desktop, Tablet, Mobile) menggunakan Tailwind breakpoints (sm, md, lg).
3.  **Pengujian Environment (Docker):**
    *   Memastikan container Docker dapat di-build (`docker-compose up -d`) tanpa error.
    *   Memastikan koneksi antara App Container (Laravel) dan DB Container (MySQL) berjalan lancar.
    *   Memastikan data MySQL persisten (tidak hilang saat container di-restart).
4.  **Performance Testing:**
    *   Mengukur kecepatan respon sistem dan keberhasilan rate pengiriman notifikasi WA.

---

## 11. Pemeliharaan (Maintenance)

*   **Corrective Maintenance:** Perbaikan bug atau eror yang ditemukan setelah sistem *go-live*.
*   **Perfective Maintenance:** Penambahan fitur atau perubahan UI berdasarkan *feedback* pengguna dengan memanfaatkan fleksibilitas Tailwind CSS.
*   **Adaptive Maintenance:** 
    *   Penyesuaian sistem jika terdapat *update* versi Laravel atau perubahan endpoint pada WhatsApp Fonte API.
    *   Pembaruan Docker image (PHP & MySQL) untuk keamanan dan performa server jangka panjang.