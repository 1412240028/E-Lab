# Todo List Prototype E-Lab

## Ringkasan status
- Prioritas saat ini: P0 dan P1
- Fokus utama: autentikasi, workflow peminjaman, dan pengalaman pengguna
- Status progres: sebagian besar item inti sudah selesai dan disempurnakan, sisanya siap dieksekusi bertahap

## Progress terbaru
- helper umum untuk redirect, sanitasi, logging, dan alert sudah diterapkan
- validasi input diperkuat pada alur login, register, peminjaman, kelola lab, dan profil
- modul kelola laboratorium sudah lebih aman dan lebih lengkap
- filter laporan sudah mendukung pencarian real-time, status, tanggal, dan role
- area filter UI sudah dirapikan agar layout lebih nyaman dipakai

## P0 - Prioritas utama

- [x] Konsistensi autentikasi dan akses role
- [x] Validasi input server-side pada proses peminjaman
- [x] Feedback sukses/error yang lebih konsisten di dashboard
- [x] Perbaikan bug pada alur login/register
  - [x] cek redirect setelah login sukses
  - [x] pastikan role yang valid selalu diarahkan ke dashboard masing-masing
  - [x] tambahkan pesan error yang lebih jelas saat login gagal
- [x] Validasi input server-side pada semua form yang tersisa
  - [x] form kelola laboratorium
  - [x] form kelola user
  - [x] form profil pengguna

## P1 - Prioritas menengah

- [x] Pengecekan bentrok jadwal pada peminjaman
- [x] Notifikasi status peminjaman di halaman mahasiswa
- [x] Filter dan pencarian data laporan yang lebih kuat
  - [x] filter berdasarkan status
  - [x] filter berdasarkan tanggal
  - [x] pencarian berdasarkan nama, laboratorium, keperluan, dan role
- [x] Perbaikan tampilan responsif pada dashboard dan form
  - [x] cek layout mobile yang terkait area filter dan panel admin
  - [x] rapikan spacing dan tombol
- [x] Standardisasi UI alert/toast antar role
  - [x] gunakan pesan yang seragam di admin, dosen, dan mahasiswa

## P2 - Pengembangan lanjutan

- [ ] Export data lebih lengkap (CSV/Excel/PDF lanjutan)
- [ ] Audit trail aktivitas pengguna
- [ ] Modul pengaturan sistem dan konfigurasi laboratorium
- [ ] Reset password berbasis email
- [ ] Profil pengguna untuk admin/dosen yang lebih lengkap

## Sprint 1 - Fondasi stabil

- [x] Audit alur guard per role
- [x] Konsolidasi helper umum untuk redirect, validasi, dan logging
- [x] Perbaiki struktur alur akses dan feedback
- [ ] Perbaiki navigasi antar halaman agar lebih natural

## Sprint 2 - Workflow peminjaman

- [x] Implementasi pengecekan bentrok jadwal
- [x] Validasi form peminjaman sebelum submit
- [ ] Perjelas status peminjaman (menunggu/disetujui/ditolak/selesai)
- [ ] Perbaiki alur approval admin/dosen
- [ ] Tambah notifikasi yang lebih detail saat status berubah

## Sprint 3 - Pengalaman pengguna

- [x] Perbaiki form peminjaman agar lebih ringkas
- [x] Standarisasi alert sukses/gagal di dashboard
- [x] Optimalkan dashboard per role agar informasi utama lebih cepat terbaca
- [x] Tambahkan filter dan pencarian pada halaman data peminjaman

## Sprint 4 - Skalabilitas

- [x] Refactor awal dengan helper terpusat
- [x] Logging aktivitas sederhana
- [ ] Persiapkan dokumentasi deployment dan backup database
- [ ] Refactor bagian kode berulang yang masih tersisa

## Next action yang direkomendasikan
1. Selesaikan alur login/register dan validasi form yang tersisa
2. Perkuat filter dan pencarian laporan
3. Rapikan UI responsif untuk dashboard dan form
4. Siapkan dokumentasi deployment dan backup database
