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
    $nama_lab = trim($_POST['nama_lab']);
    $kapasitas = (int)$_POST['kapasitas'];
    $lokasi = trim($_POST['lokasi']);
    $status = $_POST['status'];

    $stmt = mysqli_prepare($conn,"
        INSERT INTO laboratorium(nama_lab, kapasitas, lokasi, status)
        VALUES(?, ?, ?, ?)
    ");
    mysqli_stmt_bind_param($stmt, "siss", $nama_lab, $kapasitas, $lokasi, $status);
    mysqli_stmt_execute($stmt);
}

// EDIT
if($aksi == 'edit'){
    $id_lab = (int)$_POST['id_lab'];
    $nama_lab = trim($_POST['nama_lab']);
    $kapasitas = (int)$_POST['kapasitas'];
    $lokasi = trim($_POST['lokasi']);
    $status = $_POST['status'];

    $stmt = mysqli_prepare($conn,"
        UPDATE laboratorium
        SET nama_lab=?, kapasitas=?, lokasi=?, status=?
        WHERE id_lab=?
    ");
    mysqli_stmt_bind_param($stmt, "sissi", $nama_lab, $kapasitas, $lokasi, $status, $id_lab);
    mysqli_stmt_execute($stmt);
}

// HAPUS
if($aksi == 'hapus'){
    $id_lab = (int)$_POST['id_lab'];

    $stmt = mysqli_prepare($conn,"
        DELETE FROM laboratorium WHERE id_lab=?
    ");
    mysqli_stmt_bind_param($stmt, "i", $id_lab);
    mysqli_stmt_execute($stmt);
}

header("Location: kelola.php");
exit;
?>