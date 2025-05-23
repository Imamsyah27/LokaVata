<?php
require('Logout.php');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loka Vata</title>
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    
    <style>

@import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');

body {
  margin: 0;
  font-family: Montserrat, sans-serif;
  background-color: #16a085;
}

/* Header styling */
header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: rgba(0, 0, 0, 0.8); /* Transparansi hitam lebih gelap */
  color: #fff;
  height: 80px;
  position: absolute; /* Overlay di atas background utama */
  top: 0;
  left: 0;
  right: 0;
  z-index: 10;
  padding: 0 10px; /* Menambahkan padding untuk elemen dalam header */
}

header nav a.btnLogout:hover{
    background-color: red;
    text-decoration:none;
}

.logo img {
  max-height: 130px;
  height: auto;
  padding-left: 20px;
}

nav {
  display: flex;
  gap: 15px;
}

nav a {
  color: #fff;
  text-decoration: none;
  padding: 5px 10px;
  border-radius: 5px;
  transition: background-color 0.3s ease;
  font-size: 18px;
}

nav a:hover,
nav a.active {
  background-color: #16a085;
}

main {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100%; /* Mengisi seluruh tinggi viewport */
  text-align: center;
  color: rgba(0, 0, 0, 0.7); /* Transparansi hitam lebih gelap */;
  position: relative;
  overflow: hidden; /* Menghindari scrolling */
  background: url('Images/Indonesia.png') no-repeat center center;
  background-size: cover;
  overflow: scroll;
}

/* Style for Pop-Up */
.popup {
  display: none;  /* Hidden by default */
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  justify-content: center;
  align-items: center;
}

.popup-content {
  background-color: white;
  padding: 20px;
  border-radius: 5px;
  text-align: center;
  width: 300px;
}

/* Hamburger button */
.menu-toggle {
  display: none;
  flex-direction: column;
  cursor: pointer;
  padding: 10px;
  z-index: 11;
}

.menu-toggle span {
  height: 3px;
  width: 25px;
  background: white;
  margin: 4px 0;
  transition: all 0.3s ease;
}

        /* Hero Section */
        .hero {
            background: url('https://source.unsplash.com/1600x900/?nature,water') no-repeat center center/cover;
            color: rgb(238, 235, 235) transparent;
            text-align: center;
            padding: 100px 20px;
        }

        .hero h1 {
            font-size: 2.5em;
        }

        /* Contact Section */
        .contact {
            text-align: left;
            margin: 0;
            padding: 40px 20px;
            background-color: transparent; /* Warna merah */
            color: white; /* Warna teks agar kontras */
}


        .contact h2 {
            margin-top: 50px;
            margin-bottom: 10px;
        }

        .contact p {
            margin-bottom: 20px;
        }

        .contact-boxes {
    display: flex;
    justify-content: center;
    gap: 100px;
    margin-bottom: 20px;
}

.box {
    width: 250px;
    padding: 20px;
    background: rgba(255, 255, 255, 0.2); /* Transparan */
    border-radius: 10px;
    text-align: center;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px); /* Efek blur */
    border: 1px solid rgba(255, 255, 255, 0.3); /* Border halus */
}

.box img {
    width: 50px;
    margin-bottom: 10px;
}

.box p {
    color: white; /* Agar teks tetap terlihat di atas transparansi */
    font-weight: bold;
}

.box button {
    background: rgba(76, 175, 80, 0.8); /* Transparan hijau */
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.box button:hover {
    background: rgba(76, 175, 80, 1); /* Warna lebih solid saat hover */
}


        /* Map Section */
        .map-section {
            padding: 40px 20px;
            background-color: #eef;
            text-align: center;
        }

        #map {
            width: 100%;
            height: 400px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        }
    
        @media (max-width: 768px) {
            .menu-toggle {
    display: flex;
  }
  header {
    flex-direction: row;
    justify-content: space-between;
  }

  .logo img {
    max-height: 60px;
    padding-left: 10px;
  }

  nav {
    position: absolute;
    top: 80px;
    left: 0;
    width: 100%;
    flex-direction: column;
    background: rgba(0, 0, 0, 0.8);
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.4s ease-out;
    z-index: 20;
  }

  nav a {
    font-size: 16px;
    padding: 8px 12px;
  }

  nav.open {
    max-height: 300px; /* Cukup besar untuk semua menu */
    transition: max-height 0.4s ease-in;
    z-index: 20;
  }
  .contact-boxes {
    flex-direction: column;
    gap: 20px;
    align-items: center;
  }

  .box {
    width: 90%;
    max-width: 350px;
  }

  .contact h2, .contact p {
    text-align: center;
  }

  .map-section {
    padding: 20px 10px;
  }

  #map {
    height: 300px;
  }

}

    </style>
</head>
<body>

    <!-- Navbar -->
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
            <a href="About.php">About Us</a>
            <a href="contactus.php" class="active">Contact-Us</a>
            <a href="?logout=true" class=btnLogout>Log Out</a>
            </nav>
      </header>
    

    <!-- Hero Section -->
    <main>
    <!-- Contact Section -->
    <section class="contact">
        <h2>Jam Operasional</h2>
        <p>Senin - Jum'at, Pukul 08.00-16.00 WIB</p>
        <h2>Contact Loka Vata</h2>
        <p>Untuk informasi lebih lanjut, silahkan pilih kontak di bawah ini untuk menghubungi kami</p>
        
        <div class="contact-boxes">
            <div class="box">
                <img src="https://img.icons8.com/color/48/000000/gmail.png" alt="Email">
                <p>Hubungi kami via E-Mail</p>
                <button onclick="alert('Email: contact@lokavata.com')">Klik Disini</button>
            </div>

            <div class="box">
                <img src="https://img.icons8.com/color/48/000000/instagram-new.png" alt="Instagram">
                <p>Hubungi kami via Instagram</p>
                <button onclick="alert('Instagram: @lokavata')">Klik Disini</button>
            </div>

            <div class="box">
                <img src="https://img.icons8.com/color/48/000000/whatsapp.png" alt="WhatsApp">
                <p>Hubungi kami via WhatsApp</p>
                <button onclick="alert('WhatsApp: +62 812-3456-7890')">Klik Disini</button>
            </div>
        </div>
        <section class="map-section">
            <div id="map"></div>
        </section>
    </section>
    </main>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <script>
        // Inisialisasi Peta
        var map = L.map('map').setView([-2.5489, 118.0149], 5); // Pusat Indonesia

        // Tambahkan Tile Layer OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Tambahkan Marker
        L.marker([-3.219399, 104.646087]).addTo(map) // Jakarta
            .bindPopup("Lab. Penginderaan Jauh dan SIG Kelautan, Universitas Sriwijaya")
            .openPopup();
    </script>
  <script>
  const toggle = document.getElementById('menuToggle');
  const nav = document.querySelector('nav');

  toggle.addEventListener('click', () => {
    nav.classList.toggle('open');
  });
</script>
</body>
</html>
