<?php
session_start();
include '../koneksi.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit;
}

require_once '../vendor/autoload.php';

$data = mysqli_query($conn,"
    SELECT peminjaman.*, users.nama, laboratorium.nama_lab
    FROM peminjaman
    JOIN users ON peminjaman.id_user = users.id_user
    JOIN laboratorium ON peminjaman.id_lab = laboratorium.id_lab
    ORDER BY peminjaman.tanggal_pinjam DESC
");

$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8');
$pdf->SetCreator('E-Lab Smart System');
$pdf->SetTitle('Laporan Peminjaman');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Laporan Peminjaman Laboratorium', 0, 1, 'C');
$pdf->Ln(5);

// Header tabel
$pdf->SetFont('helvetica', 'B', 9);
$pdf->SetFillColor(75, 46, 167);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(10, 8, 'No', 1, 0, 'C', true);
$pdf->Cell(50, 8, 'Nama Mahasiswa', 1, 0, 'C', true);
$pdf->Cell(40, 8, 'Laboratorium', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Tanggal', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Jam Mulai', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Jam Selesai', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Status', 1, 1, 'C', true);

// Isi tabel
$pdf->SetFont('helvetica', '', 9);
$pdf->SetTextColor(0, 0, 0);
$no = 1;

while($d = mysqli_fetch_assoc($data)){
    $pdf->SetFillColor($no % 2 == 0 ? 240 : 255, $no % 2 == 0 ? 240 : 255, $no % 2 == 0 ? 240 : 255);
    $pdf->Cell(10, 7, $no++, 1, 0, 'C', true);
    $pdf->Cell(50, 7, $d['nama'], 1, 0, 'L', true);
    $pdf->Cell(40, 7, $d['nama_lab'], 1, 0, 'L', true);
    $pdf->Cell(30, 7, $d['tanggal_pinjam'], 1, 0, 'C', true);
    $pdf->Cell(25, 7, $d['jam_mulai'], 1, 0, 'C', true);
    $pdf->Cell(25, 7, $d['jam_selesai'], 1, 0, 'C', true);
    $pdf->Cell(30, 7, ucfirst($d['status']), 1, 1, 'C', true);
}

$pdf->Output('laporan_peminjaman.pdf', 'D');
?>