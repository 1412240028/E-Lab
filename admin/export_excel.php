<?php
require_once "_guard.php";
require_once "../koneksi.php";

$cari = isset($_GET['cari']) ? trim($_GET['cari']) : '';
$filter_status = isset($_GET['status']) ? trim($_GET['status']) : '';
$filter_tanggal = isset($_GET['tanggal']) ? trim($_GET['tanggal']) : '';

$allowedStatus = ['menunggu', 'disetujui', 'ditolak'];

$where = "WHERE 1=1";
$params = [];
$types = "";

if ($cari !== '') {
    $where .= " AND (users.nama LIKE ? OR laboratorium.nama_lab LIKE ?)";
    $params[] = "%$cari%";
    $params[] = "%$cari%";
    $types .= "ss";
}

if ($filter_status !== '') {
    if (!in_array($filter_status, $allowedStatus, true)) {
        $filter_status = '';
    } else {
        $where .= " AND peminjaman.status = ?";
        $params[] = $filter_status;
        $types .= "s";
    }
}

if ($filter_tanggal !== '') {
    $where .= " AND peminjaman.tanggal_pinjam = ?";
    $params[] = $filter_tanggal;
    $types .= "s";
}

$stmt = mysqli_prepare($conn, "
    SELECT peminjaman.*, users.nama, laboratorium.nama_lab
    FROM peminjaman
    JOIN users ON peminjaman.id_user = users.id_user
    JOIN laboratorium ON peminjaman.id_lab = laboratorium.id_lab
    $where
    ORDER BY peminjaman.tanggal_pinjam DESC
");

if (!$stmt) {
    die("Gagal menyiapkan export Excel.");
}

if (count($params) > 0) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

mysqli_stmt_execute($stmt);
$data = mysqli_stmt_get_result($stmt);

header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header('Content-Disposition: attachment; filename="laporan_peminjaman.xls"');
header('Pragma: no-cache');
header('Expires: 0');
?>

<table border="1">
    <tr>
        <th>No</th>
        <th>Nama Mahasiswa</th>
        <th>Laboratorium</th>
        <th>Tanggal</th>
        <th>Jam Mulai</th>
        <th>Jam Selesai</th>
        <th>Keperluan</th>
        <th>Status</th>
    </tr>

    <?php $no = 1; ?>
    <?php while ($d = mysqli_fetch_assoc($data)) { ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($d['nama']) ?></td>
            <td><?= htmlspecialchars($d['nama_lab']) ?></td>
            <td><?= htmlspecialchars($d['tanggal_pinjam']) ?></td>
            <td><?= htmlspecialchars($d['jam_mulai']) ?></td>
            <td><?= htmlspecialchars($d['jam_selesai']) ?></td>
            <td><?= htmlspecialchars($d['keperluan']) ?></td>
            <td><?= htmlspecialchars(ucfirst($d['status'])) ?></td>
        </tr>
    <?php } ?>
</table>