<?php
require_once "_guard.php";
require_once "../koneksi.php";
require_once dirname(__DIR__) . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    elab_redirect('dashboard.php', 'error', 'Akses tidak valid');
}

$id = isset($_POST['id_peminjaman']) ? (int) $_POST['id_peminjaman'] : 0;
$status = elab_sanitize_text($_POST['status'] ?? '');

$allowedStatus = ['disetujui', 'ditolak'];

if ($id <= 0 || !in_array($status, $allowedStatus, true)) {
    elab_redirect('dashboard.php', 'error', 'Parameter tidak valid');
}

$stmt = mysqli_prepare($conn, "
    UPDATE peminjaman
    SET status = ?, dibaca = 0
    WHERE id_peminjaman = ?
    AND status = 'menunggu'
");

if (!$stmt) {
    elab_redirect('dashboard.php', 'error', 'Gagal menyiapkan data peminjaman');
}

mysqli_stmt_bind_param($stmt, 'si', $status, $id);
mysqli_stmt_execute($stmt);

elab_log_activity('peminjaman_diupdate', ['id_peminjaman' => $id, 'status' => $status]);
elab_redirect('dashboard.php', 'success', 'Peminjaman berhasil diupdate');
?>