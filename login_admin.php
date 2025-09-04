<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $kelurahan = $_GET['kelurahan'] ?? '';

    $query = "SELECT * FROM admin WHERE username='$username' AND password='$password' AND kelurahan='$kelurahan'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $admin = mysqli_fetch_assoc($result);
        
        $_SESSION['admin'] = $admin['username'];
        $_SESSION['kelurahan'] = $admin['kelurahan'];

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
<body class="login-page"> 
    <div class="container">
    <h2>Login Admin - <?php echo htmlspecialchars($_GET['kelurahan'] ?? ''); ?></h2>

    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <fo method="post">
       <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" name="login">Login</button>
    
    </form>
    <a href="pendidikan.php" class="btn-kembali">⬅ Kembali ke Data Pendidikan</a>

</body>
</html>
