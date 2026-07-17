<?php
require_once "_guard.php";
require_once "../koneksi.php";
require_once dirname(__DIR__) . '/includes/functions.php';

$id_user = (int) $_SESSION['id_user'];
$id_lab = isset($_POST['id_lab']) ? (int) $_POST['id_lab'] : 0;
$tanggal_pinjam = elab_sanitize_text($_POST['tanggal_pinjam'] ?? '');
$jam_mulai = elab_sanitize_text($_POST['jam_mulai'] ?? '');
$jam_selesai = elab_sanitize_text($_POST['jam_selesai'] ?? '');
$keperluan = elab_sanitize_text($_POST['keperluan'] ?? '');

if ($id_lab == 0 || $tanggal_pinjam === '' || $jam_mulai === '' || $jam_selesai === '' || $keperluan === '') {
    elab_redirect('dashboard.php', 'error', 'Semua field harus diisi');
}

if ($jam_mulai >= $jam_selesai) {
    elab_redirect('dashboard.php', 'error', 'Jam mulai harus lebih awal dari jam selesai');
}

if (elab_has_schedule_conflict($conn, $id_lab, $tanggal_pinjam, $jam_mulai, $jam_selesai)) {
    elab_redirect('dashboard.php', 'error', 'Jadwal bentrok! Lab sudah digunakan pada jam tersebut');
}

$stmt = mysqli_prepare($conn, "
    INSERT INTO peminjaman(id_user, id_lab, tanggal_pinjam, jam_mulai, jam_selesai, keperluan, status)
    VALUES(?, ?, ?, ?, ?, ?, 'menunggu')
");

if (!$stmt) {
    elab_redirect('dashboard.php', 'error', 'Gagal menyiapkan data peminjaman');
}

mysqli_stmt_bind_param($stmt, 'iissss', $id_user, $id_lab, $tanggal_pinjam, $jam_mulai, $jam_selesai, $keperluan);

if (!mysqli_stmt_execute($stmt)) {
    elab_redirect('dashboard.php', 'error', 'Peminjaman gagal diajukan');
}

elab_log_activity('peminjaman_diajukan', ['id_user' => $id_user, 'id_lab' => $id_lab, 'tanggal' => $tanggal_pinjam]);
elab_redirect('dashboard.php', 'success', 'Peminjaman berhasil diajukan');
?>