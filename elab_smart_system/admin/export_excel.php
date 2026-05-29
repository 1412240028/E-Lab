<?php
session_start();
include '../koneksi.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit;
}

$data = mysqli_query($conn,"
    SELECT peminjaman.*, users.nama, laboratorium.nama_lab
    FROM peminjaman
    JOIN users ON peminjaman.id_user = users.id_user
    JOIN laboratorium ON peminjaman.id_lab = laboratorium.id_lab
    ORDER BY peminjaman.tanggal_pinjam DESC
");

// Header untuk download Excel
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="laporan_peminjaman.xls"');
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
    <?php $no = 1; while($d = mysqli_fetch_assoc($data)){ ?>
    <tr>
        <td><?= $no++ ?></td>
        <td><?= htmlspecialchars($d['nama']) ?></td>
        <td><?= htmlspecialchars($d['nama_lab']) ?></td>
        <td><?= $d['tanggal_pinjam'] ?></td>
        <td><?= $d['jam_mulai'] ?></td>
        <td><?= $d['jam_selesai'] ?></td>
        <td><?= htmlspecialchars($d['keperluan']) ?></td>
        <td><?= ucfirst($d['status']) ?></td>
    </tr>
    <?php } ?>
</table>