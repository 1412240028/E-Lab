# E-Lab Smart System

E-Lab Smart System adalah aplikasi manajemen peminjaman laboratorium berbasis web yang dibuat menggunakan PHP Native dan MySQL. Sistem ini dirancang untuk membantu proses pengajuan, verifikasi, dan monitoring penggunaan laboratorium secara lebih terstruktur.

## Fitur Utama

- Login multi-role untuk admin, dosen, dan mahasiswa
- Registrasi mahasiswa
- Dashboard untuk masing-masing role
- Pengajuan peminjaman laboratorium
- Verifikasi peminjaman oleh admin/dosen
- Monitoring status peminjaman
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

Mahasiswa dapat mendaftar, login, mengajukan peminjaman laboratorium, melihat riwayat, dan menerima notifikasi status pengajuan.

### Dosen

Dosen dapat melihat jadwal penggunaan laboratorium dan melakukan verifikasi terhadap peminjaman mahasiswa.

## Teknologi yang Digunakan

- PHP Native
- MySQL
- Bootstrap 5
- CSS modular
- Chart.js
- TCPDF
- Composer
- XAMPP / PHP lokal

## Struktur Folder

```txt
E-Lab/
├── admin/
│   ├── dashboard.php
│   ├── export_excel.php
│   ├── export_pdf.php
│   ├── jadwal.php
│   ├── kelola.php
│   ├── kelola_proses.php
│   ├── kelola_user.php
│   ├── kelola_user_proses.php
│   ├── laporan.php
│   ├── proses.php
│   ├── _guard.php
│   └── _nav.php
├── assets/
│   ├── css/
│   └── images/
├── dosen/
│   ├── dashboard.php
│   ├── jadwal.php
│   ├── verifikasi.php
│   ├── _guard.php
│   └── _nav.php
├── mahasiswa/
│   ├── cetak_surat.php
│   ├── dashboard.php
│   ├── notifikasi.php
│   ├── profil.php
│   ├── profil_proses.php
│   ├── riwayat.php
│   ├── simpan.php
│   ├── _guard.php
│   └── _nav.php
├── vendor/
├── composer.json
├── composer.lock
├── koneksi.php
├── login.php
├── logout.php
├── register.php
└── index.php
```

## Kebutuhan Sistem

- PHP 7.4 atau lebih baru
- MySQL / MariaDB
- Composer
- XAMPP atau stack web serupa

## Instalasi dan Menjalankan Project

1. Clone repository ini ke folder lokal:
   ```bash
git clone https://github.com/1412240028/E-Lab
cd E-Lab
```
2. Install dependency Composer:
   ```bash
composer install
```
3. Salin file konfigurasi database (jika diperlukan) dan sesuaikan `koneksi.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'elab_smart_system');
   ```
4. Jalankan Apache dan MySQL di XAMPP.
5. Import database ke phpMyAdmin.
6. Akses aplikasi melalui browser:
   ```text
http://localhost/lab/
```

## Konfigurasi Database

File konfigurasi database berada di `koneksi.php`.

Contoh konfigurasi lokal:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'elab_smart_system');
```

## Catatan Penting

- Folder `vendor/` dibutuhkan untuk dependency TCPDF.
- Jangan menghapus `vendor/` jika ingin menjalankan export PDF.
- Jika menggunakan VS Code, tambahkan `vendor/` ke exclude agar editor tidak memproses file dependency besar.

## Pengembangan

Project ini dikembangkan sebagai sistem peminjaman laboratorium berbasis role dengan fokus pada kemudahan penggunaan, validasi data, dan tampilan antarmuka yang konsisten.

## Developer

- Dhoni Prasetya
- Teknik Informatika
- Universitas PGRI Ronggolawe Tuban

