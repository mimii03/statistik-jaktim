<?php
require 'koneksi.php';
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email    = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $confirm  = trim($_POST["confirm"]);

    // Validasi input kosong
    if (empty($username) || empty($email) || empty($password) || empty($confirm)) {
        $error = "Semua field harus diisi!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid!";
    } elseif ($password !== $confirm) {
        $error = "Password dan konfirmasi password tidak sama!";
    } else {
        // Cek apakah username atau email sudah dipakai
        $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' OR email='$email'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Username atau Email sudah digunakan!";
        } else {
            // Enkripsi password
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            // Simpan ke database
            $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed')";
            if (mysqli_query($conn, $query)) {
                header("Location: login.php");
                exit;
            } else {
                $error = "Gagal mendaftar. Coba lagi.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        <?php include 'style.css'; ?>
    </style>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <?php if (!empty($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" value="<?= htmlspecialchars($username ?? '') ?>">
            <input type="text" name="email" placeholder="Email" value="<?= htmlspecialchars($email ?? '') ?>">
            <input type="password" name="password" placeholder="Password">
            <input type="password" name="confirm" placeholder="Konfirmasi Password">
            <button type="submit">Daftar</button>
        </form>
        <p>Sudah punya akun? <a href="login.php" class="link-daftar">Login</a></p>
    </div>
</body>
</html>
