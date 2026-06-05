# TODO (Markdown)

## Plan Besar: Standarisasi E-Lab Smart System

### Target akhirnya

Admin, Mahasiswa, dan Dosen punya pola kode yang konsisten:

- guard rapi
- nav reusable
- UI satu style
- query lebih aman
- flow peminjaman jelas
- folder project lebih profesional

---

[x] Project bisa jalan di localhost
[x] Struktur project sudah diketahui
[x] Halaman utama sudah dicek
[x] Role login sudah dites
[x] Flow database sudah dipahami
[x] Backup lokal sudah dibuat
[x] Commit baseline sudah dibuat
[x] Branch refactor sudah dibuat

## Fase 1 — Rapikan Struktur Role

### Tujuan

Biar setiap role punya pola yang sama, nggak ada yang “anak emas” dan “anak tiri CSS”.

### Yang dikerjakan

Struktur ideal:

```text
elab_smart_system/
├── admin/
│   ├── _guard.php
│   ├── _nav.php
│   ├── dashboard.php
│   ├── kelola-lab.php
│   ├── jadwal.php
│   ├── users.php
│   └── laporan.php
│
├── mahasiswa/
│   ├── _guard.php
│   ├── _nav.php
│   ├── dashboard.php
│   ├── peminjaman.php
│   ├── history.php
│   ├── notifikasi.php
│   └── profil.php
│
├── dosen/
│   ├── _guard.php
│   ├── _nav.php
│   ├── dashboard.php
│   ├── jadwal.php
│   └── verifikasi.php
```

### Prioritas

- Pastikan admin punya _guard.php dan _nav.php.
- Pastikan mahasiswa punya _guard.php dan _nav.php.
- Dosen sudah lumayan rapi, tinggal dijadikan patokan.

### Hasil akhir fase ini

Setiap halaman tidak lagi nulis session check berulang-ulang.

Contoh pola:

```php
require_once "_guard.php";
require_once "../koneksi.php";
```

---

## Fase 2 — Standarisasi Navigasi

### Tujuan

Navbar/bottom nav tiap role harus konsisten secara struktur, tapi tetap beda warna sesuai role.

### Konsep role color

- Admin      → ungu / biru gelap
- Mahasiswa  → biru / cyan
- Dosen      → hijau / biru akademik

### Yang dikerjakan

Buat nav reusable:

- admin/_nav.php
- mahasiswa/_nav.php
- dosen/_nav.php

Isi tiap nav pakai active state seperti punya dosen sekarang:

```php
basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : ''
```

### Hasil akhir fase ini

Kalau nanti tambah menu baru, tinggal edit satu file nav, bukan edit 5 halaman satu-satu. Ini hemat emosi dan mengurangi “anj ini navbar beda sendiri” moment.

---

## Fase 3 — Rapikan Flow Peminjaman

### Tujuan

Biar alur bisnis sistem jelas dari mahasiswa sampai admin/dosen.

### Flow ideal

Mahasiswa ajukan peminjaman

↓

Status = menunggu

↓

Dosen/Admin verifikasi

↓

Disetujui / Ditolak

↓

Jika disetujui → muncul di Jadwal

### Status yang harus dipakai konsisten

- menunggu
- disetujui
- ditolak

Jangan campur seperti:

- pending
- approve
- approved
- rejected
- setuju

Itu nanti bikin query error halus. Halus, tapi nyebelin.

### Yang dikerjakan

- Cek semua file yang berhubungan dengan peminjaman.
- Samakan status.
- Pastikan halaman jadwal hanya ambil:

```sql
WHERE status = 'disetujui'
```

- Pastikan halaman verifikasi hanya ambil:

```sql
WHERE status = 'menunggu'
```

---

## Fase 4 — Standarisasi Query Database

### Tujuan

Query yang menerima input user harus pakai prepared statement.

### Sudah bagus

Login dan beberapa bagian verifikasi sudah pakai prepared statement.

### Yang perlu dicek

Semua proses seperti:

- tambah peminjaman
- edit profil
- hapus data
- update status
- kelola lab
- kelola user

Kalau ada input dari form, jangan langsung begini:

```php
$id = $_POST['id'];
mysqli_query($conn, "DELETE FROM users WHERE id_user=$id");
```

Lebih aman jadi:

```php
$stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id_user=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
```

### Hasil akhir fase ini

Project lebih aman dan lebih layak disebut sistem, bukan “PHP cepat jadi cepat panik”.

---

## Fase 5 — Rapikan UI/UX Per Role

### Tujuan

Semua halaman terasa satu keluarga desain.

### Standar layout

Setiap halaman sebaiknya punya pola:

```text
app-shell
└── app-container
    ├── header
    ├── app-body
    └── nav
```

### Komponen yang distandarkan

- card
- button
- badge status
- alert
- form
- table/list
- empty state

### Mapping CSS

```text
assets/css/
├── base/
│   ├── variables.css
│   └── global.css
│
├── components/
│   ├── buttons.css
│   ├── forms.css
│   ├── cards.css
│   ├── badges.css
│   └── alerts.css
│
├── layout/
│   ├── shell.css
│   └── navigation.css
│
└── pages/
    ├── admin/
    ├── mahasiswa/
    └── dosen/
```

### Prioritas restyle

Urutan yang paling masuk akal:

1. Dosen dashboard
2. Dosen jadwal
3. Dosen verifikasi
4. Admin dashboard
5. Admin kelola lab
6. Admin users
7. Admin laporan
8. Mahasiswa dashboard
9. Mahasiswa peminjaman
10. Mahasiswa history/profil/notifikasi

Karena dosen baru kamu kerjakan, mending distabilkan dulu biar jadi template.

---

## Fase 6 — Rapikan Validasi dan Feedback

### Tujuan

User nggak bingung setelah klik tombol.

### Yang ditambahkan

Setiap aksi harus punya feedback:

- Berhasil mengajukan peminjaman
- Berhasil menyetujui pengajuan
- Berhasil menolak pengajuan
- Gagal menyimpan data
- Data tidak ditemukan

### Format feedback

Pakai alert reusable:

```html
<div class="alert alert-success">...</div>
<div class="alert alert-danger">...</div>
```

Atau nanti bisa dibikin lebih cakep pakai custom class:

```html
<div class="elab-alert success"></div>
<div class="elab-alert danger"></div>
```

---

## Fase 7 — Rapikan Keamanan Session

### Tujuan

Role tidak bisa saling nyasar halaman.

### Standar guard

Contoh untuk admin:

```php
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_user']) || !isset($_SESSION['role'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}
?>
```

Untuk mahasiswa dan dosen tinggal ganti role.

### Bonus

Tambahkan `session_regenerate_id(true)` setelah login, dan ini sudah ada di login kamu. Good.

---

## Fase 8 — Rapikan Dokumentasi Project

### Tujuan

Repo kamu nggak cuma bisa jalan, tapi juga bisa dipahami orang lain/dosen.

### Tambahkan README.md

Isi minimal:

```md
# E-Lab Smart System

Sistem manajemen peminjaman laboratorium berbasis PHP Native dan MySQL.

## Role
- Admin
- Mahasiswa
- Dosen

## Fitur
- Login & registrasi mahasiswa
- Pengajuan peminjaman lab
- Verifikasi peminjaman
- Jadwal penggunaan laboratorium
- Dashboard statistik

## Teknologi
- PHP Native
- MySQL
- Bootstrap
- CSS Modular
```

Tambahkan struktur folder:

```text
elab_smart_system/
├── admin/
├── mahasiswa/
├── dosen/
├── assets/
├── koneksi.php
├── login.php
└── register.php
```

Ini penting buat nilai project. Dosen biasanya suka kalau project keliatan niat, bukan cuma “yang penting jalan di laptop saya”.

---

## Fase 9 — Testing Manual

### Tujuan

Pastikan fitur tidak cuma cakep, tapi beneran jalan.

### Test case wajib

1. Login admin
2. Login mahasiswa
3. Login dosen
4. Mahasiswa daftar akun
5. Mahasiswa ajukan peminjaman
6. Status awal masuk sebagai menunggu
7. Dosen melihat pengajuan di verifikasi
8. Dosen setujui pengajuan
9. Pengajuan pindah ke jadwal
10. Dosen tolak pengajuan
11. User logout
12. User role mahasiswa tidak bisa buka halaman dosen/admin
13. User role dosen tidak bisa buka halaman admin
14. User belum login tidak bisa buka dashboard

---

## Fase 10 — Commit Bertahap

Jangan satu commit isinya semua. Nanti kalau error, rollback-nya kayak nyari jarum di tumpukan stylesheet.

### Urutan commit ideal

```bash
git add .
git commit -m "refactor: standardize role guards and navigation"
git add .
git commit -m "refactor: align peminjaman status workflow"
git add .
git commit -m "style: improve dosen pages layout consistency"
git add .
git commit -m "style: standardize shared UI components"
git add .
git commit -m "docs: add project documentation"
```

---

## Roadmap Eksekusi yang Paling Masuk Akal

### Hari/Step 1

Rapikan guard dan nav semua role.

- admin/_guard.php
- admin/_nav.php
- mahasiswa/_guard.php
- mahasiswa/_nav.php
- dosen/_guard.php
- dosen/_nav.php

### Step 2

Rapikan halaman dosen sampai solid:

- dosen/dashboard.php
- dosen/jadwal.php
- dosen/verifikasi.php

### Step 3

Samakan style admin dan mahasiswa agar mengikuti pola dosen.

### Step 4

Audit query yang masih raw SQL dari input user.

### Step 5

Tambah README.

