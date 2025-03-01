<?php
require('config.php');

if (isset($_GET['logout'])) {
session_unset();  // Menghapus semua session
    session_destroy();  // Menghancurkan session
    header("Location: Login.php");  // Redirect ke halaman login setelah logout
    exit();
}
?>