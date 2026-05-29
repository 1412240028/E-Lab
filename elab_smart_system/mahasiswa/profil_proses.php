<?php
session_start();
include '../koneksi.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'mahasiswa'){
    header("Location: ../login.php");
    exit;
}

$id_user = (int)$_SESSION['id_user'];
$nama = trim($_POST['nama']);
$password_baru = trim($_POST['password_baru']);
$password_lama = md5(trim($_POST['password_lama']));

// Cek password lama
$cek = mysqli_prepare($conn,"
    SELECT * FROM users WHERE id_user=? AND password=?
");
mysqli_stmt_bind_param($cek, "is", $id_user, $password_lama);
mysqli_stmt_execute($cek);
$hasil = mysqli_stmt_get_result($cek);

if(mysqli_num_rows($hasil) == 0){
    echo "<script>alert('Password lama salah'); window.location='profil.php';</script>";
    exit;
}

// Update nama saja
if($password_baru == ''){
    $stmt = mysqli_prepare($conn,"
        UPDATE users SET nama=? WHERE id_user=?
    ");
    mysqli_stmt_bind_param($stmt, "si", $nama, $id_user);
}else{
    // Update nama + password
    $password_baru_hash = md5($password_baru);
    $stmt = mysqli_prepare($conn,"
        UPDATE users SET nama=?, password=? WHERE id_user=?
    ");
    mysqli_stmt_bind_param($stmt, "ssi", $nama, $password_baru_hash, $id_user);
}

mysqli_stmt_execute($stmt);

// Update session nama
$_SESSION['nama'] = $nama;

echo "<script>alert('Profil berhasil diupdate'); window.location='profil.php';</script>";
?>