<?php
session_start();
require('config.php'); // Koneksi ke database
require('Logout.php');

if (!isset($_SESSION["user_id"]) || !isset($_SESSION["role"])) {
  header("Location: Login.php");
  exit();
}

$user_id = $_SESSION["user_id"];
$role = $_SESSION["role"];
$name = "";

// Ambil nama berdasarkan peran user
if ($role === "admin") {
  $stmt = $conn->prepare("SELECT name FROM admin_accounts WHERE id = ?");
} else {
  $stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name);
$stmt->fetch();
$stmt->close();
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
      <img src="Images/LokaVata.png" alt="LokaVata Logo">
    </div>
  <div class="menu-toggle" id="menuToggle">
  <span></span>
  <span></span>
  <span></span>
</div>
    <nav>
      <a href="#" class="active">Home</a>
      <a href="Maps.php">Maps</a>
      <a href="Faq.php">FAQ</a>
      <a href="About.php">About Us</a>
      <a href="contactus.php">Contact Us</a>
      <a href="?logout=true" class=btnLogout>Log Out</a>
    </nav>
  </header>

  <main>
    <?php if ($role === "admin") : ?>
      <p>Anda login sebagai <strong>Admin</strong>.</p>
      <div class="chat-button-container">
          <a href="admin_chat.php" class="chat-button">📩 Balas Pesan User</a>
      </div>
        <?php else : ?>
            <p>Anda login sebagai <strong>User</strong>.</p>
        <?php endif; ?>

    <h1>We provide the Data</h1>
    <p class="mangrove-availability">Provide all Mangrove Data since 2024</p>
    <a href="Maps.php">
      <button class="explore-button">Begin your exploration here</button>
    </a>

    <div class="social-links">
      <a href="https://linktr.ee/lokavata.official" class="icon"><i class="fa-solid fa-magnifying-glass"></i></a>
    </div>
  </main>
  <script>
  const toggle = document.getElementById('menuToggle');
  const nav = document.querySelector('nav');

  toggle.addEventListener('click', () => {
    nav.classList.toggle('open');
  });
</script>

</body>
</html>