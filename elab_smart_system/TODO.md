# TODO - Security & Role Handling Upgrade

## Step 1 (done)
- Perbarui `login.php`:
  - Ganti MD5 verification → `password_verify`
  - Tambahkan `session_regenerate_id(true)` setelah login sukses
  - Tambahkan error handling detail untuk `mysqli_prepare` / `mysqli_stmt_execute`
  - Tangani role selain admin/mahasiswa (tampilkan error, jangan redirect)

## Step 2 (done)
- Perbarui `register.php`:
  - Ganti hash MD5 → `password_hash(PASSWORD_DEFAULT)`

## Step 3 (done)
- `login.php` kompatibilitas password MD5 lama:
  - fallback `md5(plain)` jika `password_verify` gagal
  - migrasi otomatis ke `password_hash` saat login pertama kali

## Step 4
- Review cepat halaman admin/mahasiswa yang membutuhkan role session (tidak perlu perubahan pada role-check saat ini)

## Step 5
- Test manual:
  - Registrasi + login (hash baru) -> sukses
  - Login user lama (hash MD5) -> sukses + otomatis migrasi
  - Role tak dikenal -> tampil error

