<nav class="bottom-nav-modern bottom-nav-admin">
    <a href="dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '' ?>">
        Beranda
    </a>

    <a href="jadwal.php" class="<?= basename($_SERVER['PHP_SELF']) === 'jadwal.php' ? 'active' : '' ?>">
        Jadwal
    </a>

    <a href="laporan.php" class="<?= basename($_SERVER['PHP_SELF']) === 'laporan.php' ? 'active' : '' ?>">
        Laporan
    </a>

    <a href="kelola.php" class="<?= basename($_SERVER['PHP_SELF']) === 'kelola.php' ? 'active' : '' ?>">
        Kelola
    </a>

    <a href="kelola_user.php" class="<?= basename($_SERVER['PHP_SELF']) === 'kelola_user.php' ? 'active' : '' ?>">
        Pengguna
    </a>

    <a href="../logout.php">
        Logout
    </a>
</nav>