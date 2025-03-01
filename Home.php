<?php
session_start();
require('config.php'); // Koneksi ke database

// Proses Logout
if (isset($_GET['logout'])) {
    session_unset();  // Menghapus semua session
    session_destroy();  // Menghancurkan session
    header("Location: Login.php");  // Redirect ke halaman login setelah logout
    exit();
}

if (!isset($_SESSION['user_id'])) {
  header("Location: Login.php"); // Jika belum login, arahkan ke Login.php
  exit(); // Pastikan script berhenti setelah redirect
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Website Exploration</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
  <link rel="stylesheet" href="Home.css">
  <style>

  </style>
</head>
<body>
  <header>
    <div class="logo">
      <img src="Images\LokaVata.png" alt="LokaVata Logo">
    </div>
    <nav>
      <a href="#" class="active">Home</a>
      <a href="Maps.php">Maps</a>
      <a href="Faq.php">FAQ</a>
      <a href="About.php">About Us</a>
      <a href="contactus.php">Contact Us</a>
      <a href="?logout=true" style="color: red; font-weight: bold;">Log Out</a>
    </nav>
  </header>

  <main>
    <!-- Display user name and log out button -->
    <?php if (isset($_SESSION['user_name'])): ?>
      <div class="user-info">
        <p>Welcome, <?php echo $_SESSION['user_name']; ?>!</p>
      </div>
    <?php else: ?>
      <p>Please log in to access your account.</p>
    <?php endif; ?>

    <h1>We provide the Data</h1>
    <p class="mangrove-availability">Provide all Mangrove Data since 2024</p>
    <a href="Maps.html">
      <button class="explore-button">Begin your exploration here</button>
    </a>

    <div class="social-links">
      <a href="https://linktr.ee/lokavata.official" class="icon"><i class="fa-solid fa-magnifying-glass"></i></a>
    </div>
  </main>

</body>
</html>
