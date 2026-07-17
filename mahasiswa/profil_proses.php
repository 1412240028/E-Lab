<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../login.php");
    exit;
}

$id_user       = (int) $_SESSION['id_user'];
$nama          = trim($_POST['nama']          ?? '');
$password_lama = trim($_POST['password_lama'] ?? '');
$password_baru = trim($_POST['password_baru'] ?? '');

if ($nama === '' || $password_lama === '') {
    header("Location: profil.php?error=Nama+dan+password+lama+wajib+diisi");
    exit;
}

// Ambil hash password saat ini
$stmtCek = mysqli_prepare($conn, "SELECT password FROM users WHERE id_user = ? LIMIT 1");
mysqli_stmt_bind_param($stmtCek, "i", $id_user);
mysqli_stmt_execute($stmtCek);
$dataUser = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtCek));

if (!$dataUser || !password_verify($password_lama, $dataUser['password'])) {
    header("Location: profil.php?error=Password+lama+salah");
    exit;
}

if ($password_baru === '') {
    // Hanya update nama
    $stmtUpd = mysqli_prepare($conn, "UPDATE users SET nama = ? WHERE id_user = ?");
    mysqli_stmt_bind_param($stmtUpd, "si", $nama, $id_user);
} else {
    if (strlen($password_baru) < 6) {
        header("Location: profil.php?error=Password+baru+minimal+6+karakter");
        exit;
    }
    $hashBaru = password_hash($password_baru, PASSWORD_DEFAULT);
    $stmtUpd  = mysqli_prepare($conn, "UPDATE users SET nama = ?, password = ? WHERE id_user = ?");
    mysqli_stmt_bind_param($stmtUpd, "ssi", $nama, $hashBaru, $id_user);
}

if (!mysqli_stmt_execute($stmtUpd)) {
    header("Location: profil.php?error=Gagal+menyimpan+perubahan");
    exit;
}

$_SESSION['nama'] = $nama;

header("Location: profil.php?success=Profil+berhasil+diperbarui");
exit;
?>