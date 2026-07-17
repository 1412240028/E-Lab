<nav class="bottom-nav-modern bottom-nav-student">
    <a href="dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '' ?>">
        Beranda
    </a>

    <a href="riwayat.php" class="<?= basename($_SERVER['PHP_SELF']) === 'riwayat.php' ? 'active' : '' ?>">
        Riwayat
    </a>

    <a href="notifikasi.php" class="<?= basename($_SERVER['PHP_SELF']) === 'notifikasi.php' ? 'active' : '' ?>">
        Notifikasi
    </a>

    <a href="profil.php" class="<?= basename($_SERVER['PHP_SELF']) === 'profil.php' ? 'active' : '' ?>">
        Profil
    </a>

    <a href="../logout.php">
        Logout
    </a>
</nav>