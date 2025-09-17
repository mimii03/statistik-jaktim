<?php
session_start();
session_unset();
session_destroy();

// cek referer (halaman sebelumnya)
$redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';

// redirect ke halaman asal
header("Location: " . $redirect);
exit;
