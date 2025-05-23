<?php
$servername = "localhost";  // Host database, biasanya 'localhost' jika menggun>
$username = "ubuntu";         // Username MySQL, defaultnya 'root' pada XAMPP
$password = "ubuntu";             // Password MySQL, defaultnya kosong pada XAM>
$dbname = "LokaVata";       // Nama database yang sudah Anda buat

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
