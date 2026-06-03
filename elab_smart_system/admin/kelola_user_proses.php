<?php
session_start();
include '../koneksi.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit;
}

$aksi = isset($_POST['aksi']) ? $_POST['aksi'] : '';

// TAMBAH
if($aksi == 'tambah'){
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $password = md5(trim($_POST['password']));
    $role = $_POST['role'];

    // Cek email duplikat
    $cek = mysqli_prepare($conn,"SELECT * FROM users WHERE email=?");
    mysqli_stmt_bind_param($cek, "s", $email);
    mysqli_stmt_execute($cek);
    $hasil = mysqli_stmt_get_result($cek);

    if(mysqli_num_rows($hasil) > 0){
        header("Location: kelola_user.php?error=Email+sudah+terdaftar");
        exit;
    }

    $stmt = mysqli_prepare($conn,"
        INSERT INTO users(nama, email, password, role)
        VALUES(?, ?, ?, ?)
    ");
    mysqli_stmt_bind_param($stmt, "ssss", $nama, $email, $password, $role);
    mysqli_stmt_execute($stmt);
}

// HAPUS
if($aksi == 'hapus'){
    $id_user = (int)$_POST['id_user'];

    // Jangan hapus diri sendiri
    if($id_user == $_SESSION['id_user']){
        header("Location: kelola_user.php?error=Tidak+bisa+hapus+akun+sendiri");
        exit;
    }

    $stmt = mysqli_prepare($conn,"DELETE FROM users WHERE id_user=?");
    mysqli_stmt_bind_param($stmt, "i", $id_user);
    mysqli_stmt_execute($stmt);
}

header("Location: kelola_user.php");
exit;
?>