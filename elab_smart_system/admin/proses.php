<?php
require_once "_guard.php";
require_once "../koneksi.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$status = isset($_GET['status']) ? trim($_GET['status']) : '';

$allowed_status = ['disetujui', 'ditolak'];

if ($id <= 0 || !in_array($status, $allowed_status, true)) {
    header("Location: dashboard.php");
    exit;
}

$stmt = mysqli_prepare($conn, "
    UPDATE peminjaman
    SET status = ?, dibaca = 0
    WHERE id_peminjaman = ?
");

if (!$stmt) {
    header("Location: dashboard.php");
    exit;
}

mysqli_stmt_bind_param($stmt, "si", $status, $id);
mysqli_stmt_execute($stmt);

header("Location: dashboard.php");
exit;
?>