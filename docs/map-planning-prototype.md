# Map Planning Prototype E-Lab

## 1. Tujuan Pengembangan

Prototipe ini dikembangkan sebagai sistem manajemen peminjaman laboratorium berbasis web dengan alur:
- registrasi dan login multi-role
- pengajuan peminjaman oleh mahasiswa
- review/verifikasi oleh admin dan dosen
- monitoring status peminjaman
- pelaporan dan ekspor data

## 2. Status Saat Ini

Aplikasi sudah memiliki fondasi fungsional yang mencakup:
- autentikasi pengguna dan guard akses per role
- dashboard untuk admin, mahasiswa, dan dosen
- form peminjaman dengan validasi dan cek bentrok jadwal
- manajemen laboratorium (tambah, edit, hapus, status, kapasitas, lokasi)
- halaman riwayat, verifikasi, dan laporan
- fitur ekspor Excel/PDF
- helper bersama untuk redirect, sanitasi input, logging, dan alert konsisten

Progress terbaru yang sudah diimplementasikan:
- validasi server-side pada alur login, register, peminjaman, kelola lab, dan profil
- perbaikan alur feedback sukses/error dengan alert yang konsisten antar role
- filter laporan real-time berdasarkan pencarian, status, tanggal, dan role
- penataan ulang UI area filter agar lebih rapi dan tidak membuang ruang kosong

## 3. Area Prioritas Pengembangan

### A. Arsitektur dan Kode
- Logika bisnis sudah mulai dikonsolidasikan melalui helper umum.
- Penggunaan koneksi database dan redirect/alert sudah lebih konsisten.
- Duplikasi kode antar halaman role sudah berkurang, meski masih ada ruang untuk refactor lanjutan.

### B. Pengalaman Pengguna
- Perbaiki alur navigasi antar role.
- Tambahkan feedback yang lebih jelas saat berhasil/gagal.
- Sederhanakan form peminjaman dan proses verifikasi.

### C. Keamanan
- Validasi input sisi server secara konsisten.
- Gunakan prepared statement untuk semua query.
- Tambahkan proteksi terhadap akses tidak sah antar role.

### D. Data dan Laporan
- Pencarian dan filter laporan sudah diperkuat, termasuk pencarian real-time dan filter role.
- Ekspor data Excel/PDF sudah tersedia dan dapat dipakai pada hasil filter.
- Struktur audit trail masih dapat ditingkatkan di tahap berikutnya.

## 4. Modul inti yang Perlu Diperkuat

### 1) Autentikasi dan Manajemen User
- login/register
- reset password
- role-based access control
- profil pengguna

### 2) Peminjaman Laboratorium
- pengajuan peminjaman
- cek bentrok jadwal
- approval/rejection workflow
- notifikasi status

### 3) Manajemen Laboratorium
- tambah/edit/hapus lab
- status ketersediaan
- kapasitas dan lokasi

### 4) Dashboard dan Laporan
- ringkasan statistik
- grafik bulanan
- laporan peminjaman
- export data

## 5. Rencana Tahap Pengembangan

### Tahap 1 - Stabilisasi Fondasi
- review struktur folder dan file utama
- konsolidasi koneksi database
- perbaiki bug login/register yang terlihat
- standarize layout dan navigasi

### Tahap 2 - Penguatan Alur Bisnis
- perbaiki proses submit peminjaman
- tambahkan validasi bentrok jadwal
- perbaiki alur approval admin/dosen

### Tahap 3 - Pengalaman dan UI
- perbaiki desain responsif
- tambah notifikasi toast/alert yang lebih konsisten
- optimalkan dashboard per role

### Tahap 4 - Lanjutan dan Skalabilitas
- refactor ke struktur modular
- tambahkan logging aktivitas
- persiapan deployment dan backup database

## 6. Rekomendasi Implementasi Selanjutnya

Prioritas pertama:
1. konsistensi autentikasi dan akses role
2. validasi dan workflow peminjaman
3. perbaikan UI/UX pada form dan dashboard
4. penataan kode agar lebih mudah dikembangkan

## 7. Catatan Teknis

- Backend saat ini masih menggunakan PHP native dan MySQL.
- Project ini sudah terhubung dengan dependency TCPDF melalui Composer.
- Struktur aplikasi cukup baik untuk prototype, tetapi perlu refactor bertahap agar lebih scalable.

## 8. Roadmap Eksekusi Implementasi

### Sprint 1 - Fondasi Stabil
- audit alur login, register, dan guard per role
- konsolidasikan koneksi database ke satu pendekatan yang konsisten
- tambahkan validasi input dan pesan error yang lebih jelas
- perbaiki navigasi antar halaman agar alur terasa lebih natural

### Sprint 2 - Workflow Peminjaman
- implementasikan pengecekan bentrok jadwal sebelum submit
- perjelas status peminjaman: menunggu, disetujui, ditolak, selesai
- tambahkan alur approval yang lebih rapi untuk admin dan dosen
- sediakan notifikasi status pengajuan yang mudah dipahami

### Sprint 3 - Pengalaman Pengguna
- perbaiki desain form peminjaman agar lebih singkat dan intuitif
- standardisasi alert, toast, dan feedback sukses/gagal
- optimalkan dashboard per role agar informasi utama lebih cepat terbaca
- tambahkan filter dan pencarian pada halaman data peminjaman

### Sprint 4 - Penguatan dan Skalabilitas
- refactor bagian kode yang sering duplikat
- siapkan struktur helper atau module untuk proses bisnis yang berulang
- tambahkan logging aktivitas sederhana untuk audit
- persiapkan dokumentasi deployment dan backup database

## 9. Backlog Prioritas

### P0 - Wajib dikerjakan lebih dulu
- konsistensi autentikasi dan akses role
- validasi input server-side pada semua form
- workflow submit peminjaman yang aman dan jelas
- perbaikan bug pada alur login/register

### P1 - Prioritas menengah
- pengecekan jadwal bentrok
- notifikasi status peminjaman
- filter dan pencarian data laporan
- perbaikan tampilan responsif pada dashboard dan form

### P2 - Pengembangan lanjutan
- export data lebih lengkap
- audit trail aktivitas pengguna
- modul pengaturan sistem dan konfigurasi laboratorium lebih lengkap

## 10. Deliverables Target

- prototype yang lebih stabil secara alur bisnis
- antarmuka yang lebih konsisten antar role
- proses peminjaman yang lebih aman dan terukur
- dokumen implementasi yang siap dipakai untuk pengembangan lanjutan
