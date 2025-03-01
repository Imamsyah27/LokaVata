<?php   
require('Logout.php');

if (!isset($_SESSION)) {
    session_start();
}

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    // Jika belum, arahkan kembali ke halaman login
    header("Location: Login.php");
exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loka Vata Website</title>
    <link rel="stylesheet" href="Maps.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/maplibre-gl@2.1.9/dist/maplibre-gl.css">
    <script src="https://unpkg.com/maplibre-gl@2.1.9/dist/maplibre-gl.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mapbox-gl-draw/1.2.0/mapbox-gl-draw.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mapbox-gl-draw/1.2.0/mapbox-gl-draw.js"></script>
</head>

<body>
    <header></header>
    <main>
        <!-- Menu Sidebar -->
        <div id="sideMenu" class="menu-slide">
            <nav>
                <ul>
                    <li><a href="Home.php">Home</a></li>
                    <li><a href="Maps.php" class="active">Maps</a></li>
                    <li><a href="About.html">About</a></li>
                    <li><a href="contactus.html">Contact Us</a></li>
                    <a href="?logout=true" style="color: red; font-weight: bold;">Log Out</a>
                </ul>
            </nav>
        </div>

        <!-- Section Peta dan Tabel -->
        <section class="map-section">
            <div class="table-overlay">
                <div class="table-container">
                    <!-- Dropdown lokasi dipindahkan ke sini -->
                    <select id="locationSelect" onchange="moveToLocation()">
                        <option value="">Choose a Region</option>
                        <option value="-3.6561,128.1904,9" data-region="maluku">Maluku</option>
                        <option value="-6.4058,106.0640,9" data-region="banten">Banten</option>
                        <option value="-8.3405,115.0920,9" data-region="bali">Bali</option>
                        <option value="0.5479,123.0732,9" data-region="gorontalo">Gorontalo</option>
                        <option value="-6.2088,106.8456,12" data-region="dki_jakarta">Dki Jakarta</option>
                        <option value="-2.7411,106.4406,9" data-region="bangka_belitung">Bangka Belitung</option>
                        <option value="-7.8014,110.3644,9" data-region="daerah_istimewa_yogyakarta">Daerah Istimewa Yogyakarta</option>
                        <option value="-6.9175,107.6191,9" data-region="jawa_barat">Jawa Barat</option>
                        <option value="-3.7928,102.2655,9" data-region="bengkulu">Bengkulu</option>
                        <option value="1.4931,124.8413,9" data-region="sulawesi_utara">Sulawesi Utara</option>
                        <option value="-1.6106,103.6070,9" data-region="jambi">Jambi</option>
                        <option value="5.5483,95.3238,9" data-region="nangroe_aceh_darussalam">Nangroe Aceh Darussalam</option>
                        <option value="-2.8505,118.9832,9" data-region="sulawesi_barat">Sulawesi Barat</option>
                        <option value="-3.0007,115.3055,9" data-region="kalimantan_selatan">Kalimantan Selatan</option>
                        <option value="0.5333,101.4500,9" data-region="riau">Riau</option>
                        <option value="-2.236466,104.843780,12" data-region="sumatera_selatan">Sumatera Selatan</option>
                        <option value="-5.4500,105.2667,9" data-region="lampung">Lampung</option>
                        <option value="-5.1333,119.4167,9" data-region="sulawesi_selatan">Sulawesi Selatan</option>
                        <option value="-1.6815,113.3824,9" data-region="kalimantan_tengah">Kalimantan Tengah</option>
                        <option value="1.6346,127.8573,9" data-region="maluku_utara">Maluku Utara</option>
                        <option value="-7.1500,110.1400,9" data-region="jawa_tengah">Jawa Tengah</option>
                        <option value="0.1323,111.2138,9" data-region="kalimantan_barat">Kalimantan Barat</option>
                        <option value="-8.6500,117.3667,9" data-region="nusatenggara_barat">Nusatenggara Barat</option>
                        <option value="2.9667,99.0667,9" data-region="sumatera_utara">Sumatera Utara</option>
                        <option value="-0.9471,100.4167,9" data-region="sumatera_barat">Sumatera Barat</option>
                        <option value="-7.2500,112.7500,9" data-region="jawa_timur">Jawa Timur</option>
                        <option value="-3.9790,121.9170,9" data-region="sulawesi_tenggara">Sulawesi Tenggara</option>
                        <option value="0.9176,104.4676,9" data-region="kepulauan_riau">Kepulauan Riau</option>
                        <option value="0.5000,117.1500,9" data-region="kalimantan_timur">Kalimantan Timur</option>
                        <option value="-4.2699,138.0804,9" data-region="papua">Papua</option>
                        <option value="-1.4304,120.4602,9" data-region="sulawesi_tengah">Sulawesi Tengah</option>
                        <option value="-0.8782,134.0640,9" data-region="papua_barat">Papua Barat</option>
                        <option value="-8.5568,121.0794,9" data-region="nusatenggara_timur">Nusatenggara Timur</option>
                    </select>
                    <table id="overlayTable" class="overlay-table">
                        <thead>
                            <tr>
                                <th>Keterangan</th>
                                <th>Total AGC (Mg/ha)</th>
                                <th>Valuasi Ekonomi (Rp)</th>
                                <th>Total Luasan (ha)</th>
                                <th>Tanggal</th>
                                <th>Unduh</th>
                            </tr>
                        </thead>
                        <tbody id="overlayTableBody"></tbody>
                    </table>                         
                </div>
            </div>
            <div id="mapLegend2" class="legend2">
                <h4>Legenda Kawasan RTRW</h4>
                <div><span style="background:#ff0000"></span> Kawasan Perlindungan</div>
                <div><span style="background:#ff6600"></span> Kawasan Konservasi Laut</div>
                <div><span style="background:#008000"></span> Kawasan Konservasi</div>
                <div><span style="background:#006600"></span> Kawasan Ekosistem Mangrove</div>
                <div><span style="background:#00ccff"></span> Kawasan Perlindungan Setempat</div>
                <div><span style="background:#0000ff"></span> Badan Air</div>
                <div><span style="background:#8B4513"></span> Kawasan Hutan Produksi</div>
                <div><span style="background:#800080"></span> Kawasan Industri</div>
                <div><span style="background:#FFA500"></span> Kawasan Transportasi</div>
                <div><span style="background:#FFD700"></span> Kawasan HGU & HGB</div>
            </div>            
            <div id="menuButton" class="menu-button">☰</div>
            <div class="logo-map-overlay">
                <img src="images/LokaVata.png" alt="Website Logo">
            </div>
        </section>
            <section class="map-container">
            <div class="map-container" id="map"></div>
        </section>
    </main>
    <script src="Maps.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@turf/turf@7.2.0"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const nav = document.querySelector('nav');
            const mapSection = document.querySelector('.map-section');

            menuButton.addEventListener('click', () => {
                nav.classList.toggle('open');
                mapSection.classList.toggle('menu-open');
            });
        });
    </script>
</body>

</html>