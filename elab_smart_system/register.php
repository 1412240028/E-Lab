<?php
session_start();

if(isset($_SESSION['role'])){
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

if(isset($_POST['daftar'])){
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $konfirmasi = trim($_POST['konfirmasi']);

    // Cek password cocok
    if($password !== $konfirmasi){
        $error = "Password dan konfirmasi tidak cocok";
    } else {
        // Cek email sudah terdaftar
        $cek = mysqli_prepare($conn,"SELECT * FROM users WHERE email=?");
        mysqli_stmt_bind_param($cek, "s", $email);
        mysqli_stmt_execute($cek);
        $hasil = mysqli_stmt_get_result($cek);

        if(mysqli_num_rows($hasil) > 0){
            $error = "Email sudah terdaftar";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conn,"
                INSERT INTO users(nama, email, password, role)
                VALUES(?, ?, ?, 'mahasiswa')
            ");
            mysqli_stmt_bind_param($stmt, "sss", $nama, $email, $hash);
            mysqli_stmt_execute($stmt);
            $success = "Registrasi berhasil! Silakan login.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrasi E-Lab</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{ background:#efefef; font-family:Arial; }
        .register-box{
            max-width:400px; margin:auto; margin-top:60px;
            background:white; padding:30px;
            border-radius:20px; box-shadow:0 2px 10px rgba(0,0,0,0.1);
        }
        .btn-ungu{ background:#4b2ea7; color:white; }
    </style>
</head>
<body>

<div class="register-box">
    <h3 class="text-center mb-4">Daftar Akun</h3>

    <?php if(isset($error)){ ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php } ?>

    <?php if(isset($success)){ ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php } ?>

    <form method="POST">
        <div class="mb-3">
            <input type="text" name="nama" class="form-control"
                placeholder="Nama Lengkap" required
                value="<?= isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : '' ?>">
        </div>
        <div class="mb-3">
            <input type="email" name="email" class="form-control"
                placeholder="Email" required
                value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control"
                placeholder="Password" required>
        </div>
        <div class="mb-3">
            <input type="password" name="konfirmasi" class="form-control"
                placeholder="Konfirmasi Password" required>
        </div>
        <button type="submit" name="daftar" class="btn btn-ungu w-100">Daftar</button>
    </form>

    <div class="text-center mt-3">
        <a href="login.php" style="color:#4b2ea7;">Sudah punya akun? Login</a>
    </div>
</div>

</body>
</html>