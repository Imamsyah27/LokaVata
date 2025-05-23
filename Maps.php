<?php   
session_start();
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
$role = $_SESSION["role"]; // ← Tambahkan ini

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
    <title>Loka Vata Website</title>
    <link rel="stylesheet" href="Maps.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/maplibre-gl@2.1.9/dist/maplibre-gl.css">
    <script src="https://unpkg.com/maplibre-gl@2.1.9/dist/maplibre-gl.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mapbox-gl-draw/1.2.0/mapbox-gl-draw.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mapbox-gl-draw/1.2.0/mapbox-gl-draw.js"></script>
</head>

<body>
<button id="toggleDropdownBtn" class="settings-button" title="Pengaturan">&#9881;</button>

    <header></header>
    <main>
        <!-- Menu Sidebar -->
        <div id="sideMenu" class="menu-slide">
            <nav>
                <ul>
                    <li><a href="Home.php">Home</a></li>
                    <li><a href="Maps.php" class="active">Maps</a></li>
                    <li><a href="About.php">About Us</a></li>
                    <li><a href="contactus.php">Contact Us</a></li>
                    <li><a href="?logout=true" class="btnLogin">Log Out</a></li>
                </ul>
            </nav>
        </div>
                <!-- Section Peta dan Tabel -->
                <section class="map-section">

                <!-- Table overlay sederhana -->
                <div class="table-overlay">
                    <div class="table-container">
                        <table id="overlayTable" class="overlay-table">
                            <thead>
                            <tr>
                                <th>Nama Wilayah</th>
                                <th>Keterangan</th>
                                <th>Total AGC (Mg)</th>
                                <th>Valuasi Ekonomi (Rp)</th>
                                <th>Total Luasan (ha)</th>
                            </tr>
                            </thead>
                            <tbody id="overlayTableBody"></tbody>
                        </table>
                        </div>
                    </div>
                <!-- Dropdown container baru (kanan atas) -->
                <div class="custom-dropdown-container">
                    <!-- Tab buttons -->
                    <div class="tab-buttons">
                    <button class="tab-button active" data-tab="data">Data</button>
                    <button class="tab-button" data-tab="legend">Legenda</button>
                    </div>

                    <!-- Tab content -->
                    <div class="tab-content">

                    <!-- Data tab -->
                    <div class="tab-pane active" id="data">
                        <div class="data-section">
                        <!-- Dropdown Lokasi -->
                         <!-- Dropdown Tahun -->
                        <label for="yearSelect">Pilih Tahun:</label>
                        <select id="yearSelect" onchange="onYearChange()">
                            <option value="2021">2019</option>
                            <option value="2021">2020</option>
                        <option value="2021">2021</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                        </select>

                        <label for="locationSelect">Pilih Wilayah:</label>
                        <select id="locationSelect" onchange="moveToLocation()">
                        <option value="">Choose a Region</option>
                        <option value="-8.3405,115.0920,9" data-region="bali">Bali</option>
                        <option value="-2.7411,106.4406,9" data-region="bangka_belitung">Bangka Belitung</option>
                        <option value="-6.4058,106.0640,9" data-region="banten">Banten</option>
                        <option value="-3.7928,102.2655,9" data-region="bengkulu">Bengkulu</option>
                        <option value="-7.8014,110.3644,9" data-region="daerah_istimewa_yogyakarta">Daerah Istimewa Yogyakarta</option>
                        <option value="-6.2088,106.8456,12" data-region="dki_jakarta">Dki Jakarta</option>
                        <option value="0.5479,123.0732,9" data-region="gorontalo">Gorontalo</option>
                        <option value="-1.6106,103.6070,9" data-region="jambi">Jambi</option>
                        <option value="-6.9175,107.6191,9" data-region="jawa_barat">Jawa Barat</option>
                        <option value="-7.1500,110.1400,9" data-region="jawa_tengah">Jawa Tengah</option>
                        <option value="-7.2500,112.7500,9" data-region="jawa_timur">Jawa Timur</option>
                        <option value="0.1323,111.2138,9" data-region="kalimantan_barat">Kalimantan Barat</option>
                        <option value="-3.0007,115.3055,9" data-region="kalimantan_selatan">Kalimantan Selatan</option>
                        <option value="-1.6815,113.3824,9" data-region="kalimantan_tengah">Kalimantan Tengah</option>
                        <option value="0.5000,117.1500,9" data-region="kalimantan_timur">Kalimantan Timur</option>
                        <option value="0.9176,104.4676,9" data-region="kepulauan_riau">Kepulauan Riau</option>
                        <option value="-5.4500,105.2667,9" data-region="lampung">Lampung</option>
                        <option value="-3.6561,128.1904,9" data-region="maluku">Maluku</option>
                        <option value="1.6346,127.8573,9" data-region="maluku_utara">Maluku Utara</option>
                        <option value="5.5483,95.3238,9" data-region="nangroe_aceh_darussalam">Nangroe Aceh Darussalam</option>
                        <option value="-8.6500,117.3667,9" data-region="nusatenggara_barat">Nusa Tenggara Barat</option>
                        <option value="-8.5568,121.0794,9" data-region="nusatenggara_timur">Nusa Tenggara Timur</option>
                        <option value="-4.2699,138.0804,9" data-region="papua">Papua</option>
                        <option value="-0.8782,134.0640,9" data-region="papua_barat">Papua Barat</option>
                        <option value="0.5333,101.4500,9" data-region="riau">Riau</option>
                        <option value="-5.1333,119.4167,9" data-region="sulawesi_selatan">Sulawesi Selatan</option>
                        <option value="-2.8505,118.9832,9" data-region="sulawesi_barat">Sulawesi Barat</option>
                        <option value="-1.4304,120.4602,9" data-region="sulawesi_tengah">Sulawesi Tengah</option>
                        <option value="-3.9790,121.9170,9" data-region="sulawesi_tenggara">Sulawesi Tenggara</option>
                        <option value="1.4931,124.8413,9" data-region="sulawesi_utara">Sulawesi Utara</option>
                        <option value="-0.9471,100.4167,9" data-region="sumatera_barat">Sumatera Barat</option>
                        <option value="-2.236466,104.843780,12" data-region="sumatera_selatan">Sumatera Selatan</option>
                        <option value="2.9667,99.0667,9" data-region="sumatera_utara">Sumatera Utara</option>
                        </select>
                    <!-- Layer Control -->
          <div class="layer-control">
            <strong>Layer Control</strong>
            <div id="layerCheckboxes"></div>
          </div>
          <div class="upload-geojson">
  <label for="geojsonUpload">📁 Upload GeoJSON:</label>
  <input type="file" id="geojsonUpload" accept=".geojson,.json">
  <button id="deleteGeoJSON" title="Hapus GeoJSON" style="display:none;">🗑️</button>
</div>


          <!-- Download Buttons -->
          <div class="download-buttons">
            <button class="download-btn csv">Download CSV</button>
            <button class="download-btn geojson">Download GeoJSON AGC</button>
          </div>
        </div>
      </div>

      <!-- Legend tab -->
      <div class="tab-pane" id="legend">
        <div class="legend-section">
          <strong>Legenda Kawasan RTRW</strong>
          <section>
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
          </section>

          <strong>Legenda AGC</strong>
<div id="legendAGC" class="legend-agc">
  <div class="legend-item"><span style="background:#ffffbf"></span> 0–10 Mg/ha</div>
  <div class="legend-item"><span style="background:#fee08b"></span> 10–30 Mg/ha</div>
  <div class="legend-item"><span style="background:#d9ef8b"></span> 30–60 Mg/ha</div>
  <div class="legend-item"><span style="background:#a6d96a"></span> 60–90 Mg/ha</div>
  <div class="legend-item"><span style="background:#66bd63"></span> 90–120 Mg/ha</div>
  <div class="legend-item"><span style="background:#1a9850"></span> 120–150 Mg/ha</div>
  <div class="legend-item"><span style="background:#006837"></span> 150+ Mg/ha</div>
</div>
        </div>
      </div>

    </div>
  </div>

  <!-- Elemen lainnya -->
  <div id="menuButton" class="menu-button">☰</div>

  <div class="logo-map-overlay">
    <img src="Images/LokaVata.png" alt="Website Logo">
  </div>

</section>

<section class="map-container">
  <div class="map-container" id="map"></div>
</section>

</div>

    </main>
    <script src="https://www.gstatic.com/firebasejs/9.6.10/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.10/firebase-firestore-compat.js"></script>
    <script src="Maps.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@turf/turf@7.2.0"></script>
    <script src="firebase.js"></script>
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

<script>
function toggleChat() {
    let chatContainer = document.getElementById("chat-container");
    if (chatContainer.style.display === "none" || chatContainer.style.display === "") {
        chatContainer.style.display = "flex";
    } else {
        chatContainer.style.display = "none";
    }
}
</script>
<script>
$(document).ready(function() {
    function loadMessages() {
    $.ajax({
        url: "get_history.php",
        type: "GET",
        dataType: "json",
        success: function(response) {
            let chatBox = $("#chat-box");
            chatBox.html(""); // Bersihkan chat sebelum update

            response.forEach(msg => {
                let chatClass = msg.sender === "admin" ? "admin-message" : "user-message";
                let chatBubble = `<div class="chat-message ${chatClass}">
                    <p>${msg.message}</p>
                    <span class="chat-time">${msg.timestamp}</span>
                </div>`;

                chatBox.append(chatBubble);
            });

            // Scroll otomatis ke bawah setelah pesan ditambahkan
            chatBox.scrollTop(chatBox.prop("scrollHeight"));
        }
    });
}


    // Update pesan setiap 2 detik
    setInterval(loadMessages, 2000);

    // Kirim pesan user tanpa reload
    $("#chat-form").submit(function(event) {
        event.preventDefault();
        let messageText = $("#message-input").val();

        $.ajax({
            url: "send_message.php",
            type: "POST",
            data: { message: messageText },
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    $("#message-input").val(""); // Kosongkan input setelah kirim
                    loadMessages();
                } else {
                    alert("Gagal mengirim pesan: " + response.error);
                }
            }
        });
    });

    // Load pesan pertama kali saat halaman dimuat
    loadMessages();
});
</script>


<script>
$(document).ready(function() {
    function loadMessages() {
    let chatBox = $("#chat-box");
    let previousScrollTop = chatBox.scrollTop();
    let isAtBottom = previousScrollTop + chatBox.innerHeight() >= chatBox.prop("scrollHeight") - 10;

    $.ajax({
        url: "get_history.php",
        type: "GET",
        dataType: "json",
        success: function(response) {
            let existingMessages = chatBox.children().length;
            chatBox.html(""); // Kosongkan chat sebelum update

            response.forEach(msg => {
                let chatClass = msg.sender === "admin" ? "admin-message" : "user-message";
                let chatBubble = `<div class="chat-message ${chatClass}">
                    <p>${msg.message}</p>
                    <span class="chat-time">${msg.timestamp}</span>
                </div>`;

                chatBox.append(chatBubble);

                if (msg.reply) {
                    let adminReply = `<div class="chat-message admin-message">
                        <p>${msg.reply}</p>
                        <span class="chat-time">${msg.timestamp}</span>
                    </div>`;
                    chatBox.append(adminReply);
                }
            });

            // Jika user berada di bagian bawah sebelum update, scroll otomatis ke bawah
            if (isAtBottom || existingMessages === 0) {
                chatBox.scrollTop(chatBox.prop("scrollHeight"));
            } else {
                chatBox.scrollTop(previousScrollTop); // Biarkan posisi tetap jika user sedang scroll ke atas
            }
        }
    });
}

// Jalankan update setiap 2 detik
setInterval(loadMessages, 2000);
loadMessages();

});
</script>
<script>
$(document).ready(function () {
  const $locationSelect = $('#locationSelect');
  const isMobile = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);

  if (!isMobile && $.fn.select2 && $locationSelect.length) {
    $locationSelect.select2({
      placeholder: "Choose a Region",
      allowClear: true,
      minimumResultsForSearch: 0,
      dropdownParent: $('body')
    });
  }
});

</script>

<!-- Loading Overlay -->
<div id="loadingOverlay" style="display: none;">
    <div class="spinner"></div>
</div>


</body>

</html>

<?php if ($role !== "admin") : ?>
    <!-- Bubble Chat -->
    <div id="chat-bubble" onclick="toggleChat()">
        💬
    </div>

    <!-- Live Chat Container -->
    <div id="chat-container">
        <div id="chat-header">
            <h3>Live Chat</h3>
            <button id="close-chat" onclick="toggleChat()">✖</button>
        </div>
        <div id="chat-box"></div> <!-- Chat akan di-load dengan AJAX -->

        <!-- Input Pesan -->
        <form id="chat-form">
            <input type="text" id="message-input" placeholder="Tulis pesan ke admin..." required>
            <button type="submit">📩</button>
        </form>
    </div>
<?php endif; ?>

