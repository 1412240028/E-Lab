<?php
require_once "_guard.php";
require_once "../koneksi.php";
require_once "../includes/functions.php";

$aksi = isset($_POST['aksi']) ? trim($_POST['aksi']) : '';
$allowedAksi = ['tambah', 'hapus'];

if (!in_array($aksi, $allowedAksi, true)) {
    elab_redirect('kelola_user.php', 'error', 'Aksi tidak valid');
}

$allowedRoles = ['admin', 'mahasiswa', 'dosen'];

if ($aksi === 'tambah') {
    $nama = elab_sanitize_text($_POST['nama'] ?? '');
    $email = elab_sanitize_text($_POST['email'] ?? '');
    $plainPassword = elab_sanitize_text($_POST['password'] ?? '');
    $role = elab_sanitize_text($_POST['role'] ?? '');
    $nim = elab_sanitize_text($_POST['nim'] ?? '');
    $nip = elab_sanitize_text($_POST['nip'] ?? '');

    if ($nama === '' || $email === '' || $plainPassword === '' || $role === '') {
        elab_redirect('kelola_user.php', 'error', 'Semua field wajib diisi');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        elab_redirect('kelola_user.php', 'error', 'Format email tidak valid');
    }

    if (strlen($plainPassword) < 6) {
        elab_redirect('kelola_user.php', 'error', 'Password minimal 6 karakter');
    }

    if (!in_array($role, $allowedRoles, true)) {
        elab_redirect('kelola_user.php', 'error', 'Role tidak valid');
    }

    if ($role === 'mahasiswa' && $nim === '') {
        elab_redirect('kelola_user.php', 'error', 'NIM wajib diisi untuk mahasiswa');
    }

    if ($role === 'dosen' && $nip === '') {
        elab_redirect('kelola_user.php', 'error', 'NIP wajib diisi untuk dosen');
    }

    $cek = mysqli_prepare($conn, "
        SELECT id_user FROM users WHERE email = ? LIMIT 1
    ");

    if (!$cek) {
        elab_redirect('kelola_user.php', 'error', 'Gagal menyiapkan validasi email');
    }

    mysqli_stmt_bind_param($cek, "s", $email);
    mysqli_stmt_execute($cek);
    $hasil = mysqli_stmt_get_result($cek);

    if (mysqli_num_rows($hasil) > 0) {
        elab_redirect('kelola_user.php', 'error', 'Email sudah terdaftar');
    }

    if (!empty($nim)) {
        $cekNim = mysqli_prepare($conn, "
            SELECT id_user FROM users WHERE nim = ? LIMIT 1
        ");
        mysqli_stmt_bind_param($cekNim, "s", $nim);
        mysqli_stmt_execute($cekNim);
        if (mysqli_num_rows(mysqli_stmt_get_result($cekNim)) > 0) {
            elab_redirect('kelola_user.php', 'error', 'NIM sudah terdaftar');
        }
    }

    if (!empty($nip)) {
        $cekNip = mysqli_prepare($conn, "
            SELECT id_user FROM users WHERE nip = ? LIMIT 1
        ");
        mysqli_stmt_bind_param($cekNip, "s", $nip);
        mysqli_stmt_execute($cekNip);
        if (mysqli_num_rows(mysqli_stmt_get_result($cekNip)) > 0) {
            elab_redirect('kelola_user.php', 'error', 'NIP sudah terdaftar');
        }
    }

    $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

    $stmt = mysqli_prepare($conn, "
        INSERT INTO users(nama, nim, nip, email, password, role)
        VALUES(?, ?, ?, ?, ?, ?)
    ");

    if (!$stmt) {
        elab_redirect('kelola_user.php', 'error', 'Gagal menyiapkan data pengguna');
    }

    mysqli_stmt_bind_param($stmt, "ssssss", $nama, $nim, $nip, $email, $hashedPassword, $role);

    if (!mysqli_stmt_execute($stmt)) {
        elab_redirect('kelola_user.php', 'error', 'Gagal menambahkan pengguna');
    }

    elab_log_activity('user_ditambahkan', ['nama' => $nama, 'role' => $role]);
    elab_redirect('kelola_user.php', 'success', 'Pengguna berhasil ditambahkan');
}

if ($aksi === 'hapus') {
    $id_user = isset($_POST['id_user']) ? (int) $_POST['id_user'] : 0;

    if ($id_user <= 0) {
        elab_redirect('kelola_user.php', 'error', 'User tidak valid');
    }

    if ($id_user === (int) $_SESSION['id_user']) {
        elab_redirect('kelola_user.php', 'error', 'Tidak bisa hapus akun sendiri');
    }

    $stmt = mysqli_prepare($conn, "
        DELETE FROM users WHERE id_user = ?
    ");

    if (!$stmt) {
        elab_redirect('kelola_user.php', 'error', 'Gagal menyiapkan hapus pengguna');
    }

    mysqli_stmt_bind_param($stmt, "i", $id_user);

    if (!mysqli_stmt_execute($stmt)) {
        elab_redirect('kelola_user.php', 'error', 'Gagal menghapus pengguna');
    }

    elab_log_activity('user_dihapus', ['id_user' => $id_user]);
    elab_redirect('kelola_user.php', 'success', 'Pengguna berhasil dihapus');
}
?>