<?php
require_once "_guard.php";
require_once "../koneksi.php";
require_once "../vendor/autoload.php";

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
    die("Gagal menyiapkan export PDF.");
}

if (count($params) > 0) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

mysqli_stmt_execute($stmt);
$data = mysqli_stmt_get_result($stmt);

$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8');
$pdf->SetCreator('E-Lab Smart System');
$pdf->SetTitle('Laporan Peminjaman');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();

$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Laporan Peminjaman Laboratorium', 0, 1, 'C');
$pdf->Ln(5);

$pdf->SetFont('helvetica', 'B', 9);
$pdf->SetFillColor(75, 46, 167);
$pdf->SetTextColor(255, 255, 255);

$pdf->Cell(10, 8, 'No', 1, 0, 'C', true);
$pdf->Cell(50, 8, 'Nama Mahasiswa', 1, 0, 'C', true);
$pdf->Cell(40, 8, 'Laboratorium', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Tanggal', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Jam Mulai', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Jam Selesai', 1, 0, 'C', true);
$pdf->Cell(55, 8, 'Keperluan', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Status', 1, 1, 'C', true);

$pdf->SetFont('helvetica', '', 8);
$pdf->SetTextColor(0, 0, 0);

$no = 1;

while ($d = mysqli_fetch_assoc($data)) {
    $fill = $no % 2 === 0;
    $fillColor = $fill ? 240 : 255;

    $pdf->SetFillColor($fillColor, $fillColor, $fillColor);

    $pdf->Cell(10, 7, $no++, 1, 0, 'C', true);
    $pdf->Cell(50, 7, $d['nama'], 1, 0, 'L', true);
    $pdf->Cell(40, 7, $d['nama_lab'], 1, 0, 'L', true);
    $pdf->Cell(30, 7, $d['tanggal_pinjam'], 1, 0, 'C', true);
    $pdf->Cell(25, 7, $d['jam_mulai'], 1, 0, 'C', true);
    $pdf->Cell(25, 7, $d['jam_selesai'], 1, 0, 'C', true);
    $pdf->Cell(55, 7, substr($d['keperluan'], 0, 45), 1, 0, 'L', true);
    $pdf->Cell(30, 7, ucfirst($d['status']), 1, 1, 'C', true);
}

$pdf->Output('laporan_peminjaman.pdf', 'D');
exit;
?>