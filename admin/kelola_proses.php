<?php
require_once "_guard.php";
require_once "../koneksi.php";
require_once "../includes/functions.php";

$aksi = isset($_POST['aksi']) ? trim($_POST['aksi']) : '';
$allowedAksi = ['tambah', 'edit', 'hapus'];

if (!in_array($aksi, $allowedAksi, true)) {
    elab_redirect('kelola.php', 'error', 'Aksi tidak valid');
}

$allowedStatus = ['tersedia', 'tidak tersedia'];

if ($aksi === 'tambah' || $aksi === 'edit') {
    $nama_lab = elab_sanitize_text($_POST['nama_lab'] ?? '');
    $kapasitas = isset($_POST['kapasitas']) ? (int) $_POST['kapasitas'] : 0;
    $lokasi = elab_sanitize_text($_POST['lokasi'] ?? '');
    $status = elab_sanitize_text($_POST['status'] ?? '');

    if ($nama_lab === '' || $kapasitas <= 0 || $lokasi === '' || !in_array($status, $allowedStatus, true)) {
        elab_redirect('kelola.php', 'error', 'Semua field harus diisi dengan benar');
    }
}

if ($aksi === 'tambah') {
    $stmt = mysqli_prepare($conn, "
        INSERT INTO laboratorium(nama_lab, kapasitas, lokasi, status)
        VALUES(?, ?, ?, ?)
    ");

    if (!$stmt) {
        elab_redirect('kelola.php', 'error', 'Gagal menyiapkan data laboratorium');
    }

    mysqli_stmt_bind_param($stmt, 'siss', $nama_lab, $kapasitas, $lokasi, $status);
    mysqli_stmt_execute($stmt);

    elab_log_activity('laboratorium_ditambahkan', ['nama_lab' => $nama_lab]);
    elab_redirect('kelola.php', 'success', 'Laboratorium berhasil ditambahkan');
}

if ($aksi === 'edit') {
    $id_lab = isset($_POST['id_lab']) ? (int) $_POST['id_lab'] : 0;

    if ($id_lab <= 0) {
        elab_redirect('kelola.php', 'error', 'ID laboratorium tidak valid');
    }

    $stmt = mysqli_prepare($conn, "
        UPDATE laboratorium
        SET nama_lab = ?, kapasitas = ?, lokasi = ?, status = ?
        WHERE id_lab = ?
    ");

    if (!$stmt) {
        elab_redirect('kelola.php', 'error', 'Gagal menyiapkan data laboratorium');
    }

    mysqli_stmt_bind_param($stmt, 'sissi', $nama_lab, $kapasitas, $lokasi, $status, $id_lab);
    mysqli_stmt_execute($stmt);

    elab_log_activity('laboratorium_diperbarui', ['id_lab' => $id_lab]);
    elab_redirect('kelola.php', 'success', 'Laboratorium berhasil diubah');
}

if ($aksi === 'hapus') {
    $id_lab = isset($_POST['id_lab']) ? (int) $_POST['id_lab'] : 0;

    if ($id_lab <= 0) {
        elab_redirect('kelola.php', 'error', 'ID laboratorium tidak valid');
    }

    $stmt = mysqli_prepare($conn, "
        DELETE FROM laboratorium
        WHERE id_lab = ?
    ");

    if (!$stmt) {
        elab_redirect('kelola.php', 'error', 'Gagal menyiapkan data laboratorium');
    }

    mysqli_stmt_bind_param($stmt, 'i', $id_lab);
    mysqli_stmt_execute($stmt);

    elab_log_activity('laboratorium_dihapus', ['id_lab' => $id_lab]);
    elab_redirect('kelola.php', 'success', 'Laboratorium berhasil dihapus');
}
?>