<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'mahasiswa') {
    header("Location: ../login.php");
    exit;
}

// Ambil data user
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id_user=?");
mysqli_stmt_bind_param($stmt, "i", $_SESSION['id_user']);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

$inisial = strtoupper(substr($user['nama'], 0, 2));
?>

<!DOCTYPE html>
<html>

<head>
    <title>Profil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #efefef;
            font-family: Arial;
        }

        .mobile {
            max-width: 430px;
            margin: auto;
            background: white;
            min-height: 100vh;
        }

        .header {
            background: #4b2ea7;
            color: white;
            padding: 25px;
            border-bottom-left-radius: 20px;
            border-bottom-right-radius: 20px;
            text-align: center;
        }

        .avatar {
            width: 80px;
            height: 80px;
            background: white;
            color: #4b2ea7;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            font-size: 28px;
            margin: auto;
            margin-bottom: 10px;
        }

        .card-box {
            background: white;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .btn-ungu {
            background: #4b2ea7;
            color: white;
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            width: 100%;
            max-width: 430px;
            background: white;
            display: flex;
            justify-content: space-around;
            padding: 15px 0;
            border-top: 1px solid #eee;
        }

        .nav-item {
            color: #999;
            font-size: 14px;
            text-align: center;
            text-decoration: none;
        }

        .active-nav {
            color: #4b2ea7;
            font-weight: bold;
        }

        .p-4 {
            padding-bottom: 80px !important;
        }
    </style>
</head>

<body>

    <div class="mobile">

        <div class="header">
            <div class="avatar"><?= $inisial ?></div>
            <h4><?= htmlspecialchars($user['nama']) ?></h4>
            <p class="mb-0" style="font-size:13px; color:#ddd;">
                <?= htmlspecialchars($user['email']) ?> • <?= ucfirst($user['role']) ?>
            </p>
        </div>

        <div class="p-4">

            <div class="card-box">
                <h5>Edit Profil</h5>
                <form method="POST" action="profil_proses.php">

                    <div class="mb-3">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control"
                            value="<?= htmlspecialchars($user['nama']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label>Password Lama</label>
                        <input type="password" name="password_lama" class="form-control" placeholder="Wajib diisi"
                            required>
                    </div>

                    <div class="mb-3">
                        <label>Password Baru <span class="text-secondary" style="font-size:12px;">(kosongkan jika tidak
                                ingin ganti)</span></label>
                        <input type="password" name="password_baru" class="form-control" placeholder="Opsional">
                    </div>

                    <button class="btn btn-ungu w-100">Simpan Perubahan</button>

                </form>
            </div>

        </div>

        <div class="bottom-nav">
            <a href="dashboard.php" class="nav-item">Beranda</a>
            <a href="riwayat.php" class="nav-item">Riwayat</a>
            <a href="notifikasi.php" class="nav-item">Notifikasi</a>
            <a href="profil.php" class="nav-item active-nav">Profil</a>
            <a href="../logout.php" class="nav-item">Logout</a>
        </div>

    </div>

</body>

</html>