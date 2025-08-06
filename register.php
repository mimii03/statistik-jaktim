<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "statistik"; // atau nama database kamu

$conn = mysqli_connect($host, $user, $pass, $db);

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Enkripsi

    // Simpan ke database
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
    if (mysqli_query($conn, $query)) {
        echo "Registrasi berhasil! Silakan <a href='login.php'>login</a>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<h2>Register</h2>
<form method="post">
  Username: <input type="text" name="username" required><br>
  Email: <input type="email" name="email" required><br>
  Password: <input type="password" name="password" required><br>
  <button type="submit" name="register">Sign Up</button>
</form>
