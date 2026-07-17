<?php
require_once "_guard.php";
require_once "../koneksi.php";

$nama   = $_SESSION['nama'] ?? 'Dosen';
$idUser = (int) $_SESSION['id_user'];

// Statistik
$queryJadwal = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM peminjaman WHERE status = 'disetujui'");
mysqli_stmt_execute($queryJadwal);
$totalJadwal = mysqli_fetch_assoc(mysqli_stmt_get_result($queryJadwal))['total'] ?? 0;

$queryMenunggu = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM peminjaman WHERE status = 'menunggu'");
mysqli_stmt_execute($queryMenunggu);
$totalMenunggu = mysqli_fetch_assoc(mysqli_stmt_get_result($queryMenunggu))['total'] ?? 0;

$queryDisetujui = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM peminjaman WHERE status = 'disetujui'");
mysqli_stmt_execute($queryDisetujui);
$totalDisetujui = mysqli_fetch_assoc(mysqli_stmt_get_result($queryDisetujui))['total'] ?? 0;

// Lab tersedia untuk form
$lab = mysqli_query($conn, "SELECT * FROM laboratorium WHERE status='tersedia' ORDER BY nama_lab ASC");

// Riwayat pengajuan dosen ini
$stmtRiwayat = mysqli_prepare($conn, "
    SELECT peminjaman.*, laboratorium.nama_lab
    FROM peminjaman
    JOIN laboratorium ON peminjaman.id_lab = laboratorium.id_lab
    WHERE peminjaman.id_user = ?
    ORDER BY peminjaman.tanggal_pinjam DESC
    LIMIT 5
");
mysqli_stmt_bind_param($stmtRiwayat, "i", $idUser);
mysqli_stmt_execute($stmtRiwayat);
$riwayat = mysqli_stmt_get_result($stmtRiwayat);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Dosen - E-Lab Smart System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/main.css">
</head>

<body class="dosen-page">
<div class="app-shell">
    <div class="app-container dosen-container">

        <header class="app-header lecturer">
            <div class="app-header-content d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="app-title">Dashboard Dosen</h1>
                    <p class="app-subtitle">
                        <?= htmlspecialchars($nama) ?> • Panel akademik laboratorium
                    </p>
                    <a href="../logout.php" class="app-logout">Keluar dari sistem</a>
                </div>
                <div class="profile-circle lecturer">
                    <?= strtoupper(substr($nama, 0, 1)) ?>
                </div>
            </div>
        </header>

        <main class="app-body dosen-body">

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success mb-3">
                    <?= htmlspecialchars($_GET['success']) ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger mb-3">
                    <?= htmlspecialchars($_GET['error']) ?>
                </div>
            <?php endif; ?>

            <!-- Hero -->
            <section class="lecturer-hero-card">
                <h2>Kontrol Akademik Laboratorium</h2>
                <p>Kelola aktivitas akademik laboratorium melalui jadwal penggunaan dan verifikasi peminjaman.</p>
            </section>

            <!-- Statistik -->
            <section class="dosen-stat-grid">
                <div class="dosen-stat-card">
                    <span>Total Jadwal</span>
                    <strong><?= $totalJadwal ?></strong>
                </div>
                <div class="dosen-stat-card">
                    <span>Menunggu Verifikasi</span>
                    <strong><?= $totalMenunggu ?></strong>
                </div>
                <div class="dosen-stat-card">
                    <span>Disetujui</span>
                    <strong><?= $totalDisetujui ?></strong>
                </div>
            </section>

            <!-- Layout 2 kolom: Form + Riwayat -->
            <div class="student-layout mt-4">

                <!-- Form Peminjaman -->
                <div>
                    <div class="lecturer-hero-card">
                        <h2>Ajukan Peminjaman Lab</h2>
                        <p>Pilih laboratorium, tanggal, jam, dan keperluan praktikum.</p>
                    </div>

                    <div class="loan-form-panel">
                        <h2 class="panel-title">Form Peminjaman</h2>
                        <p class="panel-desc">Isi data peminjaman dengan lengkap untuk diproses admin.</p>

                        <form method="POST" action="simpan.php">

                            <div class="input-group-modern">
                                <label>Laboratorium</label>
                                <select name="id_lab" class="form-select" required>
                                    <option value="">Pilih laboratorium</option>
                                    <?php while ($d = mysqli_fetch_assoc($lab)): ?>
                                        <option value="<?= htmlspecialchars($d['id_lab']) ?>">
                                            <?= htmlspecialchars($d['nama_lab']) ?> •
                                            <?= htmlspecialchars($d['kapasitas']) ?> kursi
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="input-group-modern">
                                <label>Tanggal Pinjam</label>
                                <input type="date" name="tanggal_pinjam" class="form-control" required>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="input-group-modern">
                                        <label>Jam Mulai</label>
                                        <input type="time" name="jam_mulai" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input-group-modern">
                                        <label>Jam Selesai</label>
                                        <input type="time" name="jam_selesai" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="input-group-modern">
                                <label>Keperluan</label>
                                <textarea name="keperluan" class="form-control" rows="4"
                                    placeholder="Contoh: Praktikum jaringan komputer" required></textarea>
                            </div>

                            <button class="btn student-cta w-100">
                                + Ajukan Peminjaman
                            </button>

                        </form>
                    </div>
                </div>

                <!-- Riwayat Pengajuan -->
                <div>
                    <div class="section-label mt-0">Status Pengajuan Terbaru</div>

                    <?php if (mysqli_num_rows($riwayat) == 0): ?>
                        <div class="empty-state">
                            Belum ada riwayat pengajuan. Ajukan peminjaman pertama dari form di samping.
                        </div>
                    <?php endif; ?>

                    <div class="student-history-list">
                        <?php while ($r = mysqli_fetch_assoc($riwayat)): ?>
                            <div class="student-history-card">
                                <div class="student-history-top">
                                    <div>
                                        <h3 class="history-lab-name">
                                            <?= htmlspecialchars($r['nama_lab']) ?>
                                        </h3>
                                        <p class="history-meta">
                                            📅 <?= htmlspecialchars($r['tanggal_pinjam']) ?><br>
                                            🕐 <?= htmlspecialchars($r['jam_mulai']) ?> -
                                                <?= htmlspecialchars($r['jam_selesai']) ?>
                                        </p>
                                    </div>
                                    <span class="badge-loan <?= htmlspecialchars($r['status']) ?>">
                                        <?= htmlspecialchars(ucfirst($r['status'])) ?>
                                    </span>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>

                    <!-- Akses Cepat -->
                    <div class="section-label">Akses Cepat</div>
                    <div class="lecturer-quick-panel">
                        <div class="lecturer-action-row">
                            <a href="jadwal.php" class="btn-elab btn-primary-elab">Lihat Jadwal</a>
                            <a href="verifikasi.php" class="btn-elab btn-purple-elab">Verifikasi</a>
                        </div>
                    </div>
                </div>

            </div>

        </main>

        <?php require_once "_nav.php"; ?>

    </div>
</div>
</body>
</html>