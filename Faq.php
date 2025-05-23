<?php
require('Logout.php')
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ Dropdown</title>
    <link rel="stylesheet" href="Faq.css">
</head>
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
      <a href="Faq.php"class="active">FAQ</a>
      <a href="About.php">About Us</a>
      <a href="contactus.php">Contact Us</a>
      <a href="?logout=true" class=btnLogout>Log Out</a>
    </nav>
  </header>
<body>
    <div class="container">
        <div class="faq">
            <div class="question" onclick="toggleAnswer(this)">Apa itu Loka Vata?</div>
            <div class="answer">
                <!-- Baris pertama: teks kiri dan teks kanan -->
                <div class="row">
                    <div class="text">
                        Loka berasal dari bahasa Sanskerta yang berarti dunia atau alam semesta. Hal ini mencerminkan skala global dan luasnya dampak mangrove terhadap kehidupan, baik manusia maupun ekosistem secara keseluruhan.
                        <!-- <a href="Images/765-Article Text-4100-2-10-20220721.pdf" target="_blank">(Eka <em>et al.</em> 2024)</a> -->
                    </div>
                    <video src="Images/loka.webm" autoplay muted loop></video>
                    <div class="text">
                        Vata dalam beberapa bahasa tradisional dapat merujuk pada angin, pohon, atau bahkan simbol dari alam yang hidup. Dalam konteks ini, Vata secara khusus dikaitkan dengan mangrove sebagai pohon yang menghubungkan daratan dan laut, menjadi penjaga kehidupan pesisir.
                        <!-- <a href="Images/765-Article Text-4100-2-10-20220721.pdf" target="_blank">(Eka <em>et al.</em> 2024)</a> -->
                    </div>
                    <video src="Images/loka.webm" autoplay muted loop></video>
                </div>
                <!-- Baris kedua: teks di bawah -->
                <div class="bottom">
                    <div class="text">
                        <p>World of Mangrove, Frasa ini menegaskan bahwa mangrove adalah bagian integral dari dunia kita. Ekosistem mangrove bukan hanya sekedar hutan pesisir, tetapi merupakan sistem yang mendukung karbon biru, keanekaragaman hayati, dan perlindungan alami dari bencana seperti erosi dan badai.</p>
                        <p>Loka Vata mencerminkan pandangan holistik tentang pentingnya mangrove dalam menjaga keseimbangan dunia. Dengan menyebutnya sebagai "World of Mangrove," ini menegaskan bahwa mangrove adalah kunci keberlanjutan bagi planet kita, menghubungkan manusia, alam, dan masa depan</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="faq">
            <div class="question" onclick="toggleAnswer(this)">Apa itu Mangrove?</div>
            <div class="answer">
                <div class="row">
                    <div class="text">
                        Mangrove adalah ekosistem hutan pesisir yang tumbuh di daerah tropis dan subtropis, terutama di wilayah pasang surut seperti muara sungai, laguna, dan pantai berlumpur. Menurut Penelitian <a href="JURNAL/siswoyo.pdf" target="_blank">(Siswoyo <em>et al.</em> 2024)</a> ekosistem ini tidak hanya unik karena mampu bertahan di lingkungan dengan kadar garam tinggi, tetapi juga memiliki peran sangat penting dalam mitigasi perubahan iklim, terutama sebagai penyerap karbon yang sangat efisien.
                        <p>Mangrove dikenal sebagai penyimpan "karbon biru" (blue carbon), yaitu karbon yang tersimpan di ekosistem laut dan pesisir. Menurut <a href="JURNAL/kusuma.pdf" target="_blank">(Kusuma <em>et al.</em> 2024)</a> Mangrove memiliki kemampuan luar biasa untuk menyerap dan menyimpan karbon dioksida (CO₂) dari atmosfer melalui proses fotosintesis. Karbon ini tidak hanya tersimpan di batang dan daunnya, tetapi juga di tanah berlumpur di bawahnya.</p>
                    </div>
                    <video src="images/vata.webm" autoplay muted loop></video>
                </div>
            </div>
        </div>
        <div class="faq">
            <div class="question" onclick="toggleAnswer(this)">Apa itu Penginderaan Jauh?</div>
            <div class="answer">
                <div class="row">
                    <div class="text">
                        Penginderaan jauh, menurut beberapa sumber, merupakan metode ilmiah untuk memperoleh informasi tentang objek, wilayah, atau peristiwa tanpa kontak langsung, dengan menganalisis data dari sensor yang menangkap energi elektromagnetik <a href="JURNAL/husna.pdf" target="_blank">(Husna <em>et al.</em> 2023)</a>. Sensor ini mencakup kamera atau pemindai elektronik, dengan kemampuan menangkap objek dari jarak mulai 1 cm hingga lebih dari 1000 km <a href="https://shorturl.at/RMNNJ" target="_blank">(Amran, 2023)</a>.
                        <p>Resolusi spasial, yang menentukan tingkat detail objek dalam data, beragam mulai dari tinggi (0,6–4 meter) hingga rendah (30–1000 meter), di mana kualitas data meningkat dengan semakin kecil ukuran piksel (Selvia et al. 2024). Data spasial mempelajari hubungan ruang antara entitas geografis <a href="JURNAL/Iham.pdf" target="_blank">(Ilham <em>et al.</em> 2023)</a>.</p>
                    </div>
                    <video src="images/vata.webm" autoplay muted loop></video>
                </div>
            </div>
        </div>
        <div class="faq">
            <div class="question" onclick="toggleAnswer(this)">Apa itu Multi Citra?</div>
            <div class="answer">
                <div class="row">
                    <div class="text">
                        Multi Citra representasi digital dari suatu objek atau area yang ditangkap oleh sensor, baik itu dari satelit, pesawat terbang, atau drone. Berdasarkan penelitian Erkamim et al. (2023) Data ini umumnya berupa gambar atau foto, namun dalam konteks analisis lingkungan, seringkali berupa data multispektral atau hyperspektral yang menangkap informasi pada berbagai panjang gelombang cahaya. Contohnya Sentinel dan Landsat merupakan data citra yang dapat menghitung indeks vegetasi
                    </div>
                    <video src="images/vata.webm" autoplay muted loop></video>
                </div>
            </div>
        </div>
        <div class="faq">
            <div class="question" onclick="toggleAnswer(this)">Apa itu Google Earth Engine?</div>
            <div class="answer">
                <div class="row">
                    <div class="text">
                        Awal Desember 2010, Google meluncurkan teknologi inovatif bidang komputasi Cloud bernama Google Earth Engine (GEE). Teknologi ini memberikan akses pengguna menganalisis data geospasial skala besar dengan efisiensi tinggi (USGS, 2010). Google Earth Engine (GEE) menurut <a href="JURNAL/Novianti.pdf" target="_blank">(Novianti <em>et al.</em> 2024)</a> merupakan platform pemrosesan citra satelit yang menggunakan komputasi cloud. Platform analisis geospasial ini menyediakan data citra satelit yang bisa diakses secara online
dan gratis, sehingga pengguna dapat melakukan berbagai jenis analisis permukaan bumi secara real-time.
                    </div>
                    <video src="images/vata.webm" autoplay muted loop></video>
                </div>
            </div>
        </div>
        <div class="faq">
            <div class="question" onclick="toggleAnswer(this)">Mengapa Potensi Karbon Biru (Blue Carbon) ada pada Mangrove?</div>
            <div class="answer">
                <div class="row">
                    <div class="text">
                        Karbon biru (blue carbon) menurut <a href="JURNAL/kusuma.pdf" target="_blank">(Kusuma <em>et al.</em> 2024)</a> merujuk pada kapasitas ekosistem pesisir dan laut untuk menyerap CO2 dari atmosfer melalui proses fotosintesis, yang kemudian diubah menjadi biomassa dan diendapkan dalam sedimen. Karbon biru begitu penting karena kapasitasnya yang tinggi dalam menyerap dan menyimpan karbon dibandingkan ekosistem daratan <a href="JURNAL/alongi.pdf" target="_blank">(Alongi, 2023)</a>. Hutan mangrove, sebagai salah satu elemen utama dalam ekosistem karbon biru, memiliki kapasitas luar biasa untuk menyerap karbon melalui proses fotosintesis dan menyimpannya dalam bentuk biomassa serta sedimen <a href="JURNAL/hilmi.pdf" target="_blank">(Hilmi <em>et al.</em> 2021)</a>.
                    </div>
                    <video src="images/vata.webm" autoplay muted loop></video>
                </div>
            </div>
        </div>
        <div class="faq">
            <div class="question" onclick="toggleAnswer(this)">Apasih bedanya  Above Ground Carbon (AGC) dan Below Ground Carbon (BGC)?</div>
            <div class="answer">
                <div class="row">
                    <div class="text">
                        Karbon biru (blue carbon) menurut <a href="JURNAL/kusuma.pdf" target="_blank">(Kusuma <em>et al.</em> 2024)</a> merujuk pada kapasitas ekosistem pesisir dan laut untuk menyerap CO2 dari atmosfer melalui proses fotosintesis, yang kemudian diubah menjadi biomassa dan diendapkan dalam sedimen. karbon biru begitu penting karena kapasitasnya yang tinggi dalam menyerapdan menyimpan karbon dibandingkan ekosistem daratan <a href="JURNAL/alongi.pdf" target="_blank">(Alongi, 2023)</a>. Hutan mangrove, sebagai salah satu elemen utama dalam ekosistem karbon biru, memiliki kapasitas luar biasa untuk menyerap karbon melalui proses fotosintesis dan menyimpannya dalam bentuk biomassa serta sedimen <a href="JURNAL/hilmi.pdf" target="_blank">(Hilmi <em>et al.</em> 2021)</a>.                    
                    </div>
                    <video src="images/vata.webm" autoplay muted loop></video>
                </div>
            </div>
        </div>
        <div class="faq">
            <div class="question" onclick="toggleAnswer(this)">Mengapa Tampilan pada maps terdapat lapisan data?</div>
            <div class="answer">
                <div class="row">
                    <div class="text">
                        Karena setiap lapisan memiliki informasi yang berbeda. Terdiri dari Data Simpanan karbon, Luasan Mangrove dan Rencana Tata Ruang Wilayah (RTRW) pada suatu wilayah. Apabila data tersebut ditampilankan secara bersamaan maka visualisasinya akan tampak jelas.
                    </div>
                </div>
            </div>
        </div>
        <div class="faq">
            <div class="question" onclick="toggleAnswer(this)">Apa Satuan Hitungan Karbon?</div>
            <div class="answer">
                <div class="row">
                    <div class="text">
                        Satuan hitungan karbon yang umum digunakan adalah ton karbon dioksida ekuivalen (ton CO₂e). Satuan ini dipilih karena karbon dioksida (CO₂) merupakan gas rumah kaca utama yang berkontribusi pada pemanasan global.
                    </div>
                </div>
            </div>
        </div>
        <div class="faq">
            <div class="question" onclick="toggleAnswer(this)">Dapatkah mendownload data AGC per provinsi?</div>
            <div class="answer">
                <div class="row">
                    <div class="text">
                        Bisa
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <script>
        function toggleAnswer(element) {
            const answer = element.nextElementSibling;

            if (answer.classList.contains('open')) {
                answer.classList.remove('open');
            } else {
                document.querySelectorAll('.answer').forEach(el => el.classList.remove('open'));
                answer.classList.add('open');
            }
        }
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
