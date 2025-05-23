<?php
$servername = "localhost";  // Host database, biasanya 'localhost' jika menggunakan XAMPP
$username = "root";         // Username MySQL, defaultnya 'root' pada XAMPP
$password = "";             // Password MySQL, defaultnya kosong pada XAMPP
$dbname = "lokg9647_livechat";       // Nama database yang sudah Anda buat

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
