<?php
session_start();
include '../koneksi.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'mahasiswa'){
    header("Location: ../login.php");
    exit;
}

$id_peminjaman = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($id_peminjaman == 0){
    header("Location: riwayat.php");
    exit;
}

// Ambil data peminjaman
$stmt = mysqli_prepare($conn,"
    SELECT peminjaman.*, users.nama, users.email, laboratorium.nama_lab, laboratorium.lokasi, laboratorium.kapasitas
    FROM peminjaman
    JOIN users ON peminjaman.id_user = users.id_user
    JOIN laboratorium ON peminjaman.id_lab = laboratorium.id_lab
    WHERE peminjaman.id_peminjaman = ?
    AND peminjaman.id_user = ?
    AND peminjaman.status = 'disetujui'
");
mysqli_stmt_bind_param($stmt, "ii", $id_peminjaman, $_SESSION['id_user']);
mysqli_stmt_execute($stmt);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if(!$data){
    header("Location: riwayat.php");
    exit;
}

require_once '../vendor/autoload.php';

$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8');
$pdf->SetCreator('E-Lab Smart System');
$pdf->SetTitle('Surat Peminjaman Laboratorium');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();
$pdf->SetMargins(20, 20, 20);

// Header surat
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'SURAT PEMINJAMAN LABORATORIUM', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(0, 8, 'E-Lab Smart System', 0, 1, 'C');
$pdf->Ln(5);

// Garis
$pdf->Line(20, $pdf->GetY(), 190, $pdf->GetY());
$pdf->Ln(8);

// Nomor surat
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 7, 'No. Surat : ELAB/' . date('Y') . '/' . str_pad($data['id_peminjaman'], 4, '0', STR_PAD_LEFT), 0, 1, 'L');
$pdf->Cell(0, 7, 'Tanggal   : ' . date('d F Y'), 0, 1, 'L');
$pdf->Ln(5);

// Isi surat
$pdf->SetFont('helvetica', '', 11);
$pdf->MultiCell(0, 7, 'Yang bertanda tangan di bawah ini, menerangkan bahwa:', 0, 'L');
$pdf->Ln(3);

// Data peminjam
$pdf->SetFont('helvetica', '', 10);
$col1 = 50;
$pdf->Cell($col1, 7, 'Nama', 0, 0, 'L');
$pdf->Cell(5, 7, ':', 0, 0, 'L');
$pdf->Cell(0, 7, $data['nama'], 0, 1, 'L');

$pdf->Cell($col1, 7, 'Email', 0, 0, 'L');
$pdf->Cell(5, 7, ':', 0, 0, 'L');
$pdf->Cell(0, 7, $data['email'], 0, 1, 'L');

$pdf->Ln(3);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(0, 7, 'Telah disetujui untuk meminjam laboratorium dengan detail:', 0, 1, 'L');
$pdf->Ln(3);

$pdf->SetFont('helvetica', '', 10);
$pdf->Cell($col1, 7, 'Nama Laboratorium', 0, 0, 'L');
$pdf->Cell(5, 7, ':', 0, 0, 'L');
$pdf->Cell(0, 7, $data['nama_lab'], 0, 1, 'L');

$pdf->Cell($col1, 7, 'Lokasi', 0, 0, 'L');
$pdf->Cell(5, 7, ':', 0, 0, 'L');
$pdf->Cell(0, 7, $data['lokasi'], 0, 1, 'L');

$pdf->Cell($col1, 7, 'Kapasitas', 0, 0, 'L');
$pdf->Cell(5, 7, ':', 0, 0, 'L');
$pdf->Cell(0, 7, $data['kapasitas'] . ' kursi', 0, 1, 'L');

$pdf->Cell($col1, 7, 'Tanggal Pinjam', 0, 0, 'L');
$pdf->Cell(5, 7, ':', 0, 0, 'L');
$pdf->Cell(0, 7, $data['tanggal_pinjam'], 0, 1, 'L');

$pdf->Cell($col1, 7, 'Jam', 0, 0, 'L');
$pdf->Cell(5, 7, ':', 0, 0, 'L');
$pdf->Cell(0, 7, $data['jam_mulai'] . ' - ' . $data['jam_selesai'] . ' WIB', 0, 1, 'L');

$pdf->Cell($col1, 7, 'Keperluan', 0, 0, 'L');
$pdf->Cell(5, 7, ':', 0, 0, 'L');
$pdf->Cell(0, 7, $data['keperluan'], 0, 1, 'L');

$pdf->Ln(10);

// Garis
$pdf->Line(20, $pdf->GetY(), 190, $pdf->GetY());
$pdf->Ln(8);

$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(0, 7, 'Demikian surat ini dibuat untuk digunakan sebagaimana mestinya.', 0, 'L');
$pdf->Ln(15);

// Tanda tangan
$pdf->Cell(0, 7, 'Admin Laboratorium', 0, 1, 'R');
$pdf->Ln(20);
$pdf->Cell(0, 7, '(................................................)', 0, 1, 'R');

$pdf->Output('surat_peminjaman_' . $data['id_peminjaman'] . '.pdf', 'D');
?>