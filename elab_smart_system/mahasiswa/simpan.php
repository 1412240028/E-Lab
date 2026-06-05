<?php
require_once "_guard.php";
require_once "../koneksi.php";

$id_user = (int) $_SESSION['id_user'];
$id_lab = isset($_POST['id_lab']) ? (int) $_POST['id_lab'] : 0;
$tanggal_pinjam = isset($_POST['tanggal_pinjam']) ? trim($_POST['tanggal_pinjam']) : '';
$jam_mulai = isset($_POST['jam_mulai']) ? trim($_POST['jam_mulai']) : '';
$jam_selesai = isset($_POST['jam_selesai']) ? trim($_POST['jam_selesai']) : '';
$keperluan = isset($_POST['keperluan']) ? trim($_POST['keperluan']) : '';

if ($id_lab === 0 || $tanggal_pinjam === '' || $jam_mulai === '' || $jam_selesai === '' || $keperluan === '') {
    echo "<script>alert('Semua field harus diisi'); window.location='dashboard.php';</script>";
    exit;
}

if ($jam_mulai >= $jam_selesai) {
    echo "<script>alert('Jam mulai harus lebih awal dari jam selesai'); window.location='dashboard.php';</script>";
    exit;
}

$cekBentrok = mysqli_prepare($conn, "
    SELECT id_peminjaman
    FROM peminjaman
    WHERE id_lab = ?
    AND tanggal_pinjam = ?
    AND status = 'disetujui'
    AND jam_mulai < ?
    AND jam_selesai > ?
    LIMIT 1
");

if (!$cekBentrok) {
    echo "<script>alert('Gagal menyiapkan pengecekan jadwal'); window.location='dashboard.php';</script>";
    exit;
}

mysqli_stmt_bind_param(
    $cekBentrok,
    "isss",
    $id_lab,
    $tanggal_pinjam,
    $jam_selesai,
    $jam_mulai
);

mysqli_stmt_execute($cekBentrok);
$hasilBentrok = mysqli_stmt_get_result($cekBentrok);

if (mysqli_num_rows($hasilBentrok) > 0) {
    echo "<script>alert('Jadwal bentrok! Laboratorium sudah digunakan pada jam tersebut'); window.location='dashboard.php';</script>";
    exit;
}

$stmt = mysqli_prepare($conn, "
    INSERT INTO peminjaman(id_user, id_lab, tanggal_pinjam, jam_mulai, jam_selesai, keperluan, status)
    VALUES(?, ?, ?, ?, ?, ?, 'menunggu')
");

if (!$stmt) {
    echo "<script>alert('Gagal menyiapkan data peminjaman'); window.location='dashboard.php';</script>";
    exit;
}

mysqli_stmt_bind_param(
    $stmt,
    "iissss",
    $id_user,
    $id_lab,
    $tanggal_pinjam,
    $jam_mulai,
    $jam_selesai,
    $keperluan
);

if (!mysqli_stmt_execute($stmt)) {
    echo "<script>alert('Peminjaman gagal diajukan'); window.location='dashboard.php';</script>";
    exit;
}

echo "<script>alert('Peminjaman berhasil diajukan'); window.location='dashboard.php';</script>";
exit;
?>