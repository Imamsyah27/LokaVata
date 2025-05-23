const MAP_SERVICE_KEY = "67b6a6056fb80e64559cc07e"; // Ganti dengan API key MapID Anda

var map = new maplibregl.Map({
    style: `https://basemap.mapid.io/styles/basic/style.json?key=${MAP_SERVICE_KEY}`,
    center: [118.5, -2.5],
    zoom: 5,
    pitch: 0,
    bearing: 0,
    container: "map",
});

const carbonPrice = 115000; // Harga karbon per Mg/ha dalam Rupiah

function createLayerControl() {
    const container = document.createElement("div");

    const layers = [
        { id: "AGCLayer", name: "AGC (Above Ground Carbon)" },
        { id: "RTRWLayer", name: "RTRW (Rencana Tata Ruang Wilayah)" },
    ];

    layers.forEach(layer => {
        const layerDiv = document.createElement("div");

        const checkbox = document.createElement("input");
        checkbox.type = "checkbox";
        checkbox.checked = true;
        checkbox.id = `checkbox-${layer.id}`;
        checkbox.dataset.layerId = layer.id;

        const label = document.createElement("label");
        label.htmlFor = `checkbox-${layer.id}`;
        label.textContent = ` ${layer.name}`;

        layerDiv.appendChild(checkbox);
        layerDiv.appendChild(label);
        container.appendChild(layerDiv);
        layerDiv.classList.add("layer-item");

        // Layer toggle
        checkbox.addEventListener("change", function () {
            const layerId = this.dataset.layerId;
            if (this.checked) {
                map.setLayoutProperty(layerId, "visibility", "visible");
            } else {
                map.setLayoutProperty(layerId, "visibility", "none");
            }
        });
    });

    const layerCheckboxesContainer = document.getElementById("layerCheckboxes");
    layerCheckboxesContainer.appendChild(container);
}

document.getElementById("geojsonUpload").addEventListener("change", function (e) {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function (event) {
        try {
            const geojson = JSON.parse(event.target.result);
            const customLayerId = "userGeoJSON";

            // Hapus jika layer sebelumnya ada
            if (map.getSource(customLayerId)) {
                map.removeLayer(customLayerId);
                map.removeSource(customLayerId);
            }

            map.addSource(customLayerId, {
                type: "geojson",
                data: geojson
            });

            map.addLayer({
                id: customLayerId,
                type: "fill",
                source: customLayerId,
                paint: {
                    "fill-color": "#ff9900",
                    "fill-opacity": 0.5,
                    "fill-outline-color": "#ff6600"
                }
            });

            // Auto zoom ke area
            const bounds = turf.bbox(geojson);
            map.fitBounds(bounds, { padding: 40 });

            // Tambahkan kontrol checkbox jika belum ada
            if (!document.getElementById("checkbox-userGeoJSON")) {
                const layerControl = document.getElementById("layerCheckboxes");
                const newItem = document.createElement("div");
                newItem.className = "layer-item";
                newItem.id = "layer-userGeoJSON"; // untuk hapus nanti

                const checkbox = document.createElement("input");
                checkbox.type = "checkbox";
                checkbox.id = "checkbox-userGeoJSON";
                checkbox.checked = true;

                const label = document.createElement("label");
                label.htmlFor = "checkbox-userGeoJSON";
                label.textContent = "User GeoJSON";

                newItem.appendChild(checkbox);
                newItem.appendChild(label);
                layerControl.appendChild(newItem);

                checkbox.addEventListener("change", function () {
                    map.setLayoutProperty(customLayerId, "visibility", this.checked ? "visible" : "none");
                });
            }

            // Tampilkan tombol hapus
            document.getElementById("deleteGeoJSON").style.display = "inline-block";

        } catch (err) {
            alert("File tidak valid! Pastikan itu adalah GeoJSON.");
            console.error("Error parsing GeoJSON:", err);
        }
    };
    reader.readAsText(file);
});

document.getElementById("deleteGeoJSON").addEventListener("click", function () {
    const customLayerId = "userGeoJSON";

    if (map.getLayer(customLayerId)) {
        map.removeLayer(customLayerId);
    }

    if (map.getSource(customLayerId)) {
        map.removeSource(customLayerId);
    }

    // Hapus checkbox dari layer control
    const checkboxDiv = document.getElementById("layer-userGeoJSON");
    if (checkboxDiv) {
        checkboxDiv.remove();
    }

    // Reset input
    document.getElementById("geojsonUpload").value = "";

    // Sembunyikan tombol hapus
    this.style.display = "none";
});


// Tambahkan kontrol navigasi
// Tambahkan kontrol navigasi di sudut kiri atas terlebih dahulu
const nav = new maplibregl.NavigationControl();
map.addControl(nav, 'top-left');
map.doubleClickZoom.disable();

// Gunakan CSS untuk memindahkannya ke tengah kiri
const navControl = document.querySelector('.maplibregl-ctrl-top-left');
if (navControl) {
    navControl.style.position = "absolute";
    navControl.style.top = "50%"; // Posisikan di tengah vertikal
    navControl.style.left = "10px"; // Jarak dari sisi kiri
    navControl.style.transform = "translateY(-50%)"; // Koreksi posisi agar benar-benar di tengah
}
        
// Tambahkan fitur drawing
const draw = new MapboxDraw({
    displayControlsDefault: true,
    controls: {
        polygon: true,
        trash: true
    }
});

map.addControl(draw, 'top-left');

// Tangani perubahan pada dropdown tahun
document.getElementById("yearSelect").addEventListener("change", function () {
    const locationSelect = document.getElementById("locationSelect");
    const selectedOption = locationSelect.value;

    if (selectedOption) {
        moveToLocation(); // Trigger ulang agar peta dan data diperbarui
    }
});

function showLoading() {
    console.log("Menampilkan loading...");
    document.getElementById("loadingOverlay").style.display = "flex";
}
function hideLoading() {
    console.log("Menyembunyikan loading...");
    document.getElementById("loadingOverlay").style.display = "none";
}


// Fungsi pindah lokasi
async function moveToLocation() {
    showLoading();

    const locationSelect = document.getElementById("locationSelect");
    const selectedOption = locationSelect.value;
    const yearSelect = document.getElementById("yearSelect");
    const selectedYear = yearSelect.value || "2024";

    if (selectedOption) {
        const coords = selectedOption.split(',');
        const lat = parseFloat(coords[0]);
        const lng = parseFloat(coords[1]);
        const zoom = parseInt(coords[2]);
        const regionKey = locationSelect.options[locationSelect.selectedIndex].getAttribute('data-region');

        map.flyTo({
            center: [lng, lat],
            zoom: zoom,
            essential: true
        });

        try {
            await updateGeoJSONLayers(regionKey, selectedYear);
        } catch (err) {
            console.error("Gagal update:", err);
        }

        // ⬅️ Pindahkan hideLoading() ke sini agar tunggu proses selesai
        setTimeout(() => {
            hideLoading();
        }, 1500); // biar tetap ada delay visual
    } else {
        hideLoading();
    }
}

// Fungsi untuk menampilkan GeoJSON berdasarkan lokasi yang dipilih
// Fungsi untuk melakukan kapitalisasi nama region
function capitalize(str) {
    return str
        .split('_')
        .map(s => s.charAt(0).toUpperCase() + s.slice(1).toLowerCase())
        .join('_');
}

// Fungsi untuk memperbarui GeoJSON layers
function updateGeoJSONLayers(regionKey, selectedYear) {
    // AGC berdasarkan tahun
    const mangroveUrl = `Data${selectedYear}/AGC1_${capitalize(regionKey)}.geojson`;

    // RTRW tetap (tidak berdasarkan tahun)
    const RTRWUrl = `RTRW/Polaruang_${capitalize(regionKey)}.geojson`;

    addAGCGeoJSON(mangroveUrl, 'AGCLayer');
    addRTRWGeoJSON(RTRWUrl, 'RTRWLayer');
    updateOverlayTable(mangroveUrl);
}



// Fungsi untuk menambahkan AGC GeoJSON ke peta
function addAGCGeoJSON(url, layerID) {
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (map.getSource(layerID)) {
                map.getSource(layerID).setData(data);
            } else {
                map.addSource(layerID, { type: 'geojson', data: data });
                map.addLayer({
                    id: layerID,
                    type: 'fill',
                    source: layerID,
                    paint: {
                        'fill-color': ["case", ["has", "AGC1"], ["interpolate", ["linear"], ["get", "AGC1"],
                            0, "#ffffbf",
                            10, "#fee08b",
                            30, "#d9ef8b",
                            60, "#a6d96a",
                            90, "#66bd63",
                            120, "#1a9850",
                            150, "#006837"
                        ], "#cccccc"],
                        'fill-outline-color': "white"
                    }
                });

                // Event klik untuk menampilkan data AGC dan valuasi ekonomi
                map.on('click', layerID, function (e) {
                    const agc = e.features[0].properties.AGC1 || 0;
                    const economyValue = agc * carbonPrice;
                    
                    new maplibregl.Popup()
                        .setLngLat(e.lngLat)
                        .setHTML(`<strong>AGC:</strong> ${agc} Mg<br><strong>Valuasi Ekonomi:</strong> Rp ${economyValue.toLocaleString('id-ID')}`)
                        .addTo(map);
                });

                // Mengubah kursor saat mengarahkan ke layer
                map.on('mouseenter', layerID, function () {
                    map.getCanvas().style.cursor = 'pointer'; // Kursor normal
                });

                map.on('mouseleave', layerID, function () {
                    map.getCanvas().style.cursor = ''; // Kembali ke default
                });
            }
        })
        .catch(error => console.error("Gagal memuat data AGC:", error));
}


// Fungsi untuk menangani intersect antara drawing dan AGC Layer
function calculateIntersectionAGC(drawnFeature) {
    const agcSource = map.getSource("AGCLayer");
    if (!agcSource) {
        console.error("AGC Layer tidak ditemukan.");
        return;
    }

    const agcData = agcSource._data;
    if (!agcData || agcData.type !== "FeatureCollection") {
        console.error("Data AGC tidak valid.");
        return;
    }

    let totalAGC = 0;
    let totalArea = 0;
    let foundIntersection = false;

    agcData.features.forEach(agcFeature => {
        if (!drawnFeature.geometry || !agcFeature.geometry) {
            console.warn("Geometry tidak valid.");
            return;
        }

                if (
            (drawnFeature.geometry.type === "Polygon" || drawnFeature.geometry.type === "MultiPolygon") &&
            (agcFeature.geometry.type === "Polygon" || agcFeature.geometry.type === "MultiPolygon")
        ) {
        // Membuat Data polygon
        const poly1 = turf.polygon(drawnFeature.geometry.coordinates)
        const poly2 = turf.polygon(agcFeature.geometry.coordinates);
        const polygon = turf.featureCollection([poly1, poly2])

            const intersection = turf.intersect(polygon);
            if (intersection) {
                foundIntersection = true;
                const agcValue = agcFeature.properties?.AGC1 || 0;
                const intersectArea = turf.area(intersection) / 10000; // Konversi ke hektar

                totalAGC += agcValue * intersectArea;
                totalArea += intersectArea;
            }
        }
    });

    if (!foundIntersection) {
        alert("Tidak ada area yang berpotongan dengan AGC.");
        return;
    }

    if (totalArea > 0) {
        const avgAGC = totalAGC / totalArea;
        const totalEconomicValue = avgAGC * carbonPrice;
        // Mengambil center dalam sebuah polygon
        let centroid = turf.centroid(drawnFeature.geometry);
        centroid = centroid.geometry.coordinates;

        new maplibregl.Popup()
            .setLngLat(centroid) // Gunakan koordinat pertama
            .setHTML(`
                <strong>Hasil Intersect:</strong><br>
                <strong>Rata-rata AGC:</strong> ${avgAGC.toFixed(2)} Mg<br>
                <strong>Valuasi Ekonomi:</strong> Rp ${totalEconomicValue.toLocaleString('id-ID')}<br>
                <strong>Total Luasan:</strong> ${totalArea.toFixed(2)} ha
            `)
            .addTo(map);
    }
}

// Tangani event setelah menggambar polygon
map.on('draw.create', function (e) {
    const drawnFeature = e.features[0];

    // Validasi jika hasil gambar bukan Polygon
    if (!drawnFeature || !drawnFeature.geometry || (drawnFeature.geometry.type !== "Polygon" && drawnFeature.geometry.type !== "MultiPolygon")) {
        alert("Harap gambar area dalam bentuk Polygon.");
        return;
    }

    calculateIntersectionAGC(drawnFeature);
});

map.on('click', function (e) {
    const features = map.queryRenderedFeatures(e.point, {
      layers: ['gl-draw-polygon-fill-inactive.cold'] // Layer ID default draw polygon
    });
  
    if (features.length > 0) {
      const drawnFeature = features[0];
      calculateIntersectionAGC(drawnFeature);
    }
  });  

function addRTRWGeoJSON(url, layerID) {
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (map.getSource(layerID)) {
                map.getSource(layerID).setData(data);
            } else {
                map.addSource(layerID, { type: 'geojson', data: data });
                map.addLayer({
                    id: layerID,
                    type: 'fill',
                    source: layerID,
                    paint: {
                        'fill-color': [
                            "match", ["get", "Kawasan"],
                            "Kawasan Perlindungan Terhadap Kawasan Bawahannya", "#ff0000",
                            "Kawasan Pencadangan Konservasi di Laut", "#ff6600",
                            "Kawasan Konservasi", "#008000",
                            "Kawasan Ekosistem Mangrove", "#006600",
                            "Kawasan Perlindungan Setempat", "#00ccff",
                            "Badan Air", "#0000ff",
                            "Kawasan Hutan Produksi", "#8B4513",
                            "Kawasan Peruntukan Industri", "#800080",
                            "Kawasan Transportasi", "#FFA500",
                            "Kawasan HGU dan HGB", "#FFD700",
                            "Kawasan Hutan Lindung", "#A3FF73",
                            "Garis Pantai", "#000000",
                            "Kawasan Pertanian dan Perkebunan"," #55FF00",
                            "Kawasan Pemukiman", " #FF5500",
                            "Kawasan Rawan Gunung Berapi", " #A80000",
                            "#CCCCCC" // Warna default
                        ],
                        'fill-opacity': 0.5,
                        'fill-outline-color': "#000000"
                    }
                });

                map.on('click', layerID, function (e) {
                    const kawasan = e.features[0].properties.Kawasan || "Tidak Diketahui";
                    new maplibregl.Popup()
                        .setLngLat(e.lngLat)
                        .setHTML(`<strong>Jenis Kawasan:</strong> ${kawasan}`)
                        .addTo(map);
                    });

                    // Mengubah kursor saat mengarahkan ke layer
                    map.on('mouseenter', layerID, function () {
                        map.getCanvas().style.cursor = 'pointer'; // Kursor normal
                    });
    
                    map.on('mouseleave', layerID, function () {
                        map.getCanvas().style.cursor = ''; // Kembali ke default
                    });
                }
            })
        .catch(error => console.error("Gagal memuat data RTRW:", error));
}

map.on('idle', () => {
    const layers = map.getStyle().layers;
    const agcLayerIndex = layers.findIndex(l => l.id === 'AGCLayer');
    if (agcLayerIndex === -1) return;
  
    const drawLayerIds = layers
      .map(l => l.id)
      .filter(id => id.startsWith('gl-draw'));
  
    drawLayerIds.forEach(drawId => {
      if (map.getLayer(drawId)) {
        map.moveLayer(drawId);
      }
    });
  });
  
function updateOverlayTable(geojsonUrl) {
    const tableBody = document.getElementById("overlayTableBody");
    tableBody.innerHTML = ""; // Kosongkan tabel sebelumnya

    let totalAGC = 0;
    let totalEconomicValue = 0;
    let totalArea = 0;
    const carbonPrice = 115000;

    fetch(geojsonUrl)
        .then(response => response.json())
        .then(data => {
            data.features.forEach(feature => {
                const agcValue = feature.properties?.AGC1 || 0;

                // Hitung luas menggunakan Turf.js
                const featureArea = turf.area(feature) / 10000; // Konversi ke hektar (ha)
                
                totalAGC += agcValue;
                totalArea += featureArea;
                totalEconomicValue += agcValue * carbonPrice;
            });

            // Ambil nama wilayah yang dipilih
            const locationSelect = document.getElementById("locationSelect");
            const selectedRegionName = locationSelect.options[locationSelect.selectedIndex].text;

                

            // Tambahkan baris ringkasan ke tabel
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${selectedRegionName}</td>
                <td>Total Data</td>
                <td>${totalAGC.toLocaleString('id-ID')} Mg</td>
                <td>Rp ${totalEconomicValue.toLocaleString('id-ID')}</td>
                <td>${totalArea.toFixed(2).toLocaleString('id-ID')} ha</td>

            `;
            tableBody.appendChild(row);

            // Tambahkan event listener untuk tombol download CSV
            row.querySelector(".download-btn.csv").addEventListener("click", downloadAGCDataAsCSV);

            // Tambahkan event listener untuk tombol download GeoJSON
            row.querySelector(".download-btn.geojson").addEventListener("click", function () {
                downloadAsGeoJSON(geojsonUrl);
            });
        })
        .catch(error => console.error(`Error loading GeoJSON data for table:`, error));
}

// Fungsi untuk mengunduh data dalam format CSV
function downloadAGCDataAsCSV() {
    let csvContent = "data:text/csv;charset=utf-8,";
    csvContent += "Keterangan,Total AGC (Mg),Valuasi Ekonomi (Rp),Total Luasan (ha),Tanggal\n";

    const row = document.querySelector("#overlayTableBody tr");
    if (row) {
        let rowData = [];
        row.querySelectorAll("td:not(:last-child)").forEach(td => rowData.push(td.innerText)); // Hindari kolom tombol
        csvContent += rowData.join(",") + "\n";
    }

    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "AGC_Data.csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Fungsi untuk mengunduh GeoJSON secara langsung
function downloadAsGeoJSON(geojsonUrl) {
    fetch(geojsonUrl)
        .then(response => response.json())
        .then(geojson => {
            const geojsonString = JSON.stringify(geojson, null, 2); // Format JSON agar lebih rapi
            const blob = new Blob([geojsonString], { type: "application/json" });

            const link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "AGC1_Data.geojson";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        })
        .catch(error => console.error("Error downloading GeoJSON:", error));
}

function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

// ===== TAB SWITCHER =====
document.addEventListener('DOMContentLoaded', function () {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabPanes = document.querySelectorAll('.tab-pane');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const target = button.getAttribute('data-tab');

            // Nonaktifkan semua tab & konten
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanes.forEach(pane => pane.classList.remove('active'));

            // Aktifkan tab yang diklik
            button.classList.add('active');
            document.getElementById(target).classList.add('active');
        });
    });
});
document.addEventListener("DOMContentLoaded", function () {
    // Inisialisasi Select2 untuk lokasi wilayah
    $('#locationSelect').select2({
        dropdownParent: $('#data') // Sesuai ID kontainer tab pane
    });

    createLayerControl();

    document.querySelector('.download-btn.csv').addEventListener('click', downloadAGCDataAsCSV);

    document.querySelector('.download-btn.geojson').addEventListener('click', function () {
        const locationSelect = document.getElementById("locationSelect");
        const regionKey = locationSelect.options[locationSelect.selectedIndex].getAttribute('data-region');

        if (!regionKey) {
            alert("Silakan pilih wilayah terlebih dahulu.");
            return;
        }

        const geojsonUrl = `Data/AGC1_${capitalize(regionKey)}.geojson`;
        downloadAsGeoJSON(geojsonUrl);
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('toggleDropdownBtn');
    const dropdownContainer = document.querySelector('.custom-dropdown-container');

    function togglePanel() {
        dropdownContainer.classList.toggle('open');
        toggleBtn.classList.toggle('panel-open');
    }

    // Saat tombol diklik
    toggleBtn.addEventListener('click', togglePanel);

    // ✅ Buka panel otomatis saat halaman dimuat
    dropdownContainer.classList.add('open');
    toggleBtn.classList.add('panel-open');
});

document.getElementById("locationSelect").addEventListener("change", function () {
    const selectedOption = this.value;
    if (selectedOption) {
        moveToLocation();
    }
});
