<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "statistik";

$conn = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Proses login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Ambil user dari database
    $result = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    $row = mysqli_fetch_assoc($result);

    if ($row && password_verify($password, $row['password'])) {
        $_SESSION['login'] = true;
        $_SESSION['username'] = $row['username'];
        header("Location: ekonomi.php");
        exit;
    } else {
        echo "<p style='color:red;'>Username atau Password salah!</p>";
    }
}
?>

<!-- Form Login -->
<h2>Login</h2>
<form method="post">
  <label>Username:</label><br>
  <input type="text" name="username" required><br><br>
  
  <label>Password:</label><br>
  <input type="password" name="password" required><br><br>
  
  <button type="submit" name="login">Login</button>
</form>
