<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $plainPassword = trim($_POST['password']);

    // Ambil user berdasarkan email saja, lalu verifikasi password dengan password_verify.
    $stmt = mysqli_prepare($conn, "
        SELECT id_user, nama, email, role, password
        FROM users
        WHERE email=?
        LIMIT 1
    ");

    if (!$stmt) {
        $error = "Gagal menyiapkan query: " . mysqli_error($conn);
    } else {
        if (!mysqli_stmt_bind_param($stmt, "s", $email)) {
            $error = "Gagal bind param: " . mysqli_stmt_error($stmt);
        } else {
            if (!mysqli_stmt_execute($stmt)) {
                $error = "Gagal execute query: " . mysqli_stmt_error($stmt);
            } else {
                $result = mysqli_stmt_get_result($stmt);
                $data = mysqli_fetch_assoc($result);

                if ($data) {
                    $storedHash = $data['password'];
                    $isValid = password_verify($plainPassword, $storedHash);

                    // Kompatibilitas user lama (MD5)
                    if (!$isValid) {
                        $isValid = hash_equals(md5($plainPassword), $storedHash);
                        if ($isValid) {
                            // Migrasi otomatis: re-hash MD5 ke password_hash
                            $newHash = password_hash($plainPassword, PASSWORD_DEFAULT);
                            $upd = mysqli_prepare($conn, "UPDATE users SET password=? WHERE id_user=?");
                            if ($upd) {
                                mysqli_stmt_bind_param($upd, "si", $newHash, $data['id_user']);
                                mysqli_stmt_execute($upd);
                            }
                        }
                    }

                    if ($isValid) {
                            // Session fixation protection
                            session_regenerate_id(true);

                            $_SESSION['id_user'] = $data['id_user'];
                            $_SESSION['nama'] = $data['nama'];
                            $_SESSION['role'] = $data['role'];

                            if ($data['role'] === 'admin') {
                                header("Location: admin/dashboard.php");
                                exit;
                            }
                            if ($data['role'] === 'mahasiswa') {
                                header("Location: mahasiswa/dashboard.php");
                                exit;
                            }

                            // Role selain admin/mahasiswa
                            $error = "Role tidak valid. Hubungi administrator.";
                        } else {
                            $error = "Email atau password salah";
                        }
                } else {
                    $error = "Email atau password salah";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login E-Lab</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #efefef;
            font-family: Arial;
        }

        .login-box {
            max-width: 400px;
            margin: auto;
            margin-top: 80px;
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-login {
            background: #4b2ea7;
            color: white;
        }
    </style>
</head>

<body>

    <div class="login-box">
        <h3 class="text-center mb-4">E-Lab Smart System</h3>

        <?php if (isset($error)) { ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php } ?>

        <form method="POST">
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Masukkan Email" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Masukkan Password" required>
            </div>
            <button type="submit" name="login" class="btn btn-login w-100">Login</button>
            <div class="text-center mt-3">
                <a href="register.php" style="color:#4b2ea7;">Belum punya akun? Daftar</a>
            </div>
        </form>
    </div>

</body>

</html>