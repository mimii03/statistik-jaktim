<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $kelurahan = $_GET['kelurahan'] ?? '';

    // Cek user di tabel admin
    $query = "SELECT * FROM admin WHERE username='$username' AND password='$password' AND kelurahan='$kelurahan'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $admin = mysqli_fetch_assoc($result);
        
        // simpan session
        $_SESSION['admin'] = $admin['username'];
        $_SESSION['kelurahan'] = $admin['kelurahan'];

        // redirect ke tambah data
        header("Location: tambahdata.php?kelurahan=" . urlencode($admin['kelurahan']));
        exit;
    } else {
        $error = "Username atau password salah untuk kelurahan $kelurahan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
</head>
<body>
    <h2>Login Admin - <?php echo htmlspecialchars($_GET['kelurahan'] ?? ''); ?></h2>

    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="post">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>
    <a href="pendidikan.php" class="btn-kembali">⬅ Kembali ke Data Pendidikan</a>

</body>
</html>
