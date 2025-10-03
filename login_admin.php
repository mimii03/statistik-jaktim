<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "koneksi.php";

$kelurahan = $_GET['kelurahan'] ?? '';
$redirect  = $_GET['redirect'] ?? "tambahdata.php?kelurahan=" . urlencode($kelurahan);
$error     = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $redirect = $_POST['redirect'] ?? $redirect;

    if ($username === '' || $password === '') {
        $error = "Username & password wajib diisi.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ? AND kelurahan = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param("ss", $username, $kelurahan);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $admin = $result->fetch_assoc();

                if (!empty($admin['password']) && password_verify($password, $admin['password'])) {
                    $_SESSION['admin'] = $admin['username'];
                    $_SESSION['kelurahan'] = $admin['kelurahan'];
                    header("Location: " . $redirect);
                    exit;
                } elseif ($admin['password'] === $password) {
                    $_SESSION['admin'] = $admin['username'];
                    $_SESSION['kelurahan'] = $admin['kelurahan'];
                    header("Location: " . $redirect);
                    exit;
                } else {
                    $error = "Password salah untuk kelurahan " . htmlspecialchars($kelurahan) . "!";
                }
            } else {
                $error = "Username tidak ditemukan untuk kelurahan " . htmlspecialchars($kelurahan) . "!";
            }

            $stmt->close();
        } else {
            $error = "Gagal menyiapkan query: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Admin</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="login-page">
  <div class="container">
    <h2>Login Admin - <?php echo htmlspecialchars($kelurahan); ?></h2>

    <?php if (!empty($error)): ?>
      <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="post">
      <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>">
      <input type="text" name="username" placeholder="Username" autocomplete="username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" name="login" value="1">Login</button>
    </form>

    <a href="data.php?kelurahan=<?php echo urlencode($kelurahan); ?>" class="btn-kembali">
      ⬅ Kembali ke Data <?php echo htmlspecialchars(ucfirst($kelurahan)); ?>
    </a>
  </div>
</body>
</html>
