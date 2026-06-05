# E-Lab Smart System

E-Lab Smart System adalah aplikasi manajemen peminjaman laboratorium berbasis web yang dibuat menggunakan PHP Native dan MySQL. Sistem ini dirancang untuk membantu proses pengajuan, verifikasi, dan monitoring penggunaan laboratorium secara lebih terstruktur.

## Fitur Utama

- Login multi-role
- Registrasi mahasiswa
- Dashboard admin, mahasiswa, dan dosen
- Pengajuan peminjaman laboratorium
- Verifikasi peminjaman oleh admin/dosen
- Monitoring status laboratorium
- Riwayat peminjaman mahasiswa
- Notifikasi status peminjaman
- Kelola data laboratorium
- Kelola data pengguna
- Laporan peminjaman
- Export laporan ke Excel dan PDF

## Role Pengguna

### Admin

Admin memiliki akses untuk memonitor sistem, mengelola laboratorium, mengelola pengguna, memverifikasi peminjaman, dan melihat laporan.

### Mahasiswa

Mahasiswa dapat melakukan registrasi, login, mengajukan peminjaman laboratorium, melihat riwayat, dan menerima notifikasi status pengajuan.

### Dosen

Dosen dapat melihat jadwal penggunaan laboratorium dan melakukan verifikasi terhadap pengajuan peminjaman mahasiswa.

## Teknologi yang Digunakan

- PHP Native
- MySQL
- Bootstrap 5
- CSS Modular
- Chart.js
- TCPDF
- XAMPP

## Struktur Folder

```txt
elab_smart_system/
├── admin/
│   ├── dashboard.php
│   ├── jadwal.php
│   ├── laporan.php
│   ├── kelola.php
│   ├── kelola_user.php
│   ├── proses.php
│   ├── kelola_proses.php
│   ├── kelola_user_proses.php
│   ├── export_excel.php
│   ├── export_pdf.php
│   ├── _guard.php
│   └── _nav.php
│
├── mahasiswa/
│   ├── dashboard.php
│   ├── simpan.php
│   ├── riwayat.php
│   ├── notifikasi.php
│   ├── profil.php
│   ├── _guard.php
│   └── _nav.php
│
├── dosen/
│   ├── dashboard.php
│   ├── jadwal.php
│   ├── verifikasi.php
│   ├── _guard.php
│   └── _nav.php
│
├── assets/
│   ├── css/
│   └── images/
│
├── koneksi.php
├── index.php
├── login.php
├── register.php
└── logout.php

## Alur Sistem

1. Mahasiswa mengajukan peminjaman
2. Status awal: **menunggu**
3. Admin/Dosen melakukan verifikasi
4. Status berubah menjadi **disetujui** atau **ditolak**
5. Mahasiswa menerima notifikasi
6. Data masuk ke riwayat dan laporan


Sistem menggunakan tiga status utama:

- menunggu
- disetujui
- ditolak
## Keamanan Dasar

Beberapa penerapan keamanan yang digunakan:

- Session guard per role
- Validasi role admin, mahasiswa, dan dosen
- Prepared statement untuk query dengan input user
- Password menggunakan `password_hash()`
- Validasi status peminjaman
- Validasi role pengguna
- Validasi input form
- Aksi update data menggunakan POST
## Cara Menjalankan Project

1. Clone repository ini.
2. Pindahkan folder project ke direktori `htdocs` XAMPP.
3. Jalankan Apache dan MySQL dari XAMPP.
4. Import database ke phpMyAdmin.
5. Sesuaikan konfigurasi database di file:
   - `elab_smart_system/koneksi.php`
6. Buka project melalui browser:
   - http://localhost/lab/elab_smart_system/
## Konfigurasi Database

File koneksi database berada di:

- `elab_smart_system/koneksi.php`

Contoh konfigurasi lokal:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'elab_smart_system');
```
## Catatan Pengembangan

Project ini dikembangkan sebagai sistem peminjaman laboratorium berbasis role dengan fokus pada kemudahan penggunaan, validasi data, dan tampilan antarmuka yang konsisten.

## Developer

- Dhoni Prasetya
- Teknik Informatika
- Universitas PGRI Ronggolawe Tuban

