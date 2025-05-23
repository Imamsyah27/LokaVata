<?php
session_start();
include('config.php');  // Panggil koneksi database
require('Logout.php');

$user_name = "";
if (isset($_SESSION["name"])) {
    $user_name = $_SESSION["name"];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Apa Kata Mereka - LokaVata</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="Main.css">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700;900&display=swap" rel="stylesheet">

</head>
<body>

<header>
  <img src="Images/LokaVata.png" alt="Logo LokaVata" class="logo1" />
  <div class="menu-toggle" onclick="toggleMenu()">☰</div>
  <nav class="nav-links">
    <a href="Home.php">Beranda</a>
    <a href="Maps.php">Peta</a>
    <a href="Faq.php">FAQ</a>
    <a href="About.php">Tentang Kami</a>
    <a href="contactus.php">Kontak Kami</a>
  </nav>
<div class="auth-icon" style="font-family: 'DM Sans', sans-serif;">
  <img src="Images/PW.png" alt="User Icon" />
  <?php if (!empty($user_name)) : ?>
    <span><?php echo htmlspecialchars($user_name); ?></span>
    <a href="?logout=true" 
       style="margin-left: 10px; color: black; text-decoration: none; font-family: 'DM Sans', sans-serif;" 
       onmouseover="this.style.color='red'" 
       onmouseout="this.style.color='black'">
       Log Out
    </a>
  <?php else: ?>
    <a href="Login.php" 
       style="text-decoration: none; font-family: 'DM Sans', sans-serif;" 
       onmouseover="this.style.color='red'" 
       onmouseout="this.style.color='black'">
       Masuk
    </a>
  <?php endif; ?>
</div>

</header>

<div class="container">
  <div class="text-content">
    <h1>MEMANTAU MANGROVE<br>MENGELOLA MASA DEPAN</h1>
    <p>
      Temukan cara baru dalam memantau dan mengelola ekosistem mangrove melalui data spasial dan teknologi satelit terkini di Lokavata.
    </p>
    <a href="Maps.php"><button class="cta-button">Jelajahi LokaVata</button></a>
    <div class="visitor-info">
      <div class="avatars">
        <img src="Images/PC.PNG" alt="Avatar 1" />
        <img src="Images/PW.PNG" alt="Avatar 2" />
        <img src="Images/PC.PNG" alt="Avatar 3" />
      </div>
      <div class="visitor-text">
        Info Pengunjung Hari Ini<br>
        Jumlah Pengguna: 20 | User Aktif: 1
      </div>
    </div>
  </div>

  <div class="phone-mockup">
    <div class="slider">
      <div class="slides" id="slides">
        <img src="Images/Bali.PNG" alt="Slide 2" />
        <img src="Images/Bali2.PNG" alt="Slide 2" />
        <img src="Images/Bali3.PNG" alt="Slide 3" />
        <img src="Images/Kalsel.PNG" alt="Slide 4" />
        <img src="Images/Sulut.PNG" alt="Slide 5" />
        <img src="Images/Jambi.PNG" alt="Slide 6" />
        <img src="Images/Banten.PNG" alt="Slide 7" />
        <img src="Images/Aceh.PNG" alt="Slide 8" />
      </div>
      <button class="nav-button prev" onclick="changeSlide(-1)">&#10094;</button>
      <button class="nav-button next" onclick="changeSlide(1)">&#10095;</button>
      <div class="dots" id="dots"></div>
    </div>
  </div>
</div>

<div class="section-bawah">
  <div class="slider-container">
    <div class="slides2" id="slides2">
      <div class="phone-mockup-bawah">
        <iframe 
          src="https://www.instagram.com/reel/DILdKUOS4B8/embed"
          frameborder="0" 
          scrolling="no" 
          allowtransparency="true" 
          allow="encrypted-media"
          style="width:100%; height:100%; border-radius: 40px; overflow: hidden;">
        </iframe>
      </div>
      <div class="phone-mockup-bawah">
        <iframe 
          src="https://www.instagram.com/reel/DJCE5gcSoS3/embed"
          frameborder="0" 
          scrolling="no" 
          allowtransparency="true" 
          allow="encrypted-media"
          style="width:100%; height:100%; border-radius: 40px; overflow: hidden;">
        </iframe>
      </div>
      <div class="phone-mockup-bawah">
        <iframe 
          src="https://www.instagram.com/reel/DJQQUGsyTKc/embed"
          frameborder="0" 
          scrolling="no" 
          allowtransparency="true" 
          allow="encrypted-media"
          style="width:100%; height:100%; border-radius: 40px; overflow: hidden;">
        </iframe>
      </div>
    </div>
    <button class="nav-button prev" onclick="changeSlide2(-1)">&#10094;</button>
    <button class="nav-button next" onclick="changeSlide2(1)">&#10095;</button>
  </div>

  <div class="text-content" style="max-width: 600px; margin-left: 40px;">
    <h1>APA KATA<br />MEREKA?</h1>
    <p>
      Beragam pihak telah ikut terlibat dalam perjalanan LokaVata. Mulai dari mahasiswa
      yang menggunakannya untuk riset, dosen yang menjadikannya sumber pembelajaran, hingga
      tim pengembang yang terus berinovasi di balik layar. Inilah cerita dan kesan mereka tentang LokaVata.
    </p>
  </div>
</div>

<footer>
  <div class="footer-container">
    <div class="footer-left">
      <img src="Images/LokaVataT.png" alt="Logo LokaVata" class="logo" />
      <span>
        LokaVata adalah platform digital untuk memantau ekosistem mangrove melalui data
        spasial, citra satelit, dan estimasi karbon.
      </span>
    </div>
    <div class="footer-logos">
      <div><img src="Images/Satelit.png" /><p>Analisis<br>Citra Satelit</p></div>
      <div><img src="Images/Web.png" /><p>Estimasi<br>Simpanan Karbon</p></div>
      <div><img src="Images/Koin.png" /><p>Valuasi<br>Ekonomi</p></div>
      <div><img src="Images/Properti.png" /><p>Peta RTRW<br>Interaktif</p></div>
    </div>
  </div>
<div class="footer-bottom">
  <div class="footer-bottom-content">
    <span class="footer-bottom-text">&copy; 2025 LokaVata. Hak Cipta Dilindungi</span>
    <img src="Images/LokaVataT.png" alt="Logo LokaVata" />
    <img src="Images/Mapid.png" alt="Logo Mapid" />
  </div>
</div>
</footer>

<script>
    function toggleMenu() {
    const nav = document.querySelector('.nav-links');
    nav.classList.toggle('active');
  }
  
const slides2 = document.getElementById("slides2");
const totalSlides2 = slides2.children.length;
let slideIndex2 = 0;

function showSlide2(index) {
  slideIndex2 = (index + totalSlides2) % totalSlides2;
  slides2.style.transform = `translateX(-${slideIndex2 * 300}px)`;
}

function changeSlide2(n) {
  showSlide2(slideIndex2 + n);
}

// Inisialisasi tampilan slide pertama
showSlide2(0);

  //..........................................................................//

  const slides = document.getElementById("slides");
  const dotsContainer = document.getElementById("dots");
  const totalSlides = slides.children.length;
  let slideIndex = 0;

  // Buat dot otomatis
  for (let i = 0; i < totalSlides; i++) {
    const dot = document.createElement("span");
    dot.classList.add("dot");
    if (i === 0) dot.classList.add("active");
    dot.addEventListener("click", () => {
      showSlide(i);
      resetInterval();
    });
    dotsContainer.appendChild(dot);
  }

  const dots = dotsContainer.querySelectorAll(".dot");

  function showSlide(index) {
    slideIndex = (index + totalSlides) % totalSlides;
    slides.style.transform = `translateX(-${slideIndex * 500}px)`;
    dots.forEach(dot => dot.classList.remove("active"));
    dots[slideIndex].classList.add("active");
  }

  function changeSlide(n) {
    showSlide(slideIndex + n);
    resetInterval();
  }

  let slideInterval = setInterval(() => changeSlide(1), 3000);

  function resetInterval() {
    clearInterval(slideInterval);
    slideInterval = setInterval(() => changeSlide(1), 3000);
  }
</script>

</body>
</html>
