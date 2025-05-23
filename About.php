<?php
require('Logout.php');
require('config.php');
if (!isset($_SESSION)) {
    session_start();
}

// Pastikan user sudah login
if (!isset($_SESSION["user_id"]) || !isset($_SESSION["role"])) {
    header("Location: Login.php");
    exit();
}

$user_id = $_SESSION["user_id"]; // Ambil user_id dari session

// Proses User Mengirim Pesan ke Database
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["send_message"])) {
    if (!isset($_SESSION["user_id"]) || empty($_SESSION["user_id"])) {
        die("Error: User ID tidak valid. Silakan login kembali.");
    }

    $user_id = $_SESSION["user_id"];
    $message = trim($_POST["message"]);

    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO messages (user_id, message) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $message);
        if ($stmt->execute()) {
            $stmt->close();
            header("Refresh:0"); // Reload halaman setelah mengirim pesan
            exit();
        } else {
            echo "Error saat menyimpan pesan: " . $stmt->error;
        }
    } else {
        echo "Pesan tidak boleh kosong!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Website Exploration</title>
  <link rel="stylesheet" href="About.css">
</head>
<body>

  <header>
    <div class="logo">
      <img src="Images\LokaVata.png" alt="LokaVata Logo">
    </div>
    <div class="menu-toggle" id="menuToggle">
  <span></span>
  <span></span>
  <span></span>
</div>

      <nav>
        <a href="Home.php">Home</a>
        <a href="Maps.php">Maps</a>
        <a href="Faq.php">FAQ</a>
        <a href="About.php" class="active">About Us</a>
        <a href="contactus.php">Contact Us</a>
        <a href="?logout=true" class=btnLogout>Log Out</a>
        </nav>

  </header>

  <div class="team-photo-container">
    <img src="Images/Tim.png" alt="Our Team Photo">
  </div>

  <main>
    <div class="container">
      <div class="team-grid">
        <div class="team-member">
          <img src="Images/Erik.png" alt="Profile 1">
          <div class="info">
            <a href="https://www.linkedin.com/in/erik-wijaya-332681237?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=ios_app" target="_blank" class="name">
              Erik Wijaya
            </a>
            <span class="role">User Interface Designer</span>
          </div>
        </div>
        <div class="team-member project-leader">
        <img src="Images/Ayu.png" alt="Profile 2">
          <div class="info">
            <a href="https://www.linkedin.com/in/gusti-ayu-made-mirah-rismayanti-1651a421a?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=ios_app" target="_blank" class="name">
              Gusti Ayu Made Mirah Rismayanti
            </a>
            <span class="role">Project Leader</span>
          </div>
        </div>
        <div class="team-member">
          <img src="Images/Nisak.png" alt="Profile 3">
          <div class="info">
            <a href="https://www.linkedin.com/in/khairun-nisak-ba62a6349?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=ios_app" target="_blank" class="name">
              Khairunnisak
            </a>
            <span class="role">Business Impact Strategies</span>
          </div>
        </div>
        <div class="team-member">
          <img src="Images/Imam.png" alt="Profile 4">
          <div class="info">
            <a href="https://www.linkedin.com/in/imam-syah-970821347?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=ios_app" target="_blank" class="name">
              Muhammad Imamsyah Dwi Yanto
            </a>
            <span class="role">Developer</span>
          </div>
        </div>
        <div class="team-member">
          <img src="Images/Heqi.png" alt="Profile 5">
          <div class="info">
            <a href="https://www.linkedin.com/in/heqi-putra-rayhan-53607621a?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=ios_app" target="_blank" class="name">
              Heqi Putra Rayhan
            </a>
            <span class="role">Data Science</span>
          </div>
        </div>
        <div class="team-member">
          <img src="Images/Qadri.png" alt="Profile 6">
          <div class="info">
            <a href="https://www.linkedin.com/in/al-qadri-taufik-bb156830b?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=ios_app" target="_blank" class="name">
              Alqadri Taufik
            </a>
            <span class="role">Spatial data Analyst</span>
          </div>
        </div>
      </div>
    </div>
  </main>  
  <script>
  const menuToggle = document.getElementById('menuToggle');
  const nav = document.querySelector('nav');

  menuToggle.addEventListener('click', () => {
    nav.classList.toggle('open');
  });
</script>

</body>
</html>