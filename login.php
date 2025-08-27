<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$db   = "statistik";

$conn = mysqli_connect($host, $user, $pass, $db);

$error = '';

// simpan query redirect kalau ada
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    $row = mysqli_fetch_assoc($result);

    if ($row && password_verify($password, $row['password'])) {
        $_SESSION['login'] = true;
        $_SESSION['username'] = $row['username'];

        // ambil dari POST hidden input
        $redirect = isset($_POST['redirect']) ? $_POST['redirect'] : 'index.php';
        header("Location: $redirect");
        exit;
    } else {
        $error = "Username atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="login-page"> 
  <div class="container">
    <h2>Login</h2>
    <?php if ($error) echo "<div class='message'>$error</div>"; ?>
    <form method="post">
      <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" name="login">Login</button>
    </form>
    <p>Belum punya akun? <a href="register.php" class="link-daftar">Daftar</a></p>
  </div>
</body>
</html>
