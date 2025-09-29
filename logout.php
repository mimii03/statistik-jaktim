<?php
session_start();
session_unset();
session_destroy();

$redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';

header("Location: " . $redirect);
exit;
